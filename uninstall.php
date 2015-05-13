<?php
// Prevent direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

//Deleting plugin options
delete_option('lti_seo_options');

//Deleting extra post information added by the plugin
delete_post_meta_by_key( 'lti_seo' );

//Deleting user profile fields created by the plugin
$user_meta_keys = array('lti_public_email','lti_job_title','lti_work_longitude','lti_work_latitude','lti_twitter_username','lti_facebook_id','lti_facebook_url','lti_gplus_url','lti_instagram_url','lti_youtube_url','lti_linkedin_url','lti_myspace_url');

foreach($user_meta_keys as $key){
    delete_metadata( 'user', 0, $key, '', true );
}


?>