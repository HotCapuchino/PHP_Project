<?php 

namespace app\controllers;
use app\core\Controller;
use DateInterval;
use DateTime;

class CalendarController extends Controller {

    protected $tasks_types = [];
    protected $tasks_statuses = [];
    protected $tasks_durations = [];
    protected $tasks = [];

    function displayCalendarAction() {
        if (!$this->model->isLoggedIn()) {
            $this->redirect('');
        }
        $this->tasks_types = $this->model->getTasksTypes();
        $this->tasks_statuses = $this->model->getTasksStatuses();
        $this->tasks_durations = $this->model->getTasksDurations();
        $this->tasks = $this->model->getTasks($this->model->getData('user_id'));
        $this->view->displayTemplate();
        $this->view->displayContent([
            'tasks_types' => $this->tasks_types,
            'tasks_statuses' => $this->tasks_statuses,
            'tasks_durations' => $this->tasks_durations,
            'tasks' => $this->tasks
        ]);
    }

    function addTaskAction() {
        $task_fields = ['topic', 'type_id', 'location', 'datetime', 'duration_id', 'comment'];
        $task_params = array($this->model->getData('user_id'));
        $task_status = $this->calculateTaskStatus($_POST['date'] . ' ' . $_POST['time']);
        $task_params[] = $task_status;
        foreach ($task_fields as $field) {
            if ($field == 'datetime') {
                $task_params[] = $_POST['date'] . ' ' . $_POST['time'];
                continue;
            }
            if ($_POST[$field]) {
                $task_params[] = $_POST[$field];
            }
        } 
        if (!$_POST['comment']) {
            $task_params[] = '';
        }
        if (sizeof($task_params) != 8) {
            echo 'something went wrong!';
            $this->getBack();
        }
        $result = $this->model->addTask($task_params);
        if ($result === true) {
            $this->redirect('calendar');
        } else {
            $this->getBack();
        }
    }

    function deleteTaskAction() {
        print_r($_POST);
        $result = $this->model->deleteTask($_POST['id']);
        if ($result === true) {
            $this->redirect('calendar');
        } else {
            $this->getBack();
        }
    }

    function editTaskAction() {
        $updating_values = [];
        $task_id = null;
        $updating_values['time'] = $_POST['date'] . ' ' . $_POST['time'];
        $updating_values['status_id'] = $this->calculateTaskStatus($updating_values['time']);
        foreach ($_POST as $key => $value) {
            if ($key == 'id') {
                $task_id = $value;
                continue;
            }
            if ($key == 'date' || $key == 'time') {
                continue;
            }
            $updating_values[$key] = $value;
        }
        $result = $this->model->updateTask($task_id, $updating_values);
        var_dump($result);
        if ($result === true) {
            $this->redirect('calendar');
        } else {
            $this->getBack();
        }
    }

    function changeTaskStatusAction() {
        if (!sizeof($this->tasks_statuses)) {
            $this->tasks_statuses = $this->model->getTasksStatuses();
        }
        $new_date = null;
        foreach ($this->tasks_statuses as $status) {
            if ($_POST['status_id'] == $status[0]) {
                if (strtolower($status[1]) == 'expired' || strtolower($status[1]) == 'completed') {
                    $new_date = date_format(date_sub(new DateTime(), new DateInterval('P1D')), 'Y-m-d H:i');
                } else if (strtolower($status[1]) == 'in progress') {
                    $new_date = date_format(date_add(new DateTime(), new DateInterval('P1D')), 'Y-m-d H:i');
                }
            } 
        }
        $params = array('status_id' => $_POST['status_id']);
        if ($new_date) {
            $params['time'] = $new_date;
        }
        $result = $this->model->updateTask($_POST['id'], $params);
        if ($result === true) {
            $this->redirect('calendar');
        } else {
            $this->getBack();
        }
    }

    // this is the function that calculates task status according to user's data
    // for example, if user sent the date, that has been already passed task_status will be
    // automatically marked as 'expired'
    private function calculateTaskStatus($user_date) {
        if (!sizeof($this->tasks_statuses)) {
            $this->tasks_statuses = $this->model->getTasksStatuses();
        }
        $task_status = null;
        if (time() > strtotime($user_date)) {
            foreach ($this->tasks_statuses as $value) {
                if (strtolower($value[1]) === 'expired') {
                    $task_status = $value[0];
                }
            }
        } else {
            foreach ($this->tasks_statuses as $value) {
                if (strtolower($value[1]) === 'in progress') {
                    $task_status = $value[0];
                }
            }
        }
        return $task_status;
    }

}