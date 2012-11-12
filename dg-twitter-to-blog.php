<?php
/*
Plugin Name: Twitter posts to Blog
Description: Post twetts to your blog
Version: 0.1
Author: Damian Gomez

Copyleft 2012, racksoft@gmail.com

I take NO RESPONSIBILITY on how you use of this plugin and/or any problem caused using this plugin, do what you want with it!
*/
$dg_tw_queryes = array();
$dg_tw_time = '';
$dg_tw_publish = '';

/*
 * SETUP THE CRON
*/
function dg_tw_load_next_items() {
	error_log('Inizio cron!!!');
	
	global $dg_tw_queryes, $dg_tw_time, $dg_tw_publish, $dg_tw_tags, $wpdb;

	if (!function_exists('curl_init'))
	{
		error_log('The DG Twitter to blog plugin require CURL libraries');
		return;
	}


	foreach($dg_tw_queryes as $query) {
		error_log('Entro nel foreach!!!');
		$dg_tw_url_compose = "http://search.twitter.com/search.json?q=".urlencode($query['value'])."&since_id=".$query['last_id'];
		$dg_tw_data = dg_tw_curl_file_get_contents($dg_tw_url_compose);
		$dg_tw_data= json_decode($dg_tw_data, true);

		if(count($dg_tw_data['results'])) {
			$dg_tw_queryes[urlencode($query['value'])]['last_id'] = $dg_tw_data['results'][0]['id_str'];
			update_option('dg_tw_queryes',$dg_tw_queryes);
			$dg_tw_queryes = get_option('dg_tw_queryes');
		}

		$dg_result = array_reverse($dg_tw_data['results']);

		foreach($dg_result as $item) {
			$querystr = "SELECT *
			FROM $wpdb->postmeta
			WHERE (meta_key = 'dg_tw_id' AND meta_value = '".(int) $item['id_str']."')
						GROUP BY post_id";
						
						$postid = $wpdb->get_results($querystr);
				
			$time = strtotime($item['created_at']);
				
			if(!count($postid)) {
				$post = array(
						'post_author'    => 0,
						'post_content'   => '<div class="twitter-post"><a href="http://twitter.com/statuses/'.$item['id_str'].'"><img src="https://api.twitter.com/1/users/profile_image?user_id='.$item['from_user_id'].'&size=bigger" alt="" align="baseline" border="0" /></a> '.$item['text'].'</div>',
						'post_name'      => dg_tw_slug($item['text']),
						'post_status'    => strval($dg_tw_publish),
						'post_title'     => $item['text'],
						'post_category'  => array(1),
						'tags_input'     => $dg_tw_tags,
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
				}
			}
		}
	}
}
add_action('dg_tw_event_start', 'dg_tw_load_next_items');


function dg_tw_schedule($schedules)
{
	// 1 minute
	$schedules['dg_tw_oneminute'] = array(
			'interval'=> 60,
			'display'=> __('Once Every Minute')
	);

	// 5 minutes
	$schedules['dg_tw_fiveminutes'] = array(
			'interval'=> 300,
			'display'=> __('Once Every 5 Minutes')
	);

	// 10 minutes
	$schedules['dg_tw_tenminutes'] = array(
			'interval'=> 600,
			'display'=> __('Once Every 10 Minutes')
	);

	// 20 minutes
	$schedules['dg_tw_twentynminutes'] = array(
			'interval'=> 1200,
			'display'=> __('Once Every 20 Minutes')
	);

	// 30 minutes
	$schedules['dg_tw_twicehourly'] = array(
			'interval'=> 1800,
			'display'=> __('Once Every 30 Minutes')
	);

	// weekly
	$schedules['dg_tw_weekly'] = array(
			'interval'=> 604800,
			'display'=> __('Once Every 7 Days')
	);

	// monthly
	$schedules['dg_tw_monthly'] = array(
			'interval'=> 2592000,
			'display'=> __('Once Every 30 Days')
	);

	return $schedules;
}
add_filter('cron_schedules','dg_tw_schedule');


function dg_add_menu_item() {
	add_options_page( 'Twitter To WP', 'Twitter To WP', 'administrator', 'dg_tw_admin_menu', 'dg_tw_drawpage');
}
add_action('admin_menu', 'dg_add_menu_item');

function dg_tw_drawpage() {
	global $dg_tw_queryes,$dg_tw_time, $dg_tw_publish, $dg_tw_tags;
	require_once('dg_tw_admin_page.php');
}

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

function dg_tw_slug($str) {
	$str = strtolower(trim($str));
	$str = preg_replace('/[^a-z0-9-]/', '-', $str);
	$str = preg_replace('/-+/', "-", $str);
	return $str;
}

function dg_tw_loop_start() {
	add_filter("the_author", "dg_tw_the_author"); // requires 2.0
	add_filter("get_the_author", "dg_tw_the_author"); // requires 2.0
}
add_action("loop_start", "dg_tw_loop_start");

function dg_tw_the_author($author) {
	$custom_fields = get_post_custom();
	
	if (isset($custom_fields["dg_tw_author"])) {
		$author = '@'.implode(", ", $custom_fields["dg_tw_author"]);
	}
	return $author;
}

function dg_tw_activation() {
	global $dg_tw_queryes, $dg_tw_time, $dg_tw_publish, $dg_tw_tags;

	$dg_tw_queryes = get_option('dg_tw_queryes');
	$dg_tw_time = get_option('dg_tw_time');
	$dg_tw_publish = (string) get_option('dg_tw_publish');
	$dg_tw_tags = (string) get_option('dg_tw_tags');
	
	if(!$dg_tw_publish) {
		update_option('dg_tw_publish','draft');
	}
	
	if(!$dg_tw_time) {
		update_option('dg_tw_time','never');
	}
	
	if ( !wp_next_scheduled( 'dg_tw_event_start' ) && $dg_tw_time && $dg_tw_time != "never") {
		$recurrences = wp_get_schedules();
		wp_schedule_event( time()+$recurrences[$dg_tw_time]['interval'], $dg_tw_time, 'dg_tw_event_start');
	}
}
register_activation_hook( __FILE__, 'dg_tw_activation' );

function dg_tw_deactivation() {
	$timestamp = wp_next_scheduled( 'dg_tw_event_start' );
	wp_clear_scheduled_hook( 'dg_tw_event_start' );
	wp_unschedule_event($timestamp, 'dg_tw_event_start');
}
register_deactivation_hook( __FILE__, 'dg_tw_deactivation' );

function dg_tw_options() {
	global $dg_tw_queryes, $dg_tw_time, $dg_tw_publish, $dg_tw_tags;

	if (!function_exists('curl_init'))
	{
		error_log('The DG Twitter to blog plugin require CURL libraries');
		return;
	}

	$dg_tw_queryes = get_option('dg_tw_queryes');
	$dg_tw_time = get_option('dg_tw_time');
	$dg_tw_publish = (string) get_option('dg_tw_publish');
	$dg_tw_tags = (string) get_option('dg_tw_tags');

	error_log("DATA ATTUALE SECONDO WORDPRESS: ".date_i18n(get_option('date_format')));

	if(isset($_POST['dg_tw_data_update'])) {
		$dg_temp_array = array();

		foreach($_POST['dg_tw_item_query'] as $item_query) {
			if(isset($dg_tw_queryes[urlencode($item_query)])) {
				$dg_temp_array[urlencode($item_query)] = $dg_tw_queryes[urlencode($item_query)];
			} else {
				$dg_temp_array[urlencode($item_query)] = array("value"=>$item_query,"last_id"=>0);
			}
		}

		update_option('dg_tw_queryes',$dg_temp_array);
		$dg_tw_queryes = get_option('dg_tw_queryes');
	}

	if(isset($_POST['dg_tw_time_update'])) {
		update_option('dg_tw_time',$_POST['dg_tw_time_selected']);
		$dg_tw_time = get_option('dg_tw_time');

		$timestamp = wp_next_scheduled( 'dg_tw_event_start' );
		wp_clear_scheduled_hook( 'dg_tw_event_start' );
		wp_unschedule_event($timestamp, 'dg_tw_event_start');

		if ( !wp_next_scheduled( 'dg_tw_event_start' ) ) {
			$recurrences = wp_get_schedules();
			wp_schedule_event( time()+$recurrences[$dg_tw_time]['interval'], $dg_tw_time, 'dg_tw_event_start');
		}
		error_log('Twitter to wordpress: changed execution time!');
	}

	if(isset($_POST['dg_tw_publish_mode'])) {
		update_option('dg_tw_publish',$_POST['dg_tw_publish_selected']);
		$dg_tw_publish = (string) get_option('dg_tw_publish');
	}

	if(isset($_POST['dg_tw_tags'])) {
		update_option('dg_tw_tags',$_POST['dg_tw_tag_tweets']);
		$dg_tw_tags = (string) get_option('dg_tw_tags');
	}
}
add_action('wp_loaded', 'dg_tw_options');
?>