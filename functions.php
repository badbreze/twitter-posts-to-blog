<?php
error_reporting(E_ALL);
/*
 * SETUP THE CRON
*/
function dg_tw_load_next_items() {
	global $dg_tw_queryes, $dg_tw_time, $dg_tw_publish, $dg_tw_tags, $dg_tw_cats, $dg_tw_ft, $wpdb,$connection;
	
	if (!function_exists('curl_init')){
		error_log('The DG Twitter to blog plugin require CURL libraries');
		return;
	}
	
	$dg_tw_exclusions = get_option('dg_tw_exclusions');
	
	if(empty($dg_tw_exclusions)) {
		$dg_tw_exclusions = array();
	}
	
	$mega_tweet = array();

	foreach($dg_tw_queryes as $slug=>$query) {
		$parameters = array(
				'q' => $query['value'],
				'since_id' => $query['last_id'],
				'include_entities' => true,
				'count' => $dg_tw_ft['ipp']
		);
		
		error_log('Loop query string \n');
		$dg_tw_data = $connection->get('search/tweets', $parameters);

		//Set the last tweet id
		if(count($dg_tw_data->statuses)) {
			$status = end($dg_tw_data->statuses);
			
			$dg_tw_queryes[urlencode($query['value'])]['last_id'] = $status->id_str;
			update_option('dg_tw_queryes',$dg_tw_queryes);
			$dg_tw_queryes = get_option('dg_tw_queryes');
		}

		foreach($dg_tw_data->statuses as $key=>$item) {
			if($dg_tw_ft['exclude_retweets'] && isset($item->retweeted_status))
				continue;
			
			if($dg_tw_ft['exclude_no_images'] && !count($item->entities->media))
				continue;
			
			if(!isset($dg_tw_ft['method']) || $dg_tw_ft['method'] == 'multiple') {
				if(dg_tw_iswhite($item->text)) {
					$result = dg_tw_publish_tweet($item,$query);
				} //iswhite
			} elseif(!in_array($item->id_str,$dg_tw_exclusions)) {
				$mega_tweet[] = array(
						'text'=>$item->text,
						'author'=> isset($item->user->display_name) ? $item->user->display_name : $item->user->name,
						'id'=>$item->id_str,
						'created_at'=>$item->created_at
				);
				
				$dg_tw_exclusions[] = $item->id_str;
			}
		}
	}
	
	if(!empty($mega_tweet)) {
		dg_tw_publish_mega_tweet($mega_tweet);
		
		update_option('dg_tw_exclusions',$dg_tw_exclusions);
	}
}

/*
 * Add cron times
 */
function dg_tw_schedule($schedules) {
	$schedules['dg_tw_oneminute'] = array(
			'interval'=> 60,
			'display'=> __('Once Every Minute')
	);

	$schedules['dg_tw_fiveminutes'] = array(
			'interval'=> 300,
			'display'=> __('Once Every 5 Minutes')
	);

	$schedules['dg_tw_tenminutes'] = array(
			'interval'=> 600,
			'display'=> __('Once Every 10 Minutes')
	);

	$schedules['dg_tw_twentynminutes'] = array(
			'interval'=> 1200,
			'display'=> __('Once Every 20 Minutes')
	);

	$schedules['dg_tw_twicehourly'] = array(
			'interval'=> 1800,
			'display'=> __('Once Every 30 Minutes')
	);

	$schedules['dg_tw_weekly'] = array(
			'interval'=> 604800,
			'display'=> __('Once Every 7 Days')
	);

	$schedules['dg_tw_bi_weekly'] = array(
			'interval'=> 1209600,
			'display'=> __('Once Every 14 Days')
	);

	$schedules['dg_tw_monthly'] = array(
			'interval'=> 2592000,
			'display'=> __('Once Every 30 Days')
	);

	return $schedules;
}

/*
 * Create admin menu element
 */
function dg_add_menu_item() {
	$privilege = get_option('dg_tw_ft');
	
	add_menu_page( 'Twitter To WP', 'Twitter To WP', $privilege['privileges'], 'dg_tw_admin_menu', 'dg_tw_drawpage', '', 3);
	add_submenu_page( 'dg_tw_admin_menu', 'Manual Posting', 'Manual Posting', $privilege['privileges'], 'dg_tw_retrieve_menu', 'dg_tw_drawpage_retrieve' );
	
	wp_enqueue_script( "twitter-posts-to-blog-js",plugins_url('js/twitter-posts-to-blog.js', __FILE__),array('jquery'));
	wp_enqueue_style( "twitter-posts-to-blog-css", plugins_url('css/twitter-posts-to-blog.css', __FILE__), false, '1.0.0');
}

/*
 * Call admin page for this plugin
 */
function dg_tw_drawpage() {
	global $dg_tw_queryes,$dg_tw_time, $dg_tw_publish, $dg_tw_ft, $dg_tw_tags, $dg_tw_cats,$tokens_error;
	
	require_once('admin_page.php');
}

/*
 * Call admin page for this plugin
 */
function dg_tw_drawpage_retrieve() {
	global $dg_tw_queryes, $dg_tw_publish, $dg_tw_ft, $dg_tw_tags, $dg_tw_cats,$connection,$tokens_error;
	
	require_once('retrieve_page.php');
}

/*
 * Print admin page message for feedback
 */
function dg_tw_feedback() {
	$dg_tw_ft = get_option('dg_tw_ft');
	
	if(isset($dg_tw_ft['feedback']) && $dg_tw_ft['feedback'] == true)
		return true;
	
	?>
		<div class="updated">
			<p>
				Thanks for using this plugin, please leave feedback in the <a href="http://wordpress.org/plugins/twitter-posts-to-blog/">plugin page</a> 
				and if you want you can <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QV5Y8ZNVWGEA8">offer me a beer</a>
				<br/>
				<a href="?page=dg_tw_admin_menu&feedback=true">Close message!</a>
			</p>
		</div>
	<?php
}

/*
 * Simple function to get curl content (json)
 */
function dg_tw_curl_file_get_contents($url) {
	$curl = curl_init();

	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 12);

	$contents = curl_exec($curl);
	curl_close($curl);
	return $contents;
}

/*
 * 
 */
function dg_tw_slug($str) {
	$str = strtolower(trim($str));
	$str = preg_replace('/[^a-z0-9-]/', '-', $str);
	$str = preg_replace('/-+/', "-", $str);
	return $str;
}

/*
 * Check if there is blacklisted words in the text of the tweet
 */
function dg_tw_iswhite($text) {
	global $dg_tw_queryes, $dg_tw_publish, $dg_tw_tags, $dg_tw_cats, $dg_tw_ft, $wpdb;
	
	if(empty($dg_tw_ft['badwords']))
		return true;
	
	$exploded = explode(',',$dg_tw_ft['badwords']);
	
	foreach($exploded as $word) {
		if(empty($word))
			continue;
		
		if(stristr ($text , $word ))
			return false;
	}
	
	return true;
}

/*
 * Starting up author filter dg_tw_the_author
 */
function dg_tw_loop_start() {
	add_filter("the_author", "dg_tw_the_author");
	add_filter("get_the_author", "dg_tw_the_author");
	add_filter("the_author_posts_link", "dg_tw_the_author_link");
	add_filter("author_link", "dg_tw_the_author_url");
}

/*
 * Filter autor name for posts setting the twitter author name and link
 */
function dg_tw_the_author($author) {
	$custom_fields = get_post_custom();
	
	if (isset($custom_fields["dg_tw_author"])) {
		$author = '@'.implode(", ", $custom_fields["dg_tw_author"]);
	}
	return $author;
}

function dg_tw_the_author_link($author) {
	$custom_fields = get_post_custom();
	
	if (isset($custom_fields["dg_tw_author"])) {
		$author = sprintf(
			'<a href="https://twitter.com/%1$s" title="%2$s" rel="author">@%3$s</a>',
			end($custom_fields["dg_tw_author"]),
			end($custom_fields["dg_tw_author"]),
			end($custom_fields["dg_tw_author"])
		);
	}
	
	return $author;
}

function dg_tw_the_author_url($author) {
	$custom_fields = get_post_custom();
	
	if (isset($custom_fields["dg_tw_author"])) {
		$author = "https://twitter.com/".end($custom_fields["dg_tw_author"]);
	}
	
	return $author;
}

/*
 * Plugin activation hook set basic options if not set already, and start cronjobs if necessary
 */
function dg_tw_activation() {
	global $dg_tw_queryes, $dg_tw_time, $dg_tw_publish, $dg_tw_tags, $dg_tw_cats, $dg_tw_ft;

	$dg_tw_queryes = get_option('dg_tw_queryes');
	$dg_tw_time = get_option('dg_tw_time');
	$dg_tw_publish = (string) get_option('dg_tw_publish');
	$dg_tw_tags = (string) get_option('dg_tw_tags');
	$dg_tw_cats = get_option('dg_tw_cats');
	$dg_tw_ft = get_option('dg_tw_ft');
	
	if(!$dg_tw_publish) {
		update_option('dg_tw_publish','draft');
	}
	
	if(!$dg_tw_time) {
		update_option('dg_tw_time',array('run'=>'never'));
	}
	
	if(!$dg_tw_ft) {
		update_option('dg_tw_ft',array(
			'ui'=>true,
			'text'=>true,
			'img_size'=>'bigger',
			'method'=>'multiple',
			'ipp'=>25,
			'author'=>0,
			'title_format'=>'Tweet from %tweet%',
			'privileges'=>'activate_plugins',
			'badwords'=>'',
			'tweetlink'=>false,
			'maxtitle'=>'60'));
	}
	
	if ( !wp_next_scheduled( 'dg_tw_event_start' ) && $dg_tw_time && $dg_tw_time['run'] != "never") {
		$recurrences = wp_get_schedules();
		wp_schedule_event( time()+$recurrences[$dg_tw_time['run']]['interval'], $dg_tw_time['run'], 'dg_tw_event_start');
	}
}

/*
 * Plugin deactivation hook remove cronjobs
 */
function dg_tw_deactivation() {
	$timestamp = wp_next_scheduled( 'dg_tw_event_start' );
	wp_clear_scheduled_hook( 'dg_tw_event_start' );
	wp_unschedule_event($timestamp, 'dg_tw_event_start');
}

function dg_tw_options() {
	global $dg_tw_queryes, $dg_tw_time, $dg_tw_publish, $dg_tw_tags,$dg_tw_cats, $dg_tw_ft,$connection,$tokens_error;
	
	if (!function_exists('curl_init'))
	{
		error_log('The DG Twitter to blog plugin require CURL libraries');
		return;
	}

	$dg_tw_queryes = get_option('dg_tw_queryes');
	$dg_tw_time = get_option('dg_tw_time');
	$dg_tw_publish = (string) get_option('dg_tw_publish');
	$dg_tw_tags = (string) get_option('dg_tw_tags');
	$dg_tw_cats = get_option('dg_tw_cats');
	$dg_tw_ft = get_option('dg_tw_ft');
	
	if(!empty($dg_tw_ft['access_key']) && !empty($dg_tw_ft['access_secret']) && !empty($dg_tw_ft['access_token']) && !empty($dg_tw_ft['access_token_secret'])) {
		$connection = new TwitterOAuth($dg_tw_ft['access_key'], $dg_tw_ft['access_secret'],$dg_tw_ft['access_token'],$dg_tw_ft['access_token_secret']);
	} else {
		$tokens_error = true;
	}
	
	if(isset($_REQUEST['feedback'])) {
		$dg_tw_ft['feedback'] = true;

		update_option('dg_tw_ft',$dg_tw_ft);
		$dg_tw_ft = get_option('dg_tw_ft');
	}

	if(isset($_POST['dg_tw_data_update'])) {
		$dg_temp_array = array();

		/*
		 * Each query string verified to ensure there is no duplicate and save last id
		 */
		if(isset($_POST['dg_tw_item_query']) && is_array($_POST['dg_tw_item_query'])) {
			foreach($_POST['dg_tw_item_query'] as $item_query) {
				if(isset($dg_tw_queryes[urlencode($item_query['value'])])) {
					if($dg_tw_queryes[urlencode($item_query['value'])]['tag'] != $item_query['tag']) {
						$dg_tw_queryes[urlencode($item_query['value'])]['tag'] = $item_query['tag'];
					}
					$dg_temp_array[urlencode($item_query['value'])] = $dg_tw_queryes[urlencode($item_query['value'])];
				} else {
					$dg_temp_array[urlencode($item_query['value'])] = array("value"=>$item_query['value'],"tag"=>$item_query['tag'],"last_id"=>0,"firts_id"=>0);
				}
			}
		}

		update_option('dg_tw_queryes',$dg_temp_array);
		$dg_tw_queryes = get_option('dg_tw_queryes');

		/*
		 * UPDATE CRON TIME
		 * if condition to dont slowdown the cron manager proccess
		 */
		if(isset($_POST['dg_tw_time_selected'])) {
			$current_date = getdate();
			
			$start_data = array(
					'month' => (isset($_POST['dg_tw_time_month'])) ? $_POST['dg_tw_time_month'] : 1,
					'week' => (isset($_POST['dg_tw_time_week'])) ? $_POST['dg_tw_time_week'] : 'Monday',
					'hour' => (isset($_POST['dg_tw_time_hour'])) ? $_POST['dg_tw_time_hour'] : 1,
					'minute' => (isset($_POST['dg_tw_time_minute'])) ? $_POST['dg_tw_time_minute'] : 1
			);
			
			$time_settings = array(
				'run'=>$_POST['dg_tw_time_selected'],
				'start'=>$start_data
			);
			
			update_option('dg_tw_time',$time_settings);
			
			$dg_tw_time = get_option('dg_tw_time');
			$timestamp = wp_next_scheduled( 'dg_tw_event_start' );
			wp_clear_scheduled_hook( 'dg_tw_event_start' );
			wp_unschedule_event($timestamp, 'dg_tw_event_start');
	
			if ( !wp_next_scheduled( 'dg_tw_event_start' ) ) {
				$recurrences = wp_get_schedules();
				
				if($_POST['dg_tw_time_selected'] == 'dg_tw_monthly') {
					$when_start = strtotime($current_date["year"].'/'.$current_date["mon"].'/'.$_POST["dg_tw_time_month"].' '.$start_data["hour"].':'.$start_data["minute"].':00');
					wp_schedule_event( $when_start, $dg_tw_time['run'], 'dg_tw_event_start');
				} elseif($_POST['dg_tw_time_selected'] == 'dg_tw_weekly') {
					$when_start = strtotime($current_date["year"].' '.$current_date["month"].' '.$_POST["dg_tw_time_week"].' '.$start_data["hour"].':'.$start_data["minute"].':00');
					wp_schedule_event( $when_start, $dg_tw_time['run'], 'dg_tw_event_start');
				} elseif($_POST['dg_tw_time_selected'] != 'never') {
					$when_start = strtotime($current_date['year'].'/'.$current_date['mon'].'/'.$current_date['mday'].' '.$start_data['hour'].':'.$start_data['minute'].':00');
					wp_schedule_event( $when_start, $dg_tw_time['run'], 'dg_tw_event_start');
				}
			}
		}
	
		/*
		 * UPDATE FORMATTING OPTIONS
		 */
		$now_ft = $dg_tw_ft;
		$now_ft['access_key'] = $_POST['dg_tw_access_key'];
		$now_ft['access_secret'] = $_POST['dg_tw_access_secret'];
		$now_ft['access_token'] = $_POST['dg_tw_access_token'];
		$now_ft['access_token_secret'] = $_POST['dg_tw_access_token_secret'];
		$now_ft['ui'] = (int) $_POST['dg_tw_ft_ui'];
		$now_ft['text'] = (int) $_POST['dg_tw_ft_text'];
		$now_ft['author'] = (int) $_POST['dg_tw_author'];
		$now_ft['method'] = $_POST['dg_tw_method'];
		$now_ft['img_size'] = $_POST['dg_tw_ft_size'];
		$now_ft['ipp'] = $_POST['dg_tw_ipp'];
		$now_ft['privileges'] = $_POST['dg_tw_privileges'];
		$now_ft['maxtitle'] = $_POST['dg_tw_maxtitle'];
		$now_ft['title_format'] = $_POST['dg_tw_title_format'];
		$now_ft['badwords'] = $_POST['dg_tw_badwords'];
		$now_ft['notags'] = isset($_POST['dg_tw_notags']) ? true : false;
		$now_ft['noreplies'] = isset($_POST['dg_tw_noreplies']) ? true : false; 	
		$now_ft['exclude_retweets'] = isset($_POST['dg_tw_exclude_retweets']) ? true : false;
		$now_ft['exclude_no_images'] = isset($_POST['dg_tw_exclude_no_images']) ? true : false;
		$now_ft['authortag'] = isset($_POST['dg_tw_authortag']) ? true : false;
		$now_ft['tweettime'] = isset($_POST['dg_tw_tweettime']) ? true : false;
		$now_ft['tweetlink'] = isset($_POST['dg_tw_tweetlink']) ? true : false;
		
		update_option('dg_tw_ft',$now_ft);
		$dg_tw_ft = get_option('dg_tw_ft');

		/*
		 * UPDATE PUBLISH MODE
		 */
		update_option('dg_tw_publish',$_POST['dg_tw_publish_selected']);
		$dg_tw_publish = (string) get_option('dg_tw_publish');
	
		/*
		 * UPDATE TAGS
		 */
		update_option('dg_tw_tags',$_POST['dg_tw_tag_tweets']);
		$dg_tw_tags = (string) get_option('dg_tw_tags');
	
		/*
		 * UPDATE CATS
		 */
		if( isset($_POST['post_category']) ){
			update_option('dg_tw_cats',$_POST['post_category']);
			$dg_tw_cats = get_option('dg_tw_cats');
		}
	}
}

/*
 * Create post from tweet
 */
function dg_tw_publish_tweet($tweet,$query = false) {
	global $dg_tw_queryes, $dg_tw_publish, $dg_tw_tags, $dg_tw_cats, $dg_tw_ft, $wpdb;
	
	$username = (isset($tweet->user->display_ame) && !empty($tweet->user->display_name)) ? $tweet->user->display_name : $tweet->user->name;
	$username = (isset($tweet->user->screen_name) && !empty($tweet->user->screen_name)) ? $tweet->user->screen_name : $username;
	
	$current_query = ($query != false) ? $query : array('tag'=>'','value'=>'');
			
	$querystr = "SELECT *
					FROM $wpdb->postmeta
					WHERE (meta_key = 'dg_tw_id' AND meta_value = '".(int) $tweet->id_str."')
					GROUP BY post_id";
				
	$postid = $wpdb->get_results($querystr);
	
	$time = strtotime($tweet->created_at);
	
	$tweet_title = filter_title($tweet);
	$author_tag = ( !empty($dg_tw_ft['authortag']) ) ? ','.$username : '';
	$post_tags = htmlspecialchars($dg_tw_tags.','.$current_query['tag'].$author_tag);
	
	if(!count($postid)) {
		$post = array(
				'post_author'    => $dg_tw_ft['author'],
				'post_content'   => $tweet->text,
				'post_name'      => dg_tw_slug($tweet_title),
				'post_status'    => strval($dg_tw_publish),
				'post_title'     => $tweet_title,
				'post_category'  => $dg_tw_cats,
				'tags_input'     => $post_tags,
				'post_type'      => 'post',
				'post_date'      => date('Y-m-d H:i:s', $time),
				'post_status'    => strval($dg_tw_publish)
		);
		
		$dg_tw_this_post = wp_insert_post( $post, true );
		
		if($dg_tw_this_post) {
			/*INSERT ATTACHMENTS*/
			$attaches_id = array();
			
			if( isset($tweet->entities->media) ) {
				$attaches_id = dg_tw_insert_attachments($tweet->entities->media,$dg_tw_this_post);
			}
			
			// add image as post preview
			set_post_thumbnail( $dg_tw_this_post, end($attaches_id) );
			/*INSERT ATTACHMENTS*/
			
			/*POST METAS*/
			$query_string = urlencode($current_query['value']);
			$query_string = ($query != false) ? $query['value'] : $query_string;
	
			add_post_meta($dg_tw_this_post, 'dg_tw_query', $query_string);
			add_post_meta($dg_tw_this_post, 'dg_tw_id', $tweet->id_str);
			add_post_meta($dg_tw_this_post, 'dg_tw_author', $username);
			add_post_meta($dg_tw_this_post, 'dg_tw_author_avatar', $tweet->user->profile_image_url);
			/*END POST METAS*/

			/*FILTER TEXT*/
			if($dg_tw_ft['ui'] || $dg_tw_ft['text']) {
				$post_content = '<span class="twitter-post">';
			
				if($dg_tw_ft['ui']) {
					foreach($attaches_id as $attach)
						$post_content .= '<img src="'.wp_get_attachment_url($attach).'" alt="'.dg_tw_slug($tweet->text).'" align="baseline" border="0" />&nbsp;';
				}
					
				if($dg_tw_ft['text']) {
					$str = dg_tw_regexText($tweet->text);
					$tweet->text = $str;
					
					$str = preg_replace("/(?<!a href=\")(?<!src=\")((http|ftp)+(s)?:\/\/[^<>\s]+)/i","<a href=\"\\0\" target=\"blank\">\\0</a>",$str);
					$str = preg_replace('|@(\w+)|', '<a href="http://twitter.com/$1" target="_blank">@$1</a>', $str);
					$str = preg_replace('|#(\w+)|', '<a href="http://twitter.com/search?q=%23$1" target="_blank">#$1</a>', $str);
					
					$tweet_link = ($dg_tw_ft['tweetlink']) ? '<p><a href="https://twitter.com/'.$username.'/status/'.$tweet->id_str.'" target="_blank">https://twitter.com/'.$username.'/status/'.$tweet->id_str.'</a></p>' : '';
					$post_content .= '<p>'.$str.'</p>'.$tweet_link;
					$post_title = filter_title($tweet);
				}
					
				$post_content .= '</span>';
				
				$update_post = array();
				$update_post['ID'] = $dg_tw_this_post;
				$update_post['post_content'] = $post_content;
				$update_post['post_title'] = $post_title;
				
				wp_update_post( $update_post );
			}
			/*END FILTER TEXT*/
		}
	} else {
		return "already";
	}
	
	return "true";
}

/*
 * Create post from tweet
 */
function dg_tw_publish_mega_tweet($tweets) {
	global $dg_tw_queryes, $dg_tw_publish, $dg_tw_tags, $dg_tw_cats, $dg_tw_ft, $wpdb;
	
	$content = '<ul id="dg_tw_list_tweets">';
	
	foreach($tweets as $tweet) {
		$str = dg_tw_regexText($tweet['text']);
		$str = preg_replace("/(?<!a href=\")(?<!src=\")((http|ftp)+(s)?:\/\/[^<>\s]+)/i","<a href=\"\\0\" target=\"blank\">\\0</a>",$str);
		$str = preg_replace('|@(\w+)|', '<a href="http://twitter.com/$1" target="_blank">@$1</a>', $str);
		$str = preg_replace('|#(\w+)|', '<a href="http://twitter.com/search?q=%23$1" target="_blank">#$1</a>', $str);
		
		$time_tweet = (!empty($dg_tw_ft['tweettime'])) ? ' - '.date('Y-m-d H:i:s',strtotime($tweet['created_at'])) : '';
		$content .= '<li class="single_tweet">'.$str.$time_tweet.'</li>';
	}

	$content .= '</ul>';

	$tweet_title = (empty($dg_tw_ft['title_format'])) ? "Periodically tweets" : $dg_tw_ft['title_format'];
	
	$post = array(
			'post_author'    => $dg_tw_ft['author'],
			'post_content'   => $content,
			'post_name'      => dg_tw_slug($tweet_title),
			'post_status'    => strval($dg_tw_publish),
			'post_title'     => $tweet_title,
			'post_category'  => $dg_tw_cats,
			'tags_input'     => $dg_tw_tags,
			'post_type'      => 'post',
			'post_status'    => strval($dg_tw_publish)
	);
	
	$dg_tw_this_post = wp_insert_post( $post, true );
	
	return 'true';
}

function dg_tw_regexText($string){
	global $dg_tw_ft;
	if($dg_tw_ft['noreplies']){
		$string = preg_replace('#RT @[\\d\\w]+:#','',$string);
		$string = preg_replace('#@[\\d\\w]+#','',$string); 									
	}
	if($dg_tw_ft['notags']){
		$string = preg_replace('/#[\\d\\w]+/','',$string); 										
	}
	return $string;
}

function filter_title($tweet) {
	global $dg_tw_queryes, $dg_tw_publish, $dg_tw_tags, $dg_tw_cats, $dg_tw_ft, $wpdb;
	
	//$result = $tweet->text;
	$result = (empty($dg_tw_ft['title_format'])) ? $tweet->text : $dg_tw_ft['title_format'];
	
	$username = (isset($tweet->user->display_ame) && !empty($tweet->user->display_ame)) ? $tweet->user->display_ame : $tweet->user->name;
	$username = (isset($tweet->user->screen_name) && !empty($tweet->user->screen_name)) ? $tweet->user->screen_name : $username;
	
	$result = str_replace('%tweet%',$tweet->text,$result);
	$result = str_replace('%author%',$username,$result);
	
	$result = substr($result,0,$dg_tw_ft['maxtitle']);
	
	return $result;
}

/*
 * Attach all founded images to selected post
 */
function dg_tw_insert_attachments($medias,$post_id) {
	$attach_id = false;
	
	foreach( $medias as $media ) {
		if( $media->type=="photo" ) {
			$upload_dir = wp_upload_dir();
			$image_data = file_get_contents($media->media_url);
			$filename = strtolower(pathinfo($media->media_url, PATHINFO_FILENAME)).".".strtolower(pathinfo($media->media_url, PATHINFO_EXTENSION));
			if(wp_mkdir_p($upload_dir['path']))
				$file = $upload_dir['path'] . '/' . $filename;
			else
				$file = $upload_dir['basedir'] . '/' . $filename;
			file_put_contents($file, $image_data);
			$wp_filetype = wp_check_filetype($filename, null );
			$attachment = array(
					'post_mime_type' => $wp_filetype['type'],
					'post_title' => sanitize_file_name($filename),
					'post_content' => '',
					'post_status' => 'inherit'
			);
			$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
			$attaches[] = $attach_id;
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
			wp_update_attachment_metadata( $attach_id, $attach_data );
		}
	}
	
	return $attaches;
}

/*
 * Manual publish
 */
function dg_tw_manual_publish() {
	global $wpdb,$connection,$dg_tw_queryes;
	
	if(!$dg_tw_queryes) {
		$dg_tw_queryes = get_option('dg_tw_queryes');
	}
	
	$tweet_id = $_REQUEST['id'];
	$query = false;
	
	foreach($dg_tw_queryes as $single_query) {
		if($single_query['value'] == $_REQUEST['query']) {
			$query = $single_query;
		}
	}
	
	
	if(empty($tweet_id)) {
		echo "false";
		die();
	}
	
	$parameters = array(
		'id' => $tweet_id,
		'include_entities' => true
	);
		
	$dg_tw_data = $connection->get('statuses/show', $parameters);

	
	if(isset($dg_tw_data->text) && empty($dg_tw_data->text)) {
		echo "nofound";
		die();
	}
	
	$result = dg_tw_publish_tweet($dg_tw_data,$query);

	echo $result;
	die();
}
?>