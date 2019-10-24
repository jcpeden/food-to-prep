<?php

if (!isset($_GET['orderId']))
    return false;

$orderId = sanitize_text_field($_GET['orderId']);

$gateways = FTP()->payment_gateways()->get_available_payment_gateways();
$gateways['paypal_authorize']->executePayment($orderId);

$arr_params = array( 'orderId' => $orderId );
wp_redirect(add_query_arg($arr_params, home_url(FTP()->endpoint_thankyou())));