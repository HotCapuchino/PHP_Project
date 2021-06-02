<?php 

namespace app\models;
use app\core\Model;
use Exception;

class AuthModel extends Model {

    public function loginUser($login, $password) {
        $errors = $this->validateFields($login, $password);
        if (!sizeof($errors['error_fields'])) {
            $sql = "SELECT id, `password` FROM `users` WHERE `login`=? LIMIT 1;";
            $params = array($login);
            $result = $this->db_operator->executeQuery($sql, $params)[0];
            if ($result[1] === md5($password)) {
                $this->session->start();
                $this->saveData('user_id', $result[0]);
                return true;
            } else {
                $errors['error_message'] = 'Wrong login or password!';
                return $errors;
            } 
        } else {
            return $errors;
        }
    }

    private function validateFields($login, $password) {
        $error_fields = [];
        $error_message = null;
        if (preg_match('/[^\w]/', $login)) {
            $error_fields['login'] = 'This field cannot contain special characters!';
        }
        if (strlen($password) < 5) {
            $error_fields['password'] = 'Too short!';
        }
        if (!$login) {
            $error_fields['login'] = 'This field cannot be empty!';
        } 
        if (!$password) {
            $error_fields['password'] = 'This field cannot be empty!';
        }
        return [
            'error_fields' => $error_fields,
            'error_message' => $error_message
        ];
    }

    public function logout() {
        return $this->session->end();
    }
}