<?php namespace Lti\Seo\Generators;

use Lti\Seo\Helpers\ICanHelpWithJSONLD;

/**
 * Hooks JSON-LD generators into Wordpress
 *
 * Class Wordpress_JSON_LD
 * @package Lti\Seo\Generators
 */
class Wordpress_JSON_LD extends JSON_LD {
	/**
	 * @var ICanHelpWithJSONLD|\Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	protected $helper;

	public function json_ld() {
		do_action( 'lti_seo_json_ld' );
	}
}

class Frontpage_JSON_LD extends Wordpress_JSON_LD {

	public function __construct( ICanHelpWithJSONLD $helper ) {
		parent::__construct( $helper );

		if ( $this->helper->get( 'jsonld_website_support' ) ) {
			if ( $this->helper->get( 'jsonld_website_type' ) == "Blog" ) {
				add_action( 'lti_seo_json_ld', array( $this, 'make_Blog' ) );
			} else {
				add_action( 'lti_seo_json_ld', array( $this, 'make_WebSite' ) );
			}
		} else {
			if ( $this->helper->get( 'jsonld_entity_support' ) ) {
				$type = $this->helper->get( 'jsonld_entity_type' );
				switch ( $type ) {
					case "Person":
					case "Organization":
						add_action( 'lti_seo_json_ld', array( $this, 'make_' . $type ) );
				}
			}
		}
	}
}

class Singular_JSON_LD extends Wordpress_JSON_LD {
	public function __construct( ICanHelpWithJSONLD $helper ) {
		parent::__construct( $helper );

		if ( $this->helper->get( 'jsonld_post_support' )==true ) {
			$type = $this->helper->get( 'jsonld_post_type' );
			switch ( $type ) {
				case "Article":
				case "BlogPosting":
				case "NewsArticle":
				case "ScholarlyArticle":
				case "TechArticle":
					add_action( 'lti_seo_json_ld', array( $this, 'make_WP_' . $type ) );
			}
		}
	}
}

class Author_JSON_LD extends Wordpress_JSON_LD {
	public function __construct( ICanHelpWithJSONLD $helper ) {
		parent::__construct( $helper );

		if ( $this->helper->get( 'jsonld_author_support' )==true ) {
			add_action( 'lti_seo_json_ld', array( $this, 'make_Person' ) );
		}
	}
}

class Page_JSON_LD extends Wordpress_JSON_LD {
	public function __construct( ICanHelpWithJSONLD $helper ) {
		parent::__construct( $helper );

		if ( $this->helper->get( 'jsonld_page_support' )==true ) {
			add_action( 'lti_seo_json_ld', array( $this, 'make_WebPage' ) );
		}
	}
}

class Search_JSON_LD extends Wordpress_JSON_LD {
	public function __construct( ICanHelpWithJSONLD $helper ) {
		parent::__construct( $helper );

		if ( $this->helper->get( 'jsonld_page_support' )==true ) {
			add_action( 'lti_seo_json_ld', array( $this, 'make_SearchResultsPage' ) );
		}
	}
}



