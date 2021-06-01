<?php

namespace app\core;
use Exception;

class Session {

    public function start() {
        session_start();
        $_SESSION['isLogged'] = true;
    }

    public function isLogged() {
        session_start();
        if (isset($_SESSION['isLogged'])) {
            return $_SESSION['isLogged'];
        } 
        return false;
    }

    public function store($key, $value) {
        try {
            session_start();
            $_SESSION[$key] = $value;
            return true;
        } catch (Exception $exp) {
            return false;
        }
    } 

    public function get($key) {
        session_start();
        return $_SESSION[$key];
    }

    public function erase($key) {
        session_start();
        unset($_SESSION[$key]);
        if (!$_SESSION[$key]) {
            return true;
        }
        return false;
    }

    public function end() {
        session_start();
        $_SESSION['isLogged'] = false;
    }
}