<?php

defined('ABSPATH') || exit;

if (!class_exists('MP_Product')) :


    class MP_Product extends MP_Data
    {
        private $post;

        protected $data = array(
            'normal_price' => 0
        );

        function __construct(WP_Post $post)
        {
            $this->set_prop('normal_price', get_post_meta($post->ID, 'normal_price', true));

            $this->post = $post;
        }

        public function get_id()
        {
            return $this->post->ID;
        }

        public function get_title()
        {
            return $this->post->post_title;
        }

        public function get_description_excerpt($num_char = 70)
        {
            return apply_filters('excerpt_content', array(strip_tags($this->post->post_content), $num_char));
        }

        public function get_description()
        {
            return $this->post->post_content;
        }

        public function set_normal_price($value)
        {
            $this->set_prop('normal_price', $value);
        }

        public function get_normal_price()
        {
            return $this->get_prop('normal_price');
        }

        public function get_slug()
        {
            return $this->post->post_name;
        }

        public function get_photo()
        {
            return get_the_post_thumbnail_url($this->post->ID, 'post-thumbnail');
        }

        public function get_photo_thumbnail()
        {
            return get_the_post_thumbnail_url($this->post->ID, 'thumbnail');
        }

        public function get_url()
        {
            return get_permalink($this->post->ID);
        }
    }

endif;