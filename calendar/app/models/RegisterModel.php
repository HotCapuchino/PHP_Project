<?php 

namespace app\models;
use app\core\Model;
use Exception;

class RegisterModel extends Model {

    public function registerUser($fields) {
        $errors = $this->validateFields($fields);
        if (!sizeof($errors['error_fields'])) {
            $check_sql = "SELECT * FROM `users` WHERE `login`=?;";
            $check_params = array($fields['login']);
            $result = $this->db_operator->executeQuery($check_sql, $check_params);
            if (sizeof($result)) {
                $errors['error_message'] = 'User with such login already exists!';
                return $errors;
            }
            $register_sql = "INSERT INTO `users`(`name`, `login`, `password`) VALUES(?, ?, ?);";
            $register_params = array($fields['name'], $fields['login'], md5($fields['password']));
            $result = $this->db_operator->executeQuery($register_sql, $register_params);
            if ($result) {
                $this->session->start();
                $user_id = $this->getRegisteredID();
                $user_id = $this->getRegisteredID();
                $this->saveData('user_id', $user_id[0][0]);
                return true;
            } else {
                $errors['error_message'] = 'Something went wrong while registering you...';
                return $errors;
            }
        } else {
            return $errors;
        }
    }

    private function getRegisteredID() {
        $sql = "SELECT id FROM `users` ORDER BY id DESC LIMIT 1;";
        return $this->db_operator->executeQuery($sql);
    }

    private function validateFields($fields) {
        $error_fields = [];
        $error_message = null;
        foreach ($fields as $key => $value) {
            echo $key . '<br>';
            if (!$value) {
                $error_fields[$key] = 'This field cannot be empty!';
                continue;
            }
            switch($key) {
                case 'name': {
                    if (preg_match('/[\d]|[^\w]/', $value)) {
                        $error_fields[$key] = 'This field cannot contain digits or special characters!';
                    }
                }
                break;
                case 'login': {
                    if (preg_match('/[^\w]/', $value)) {
                        $error_fields[$key] = 'This field cannot contain special characters!';
                    }
                }
                break;
                case 'password': {
                    if (strlen($value) < 5) {
                        $error_fields[$key] = 'Too short!';
                    }
                }
                break;
                case 'repeated_password': { 
                    if ($value != $fields['password']) {
                        $error_fields[$key] = 'Passwords don\'t match!';
                    }
                }
                break;
            }
        }
        return [
            'error_fields' => $error_fields,
            'error_message' => $error_message
        ];
    }

}