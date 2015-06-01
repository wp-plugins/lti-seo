<?php namespace Lti\Seo\Helpers;

use Lti\Seo\Plugin\Postbox_Values;

interface ICanHelp {
}

/**
 * Does anything wordpress related on behalf of generators
 *
 * Class Wordpress_Helper
 * @package Lti\Seo\Helpers
 */
class Wordpress_Helper implements ICanHelp {

	protected $is_home_posts_page;
	protected $is_home_static_page;
	protected $is_posts_page;
	protected $site_name;
	protected $page_title;
	protected $current_url;
	protected $page_description;
	protected $page_type;
	protected $post_id;
	protected $shortlink;
	protected $post_meta;
	protected $user_meta;
	protected $language;
	protected $user;
	protected $user_id;

	/**
	 * @var \Lti\Seo\Plugin\Plugin_Settings
	 */
	protected $settings;

	public function __construct( $settings ) {
		$this->settings = $settings;
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

	public static function get_home_url() {
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

	public function get_language() {
		if ( is_null( $this->language ) ) {
			$this->language = get_locale();
		}

		return $this->language;
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

	public function get_current_url() {
		return $this->current_url;
	}

	public function get_settings() {
		return $this->settings;
	}

	/**
	 * Wrapper for the built-in filter_input function
	 *
	 * @param $type
	 * @param $variable_name
	 * @param int $filter
	 *
	 * @return mixed
	 */
	public function filter_input( $type, $variable_name, $filter = FILTER_DEFAULT ) {
		return filter_input( $type, $variable_name, $filter );
	}

	public function filter_var_array($data, $filter = FILTER_SANITIZE_STRING){
		return filter_var_array($data, $filter);
	}
}