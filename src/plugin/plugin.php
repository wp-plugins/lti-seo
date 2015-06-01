<?php namespace Lti\Seo\Plugin;

/**
 * Loads all the default plugin values
 *
 * An object of this type is inserted in the options database table
 * whenever plugin settings are saved.
 *
 * Class Defaults
 * @package Lti\Seo\Plugin
 */
class Defaults {
	public $values;

	public function __construct() {
		$this->values = array(
			new def( "version", 'Text', LTI_SEO_VERSION ),
			new def( 'link_rel_support', 'Checkbox', false ),
			new def( 'link_rel_canonical', 'Checkbox', false ),
			new def( 'link_rel_author', 'Checkbox', false ),
			new def( 'link_rel_publisher', 'Checkbox', false ),
			new def( 'keyword_support', 'Checkbox', false ),
			new def( 'keyword_tag_based', 'Checkbox', false ),
			new def( 'keyword_cat_based', 'Checkbox', false ),
			new def( 'robot_support', 'Checkbox', false ),
			new def( 'post_robot_noindex', 'Checkbox', false, true ),
			new def( 'post_robot_nofollow', 'Checkbox', false, true ),
			new def( 'post_robot_noodp', 'Checkbox', false, true ),
			new def( 'post_robot_noydir', 'Checkbox', false, true ),
			new def( 'post_robot_noarchive', 'Checkbox', false, true ),
			new def( 'post_robot_nosnippet', 'Checkbox', false, true ),
			new def( 'robot_noindex', 'Checkbox', false ),
			new def( 'robot_nofollow', 'Checkbox', false ),
			new def( 'robot_noodp', 'Checkbox', false ),
			new def( 'robot_noydir', 'Checkbox', false ),
			new def( 'robot_noarchive', 'Checkbox', false ),
			new def( 'robot_nosnippet', 'Checkbox', false ),
			new def( 'robot_date_based', 'Checkbox', false ),
			new def( 'robot_cat_based', 'Checkbox', false ),
			new def( 'robot_tag_based', 'Checkbox', false ),
			new def( 'robot_tax_based', 'Checkbox', false ),
			new def( 'robot_author_based', 'Checkbox', false ),
			new def( 'robot_search_based', 'Checkbox', false ),
			new def( 'robot_notfound_based', 'Checkbox', false ),
			new def( 'description_support', 'Checkbox', false ),
			new def( 'open_graph_support', 'Checkbox', false ),
			new def( 'facebook_publisher', 'Url' ),
			new def( 'frontpage_description', 'Checkbox', false ),
			new def( 'frontpage_description_text', 'Text' ),
			new def( 'frontpage_robot', 'Checkbox', false ),
			new def( 'frontpage_robot_noindex', 'Checkbox', false ),
			new def( 'frontpage_robot_nofollow', 'Checkbox', false ),
			new def( 'frontpage_robot_noodp', 'Checkbox', false ),
			new def( 'frontpage_robot_noydir', 'Checkbox', false ),
			new def( 'frontpage_robot_noarchive', 'Checkbox', false ),
			new def( 'frontpage_robot_nosnippet', 'Checkbox', false ),
			new def( 'frontpage_keyword', 'Checkbox', false ),
			new def( 'frontpage_keyword_text', 'Text' ),
			new def( 'frontpage_social_img_url', 'Url' ),
			new def( 'frontpage_social_img_id', 'Text' ),
			new def( 'jsonld_website_support', 'Checkbox', false ),
			new def( 'jsonld_website_type', 'Radio',
				array( 'default' => 'WebSite', 'choice' => array( 'WebSite', 'Blog' ) ) ),
			new def( 'jsonld_entity_support', 'Checkbox', false ),
			new def(
				'jsonld_entity_type',
				'Radio',
				array( 'default' => 'Person', 'choice' => array( 'Person', 'Organization' ) )
			),
			new def( 'jsonld_org_name', 'Text' ),
			new def( 'jsonld_person_wp_userid', 'Text' ),
			new def( 'jsonld_org_logo_url', 'Url' ),
			new def( 'jsonld_org_logo_id', 'Text' ),
			new def( 'jsonld_org_alternate_name', 'Text' ),
			new def( 'jsonld_org_website_url', 'Url' ),
			new def( 'jsonld_page_support', 'Checkbox' ),
			new def( 'jsonld_post_support', 'Checkbox' ),
			new def( 'jsonld_post_type', 'Radio', array(
				'default' => 'Article',
				'choice'  => array( 'Article', 'BlogPosting', 'NewsArticle', 'ScholarlyArticle', 'TechArticle' )
			) ),
			new def( 'jsonld_author_support', 'Checkbox' ),
			new def( 'twitter_card_support', 'Checkbox' ),
			new def( 'twitter_publisher', 'Text' ),
			new def(
				'twitter_card_type',
				'Radio',
				array( 'default' => 'summary', 'choice' => array( 'summary', 'summary_large_image' ) )
			),
			new def( 'gplus_publisher', 'Url' ),
			new def( 'account_facebook', 'Url' ),
			new def( 'account_twitter', 'Url' ),
			new def( 'account_gplus', 'Url' ),
			new def( 'account_instagram', 'Url' ),
			new def( 'account_youtube', 'Url' ),
			new def( 'account_linkedin', 'Url' ),
			new def( 'account_myspace', 'Url' ),
			new def( 'google_access_token', 'Text' ),
			new def( 'google_meta_activation', 'Html' ),
		);
	}
}

/**
 * Defines default values for each field
 *
 * Class def
 * @package Lti\Seo\Plugin
 */
class def {

	/**
	 * @var string Name of the setting, which will be used throughout the app
	 */
	public $name;
	/**
	 * @var string Type of value (text, radio...)
	 */
	public $type;
	/**
	 * @var mixed Value when initialized
	 */
	public $default_value;
	/**
	 * @var bool Whether the setting has knock on effects on postbox values.
	 */
	public $impacts_user_settings;

	/**
	 * @param $name
	 * @param $type
	 * @param null $default_value
	 * @param bool $impacts_user_settings
	 */
	public function __construct( $name, $type, $default_value = null, $impacts_user_settings = false ) {
		$this->name                  = $name;
		$this->type                  = __NAMESPACE__ . "\\Field_" . $type;
		$this->default_value         = $default_value;
		$this->impacts_user_settings = $impacts_user_settings;
	}
}

/**
 * Puts all settings together
 *
 * Class Plugin_Settings
 * @package Lti\Seo\Plugin
 */
class Plugin_Settings {
	/**
	 * @param \stdClass $settings
	 */
	public function __construct( \stdClass $settings = null ) {

		$defaults = new Defaults();

		/**
		 * @var def $value
		 */
		foreach ( $defaults->values as $value ) {
			$storedValue = false;
			if ( isset( $settings->{$value->name} ) ) {
				$storedValue = $settings->{$value->name};
			}
			$className = $value->type;

			//Settings is null when we reset to defaults
			//In that case, we need to set the value to null so that checkboxes pick up their default values instead
			//of being initialized to false
			if ( $settings == null ) {
				$this->{$value->name} = new $className( null, $value->default_value,
					$value->impacts_user_settings );
			} else {
				$this->{$value->name} = new $className( $storedValue, $value->default_value,
					$value->impacts_user_settings );
			}
		}
	}

	public static function get_defaults() {
		return new self();
	}

	public function save( Array $values = array() ) {
		return new Plugin_Settings( (object) $values );
	}

	public function get( $value ) {
		if ( isset( $this->{$value} ) && ! empty( $this->{$value}->value ) && ! is_null( $this->{$value}->value ) ) {
			return $this->{$value}->value;
		}

		return null;
	}

	/**
	 * Adding new values to the settings class (like temporary ones) or setting existing ones.
	 *
	 * @param string $key
	 * @param string $value
	 * @param string $type Text, Checkbox, Radio, etc.
	 */
	public function set( $key, $value, $type = "Text" ) {
		//We make sure the field, if it exists in the settings class,
		//has the same type as originally defined because that impacts how the value is sanitized.
		if ( isset( $this->{$key} ) ) {
			$rC   = new \ReflectionClass( $this->{$key} );
			$type = substr( $rC->getShortName(), 6 );
			//Radio buttons are supposed to be initialized with an array of default values but when we set values
			//like this we don't set defaults so we pass Radio types as Text types. Values set this way are temporary anyway.
			if ( $type == 'Radio' ) {
				$type = 'Text';
			}
		}
		$className    = __NAMESPACE__ . "\\Field_" . $type;
		$this->{$key} = new $className( $value );
	}

	/**
	 * Comparing two Plugin_Settings objects
	 *
	 * @param Plugin_Settings $values
	 *
	 * @return array $changed key-value array of the properties that changed
	 */
	public function compare( $values ) {
		$changed       = array();
		$currentValues = get_object_vars( $this );
		$oldValues     = get_object_vars( $values );

		foreach ( $currentValues as $key => $value ) {
			if ( $value->isTracked ) {
				if ( isset( $oldValues[ $key ] ) && $oldValues[ $key ]->value != $value->value ) {
					$changed[ $key ] = $value->value;
				}
			}
		}

		return $changed;
	}

	public function remove( $key ) {
		if ( isset( $this->{$key} ) ) {
			unset( $this->{$key} );
		}
	}

	public function postbox_is_required() {
		if ( $this->get( 'keyword_support' ) == true || $this->get( 'robot_support' ) == true || $this->get( 'description_support' ) == true || $this->get( 'open_graph_support' ) == true || $this->get( 'twitter_card_support' ) == true ) {
			return true;
		} else {
			return false;
		}
	}
}

