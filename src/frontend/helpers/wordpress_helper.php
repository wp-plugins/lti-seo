<?php namespace Lti\Seo\Helpers;

use Lti\Seo\Plugin\Fields;
use Lti\Seo\Plugin\Postbox_Values;

interface ICanHelp {
}

/**
 * Does anything wordpress related on behalf on generators
 * @TODO: find a way to specialize helpers more, this class could become humongous
 *
 * Class Wordpress_Helper
 * @package Lti\Seo\Helpers
 */
class Wordpress_Helper extends Generic_Helper implements ICanHelp, ICanHelpWithJSONLD {

	private $is_home_posts_page;
	private $is_home_static_page;
	private $is_posts_page;
	private $site_name;
	private $page_title;
	private $current_url;
	private $page_description;
	private $page_type;
	private $post_id;
	private $shortlink;
	private $post_meta;
	private $user_meta;
	private $language;
	private $user;
	private $user_id;

	/**
	 * @var \Lti\Seo\Plugin\Plugin_Settings
	 */
	protected $settings;

	public function __construct( $settings ) {
		parent::__construct( $settings );
	}

	public function get( $value ) {
		return $this->settings->get( $value );
	}

	/**
	 * To make sure those methods have been called, they're used a lot.
	 *
	 */
	public function init() {
		$this->page_type();
		$this->get_canonical_url();
	}

	public function get_site_name() {
		if ( is_null( $this->site_name ) ) {
			$this->site_name = esc_attr( get_bloginfo( 'name' ) );
		}

		return $this->site_name;
	}

	public function get_title() {
		if ( is_null( $this->page_title ) ) {
			$this->page_title = wp_title( '-', false, 'right' );
		}

		return $this->page_title;
	}

	public function get_post_info( $key ) {
		$field = get_post_field( $key, $this->post_id, 'raw' );
		if ( ! empty( $field ) ) {
			return $field;
		}

		return null;
	}

	public function get_post_meta() {
		if ( is_null( $this->post_meta ) ) {
			$box_values      = get_post_meta( $this->post_id, "lti_seo", true );
			$this->post_meta = $box_values;
		}

		return $this->post_meta;
	}

	public function get_post_meta_key( $key ) {
		if ( is_null( $this->post_meta ) ) {
			$this->get_post_meta();
		}

		if ( ! empty( $this->post_meta ) && $this->post_meta instanceof Postbox_Values ) {
			return $this->post_meta->get( $key );
		}

		return null;
	}

	public function get_author_url() {
		$url = esc_url( get_author_posts_url( get_the_author_meta( 'ID',
			$this->get_post_info( 'post_author' ) ) ) );

		return $url;
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
				$info['first_name'] = $this->get_user_meta_key( "first_name" );
				$info['last_name']  = $this->get_user_meta_key( "last_name" );
				$info['profile_id'] = $this->get_user_meta_key( "lti_facebook_id" );
				break;
			case 'twitter':
				$info = $this->get_user_meta_key( "lti_twitter_username" );
				break;
			case 'gplus':
				$info = $this->get_user_meta_key( "lti_gplus_url" );
				break;
			case 'all_with_labels':
				$twitter = $this->get_user_meta_key( "lti_twitter_username" );
				if ( ! is_null( $twitter ) && ! empty( $twitter ) ) {
					$twitter         = 'https://twitter.com' . str_replace( '@', '/', $twitter );
					$info['twitter'] = $twitter;
				}
				$data = $this->get_user_meta_key( "lti_facebook_url" );
				if ( ! is_null( $data ) && ! empty( $data ) ) {
					$info['facebook'] = $data;
				}
				$data = $this->get_user_meta_key( "lti_gplus_url" );
				if ( ! is_null( $data ) && ! empty( $data ) ) {
					$info['gplus'] = $data;
				}
				$data = $this->get_user_meta_key( "lti_instagram_url" );
				if ( ! is_null( $data ) && ! empty( $data ) ) {
					$info['instagram'] = $data;
				}
				$data = $this->get_user_meta_key( "lti_youtube_url" );
				if ( ! is_null( $data ) && ! empty( $data ) ) {
					$info['youtube'] = $data;
				}
				$data = $this->get_user_meta_key( "lti_linkedin_url" );
				if ( ! is_null( $data ) && ! empty( $data ) ) {
					$info['linkedin'] = $data;
				}
				$data = $this->get_user_meta_key( "lti_public_email" );
				if ( ! is_null( $data ) && ! empty( $data ) ) {
					$info['email'] = 'mailto:' . $data . '?subject=%s&body=%s';
				}
				break;
			default:

				$twitter = $this->get_user_meta_key( "lti_twitter_username" );
				if ( ! is_null( $twitter ) && ! empty( $twitter ) ) {
					$twitter = 'https://twitter.com' . str_replace( '@', '/', $twitter );
				}
				$info = array_values( array_filter( array(
						$this->get_user_meta_key( "lti_facebook_url" ),
						$twitter,
						$this->get_user_meta_key( "lti_gplus_url" ),
						$this->get_user_meta_key( "lti_instagram_url" ),
						$this->get_user_meta_key( "lti_youtube_url" ),
						$this->get_user_meta_key( "lti_linkedin_url" ),
						$this->get_user_meta_key( "lti_myspace_url" )
					)
				) );
				break;
		}

		return $info;
	}

	public function get_user_meta() {
		$author = null;
		if ( is_null( $this->user_meta ) ) {
			if ( $this->page_type() == "Author" ) {
				$author = get_query_var( 'author' );
				//In the front page, the author is set the wordpress user that's specified in the settings > front page > JSON-LD
			} elseif ( $this->page_type() == "Frontpage" ) {
				$author = $this->settings->get( 'jsonld_person_wp_userid' );
			} else {
				$author = get_the_author_meta( 'ID',
					$this->get_post_info( 'post_author' ) );
			}
			$this->user_meta = get_user_meta( $author );
		}

		return $this->user_meta;
	}

	public function get_user_meta_key( $key ) {
		if ( is_null( $this->user_meta ) ) {
			//if we get all meta keys, wp returns an array of arrays
			$this->get_user_meta();
		}

		if ( isset( $this->user_meta[ $key ] ) ) {
			$value = $this->user_meta[ $key ];
			if ( is_array( $value ) ) {
				$this->user_meta[ $key ] = array_shift( $value );
			}
			if ( ! is_null( $this->user_meta[ $key ] ) && ! empty( $this->user_meta[ $key ] ) ) {
				return $this->user_meta[ $key ];
			}
		}

		return null;
	}

	public function get_user() {
		$author = null;
		if ( is_null( $this->user_id ) ) {
			if ( $this->page_type == "Frontpage" ) {
				if ( $this->get( 'jsonld_entity_support' ) == true && $this->get( 'jsonld_entity_type' ) == "Person" ) {
					$this->user_id = $this->get( 'jsonld_person_wp_userid' );
				} else {
					$this->user_id = null;
				}
			} else if ( $this->page_type() == "Author" ) {
				$this->user_id = get_query_var( 'author' );
			} else {
				$this->user_id = get_the_author_meta( 'ID',
					$this->get_post_info( 'post_author' ) );
			}
		}
		$this->user = get_userdata( $this->user_id );

		return $this->user_id;
	}

	public function get_user_key( $key ) {
		if ( is_null( $this->user_id ) ) {
			$this->get_user();
		}
		if ( isset( $this->user->{$key} ) && ! empty( $this->user->{$key} ) ) {
			return $this->user->{$key};
		}

		return null;
	}

	public function page_type() {
		if ( is_null( $this->page_type ) ) {
			if ( is_front_page() ) {
				$this->page_type = "Frontpage";
			} elseif ( is_singular() ) {
				if ( is_attachment() ) {
					$this->page_type = 'Attachment';
				} elseif ( is_page() ) {
					$this->page_type = 'Page';
					$this->post_id   = $this->get_post_info( 'ID' );
				} else {
					$this->page_type = 'Singular';
					$this->post_id   = $this->get_post_info( 'ID' );
				}
			} elseif ( is_category() || is_tag() || is_tax() ) {
				$this->page_type = "Catagax";
			} elseif ( is_author() ) {
				$this->page_type = "Author";
			} elseif ( is_archive() ) {
				$this->page_type = 'Archive';
			} elseif ( is_search() ) {
				$this->page_type = 'Search';
			} elseif ( is_404() ) {
				$this->page_type = 'NotFound';
			}
		}

		return $this->page_type;
	}

	public function get_post_id() {
		return $this->post_id;
	}

	public function page_post_format() {
		$post_type = get_post_format();
		if ( $post_type !== false && $this->page_type() != "Frontpage" ) {
			return sprintf( "%s_%s", $this->page_type(), ucfirst( $post_type ) );
		}

		return $this->page_type();
	}

	/**
	 * Is this the homepage AND does it show posts?
	 *
	 * @return bool
	 */
	public function is_home_posts_page() {
		if ( is_null( $this->is_home_posts_page ) ) {
			$this->is_home_posts_page = ( is_home() && 'posts' == get_option( 'show_on_front' ) );
		}

		return $this->is_home_posts_page;

	}

	/**
	 * Is this a static page being used as the frontpage?
	 *
	 * @return bool
	 */
	public function is_home_static_page() {
		if ( is_null( $this->is_home_static_page ) ) {
			$this->is_home_static_page = ( is_front_page() && 'page' == get_option( 'show_on_front' ) && is_page( get_option( 'page_on_front' ) ) );
		}

		return $this->is_home_static_page;

	}

	/**
	 * Is this the show posts page?
	 *
	 * @return bool
	 */
	public function is_posts_page() {
		if ( is_null( $this->is_posts_page ) ) {
			$this->is_posts_page = ( is_home() && 'page' == get_option( 'show_on_front' ) );
		}

		return $this->is_posts_page;
	}

	public function get_home_url() {
		return home_url( '/' );
	}

	/**
	 * Returns the canonical URL for the page
	 * Not sure this returns accurate results.
	 *
	 * @return bool|mixed|null|string|void|\WP_Error
	 */
	public function get_canonical_url() {
		if ( is_null( $this->current_url ) ) {
			$this->current_url = apply_filters( 'lti_seo_get_canonical_url', false );

			if ( $this->current_url === false ) {
				$link = "";
				if ( is_singular() ) {
					$link = get_permalink();
				} elseif ( is_search() ) {
					$link = get_search_link();
				} elseif ( is_front_page() ) {
					$link = home_url( '/' );
				} elseif ( $this->is_posts_page() ) {
					$link = get_permalink( get_option( 'page_for_posts' ) );
				} elseif ( $this->page_type() == 'Catagax' ) {
					$term = get_queried_object();
					$link = get_term_link( $term, $term->taxonomy );
				} elseif ( is_post_type_archive() ) {
					$post_type = get_query_var( 'post_type' );
					if ( is_array( $post_type ) ) {
						$post_type = reset( $post_type );
					}
					$link = get_post_type_archive_link( $post_type );
				} elseif ( is_author() ) {
					$link = get_author_posts_url( get_query_var( 'author' ), get_query_var( 'author_name' ) );
				} elseif ( is_archive() ) {
					if ( is_date() ) {
						if ( is_day() ) {
							$link = get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ),
								get_query_var( 'day' ) );
						} elseif ( is_month() ) {
							$link = get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
						} elseif ( is_year() ) {
							$link = get_year_link( get_query_var( 'year' ) );
						}
					}
				} elseif ( is_attachment() ) {
					$link = wp_get_attachment_url( $this->post_id );
				} else {
					$link = get_permalink();
				}
				$this->current_url = $link;
			}
		}

		return $this->current_url;
	}

	public function get_shortlink() {
		if ( is_null( $this->shortlink ) ) {
			$link = wp_get_shortlink();
			if ( ! is_null( $link ) && ! empty( $link ) ) {
				$this->shortlink = $link;
			} else {
				$this->shortlink = get_permalink();
			}
		}

		return $this->shortlink;
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

	public function get_language() {
		if ( is_null( $this->language ) ) {
			$this->language = get_locale();
		}

		return $this->language;
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

	public function get_categories() {
		return $this->extract_array_object_value( get_the_category( $this->post_id ),
			'cat_name' );
	}

	public function get_tags() {
		return $this->extract_array_object_value( get_the_tags( $this->post_id ),
			'name' );
	}

	public function extract_array_object_value( $values, $field ) {
		$vals = array();
		if ( is_array( $values ) ) {
			foreach ( $values as $value ) {
				$vals[] = $value->{$field};
			}
		}

		return $vals;
	}

	public function update_global_post_fields( $changed = array(), $reset = false ) {
		/**
		 * @var \wpdb $wpdb
		 */
		global $wpdb;
		//@TODO: check whether this can be covered by some wp method
		$sql = 'SELECT ' . $wpdb->posts . '.ID,' . $wpdb->postmeta . '.meta_value  FROM ' . $wpdb->posts . '
				LEFT JOIN ' . $wpdb->postmeta . ' ON (' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id AND ' . $wpdb->postmeta . '.meta_key = "lti_seo")
				WHERE ' . $wpdb->posts . '.post_type = "post" AND ' . $wpdb->posts . '.post_status!="auto-draft"';

		$results = $wpdb->get_results( $sql );

		if ( is_array( $results ) ) {
			foreach ( $results as $result ) {
				$postbox_values = $result->meta_value;
				if ( ! is_null( $postbox_values ) && ! $reset ) {
					$postbox_values = unserialize( $postbox_values );
				} else {
					$postbox_values = new Postbox_Values( new \stdClass() );
				}

				foreach ( $changed as $changedKey => $changedValue ) {
					if ( isset( $postbox_values->{$changedKey} ) && $postbox_values->{$changedKey} instanceof Fields ) {
						$postbox_values->{$changedKey}->value = $changedValue;
					}
				}

				update_post_meta( $result->ID, 'lti_seo', $postbox_values );
			}
		}
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

	public function get_schema_org( $setting ) {
		switch ( $this->schema ) {
			case "CreativeWork":
				switch ( $setting ) {
					case 'headline':
						return $this->get_title();
					case 'keywords':
						return implode( ',', $this->get_keywords() );
					case 'thumbnailUrl':
						return $this->get_thumbnail_url();
					case 'inLanguage':
						return $this->get_language();
					case 'datePublished':
						return lti_iso8601_date( $this->get_post_info( 'post_date' ) );
					case 'dateModified':
						return lti_iso8601_date( $this->get_post_info( 'post_modified' ) );
					case 'copyrightYear':
						return lti_mysql_date_year( $this->get_post_info( 'post_date' ) );
					case 'author':
						if ( $this->get( 'jsonld_entity_support' ) == true ) {
							$type = $this->get( 'jsonld_entity_type' );

							if ( $type == 'Person' ) {
								return array(
									'Person' => $this
								);
							}
						}
						break;
					case 'publisher':
						if ( $this->get( 'jsonld_entity_support' ) == true ) {
							$type = $this->get( 'jsonld_entity_type' );
							if ( $type == 'Organization' ) {
								return array(
									'Organization' => $this
								);
							}
						}
						break;
				}
				break;
			case 'Person':
				switch ( $setting ) {
					case 'name':
						return $this->get_user_key( 'display_name' );
					case 'url':
						return $this->get_user_key( 'user_url' );
					case 'workLocation:longitude':
						return $this->get_user_meta_key( 'lti_work_longitude' );
					case 'workLocation:latitude':
						return $this->get_user_meta_key( 'lti_work_latitude' );
					case 'jobTitle':
						return $this->get_user_meta_key( 'lti_job_title' );
					case 'email':
						return $this->get_user_meta_key( 'lti_public_email' );
					case 'sameAs':
						return $this->get_author_social_info();
				}
				break;
			case 'Organization':
				switch ( $setting ) {
					case 'name':
						return $this->get( 'jsonld_org_name' );
					case 'url':
						return $this->get( 'jsonld_org_website_url' );
					case 'alternateName':
						return $this->get( 'jsonld_org_alternate_name' );
					case 'logo':
						return $this->get( 'jsonld_org_logo_url' );
					case 'sameAs':
						return $this->get_social_urls();
				}
				break;
			case 'Article':
			case 'BlogPosting':
			case 'NewsArticle':
			case 'ScholarlyArticle':
			case 'TechArticle':
				switch ( $setting ) {
					case 'articleSection':
						return $this->get_categories();
					case 'wordCount':
						return $this->get_post_meta_key( 'word_count' );
					case 'Person:url':
						return $this->get_user_key( 'user_url' );
				}

				break;
		}

		return null;
	}

	public function get_schema_org_setting( $setting ) {
		return $this->get( 'jsonld_type_' . $setting );
	}

	public function get_search_action_type() {
		return 'Lti\Seo\Generators\WordpressSearchAction';
	}

	public function get_current_url() {
		return $this->current_url;
	}
}
