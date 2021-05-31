<?php 

namespace app\models;
use app\core\Model;
use Exception;

class CalendarModel extends Model {

    public function getTasks($user_id) {
        $sql = "SELECT * FROM `tasks` WHERE user_id=?;";
        $params = array($user_id);
        return $this->db_operator->executeQuery($sql, $params);
    }

    public function getTasksTypes() {
        $sql = "SELECT * FROM `tasks_types` ORDER BY id ASC;";
        return $this->db_operator->executeQuery($sql); 
    }

    public function getTasksStatuses() {
        $sql = "SELECT * FROM `tasks_statuses` ORDER BY id ASC;";
        return $this->db_operator->executeQuery($sql);
    }

    public function getTasksDurations() {
        $sql = "SELECT * FROM `tasks_durations` ORDER BY seconds ASC;";
        return $this->db_operator->executeQuery($sql);
    }

    public function addTask($task_params) {
        $sql = "INSERT INTO `tasks`(user_id, status_id, topic, type_id, location, time, duration_id, comment)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
        return $this->db_operator->executeQuery($sql, $task_params);
    }

    public function deleteTask($task_id) {
        $sql = "DELETE FROM `tasks` WHERE id=? LIMIT 1;";
        $params = array($task_id);
        return $this->db_operator->executeQuery($sql, $params);
    }

    public function updateTask($task_id, $task_params) {
        $update_string = '';
        $params[':id'] = $task_id;
        $index = 0;
        foreach ($task_params as $key => $param) {
            $update_string .= "$key=:$key";
            if ($index == sizeof($task_params) - 1) {
                $update_string .= ' ';
            } else {
                $update_string .= ', ';
            }
            $params[":$key"] = $param;
            $index++;
        }
        $sql = "UPDATE `tasks` SET " . $update_string . "WHERE id=:id LIMIT 1;";
        echo $sql;
        return $this->db_operator->executeQuery($sql, $params);
    }
}