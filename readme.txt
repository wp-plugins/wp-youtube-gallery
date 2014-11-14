=== WP Youtube Gallery ===
Contributors:india-web-developer
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WN785E5V492L4
Tags: youtube gallery, youtube,gallery,video gallery
Requires at least: 2.7
Tested up to: 4.0
Stable tag: 1.1

Add Youtube Gallery On Your Site!

== Description ==

"wp-youtube-gallery" is the very simple plugin for add to simple youtube gallery on your site.

Using this plugin we can easly add the youtube gallery on any page/post using shortcode. It's a responsive plugin.

[wp_youtube_gallery category_slug="test" per_row="4" total_videos="3" height="200" width="300"]

= Features = 
* Responsive gallery
* Show Youtube videos on any page/post using shortcode
* Use category id/slug in shortcode for publish the video from specific category
* Option for define the number of videos to publish on post
* Options for define the number or videos per row

= Shortcode = 
* [wp_youtube_gallery catid="ENTER YOUTUBE CATEGORY ID"] OR [wp_youtube_gallery category_slug="ENTER YOUTUBE CATEGORY SLUG"]



== Installation ==

Step 1. Upload "wp-youtube-gallery" folder to the `/wp-content/plugins/` directory

Step 2. Activate the plugin through the Plugins menu in WordPress

Step 3. Go to Settings/"WP Youtube Gallery" and configure the plugin settings.

== Frequently Asked Questions ==
1.How add gallery on my website?

Use [wp_youtube_gallery catid="ENTER YOUTUBE CATEGORY ID"] OR [wp_youtube_gallery category_slug="ENTER YOUTUBE CATEGORY SLUG"] shortcode to add the gallery on any page/post.

2.How add gallery in template files?

Add given code <?php if(function_exists('get_wp_youtube_gallery')){ echo do_shortcode('[wp_youtube_gallery category_slug="ENTER YOUTUBE CATEGORY SLUG"]');}?>

== Screenshots ==

1. screenshot-1.png

2. screenshot-2.png

== Changelog == 

= 1.2 = 
 * Added Lightbox
 * Changed plugin settings page layout
 * Fixed some minor issues

= 1.1 = 
 * Plugin is now responsive
 * Add new options to define into sortcode
 * Fixed CSS issues

= 1.0 = 
 * First stable release
