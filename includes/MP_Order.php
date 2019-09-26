<?php

defined('ABSPATH') || exit;

if (!class_exists('MP_Order')) :

    class MP_Order extends MP_Abstract_Order
    {

        public function get_data()
        {
            $this->data['total'] = get_post_meta($this->get_id(), '_order_total', true);

            $address = 'billing';

            foreach ($this->data[$address] as $prop => $value) {
                $this->data[$address][$prop] = get_post_meta($this->get_id(), '_' . $address . '_' . $prop, true);
            }
        }

        public function save(MP_Cart $cart = null)
        {
            try {
                if ($this->get_id()) {

                    $this->update();

                } else {
                    $this->create();

                    $this->set_order_items($cart->get_cart());
                }


            } catch (Exception $exception) {
                error_log($exception);
            }

            return $this->get_id();
        }


        /**
         * Create order
         */
        private function create()
        {
            $id = wp_insert_post(
                array(
                    'post_date' => gmdate('Y-m-d H:i:s'),
                    'post_date_gmt' => gmdate('Y-m-d H:i:s'),
                    'post_type' => 'mp-order',
                    'post_status' => 'mp-pending',
                    'post_author' => 1,
                    'post_title' => $this->get_post_title()
                ),
                true
            );

            if ($id > 0) {
                $this->set_id($id);
                $this->update_or_delete_post_meta();
            }
        }

        private function update()
        {
            $this->update_or_delete_post_meta();
        }

        private function update_or_delete_post_meta()
        {
            $meta_key_to_props = $this->meta_key_to_props;

            $data = $this->get_props_to_update($meta_key_to_props);

            foreach ($data as $meta_key => $prop) {
                if (isset($prop) && $prop != '') {
                    $value = $prop;

                    update_post_meta($this->get_id(), $meta_key, $value);
                }
            }
        }

        private function get_props_to_update($meta_key_to_props)
        {
            $props_to_update = array();

            $changes = $this->get_changes();

            $fields_prefix = $this->fields_prefix;

            foreach ($meta_key_to_props as $meta_key => $prop) {
                if (isset($changes[$prop])) {
                    $props_to_update[$meta_key] = $changes[$prop];
                }
            }

            foreach ($changes as $key => $meta_value) {

                if (isset($fields_prefix[current(explode('_', $key))])) {

                    foreach ($meta_value as $end_key => $value) {
                        $meta_key = '_' . $key . '_' . $end_key;

                        if (isset($value)) {
                            $props_to_update[$meta_key] = $value;

                            $this->data[$key][$end_key] = $value;
                        }
                    }
                } else {
                    if ($meta_value) {
                        $this->data[$key] = $meta_value;
                    }
                }
            }

            return $props_to_update;
        }

        private function get_post_title()
        {
            return '# ' . gmdate('Y-m-d H:i:s');
        }

        public function set_order_title()
        {

            $updatePost = array(
                'ID' => $this->get_id(), // wordpress Id
                'post_title' => '#' . $this->get_id() . ' ' . $this->get_billing_full_name(), // Updated title
            );

            wp_update_post($updatePost);
        }

        public function set_billing_first_name($value)
        {
            $this->set_address_prop('first_name', 'billing', $value);
        }

        public function get_billing_first_name()
        {
            return $this->get_address_prop('first_name', 'billing');
        }

        public function set_billing_last_name($value)
        {
            $this->set_address_prop('last_name', 'billing', $value);
        }

        public function get_billing_last_name()
        {
            return $this->get_address_prop('last_name', 'billing');
        }

        public function get_billing_full_name()
        {
            return $this->get_billing_first_name() . ' ' . $this->get_billing_last_name();
        }

        public function set_billing_address($value)
        {
            $this->set_address_prop('address', 'billing', $value);
        }

        public function get_billing_address()
        {
            return $this->get_address_prop('address', 'billing');
        }

        public function set_billing_postcode($value)
        {
            $this->set_address_prop('postcode', 'billing', $value);
        }

        public function get_billing_postcode()
        {
            return $this->get_address_prop('postcode', 'billing');
        }

        public function set_billing_phone($value)
        {
            $this->set_address_prop('phone', 'billing', $value);
        }

        public function get_billing_phone()
        {
            return $this->get_address_prop('phone', 'billing');
        }

        public function set_billing_email($value)
        {
            $this->set_address_prop('email', 'billing', $value);
        }

        public function get_billing_email()
        {
            return $this->get_address_prop('email', 'billing');
        }

        public function set_billing_order_comments($value)
        {
            $this->set_address_prop('order_comments', 'billing', $value);
        }

        public function get_billing_order_comments()
        {
            return $this->get_address_prop('order_comments', 'billing');
        }

        private function set_address_prop($prop, $address = 'billing', $value)
        {
            if (array_key_exists($prop, $this->data[$address])) {
                if ($value !== $this->data[$address][$prop]) {
                    $this->changes[$address][$prop] = $value;
                }
            }
        }

        private function get_address_prop($prop, $address = 'billing')
        {
            if (array_key_exists($prop, $this->data[$address])) {
                return $this->data[$address][$prop];
            }
        }
    }

endif;
