<?php 
/**
* Plugin - Masonry Gallery
* Template - Edit
*/
?>
<div id="wpbody" role="main">
	<div id="wpbody-content" aria-label="Main content" tabindex="0">
		<?php
		if(isset($_GET['page_id'])){
			$page_id = $_GET['page_id'];
		}
		$querystr = "SELECT * FROM " . $wpdb->prefix . "page_data WHERE page_id=".$page_id;
		$gallery = $wpdb->get_results($querystr, OBJECT);
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($){
			var bsTooltip = $.fn.tooltip;
			var bsButton = $.fn.button;

			$.widget.bridge('uibutton', $.ui.button);
			$.widget.bridge('uitooltip', $.ui.tooltip);
			$.fn.tooltip = bsTooltip;
			$.fn.button = bsButton;
			
			$(document).on("click", "#savephp", function(g){
				g.preventDefault();
				$(this).val("Please wait... Saving");
				$('input[name="page_data"]').val($('#content_area').keditor('getContent'));
				var page_data = $('#page_data').val();
				var page_id = $('#page_id').val();
				$.ajax({
					type : "POST",
					url : ajaxurl,
					data : {
						action: 'save_layouts',
						postdata : { pagedata: page_data, pageid: page_id }
					},
					success: function(data){
						window.setTimeout(function(){location.reload()},3000);
					}
				});
				
				return false;
			});
		});

		</script>
		<form>
			<div class="absolute" align="center"><input type="submit" value="Save Layout" class="btn btn-primary" id="savephp" /></div>
			<input type="hidden" value="" name="page_data" id="page_data">
			<input type="hidden" value="<?php echo $page_id;?>" name="page_id" id="page_id">
			<div id="content_area">
				
				<?php echo $gallery[0]->page_content;?>
			</div>
		</form>

		<script type="text/javascript">
		jQuery( document ).ready(function($) {

			$('#closeWindowButton').click(function(e){
				e.preventDefault();
				$("#window").fadeOut(100);
			});

			// $("form").submit(function(e){
				// $('input[name="page-data"]').val($('#content-area').keditor('getContent'));
			// });

			$('#content_area').keditor({
			onReady: function () {
				console.log('Callback "onReady"');
			},
			onInitFrame: function (frame, frameHead, frameBody) {
				console.log('Callback "onInitFrame"', frame, frameHead, frameBody);
			},
			onSidebarToggled: function (isOpened) {
				console.log('Callback "onSidebarToggled"', isOpened);
			},
			onInitContentArea: function (contentArea) {
				console.log('Callback "onInitContentArea"', contentArea);
			},
			onContentChanged: function (event) {
				console.log('Callback "onContentChanged"', event);
			},
			onInitContainer: function (container) {
				console.log('Callback "onInitContainer"', container);
			},
			onBeforeContainerDeleted: function (event, selectedContainer) {
				console.log('Callback "onBeforeContainerDeleted"', event, selectedContainer);
			},
			onContainerDeleted: function (event, selectedContainer) {
				console.log('Callback "onContainerDeleted"', event, selectedContainer);
			},
			onContainerChanged: function (event, changedContainer) {
				console.log('Callback "onContainerChanged"', event, changedContainer);
			},
			onContainerDuplicated: function (event, originalContainer, newContainer) {
				console.log('Callback "onContainerDuplicated"', event, originalContainer, newContainer);
			},
			onContainerSelected: function (event, selectedContainer) {
				console.log('Callback "onContainerSelected"', event, selectedContainer);
			},
			onContainerSnippetDropped: function (event, newContainer, droppedContainer) {
				console.log('Callback "onContainerSnippetDropped"', event, newContainer, droppedContainer);
			},
			onComponentReady: function (component) {
				console.log('Callback "onComponentReady"', component);
			},
			onInitComponent: function (component) {
				console.log('Callback "onInitComponent"', component);
			},
			onBeforeComponentDeleted: function (event, selectedComponent) {
				console.log('Callback "onBeforeComponentDeleted"', event, selectedComponent);
			},
			onComponentDeleted: function (event, selectedComponent) {
				console.log('Callback "onComponentDeleted"', event, selectedComponent);
			},
			onComponentChanged: function (event, changedComponent) {
				console.log('Callback "onComponentChanged"', event, changedComponent);
			},
			onComponentDuplicated: function (event, originalComponent, newComponent) {
				console.log('Callback "onComponentDuplicated"', event, originalComponent, newComponent);
			},
			onComponentSelected: function (event, selectedComponent) {
				console.log('Callback "onComponentSelected"', event, selectedComponent);
			},
			onComponentSnippetDropped: function (event, newComponent, droppedComponent) {
				console.log('Callback "onComponentSnippetDropped"', event, newComponent, droppedComponent);
			},
			onBeforeDynamicContentLoad: function (dynamicElement, component) {
				console.log('Callback "onBeforeDynamicContentLoad"', dynamicElement, component);
			},
			onDynamicContentLoaded: function (dynamicElement, response, status, xhr) {
				console.log('Callback "onDynamicContentLoaded"', dynamicElement, response, status, xhr);
			},
			onDynamicContentError: function (dynamicElement, response, status, xhr) {
				console.log('Callback "onDynamicContentError"', dynamicElement, response, status, xhr);
			}
			});

		});
		</script>
		<div id="window">
			<div id="windowContent">
				<?php 
				include(WPMG_INCLUDE_PATH . "templates/upload.php");
				?>
			</div>
		</div>
	</div>
</div>