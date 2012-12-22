<?php
/*
Part of Plugin "Ozh's Better Feed"
http://planetozh.com/blog/my-projects/wordpress-plugin-better-feed-rss/
*/


/* tokens :
 *
 * %%blogname%% : blog name (My Weblog)
 * %%blogurl%% : blog url (http://myblog.com/)
 * %%feedurl%% : feed url (http://myblog.com/feed/)
 * %%posttitle%% : post title (Hello World)
 * %%posturl%% : post url (http://myblog.com/archives/2001/02/03/hello-world/ or http://myblog.com/?p=1337)
 * %%id%% : post ID (its number, i.e. 1337 in above example)
 * %%date[Y]%% : date / time of post, where pattern within brackets follows PHP date() syntax
 * %%categories%% : list of commas separated category names the post is filed in
 * %%categorylinks%% : list of commas separated category links the post is filed in
 * %%tags%% : list of commas separated category names the post is filed in
 * %%taglinks%% : list of commas separated category links the post is filed in
 * %%comments%% : number of comments
 * %%comments_text%% : text for 0, 1 or xx comments (see below)
 * %%wordcount%% : total number of words in a post
 * %%wordcount_remain%% : number of words in second part after the <!--more--> split
 * %%author_firstname%% : author's firstname
 * %%author_lastname%% : author's lastname
 * %%author_nickname%% : author's nickname
 * %%<?php somefunc(); ?>%% : omg omg omg
 */

global $wp_ozh_betterfeed;

function wp_ozh_betterfeed_dofeed($content) {
	global $post, $id, $wp_ozh_betterfeed;
	
	wp_ozh_betterfeed_defaults();
	
	// Empty "Read more" setting ? Comply to the default "read more" link
	if ($wp_ozh_betterfeed['readmore'] == '') {
		global $more;
		$more = 0;
	}

	if ($wp_ozh_betterfeed['linkback']) {
		add_filter('the_content', 'wp_ozh_betterfeed_link', 999999);
	}

	$wp_ozh_betterfeed['splitted'] = 0;
	$separator = "<span id=\"more-$id\"></span>";

	if ($wp_ozh_betterfeed['split'] and (strpos($content,$separator) != FALSE)) {
		$content = preg_split("#$separator#",$content);
		$content = $content[0] . $wp_ozh_betterfeed['readmore'];
		//close <p> tags that might have been lost in the splitting
		if (!preg_match("#</p>$#", $content)) {
			$content .= "</p>\n";
		}
		$wp_ozh_betterfeed['splitted'] = 1;
	}

	if ($wp_ozh_betterfeed['multipage'] and (strpos($post->post_content,"<!--nextpage-->") != FALSE)) {
		$wp_ozh_betterfeed['splitted'] = 1;
		$content .= $wp_ozh_betterfeed['readmore'];
	}
	
	$content .= $wp_ozh_betterfeed['footer'] ;

	return (wp_ozh_betterfeed_detokenize($content));
}

function wp_ozh_betterfeed_link($content) {
	$content .= "\n<p><small>Feed enhanced by <a href='http://planetozh.com/blog/my-projects/wordpress-plugin-better-feed-rss/'>Better Feed</a> from  <a href='http://planetozh.com/blog/'>Ozh</a></small></p>\n";
	return $content;
}


/* The function that translate every %%stuff%% into their values */
function wp_ozh_betterfeed_detokenize($string='') {

	global $id, $wp_ozh_betterfeed;
	
	$com_nb = get_comments_number($id);
	
	// simple replacements
	$search =  array('%%blogname%%','%%blogurl%%','%%feedurl%%', '%%posttitle%%','%%posturl%%',
		'%%id%%', '%%author_firstname%%', '%%author_lastname%%', '%%author_nickname%%',
		'%%comments%%');
	$replace = array(get_bloginfo('name'), get_bloginfo('url'), get_bloginfo('rss2_url'), get_the_title(), get_permalink(),
		$id, get_the_author_firstname(), get_the_author_lastname(), get_the_author_nickname(),
		$com_nb);
	$string = str_replace($search, $replace, $string);

	// less simple replacements
	if (strpos($string,'%%categorylinks%%') != FALSE) {
		$cats = get_the_category_list(', ');
		$string = str_replace('%%categorylinks%%', $cats, $string);
	}

	if (strpos($string,'%%categories%%') != FALSE) {
		if (!$cats) $cats = get_the_category_list(', ');
		$string = str_replace('%%categories%%', strip_tags($cats), $string);
	}

	if (strpos($string,'%%taglinks%%') != FALSE) {
		$tags = get_the_tag_list('',', ');
		$string = str_replace('%%taglinks%%', $tags, $string);
	}
	
	if (strpos($string,'%%tags%%') != FALSE) {
		if (!$tags) $tags = get_the_tag_list('',', ');
		$string = str_replace('%%tags%%', strip_tags($tags), $string);
	}

	if (strpos($string,'%%comments_text%%') != FALSE) {
		if ($com_nb == 0) {
			$blah = $wp_ozh_betterfeed['0comment'];
		} elseif ($com_nb == 1) {
			$blah = $wp_ozh_betterfeed['1comment'];
		} elseif ($com_nb  > 1) {
			$blah = str_replace('%', $com_nb, $wp_ozh_betterfeed['Xcomments']);
		}
		$string = str_replace('%%comments_text%%', $blah, $string);
	}

	if (strpos($string,'%%wordcount%%') != FALSE) {
		$string = str_replace('%%wordcount%%', wp_ozh_betterfeed_wordcount('all'), $string);
	}

	if (strpos($string,'%%wordcount_remain%%') != FALSE) {
		$string = str_replace('%%wordcount_remain%%', wp_ozh_betterfeed_wordcount('remain'), $string);
	}

	if (strpos($string,'%%date[') != FALSE) {
		$string = preg_replace('/%%date\[([^]]+)\]%%/e', "get_post_time('\\1')", $string);
	}
	
	if (strpos($string,'%%<?php') != FALSE) {
		$string = preg_replace('/%%<\?php(.*?)\?>%%/e', "wp_ozh_betterfeed_phpexec('\\1')", $string);
	}

	return $string;

}

/* Function that catches output of custom PHP functions */
function wp_ozh_betterfeed_phpexec($run) {
	$run = stripslashes($run);
	ob_start();
	eval($run);
	$catch = ob_get_contents();
	ob_end_clean();
	return $catch;
}

/* The function that counts words (before and after the <!--more--> part if applicable) */
function wp_ozh_betterfeed_wordcount($scope='all') {
    global $post, $id, $wp_ozh_betterfeed;
    $text = $post->post_content;
    if ($scope=='remain') {
    	if ( (strpos($text,'<!--more-->') != FALSE ) and $wp_ozh_betterfeed['split']) {
    		list($temp,$text) = explode('<!--more-->', $text,2);
    	} elseif ( (strpos($text,'<!--nextpage-->') != FALSE ) and $wp_ozh_betterfeed['multipage']) {
    		list($temp,$text) = explode('<!--nextpage-->', $text,2);
    	}
    }
    $text = str_replace("\n", " ", $text);
    $text = split(' ', strip_tags($text));
    foreach ($text as $k=>$v) {
    	if (trim($v) == '') {
    		unset($text[$k]);
    	}
    }
    $count = count($text);
    return number_format($count);
}

// Set default values when unset
function wp_ozh_betterfeed_defaults() {
	global $wp_ozh_betterfeed;
	
	$footer = <<<FEEDFOOT
<hr />
<p><small>&copy; %%author_nickname%% for <a href="%%blogurl%%">%%blogname%%</a>, %%date[Y]%%. |
<a href="%%posturl%%">Permalink</a> |
<a href="%%posturl%%#comments">%%comments_text%%</a> |
Add to
<a href="http://del.icio.us/post?url=%%posturl%%&amp;title=%%posttitle%%">del.icio.us</a>
<br/>
Post tags: %%taglinks%%<br/>
</small></p>
FEEDFOOT;

	$defaults = array(
		'split' => 1,
		'multipage' => 1,
		'readmore' => '(...)<br/>Read the rest of <a href="%%posturl%%">%%posttitle%%</a> (%%wordcount_remain%% words)',
		'footer' => $footer,
		'0comment' => 'No comment',
		'1comment' => 'One comment',
		'Xcomments' => '% comments',
		'linkback' => 1);
		
	if (!count($wp_ozh_betterfeed))
		$wp_ozh_betterfeed = array_map('stripslashes',(array)get_option('ozh_betterfeed'));
	
	$wp_ozh_betterfeed = array_merge($defaults, $wp_ozh_betterfeed);
}


?>