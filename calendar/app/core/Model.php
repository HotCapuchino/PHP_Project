<?php

namespace app\core;
use app\core\DatabaseOperator;
use app\core\Session;

class Model {

    protected $db_operator = null;
    protected $session = null;

    public function __construct() {
        $this->connectToDB();
        $this->session = new Session();
    }

    public function isLoggedIn() {
        return $this->session->isLogged();
    }

    public function saveData($key, $value) {
        if (!$this->session->store($key, $value)) {
            echo 'Oops! An error occurred!';
            return false;
        };
        return true;
    }

    public function getData($key) {
        return $this->session->get($key);
    }

    public function eraseData($key) {
        if (!$this->session->erase($key)) {
            echo 'Oops! Error occurred!';
        };
    }

    private function connectToDB() {
        $this->db_operator = new DatabaseOperator();
        if (!$this->db_operator) {
            echo 'Unable to connect database!';
        } else {
            // echo 'Connection established!';
        }
    }
}