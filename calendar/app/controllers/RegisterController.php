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
            $this->model->saveData('register_payload', $register_payload);
            $this->getBack();
        }
    }
}