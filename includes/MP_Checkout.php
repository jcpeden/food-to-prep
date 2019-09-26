<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'MP_Checkout' ) ) :

    class MP_Checkout
    {
        private static $instance;

        public static function get_instance()
        {
            if (null == self::$instance) {
                self::$instance = new MP_Checkout();
            }
            return self::$instance;
        }


        /**
         *  Process the checkout after the confirm order.
         */
        public function process_checkout()
        {
            if (FTP()->cart->is_empty()) {
                wp_die();
            }

            $posted_data = sanitize_text_field($_POST);


            $errors      = new WP_Error();

            $this->validate_checkout($posted_data, $errors);

            $list_messages = array();

            foreach ( $errors->get_error_messages('') as $message ) {
                array_push($list_messages, $message);
            }

            if ( 0 === sizeof($list_messages) ) {
                $order_id = $this->create_order($posted_data);
                $response = $this->process_order_payment( $order_id, $posted_data['payment_method'] );

                $response['refresh'] = true;
                $response['reload'] = true;

                wp_send_json( $response );
            }else{

                $response = array(
                    'result'   => 'failure',
                    'messages' => isset( $list_messages ) ? implode(' ', $list_messages) : '',
                    'refresh'  => true,
                    'reload'   => false
                );

                wp_send_json( $response );
            }
        }

        public function create_order(array $data)
        {

            try {
                $order = new MP_Order();


                foreach ( $data as $key => $value ) {
                    if ( is_callable( array( $order, "set_{$key}" ) ) ) {
                        $order->{"set_{$key}"}( $value );
                    }
                }

                $order->set_total(FTP()->cart->get_cart_total());
                $order->set_payment_method($data['payment_method']);

                $order_id = $order->save(FTP()->cart);

                $order->set_order_title();

                food_to_prep_create_order_billing_email($order_id);

                FTP()->cart->clear_all_cart_item();
                return $order_id;
            } catch (Exception $e) {
                return new WP_Error('checkout-error', $e->getMessage());
            }
        }

        public function process_order_payment($order_id, $payment_method){
            $available_gateways = FTP()->payment_gateways()->get_available_payment_gateways();

            if (!isset( $available_gateways[$payment_method])){
                return;
            }

            $result = $available_gateways[ $payment_method ]->process_payment( $order_id );

            return $result;
        }

        /**
         * Validates the posted checkout data based on field properties.
         *
         * @param  array    $data   An array of posted data.
         * @param  WP_Error $errors Validation error.
         */
        private function validate_checkout(&$data, &$errors)
        {
            $checkout_fields = $this->get_checkout_fields();

            foreach ($data as $key => $value){
                $field_label = $key;

                if (isset($checkout_fields[$key])){
                    $field_label = $checkout_fields[$key]['lable'];


                    // Validate require
                    if ($checkout_fields[$key]['require']){
                        if ($value == ''){
                            $errors->add( 'validation', sprintf( __( '<div class="checkout-error__item">%s is required.</div>', 'food-to-prep' ), '<strong>' . esc_html( $field_label ) . '</strong>' ) );
                        }
                    }
                }
            }
        }

        public static function get_checkout_fields(){
            $fields = array(
                'billing_first_name' => array(
                    'lable' => __('First name', 'food-to-prep'),
                    'size' => 'medium',
                    'require' => true
                ),
                'billing_last_name' => array(
                    'lable' => __('Last name', 'food-to-prep'),
                    'size'  => 'medium',
                    'require' => true
                ),
                'billing_address' => array(
                    'lable' => __('Address', 'food-to-prep'),
                    'size'  => 'large',
                    'require' => true
                ),
                'billing_postcode' => array(
                    'lable' => __('Postcode', 'food-to-prep'),
                    'size'  => 'large',
                    'require' => true
                ),
                'billing_phone' => array(
                    'lable' => __('Phone', 'food-to-prep'),
                    'size'  => 'medium',
                    'require' => true
                ),
                'billing_email' => array(
                    'lable' => __('Email', 'food-to-prep'),
                    'size'  => 'medium',
                    'require' => true
                )
            );

            return $fields;
        }
    }

endif;
