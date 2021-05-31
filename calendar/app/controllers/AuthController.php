<?php 

namespace app\controllers;
use app\core\Controller;

class AuthController extends Controller {

    protected $error_fields = [];
    protected $error_message = null;
    protected $user_values = [];

    public function displayAuthPageAction() {
        if ($this->model->isLoggedIn()) {
            $this->redirect('calendar');
        }
        $login_payload = $this->model->getData('login_payload');
        if ($login_payload) {
            $this->error_fields = $login_payload['error_fields'];
            $this->error_message = $login_payload['error_message'];
            $this->user_values = $login_payload['user_values'];
        }
        $this->view->displayTemplate();
        $this->view->displayContent([
            'error_fields' => $this->error_fields,
            'error_message' => $this->error_message,
            'user_values' => $this->user_values
        ]);
    }

    public function loginAction() {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $user_values = $_POST;
        $result = $this->model->loginUser($login, $password);
        if ($result === true) {
            $this->redirect('calendar');
        } else {
            extract($result);
            $login_payload = [
                'error_fields' => $error_fields,
                'error_message' => $error_message,
                'user_values' => $user_values
            ];
            $this->model->saveData('login_payload', $login_payload);
            $this->model->getData('login_payload');
            $this->getBack();
        }
    }

    public function logoutAction() {
        if ($this->model->logout()) {
            $this->redirect('/');
        } else {
            $this->model->saveData('logout_error', true);
            $this->getBack();
        }
    }

}