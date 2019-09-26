<?php

function food_to_prep_order_user_billing_email_content($order)
{

    ob_start();
    include(FoodToPrep::template_patch() . 'emails/customer-new-order.php');
    $email_content = ob_get_clean();

    ob_start();
    include(FoodToPrep::template_patch() . 'emails/css.css');
    $css = ob_get_clean();

    try {
        $emogrifier = new \Pelago\Emogrifier($email_content, $css);
        $email_content = $emogrifier->emogrify();
    } catch (Exception $e) {
        error_log($e);
    }

    return $email_content;
}

function food_to_prep_create_order_billing_email($order_id)
{
    $order = food_to_prep_get_order(get_post($order_id));

    if ($order->get_id() > 0) {
        $user_email = $order->get_billing_email();
        $headers = array('Content-Type: text/html; charset=UTF-8');

        $email_content = food_to_prep_order_user_billing_email_content($order);

        wp_mail($user_email, 'Thank You For Ordering', $email_content, $headers);
    }
}