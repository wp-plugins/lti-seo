<?php namespace Lti\Seo\Helpers;

/**
 * Class LTI_SEO_Helper
 *
 * Uses Wordpress info to generate specific types of data on behalf of generators
 *
 * @package Lti\Seo\Helpers
 */
class LTI_SEO_Helper extends Wordpress_Helper {

	public function get_author_social_info_with_labels() {
		$info    = null;
		$twitter = $this->get_user_meta_key( "lti_twitter_username" );
		if ( ! is_null( $twitter ) && ! empty( $twitter ) ) {
			$twitter         = 'https://twitter.com' . str_replace( '@', '/', $twitter );
			$info['twitter'] = $twitter;
		}

		$accounts = array(
			'lti_facebook_url'  => 'facebook',
			'lti_gplus_url'     => 'gplus',
			'lti_instagram_url' => 'instagram',
			'lti_youtube_url'   => 'youtube',
			'lti_linkedin_url'  => 'linkedin',
		);

		foreach ( $accounts as $htmlClassName => $account ) {
			$data = $this->get_user_meta_key( $htmlClassName );
			if ( ! is_null( $data ) && ! empty( $data ) ) {
				$info[ $account ] = $data;
			}
		}

		$data = $this->get_user_meta_key( "lti_public_email" );
		if ( ! is_null( $data ) && ! empty( $data ) ) {
			$info['email'] = 'mailto:' . $data . '?subject=%s&body=%s';
		}

		return $info;
	}

	public function get_social_image_url( $image_size = 'large' ) {
		if ( $image_size == 'large' ) {
			$image_size = apply_filters( 'lti_seo_image_size_index', $image_size );
		}
		if ( ! is_null( $this->post_id ) ) {
			$image_data = $this->get_img( get_post_thumbnail_id(), $image_size );

		} else {
			$img_id     = $this->get_custom_social_image();
			$image_data = $this->get_img( $img_id, $image_size );
		}

		if ( isset( $image_data->url ) ) {
			return $image_data->url;
		}

		return "";
	}

	public function get_thumbnail_url() {
		return $this->get_social_image_url( 'thumbnail' );
	}

	private function get_img( $img_id, $image_size = 'full' ) {
		if ( ! is_null( $img_id ) ) {
			$image_data = new \stdClass();
			$tmp        = wp_get_attachment_image_src( $img_id, $image_size );
			if ( $tmp !== false ) {
				$image_data->url                  = esc_url( $tmp[0] );
				$image_data->properties           = array();
				$image_data->properties['width']  = $tmp[1];
				$image_data->properties['height'] = $tmp[2];
				$image_data->properties['type']   = esc_attr( get_post_mime_type( $img_id ) );

				return $image_data;
			}
		}

		return null;
	}

	public function get_social_urls() {
		$social_profiles = array(
			'account_facebook',
			'account_twitter',
			'account_gplus',
			'account_instagram',
			'account_youtube',
			'account_linkedin',
			'account_myspace'
		);
		$profiles        = array();
		foreach ( $social_profiles as $profile ) {
			$tmp = $this->get( $profile );
			if ( ! is_null( $tmp ) && ! empty( $tmp ) ) {
				$profiles[] = $tmp;
			}
		}

		return $profiles;
	}

	private function get_custom_social_image() {
		$img_id = null;
		if ( $this->page_type() == "Frontpage" ) {
			$img_id = $this->settings->get( 'frontpage_social_img_id' );
		} else {
			$img_id = $this->get_post_meta_key( 'social_img_id' );
		}

		return $img_id;
	}

	public function get_attached_post_images( $limit = - 1 ) {
		$args = array(
			'numberposts'    => $limit,
			'post_parent'    => get_queried_object_id(),
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'order'          => 'ASC',
			'post_mime_type' => 'image',
			'orderby'        => 'menu_order ID'
		);

		$images = get_children( $args );

		if ( ! empty( $images ) ) {
			$output = array();
			foreach ( $images as $image ) {
				$output[ $image->ID ] = $this->get_img( $image->ID );
			}
			$thmb_id = get_post_thumbnail_id();
			//we don't include the thumbnail image, it's inserted separately
			if ( isset( $output[ $thmb_id ] ) ) {
				unset( $output[ $thmb_id ] );
			}

			return $output;
		}

		return null;
	}

	public function get_description() {
		if ( is_null( $this->page_description ) ) {
			if ( $this->page_type() === "Frontpage" ) {
				if ( ! is_null( $this->settings->get( 'frontpage_description_text' ) ) ) {
					$this->page_description = $this->settings->get( 'frontpage_description_text' );
				} else {
					$this->page_description = get_bloginfo( 'description' );
				}
			} else {
				$this->page_description = $this->get_post_meta_key( 'description' );
				if ( $this->settings->get( 'description_support' ) == false || empty( $this->page_description ) ) {
					$this->page_description = get_bloginfo( 'description' );
				}
			}
		}

		return esc_attr( $this->page_description );
	}

	public function get_social_images( $number = - 1 ) {

		/**
		 * Allow filtering of the image size
		 *
		 * @api string $index The index as returned by get_intermediate_image_sizes()
		 * @see get_intermediate_image_sizes()
		 *
		 */
		$image_size = apply_filters( 'lti_seo_image_size_index', 'medium' );

		$data = array();

		$image_data = null;
		if ( ! is_null( $this->post_id ) ) {
			$image_data = $this->get_img( get_post_thumbnail_id(), $image_size );
			if ( ! is_null( $image_data ) ) {
				$data[ get_post_thumbnail_id() ] = $image_data;
			}

			if ( $number == - 1 ) {
				$nb_img = $number;
			} else {
				$nb_img = $number - count( $data );
			}
			$img = array();
			if ( $this->page_post_format() == "Singular_Gallery" ) {
				$tmp = get_post_galleries( $this->post_id, false );

				if ( ! empty( $tmp ) && isset( $tmp[0]['ids'] ) ) {
					$tmp = explode( ',', $tmp[0]['ids'], 4 );

					foreach ( $tmp as $id ) {
						$data[ $id ] = $this->get_img( $id );
					}
				}
			} else {
				$img = $this->get_attached_post_images( $nb_img );
			}
			if ( ! empty( $img ) ) {
				foreach ( $img as $key => $image ) {
					$data[ $key ] = $image;
				}
			}
		}

		$img_id     = $this->get_custom_social_image();
		$image_data = $this->get_img( $img_id, $image_size );
		if ( ! is_null( $image_data ) ) {
			$data[ $img_id ] = $image_data;
		}

		if ( ! empty( $data ) ) {
			return $data;
		}

		return null;
	}

	public function get_keywords() {
		$keywords = array();

		if ( $this->page_type() == "Frontpage" ) {
			$keywords = explode( ",", $this->settings->get( 'frontpage_keyword_text' ) );
		} else {
			if ( $this->settings->get( 'keyword_support' ) == true ) {
				$keywords = $this->get_post_meta_key( 'keyword_text' );
				if ( empty( $keywords ) || is_null( $keywords ) ) {
					$keywords = array();
					if ( $this->settings->get( 'keyword_cat_based' ) === true ) {
						$keywords = array_unique( $this->get_categories() );
					}
					if ( $this->settings->get( 'keyword_tag_based' ) === true ) {
						$keywords = array_unique( array_merge( $keywords,
							$this->get_tags() ) );
					}
				} else {
					$keywords = explode( ",", str_replace( ', ', ',', $keywords ) );
				}
			}
		}

		return $keywords;
	}

	/**
	 * Used by tag generators to fetch social profiles
	 * Can also be used in themes to get author social profiles
	 *
	 * @param string $platform
	 *
	 * @return null
	 */
	public function get_author_social_info( $platform = '' ) {
		$info = null;
		switch ( $platform ) {
			case 'facebook':
				$info['first_name']  = $this->get_user_meta_key( 'first_name' );
				$info['last_name']   = $this->get_user_meta_key( 'last_name' );
				$info['profile_url'] = $this->get_user_meta_key( 'lti_facebook_url' );
				$info['profile_id'] = $this->get_user_meta_key( 'lti_facebook_id' );
				break;
			case 'twitter':
				$info = $this->get_user_meta_key( 'lti_twitter_username' );
				break;
			case 'gplus':
				$info = $this->get_user_meta_key( 'lti_gplus_url' );
				break;
		}

		return $info;
	}

	public function get_all_author_social_info() {
		$twitter = $this->get_user_meta_key( 'lti_twitter_username' );
		if ( ! is_null( $twitter ) && ! empty( $twitter ) ) {
			$twitter = 'https://twitter.com' . str_replace( '@', '/', $twitter );
		}
		$info = array_values( array_filter( array(
				$this->get_user_meta_key( 'lti_facebook_url' ),
				$twitter,
				$this->get_user_meta_key( 'lti_gplus_url' ),
				$this->get_user_meta_key( 'lti_instagram_url' ),
				$this->get_user_meta_key( 'lti_youtube_url' ),
				$this->get_user_meta_key( 'lti_linkedin_url' ),
				$this->get_user_meta_key( 'lti_myspace_url' )
			)
		) );

		return $info;
	}
}