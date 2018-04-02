<?php
/**
 * Scripts
 *
 * @package     CHETAN\Masonry-Gallery\Scripts
 * @since       1.0.0
 */


// Exit if accessed directly
$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);

if ( !isset($wp_did_header) ) {

    $wp_did_header = true;

    require_once( $path . '/wp-load.php' );

    wp();

    require_once( ABSPATH . WPINC . '/template-loader.php' );

}
$args = array(
	'post_type' => 'attachment',
	'numberposts' => -1,
	'post_status' => null,
	'post_parent' => null, // any parent
	); 
$attachments = get_posts($args);

if ( $attachments ) {
	$snippet = 22;
	foreach ( $attachments as $attachment ) {
		$snippet++;
		$ia = wp_get_attachment_image_src( $attachment->ID, 'full' );
		?>
		<section class="keditor-ui keditor-snippet ui-draggable ui-draggable-handle" data-snippet="#keditor-snippet-<?php echo $snippet;?>" data-type="component-photo" data-toggle="tooltip" data-placement="left" title="Photo" data-keditor-categories="Media;Photo">
			<img width="<?php echo $ia[1]; ?>" height="<?php echo $ia[2]; ?>" src="<?php echo wp_get_attachment_image_url( $attachment->ID, 'thumbnail' ); ?>">
		</section>
		<?php 
	}
}
// if ($attachments) {
	// foreach ($attachments as $post) {
		// setup_postdata($post);
		// the_title();
		// the_attachment_link($post->ID, false);
		// the_excerpt();
	// }
// }
?>