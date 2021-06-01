<?php 

namespace app\controllers;
use app\core\Controller;

class RegisterController extends Controller {

    protected $error_fields = [];
    protected $error_message = null;
    protected $user_values = [];

    public function displayRegisterPageAction() {
        if ($this->model->isLoggedIn()) {
            $this->redirect('calendar');
        }
        $register_payload = $this->model->getData('register_payload');
        if ($register_payload) {
            $this->error_fields = $this->model->getData('error_fields');
            $this->error_message = $this->model->getData('error_message');
            $this->user_values = $this->model->getData('user_values');
        }
        print_r($register_payload);
        $this->model->eraseData('register_payload');
        $this->view->displayTemplate();
        $this->view->displayContent([
            'error_fields' => $this->error_fields,
            'error_message' => $this->error_message,
            'user_values' => $this->user_values
        ]);
    }

    public function signUpAction() {
        $user_values = $_POST;
        $result = $this->model->registerUser($user_values);
        var_dump($result);
        if ($result === true) {
            $this->redirect('calendar');
        } else {
            extract($result);
            $register_payload = [
                'error_fields' => $error_fields,
                'error_message' => $error_message,
                'user_values' => $user_values
            ];
            print_r($register_payload);
            $this->model->saveData('register_payload', $register_payload);
            $this->getBack();
        }
    }
}