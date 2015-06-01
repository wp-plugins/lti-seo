<?php namespace Lti\Seo\Generators;
use Lti\Seo\Helpers\ICanHelpWithJSONLD;

/**
 * These classes do anything wordpress specific when dealing with JSON-LD markup
 *
 * Relies on the lti/json-ld composer package
 * @link https://packagist.org/packages/lti/json-ld
 *
 */


/**
 * Class WP_Article
 * @package Lti\Seo\Generators
 */
class WP_Article extends Article {

	protected $realType = "Article";

	public function get_author() {
		static::$helper->set_schema( 'Person' );
		$user_website = static::$helper->get_schema_org( 'url' );
		if ( ! empty( $user_website ) && ! is_null( $user_website ) ) {
			$thing = new Thing( array( 'url' => $user_website) );
			$thing->set_type( 'Person' );
			$this->author = $thing;
		}
	}

	public function get_publisher() {
		static::$helper->set_schema( 'Organization' );
		$org_url = static::$helper->get_schema_org( 'url' );
		if ( ! empty( $org_url ) && ! is_null( $org_url ) ) {
			$thing = new Thing( array( 'url' => $org_url ) );
			$thing->set_type( 'Organization' );
			$this->publisher = $thing;
		}
	}
}


class WP_BlogPosting extends WP_Article {
	protected $realType = "BlogPosting";
}

class WP_NewsArticle extends WP_Article {
	protected $realType = "NewsArticle";
}

class WP_ScholarlyArticle extends WP_Article {
	protected $realType = "ScholarlyArticle";
}

class WP_TechArticle extends WP_Article {
	protected $realType = "TechArticle";
}

class WordpressSearchAction extends SearchAction implements ICanSearch {
	protected $realType = "SearchAction";
	/**
	 * @param \Lti\Seo\Helpers\ICanHelpWithJSONLD $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		$this->target        = sprintf( "%s?s={search_term}", $helper->get_current_url() );
		$query_type          = $this->get_query_type();
		$this->{$query_type} = "required name=search_term";
	}

	public function get_query_type() {
		return "query-input";
	}
}
