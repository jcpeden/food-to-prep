<?php

defined('ABSPATH') || exit;

if (!class_exists('MP_Payment_Gateways')) :

    class MP_Payment_Gateways
    {
        public $payment_gateways = null;

        protected static $_instance = null;

        public static function instance()
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function get_available_payment_gateways()
        {
            if (!isset($this->payment_gateways)) {

                $checkout_methods = array();

                $checkout_methods = apply_filters('meal_prep_checkout_methods', $checkout_methods);

                $payment_gateways = array();

                foreach ($checkout_methods as $gateway) {
                    if (is_string($gateway) && class_exists($gateway)) {
                        $gateway = new $gateway();
                    }

                    // Gateways need to be valid and extend WC_Payment_Gateway.
                    if (!is_a($gateway, 'MP_Payment_Gateway')) {
                        continue;
                    }

                    $payment_gateways[$gateway->id] = $gateway;
                }

                $this->payment_gateways = $payment_gateways;
            }

            return $this->payment_gateways;
        }
    }

endif;
