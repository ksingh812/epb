<?php
/**
* Require files that deal with various plugin integrations of WP Job Manager.
*
* @package Listable
*/

if ( class_exists( 'WP_Job_Manager_Products' ) ) {
	require get_template_directory() . '/inc/wpjm-integrations/wp-job-manager-products.php';
}

if ( class_exists( 'WP_Job_Manager_Job_Tags' ) ) {
	require get_template_directory() . '/inc/wpjm-integrations/wp-job-manager-tags.php';
}