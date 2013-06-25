<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>Twitter To Wordpress Autopost</h2>
	<h3>Autopost params</h3>
	
	<form method="post">
		<input type="hidden" name="dg_tw_data_update" value="yes" />
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<td scope="row">
						<b>Twitter app settings:</b>
					</td>
					<td>
						<span class="description">Consumer key:</span><br/>
						<input type="text" size="60" name="dg_tw_access_key" class="regular-text" value="<?php echo @$dg_tw_ft['access_key']; ?>"><br/><br/>
						<span class="description">Consumer secret:</span><br/>
						<input type="text" size="60" name="dg_tw_access_secret" class="regular-text" value="<?php echo @$dg_tw_ft['access_secret']; ?>"><br/><br/>
						<span class="description">Access token:</span><br/>
						<input type="text" size="60" name="dg_tw_access_token" class="regular-text" value="<?php echo @$dg_tw_ft['access_token']; ?>"><br/><br/>
						<span class="description">Access token secret:</span><br/>
						<input type="text" size="60" name="dg_tw_access_token_secret" class="regular-text" value="<?php echo @$dg_tw_ft['access_token_secret']; ?>">
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<b>Capabilities:</b>
					</td>
					<td>
						<span class="description">Who can see this page and change settings:</span><br/>
						<select name="dg_tw_privileges">
							<option value="activate_plugins"<?php if (isset($dg_tw_ft['privileges']) && $dg_tw_ft['privileges'] === 'activate_plugins') echo ' selected=selected'; ?>>Administrator</option>
							<option value="delete_pages"<?php if (isset($dg_tw_ft['privileges']) && $dg_tw_ft['privileges'] === 'delete_pages') echo ' selected=selected'; ?>>Editor</option>
							<option value="delete_posts"<?php if (isset($dg_tw_ft['privileges']) && $dg_tw_ft['privileges'] === 'delete_posts') echo ' selected=selected'; ?>>Author</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row" style="width:23%;">
						<b>Cron time:</b>
					</td>
					<td>
						<span class="description">Choose how much time must pass before load new items, use "never" to disable</span><br/>
						<select name="dg_tw_time_selected" id="dg_tw_time_selected">
							<option value="never"<?php if ( isset($dg_tw_time['run']) && $dg_tw_time['run'] === 'never') echo ' selected=selected'; ?>>never</option>
							<option value="dg_tw_oneminute"<?php if ( isset($dg_tw_time['run']) && $dg_tw_time['run'] === 'dg_tw_oneminute') echo ' selected=selected'; ?>>every minute</option>
							<option value="dg_tw_fiveminutes"<?php if ( isset($dg_tw_time['run']) && $dg_tw_time['run'] === 'dg_tw_fiveminutes') echo ' selected=selected'; ?>>every 5 minutes</option>
							<option value="dg_tw_tenminutes"<?php if ( isset($dg_tw_time['run']) && $dg_tw_time['run'] === 'dg_tw_tenminutes') echo ' selected=selected'; ?>>every 10 minutes</option>
							<option value="dg_tw_twentynminutes"<?php if ( isset($dg_tw_time['run']) && $dg_tw_time['run'] === 'dg_tw_twentynminutes') echo ' selected=selected'; ?>>every 20 minutes</option>
							<option value="dg_tw_twicehourly"<?php if ( isset($dg_tw_time['run']) && $dg_tw_time['run'] === 'dg_tw_twicehourly') echo ' selected=selected'; ?>>every 30 minutes</option>
							<option value="hourly"<?php if ( isset($dg_tw_time['run']) && $dg_tw_time['run'] === 'hourly') echo ' selected=selected'; ?>>hourly</option>
							<option value="twicedaily"<?php if ( isset($dg_tw_time['run']) && $dg_tw_time['run'] === 'twicedaily') echo ' selected=selected'; ?>>twice a day</option>
							<option value="daily"<?php if ( isset($dg_tw_time['run']) && $dg_tw_time['run'] === 'daily') echo ' selected=selected'; ?>>daily</option>
							<option value="dg_tw_weekly"<?php if ( isset($dg_tw_time['run']) && $dg_tw_time['run'] === 'dg_tw_weekly') echo ' selected=selected'; ?>>weekly</option>
							<option value="dg_tw_monthly"<?php if ( isset($dg_tw_time['run']) && $dg_tw_time['run'] === 'dg_tw_monthly') echo ' selected=selected'; ?>>monthly</option>
						</select><br/><br/>
						<div id="dg_tw_cycle_selectors">
							<span class="description">Choose the cycle time (this is the start date be carefuly)</span><br/>
							Day of the month: 
							<select name="dg_tw_time_month">
								<optgroup label="Day of the Month">
									<?php
										for($i = 1; $i <= 31; $i++) {
											$selected = (isset($dg_tw_time['start']['month']) && $dg_tw_time['start']['month'] == $i) ? "selected" : "";
											echo '<option '.$selected.' value="'.$i.'">'.$i.'</option>';
										}
									?>
								</optgroup>
							</select><br/>
							Day of the week: 
							<select name="dg_tw_time_week">
								<optgroup label="Day of the Week">
									<?php
										$array_week = array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
										
										foreach($array_week as $day) {
											$selected = (isset($dg_tw_time['start']['week']) && $dg_tw_time['start']['week'] == $day) ? "selected" : "";
											echo '<option '.$selected.' value="'.$day.'">'.$day.'</option>'; 
										}
									?>
								</optgroup>
							</select><br/>
						</div>
						
						Time: 
						<select name="dg_tw_time_hour">
							<optgroup label="Hour">
								<?php
									for($i = 0; $i <= 23; $i++) {
										$selected = (isset($dg_tw_time['start']['hour']) &&$dg_tw_time['start']['hour'] == $i) ? "selected" : "";
										echo '<option '.$selected.' value="'.$i.'">'.$i.'</option>';
									}
								?>
							</optgroup>
						</select>&nbsp;:&nbsp;
						<select name="dg_tw_time_minute">
							<optgroup label="Minute">
								<?php
									for($i = 1; $i <= 59; $i++) {
										$selected = (isset($dg_tw_time['start']['minute']) &&$dg_tw_time['start']['minute'] == $i) ? "selected" : "";
										echo '<option '.$selected.' value="'.$i.'">'.$i.'</option>';
									}
								?>
							</optgroup>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<b>Publish settings:</b>
					</td>
					<td>
						<span class="description">Post status: published or draft</span><br/>
						<select name="dg_tw_publish_selected">
							<option value="publish"<?php if ($dg_tw_publish === 'publish') echo ' selected=selected'; ?>>Published</option>
							<option value="draft"<?php if ($dg_tw_publish === 'draft') echo ' selected=selected'; ?>>Draft</option>
						</select><br/><br/>
						<span class="description">Post method</span><br/>
						<select name="dg_tw_method">
							<option value="multiple" <?php if (isset($dg_tw_ft['method']) && $dg_tw_ft['method'] === 'multiple') echo 'selected=selected'; ?>>One post per tweet</option>
							<option value="single" <?php if (isset($dg_tw_ft['method']) && $dg_tw_ft['method'] === 'single') echo 'selected=selected'; ?>>All tweets in one post</option>
						</select><br/><br/>
						<span class="description">Post author:</span><br/>
						<?php
							$args = array(
									'orderby'                 => 'display_name',
									'order'                   => 'ASC',
									'multi'                   => false,
									'show'                    => 'display_name',
									'echo'                    => true,
									'selected'                => isset($dg_tw_ft['author']) ? $dg_tw_ft['author'] : null,
									'include_selected'        => true,
									'name'                    => 'dg_tw_author',
									'blog_id'                 => $GLOBALS['blog_id']
							);

							wp_dropdown_users( $args );
						?>
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
						<input type="checkbox" name="dg_tw_ft_ui" value="1" <?php if (isset($dg_tw_ft['ui']) && $dg_tw_ft['ui']) echo ' checked=checked'; ?>/>
						&nbsp;
						<span class="description">Insert user image in body</span><br/>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<b>Body text:</b>
					</td>
					<td>
						<input type="checkbox" name="dg_tw_ft_text" value="1" <?php if (isset($dg_tw_ft['text']) && $dg_tw_ft['text']) echo ' checked=checked'; ?>/>
						&nbsp;
						<span class="description">Insert tweet text in body</span><br/>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<b>Image size:</b>
					</td>
					<td>
						<span class="description">Select user image size:</span><br/>
						<select name="dg_tw_ft_size">
							<option value="original"<?php if (isset($dg_tw_ft['img_size']) && $dg_tw_ft['img_size'] === 'original') echo ' selected=selected'; ?>>Original</option>
							<option value="mini"<?php if (isset($dg_tw_ft['img_size']) && $dg_tw_ft['img_size'] === 'mini') echo ' selected=selected'; ?>>Mini - 24px by 24px</option>
							<option value="normal"<?php if (isset($dg_tw_ft['img_size']) && $dg_tw_ft['img_size'] === 'normal') echo ' selected=selected'; ?>>Normal - 48px by 48px</option>
							<option value="bigger"<?php if (isset($dg_tw_ft['img_size']) && $dg_tw_ft['img_size'] === 'bigger') echo ' selected=selected'; ?>>Bigger - 73px by 73px</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<b>Items at time:</b>
					</td>
					<td>
						<span class="description">How many item want to load each time the cron run:</span><br/>
						<input type="text" size="60" name="dg_tw_ipp" class="regular-text" value="<?php echo isset($dg_tw_ft['ipp']) ? $dg_tw_ft['ipp'] : ''; ?>">
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<b>Title Settings:</b>
					</td>
					<td>
						<span class="description">Title formatting: (eg. %tweet%,%author% )</span><br/>
						<input type="text" size="60" name="dg_tw_title_format" class="regular-text" value="<?php echo isset( $dg_tw_ft['title_format'] ) ? $dg_tw_ft['title_format'] : ''; ?>"><br/><br/>
						
						<span class="description">Set the maximum length in characters of the title;</span><br/>
						<input type="text" size="60" name="dg_tw_maxtitle" class="regular-text" value="<?php echo isset( $dg_tw_ft['maxtitle'] ) ? $dg_tw_ft['maxtitle'] : ''; ?>">
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<b>Words blacklist:</b>
					</td>
					<td>
						<span class="description">Does not post tweets with these words:</span><br/>
						<input type="text" size="60" name="dg_tw_badwords" class="regular-text" value="<?php echo isset( $dg_tw_ft['badwords'] ) ? $dg_tw_ft['badwords']: ''; ?>">
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<b>Post Modifications:</b>
					</td>
					<td>
						<input type="checkbox" name="dg_tw_notags" <?php if( !empty($dg_tw_ft['notags']) ) echo 'checked'; ?> />
						<span class="description">Remove all hashtags from posts</span><br/>
						<input type="checkbox" name="dg_tw_noreplies" <?php if( !empty($dg_tw_ft['noreplies']) ) echo 'checked'; ?> />
						<span class="description">Remove all @replies from posts (removes retweet "RT @user:" text as well)</span>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<b>Post Exclusions:</b>
					</td>
					<td>
						<i>(this may publish less items each time the cron run)</i><br/>
						<input type="checkbox" name="dg_tw_exclude_retweets" <?php if( !empty($dg_tw_ft['exclude_retweets']) ) echo 'checked'; ?> />
						<span class="description">Exclude retweets</span><br/>
						<input type="checkbox" name="dg_tw_exclude_no_images" <?php if( !empty($dg_tw_ft['exclude_no_images']) ) echo 'checked'; ?> />
						<span class="description">Exclude if no images</span>
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
									<input type="text" size="20" class="regular-text" name="dg_tw_item_query[<?php echo $query_element['value']; ?>][value]" value="<?php echo $query_element['value']; ?>">
									&nbsp;&nbsp;&nbsp;tag:&nbsp;<input type="text" size="20" name="dg_tw_item_query[<?php echo $query_element['value']; ?>][tag]" value="<?php echo $query_element['tag']; ?>">
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