<?php namespace Lti\Seo\Generators;

/**
 * Handles meta description tags
 *
 * Class Description
 * @package Lti\Seo\Generators
 */
class Description extends GenericMetaTag {

	public function display_tags() {
		if ( ! is_null( $this->tags ) && ! empty( $this->tags ) ) {
			echo $this->generate_tag( 'name', 'description', $this->tags );
		}
	}

	public function make_tags() {
		return apply_filters( 'lti_seo_description', $this->tags );
	}
}

class Frontpage_Description extends Description implements ICanMakeHeaderTags {
	public function make_tags() {
		if ( $this->helper->get( 'frontpage_description' ) == true ) {
			$this->tags = $this->helper->get_description();
		}

		return parent::make_tags();

	}
}

class Page_Description extends Frontpage_Description {

}

class Singular_Description extends Description {
	public function make_tags() {
		if ( $this->helper->get( 'description_support' ) == true ) {
			$this->tags = $this->helper->get_description();
		}

		return parent::make_tags();
	}

}