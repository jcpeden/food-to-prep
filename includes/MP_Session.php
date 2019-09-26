<?php

defined('ABSPATH') || exit;

if (!class_exists('MP_Session')) :


    class MP_Session
    {
        function init()
        {
            session_start();
        }

        public function get_session($key, $default = null)
        {
            return isset($_SESSION[$key]) ? json_decode($_SESSION[$key]) : $default;

        }

        public function set_session($key, $value)
        {
            return $_SESSION[$key] = json_encode($value);
        }
    }

endif;