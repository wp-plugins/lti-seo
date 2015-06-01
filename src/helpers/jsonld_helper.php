<?php namespace Lti\Seo\Helpers;

class Wordpress_Helper_JSONLD extends JSONLD_Helper implements ICanHelpWithJSONLD {

	protected $schema;
	/**
	 * @var \Lti\Seo\Helpers\LTI_SEO_Helper
	 */
	protected $helper;

	public function __construct( ICanHelp $helper ) {
		$this->helper = $helper;
	}

	public function get( $setting ) {
		return $this->helper->get( $setting );
	}

	public function get_current_url() {
		return $this->helper->get_current_url();
	}

	/**
	 * @see Generic_Helper::$target_property
	 * @return string
	 */
	public function get_target_property() {
		return $this->target_property;
	}

	public function set_target_property( $property ) {
		$this->target_property = $property;
	}

	public function get_schema_org( $setting ) {
		$schema = null;
		switch ( $this->schema ) {
			case "CreativeWork":
				$schema = new Schema_Router_CreativeWork( $this->helper, $this );
				break;
			case 'Person':
				$schema = new Schema_Router_Person( $this->helper );
				break;
			case 'Organization':
				$schema = new Schema_Router_Organization( $this->helper );
				break;
			case 'Article':
			case 'BlogPosting':
			case 'NewsArticle':
			case 'ScholarlyArticle':
			case 'TechArticle':
				$schema = new Schema_Router_Article( $this->helper );
				break;
		}

		if ( is_object( $schema ) ) {
			return $schema->get( $setting );
		}

		return null;
	}

	public function get_schema_org_setting( $setting ) {
		return $this->helper->get( 'jsonld_type_' . $setting );
	}

	public function get_search_action_type() {
		return 'Lti\Seo\Generators\WordpressSearchAction';
	}


}

class Schema_Router {
	protected $wordpress_helper;
	protected $jsonld_helper;

	/**
	 * @param ICanHelp|\Lti\Seo\Helpers\LTI_SEO_Helper $helper
	 * @param ICanHelpWithJSONLD $jsonld
	 */
	public function __construct( ICanHelp $helper, ICanHelpWithJSONLD $jsonld = null ) {
		$this->wordpress_helper = $helper;
		$this->jsonld_helper    = $jsonld;
	}
}

class Schema_Router_CreativeWork extends Schema_Router {

	public function get( $setting ) {
		switch ( $setting ) {
			case 'headline':
				return $this->wordpress_helper->get_title();
			case 'keywords':
				return implode( ',', $this->wordpress_helper->get_keywords() );
			case 'thumbnailUrl':
				return $this->wordpress_helper->get_thumbnail_url();
			case 'inLanguage':
				return $this->wordpress_helper->get_language();
			case 'datePublished':
				return lti_iso8601_date( $this->wordpress_helper->get_post_info( 'post_date' ) );
			case 'dateModified':
				return lti_iso8601_date( $this->wordpress_helper->get_post_info( 'post_modified' ) );
			case 'copyrightYear':
				return lti_mysql_date_year( $this->wordpress_helper->get_post_info( 'post_date' ) );
			case 'author':
				if ( $this->wordpress_helper->get( 'jsonld_entity_support' ) == true ) {
					$type = $this->wordpress_helper->get( 'jsonld_entity_type' );

					if ( $type == 'Person' ) {
						return array(
							'Person' => $this->jsonld_helper
						);
					}
				}
				break;
			case 'publisher':
				if ( $this->wordpress_helper->get( 'jsonld_entity_support' ) == true ) {
					$type = $this->wordpress_helper->get( 'jsonld_entity_type' );
					if ( $type == 'Organization' ) {
						return array(
							'Organization' => $this->jsonld_helper
						);
					}
				}
				break;
		}

		return null;
	}
}

class Schema_Router_Person extends Schema_Router {
	public function get( $setting ) {
		switch ( $setting ) {
			case 'name':
				return $this->wordpress_helper->get_user_key( 'display_name' );
			case 'url':
				return $this->wordpress_helper->get_user_key( 'user_url' );
			case 'workLocation:longitude':
				return $this->wordpress_helper->get_user_meta_key( 'lti_work_longitude' );
			case 'workLocation:latitude':
				return $this->wordpress_helper->get_user_meta_key( 'lti_work_latitude' );
			case 'jobTitle':
				return $this->wordpress_helper->get_user_meta_key( 'lti_job_title' );
			case 'email':
				return $this->wordpress_helper->get_user_meta_key( 'lti_public_email' );
			case 'sameAs':
				return $this->wordpress_helper->get_all_author_social_info();
		}

		return null;
	}
}

class Schema_Router_Organization extends Schema_Router {

	public function get( $setting ) {
		switch ( $setting ) {
			case 'name':
				return $this->wordpress_helper->get( 'jsonld_org_name' );
			case 'url':
				return $this->wordpress_helper->get( 'jsonld_org_website_url' );
			case 'alternateName':
				return $this->wordpress_helper->get( 'jsonld_org_alternate_name' );
			case 'logo':
				return $this->wordpress_helper->get( 'jsonld_org_logo_url' );
			case 'sameAs':
				return $this->wordpress_helper->get_social_urls();
		}

		return null;
	}
}

class Schema_Router_Article extends Schema_Router {
	public function get( $setting ) {
		switch ( $setting ) {
			case 'articleSection':
				return $this->wordpress_helper->get_categories();
			case 'wordCount':
				return $this->wordpress_helper->get_post_meta_key( 'word_count' );
			case 'Person:url':
				return $this->wordpress_helper->get_user_key( 'user_url' );
		}

		return null;
	}

}
