<?php namespace Lti\Seo;

use Lti\Seo\Generators\Robot;
use Lti\Seo\Helpers\ICanHelp;
use Lti\Seo\Plugin\Fields;
use Lti\Seo\Plugin\Plugin_Settings;
use Lti\Seo\Plugin\Postbox_Values;
use Lti\Wordpress\LTI_Menu;

/**
 * Deals with everything that happens in the admin screen and postbox,
 * and defines custom user profile fields
 *
 * Class Admin
 * @package Lti\Seo
 */
class Admin
{

    /**
     * @var string Tracks page type so we can display error/warning messages
     */
    private $page_type = 'edit';
    /**
     * @var string Contains messages to be displayed after saves/resets
     */
    protected $message = '';
    /**
     * @var string In case we forget our own name in the heat of the battle
     */
    private $plugin_name;
    /**
     * @var string Plugin version
     */
    private $version;
    /**
     * @var \Lti\Seo\Plugin\Plugin_Settings
     */
    protected $settings;
    /**
     * @var \Lti\Seo\Plugin\Postbox_Values
     */
    private $box_values;
    /**
     * @var array Types of posts where a postbox has to be added
     */
    private $unsupported_post_types = array( 'attachment' );
    /**
     * @var string Helps defining what kind of settings to use (settings or postbox values)
     */
    private $current_page = "admin";
    /**
     * @var \Lti\Seo\Helpers\LTI_SEO_Helper
     */
    private $helper;

    /**
     * @var Admin_Google
     */
    private $google;


    /**
     * @param $plugin_name
     * @param $plugin_basename
     * @param $version
     * @param Plugin_Settings $settings
     * @param $plugin_path
     * @param ICanHelp $helper
     */
    public function __construct(
        $plugin_name,
        $plugin_basename,
        $version,
        Plugin_Settings $settings,
        $plugin_path,
        ICanHelp $helper
    ) {

        $this->plugin_name     = $plugin_name;
        $this->plugin_basename = $plugin_basename;
        $this->version         = $version;
        $this->admin_dir_url   = plugin_dir_url( __FILE__ );
        $this->admin_dir       = dirname( __FILE__ );
        $this->plugin_dir      = $plugin_path;
        $this->plugin_dir_url  = plugin_dir_url( $plugin_path . '/index.php' );
        $this->settings        = $settings;
        $this->helper          = $helper;

        if ( ! LTI_Seo::$is_plugin_page) {
            return;
        }
        $this->google = new Admin_Google( $this, $this->helper );

    }

    /**
     * Adding our CSS stylesheet
     */
    public function enqueue_styles()
    {
        wp_enqueue_style( $this->plugin_name, $this->plugin_dir_url . 'assets/dist/css/lti_seo_admin.css',
            array( 'thickbox' ), $this->version,
            'all' );
    }

    /**
     * Adding our JS
     * Defining translated values for javascript to use
     */
    public function enqueue_scripts()
    {
        //We need the image loader
        wp_enqueue_media();
        wp_enqueue_script( $this->plugin_name, $this->plugin_dir_url . 'assets/dist/js/lti_seo_admin.js',
            array( 'jquery' ),
            $this->version,
            false );
        wp_localize_script( $this->plugin_name, 'lti_seo_i8n', array( 'use_img' => ltint( 'general.use_image' ) ) );
    }

    /**
     * Adding "Help" button to the admin screen
     */
    public function admin_menu()
    {
        //If the LTI menu item hasn't been created
        if (is_null( LTI_Menu::$main_menuitem )) {
            LTI_Menu::$main_menuitem = 'lti-seo-options';
            add_menu_page( 'LTI', 'LTI', 'manage_options', LTI_Menu::$main_menuitem,
                array( $this, 'options_page' ),
                LTI_Menu::$image_base64_url );
        }
        $page = add_submenu_page( LTI_Menu::$main_menuitem, ltint( 'admin.menu_title' ), ltint( 'admin.menu_item' ),
            'manage_options', 'lti-seo-options', array( $this, 'options_page' ) );
        add_action( 'load-' . $page, array( $this, 'wp_help_menu' ) );
    }

    /**
     * Defining tabs for the help menu
     * (Button "help" on the top right side of the screen)
     *
     * @see Admin::admin_menu
     */
    public function wp_help_menu()
    {
        include $this->admin_dir . '/partials/help_menu.php';
        $screen = get_current_screen();
        $menu   = new \Lti_Seo_Help_Menu();
        $screen->add_help_tab( array(
            'id'      => 'general_hlp_welcome',
            'title'   => ltint( 'general_hlp_welcome' ),
            'content' => $menu->welcome_tab()
        ) );
        $screen->add_help_tab( array(
            'id'      => 'general_hlp_general',
            'title'   => ltint( 'general_hlp_general' ),
            'content' => $menu->general_tab()
        ) );
        $screen->add_help_tab( array(
            'id'      => 'general_hlp_frontpage',
            'title'   => ltint( 'general_hlp_frontpage' ),
            'content' => $menu->frontpage_tab()
        ) );
        $screen->add_help_tab( array(
            'id'      => 'general_hlp_social',
            'title'   => ltint( 'general_hlp_social' ),
            'content' => $menu->social_tab()
        ) );
        $screen->add_help_tab( array(
            'id'      => 'general_hlp_google',
            'title'   => ltint( 'general_hlp_google' ),
            'content' => $menu->google_tab()
        ) );

        $screen->set_help_sidebar(
            $menu->sidebar()
        );
    }

    /**
     * Adds a LTI SEO button to the WP "Settings" menu item in the admin sidebar
     *
     * @param $links
     * @param $file
     *
     * @return mixed
     */
    public function plugin_actions( $links, $file )
    {
        if ($file == 'lti-seo/lti-seo.php' && function_exists( "admin_url" )) {
            array_unshift( $links,
                '<a href="' . $this->get_admin_slug() . '">' . ltint( 'general.settings' ) . '</a>' );
        }

        return $links;
    }

    public static function get_admin_slug()
    {
        return admin_url( 'admin.php?page=lti-seo-options' );
    }

    /**
     * Renders the admin view
     *
     */
    public function options_page()
    {
        $post_variables = $this->helper->filter_var_array( $_POST );

        $update_type = '';
        /**
         * Each submit button in the form has a particular name
         * helping us figure out what kind of processing to do (if any)
         * on top of saving settings
         */
        switch (true) {
            case isset( $post_variables['lti_seo_update'] ):
                $update_type = "normal";
                break;
            case isset( $post_variables['lti_seo_google_auth'] ):
                $update_type = "google_auth";
                break;
            case isset( $post_variables['lti_seo_google_add'] ):
                $update_type = "google_add";
                break;
            case isset( $post_variables['lti_seo_google_verify'] ):
                $update_type = "google_verify";
                break;
            case isset( $post_variables['lti_seo_google_logout'] ):
                $update_type = "google_logout";
                break;
            /**
             * Settings reset handler
             */
            case isset( $post_variables['lti_seo_reset'] ):
                $this->settings = new Plugin_Settings();
                update_option( 'lti_seo_options', $this->settings );
                $this->update_global_post_fields( array(), true );

                $this->page_type = "lti_reset";
                $this->message   = ltint( 'opt.msg.reset' );
                break;
            default:
                $this->page_type = "lti_edit";
        }

        if (isset( $post_variables['lti_seo_token'] ) && ! empty( $update_type )) {
            $this->validate_input( $post_variables, $update_type );
        }

        include $this->admin_dir . '/partials/options-page.php';
    }

    /**
     * User input validation
     * Compares old values with new because some fields have a global impact,
     * including values that users set in postboxes
     *
     * @param $data
     */
    public function validate_input( $data, $update_type )
    {
        if (wp_verify_nonce( $data['lti_seo_token'], 'lti_seo_options' ) !== false) {
            unset( $data['_wpnonce'], $data['option_page'], $data['_wp_http_referer'] );
            $oldSettings         = $this->settings;
            $google_access_token = $this->settings->get( 'google_access_token' );
            $this->settings      = $this->settings->save( $data );

            /**
             * We save values into a new settings object, and our google access token, when set, isn't a part of the form
             * so we make sure it's saved if it existed before this form submission.
             */
            if ( ! is_null( $google_access_token )) {
                $this->settings->set( 'google_access_token', $google_access_token );
            }

            /**
             * We need to monitor settings changes that could affect post settings
             * For example: a change in robots settings affects the global settings but also the post robots settings.
             */
            if ($this->settings != $oldSettings) {
                $changed = $this->settings->compare( $oldSettings );

                if ( ! empty( $changed )) {
                    $this->update_global_post_fields( $changed );
                }
            }

            $this->page_type = "lti_update";

            if (method_exists( $this->google, $update_type )) {
                $this->google->helper->init_site_service( $this->helper->get_home_url() );
                call_user_func( array( $this->google, $update_type ), $data );
            } else {
                $this->message = ltint( "opt.msg.update_ok" );
            }

            update_option( 'lti_seo_options', $this->settings );
        } else {
            $this->page_type = "lti_error";
            $this->message   = ltint( "opt.msg.error_token" );
        }
    }

    /**
     * Adds postboxes to posts
     *
     */
    public function add_meta_boxes()
    {

        //Checking if enough settings are enabled to warrant the activation of a postbox
        if ($this->settings->postbox_is_required()) {
            $supported_post_types = $this->get_supported_post_types();

            foreach ($supported_post_types as $supported_post_type) {
                add_meta_box(
                    'lti-seo-metadata-box',
                    ltint( 'admin.meta_box' ),
                    array( $this, 'metadata_box' ),
                    $supported_post_type,
                    'advanced',
                    'high'
                );
            }
        }
    }

    /**
     * Displays postbox values
     *
     * @param \WP_Post $post
     */
    public function metadata_box( \WP_Post $post )
    {
        $this->box_values = get_post_meta( $post->ID, "lti_seo", true );

        /**
         * When the post is created, we need to set robot values according to what was set
         * in the admin screen
         */
        if (empty( $this->box_values )) {
            $this->box_values = new Postbox_Values( array() );
            $robot            = new Robot( $this->helper );
            //We get an array of robot settings that we copy as box values
            $robot_settings = $robot->get_robot_setting( 'robot_support', 'post_' );
            foreach ($robot_settings as $setting) {
                $this->box_values->set( 'post_robot_' . $setting, true );
            }
        }

        //We add keyword suggestions if the field is empty
        if ($this->settings->get( 'keyword_support' ) === true) {
            $keyword_text = $this->box_values->get( 'keywords' );
            if (is_null( $keyword_text ) || empty( $keyword_text )) {
                $keywords = $this->helper->get_keywords();
                if ( ! empty( $keywords )) {
                    $this->box_values->set( 'keywords_suggestion',
                        implode( ',', $keywords ) );

                }
            }
        }
        $this->set_current_page( 'post-edit' );
        include $this->admin_dir . '/partials/postbox.php';
    }

    /**
     * Updating settings in LTI SEO postboxes if any "global scope" settings changed.
     * We need to go through each postmeta and update them with new settings
     *
     * @param array $changed
     * @param bool $reset
     */
    public function update_global_post_fields( $changed = array(), $reset = false )
    {
        /**
         * @var \wpdb $wpdb
         */
        global $wpdb;
        //@TODO: check whether this can be covered by some wp method
        $sql = 'SELECT ' . $wpdb->posts . '.ID,' . $wpdb->postmeta . '.meta_value  FROM ' . $wpdb->posts . '
				LEFT JOIN ' . $wpdb->postmeta . ' ON (' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id AND ' . $wpdb->postmeta . '.meta_key = "lti_seo")
				WHERE ' . $wpdb->posts . '.post_type = "post" AND ' . $wpdb->posts . '.post_status!="auto-draft"';

        $results = $wpdb->get_results( $sql );

        if (is_array( $results )) {
            foreach ($results as $result) {
                $postbox_values = $result->meta_value;
                if ( ! is_null( $postbox_values ) && ! $reset) {
                    $postbox_values = unserialize( $postbox_values );
                } else {
                    $postbox_values = new Postbox_Values( new \stdClass() );
                }

                foreach ($changed as $changedKey => $changedValue) {
                    if (isset( $postbox_values->{$changedKey} ) && $postbox_values->{$changedKey} instanceof Fields) {
                        $postbox_values->{$changedKey}->value = $changedValue;
                    }
                }

                update_post_meta( $result->ID, 'lti_seo', $postbox_values );
            }
        }
    }

    /**
     * Determines what post types need a postbox
     * Only posts and pages are supported and we could have hardcoded the values,
     * but hey, we're extra mile kinda guys!
     *
     * @return array
     */
    public function get_supported_post_types()
    {
        $post_types = get_post_types( array( 'public' => true, 'show_ui' => true ) );

        return array_diff( $post_types, $this->unsupported_post_types );
    }

    /**
     * Returns the proper settings to apply depending on whether we're in the settings screen
     * or editing a post/page.
     *
     * @return \Lti\Seo\Plugin\Plugin_Settings
     */
    public function get_form_values()
    {
        switch ($this->current_page) {
            case "post-edit":
                return $this->box_values;
        }

        return $this->settings;
    }

    /**
     * Saves posts
     *
     * @param int $post_ID
     * @param \WP_Post $post
     * @param int $update
     */
    public function save_post( $post_ID, $post, $update )
    {
        $post_variables = $this->helper->filter_input( INPUT_POST, 'lti_seo' );

        if ( ! is_null( $post_variables )) {
            $post_variables = $this->helper->filter_var_array( $_POST['lti_seo'] );
            if ( ! is_null( $post_variables ) && ! empty( $post_variables )) {
                update_post_meta( $post_ID, 'lti_seo', new Postbox_Values( (object) $post_variables ) );
            }
        }
    }

    /**
     * Adds extra links for the LTI SEO item in plugins.php
     *
     * @param $links
     * @param $file
     *
     * @return array
     */
    public function plugin_row_meta( $links, $file )
    {
        if ($file == $this->plugin_basename) {
            $links[] = '<a href="http://dev.linguisticteam.org/lti-seo-help/" target="_blank">' . ltint( 'admin.help' ) . '</a>';
            $links[] = '<a href="https://github.com/DeCarvalhoBruno/lti-wp-seo" target="_blank">' . ltint( 'admin.contribute' ) . '</a>';
        }

        return $links;
    }

    public function set_current_page( $page )
    {
        $this->current_page = $page;
    }

    public function get_settings()
    {
        return $this->settings;
    }

    public function get_page_type()
    {
        return $this->page_type;
    }

    public function get_message()
    {
        return $this->message;
    }

    public function set_message( $message )
    {
        $this->message = $message;
    }

    public function get_setting( $setting )
    {
        return $this->settings->get( $setting );
    }

    public function remove_setting( $setting )
    {
        $this->settings->remove( $setting );
    }

    public function set_setting( $setting, $value, $type = 'Text' )
    {
        $this->settings->set( $setting, $value, $type );
    }
}