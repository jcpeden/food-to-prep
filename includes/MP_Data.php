<?php

defined('ABSPATH') || exit;

if (!class_exists('MP_Data')) :

    /**
     * Class MP_Data
     */
    abstract class MP_Data
    {
        protected $id;

        protected $data;
        protected $changes = array();

        protected function get_prop($prop)
        {
            $value = null;

            if (array_key_exists($prop, $this->data)) {
                $value = $this->data[$prop];
            }

            return $value;
        }

        protected function set_prop($prop, $value)
        {
            if (array_key_exists($prop, $this->data)) {
                $this->data[$prop] = $value;
                $this->changes[$prop] = $value;
            }
        }

        public function get_changes()
        {
            return $this->changes;
        }

        public function set_id($id)
        {
            $this->id = $id;
        }

        public function get_id()
        {
            return $this->id;
        }
    }

endif;
