<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'MP_Cart' ) ) :

    /**
     * Class MP_Cart.
     */
    class MP_Cart
    {
        private $cart;

        /**
         *
         * Function Add item Meal to Cart
         *
         * @param MP_Product $product
         * @param int $quantity
         */
        function add_item_to_cart(MP_Product $product, $quantity = 1)
        {
            $this->get_update_cart();

            if (is_null($this->cart->items)){
                $this->cart->items = array();
            }

            $product_item = (object)array(
                'id' => $product->get_id(),
                'name' => $product->get_title(),
                'quality' => $quantity,
                'price' => $product->get_normal_price(),
                'photo' => $product->get_photo_thumbnail(),
                'url' => $product->get_url()
            );

            $is_new = true;
            if( ( is_array($this->cart->items) && count($this->cart->items) > 0 )) {

                foreach ( $this->cart->items as $index => $meal ) {
                    if( $meal->id == $product->get_id() ) {
                        $product_item = $this->cart->items[$index];
                        $product_item_quality = $product_item->quality + $quantity;
                        $product_item->quality = $product_item_quality;
                        $this->cart->items[$index] = $product_item;
                        $is_new = false;
                        break;
                    }
                }
            }

            if($is_new) {
                array_push($this->cart->items, $product_item);
            }


            $this->cart->total = $this->cart->total ? $this->cart->total + $product->get_normal_price()*$quantity : $product->get_normal_price()*$quantity;

            $this->set_cart();
            return $this->cart->items;
        }

        function clear_all_cart_item(){
            $this->get_update_cart();

            $this->cart->items = array();
            $this->cart->total = 0;

            $this->set_cart();
        }

        function get_cart(){
            $this->get_update_cart();

            return $this->cart->items;
        }

        function get_cart_total(){
            $this->get_update_cart();

            return $this->cart->total;
        }

        function get_formated_cart_total(){
            return FTP()->get_format_currency(number_format($this->get_cart_total(),2));
        }

        function get_formated_cart_subtotal(){
            return FTP()->get_format_currency(number_format($this->get_cart_total(), 2));
        }

        function is_empty(){
            return 0 === count( $this->get_cart() );
        }

        private function get_update_cart()
        {
            if (is_null($this->cart)) {
                $this->cart = FTP()->session->get_session('cart');
            }
        }

        private function set_cart()
        {
            if (!is_null($this->cart)) {
                FTP()->session->set_session('cart', $this->cart);
            }
        }
    }
endif;
