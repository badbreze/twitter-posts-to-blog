<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>Twitter To Wordpress Autopost</h2>
	<h3>Autopost Next Items</h3>
	
	<?php
		if(empty($dg_tw_queryes)) {
			echo "Nothing to show here";
			exit;
		}
		
		foreach($dg_tw_queryes as $query) {
			$dg_tw_url_compose = "http://search.twitter.com/search.json?q=".urlencode($query['value'])."&since_id=".$query['last_id']."&include_entities=1&rpp=".$dg_tw_ft['ipp'];
			$dg_tw_data = dg_tw_curl_file_get_contents($dg_tw_url_compose);
			$dg_tw_data= json_decode($dg_tw_data, true);
			
			$dg_result = array_reverse($dg_tw_data['results']);
			
			echo "<h3>".$query['value']."</h3>";
			?>
			<table class="wp-list-table widefat fixed posts" cellspacing="0">
				<thead>
					<th scope="col" style="width: 20%;" id="title" class="manage-column sortable desc" style="">
						<span>Author</span>
					</th>
					<th scope="col" id="title" class="manage-column column-title sortable desc" style="">
						<span>Post Content</span>
					</th>
					<th scope="col" id="title" class="manage-column column-title sortable desc" style="">
						<span>Original Content</span>
					</th>
					<th scope="col" id="title" style="width: 10%;" class="manage-column column-title sortable desc" style="">
						<span>Publish</span>
					</th>
				</thead>
				
				<tbody id="the-list">
					<?php
						foreach($dg_result as $item) {
							if(dg_tw_iswhite($item['text'])) {
								$content = dg_tw_regexText( $item['text'] );
								?>
								<tr id="post-190" class="post-190 type-post status-publish format-standard hentry alternate iedit author-self" valign="top">
									<td scope="row">
										<b><?php echo $item['from_user_name']; ?></b>
									</td>
									<td scope="row">
										<?php echo $content; ?>
									</td>
									<td scope="row">
										<?php echo $item['text']; ?>
									</td>
									<td scope="row">
										<button type="button" class="manual_publish" data-query="<?php echo $query['value']; ?>" data-pid="<?php echo $item['id_str']; ?>">Publish</button>
									</td>
								</tr>
								<?php
							}
						}
					?>
				</tbody>
				
				<tfoot>
					<th scope="col" style="width: 20%;" id="title" class="manage-column sortable desc" style="">
						<span>Author</span>
					</th>
					<th scope="col" id="title" class="manage-column column-title sortable desc" style="">
						<span>Post Content</span>
					</th>
					<th scope="col" id="title" class="manage-column column-title sortable desc" style="">
						<span>Original Content</span>
					</th>
					<th scope="col" id="title" style="width: 10%;" class="manage-column column-title sortable desc" style="">
						<span>Publish</span>
					</th>
				</tfoot>
			</table>
			<br><br><br>
			<?php
		}
	?>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	jQuery('.manual_publish').on('click',function () {
		var selected = jQuery(this);
		var pid = selected.data('pid');
		var query = selected.data('query');
		var parent = selected.parent();
		var parent_parent = selected.parent().parent();

		selected.remove();
		parent.append('<span>Publishing...</span>');
		
		var data = {
			action: 'dg_tw_manual_publish',
			id: pid,
			query: query
		};
		
		jQuery.post(ajaxurl, data, function(response) {
			if(response == 'true') {
				parent_parent.remove();
			}
			if(response == 'already') {
				alert('This tweet is already published!');
			}
			if(response == 'nofound') {
				alert('Tweet nowt found, retry later!');
			}
		});
	});
});
</script>