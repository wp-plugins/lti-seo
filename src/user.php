<?php namespace Lti\Seo;

use Lti\Seo\Plugin\Plugin_Settings;
use Lti\Seo\Helpers\ICanHelp;

/**
 * Class User
 *
 * Managed extra user information added by the plugin
 *
 * @package Lti\Seo
 */
class User {

	/**
	 * @var array All the info about custom user profile fields
	 */
	private $user_field_info;
	/**
	 * @var \Lti\Seo\Plugin\Plugin_Settings
	 */
	private $settings;

	/**
	 * @param Plugin_Settings $settings
	 * @param \Lti\Seo\Helpers\ICanHelp $helper
	 */
	public function __construct( Plugin_Settings $settings, ICanHelp $helper ) {
		$this->helper          = $helper;
		$this->settings        = $settings;
		$this->user_field_info = array(
			array( "lti_public_email", ltint_po( 'user.public_email' ), ltint_po( 'hlp.user.public_email' ) ),
			array( "lti_job_title", ltint_po( 'user.job_title' ), ltint_po( 'hlp.user.job_title' ) ),
			array( "lti_work_longitude", ltint_po( 'user.work_longitude' ), ltint_po( 'hlp.user.work_longitude' ) ),
			array( "lti_work_latitude", ltint_po( 'user.work_latitude' ), ltint_po( 'hlp.user.work_latitude' ) ),
			array(
				"lti_twitter_username",
				ltint_po( 'user.twitter_username' ),
				ltint_po( 'hlp.user.twitter_username' )
			),
			array( "lti_facebook_id", ltint_po( 'user.facebook_id' ), ltint_po( 'hlp.user.facebook_id' ) ),
			array( "lti_facebook_url", ltint_po( 'user.facebook_url' ), ltint_po( 'hlp.user.facebook_url' ) ),
			array( "lti_gplus_url", ltint_po( 'user.gplus_url' ), ltint_po( 'hlp.user.gplus_url' ) ),
			array( "lti_instagram_url", ltint_po( 'user.instagram_url' ), ltint_po( 'hlp.user.instagram_url' ) ),
			array( "lti_youtube_url", ltint_po( 'user.youtube_url' ), ltint_po( 'hlp.user.youtube_url' ) ),
			array( "lti_linkedin_url", ltint_po( 'user.linkedin_url' ), ltint_po( 'hlp.user.linkedin_url' ) ),
			array( "lti_myspace_url", ltint_po( 'user.myspace_url' ), ltint_po( 'hlp.user.myspace_url' ) )
		);
	}

	/**
	 * Displays the group of custom user profile fields
	 *
	 * @param $user
	 */
	public function show_user_profile( $user ) {
		$fields = array();
		foreach ( $this->user_field_info as $field ) {
			$fields[] = $this->user_profile_field( $user->ID, $field[0], $field[1], $field[2] );
		}

		echo sprintf( '
		<h3>%s</h3>
		<table class="form-table">
			%s
		</table>', ltint( "user.fields_title" ), implode( PHP_EOL, $fields ) );
	}

	/**
	 * Displays individual custom user fields
	 *
	 * @param int $userID
	 * @param string $field
	 * @param string $label
	 * @param string $description Appears under the field
	 *
	 * @return string
	 */
	private function user_profile_field( $userID, $field, $label, $description ) {
		return sprintf( '<tr>
				<th><label for="%1$s">%2$s</label></th>
				<td>
					<input type="text" name="%1$s" id="%1$s" class="regular-text"
					       value="' . esc_attr( get_the_author_meta( $field, $userID ) ) . '" /><br />
					<span class="description">%3$s</span>
				</td>
			</tr>', $field, ltint( $label ), ltint( $description ) );
	}

	/**
	 * Triggered when the user profile is saved
	 *
	 * @param int $user_id
	 *
	 * @return bool
	 */
	public function personal_options_update( $user_id ) {
		if ( current_user_can( 'edit_user', $user_id ) ) {
			foreach ( $this->user_field_info as $field ) {
				update_user_meta( $user_id, $field[0], $this->helper->filter_input( INPUT_POST, $field[0] ) );
			}
		}

		return true;
	}


}