<?php
/**
 * Plugin Name:     Food To Prep
 * Plugin URI:      https://wordpress.org/plugins/food-to-prep
 * Description:     Food To Prep by Tweak Digital.
 * Author:          Tweak Digital
 * Author URI:      http://www.tweakdigital.co.uk/
 * Text Domain:     food-to-prep
 * Domain Path:     /languages
 * Version:         0.1.10
 *
 * @package         Meal_Prep
 */

require_once 'autoload.php';
require_once 'vendor/autoload.php';

class FoodToPrep
{
    private static $instance;

    protected $templates;


    /**
     * @var MP_Cart
     */
    public $cart;

    /**
     * @var MP_Session
     */
    public $session;

    /**
     * @var MP_Setting
     */
    public $settings;

    public $payment_gateways;


    public static function template_patch()
    {
        return dirname(__FILE__) . '/templates/';
    }

    public static function plugin_asset_url()
    {
        return plugin_dir_url(__FILE__) . 'assets';
    }

    public static function plugin_version()
    {
        if (!function_exists('get_plugin_data')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $plugin_data = get_plugin_data(plugin_dir_path(__FILE__) . 'food-to-prep.php');

        return $plugin_data['Version'];
    }

    public static function get_instance()
    {
        if (null == self::$instance) {
            self::$instance = new FoodToPrep();
        }
        return self::$instance;
    }

    private function initialize_cart()
    {
        if (is_null($this->cart) || !$this->cart instanceof MP_Cart) {
            $this->cart = new MP_Cart();
        }
    }

    private function initialize_session()
    {
        if (is_null($this->session) || !$this->session instanceof MP_Session) {
            $this->session = new MP_Session();
            $this->session->init();
        }
    }

    private function initialize_ajax()
    {
        MP_AJAX::init();
    }

    private function initialize_settings()
    {
        if (is_null($this->settings) || !$this->settings instanceof MP_Setting) {
            $this->settings = new MP_Setting();
        }
    }

    private function __construct()
    {
        add_action('init', array($this, 'register_assets_plugin'));
        add_filter('plugin_row_meta', array($this, 'support_and_faq_links'), 10, 4);


        add_filter('page_template', array($this, 'custom_page_template'));

        add_action('template_redirect', array($this, 'custom_revice_order'), 0);


        add_shortcode('meal_prep_mini_cart', 'meal_to_prep_get_html_mini_cart');

        add_action('storefront_header', array($this, 'storefront_header_cart'), 60);
        add_action('meal-item__add-to-cart', 'meal_to_prep_get_add_to_cart_button');


        $this->initialize_session();
        $this->initialize_cart();
        $this->initialize_ajax();
        $this->initialize_settings();
    }


    function register_assets_plugin()
    {
        if (is_admin()) {
            wp_enqueue_style('food-prep-admin-plugin-admin-style', FoodToPrep::plugin_asset_url() . '/css/admin.style.min.css', '', FoodToPrep::plugin_version());
            wp_enqueue_script('food-prep-admin-order-detail', FoodToPrep::plugin_asset_url() . '/js/admin/order-detail.min.js', array('jquery'), null, true);

        } else {

            wp_enqueue_style('food-prep-boostrap', FoodToPrep::plugin_asset_url() . '/libs/bootstrap-4.3.1/css/bootstrap.min.css', '', null);
            wp_enqueue_style('food-prep-plugin-style', FoodToPrep::plugin_asset_url() . '/css/style.min.css', '', FoodToPrep::plugin_version());

            wp_enqueue_script('food-prep-boostrap-script', FoodToPrep::plugin_asset_url() . '/libs/bootstrap-4.3.1/js/bootstrap.min.js', array('jquery'), null, true);
            wp_enqueue_script('food-prep-grid-gallery', FoodToPrep::plugin_asset_url() . '/js/grid-gallery.min.js', array('jquery'), FoodToPrep::plugin_version(), true);
            wp_enqueue_script('food-prep-add-to-cart', FoodToPrep::plugin_asset_url() . '/js/add-to-cart.min.js', array('jquery'), FoodToPrep::plugin_version(), true);

            wp_localize_script('food-prep-add-to-cart', 'meal_prep',
                array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'mp_ajax_url' => apply_filters('mp_ajax_endpoint', array())
                ));
        }
    }

    function custom_page_template($page_template)
    {
        if (is_page(FTP()->endpoint_cart())) {
            $page_template = FoodToPrep::template_patch() . 'page-cart.php';
        }

        if (is_page(FTP()->endpoint_checkout())) {
            $page_template = FoodToPrep::template_patch() . 'page-checkout.php';
        }

        if (is_page(FTP()->endpoint_revice_order())) {
            $page_template = FoodToPrep::template_patch() . 'page-revice-revice.php';
        }

        if (is_page(FTP()->endpoint_menu())) {
            $page_template = FoodToPrep::template_patch() . 'page-meal-list.php';
        }

        return $page_template;
    }

    function custom_revice_order()
    {
        global $wp_query;

        if (!empty($_GET['revice-order'])) {
            $wp_query->set('revice-order', sanitize_text_field(wp_unslash($_GET['remove-order'])));
        }

        $action = $wp_query->get('mp-ajax');

        if ($action) {
            self::wc_ajax_headers();
            $action = sanitize_text_field($action);
            do_action('mp_ajax_' . $action);
            wp_die();
        }
    }


    /**
     * Food list endpoint.
     * @return string
     */
    function endpoint_menu()
    {
        return MTP_OSA()->get_option('endpoint_meal_list', 'meal_prep_other');
    }

    function endpoint_thankyou()
    {
        return MTP_OSA()->get_option('endpoint_thankyou', 'meal_prep_other');
    }

    function endpoint_cart()
    {
        return MTP_OSA()->get_option('endpoint_cart', 'meal_prep_other');
    }

    function endpoint_checkout()
    {
        return MTP_OSA()->get_option('endpoint_checkout', 'meal_prep_other');
    }

    function endpoint_revice_order()
    {
        return MTP_OSA()->get_option('endpoint_revice_order', 'meal_prep_other');
    }


    function checkout()
    {
        return MP_Checkout::get_instance();
    }

    function get_order_status()
    {

        $order_statuses = array(
            'mp-pending' => array(
                'label' => _x('Pending payment', 'Order status', 'food-to-prep'),
                'public' => is_admin(),
                'exclude_from_search' => false,
                'show_in_admin_all_list' => false,
                'show_in_admin_status_list' => true,
                'label_count' => _n_noop('Pending payment <span class="count">(%s)</span>', 'Pending payment <span class="count">(%s)</span>', 'food-to-prep'),
            ),
            'mp-processing' => array(
                'label' => _x('Processing', 'Order status', 'food-to-prep'),
                'public' => is_admin(),
                'exclude_from_search' => false,
                'show_in_admin_all_list' => false,
                'show_in_admin_status_list' => true,
                'label_count' => _n_noop('Processing <span class="count">(%s)</span>', 'Processing <span class="count">(%s)</span>', 'food-to-prep'),
            ),
            'mp-cancelled' => array(
                'label' => _x('Cancelled', 'Order status', 'food-to-prep'),
                'public' => is_admin(),
                'exclude_from_search' => false,
                'show_in_admin_all_list' => false,
                'show_in_admin_status_list' => true,
                'label_count' => _n_noop('Cancelled <span class="count">(%s)</span>', 'Cancelled <span class="count">(%s)</span>', 'food-to-prep'),
            ),
            'mp-completed' => array(
                'label' => _x('Completed', 'Order status', 'food-to-prep'),
                'public' => is_admin(),
                'exclude_from_search' => false,
                'show_in_admin_all_list' => false,
                'show_in_admin_status_list' => true,
                'label_count' => _n_noop('Completed <span class="count">(%s)</span>', 'Completed <span class="count">(%s)</span>', 'food-to-prep'),
            ),
        );

        return $order_statuses;
    }

    /**
     * Get gateways class.
     *
     * @return MP_Payment_Gateways
     */
    public function payment_gateways()
    {
        return MP_Payment_Gateways::instance();
    }

    function storefront_header_cart()
    {
        ?>
        <div class="float-mini-cart">
            <?php print do_shortcode('[meal_prep_mini_cart]'); ?>
        </div>
        <?php
    }

    public function get_format_currency($price)
    {
        if (is_numeric($price)) {
            return $this->settings->get_currency_symbol() . $price;
        }
        return $this->settings->get_currency_symbol() . "0";
    }


    function support_and_faq_links($links_array, $plugin_file_name, $plugin_data, $status)
    {
        if (strpos($plugin_file_name, basename(__FILE__))) {
            // you can still use array_unshift() to add links at the beginning
            $links_array[] = '<a href="https://wordpress.org/support/plugin/food-to-prep/" target="_blank">Support</a>';
        }

        return $links_array;
    }
}

add_action('plugins_loaded', array('FoodToPrep', 'get_instance'));

function FTP()
{
    return FoodToPrep::get_instance();
}


register_deactivation_hook(__FILE__, 'flush_rewrite_rules');
register_activation_hook(__FILE__, 'MPP_flush_rewrites');
function MPP_flush_rewrites()
{
    flush_rewrite_rules();
}


/**
 *
 * Filter function get excerpt content
 *
 * @param array $content ['string' => '', 'length' => 0]
 * @return string Excerpt content
 *
 */

add_filter('excerpt_content', function ($content = array('string' => '', 'length' => 100)) {
    $string = array_key_exists('string', $content) ? $content['string'] : $content[0];
    $length = array_key_exists('length', $content) ? $content['length'] : $content[1];

    $string = trim($string);
    $string_arr = explode(' ', $string);
    $count_char = 0;
    $excerpt_content = '';
    foreach ($string_arr as $word) {
        $count_char += strlen($word);
        if ($count_char <= $length) {
            $excerpt_content .= $word . ' ';
            $count_char++;
        } else {
            break;
        }
    }
    if ($count_char <= $length) {
        return $string;
    } else {
        return trim($excerpt_content) . '...';
    }
});

function ftp_prefix_modify_query_order($query)
{
    if (is_main_query()) {

        /**
         * Support sortby in menu pages
         */
        if (is_page(FTP()->endpoint_menu()) || is_tax('meal-category')) {
            $sortby = get_query_var('sortby');

            if (isset($sortby)) {
                $args = array('date' => 'DESC');

                switch ($sortby) {
                    case 'oldest':
                        $args = array('date' => 'ASC');
                        break;
                    case 'a-z':
                        $args = array('title' => 'ASC');
                        break;
                    default:
                        $args = array('date' => 'DESC');
                }

                $query->set('orderby', $args);
            }
        }
    }
}

add_action('pre_get_posts', 'ftp_prefix_modify_query_order');

add_action('init', 'ftp_register_param');
function ftp_register_param()
{
    global $wp;
    $wp->add_query_var('sortby');
}