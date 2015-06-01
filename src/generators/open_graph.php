<?php namespace Lti\Seo\Generators;

/**
 * Generates open graph tags
 *
 * Class Open_Graph
 * @package Lti\Seo\Generators
 */
class Open_Graph extends GenericMetaTag {

	protected $meta_tag_name_attribute = "property";

	public function display_tags() {
		$img = $article = $profile = null;
		if ( isset( $this->tags['og']['image'] ) ) {
			$img = $this->tags['og']['image'];
			unset( $this->tags['og']['image'] );
		}

		//We want this processing to be done on articles only, which have profile info
		//but no "fb" profile ID that we attach to author pages
		if ( isset( $this->tags['profile'] ) && ! isset( $this->tags['fb'] ) ) {
			$profile = $this->tags['profile'];
			$article = $this->tags['article'];
			unset( $this->tags['profile'], $this->tags['article'] );
		}

		$meta = "";
		foreach ( $this->tags as $tag_name => $tag ) {
			$meta .= $this->tag_loop( $tag, $tag_name );
		}

		//We process the profile array apart because FB expects tags in a certain order
		//i.e if an author tag is found, profile info is expected right after. That's how FB builds "arrays" of stuff.
		if ( is_array( $profile ) ) {
			if ( isset( $article['author'] ) && ! is_null( $article['author'] ) ) {
				$meta .= $this->generate_tag( $this->meta_tag_name_attribute,
					'article:author', $article['author'] );
				if ( isset( $profile['first_name'] ) && ! empty( $profile['first_name'] ) ) {
					$meta .= $this->generate_tag( $this->meta_tag_name_attribute,
						'profile:first_name', $profile['first_name'] );
				}
				if ( isset( $profile['last_name'] ) && ! empty( $profile['last_name'] ) ) {
					$meta .= $this->generate_tag( $this->meta_tag_name_attribute,
						'profile:last_name', $profile['last_name'] );
				}
				unset( $article['author'] );
				if ( ! empty( $article ) ) {
					$meta .= $this->tag_loop( $article, 'article' );
				}
			}

		}

		if ( is_array( $img ) ) {
			foreach ( $img as $image ) {
				if ( is_ssl() ) {
					$meta .= $this->generate_tag( $this->meta_tag_name_attribute, 'og:image', $image->url );
					$meta .= $this->generate_tag( $this->meta_tag_name_attribute, 'og:image:secure_url',
						$image->url );
				} else {
					$meta .= $this->generate_tag( $this->meta_tag_name_attribute, 'og:image', $image->url );
				}
				foreach ( $image->properties as $key => $val ) {
					$meta .= $this->generate_tag( $this->meta_tag_name_attribute, sprintf( 'og:image:%s', $key ),
						$val );
				}
			}
		}

		if ( ! empty( $meta ) ) {
			echo $meta;
		}
	}

	public function make_tags() {
		/**
		 * Allow filtering of open graph data
		 *
		 * @api array $tags An array of open graph tags
		 */
		return apply_filters( "lti_seo_open_graph", $this->tags );
	}

	private function tag_loop( $tag, $tag_name ) {
		$meta = null;
		if ( is_array( $tag ) && ! empty( $tag ) ) {
			foreach ( $tag as $subtag => $properties ) {
				//If we still have an array, we do one more loop
				if ( is_array( $properties ) ) {
					foreach ( $properties as $property ) {
						if ( ! empty( $property ) ) {
							$meta .= $this->generate_tag( $this->meta_tag_name_attribute,
								sprintf( '%s:%s', $tag_name, $subtag ),
								$property );
						}
					}
				} else {
					if ( ! empty( $properties ) ) {
						$meta .= $this->generate_tag( $this->meta_tag_name_attribute,
							sprintf( '%s:%s', $tag_name, $subtag ),
							$properties );
					}
				}
			}
		}

		return $meta;
	}

}

class Frontpage_Open_Graph extends Open_Graph implements ICanMakeHeaderTags {

	public function make_tags() {
		$og = array();

		$og['type']        = 'website';
		$og['site_name']   = esc_attr( $this->helper->get_site_name() );
		$og['title']       = esc_attr( $this->helper->get_title() );
		$og['url']         = esc_url( home_url( '/' ) );
		$og['description'] = esc_attr( $this->helper->get_description() );
		$og['locale']      = get_locale();
		$og['image']       = $this->helper->get_social_images( $this->number_images );

		$this->tags = compact( 'og' );

		return parent::make_tags();
	}

}

class Singular_Open_Graph extends Frontpage_Open_Graph implements ICanMakeHeaderTags {

	public function make_tags() {
		$ar                        = parent::make_tags();
		$ar['og']['type']          = 'article';
		$ar['og']['url']           = esc_url( $this->helper->get_canonical_url() );
		$article['published_time'] = esc_attr( lti_iso8601_date( $this->helper->get_post_info( 'post_date' ) ) );
		$article['modified_time']  = esc_attr( lti_iso8601_date( $this->helper->get_post_info( 'post_modified' ) ) );
		$profile                   = $this->helper->get_author_social_info( 'facebook' );
		$ar['article']             = $article;


		if ( ! empty( $profile['profile_url'] ) ) {
			$ar['article']['author']     = $profile['profile_url'];
			$ar['profile']['first_name'] = $profile['first_name'];
			$ar['profile']['last_name']  = $profile['last_name'];
		}

		$publisher = $this->helper->get( 'facebook_publisher' );
		if ( ! is_null( $publisher ) ) {
			$ar['article']['publisher'] = esc_url( $publisher );
		}

		$categories = $this->helper->get_categories();
		if ( is_array( $categories ) && ! empty( $categories ) ) {
			foreach ( $categories as $category ) {
				$ar['article']['section'][] = $category;
			}
		}
		$tags = $this->helper->get_tags();
		if ( is_array( $tags ) && ! empty( $tags ) ) {
			foreach ( $tags as $tag ) {
				$ar['article']['tag'][] = $tag;
			}
		}

		$this->tags = $ar;

		return Open_Graph::make_tags();
	}

}

class Page_Open_Graph extends Frontpage_Open_Graph {
}

class Singular_Gallery_Open_Graph extends Singular_Open_Graph {
}

class Author_Open_Graph extends Frontpage_Open_Graph implements ICanMakeHeaderTags {
	public function make_tags() {
		$ar = array();

		$ar['og']['type']  = 'profile';
		$ar['og']['url']   = esc_url( $this->helper->get_canonical_url() );
		$ar['og']['title'] = esc_attr( $this->helper->get_title() );

		$profile = $this->helper->get_author_social_info( 'facebook' );
		if ( ! empty( $profile['profile_id'] ) ) {
			$ar['fb']['profile_id'] = $profile['profile_id'];
		}
		if ( ! empty( $profile['first_name'] ) ) {
			$ar['profile']['first_name'] = $profile['first_name'];
		}
		if ( ! empty( $profile['last_name'] ) ) {
			$ar['profile']['last_name'] = $profile['last_name'];
		}
		$this->tags = $ar;

		return Open_Graph::make_tags();
	}
}