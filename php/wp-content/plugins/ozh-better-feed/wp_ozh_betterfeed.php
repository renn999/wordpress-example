<?php
/*
Plugin Name: Ozh' Better Feed
Plugin URI: http://planetozh.com/blog/my-projects/wordpress-plugin-better-feed-rss/
Description: Your feeds, on steroids. Add a feed footer with a copyright, "Add to delicious", the number of comments... Add anything to your feed. <strong>For WordPress 2.8+</strong>
Version: 2.2
Author: Ozh
Author URI: http://planetOzh.com
*/

function wp_ozh_betterfeed($content) {
	if (!is_feed()) return $content;
	require_once(dirname(__FILE__).'/inc/feed.php');
	return wp_ozh_betterfeed_dofeed($content);
}


add_filter('the_content', 'wp_ozh_betterfeed', 9999);

if (is_admin()) {
	require_once(dirname(__FILE__).'/inc/core.php');
	add_action('admin_menu', 'wp_ozh_betterfeed_add_page');
	add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'wp_ozh_betterfeed_plugin_actions', -10);
	add_filter( 'ozh_adminmenu_icon_better_feed', 'wp_ozh_betterfeed_icon');
}
?>