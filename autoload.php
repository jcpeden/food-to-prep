<?php

spl_autoload_register( 'meal_to_prep_autoloader' ); // Register autoloader
function meal_to_prep_autoloader($class_name ) {
    if ( false !== strpos( $class_name, 'MP' ) ) {
        $classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;

        $class_file = $class_name . '.php';

        $fileName = $classes_dir . $class_file;

        if(file_exists($fileName)){
            require_once $fileName;
        }
    }
}


require_once 'includes/db/db-init.php';

require_once 'class-wp-osa.php';
require_once 'wposa-init.php';


require_once 'mp-template-functions.php';

//Remove Test Payments
//require_once 'includes/payments/MP_Check_Gateway.php';
require_once 'includes/payments/MP_PayPal_Gateway.php';


require_once 'includes/notices/mp-order-functions.php';
require_once 'includes/notices/mp-helper-functions.php';

require_once 'post-types/CPT_MP_Meal.php';
require_once 'post-types/CPT_MP_Order.php';

