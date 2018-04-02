<?php
/**
* Plugin - Masonry Gallery
* Template - Viewer
*/

if(isset($_GET['page_id'])){
    $page_id = $_GET['page_id'];
}

$querystr = "SELECT * FROM " . $wpdb->prefix . "page_data WHERE page_id=".$page_id;
$gallery = $wpdb->get_results($querystr, OBJECT);
?>

<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<a href="?page=masonry_gallery">back to index</a>
<div class="container">
	<?php echo base64_decode($gallery[0]->page_content);?>
</div>