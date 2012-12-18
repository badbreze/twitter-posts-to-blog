<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>Twitter To Wordpress Autopost</h2>
	<h3>Autopost params</h3>
	
	<form method="post" action="#">
		<input type="hidden" name="dg_tw_data_update" value="yes" />
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<td scope="row" style="width:23%;">
						<b>Cron time:</b>
					</td>
					<td>
						<span class="description">Choose how much time must pass before load new items, use "never" to disable</span><br/>
						<select name="dg_tw_time_selected">
							<option value="never"<?php if ($dg_tw_time === 'never') echo ' selected=selected'; ?>>never</option>
							<option value="dg_tw_oneminute"<?php if ($dg_tw_time === 'dg_tw_oneminute') echo ' selected=selected'; ?>>every minute</option>
							<option value="dg_tw_fiveminutes"<?php if ($dg_tw_time === 'dg_tw_fiveminutes') echo ' selected=selected'; ?>>every 5 minutes</option>
							<option value="dg_tw_tenminutes"<?php if ($dg_tw_time === 'dg_tw_tenminutes') echo ' selected=selected'; ?>>every 10 minutes</option>
							<option value="dg_tw_twentynminutes"<?php if ($dg_tw_time === 'dg_tw_twentynminutes') echo ' selected=selected'; ?>>every 20 minutes</option>
							<option value="dg_tw_twicehourly"<?php if ($dg_tw_time === 'dg_tw_twicehourly') echo ' selected=selected'; ?>>every 30 minutes</option>
							<option value="hourly"<?php if ($dg_tw_time === 'hourly') echo ' selected=selected'; ?>>hourly</option>
							<option value="twicedaily"<?php if ($dg_tw_time === 'twicedaily') echo ' selected=selected'; ?>>twice a day</option>
							<option value="daily"<?php if ($dg_tw_time === 'daily') echo ' selected=selected'; ?>>daily</option>
							<option value="dg_tw_weekly"<?php if ($dg_tw_time === 'dg_tw_weekly') echo ' selected=selected'; ?>>weekly</option>
							<option value="dg_tw_monthly"<?php if ($dg_tw_time === 'dg_tw_monthly') echo ' selected=selected'; ?>>monthly</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<b>Publish status:</b>
					</td>
					<td>
						<span class="description">Choose how the plugin create articles: published or draft</span><br/>
						<select name="dg_tw_publish_selected">
							<option value="publish"<?php if ($dg_tw_publish === 'publish') echo ' selected=selected'; ?>>Published</option>
							<option value="draft"<?php if ($dg_tw_publish === 'draft') echo ' selected=selected'; ?>>Draft</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<b>Post tags:</b>
					</td>
					<td>
						<span class="description">Type tags you want append to each tweet (dont use query strings here)</span><br/>
						<input type="text" size="60" name="dg_tw_tag_tweets" class="regular-text" value="<?php echo $dg_tw_tags; ?>">
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<b>Post category:</b>
					</td>
					<td>
						<span class="description">Select categories for tweets</span><br/>
						<ul class="list:category categorychecklist form-no-clear">					
							<?php
								$selected_cats = $dg_tw_cats;
								wp_terms_checklist(0,
													array(
														'taxonomy' => 'category',
														'descendants_and_self' => 0,
														'selected_cats' => $selected_cats,
														'popular_cats' => false,
														'walker' => null,
														'checked_ontop' => false
								));
							?>
						</ul>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<b>Body images:</b>
					</td>
					<td>
						<span class="description">Insert user image in body</span><br/>
						<input type="checkbox" name="dg_tw_ft_ui" class="regular-text" value="1" <?php if ($dg_tw_ft['ui']) echo ' checked=checked'; ?>/>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<b>Body text:</b>
					</td>
					<td>
						<span class="description">Insert tweet text in body</span><br/>
						<input type="checkbox" name="dg_tw_ft_text" class="regular-text" value="1" <?php if ($dg_tw_ft['text']) echo ' checked=checked'; ?>/>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<b>Image size:</b>
					</td>
					<td>
						<span class="description">Select user image size:</span><br/>
						<select name="dg_tw_ft_size">
							<option value="original"<?php if ($dg_tw_ft['img_size'] === 'original') echo ' selected=selected'; ?>>Original</option>
							<option value="mini"<?php if ($dg_tw_ft['img_size'] === 'mini') echo ' selected=selected'; ?>>Mini - 24px by 24px</option>
							<option value="normal"<?php if ($dg_tw_ft['img_size'] === 'normal') echo ' selected=selected'; ?>>Normal - 48px by 48px</option>
							<option value="bigger"<?php if ($dg_tw_ft['img_size'] === 'bigger') echo ' selected=selected'; ?>>Bigger - 73px by 73px</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<b>Items at time:</b>
					</td>
					<td>
						<span class="description">How many item want to load each time the cron run:</span><br/>
						<input type="text" size="60" name="dg_tw_ipp" class="regular-text" value="<?php echo $dg_tw_ft['ipp']; ?>">
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<b>Privilege:</b>
					</td>
					<td>
						<span class="description">Who can use this plugin:</span><br/>
						<select name="dg_tw_privileges">
							<option value="level_10"<?php if ($dg_tw_ft['privileges'] === 'level_10') echo ' selected=selected'; ?>>Administrator - Level 10</option>
							<option value="level_7"<?php if ($dg_tw_ft['privileges'] === 'level_7') echo ' selected=selected'; ?>>Editor - Level 7</option>
							<option value="level_2"<?php if ($dg_tw_ft['privileges'] === 'level_2') echo ' selected=selected'; ?>>Author - Level 2</option>
							<option value="level_1"<?php if ($dg_tw_ft['privileges'] === 'level_1') echo ' selected=selected'; ?>>Contributor - Level 1</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<b>Your search queryes</b>
					</td>
					<td>
						<span class="description">You can add more item by click the ADD button below</span><br/>
						<input type="text" id="dg_tw_add_title" size="60" name="dg_tw_query" class="regular-text" value=""> 
									<input type="button" id="dg_tw_add_element" name="add_feed" value="Add" class="button-primary">
					</td>
				</tr>
				<tr valign="top">
					<td scope="row"></td>
					<td>
						<span class="description">Current queryes</span><br/>
						<div id="dg_tw_elements_selected">
							<?php if(!empty($dg_tw_queryes)) foreach($dg_tw_queryes as $query_element) { ?>
								<p style="text-align:left;padding:5px;">
									<input class="button-primary dg_tw_button_remove" type="button" name="delete" value="Delete"> 
									<input type="text" size="30" class="regular-text" name="dg_tw_item_query[]" value="<?php echo $query_element['value']; ?>">
									<span> - Last id: <a target="_blank" href="https://twitter.com/search?q=<?php echo urlencode($query_element['value']); ?>&since_id=<?php echo $query_element['last_id']; ?>"><?php echo $query_element['last_id']; ?></a></span> 
								</p>
							<?php } ?>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" value="Save settings" class="button-primary" id="submit" name="submit"/>
		</p>
	</form>
</div>
<script type="text/javascript">
	jQuery('#dg_tw_add_element').click(function() {
		if(jQuery('#dg_tw_add_title').val().length != 0) {
			jQuery('#dg_tw_elements_selected').append('<p style="text-align:left;padding:5px;"><input class="button-primary dg_tw_button_remove" type="button" name="delete" value="Delete"><input type="text" size="30" class="regular-text" name="dg_tw_item_query[]" value="'+jQuery('#dg_tw_add_title').val()+'"></span></p>');
			jQuery('#dg_tw_add_title').attr('value','')
		} else {
			alert('Fill the query string box!');
		}
	});
	jQuery('.dg_tw_button_remove').live('click',function() {
		jQuery(this).parent().remove();
	});
</script>