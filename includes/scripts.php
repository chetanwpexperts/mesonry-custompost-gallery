<?php
/**
 * Scripts
 *
 * @package     CHETAN\Masonry-Gallery\Scripts
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


/**
 * Load admin scripts
 *
 * @since       0.1
 * @return      void
 */
function wpr_giftcards_admin_scripts() {
    // Load Stylasheet 
	wp_register_style( 'bootstrap_css', WPMG_URL.'/includes/bootstrap.css', true, '0.1' );
	wp_enqueue_style( 'bootstrap_css' );
	wp_register_style( 'bootstrap_font_css', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', true, '0.1' );
	wp_enqueue_style( 'bootstrap_font_css' );
	wp_register_style( 'plugin_main_css', WPMG_URL.'/includes/style.css', true, '0.1' );
	wp_enqueue_style( 'plugin_main_css' );
	
	// pls remove this (will cause not found)
	/*wp_register_style( 'style_css', WPMG_URL.'/includes/jQuery-File-Upload/css/style.css', true, '0.1' );
	wp_enqueue_style( 'style_css' );
	wp_register_style( 'fileupload_css', WPMG_URL.'/includes/jQuery-File-Upload/css/jquery.fileupload.css', true, '0.1' );
	wp_enqueue_style( 'fileupload_css' );
	*/
	wp_register_style( 'toast_css', WPMG_URL.'/includes/jquery-toast-plugin/src/jquery.toast.css', true, '0.1' );
	wp_enqueue_style( 'toast_css' );
	wp_register_style( 'font-awesome_css', WPMG_URL.'/includes/keditor/examples/plugins/font-awesome-4.5.0/css/font-awesome.min.css', true, '0.1' );
	wp_enqueue_style( 'font-awesome_css' );
	wp_register_style( 'keditor_css', WPMG_URL.'/includes/keditor/dist/css/keditor-1.1.5.min.css', true, '0.1' );
	wp_enqueue_style( 'keditor_css' );
	wp_register_style( 'components_css', WPMG_URL.'/includes/keditor/dist/css/keditor-components-1.1.5.min.css', true, '0.1' );
	wp_enqueue_style( 'components_css' );
	wp_register_style( 'examples_css', WPMG_URL.'/includes/keditor/examples/css/examples.css', true, '0.1' );
	wp_enqueue_style( 'examples_css' );
	
	// Load jQuery 
	wp_register_script( 'bootstrap', WPMG_URL.'/includes/keditor/examples/plugins/bootstrap-3.3.6/js/bootstrap.min.js', array('jquery'),'0.1', true);
	wp_enqueue_script( 'bootstrap' );
	// wp_register_script( 'widget', WPMG_URL.'/includes/jQuery-File-Upload/js/vendor/jquery.ui.widget.js', array('jquery'),'0.1', true);
	// wp_enqueue_script( 'widget' );
	/*
	wp_register_script( 'transport', WPMG_URL.'/includes/jQuery-File-Upload/js/jquery.iframe-transport.js', array('jquery'),'0.1', true);
	wp_enqueue_script( 'transport' ); 
	*/
	wp_register_script( 'twbsPagination', WPMG_URL.'/includes/twbs-pagination/jquery.twbsPagination.js', array('jquery'),'0.1', true);
	wp_enqueue_script( 'twbsPagination' ); 
	wp_register_script( 'toast', WPMG_URL.'/includes/jquery-toast-plugin/src/jquery.toast.js', array('jquery'),'0.1', true);
	wp_enqueue_script( 'toast' );
	wp_register_script( 'nicescroll', WPMG_URL.'/includes/keditor/examples/plugins/jquery.nicescroll-3.6.6/jquery.nicescroll.min.js', array('jquery'),'0.1', true);
	wp_enqueue_script( 'nicescroll' ); 
	wp_register_script( 'jquery-ui', WPMG_URL.'/includes/keditor/examples/plugins/jquery-ui-1.11.4/jquery-ui.min.js', array('jquery'),'0.1', true);
	wp_enqueue_script( 'jquery-ui' ); 
	wp_register_script( 'ckeditor', WPMG_URL.'/includes/keditor/examples/plugins/ckeditor-4.5.6/ckeditor.js', array('jquery'),'0.1', true);
	wp_enqueue_script( 'ckeditor' ); 
	wp_register_script( 'adapters', WPMG_URL.'/includes/keditor/examples/plugins/ckeditor-4.5.6/adapters/jquery.js', array('jquery'),'0.1', true);
	wp_enqueue_script( 'adapters' ); 
	wp_register_script( 'keditor', WPMG_URL.'/includes/keditor/dist/js/keditor-1.1.5.js', array('jquery'),'0.1', true);
	wp_enqueue_script( 'keditor' ); 
	wp_register_script( 'components_js', WPMG_URL.'/includes/keditor/dist/js/keditor-components-1.1.5.js', array('jquery'),'0.1', true);
	wp_enqueue_script( 'components_js' ); 
}

add_action( 'admin_enqueue_scripts', 'wpr_giftcards_admin_scripts', 100 );

function custom_scripts_theme()
{	
	// Load Stylasheet 
	wp_register_style( 'custom_bootstrap_css', plugins_url( '/bootstrap.css', __FILE__ ) );
	wp_enqueue_style( 'custom_bootstrap_css' );
}
add_action( 'wp_enqueue_scripts', 'custom_scripts_theme' );
