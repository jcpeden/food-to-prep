<?php


if (!class_exists('MTP_Settings')) :

    /**
     *
     * Settings Class
     *
     * Instance all setting of plugin usable
     *
     */

    class MTP_Settings
    {
        private $options;

        public function __construct()
        {
            add_action('admin_menu', array($this, 'setting_plugin_setup_menu'));
        }

        function setting_plugin_setup_menu()
        {
            add_menu_page(
                'Food to Prep',
                'Food to Prep',
                'edit_posts',
                'meal-prep',
                array($this, 'meal_prep_init'),
                'dashicons-carrot',
                '50'
            );

        }

        function meal_prep_init()
        {
            ?>
            <h1>Food To Prep</h1>
            <?php
        }

        function create_setting_page()
        {
            $this->options = get_option('meal-prep-option-setting');
            ?>
            <div class="wrap">
                <?php settings_errors(); ?>
                <h1 style="font-weight: 700;">Food To Prep Settings</h1>
                <form method="post" action="options.php" novalidate="novalidate">
                    <?php
                    settings_fields('meal-prep-option-group');
                    do_settings_sections('meal-prep-setting-admin');
                    submit_button();
                    ?>
                </form>

            </div>
            <?php
        }
    }

    new MTP_Settings();

endif;
