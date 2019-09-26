<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'MP_Abstract_Order' ) ) :
    abstract class MP_Abstract_Order extends MP_Data
    {
        private $table_orderitems = 'mp_order_items';
        protected $table_order_itemmeta = 'mp_order_itemmeta';


        protected $data = array(
            'total'             => 0,
            'payment_method'    => null,
            'transaction_id'    => null,
            'billing'               => array(
                'first_name'        => null,
                'last_name'         => null,
                'country'           => null,
                'address'           => null,
                'postcode'          => null,
                'email'             => null,
                'phone'             => null,
                'order_comments'    => null
            ),
        );

        protected $meta_key_to_props = array(
            '_order_total'          => 'total',
            '_transaction_id'       => 'transaction_id',
            '_payment_method'       => 'payment_method'
        );

        protected $fields_prefix = array(
            'shipping' => true,
            'billing'  => true
        );

        /**
         * @var MP_Order_Item[]
         */
        private $order_items = array();

        protected function set_order_items($items)
        {
            global $wpdb;

            foreach ($items as $order_item) {

                $order = array(
                    'name' => $order_item->name,
                    'order_item_type' => 'order_item',
                    'order_id' => $this->get_id()
                );

                $wpdb->insert("{$wpdb->prefix}{$this->table_orderitems}",
                    $order
                );

                $order_metas = array();

                if ($wpdb->insert_id ){

                    $order_item_id = $wpdb->insert_id;

                    $need_update = array(
                        'id',
                        'quality',
                        'price'
                    );

                    foreach ($need_update as $meta_key){
                        $meta = array(
                            'meta_key'      => $meta_key,
                            'meta_value'    => $order_item->{$meta_key},
                            'order_item_id' => $order_item_id
                        );

                        $wpdb->insert("{$wpdb->prefix}{$this->table_order_itemmeta}",
                            $meta
                        );

                        array_push($order_metas, (object) $meta);
                    }

                    $order['id'] = $order_item_id;
                    $order['order_meta'] = $order_metas;

                    array_push($order_item, (object) $order);
                }
            }
        }


        public function get_order_items(){
            global $wpdb;

            if (sizeof($this->order_items) == 0){
                $result = $wpdb->get_results(
                    $wpdb->prepare("SELECT order_item_id, name, order_item_type, order_id FROM {$wpdb->prefix}{$this->table_orderitems} WHERE order_id = %d", $this->get_id()),
                    ARRAY_A);

                foreach ($result as $item){

                    $metas = $wpdb->get_results(
                        $wpdb->prepare("SELECT meta_key, meta_value FROM {$wpdb->prefix}{$this->table_order_itemmeta} WHERE order_item_id = %d", $item['order_item_id']),
                        ARRAY_A);

                    $order_item = new MP_Order_Item($item);
                    $order_item->add_metas($metas);




                    array_push($this->order_items, $order_item);
                }
            }

            return $this->order_items;
        }

        public function get_total(){
            return $this->get_prop('total');
        }

        public function get_formated_total(){
            return FTP()->get_format_currency(number_format($this->get_total(),2));
        }

        public function set_total($value)
        {
            $this->set_prop('total', $value);
        }

        public function get_created_date(){
            return get_the_date('M-Y-d', $this->get_id());
        }

        /**
         * @return mixed
         */
        public function get_payment_method()
        {

            $value = $this->get_prop('payment_method');
            if (!isset($value)){
                $method = get_post_meta($this->get_id(), '_payment_method', true);
                $this->set_payment_method($method);
            }

            return $this->get_prop('payment_method');
        }

        /**
         * @param $payment_method
         */
        public function set_payment_method($payment_method)
        {
            $this->set_prop('payment_method', $payment_method);
        }

        /**
         * @return mixed
         */
        public function get_transaction_id()
        {
            return $this->get_prop('transaction_id');
        }

        /**
         * @param $payment_method
         */
        public function set_transaction_id($transaction_id)
        {
            $this->set_prop('transaction_id', $transaction_id);
        }

        public function update_status($status){
            if ( ! $this->get_id() ) { // Order must exist.
                return false;
            }

            wp_update_post(array(
                'ID' => $this->get_id(),
                'post_status' => 'mp-' . $status
            ));

            return true;
        }

        /**
         * Adds a note (comment) to the order. Order must exist.
         *
         * @param  string $note              Note to add.
         * @param  int    $is_customer_note  Is this a note for the customer?.
         * @param  bool   $added_by_user     Was the note added by a user?.
         * @return int                       Comment ID.
         */
        public function add_order_note($note)
        {
            if (!$this->get_id()) {
                return 0;
            }

            $comment_author = __('FoodToPrep', 'food-to-prep');
            $comment_author_email = strtolower(__('FoodToPrep', 'food-to-prep')) . '@';
            $comment_author_email .= isset($_SERVER['HTTP_HOST']) ? str_replace('www.', '', sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST']))) : 'noreply.com'; // WPCS: input var ok.
            $comment_author_email = sanitize_email($comment_author_email);

            $commentdata = array(
                'comment_post_ID' => $this->get_id(),
                'comment_author' => $comment_author,
                'comment_author_email' => $comment_author_email,
                'comment_author_url' => '',
                'comment_content' => $note,
                'comment_agent' => 'FoodToPrep',
                'comment_type' => 'order_note',
                'comment_parent' => 0,
                'comment_approved' => 1,
            );

            $comment_id = wp_insert_comment($commentdata);

            return $comment_id;
        }
    }
endif;
