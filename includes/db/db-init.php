<?php

function MTP_plugin_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'mp_order_items';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
		order_item_id mediumint(9) NOT NULL AUTO_INCREMENT,
		name tinytext NOT NULL,
		order_item_type tinytext NOT NULL,
		order_id mediumint(9) NOT NULL,
		PRIMARY KEY  (order_item_id)
	) $charset_collate;";

    $table_name2 = $wpdb->prefix . 'mp_order_itemmeta';

    $sql1 = "CREATE TABLE $table_name2 (
		meta_id mediumint(9) NOT NULL AUTO_INCREMENT,
		order_item_id mediumint(9) NOT NULL,
        meta_key varchar(255) NOT NULL,
        meta_value tinytext NOT NULL,
		PRIMARY KEY  (meta_id),
		KEY order_item_id (order_item_id),
		KEY meta_key (meta_key)
	) $charset_collate;";


    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    dbDelta($sql1);

    add_option('mp_prep_version', FoodToPrep::plugin_version());
}

register_activation_hook(__FILE__, 'MTP_plugin_install');
