<?php

/**
 * Appears when we click on the help button. (Top-right of the admin screen)
 *
 * Class Lti_Seo_Help_Menu
 *
 * @see \Lti\Seo\Admin::wp_help_menu
 */
class Lti_Seo_Help_Menu {
	public function __construct() {

	}

	public function welcome_tab() {
		return '<p>' . ltint( 'general_hlp_welcome_1' ) . '</p>'.
		'<p>' . ltint( 'general_hlp_welcome_2' ) . '</p>'.
		'<p>' . ltint( 'general_hlp_welcome_3' ) . '</p>';
	}

	public function general_tab() {
		return '<p>' . ltint( 'general_hlp_general_1' ) . '</p>' .
		       '<p>' . ltint( 'general_hlp_general_2' ) . '</p>' .
		       '<p>' . ltint( 'general_hlp_general_3' ) . '</p>' .
		       '<p>' . ltint( 'general_hlp_general_4' ) . '</p>';
	}

	public function frontpage_tab() {
		return '<p>' . ltint( 'general_hlp_frontpage_1' ) . '</p>'.
		       '<p>' . ltint( 'general_hlp_frontpage_2' ) . '</p>';
	}

	public function social_tab() {
		return '<p>' . ltint( 'general_hlp_social_1' ) . '</p>'.
		       '<p>' . ltint( 'general_hlp_social_2' ) . '</p>'.
		       '<p>' . ltint( 'general_hlp_social_3' ) . '</p>';
	}
	public function google_tab() {
		return '<p>' . ltint( 'general_hlp_google_1' ) . '</p>'.
		       '<p>' . ltint( 'general_hlp_google_2' ) . '</p>';
	}

	public function sidebar() {
		return '<p><strong>' . ltint( 'general_hlp_about_us' ) . '</strong></p>' .
		       '<p><a href="http://dev.linguisticteam.org/lti-seo-help/" target="_blank">' . ltint( 'general_hlp_dev_blog' ) . '</a></p>' .
		       '<p><strong>' . ltint( 'general_hlp_contribute' ) . '</strong></p>' .
		       '<p><a href="https://github.com/DeCarvalhoBruno/lti-wp-seo" target="_blank">' . ltint( 'general_hlp_github' ) . '</a></p>';
	}
}