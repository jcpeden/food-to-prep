<?php

defined('ABSPATH') || exit;

if (!class_exists('CPT_MP_Order')) :

    class CPT_MP_Order
    {
        private $post_type;

        function __construct()
        {
            $this->post_type = 'mp-order';

            add_action('init', array($this, 'mp_order_init'));
            add_action('admin_menu', array($this, 'my_cpt_admin_submenu'), 30);

            add_action('add_meta_boxes', array($this, 'adding_custom_meta_boxes1'));

            add_filter("manage_{$this->post_type}_posts_columns", array($this, 'set_custom_edit_book_columns'));

            add_action("manage_{$this->post_type}_posts_custom_column", array($this, 'custom_book_column'), 10, 2);


            add_action('admin_footer-edit.php', array($this, 'my_custom_status_add_in_quick_edit'));
            add_action('admin_footer-post.php', array($this, 'my_custom_status_add_in_post_page'));
            add_action('admin_footer-post-new.php', array($this, 'my_custom_status_add_in_post_page'));

            add_action('admin_head-post.php', array($this, 'hide_publishing_actions'));
            add_action('admin_head-post-new.php', array($this, 'hide_publishing_actions'));

        }

        /**
         * Registers the `mp_order` post type.
         */
        function mp_order_init()
        {
            register_post_type($this->post_type, array(
                'labels' => array(
                    'name' => __('Revice Order Lists', 'food-to-prep'),
                    'singular_name' => __('Order', 'food-to-prep'),
                    'all_items' => __('All Orders', 'food-to-prep'),
                    'archives' => __('Order Archives', 'food-to-prep'),
                    'attributes' => __('Order Attributes', 'food-to-prep'),
                    'insert_into_item' => __('Insert into Order', 'food-to-prep'),
                    'uploaded_to_this_item' => __('Uploaded to this Order', 'food-to-prep'),
                    'featured_image' => _x('Featured Image', 'mp-order', 'food-to-prep'),
                    'set_featured_image' => _x('Set featured image', 'mp-order', 'food-to-prep'),
                    'remove_featured_image' => _x('Remove featured image', 'mp-order', 'food-to-prep'),
                    'use_featured_image' => _x('Use as featured image', 'mp-order', 'food-to-prep'),
                    'filter_items_list' => __('Filter Orders list', 'food-to-prep'),
                    'items_list_navigation' => __('Orders list navigation', 'food-to-prep'),
                    'items_list' => __('Orders list', 'food-to-prep'),
                    'new_item' => __('New Order', 'food-to-prep'),
                    'add_new' => __('Add New', 'food-to-prep'),
                    'add_new_item' => __('Add New Order', 'food-to-prep'),
                    'edit_item' => __('Edit Order', 'food-to-prep'),
                    'view_item' => __('View Order', 'food-to-prep'),
                    'view_items' => __('View Orders', 'food-to-prep'),
                    'search_items' => __('Search Orders', 'food-to-prep'),
                    'not_found' => __('No Orders found', 'food-to-prep'),
                    'not_found_in_trash' => __('No Orders found in trash', 'food-to-prep'),
                    'parent_item_colon' => __('Parent Order:', 'food-to-prep'),
                    'menu_name' => __('Orders', 'food-to-prep'),
                ),
                'public' => false,
                'show_ui' => true,
                'capabilities' => array(
                    'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout
                ),
                'map_meta_cap' => true,
                'hierarchical' => false,
                'publicly_queryable' => false,
                'exclude_from_search' => true,
                'supports' => false,
                'has_archive' => true,
                'rewrite' => true,
                'query_var' => true,
                'show_in_nav_menus' => false,
                'show_in_menu' => false
            ));


            $order_statuses = FTP()->get_order_status();

            foreach ($order_statuses as $order_status => $values) {
                register_post_status($order_status, $values);
            }
        }

        function my_cpt_admin_submenu()
        {

            $cpt = 'mp-order';

            $cpt_obj = get_post_type_object($cpt);


            add_submenu_page(
                'meal-prep',                      // parent slug
                $cpt_obj->labels->name,            // page title
                $cpt_obj->labels->menu_name,       // menu title
                $cpt_obj->cap->edit_posts,         // capability
                'edit.php?post_type=' . $cpt       // menu slug
            );
        }


        function adding_custom_meta_boxes1($post)
        {
            add_meta_box(
                'meal-prep__order-data',
                __('Order Detail', 'food-to-prep'),
                array($this, 'render_my_meta_box1'),
                $this->post_type,
                'normal',
                'high'
            );

            add_meta_box(
                'meal-prep__order-items',
                __('Order Items', 'food-to-prep'),
                array($this, 'render__order_items'),
                $this->post_type,
                'normal',
                'high'
            );
        }


        function render_my_meta_box1()
        {
            global $post;

            $order = food_to_prep_get_order($post);
            $countries = apply_filters('get_countries', 'all');

            ?>
            <div>
                <?php
                $payment_methods = FTP()->payment_gateways()->get_available_payment_gateways();
                $method_title = $order->get_payment_method();

                $method = $payment_methods[$method_title];

                if (isset($method)) {
                    $method_title = $method->method_title;
                }

                printf('Payment via %s', $method_title) ?>
            </div>
            <div class="meal-prep__order_data" data-order-id="<?php esc_html_e($order->get_id(), 'food-to-prep'); ?>">
                <div class="order_data_column">
                    <h3><?php esc_html_e('General', 'food-to-prep'); ?></h3>
                    <div class="order-info">
                        <div class="order-info-row">
                            <p class="order-info__title"><?php esc_html_e("Date created:", 'food-to-prep'); ?></p>
                            <p class="order-info__value">
                                <?php esc_html_e(get_post_time('M-Y-d H:m'), 'food-to-prep'); ?>
                            </p>
                        </div>
                        <div class="order-info-row">
                            <p class="order-info__title"><?php esc_html_e("Status:", 'food-to-prep'); ?></p>
                            <p class="order-info__value">
                                <select id="order_status" name="order_status" class="order-info__order-status">
                                    <?php

                                    $current_status = get_post_status();

                                    $order_status = FTP()->get_order_status();

                                    foreach ($order_status as $key => $status) {
                                        $selected = ($key == $current_status) ? 'selected' : '';
                                        printf('<option value="%s" %s>%s</option>', $key, $selected, $status['label']);
                                    }

                                    ?>
                                </select>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="order_data_column">
                    <h3><?php esc_html_e('Billing', 'food-to-prep') ?></h3>
                    <div class="billing-info">
                        <div class="billing-info-row">
                            <p class="billing-info__title"><?php esc_html_e("Customer:", 'food-to-prep'); ?></p>
                            <p class="billing-info__value"><?php esc_html_e($order->get_billing_first_name() . ' ' . $order->get_billing_last_name(), 'food-to-prep'); ?></p>
                        </div>
                        <div class="billing-info-row">
                            <p class="billing-info__title"><?php esc_html_e("Address:", 'food-to-prep'); ?></p>
                            <p class="billing-info__value"><?php esc_html_e($order->get_billing_address(), 'food-to-prep'); ?></p>
                        </div>
                        <div class="billing-info-row">
                            <p class="billing-info__title"><?php esc_html_e('Postcode', 'food-to-prep'); ?></p>
                            <p class="billing-info__value"><?php esc_html_e($order->get_billing_postcode(), 'food-to-prep'); ?></p>
                        </div>
                        <div class="billing-info-row">
                            <p class="billing-info__title"><?php esc_html_e("Phone Number:", 'food-to-prep'); ?></p>
                            <p class="billing-info__value"><?php esc_html_e($order->get_billing_phone(), 'food-to-prep'); ?></p>
                        </div>
                        <div class="billing-info-row">
                            <p class="billing-info__title"><?php esc_html_e("Email Address:", 'food-to-prep'); ?></p>
                            <p class="billing-info__value"><?php esc_html_e($order->get_billing_email(), 'food-to-prep'); ?></p>
                        </div>
                        <?php
                        if ($order->get_billing_order_comments()) {
                            ?>
                            <div class="billing-info-row">
                                <p class="billing-info__title"><?php esc_html_e("Customer provided note:", 'food-to-prep') ?></p>
                                <p class="billing-info__value"><?php esc_html_e($order->get_billing_order_comments(), 'food-to-prep'); ?></p>
                            </div>
                            <?php
                        }
                        ?>

                    </div>
                    <div class="edit-billing-info" style="display: none">
                        <div class="form-field billing-info-half-row">
                            <p class="billing-info__title"><?php esc_html_e("First Name", 'food-to-prep') ?></p>
                            <input type="text" name="billing_first_name"
                                   value="<?php esc_html_e($order->get_billing_first_name(), 'food-to-prep'); ?>"/>
                        </div>
                        <div class="form-field billing-info-half-row">
                            <p class="billing-info__title"><?php esc_html_e("Last Name", 'food-to-prep') ?></p>
                            <input type="text" name="billing_last_name"
                                   value="<?php esc_html_e($order->get_billing_last_name(), 'food-to-prep'); ?>"/>
                        </div>
                        <div class="form-field billing-info-row">
                            <p class="billing-info__title"><?php esc_html_e("Address", 'food-to-prep'); ?></p>
                            <input type="text" name="billing_address"
                                   value="<?php esc_html_e($order->get_billing_address(), 'food-to-prep'); ?>"/>
                        </div>
                        <div class="form-field billing-info-row">
                            <p class="billing-info__title"><?php esc_html_e("Postcode", 'food-to-prep'); ?></p>
                            <input type="text" name="billing_postcode"
                                   value="<?php esc_html_e($order->get_billing_postcode(), 'food-to-prep'); ?>"/>
                        </div>
                        <div class="form-field billing-info-half-row">
                            <p class="billing-info__title"><?php esc_html_e("Phone Number", 'food-to-prep') ?></p>
                            <input type="text" name="billing_phone" value="<?php esc_html_e($order->get_billing_phone(), 'food-to-prep'); ?>"/>
                        </div>
                        <div class="form-field billing-info-half-row">
                            <p class="billing-info__title"><?php esc_html_e("Email Address", 'food-to-prep') ?></p>
                            <input type="text" name="billing_email" value="<?php esc_html_e($order->get_billing_email(), 'food-to-prep'); ?>"/>
                        </div>
                        <div class="form-field billing-info-row">
                            <p class="billing-info__title"><?php esc_html_e("Customer provided note", 'food-to-prep'); ?></p>
                            <textarea name="billing_order_comments" class="input-text" id="billing_order_comments"
                                      placeholder="Notes about your order, e.g. special notes for delivery."
                                      rows="2" cols="5" spellcheck="false"
                            ><?php esc_html_e($order->get_billing_order_comments(), 'food-to-prep'); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <?php
        }

        function render__order_items()
        {
            global $post;

            $order = food_to_prep_get_order($post);

            ?>
            <table class="order_items__table">
                <thead>
                <tr>
                    <th colspan="2">Item</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>

                <?php
                $order_items = $order->get_order_items();

                if (is_array($order_items) && sizeof($order_items) > 0) {
                    foreach ($order_items as $item) {
                        ?>
                        <tr data-id="<?php esc_html_e($item->getOrderItemId(), 'food-to-prep'); ?>">
                            <td class="image-thumbnail">
                                <div
                                    style="background-image: url('<?php esc_html_e(get_the_post_thumbnail_url($item->getProductId()), 'food-to-prep'); ?>')"></div>
                            </td>
                            <td>
                                <a href="<?php esc_html_e(get_edit_post_link($item->getProductId()), 'food-to-prep'); ?>"><?php esc_html_e($item->getName(), 'food-to-prep') ?></a>
                            </td>
                            <td style="width: 100px;">× <?php esc_html_e($item->getQuality(), 'food-to-prep') ?></td>
                            <td style="width: 100px;"><?php esc_html_e($item->get_formated_price(), 'food-to-prep') ?></td>
                        </tr>
                        <?php
                    }
                }

                ?>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="3"><?php esc_html_e('Total', 'food-to-prep'); ?></th>
                    <td><?php esc_html_e($order->get_formated_total(), 'food-to-prep'); ?></td>
                </tr>
                </tfoot>
            </table>
            <?php
        }

        function set_custom_edit_book_columns($columns)
        {
            $columns['status'] = __('Status', 'food-to-prep');
            $columns['qty'] = __('Qty', 'food-to-prep');
            $columns['total'] = __('Total', 'food-to-prep');

            return $columns;
        }

        function custom_book_column($column, $post_id)
        {
            $post = get_post($post_id);

            $order = food_to_prep_get_order($post);

            switch ($column) {

                case 'total' :
                    esc_attr_e($order->get_formated_total(), 'food-to-prep');
                    break;

                case 'status':
                    $post_status = get_post_status_object(get_post_status($post));

                    printf('<div class="order-status status__%s">%s</div>', esc_attr($post_status->name), esc_attr($post_status->label));
                    break;

                case 'qty':
                    $qty = 0;

                    foreach ($order->get_order_items() as $item) {
                        $qty = $qty + $item->getQuality();
                    }

                    printf('× %s', esc_attr($qty));
                    break;
            }
        }


        function my_custom_status_add_in_quick_edit()
        {

            $status = '';

            foreach (FTP()->get_order_status() as $key => $value) {
                $status .= "<option value='{$key}'>{$value['label']}</option>";
            }

            ?>
            <script>
                var status_scripts = "<?php _e($status, 'food-to-prep') ?>";

                jQuery(document).ready(function () {
                    jQuery('.post-type-mp-order select[name="_status"]').append(status_scripts);
                    jQuery('.post-type-mp-order select[name="_status"]').find('option[value=publish], option[value=pending], option[value=draft]').remove();
                });
            </script>
            <?php
        }

        function my_custom_status_add_in_post_page()
        {
            $status = '';

            foreach (FTP()->get_order_status() as $key => $value) {
                $status .= "<option value='{$key}'>{$value['label']}</option>";
            }

            ?>
            <script>
                jQuery(document).ready(function () {

                    var status_scripts = "<?php _e($status, 'food-to-prep') ?>";
                    jQuery('.post-type-mp-order select[name="post_status"]').append(status_scripts);
                });
            </script>
            <?php
        }

        function hide_publishing_actions()
        {
            $my_post_type = 'mp-order';
            global $post;
            if ($post->post_type == $my_post_type) {
                ?>
                <style type="text/css">
                    #misc-publishing-actions,
                    #minor-publishing-actions {
                        display: none;
                    }
                </style>
                <?php
            }
        }
    }

    new CPT_MP_Order();


endif;
