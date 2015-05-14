<?php namespace Lti\Seo;

use Lti\Seo\Helpers\ICanHelp;
use Lti\Seo\Plugin\Plugin_Settings;


/**
 * Takes care of displaying SEO tags in pages
 *
 * Class Frontend
 * @package Lti\Seo
 */
class Frontend {

	private $plugin_name;
	private $version;

	private $class_pattern = "Lti\\Seo\\Generators\\%s_%s";

	/**
	 * @var \Lti\Seo\Plugin\Plugin_Settings
	 */
	private $settings;

	/**
	 * @var ICanHelp|\Lti\Seo\Helpers\Wordpress_Helper
	 */
	private $helper;

	/**
	 * @param string $plugin_name
	 * @param string $version
	 * @param Plugin_Settings $settings
	 * @param ICanHelp $helper
	 */
	public function __construct(
		$plugin_name,
		$version,
		Plugin_Settings $settings,
		ICanHelp $helper
	) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->settings    = $settings;
		$this->helper      = $helper;
	}


	/**
	 * Displays all the header tags
	 *
	 * The main class calls this method as an action hook for wp_head
	 *
	 */
	public function head() {
		$this->helper->init();

		$this->hook_functionality( 'Link_Rel' );

		if ( $this->settings->get( 'description_support' ) == true || $this->settings->get( 'frontpage_description' ) == true ) {
			$this->hook_functionality( 'Description' );
		}

		if ( $this->settings->get( 'keyword_support' ) == true || $this->settings->get( 'frontpage_keyword' ) == true ) {
			$this->hook_functionality( 'Keyword' );
		}

		/**
		 * Open Graph and Twitter tag generators need the type of page (singular, frontpage)
		 * plus the post format (gallery, aside, etc.) so they can parse the images within the post
		 */
		if ( $this->settings->get( 'open_graph_support' ) == true ) {
			$this->hook_functionality( 'Open_Graph', 'page_post_format' );
		}

		if ( $this->settings->get( 'twitter_card_support' ) == true ) {
			$this->hook_functionality( 'Twitter_Card', 'page_post_format' );
		}

		$this->hook_functionality( 'Robot' );


		$class = sprintf( $this->class_pattern, call_user_func( array( $this->helper, 'page_type' ) ), 'JSON_LD' );
		if ( class_exists( $class ) ) {
			$json_ld = new $class( $this->helper );

			add_action( 'lti_seo_head', array( $json_ld, 'json_ld' ) );
		}
		/**
		 * Allow filtering of the html comment to apply for seo tags
		 *
		 * @api string
		 *
		 */
		$seo_comment = apply_filters( 'lti_seo_header_comment', 'SEO' );
		if ( ! empty( $seo_comment ) ) {
			echo sprintf( "<!-- %s -->" . PHP_EOL, $seo_comment );
		}
		do_action( 'lti_seo_head' );
		if ( ! empty( $seo_comment ) ) {
			echo sprintf( "<!-- END %s -->" . PHP_EOL . PHP_EOL, $seo_comment );
		}
	}

	/**
	 * Takes care of instantiating the proper class depending on the functionality
	 *
	 * @param $type
	 * @param string $format
	 */
	private function hook_functionality( $type, $format = 'page_type' ) {
		$class = sprintf( $this->class_pattern, call_user_func( array( $this->helper, $format ) ), $type );
		if ( class_exists( $class ) ) {
			$og = new $class( $this->helper );
			add_action( 'lti_seo_head', array( $og, 'display_tags' ) );
		}
	}

}
