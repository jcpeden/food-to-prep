<?php

/**
 * @param int|WP_Post|null $post   Optional. Post ID or post object. Defaults to global $post.
 * @return MP_Order
 */
function food_to_prep_get_order($post){
    $_post = get_post($post);

    $order = new MP_Order();
    $order->set_id($_post->ID);

    $order->get_data();

    return $order;
}