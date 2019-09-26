<?php

if (!class_exists('MP_Check_Gateway')) :

    class MP_Check_Gateway extends MP_Payment_Gateway
    {
        public function __construct()
        {
            $this->id = 'check';
            $this->method_title = 'Check payments';

            $this->description = 'Test payments';


            add_filter('meal_prep_checkout_methods', array($this, 'add_checkout_method'));
        }

        public function process_payment($order_id)
        {
            $arr_params = array('orderId' => $order_id);
            $approvalUrl = add_query_arg($arr_params, FTP()->endpoint_thankyou());

            return array(
                'result' => 'success',
                'messages' => $approvalUrl
            );
        }
    }

    new MP_Check_Gateway();

endif;