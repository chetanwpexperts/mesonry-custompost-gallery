<?php
/**
 * Scripts
 *
 * @package     CHETAN\Masonry-Gallery\Scripts
 * @since       0.1
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;
/**
* Activation or deactivation hooks
*/
register_activation_hook( __FILE__, 'masonry_gallery_plugin_create_db' );
register_deactivation_hook( __FILE__, 'masonry_gallery_plugin_drop_db' );

/**
* masonry_gallery_plugin_create_db
* call on the installation time, create database tables related plugin
* Activation or deactivation callback functions
*/
function masonry_gallery_plugin_create_db() 
{
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$page_data = $wpdb->prefix . 'page_data';
	$content_image = $wpdb->prefix . 'content_image';
	$post_layouts = $wpdb->prefix . 'post_layouts';
	
	$sql = "CREATE TABLE $page_data (
		page_id mediumint(9) NOT NULL AUTO_INCREMENT,
		page_title varchar(100) NOT NULL,
		page_content longtext NULL,
		UNIQUE KEY page_id (page_id)
	) $charset_collate;";
	
	$sql2 = "CREATE TABLE $content_image (
		image_id mediumint(9) NOT NULL AUTO_INCREMENT,
		page_id varchar(11) NOT NULL,
		image_name varchar(100) NOT NULL,
		image_title varchar(100) NOT NULL,
		image_alt varchar(100) NOT NULL,
		image_caption varchar(100) NOT NULL,
		image_description varchar(100) NOT NULL,
		created_at timestamp NOT NULL,
		modified_at timestamp NOT NULL,
		UNIQUE KEY image_id (image_id)
	) $charset_collate;";
	
	$sql3 = "CREATE TABLE $post_layouts (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		project_id int(11) NOT NULL,
		layout_id int(100) NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	dbDelta( $sql2 );
	dbDelta( $sql3 );
}

/**
* masonry_gallery_plugin_drop_db
* call on the uninstallation time, delete database tables related plugin
* Activation or deactivation callback functions
*/
function masonry_gallery_plugin_drop_db() {
	global $wpdb;
	$page_data = $wpdb->prefix."page_data";
	$content_image = $wpdb->prefix."content_image";
	$post_layouts = $wpdb->prefix."post_layouts";
	$sql = "DROP TABLE $page_data";
	$sql2 = "DROP TABLE $content_image";
	$sql3 = "DROP TABLE $post_layouts";
	$wpdb->query($sql);
	$wpdb->query($sql2);
	$wpdb->query($sql3);
}