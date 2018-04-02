<?php 
/**
* Plugin - Masonry Gallery
* Template - Attachments
*/
?>
<div id="wpbody" role="main">
	<div id="wpbody-content" aria-label="Main content" tabindex="0">
		<?php 	
		$args = array(
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_status' => null,
			'post_parent' => null, // any parent
			); 
		$attachments = get_posts($args);

		if ( $attachments ) {
			$snippet = 22;
			foreach ( $attachments as $attachment ) 
			{
				echo "<div class='col-md-4 col-sm-4 customhw'>";
				$snippet++;
				$videos = get_attached_media( 'video', $attachment->ID );
				$ia = wp_get_attachment_image_src( $attachment->ID, 'medium' );
				$filename = wp_get_attachment_url( $attachment->ID, 'thumbnail' );
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				if($ext == "mp4"){
					?>
					<div data-type="component-video" data-preview="snippets/default/preview/video-preview.png" data-keditor-title="Video" data-keditor-categories="Media">
						<div class="video-wrapper">
							<video width="100%" height="" controls style="background: #222;">
								<source src="<?php echo $filename;?>" type="video/mp4" />
								<source src="<?php echo $filename;?>" type="video/ogg" />
							</video>
						</div>
					</div>
					<?php 
				}
				?>
				<div data-type="component-photo" data-preview="<?php echo wp_get_attachment_image_url( $attachment->ID, 'full' ); ?>" data-keditor-title="Photo" data-keditor-categories="Media;Photo">
					<?php echo $attachment->ID;?>
					<div class="photo-panel">
						<img src="<?php echo wp_get_attachment_image_url( $attachment->ID, 'full' ); ?>" width="<?php echo $ia[1]; ?>" height="<?php echo $ia[2]; ?>" />
					</div>
				</div>
				<?php 
				echo "</div>";
			}
		}
		?>
	</div>
</div>