<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'MP_AJAX' ) ) :

    class MP_AJAX
    {
        public static function init()
        {
            add_action( 'init', array( __CLASS__, 'define_ajax' ), 0 );
            add_action( 'template_redirect', array( __CLASS__, 'do_wc_ajax' ), 0 );

            self::add_ajax_events();
        }

        /**
         * Set WC AJAX constant and headers.
         */
        public static function define_ajax() {
            // phpcs:disable
            if ( ! empty( $_GET['mp-ajax'] ) ) {
                define('DOING_AJAX', true);
                define('WC_DOING_AJAX', true);

                @ini_set( 'display_errors', 0 );

                $GLOBALS['wpdb']->hide_errors();
            }
            // phpcs:enable
        }

        /**
         * Send headers for WC Ajax Requests.
         *
         * @since 2.5.0
         */
        private static function wc_ajax_headers() {
            if ( ! headers_sent() ) {
                send_origin_headers();
                send_nosniff_header();
                nocache_headers();
                header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
                header( 'X-Robots-Tag: noindex' );
                status_header( 200 );
            } elseif ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                headers_sent( $file, $line );
                trigger_error( "mp_ajax_headers cannot set headers - headers already sent by {$file} on line {$line}", E_USER_NOTICE ); // @codingStandardsIgnoreLine
            }
        }

        public static function do_wc_ajax(){
            global $wp_query;

            // phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification
            if ( ! empty( $_GET['mp-ajax'] ) ) {
                $wp_query->set( 'mp-ajax', sanitize_text_field( wp_unslash( $_GET['mp-ajax'] ) ) );
            }

            $action = $wp_query->get( 'mp-ajax' );

            if ( $action ) {
                self::wc_ajax_headers();
                $action = sanitize_text_field( $action );
                do_action( 'mp_ajax_' . $action );
                wp_die();
            }
            // phpcs:enable
        }

        /**
         * Hook in methods - uses WordPress ajax handlers (admin-ajax).
         */
        public static function add_ajax_events()
        {
            $ajax_events_nopriv = array(
                'get_refreshed_fragments',
                'add_to_cart',
                'clear_cart',
                'cart_content',
                'checkout_order_detail',
                'checkout',
                'get_countries'
            );

            add_filter('mp_ajax_endpoint', function(){
                $arr = array(
                    'get_refreshed_fragments',
                    'add_to_cart',
                    'clear_cart',
                    'cart_content',
                    'checkout_order_detail',
                    'checkout',
                    'get_countries'
                );

                $result = array();

                foreach ($arr as $key){
                    $result[$key] = '/?mp-ajax=' . $key;
                }

                return $result;
            });

            foreach ($ajax_events_nopriv as $ajax_event) {
                add_action('wp_ajax_meal-prep_' . $ajax_event, array(__CLASS__, $ajax_event));
                add_action('wp_ajax_nopriv_meal-prep_' . $ajax_event, array(__CLASS__, $ajax_event));

                add_action( 'mp_ajax_' . $ajax_event, array( __CLASS__, $ajax_event ) );
            }
        }

        public static function get_refreshed_fragments($not_print = false)
        {
            ob_start();

            meal_to_prep_mini_cart();

            $mini_cart = ob_get_clean();

            $data = array(
                'fragments' => array(
                    'div.meal-item__mini-cart' => $mini_cart
                )
            );

            if ($not_print){
                return $data;
            }else{
                wp_send_json($data);
            }

        }

        public static function add_to_cart()
        {
            if (isset($_POST['id'])) {

                $item_id = intval( $_POST['id']);
                $item_quantity = intval($_POST['quantity']);

                $meal = get_post($item_id);

                $product = new MP_Product($meal);

                FTP()->cart->add_item_to_cart($product, $item_quantity);

                $quanlity_text = $item_quantity > 1 ? $item_quantity . ' × ' : '';

                $add_to_cart_message = '<div class="mp_message success">'. esc_html__( sprintf('%s"%s" has been added to your cart.', $quanlity_text, $product->get_title() ), 'food-to-prep') . '</div>';

                $cart = self::get_refreshed_fragments(true);
                $cart['fragments']['div.meal-item__messages'] = $add_to_cart_message;

                wp_send_json($cart);
            }
        }

        public static function clear_cart()
        {
            FTP()->cart->clear_all_cart_item();

            $cart = self::get_refreshed_fragments(true);

            wp_send_json($cart);
        }

        public static function cart_content(){
            ob_start();

            meal_to_pre_get_cart_content();

            $cart_content = ob_get_clean();

            $data = array('html' => $cart_content);

            wp_send_json($data);
        }

        public static function checkout_order_detail(){
            ob_start();

            meal_to_prep_get_checkout_content();

            $checkout_content = ob_get_clean();

            $data = array('html' => $checkout_content);

            wp_send_json($data);
        }

        public static function checkout(){

            FTP()->checkout()->process_checkout();
        }

        public static function get_countries() {
            $countries = apply_filters('get_countries', 'all');
            wp_send_json(
                array(
                    'success' => true,
                    'results' => $countries
                )
            );
        }
    }

endif;
