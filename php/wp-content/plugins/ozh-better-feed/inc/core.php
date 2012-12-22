<?php
/*
Part of Plugin "Ozh's Better Feed"
http://planetozh.com/blog/my-projects/wordpress-plugin-better-feed-rss/
*/

global $wp_ozh_betterfeed;

function wp_ozh_betterfeed_add_page() {
	add_options_page('Better Feed', 'Better Feed', 8, 'better_feed', 'wp_ozh_betterfeed_options_page_includes');
}

function wp_ozh_betterfeed_icon() {
	return WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)) . '/ozh.png';
}

function wp_ozh_betterfeed_options_page_includes() {
	require_once(dirname(__FILE__).'/feed.php');
	require_once(dirname(__FILE__).'/optionpage.php');
	wp_ozh_betterfeed_options_page();
}

function wp_ozh_betterfeed_plugin_actions($links) {
	$links[] = "<a class='edit' href='options-general.php?page=better_feed'><b>Settings</b></a>";
	return $links;
}
