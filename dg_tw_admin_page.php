<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>Twitter To Wordpress Autopost</h2>
	<h3>Autopost params</h3>
	
	<div style="display:block;float:right;width:350px;">
		<div id="side-sortables" class="meta-box-sortabless" style="position:relative;">
			<div class="postbox">
				<h3 class="hndle" style="padding:7px;"><span>Cron Period</span></h3>
				<div class="inside">
					<form method="post" action="#">
						<input type="hidden" name="dg_tw_time_update" value="yes" />
						<span>Choose how much time must pass before load new items, use "never" to disable</span><br/><br/>
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
						</select>&nbsp;&nbsp;
						<input class="button-primary" type="submit" name="save" value="Save Now">
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<div style="display:block;float:right;width:350px;clear: both;">
		<div id="side-sortables" class="meta-box-sortabless" style="position:relative;">
			<div class="postbox">
				<h3 class="hndle" style="padding:7px;"><span>Autopublish status</span></h3>
				<div class="inside">
					<form method="post" action="#">
						<input type="hidden" name="dg_tw_publish_mode" value="yes" />
						<span>Choose how the plugin create articles: published or draft</span><br/><br/>
						<select name="dg_tw_publish_selected">
							<option value="publish"<?php if ($dg_tw_publish === 'publish') echo ' selected=selected'; ?>>Published</option>
							<option value="draft"<?php if ($dg_tw_publish === 'draft') echo ' selected=selected'; ?>>Draft</option>
						</select>&nbsp;&nbsp;
						<input class="button-primary" type="submit" name="save" value="Save Now">
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<div style="display:block;float:right;width:350px;clear: both;">
		<div id="side-sortables" class="meta-box-sortabless" style="position:relative;">
			<div class="postbox">
				<h3 class="hndle" style="padding:7px;"><span>Some tags?</span></h3>
				<div class="inside">
					<form method="post" action="#">
						<input type="hidden" name="dg_tw_tags" value="yes" />
						<span>Type tags you want append to each tweet</span><br/><br/>
						<input type="text" size="60" name="dg_tw_tag_tweets" class="regular-text" value="<?php echo $dg_tw_tags; ?>">&nbsp;&nbsp;
						<input class="button-primary" type="submit" name="save" value="Save Now">
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<form method="post" action="#" style="display:block;margin-right:370px;">
		<input type="hidden" name="dg_tw_data_update" value="yes" />
		<div class="postbox">
			<h3 class="hndle" style="padding:7px;"><span>From Twitter Search</span></h3>
			<div class="inside" id="dg_tw_elements">
				<p>You can add more item by click the ADD button below</p>
				<p style="text-align:left;margin-bottom:40px;">
					Add query string: 
					<input type="text" id="dg_tw_add_title" size="60" name="dg_tw_query" class="regular-text" value=""> 
					<input type="button" id="dg_tw_add_element" name="add_feed" value="Add" class="button-primary">
 				</p>
				<div id="dg_tw_elements_selected">
					<?php if(!empty($dg_tw_queryes)) foreach($dg_tw_queryes as $query_element) { ?>
						<p style="text-align:left;padding:5px;">
							<input class="button-primary dg_tw_button_remove" type="button" name="delete" value="Delete"> 
							<input type="text" size="30" class="regular-text" name="dg_tw_item_query[]" value="<?php echo $query_element['value']; ?>">
							<span> - Last id: <a target="_blank" href="https://twitter.com/search?q=<?php echo urlencode($query_element['value']); ?>&since_id=<?php echo $query_element['last_id']; ?>"><?php echo $query_element['last_id']; ?></a></span> 
						</p>
					<?php } ?>
				</div>
				<input class="button-primary" type="submit" name="save" value="Save Now">
				</div>
			</div>
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