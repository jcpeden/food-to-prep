<?php

class MTP_Dashboard_Admin_Support
{
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'admin_menu' ), 100 );
    }

    public function admin_menu(){
        $cpt = 'mp-order';

        add_submenu_page(
            'edit.php?post_type=' . $cpt,
            'Supports',
            'Supports',
            'manage_options',
            'ftp-supports',
            array( $this, 'plugin_page' )
        );
    }

    public function plugin_page(){
        ?>
        <div class="wrap">
            <h1>Supports</h1>

            <p>Technical support for clients is available via plugin <a href="https://wordpress.org/support/plugin/food-to-prep/" target="_blank">forum</a>.</p>
        </div>
        <?php
    }
}

new MTP_Dashboard_Admin_Support();