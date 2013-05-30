=== Twitter posts to Blog ===
Contributors: badbreze
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QV5Y8ZNVWGEA8
Tags: twitter, autopost
Tested up to: 3.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin simply create posts in your blog from selected twitter searches.


== Description ==
= With this plugin you can stream tweets to your blog, it's simply to use =
* Go to the plugin settings menu "Twitter To WP" under "Dashboard"
* Configure the plugin options eg.:
* Capabilities: here you can select who can change settings of this plugin 
* Cron time: choose how much time must pass before load new items, use "never" to disable
* Publish status: Choose how the plugin create articles: published or draft
* Posts Tags: Type tags you want append to each tweet (dont use query strings here)
* Posts categories: Choose categories you want append to each tweet post
* Body images: Check if you want to insert images into body of the posts
* Body text: Check if you want to insert the tweet text into body of the posts
* Images size: deprecated, witing for feedback about the use of this image (the user avatar)
* Items at time: choose how much tweets want to import each time the cron run
* Max Title Length: because the title is the tweet text here you can choose the lenght of the title (truncate tweet text) (0 = no title)
* Words blacklist: insert unwanted words to the blacklist (comma separated) tweets with choosed words will be ignored
* Your search queryes: here you can add or remove terms for tweets import, here the query samples

= Example Finds tweets... =
* twitter search - containing both "twitter" and "search". This is the default operator
* "happy hour" - containing the exact phrase "happy hour"
* love OR hate - containing either "love" or "hate" (or both)
* beer -root - containing "beer" but not "root"
* #haiku - containing the hashtag "haiku"
* from:twitterapi - sent from the user @twitterapi
* to:twitterapi - sent to the user @twitterapi
* place:opentable:2 - about the place with OpenTable ID 2
* place:247f43d441defc03 - about the place with Twitter ID 247f43d441defc03
* @twitterapi - mentioning @twitterapi
* superhero since:2011-05-09 - containing "superhero" and sent since date "2011-05-09" (year-month-day).
* twitterapi until:2011-05-09 - containing "twitterapi" and sent before the date "2011-05-09".
* movie -scary :) - containing "movie", but not "scary", and with a positive attitude.
* flight :( - containing "flight" and with a negative attitude.
* traffic ? - containing "traffic" and asking a question.
* hilarious filter:links - containing "hilarious" and with a URL.
* news source:tweet_button - containing "news" and entered via the Tweet Button

= More? =
Want more functionality or some modifications? Ok tell me wath you want and i try to add or modify the plugin functions


== Installation ==
Copy the plugin into the wordpress directory ( wp-content/plugins/ )
Activate plugin from admin control panel

This plugin create new menu under Settings ( Settings -> Twitter To WP )
Follow the description in order to configure the plugin


== Changelog ==

= 0.6.3.* =
* User from twitter fix
* Fix username and query in manual publishing
* Some fixes

= 0.6.3 =
* List next tweets
* Manual publishing of tweets from the list

= 0.6.2.* =
* Feedback request
* Readme updated
* Fix feedback request every time you save settings

= 0.6.2 =
* Some fix
* New menu position with icon
* Update capabilities because levels are deprecated
* Visual fixes

= 0.6.1 =
* Users Feedback:
* sllim99: Posts thumbnails if the tweet has images
* Umpqua: Posts urls as hyperlinks

= 0.6 =
* NO CHANGES, ONLY VERSION UPDATE

= 0.5.2 =
* Fix blacklist not filtering

= 0.5.1 =
* Small fix for query tags, thanks Rob Yardman

= 0.5 =
* Users Feedback:
* Rob Yardman: Tags for each query string
* Rob Yardman: Title length
* hazem: Words blacklist

= 0.4 =
* Post categories

= 0.3 =
* Formatting setting added.
* New backend interface
* More readable sorce code

= 0.2 =
* Removed debug code.

= 0.1 =
* Initial relase.