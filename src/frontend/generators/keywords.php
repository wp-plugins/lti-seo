<?php namespace Lti\Seo\Generators;

/**
 * Generates keyword meta tags
 *
 * Class Keyword
 * @package Lti\Seo\Generators
 */
class Keyword extends GenericMetaTag {

	public function display_tags() {
		if ( ! is_null( $this->tags ) && ! empty( $this->tags ) ) {
			echo $this->generate_tag( 'name', 'keywords', esc_attr( $this->tags ) );
		}
	}


	public function make_tags() {
		/**
		 * Allow filtering of keywords
		 *
		 * @api string
		 */
		return apply_filters( 'lti_seo_keywords', $this->tags );
	}
}

class Frontpage_Keyword extends Keyword implements ICanMakeHeaderTags {

	public function make_tags() {
		if ( $this->helper->get( 'frontpage_keyword' ) == true ) {
			$this->tags = explode( ",", trim( $this->helper->get( 'frontpage_keyword_text' ) ) );
			$this->tags = implode( ",", array_filter( $this->tags ) );
		}

		return parent::make_tags();
	}

}

class Page_Keyword extends Frontpage_Keyword {

}

class Singular_Keyword extends Keyword implements ICanMakeHeaderTags {

	public function make_tags() {
		if ( $this->helper->get( 'keyword_support' ) == true ) {
			$this->tags = explode( ",",
				str_replace( ', ', ',', trim( $this->helper->get_post_meta_key( 'keywords' ) ) ) );
			$this->tags = implode( ",", array_filter( $this->tags ) );
		}

		return parent::make_tags();
	}
}