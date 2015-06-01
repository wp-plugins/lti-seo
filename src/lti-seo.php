<?php namespace Lti\Seo;

use Lti\Seo\Helpers\LTI_SEO_Helper;
use Lti\Seo\Plugin\Plugin_Settings;

/**
 * Main plugin class, loads all the goods
 *
 * A static instance is kept for testing purposes, and for summoning the beast easily
 * when working with the template.
 *
 * Class LTI_SEO
 * @package Lti\Seo
 */
class LTI_SEO {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @var      \Lti\SEO\Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @var      string The string used to uniquely identify this plugin.
	 */
	protected $LTI_SEO;

	/**
	 * The current version of the plugin.
	 *
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	public static $instance;

	/**
	 * @var \Lti\Seo\Plugin\Plugin_Settings
	 */
	private $settings;

	private $file_path;
	public $plugin_path;
	private $basename;
	/**
	 * @var \Lti\Seo\Admin
	 */
	public $admin;
	public $frontend;
	private $helper;
	/**
	 * @var \Lti\Seo\User
	 */
	private $user;
	public static $is_plugin_page = false;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 */
	public function __construct() {
		$this->file_path   = plugin_dir_path( __FILE__ );
		$this->name        = LTI_SEO_NAME;
		$this->plugin_path = LTI_SEO_PLUGIN_DIR;
		$this->basename    = LTI_SEO_PLUGIN_BASENAME;
		$this->settings    = get_option( "lti_seo_options" );

		if ( $this->settings === false || empty( $this->settings ) ) {
			$this->settings = new Plugin_Settings();
		}

		$this->load_dependencies();
		$this->set_locale();
		static::$is_plugin_page = ( filter_input( INPUT_GET, 'page' ) == 'lti-seo-options' );
	}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function get_settings() {
		return $this->settings;
	}

	public function get_helper() {
		return $this->helper;
	}


	private function load_dependencies() {
		require_once $this->file_path . 'helper.php';
		require_once $this->file_path . 'plugin/postbox.php';

		$this->loader = new Loader();
		$this->helper = new LTI_SEO_Helper( $this->settings );
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new i18n( $this->name );
		$plugin_i18n->set_domain( $this->get_plugin_name() );
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function define_admin_hooks() {
		$this->admin = new Admin( $this->name, $this->basename, $this->version, $this->settings, $this->plugin_path,
			$this->helper );

		$this->loader->add_action( 'admin_init', $this, 'activate' );
		$this->loader->add_filter( 'plugin_row_meta', $this->admin, 'plugin_row_meta', 10, 2 );

		$this->loader->add_action( 'admin_menu', $this->admin, 'admin_menu' );
		$this->loader->add_filter( 'plugin_action_links', $this->admin, 'plugin_actions', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $this->admin, 'add_meta_boxes' );
		$this->loader->add_action( 'save_post', $this->admin, 'save_post', 10, 3 );

		if ( apply_filters( 'lti_seo_allow_profile_social_settings', true ) ) {
			$this->user = new User( $this->settings, $this->helper );
			$this->loader->add_action( 'show_user_profile', $this->user, 'show_user_profile' );
			$this->loader->add_action( 'edit_user_profile', $this->user, 'show_user_profile' );
			$this->loader->add_action( 'personal_options_update', $this->user, 'personal_options_update', 10, 1 );
			$this->loader->add_action( 'edit_user_profile_update', $this->user, 'personal_options_update', 10, 1 );
		}

		if ( ( isset( $GLOBALS['pagenow'] ) && ( $GLOBALS['pagenow'] === 'post.php' || $GLOBALS['pagenow'] === 'post-new.php' ) ) || LTI_SEO::$is_plugin_page ) {
			$this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_scripts' );
		}

		if ( LTI_Seo::$is_plugin_page ) {
			$this->loader->add_filter( 'admin_footer_text', $this, 'admin_footer_text' );
			$this->loader->add_filter( 'update_footer', $this, 'update_footer', 15 );
		}
	}

	/**
	 * @return \Lti\Seo\Admin
	 */
	public function get_admin() {
		return $this->admin;
	}

	public function get_frontend() {
		return $this->frontend;
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function define_public_hooks() {
		$this->frontend = new Frontend( $this->name, $this->version, $this->settings,
			$this->helper );

		$this->loader->add_action( 'wp_head', $this->frontend, 'head' );
	}

	public function admin_footer_text( $text ) {
		if ( ! static::$is_plugin_page ) {
			return $text;
		}

		return sprintf( '<em>%s <a target="_blank" href="http://wordpress.org/support/view/plugin-reviews/%s#postform">%s</a></em>',
			ltint( 'admin.footer.feedback' ), LTI_SEO_NAME, ltint( 'admin.footer.review' ) );
	}

	public function update_footer( $text ) {
		if ( ! static::$is_plugin_page ) {
			return $text;
		}

		return sprintf( '<a target="_blank" title="%s" href="https://wordpress.org/plugins/%s/changelog/">%s %s</a>, %s',
			ltint( 'general.changelog' ), LTI_SEO_NAME, ltint( 'general.version' ), LTI_SEO_VERSION, $text );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 */
	public function run() {
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    \Lti\Seo\Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public static function activate() {
		Activator::activate();
	}

	public static function deactivate() {
		Deactivator::deactivate();
	}
}
