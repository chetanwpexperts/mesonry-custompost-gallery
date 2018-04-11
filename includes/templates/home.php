<?php 
/**
* Plugin - Masonry Gallery
* Template - Home
*/
?>
<h2>Add New Mesonry Layout</h2>
<style>
section {
    width: 68%;
}
.photo-panel img {
    height: auto !important;
}
</style>
<script type="text/javascript">
		jQuery(document).ready(function($){
			$(document).on("change", "#assigntopost", function(g){
				g.preventDefault();
				var postid = $(this).val();
				var layoutid = $(this).find(':selected').attr('data-pID');
				$.ajax({
					type : "POST",
					url : ajaxurl,
					data : {
						action: 'assign_layouts_to_post',
						postid:postid,
						layoutid:layoutid,
					},
					success: function(data){
						window.setTimeout(function(){location.reload()},3000);
					}
				});
				
				return false;
			});
		});

		</script>
<?php 
$querystr = "SELECT * FROM " . $wpdb->prefix . "page_data";
$allLayouts = $wpdb->get_results($querystr, OBJECT);
?>
<a href="?page=add_layout" class="btn btn-primary pull-left" id="add" style="width:8%"><i class="fa fa-plus"></i> Add New </a>
<p>&nbsp;</p> 
<p>&nbsp;</p> 
<table>
	<tr>
		<th>Page ID</th>
		<th>Page Title</th>
		<th>Layout Preview</th>
		<th>Assign</th>
		<th>Layout Shortcode</th>
		<th>Action</th>
	</tr>
	<?php
	$count = 0;
	foreach($allLayouts as $layout) : 
	$count++;
	?>
	<tr>
		<td width="8%"><?php echo $layout->page_id;?></td>
		<td width="8%"><?php echo $layout->page_title;?> <?php echo $count;?></td>
		<td width="40%"> <?php echo $layout->page_content;?> </td>
		<td><?php echo $this->generate_post_select("assigntopost", "artprojects", $layout->page_id, $selected = 0);?></td>
		<td><strong>[mesonry_layout ID="<?php echo $this->get_post_id_by_meta_key_and_value('layout_id', $layout->page_id);?>"]</strong></td>
		<td><a href="?page=edit_gallery&page_id=<?php echo $layout->page_id;?>">Edit</a> </td>
	</tr>
	<?php 
	endforeach;
	?>
</table>