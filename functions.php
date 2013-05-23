<?php
/*
 * SETUP THE CRON
*/
function dg_tw_load_next_items() {
	global $dg_tw_queryes, $dg_tw_time, $dg_tw_publish, $dg_tw_tags, $dg_tw_cats, $dg_tw_ft, $wpdb;

	if (!function_exists('curl_init'))
	{
		error_log('The DG Twitter to blog plugin require CURL libraries');
		return;
	}

	foreach($dg_tw_queryes as $query) {
		$dg_tw_url_compose = "http://search.twitter.com/search.json?q=".urlencode($query['value'])."&since_id=".$query['last_id']."&include_entities=1&rpp=".$dg_tw_ft['ipp'];
		$dg_tw_data = dg_tw_curl_file_get_contents($dg_tw_url_compose);
		$dg_tw_data= json_decode($dg_tw_data, true);

		if(count($dg_tw_data['results'])) {
			$dg_tw_queryes[urlencode($query['value'])]['last_id'] = $dg_tw_data['results'][0]['id_str'];
			update_option('dg_tw_queryes',$dg_tw_queryes);
			$dg_tw_queryes = get_option('dg_tw_queryes');
		}

		$dg_result = array_reverse($dg_tw_data['results']);
		foreach($dg_result as $item) {
			if(dg_tw_iswhite($item['text'])) {
				$querystr = "SELECT *
				FROM $wpdb->postmeta
				WHERE (meta_key = 'dg_tw_id' AND meta_value = '".(int) $item['id_str']."')
							GROUP BY post_id";
							
							$postid = $wpdb->get_results($querystr);
					
				$time = strtotime($item['created_at']);
				
				$attach_id = 0;
				
				$attaches = array();
				
				if( isset($item['entities']['media']) ) {
					foreach( $item['entities']['media'] as $media ) {
						if( $media['type']=="photo" ) {
							$upload_dir = wp_upload_dir();
							$image_data = file_get_contents($media['media_url']);
							$filename = strtolower(pathinfo($media['media_url'], PATHINFO_FILENAME)).".".strtolower(pathinfo($media['media_url'], PATHINFO_EXTENSION));
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
							$attach_id = wp_insert_attachment( $attachment, $file, $dg_tw_this_post );
							$attaches[] = $attach_id;
							require_once(ABSPATH . 'wp-admin/includes/image.php');
							$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
							wp_update_attachment_metadata( $attach_id, $attach_data );
						}
					}
				}
	
				$post_content = "";
				
				if($dg_tw_ft['ui'] || $dg_tw_ft['text']) {
					$post_content .= '<div class="twitter-post">';
				
					if($dg_tw_ft['ui']) {
						foreach($attaches as $attach)
							$post_content .= '<img src="'.wp_get_attachment_url($attach).'" alt="'.dg_tw_slug($item['text']).'" align="baseline" border="0" />&nbsp;';
					}
					
					if($dg_tw_ft['text']) {
						$str= preg_replace("/(?<!a href=\")(?<!src=\")((http|ftp)+(s)?:\/\/[^<>\s]+)/i","<a href=\"\\0\" target=\"blank\">\\0</a>",$item['text']);
						
						$post_content .= '<p>'.$str.'</p>';
					}
					
					$post_content .= '</div>';
				}
				
				$item['text'] = substr($item['text'],$dg_tw_ft['maxtitle']);
				$post_tags = htmlspecialchars($dg_tw_tags.','.$query['tag']);
					
				if(!count($postid)) {
					$post = array(
							'post_author'    => 0,
							'post_content'   => $post_content,
							'post_name'      => dg_tw_slug($item['text']),
							'post_status'    => strval($dg_tw_publish),
							'post_title'     => $item['text'],
							'post_category'  => $dg_tw_cats,
							'tags_input'     => $post_tags,
							'post_type'      => 'post',
							'post_date'      => date('Y-m-d H:i:s', $time),
							'post_date_gmt'  => gmdate('Y-m-d H:i:s', $time),
							'post_status'    => strval($dg_tw_publish)
					);
	
					$dg_tw_this_post = wp_insert_post( $post, $wp_error );
					if($dg_tw_this_post) {
						add_post_meta($dg_tw_this_post, 'dg_tw_query', urlencode($query['value']));
						add_post_meta($dg_tw_this_post, 'dg_tw_id', $item['id_str']);
						add_post_meta($dg_tw_this_post, 'dg_tw_author', $item['from_user']);
						
						// add image as post preview
						set_post_thumbnail( $dg_tw_this_post, $attach_id );
					}
				}
			} //iswhite
		}
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
	
	wp_register_style( "twitter-posts-to-blog-css",  plugins_url('css/twitter-posts-to-blog.css', __FILE__), false, '1.0.0');
	
	wp_enqueue_style( "twitter-posts-to-blog-css");
}

/*
 * Call admin page for this plugin
 */
function dg_tw_drawpage() {
	global $dg_tw_queryes,$dg_tw_time, $dg_tw_publish, $dg_tw_ft, $dg_tw_tags, $dg_tw_cats;
	require_once('dg_tw_admin_page.php');
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

function dg_tw_iswhite($text) {
	global $dg_tw_queryes, $dg_tw_time, $dg_tw_publish, $dg_tw_tags, $dg_tw_cats, $dg_tw_ft, $wpdb;
	
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
}

/*
 * Filter autor name for posts setting the twitter author name
 */
function dg_tw_the_author($author) {
	$custom_fields = get_post_custom();
	
	if (isset($custom_fields["dg_tw_author"])) {
		$author = '@'.implode(", ", $custom_fields["dg_tw_author"]);
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
		update_option('dg_tw_time','never');
	}
	
	if(!$dg_tw_ft) {
		update_option('dg_tw_ft',array(
			'ui'=>true,
			'text'=>true,
			'img_size'=>'bigger',
			'ipp'=>25,
			'privileges'=>'activate_plugins',
			'badwords'=>'',
			'maxtitle'=>'60'));
	}
	
	if ( !wp_next_scheduled( 'dg_tw_event_start' ) && $dg_tw_time && $dg_tw_time != "never") {
		$recurrences = wp_get_schedules();
		wp_schedule_event( time()+$recurrences[$dg_tw_time]['interval'], $dg_tw_time, 'dg_tw_event_start');
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
	global $dg_tw_queryes, $dg_tw_time, $dg_tw_publish, $dg_tw_tags,$dg_tw_cats, $dg_tw_ft;

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

	if(isset($_POST['dg_tw_data_update'])) {
		$dg_temp_array = array();

		/*
		 * Each query string verified to ensure there is no duplicate and save last id
		 */
		if(is_array($_POST['dg_tw_item_query'])) {
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
		if($_POST['dg_tw_time_selected'] != $dg_tw_time) {
			update_option('dg_tw_time',$_POST['dg_tw_time_selected']);
			$dg_tw_time = get_option('dg_tw_time');
	
			$timestamp = wp_next_scheduled( 'dg_tw_event_start' );
			wp_clear_scheduled_hook( 'dg_tw_event_start' );
			wp_unschedule_event($timestamp, 'dg_tw_event_start');
	
			if ( !wp_next_scheduled( 'dg_tw_event_start' ) ) {
				$recurrences = wp_get_schedules();
				wp_schedule_event( time()+$recurrences[$dg_tw_time]['interval'], $dg_tw_time, 'dg_tw_event_start');
			}
		}
	
		/*
		 * UPDATE FORMATTING OPTIONS
		 */
		$now_ft = array();
		$now_ft['ui'] = (int) $_POST['dg_tw_ft_ui'];
		$now_ft['text'] = (int) $_POST['dg_tw_ft_text'];
		$now_ft['img_size'] = $_POST['dg_tw_ft_size'];
		$now_ft['ipp'] = $_POST['dg_tw_ipp'];
		$now_ft['privileges'] = $_POST['dg_tw_privileges'];
		$now_ft['maxtitle'] = $_POST['dg_tw_maxtitle'];
		$now_ft['badwords'] = $_POST['dg_tw_badwords'];
		
		update_option('dg_tw_ft',$now_ft);
		$dg_tw_ft = get_option('dg_tw_ft');

		/*
		 * UPDATE PUBLISH MODE
		 */
		update_option('dg_tw_publish',$_POST['dg_tw_publish_selected']);
		$dg_tw_publish = (string) get_option('dg_tw_publish');
	
		/*
		 * UPDATE ATGS
		 */
		update_option('dg_tw_tags',$_POST['dg_tw_tag_tweets']);
		$dg_tw_tags = (string) get_option('dg_tw_tags');
	
		/*
		 * UPDATE CATS
		 */
		update_option('dg_tw_cats',$_POST['post_category']);
		$dg_tw_cats = get_option('dg_tw_cats');
	
	}
}
?>