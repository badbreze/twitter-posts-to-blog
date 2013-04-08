=== Twitter posts to Blog ===
Contributors: badbreze
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QV5Y8ZNVWGEA8
Tags: twitter, autopost
Tested up to: 3.5.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin simply create posts in your blog from selected twitter searches, I take NO RESPONSIBILITY on how you use this plugin and/or any problem caused using this plugin, do what you want with it!


== Description ==
= With this plugin you can stream tweets to your blog, it's simply to use =
* Go to the plugin settings menu ( Settings -> Twitter To WP)
* You have one text box called "Add query string:", type the query string you want to autopublish, eg.:
* @egUsername
* #egTag
* egText
* Now Click ADD button to add this query string to "current queryes"
* From "Cron Time" choose every how much time this plugin import new items
* From "Publish Status" select your favorite publish status
* Optional from "Post Tags" type the tags you want to be added in every post
* Optional from "Body Images" choose if you want to insert user image into body
* Optional from "Body Text" choose if you want to insert tweet text into body
* Optional from "Image size" choose image size for the body image (if "Body Images" are selected)
* Optional from "Items at time" choose how much tweets want to import each time the cron run
* Click Save Settings Button
* Now the plugin import automaticaly choosed tweets

= More? =
Want more functionality or some modifications? Ok tell me wath you want and i try to add or modify the plugin functions


== Installation ==
Copy the plugin into the wordpress directory ( wp-content/plugins/ )
Activate plugin from admin control panel

This plugin create new menu under Settings ( Settings -> Twitter To WP )
Follow the description in order to configure the plugin


== Changelog ==

= 0.1 =
* Initial relase.

= 0.2 =
* Removed debug code.

= 0.3 =
* Formatting setting added.
* New backend interface
* More readable sorce code

= 0.4 =
* Post categories

= 0.5 =
* Users Feedback:
* Rob Yardman: Tags for each query string
* Rob Yardman: Title length
* hazem: Words blacklist

= 0.5.1 =
* Small fix for query tags, thanks Rob Yardman

= 0.5.2 =
* Fix blacklist not filtering