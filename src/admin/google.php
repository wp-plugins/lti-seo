<?php namespace Lti\Seo;

use Lti\Google\Google_Helper;
use Lti\Seo\Helpers\ICanHelp;

/**
 * Class Admin_Google
 * @package Lti\Seo
 */
class Admin_Google
{
    /**
     * I haven't tested the PHP Google code without curl, so let's say we need curl.
     * @var bool
     */
    public $can_send_curl_requests;
    /**
     * @var array
     */
    public $error;

    /**
     * @var \Lti\Google\Google_Helper
     */
    public $helper;

    /**
     * @param Admin $admin
     * @param ICanHelp|\Lti\Seo\Helpers\Wordpress_Helper $wp_helper
     */
    public function __construct( Admin $admin, ICanHelp $wp_helper )
    {
        $this->wp_helper              = $wp_helper;
        $this->admin                  = $admin;
        $this->can_send_curl_requests = function_exists( 'curl_version' );
        if ($this->can_send_curl_requests === true) {
            $this->helper = new Google_Helper( array(
                'https://www.googleapis.com/auth/webmasters',
                'https://www.googleapis.com/auth/siteverification',
            ), LTI_SEO_NAME );


            $access_token = $this->admin->get_setting( 'google_access_token' );
            if ( ! is_null( $access_token ) && ! empty( $access_token )) {
                $this->helper->set_access_token( $access_token );

                //If we can't renew our token anymore, we discard it from the settings.
                if ($this->helper->assess_token_validity() !== true) {
                    $this->admin->remove_setting( 'google_access_token' );
                }
            }
        }
    }

    /**
     * Returns basic info about the current site, whether it exists and what the user permissions are
     *
     * @return \stdClass
     */
    public function get_site_info()
    {
        $this->helper->init_site_service( $this->wp_helper->get_home_url() );
        $obj       = new \stdClass();
        $obj->site = $this->helper->get_site_service();
        try {
            $obj->site->request_site_info();
            $obj->is_listed = true;
        } catch ( \Google_Service_Exception $e ) {
            $obj->is_listed = false;
        }

        return $obj;
    }

    /**
     * Authentication
     *
     * @param $post_variables
     */
    public function google_auth( $post_variables )
    {
        try {
            $this->admin->set_setting( 'google_access_token',
                $this->helper->authenticate( $post_variables['google_auth_token'] ) );
            $this->admin->set_message( ltint( 'msg.google_logged_in' ) );
        } catch ( \Google_Auth_Exception $e ) {
            $this->error = array(
                'error'           => ltint( 'err.google_auth_failure' ),
                'google_response' => $e->getMessage()
            );
            $this->admin->remove_setting( 'google_access_token' );
        }
    }

    /**
     * Logout
     *
     */
    public function google_logout()
    {
        $this->admin->remove_setting( 'google_access_token' );
        $this->admin->set_message( ltint( 'google.msg.logout' ) );
        $this->helper->revoke_token();
    }

    /**
     * Adding a site to the Google search console
     *
     * @return null
     */
    public function google_add()
    {
        try {
            $this->helper->get_site_service()->add_site();
            $this->admin->set_message( ltint( 'msg.google_added' ) );
        } catch ( \Google_Auth_Exception $e ) {
            $this->error = array(
                'error'           => ltint( 'err.google_add_failure' ),
                'google_response' => $e->getMessage()
            );

            return null;
        }
        $this->google_verify();


    }

    /**
     * Verifying an existing site on the Google search console
     *
     */
    public function google_verify()
    {
        try {
            //We fetch a meta tag that'll be put in every page's header so Google can verify the site.
            $site_service     = $this->helper->get_site_service();
            $activation_token = $site_service->get_verification_token();
            $this->admin->set_setting( 'google_meta_activation', $activation_token );

            //We save the token so when Google fetches the page next time, the token will be in the page's header.
            update_option( 'lti_seo_options', $this->admin->get_settings() );

            $site_service->verify_site();

            //If we reach this part of the code without an exception being thrown, the site's been activated
            //No need to keep the activation meta tag anymore.
            $this->admin->remove_setting( 'google_meta_activation' );
            update_option( 'lti_seo_options', $this->admin->get_settings() );
            $this->admin->set_message( ltint( 'msg.google_verified' ) );
        } catch ( \Google_Service_Exception $e ) {
            $this->error = array(
                'error'           => ltint( 'err.google_verify_failure' ),
                'google_response' => $e->getMessage()
            );
        }
    }

    /**
     * Gets the class that wraps our Google Client
     */
    public function get_helper()
    {
        return $this->helper;
    }
}
