<?php
$options = get_option('pixiv_options');

// Sidebar
function pixiv_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar 1 (Home)', 'pixiv' ),
		'id' => 'sidebar1-main',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'Sidebar 2 (Home)', 'pixiv' ),
		'id' => 'sidebar2-home',
		'description'   => __('Only display when you select 3 columns layout.','pixiv'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'Sidebar 1 (Post)', 'pixiv' ),
		'id' => 'sidebar1-post',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'Sidebar 2 (Post)', 'pixiv' ),
		'id' => 'sidebar2-post',
		'description'   => __('Only display when you select 3 columns layout.','pixiv'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'pixiv_widgets_init' );

register_nav_menus( array(
	'main' => __( 'Menu (Main)', 'pixiv' ),
	'sub' => __( 'Menu (Subscription)', 'pixiv' ),
	'footer' => __( 'Menu (Footer)', 'pixiv' ),
) );
// Custom background support
add_custom_background();

// Add default posts and comments RSS feed links to head
add_theme_support('automatic-feed-links');

// Thumbnails
add_theme_support('post-thumbnails');

// Custom editor style
add_editor_style('style-editor.css');

// Custom header
add_custom_image_header('pixiv_banner_style','pixiv_banner_admin');
define('NO_HEADER_TEXT', true );
define('HEADER_TEXTCOLOR', '258fb8');
define('HEADER_IMAGE_WIDTH', 980);
define('HEADER_IMAGE_HEIGHT', 200);
function pixiv_banner_style(){ ?>
	<style type='text/css'>
		#banner{background: url(<?php header_image(); ?>);}
	</style>
<?php }
function pixiv_banner_admin(){ ?>
	<style type='text/css'>
		#headimg{width: <?php echo HEADER_IMAGE_WIDTH; ?>px;height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;}
	</style>
<?php }

register_default_headers( array(
	'platform' => array(
		'url' => '%s/headers/defaults/platform.jpg',
		'thumbnail_url' => '%s/headers/defaults/thumbnail/platform.jpg',
		'description' => __( 'Platform', 'pixiv' )
	),
	'xmas' => array(
		'url' => '%s/headers/defaults/xmas.jpg',
		'thumbnail_url' => '%s/headers/defaults/thumbnail/xmas.jpg',
		'description' => __( 'Xmas', 'pixiv' )
	),
	'beach' => array(
		'url' => '%s/headers/defaults/beach.jpg',
		'thumbnail_url' => '%s/headers/defaults/thumbnail/beach.jpg',
		'description' => __( 'Beach', 'pixiv' )
	),
	'starry' => array(
		'url' => '%s/headers/defaults/starry.jpg',
		'thumbnail_url' => '%s/headers/defaults/thumbnail/starry.jpg',
		'description' => __( 'Starry', 'pixiv' )
	),
	'sunset' => array(
		'url' => '%s/headers/defaults/sunset.jpg',
		'thumbnail_url' => '%s/headers/defaults/thumbnail/sunset.jpg',
		'description' => __( 'Sunset', 'pixiv' )
	),
) );

// Script load
wp_register_script('pixiv-main',get_bloginfo('template_directory') . '/js/main.js',array('jquery'),'2.1.2');
wp_register_script('pixiv-comment',get_bloginfo('template_directory') . '/js/comment.js',array('jquery'),'2.1.0');

// Content width
if ( ! isset( $content_width ) ) { 
	if ( is_home() )
		( $options['index_layout'] == 'both_three' ) ? $content_width = 556 : $content_width = 676;
	elseif ( is_single() )
		( $options['single_layout'] == 'both_three' ) ? $content_width = 538 : $content_width = 738;
	else
		$content_width = 938;
}

// Translation support
load_textdomain('pixiv', dirname(__FILE__).'/languages/' . get_locale() . '.mo');

// Other Css & JS
function pixiv_font(){
	$options = get_option('pixiv_options');
	if( $options['font_famliy'] == 'segoe'){ ?>
		<style type="text/css">body{font-family: "Segoe UI",Calibri,"Myriad Pro",Myriad,"Trebuchet MS",Helvetica,Arial,sans-serif;}</style>
	<?php } elseif( $options['font_famliy'] == 'arial'){ ?>
		<style type="text/css">body{font-family: "Helvetica Neue",Helvetica,Arial,Geneva,"MS Sans Serif",sans-serif;}</style>
	<?php } elseif( $options['font_famliy'] == 'georgia'){ ?>
		<style type="text/css">body{font-family: Georgia,"Nimbus Roman No9 L",serif;}</style>
	<?php } else { ?>
		<style type="text/css">body{font-family: "Lucida Grande","Lucida Sans","Lucida Sans Unicode","Helvetica Neue",Helvetica,Arial,Verdana,sans-serif;}</style>
	<?php }
}
function pixiv_links_color(){ 
	$options = get_option('pixiv_options');
	$color = $options['links_color']; ?>
	<style type="text/css">
		a,#header h2,#post_navi span,#footer #pagetop,#respond input#submit{color: <?php echo '#'.$color; ?> ;}
		#header #search input#s2{background-color: <?php echo '#'.$color; ?> ;}
		.entry blockquote,li.comment .comment_content blockquote{border-color: <?php echo '#'.$color; ?>}
	</style>
<?php }
function pixiv_ajaxnavi(){ $options = get_option('pixiv_options'); ?>
<!--[if (gt IE 9)|!(IE)]><!-->
<script type="text/javascript">
jQuery(document).ready(function(){<?php echo $options['pagenavi_autoload'] ? 'pageAJAXNavi(true)' : 'pageAJAXNavi()'; ?>;});
var al_loading = '<?php _e('Loading...','pixiv'); ?>',
	al_error = '<?php _e('Error! Sorry, you have to manually refresh the page.','pixiv'); ?>';
</script>
<!--<![endif]-->
<?php }
function pixiv_reply_at(){ ?>
<script type="text/javascript">
	jQuery(document).ready(function(){
		replyAction();
	});
</script>
<?php }
function pixiv_ajax_comments(){ $options = get_option('pixiv_options'); if( is_singular() ) { ?>
<!--[if (gt IE 9)|!(IE)]><!-->
<script type="text/javascript">
	var	txt1 = '<?php _e('Submitting, please wait...','pixiv'); ?>',
		txt3 = '<?php _e('Submit Complete','pixiv'); ?>',
		edt1 = '<?php _e('You can re-edit your comment before refreshing page.','pixiv'); ?>',
		edt2 = '<?php _e('(Re-edit)','pixiv'); ?>',
		submit_val = '<?php _e('Post Comment', 'pixiv'); ?>',
		wait = <?php echo $options['comment_ajax_restrict']; ?>,
		<?php echo $options['comment_ajax_reedit'] ? 'edit_mode = true' : 'edit_mode = false' ?>;
</script>
<!--<![endif]-->
<?php } }
function pixiv_ajax_comm_navi(){ if( is_singular() ) { ?>
<!--[if (gt IE 9)|!(IE)]><!-->
<script type="text/javascript">
jQuery(document).ready(function(){commAJAXNavi();});
	var al_loading = '<?php _e('Loading...','pixiv'); ?>',
		al_error = '<?php _e('Error! Sorry, you have to manually refresh the page.','pixiv'); ?>';
</script>
<!--<![endif]-->
<?php } }
if( $options['font_famliy'] != 'meiryo' ) add_action('wp_head','pixiv_font');
if( $options['links_color'] != '258fb8' ) add_action('wp_head','pixiv_links_color');
if( $options['pagenavi_ajax'] ) add_action('wp_head','pixiv_ajaxnavi');
if( $options['reply_before_at'] ) add_action('wp_head','pixiv_reply_at');
if( $options['comment_ajax_enable'] ) add_action('wp_head','pixiv_ajax_comments');
if( $options['comment_ajax_enable'] ) add_action('wp_head','pixiv_ajax_comm_navi');

function pixiv_ac_edit() {
	if( isset($_GET['action'])&& $_GET['action'] == 'ac_edit'){
	$comment_author			= ( isset($_POST['author']) ) ? trim(strip_tags($_POST['author'])) : null;
	$comment_author_email	= ( isset($_POST['email']) ) ? trim($_POST['email']) : null;
	$comment_author_url		= ( isset($_POST['url']) ) ? trim($_POST['url']) : null;
	$comment_content		= ( isset($_POST['comment']) ) ? trim($_POST['comment']) : null;
	
	$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content');
	$edit_ID = ( isset($_POST['comment_edit_ID']) ) ? $_POST['comment_edit_ID'] : null;
	$comment_ID = $commentdata['comment_ID'] = $edit_ID;
	wp_update_comment($commentdata);
	
	$location = get_comment_link($edit_ID);
	wp_redirect($location);
	die();
	} else { return; }
}
add_action('init','pixiv_ac_edit');

// Page navi
function pixiv_pagenavi() {
	global $wp_query,$wp_rewrite;
	$wp_query->query_vars['paged']>1?$current=$wp_query->query_vars['paged']:$current=1;
	$pagination=array(
		'base'=>@add_query_arg('paged','%#%'),
		'format'=>'',
		'total'=>$wp_query->max_num_pages,
		'mid_size' => 4,
		'type' => 'plain',
		'current'=>$current ,
		'prev_text'=>__('&laquo; Prev','pixiv'),
		'next_text'=>__('Next &raquo;','pixiv'));
	echo '<nav class="wp-pagenavi">'.paginate_links($pagination).'</nav>';
}

/* comment_mail_notify v1.0 by willin kan. */
function pixiv_cmn_core($comment_id) {
  $options = get_option('pixiv_options');
  $admin_notify = '1'; //  whether to send notification to admin (1:Yes 0:No)
  $admin_email = $options['reply_notify_admin_email'];
  $comment = get_comment($comment_id);
  $comment_author_email = trim($comment->comment_author_email);
  $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
  global $wpdb;
  if ($wpdb->query("Describe {$wpdb->comments} comment_mail_notify") == '')
    $wpdb->query("ALTER TABLE {$wpdb->comments} ADD COLUMN comment_mail_notify TINYINT NOT NULL DEFAULT 0;");
  if (($comment_author_email != $admin_email && isset($_POST['comment_mail_notify'])) || ($comment_author_email == $admin_email && $admin_notify == '1'))
    $wpdb->query("UPDATE {$wpdb->comments} SET comment_mail_notify='1' WHERE comment_ID='$comment_id'");
  $notify = $parent_id ? get_comment($parent_id)->comment_mail_notify : '0';
  $spam_confirmed = $comment->comment_approved;
  if ($parent_id != '' && $spam_confirmed != 'spam' && $notify == '1') {
    $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])); // e-mail sender
    $subject = $options['reply_notify_subject'];
    $subject = str_replace('[blogname]', get_option('blogname'), $subject);
    $subject = str_replace('[postname]', get_the_title($comment->comment_post_ID), $subject);
    $subject = str_replace('[commauthor]', trim(get_comment($parent_id)->comment_author), $subject);
    $subject = str_replace('[replyauthor]', trim($comment->comment_author), $subject);
    $subject = str_replace('[commcontent]', trim(get_comment($parent_id)->comment_content), $subject);
    $subject = str_replace('[replycontent]', trim($comment->comment_content), $subject);
    $subject = str_replace('[commurl]', htmlspecialchars(get_comment_link($parent_id)), $subject);
    $message = $options['reply_notify_content'];
    $message = str_replace('[blogname]', get_option('blogname'), $message);
    $message = str_replace('[postname]', get_the_title($comment->comment_post_ID), $message);
    $message = str_replace('[commauthor]', trim(get_comment($parent_id)->comment_author), $message);
    $message = str_replace('[replyauthor]', trim($comment->comment_author), $message);
    $message = str_replace('[commcontent]', trim(get_comment($parent_id)->comment_content), $message);
    $message = str_replace('[replycontent]', trim($comment->comment_content), $message);
    $message = str_replace('[commurl]', htmlspecialchars(get_comment_link($parent_id)), $message);
    $to = trim(get_comment($parent_id)->comment_author_email);
    $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
    wp_mail( $to, $subject, $message, $headers );
  }
}
if( $options['reply_notify_enable'] ) add_action('comment_post', 'pixiv_cmn_core');

function pixiv_cmn_checkbox() {
  $label = __('Notify me of follow-up comments via email','pixiv');
  echo '<div id="comment_notify_checkbox"><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked"/><label for="comment_mail_notify">'.$label.'</label></div>';
}
if( $options['reply_notify_enable'] ) add_action('comment_form', 'pixiv_cmn_checkbox');

function pixiv_custom_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $commentcount;
	if(!$commentcount) {
		$commentcount = 0;
	}
?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
 	<?php if (function_exists('get_avatar') && get_option('show_avatars')) { echo get_avatar($comment, 30); } ?>
 	<div class="comment_meta">
 		<div class="alignleft">
 			<?php if( get_comment_author_url() ) : ?>
 				<a id="commentauthor-<?php comment_ID() ?>" href="<?php comment_author_url() ?>" rel="external nofollow" class="author" title="<?php comment_author(); ?>"><?php comment_author(); ?></a>
 			<?php else : ?>
 				<span id="commentauthor-<?php comment_ID() ?>" class="author" title="<?php comment_author(); ?>"><?php comment_author(); ?></span>
 			<?php endif; ?>
 			<?php if ($comment->comment_approved == '0') { ?>
 			<small><?php _e('(Moderating)','pixiv'); ?></small>
 			<?php } elseif($comment->comment_author_email == get_the_author_meta('email')) { ?>
 			<small><?php _e('(Admin)','pixiv'); ?></small>
 			<?php } ?>
 			<span class="date"><?php echo get_comment_time(__('M jS, Y G:i','pixiv')); ?></span>
 		</div>
 		<div class="alignright">
 			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
 			<a href="javascript:void(0);" class="comment-quote-link" title="<?php _e('Quote','pixiv'); ?>"><?php _e('Quote','pixiv'); ?></a>
 			<?php edit_comment_link(__('Edit','pixiv')); ?>
 		</div>
 	</div>
    <div class="clearfix"></div>
    <div class="comment_content" id="comment-content-<?php comment_ID() ?>">
	    <?php comment_text(); ?>
    </div>
    <div class="clearfix"></div>
<?php }

// Custom trackbacks/pingbacks
function pixiv_custom_pings($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
?>
 <li class="trackback" id="comment-<?php comment_ID() ?>">
 	<div class="trackback_meta">
 		<?php echo get_comment_time(__('M jS, Y G:i','pixiv')) ?>
 		<?php edit_comment_link(__('Edit','pixiv'), '', ''); ?>
 	</div>
 	<div class="trackback_title">
 		<a href="<?php comment_author_url() ?>"><?php comment_author(); ?></a>
 	</div>
 </li>
<?php }
if ( file_exists( TEMPLATEPATH. '/admin/admin.php' ) ) {
	require_once( TEMPLATEPATH. '/admin/admin.php' );
	$pixivOpt = new pixivOpt();
}
?>