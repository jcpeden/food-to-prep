<?php

defined('ABSPATH') || exit;

if (!class_exists('MP_Order_Item')) :

    class MP_Order_Item
    {

        private $order_item_id;
        private $product_id;



        private $name;
        private $quality;
        private $price;

        function __construct(array $init_array)
        {
            $mixed_prefix = array(
                'order_item_id' => 'OrderItemId',
                'name'          => 'Name'
            );

            foreach ($init_array as $key => $value){
                if (isset($mixed_prefix[$key])){
                    if (is_callable(__CLASS__, "set{$mixed_prefix[$key]}")){
                        $this->{"set{$mixed_prefix[$key]}"}($value);
                    }
                }
            }
        }

        public function add_metas(array $meta_array){
            $mixed_prefix = array(
                'id'        => 'ProductId',
                'quality'   => 'Quality',
                'price'     => 'Price'
            );

            foreach ($meta_array as $item){
                $key = $item['meta_key'];
                $value = $item['meta_value'];

                if (isset($mixed_prefix[$key])){
                    if (is_callable(__CLASS__, "set{$mixed_prefix[$key]}")){
                        $this->{"set{$mixed_prefix[$key]}"}($value);
                    }
                }
            }
        }

        /**
         * @return mixed
         */
        public function getOrderItemId()
        {
            return $this->order_item_id;
        }

        /**
         * @param mixed $order_item_id
         * @return MP_Order_Item
         */
        private function setOrderItemId($order_item_id)
        {
            $this->order_item_id = $order_item_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getProductId()
        {
            return $this->product_id;
        }

        /**
         * @param mixed $product_id
         * @return MP_Order_Item
         */
        private function setProductId($product_id)
        {
            $this->product_id = $product_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * @param mixed $name
         * @return MP_Order_Item
         */
        private function setName($name)
        {
            $this->name = $name;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getQuality()
        {
            return $this->quality;
        }

        /**
         * @param mixed $quality
         * @return MP_Order_Item
         */
        private function setQuality($quality)
        {
            $this->quality = $quality;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getPrice()
        {
            return $this->price;
        }

        public function get_formated_price(){
            return FTP()->get_format_currency(number_format($this->getPrice(), 2));
        }

        /**
         * @param mixed $price
         * @return MP_Order_Item
         */
        private function setPrice($price)
        {
            $this->price = $price;
            return $this;
        }
    }

endif;
