<?php
/*
Plugin Name: surl
Plugin URI: http://fairyfish.net/2008/05/30/short-url-wordpress-plugin/
Description: Get an alternate <a href="http://surl.cn/">sURL</a> link for your article or post permalink. nothing to add but Just enabled plugin and enjoying!
Author: askie
Version: 0.1
Author URI: http://www.pkphp.com/

Licence:
Provided under the GNU General Public Licence v3.0
http://www.gnu.org/licenses/gpl-3.0.txt

USAGE:
Just enabled plugin!
*/

function showSurLink($content) 
{
	$apost = get_post($id);
	
	$surlink = $apost->surl_cn;
	
	if ($surlink=="") 
	{
		$surlink=__createSurLink($id);
	}

	if ($surlink=="") 
	{
		return $content;
	}
	else 
	{
		$title = str_replace('"',"&quot;",$apost->post_title);
		$code='Shot alternate link for this article: <a href="'.$surlink.'" title="ShotURL link to - '.$title.'">'.$title.'</a>';
		return $content.$code;
	}	
}
function createSurLink($post)
{
	global $wpdb;
	
	if (is_numeric($post)) 
	{
		$postid=$post;
		$post=array(get_post($post));
	}
	
	foreach ($post as $key=>$var) 
	{
		$link=get_permalink($post[$key]->ID);
		$title=$post[$key]->post_title;
		$post[$key]->surl_cn =  file_get_contents("http://surl.cn/api-create.php?url=" . $link."&name=".urlencode($title));
		$wpdb->query("UPDATE `{$wpdb->posts}` SET `surl_cn` = '{$post[$key]->surl_cn}' WHERE `ID` = '{$post[$key]->ID}'");
	}
	
	return isset($postid)?$postid:$post;
}

function __createSurLink($id)
{
	global $wpdb,$lastCreateSurl;
	
	$apost = get_post($id);
	$link  = get_permalink($id);
	$title = $apost->post_title;
	$apost->surl_cn = file_get_contents("http://surl.cn/api-create.php?url=" . $link . "&name=".urlencode($title));
	$wpdb->query("UPDATE `{$wpdb->posts}` SET `surl_cn` = '{$apost->surl_cn}' WHERE `ID` = '{$apost->ID}'");
	
	return $apost->surl_cn;
}

//扩展数据库
function installDatabase()
{
	global $wpdb;

	//判断地段是否已经存在
	$row = $wpdb->get_row("SELECT * FROM `{$wpdb->posts}`  LIMIT 0 , 1",ARRAY_A);
	if (!key_exists("surl_cn",$row)) 
	{
		$sql = "ALTER TABLE `{$wpdb->posts}` ADD `surl_cn` VARCHAR( 40 )";
		$wpdb->query($sql);
	}
	return ;
}

if ($GLOBALS['_GET']["action"]) 
{
	installDatabase();
}
add_filter('the_content',"showSurLink");
add_action('publish_post', 	'createSurLink');
add_action('publish_page', 	'createSurLink');
add_action('edit_post', 	'createSurLink');
?>
