<?php namespace Lti\Seo\Generators;

use Lti\Seo\Helpers\ICanHelp;
use Lti\Seo\Plugin\Plugin_Settings;


interface ICanMakeHeaderTags {
	public function display_tags();

	public function make_tags();

	public function generate_tag( $name, $property, $content );
}

abstract class GenericMetaTag {

	/**
	 * @var array Where tags are placed before output
	 */
	protected $tags;

	/**
	 * @var int If needed, the post id
	 */
	protected $post_id;

	/**
	 * @var string The default name of the name attribute in meta tags
	 */
	protected $meta_tag_name_attribute = "name";


	protected $number_images = -1;

	/**
	 * @var ICanHelp|\Lti\Seo\Helpers\LTI_SEO_Helper
	 */
	protected $helper;

	/**
	 * @param ICanHelp $helper
	 * @param Plugin_Settings $settings
	 */
	public function __construct( ICanHelp $helper, $post_id = null ) {
		$this->helper = $helper;
		$this->post_id = $post_id;
		$this->tags   = $this->make_tags();
	}

	public function generate_tag( $name, $property, $content ) {
		return sprintf( '<meta %s="%s" content="%s" />' . PHP_EOL,$name, $property, $content );
	}

	public function get_tags(){
		return $this->tags;
	}


}