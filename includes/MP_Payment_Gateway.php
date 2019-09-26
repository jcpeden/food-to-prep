<?php

defined('ABSPATH') || exit;

if (!class_exists('MP_Payment_Gateway')) :

    abstract class MP_Payment_Gateway
    {
        public $id;
        public $method_title;
        public $description;


        public function add_checkout_method($args)
        {
            array_push($args, get_class($this));

            return $args;
        }

        protected function process_payment($order_id)
        {
            return array();
        }
    }

endif;