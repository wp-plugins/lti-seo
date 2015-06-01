<?php

/**
 * Lti + i8n = ltint
 *
 * @param $text
 * @param string $domain
 *
 * @return string|void
 */
function ltint( $text, $domain = 'lti-seo' ) {
	return __( $text, $domain );
}

/**
 * Retarded way of having certain fields being picked up by poedit
 * without having to use the translation domain
 *
 * @param $value
 * @return mixed
 */
function ltint_po($value){
	return $value;
}

/**
 * Displays input text values
 *
 * @param $value
 *
 * @return mixed|null|string|void
 */
function ltiopt( $value ) {
	$admin = \Lti\Seo\LTI_SEO::get_instance()->get_admin();
	return esc_attr($admin->get_form_values()->get( $value ));
}

/**
 * Displays input checkbox values
 *
 * @param $value
 *
 * @return null|string
 */
function ltichk( $value ) {
	$val = ltiopt( $value );
	if ( $val == true ) {
		return 'checked="checked"';
	} else {
		return null;
	}
}

/**
 * Displays input radio values
 *
 * @param $key
 * @param $currentValue
 *
 * @return null|string
 */
function ltirad( $key, $currentValue ) {
	$storedValue = ltiopt( $key );
	if ( $storedValue == $currentValue ) {
		return 'checked="checked"';
	} else {
		return null;
	}
}

/**
 * Retrieves page type so we can display error/info messages properly
 * @return string
 */
function ltipagetype() {
	$admin = \Lti\Seo\LTI_SEO::get_instance()->get_admin();
	return $admin->get_page_type();
}

/**
 * Gets the right info/error message
 *
 * @return string
 */
function ltimessage() {
	$admin = \Lti\Seo\LTI_SEO::get_instance()->get_admin();
	return $admin->get_message();
}
if(!function_exists('lti_iso8601_date')) {
	function lti_iso8601_date( $date ) {
		return mysql2date( 'c', $date );
	}
}
if(!function_exists('lti_mysql_date_year')) {
	function lti_mysql_date_year( $date ) {
		return mysql2date( 'Y', $date );
	}
}