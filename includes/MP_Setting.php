<?php

defined('ABSPATH') || exit;

if (!class_exists('MP_Setting')) :

    class MP_Setting
    {
        private $settings_default = array(
            'currency' => 'USD'
        );

        private $currency = array(
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£'
        );

        private $settings;

        public function __construct()
        {

            $this->settings = array();

            foreach ($this->settings_default as $key => $default) {
                $value = MTP_OSA()->get_option($key, 'meal_prep_other');

                $this->settings[$key] = $value;
            }
        }

        public function get_settings()
        {
            return isset($this->settings) ? $this->settings : $this->settings_default;
        }

        public function get_currency_symbol()
        {
            $current_setting = isset($this->settings['currency']) ? $this->settings['currency'] : $this->settings_default['currency'];

            return $this->currency[$current_setting];
        }

        public function get_current_currency()
        {
            $current_setting = isset($this->settings['currency']) ? $this->settings['currency'] : $this->settings_default['currency'];

            return $current_setting;
        }
    }

endif;