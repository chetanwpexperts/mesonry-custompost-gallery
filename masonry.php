<?php
/**
 * Plugin Name: WP Post Gallery - Masonry Gallery
 * Plugin URI: #
 * Description: WP Post Gallery for custom post types and post types with bootstrap masonry settings 
 * Author: Chetan
 * Author URI: #
 * Version: 0.1
 * Text Domain: wpexpertsweb
 * @copyright Copyright (c) 2018
 * License: GPL2
 */

if( !defined( 'ABSPATH' ) ) exit;
if( !class_exists( 'WPMasonryGallery' ) ) 
{
	class WPMasonryGallery 
	{
		/**
         * @var         WPMasonryGallery $instance The one true WPMasonryGallery
         * @since       0.1
         */
        private static $instance;
		
		/**
         * Get active instance
         *
         * @access      public
         * @since       0.1
         * @return      object self::$instance The one true WPMasonryGallery
         */
        public static function instance() 
		{
            if( !self::$instance ) {
                self::$instance = new WPMasonryGallery();
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->hooks();
            }

            return self::$instance;
        }

		/**
         * Setup plugin constants
         *
         * @access      private
         * @since       0.1
         * @return      void
         */
        private function setup_constants() 
		{
            define( 'WPMG_VERSION',   '0.1' ); // Plugin version
			define( 'WPMG_DS', DIRECTORY_SEPARATOR);
			define( 'WPMG_INCLUDE_PATH', plugin_dir_path( __FILE__ ) . 'includes/'); // Gallery Include Path
			define( 'WPMG_UPLOAD_DIRECTORY_PATH', WPMG_INCLUDE_PATH . 'files'); // Gallery Upload Path
			define( 'WPMG_TEMPLATE_PATH', WPMG_INCLUDE_PATH . 'templates/'); // Template Directory Path
            define( 'WPMG_DIR',       plugin_dir_path( __FILE__ ) ); // Plugin Folder Path
            define( 'WPMG_URL',       plugins_url( 'mesonry-custompost-gallery/', 'masonry.php' ) ); // Plugin Folder URL
            define( 'WPMG_FILE',      plugin_basename( __FILE__ )  ); // Plugin Root File
			define( 'WPMG_IMAGE_URL', plugins_url() . '/mesonry-custompost-gallery/includes/files/'); 
			define( 'WPMG_AJAX_URL', admin_url( 'admin-ajax.php' )); // ajax url to use in ajax call
        }
		
		/**
         * Include necessary files
         *
         * @access      private
         * @since       0.1
         * @return      void
         */
        private function includes() 
		{
            // Include scripts
            require_once WPMG_INCLUDE_PATH . 'scripts.php';
        }
		
		/**
         * Run action and filter hooks
         *
         * @access      private
         * @since       0.1
         * @return      void
         *
         */
        private function hooks() 
		{
			/**
			* Action hooks
			*/
			add_action( "admin_menu", array($this, 'masonry_gallery_menu') );
			add_action('admin_head', array($this, 'addPluginUrl' ) );
			add_action( 'admin_notices', array($this, 'my_update_notice') );
			add_action('admin_head', array($this, 'admin_menu_fix' ) );
			
			/**
			* Action hooks for ajax call
			* Edit template
			*/
			add_action('wp_ajax_nopriv_save_layouts', array($this, 'save_layouts' ) );
			add_action('wp_ajax_save_layouts', array($this, 'save_layouts' ) );
			add_action('wp_ajax_nopriv_assign_layouts_to_post', array($this, 'assign_layouts_to_post' ) );
			add_action('wp_ajax_assign_layouts_to_post', array($this, 'assign_layouts_to_post' ) );
			
			/**
			* Shortcode
			*/
			add_shortcode( 'mesonry_layout', array($this, 'mesonry_templates_shortcode') );
        }
		
		function admin_menu_fix() {
			echo '<style>
			#adminmenu { transform: translateZ(0); }
			</style>';
		}

		/**
		* Add plugin notice, message
		*/
		function my_update_notice() {
			if($_REQUEST['page'] == 'edit_gallery' || $_REQUEST['page'] == 'add_layout') : 
				?>
				<div class="updated notice">
					<p><?php _e( '<strong>Drag and drop column layout from your right side</strong>', 'wpexpertsweb' ); ?></p>
				</div>
				<?php
			endif;
		}
		
		/**
		* Add plugin message
		*/
		function checkFileType($string){
			$ext =  strrchr($string,'.');

			if($ext === '.mp4'){
				return 'video';
			}else{
				return 'image';
			}
		}
		
		/**
		* Add plugin url to javascript / jQuery files
		*/
		function addPluginUrl()
		{
			ob_start();
			?>
			<script type="text/javascript">
				var plugin_url = '<?php echo WPMG_URL;?>';
				var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' );?>"; 
			</script>
			<?php
			$script = ob_get_clean();
			echo $script;
		}
		
		/**
		* Method: masonry_gallery_menu
		* admin menu
		*/
		function masonry_gallery_menu() 
		{
			add_menu_page( 'Masonry Gallery', 'Masonry Gallery', 2, 'masonry_gallery', array($this, 'home') );
			add_submenu_page( "masonry_gallery", "Add Layout", "Add Layout", 0, "add_layout", array($this, "addlayout") );
			add_submenu_page( "masonry_gallery", "WP Media", "WP Media", 1, "media", array($this, "getmediafiles") );
			add_submenu_page( NULL, "Edit Gallery", "Edit Gallery", 1, "edit_gallery", array($this, "edit") );
		}
		
		/**
		* Method: home
		* @return: Home page
		*/
		function home()
		{
			global $wpdb;
			include(WPMG_TEMPLATE_PATH . "home.php");
		}

		/**
		* Method: addlayout
		* @return: Add new layout page
		*/
		function addlayout()
		{
			global $wpdb;
			include(WPMG_TEMPLATE_PATH . "add_layout.php");
		} 
		
		/**
		* Method: edit
		* @return: Edit/Assign/Delete to layout
		*/
		function edit()
		{
			global $wpdb;
			include(WPMG_TEMPLATE_PATH . "edit.php");
		}
		
		/**
		* Method: getmediafiles
		* @return: Get all wordpress media files such as audio/video/images
		*/
		function getmediafiles()
		{
			global $wpdb;
			include(WPMG_TEMPLATE_PATH . "attachments.php");
		}
		
		/**
		* Method: save_layouts
		* Function to save, update, get to gallery layout
		* Masonry gallery ajax call function - admin-ajax.php
		**/
		function save_layouts()
		{
			global $wpdb;
			if(!isset($_POST['postdata']['pageid'])) :
				$q = "INSERT INTO ".$wpdb->prefix."page_data (page_title, page_content) VALUES('Gallery', '".$_POST['postdata']['pagedata']."')";
				$r = $wpdb->query($q);
				if (!$r) {
					echo "Not Inserted";
				} else {
					echo "Inserted";
				}
				exit;
			endif;
			
			$querystr = "SELECT * FROM " . $wpdb->prefix . "page_data WHERE page_id=".$_POST['postdata']['pageid'];
			$rows = $wpdb->get_results($querystr, OBJECT);
			$page_data = $_POST['postdata']['pagedata'];
			if(!empty($rows)):
				$q = "UPDATE ".$wpdb->prefix."page_data SET page_content='". $page_data."' WHERE page_id=".$_POST['postdata']['pageid'];
				$r = $wpdb->query($q);
				if (!$r) {
					echo "Not Updated";
				} else {
					echo "Updated";
				}
			else:
				$q = "INSERT INTO ".$wpdb->prefix."page_data (page_title, page_content) VALUES('Gallery', '".$page_data."')";
				$r = $wpdb->query($q);
				if (!$r) {
					echo "Not Inserted";
				} else {
					echo "Inserted";
				}
			endif;
			exit;
		}
		
		/**
		* Method: assign_layouts_to_post
		* Function to assign gallery layout to perticular post
		* Assigning Masonry gallery to post ajax call function - admin-ajax.php
		**/
		function assign_layouts_to_post()
		{
			$post_ID = $_POST['postid'];
			$layout_ID = $_POST['layoutid'];
			update_post_meta($post_ID, "layout_id", $layout_ID);
			update_post_meta($post_ID, "layout_assign_to_post", $post_ID);
			echo "Assigned";
			exit;
		}
		
		/**
		* Method: generate_post_select
		* Custom Post Type: Projects
		* @return: get custom post dropdown list
		*/
		function generate_post_select($select_id, $post_type, $layoutID, $selected = 0) 
		{
			$post_type_object = get_post_type_object($post_type);
			$label = $post_type_object->label;
			$posts = get_posts(array('post_type'=> $post_type, 'post_status'=> 'publish', 'suppress_filters' => false, 'posts_per_page'=>-1));
			echo '<select name="'. $select_id .'" id="'.$select_id.'">';
			echo '<option value = "" >All '.$label.' </option>';
			foreach ($posts as $post) {
				$meta_layout_postid = get_post_meta( $post->ID, 'layout_assign_to_post', true); 
				$meta_layout_id = get_post_meta( $post->ID, 'layout_id', true); 
				?>
				<option value="<?php echo $post->ID;?>" data-pID="<?php echo $layoutID;?>" <?php echo ($meta_layout_postid == $post->ID && $meta_layout_id == $layoutID) ? ' selected="selected"' : '';?>><?php echo $post->post_title;?></option>
				<?php 
			}
			echo '</select>';
		}
		
		/**
		* Method: generate_post_select
		* Custom Post Type: Projects
		* @return: get custom post dropdown list
		*/
		function mesonry_templates_shortcode($atts)
		{
			global $wpdb;
			if(!empty($atts['id'])) :
				$meta_layout_id = get_post_meta( $atts['id'], 'layout_id', true); 
				$querystr = "SELECT * FROM " . $wpdb->prefix . "page_data WHERE page_id=".$meta_layout_id;
				$gallery = $wpdb->get_row($querystr);
				return $gallery->page_content;
			else:
				return "No Mesonry layout assigned to this post";
			endif;
		}
		
		/**
		 * Get post id from meta key and value
		 * @param string $key
		 * @param mixed $value
		 * @return int|bool
		 * @author David M&aring;rtensson <david.martensson@gmail.com>
		 */
		function get_post_id_by_meta_key_and_value($key, $value) {
			global $wpdb;
			$meta = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE meta_key='".$wpdb->escape($key)."' AND meta_value='".$wpdb->escape($value)."'");
			if (is_array($meta) && !empty($meta) && isset($meta[0])) {
				$meta = $meta[0];
			}		
			if (is_object($meta)) {
				return $meta->post_id;
			}
			else {
				return false;
			}
		}
	}
}	

/**
 * The main function responsible for returning the one true WPMasonryGallery
 * instance to functions everywhere
 *
 * @since       0.1
 * @return      \WPMasonryGallery The one true WPMasonryGallery
 *
 */
function WPMasonryGallery_load() 
{
    return WPMasonryGallery::instance();
}
add_action( 'plugins_loaded', 'WPMasonryGallery_load' );

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