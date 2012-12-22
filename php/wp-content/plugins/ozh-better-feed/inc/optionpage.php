<?php
/*
Part of Plugin "Ozh's Better Feed"
http://planetozh.com/blog/my-projects/wordpress-plugin-better-feed-rss/
*/

global $wp_ozh_betterfeed;

function wp_ozh_betterfeed_processform() {

	global $wp_ozh_betterfeed;
	
	check_admin_referer('ozh-betterfeed');
	
	// Debug:
	//echo "<pre>";echo htmlentities(print_r($_POST,true));echo "</pre>";	
	
	switch ($_POST['action']) {
	case 'update_options':
	
		$options['split'] = ($_POST['bf_split']) ? '1' : '0';
		$options['multipage'] = ($_POST['bf_multipage'])? '1' : '0';
		$options['readmore'] = ($_POST['bf_readmore']);
		$options['footer'] = ($_POST['bf_footer_html']);
		$options['0comment'] = ($_POST['bf_0comments']);
		$options['1comment']= ($_POST['bf_1comments']);
		$options['Xcomments'] = ($_POST['bf_Xcomments']);
		$options['linkback'] = ($_POST['bf_linkback'])? '1' : '0';
		
		if (!update_option('ozh_betterfeed', $options))
			add_option('ozh_betterfeed', $options);
			
		$wp_ozh_betterfeed = array_merge( (array)$wp_ozh_betterfeed, $options );
		
		$msg = "updated";
		break;

	case 'reset_options':
		delete_option('ozh_betterfeed');
		$msg = "deleted";
		break;
	}

	echo '<div id="message" class="updated fade">';
	echo "<p>Better Feed settings <strong>$msg</strong></p>\n";
	echo "</div>\n";	
}

function wp_ozh_betterfeed_options_page() {
	global $wp_ozh_betterfeed;
	
	if (isset($_POST['ozh_betterfeed']) && ($_POST['ozh_betterfeed'] == 1) )
		wp_ozh_betterfeed_processform();
	
	wp_ozh_betterfeed_defaults();
	wp_ozh_betterfeed_css();
	wp_ozh_betterfeed_help();
	wp_ozh_betterfeed_js();
	add_action('in_admin_footer', 'wp_ozh_betterfeed_footer');
	
	// echo "<pre>".wp_ozh_betterfeed_sanitize(print_r($wp_ozh_betterfeed,true))."</pre>";
	
	// Take over: make sure the_editor is not unnecessary big
	$old_rows = get_option('default_post_edit_rows');
	update_option('default_post_edit_rows',10);
	// Take over: tell the visual editor to shut the fuck up
	add_filter('user_can_richedit', create_function('','return false;'));
	
	$checked_split = ($wp_ozh_betterfeed['split'] == 1) ? 'checked="checked"' : '' ;
	$checked_multi = ($wp_ozh_betterfeed['multipage'] == 1) ? 'checked="checked"' : '' ;
	$footer = wp_ozh_betterfeed_sanitize($wp_ozh_betterfeed['footer']);
	$readmore = wp_ozh_betterfeed_sanitize($wp_ozh_betterfeed['readmore']);
	$linkback = ($wp_ozh_betterfeed['linkback'] == 1) ? 'checked="checked"' : '' ;
	$com_0 = wp_ozh_betterfeed_sanitize($wp_ozh_betterfeed['0comment']);
	$com_1 = wp_ozh_betterfeed_sanitize($wp_ozh_betterfeed['1comment']);
	$com_X = wp_ozh_betterfeed_sanitize($wp_ozh_betterfeed['Xcomments']);
	
	echo <<<HTML
    <div class="wrap">
    <h2>Better Feed Options</h2>
	<h3>Your Feed, on Steroids</h3>
    <form method="post" action="">
HTML;
	wp_nonce_field('ozh-betterfeed');
	echo <<<HTML
	<table class="form-table"><tbody>
	<input type="hidden" name="ozh_betterfeed" value="1"/>
    <input type="hidden" name="action" value="update_options">
	
    <tr><th scope="row">Cut your Feed</th>
	<td><label><input type="checkbox" $checked_split name="bf_split"> Cut the Feed on "Read more" links (&lt;!--more-->)</label><br/>
	<label><input type="checkbox" $checked_multi name="bf_multipage"> Cut the Feed on "Next page" links (&lt;!--nextpage-->)</label><br/>
	(By default, WordPress shows full feed items even when they are cut on your blog)
	</td></tr>
	
    <tr><th scope="row">"Read more" Link</th>
	<td><label><textarea style="width:90%" rows="4" cols="30" id="bf_morelink" name="bf_readmore">$readmore</textarea><br/>Text for the "Read more" link in your feed, if applicable<br/>
	If left empty, the "<a href="http://codex.wordpress.org/Customizing_the_Read_More">Read more</a>" link in your feed will be the same as on your blog (some feed features such as counting words might not work)
	<br/>(NB: for testing purpose, the "Read More" link will show in the Preview panel &darr;)</label>
	</td></tr>

    <tr><th scope="row">Feed Item Footer</th>
	<td>Customize the footer that will appear below each feed item
	<div id="poststuff">
		<div id="switch-preview">
			<a class="active" id="bf_switchto_html">HTML</a>
			<a id="bf_switchto_preview">Preview</a>
			<a id="bf_switchto_help">Help</a>
		</div>
		<div id="bf_footer_wrap">
HTML;
	the_editor($footer, 'bf_footer_html');
echo <<<HTML
		</div>
	</div>
	</td></tr>
	
    <tr><th scope="row">"X Comments" Labels</th>
	<td>If used, the special token <code>%%comment_text%%</code> will be replaced with the following:<br/>
	<label><input type="text" value="$com_0" size="30" id="bf_0com" name="bf_0comments"> when there is no comment</label><br/>
	<label><input type="text" value="$com_1" size="30" id="bf_1com" name="bf_1comments"> when there is 1 comment</label><br/>
	<label><input type="text" value="$com_X" size="30" id="bf_Xcom" name="bf_Xcomments"> when there is more than 1 comment, where '%' is replace by the number of comments</label><br/>
	</td></tr>
	
    <tr><th scope="row">Credit &amp; Love</th>
	<td><label><input type="checkbox" $linkback name="bf_linkback"> Add a link back to the plugin page</label><br/>
	If you think this is a cool plugin, please leave this option enabled so that your loyal feed readers and subscribers can find about this plugin too!
	</td></tr>

	</tbody></table>
	
	<p class="submit">
	<input name="submit" value="Save Changes" type="submit" />
	</p>

	</form>
	</div>
	
	<div class="wrap"><h2>Reset Settings</h2>
	<form method="post" action="">
HTML;
	wp_nonce_field('ozh-betterfeed');
	echo <<<HTML2
	<input type="hidden" name="ozh_betterfeed" value="1"/>
    <input type="hidden" name="action" value="reset_options">

	<p>Clicking the following button will remove all the <strong>Better Feed</strong> settings from your database. You might want to do so in the following cases:</p>
	<ul>
	<li>you want to uninstall the plugin and leave no unnecessary entries in your database.</li>
	<li>you want all settings to be reverted to their default values</li>
	</ul>
	<p class="submit" style="border-top:0px;padding:0;"><input style="color:red" name="submit" value="Reset Settings" onclick="return(confirm('Really do?'))" type="submit" /></p>
	<p>There is no undo, so be very sure you want to click the button!</p>
	
	</form>
	</div>
HTML2;

	// End of take over
	update_option('default_post_edit_rows',$old_rows);
}

// Sanitize string for display: escape HTML but preserve UTF8 (or whatever)
function wp_ozh_betterfeed_sanitize($string) {
	return stripslashes(attribute_escape($string));
	//return stripslashes(htmlentities($string, ENT_COMPAT, get_bloginfo('charset')));
}

function wp_ozh_betterfeed_css() {
	echo <<<HTML
	<style type="text/css">
	div.wrap{
		margin-bottom:2em;
	}
	#editor-toolbar, #ed_block, #ed_del, #ed_ins, #ed_code, #ed_spell, #ed_more  {
		display:none;
	}
	#editorcontainer #bf_footer_html {
		border:0pt none;
		line-height:150%;
		outline-color:invert;
		outline-style:none;
		outline-width:medium;
		padding:0pt;
	}
	#bf_footer_html {
		margin:0pt;
		width:100%;
	}
	#switch-preview {
		display:none; /* shown by js */
		height:30px;
		margin-top:-21px;
		position:relative;
	}
	#switch-preview .active {
		background-color:#CEE1EF !important;
		color:#333333;
		-moz-border-radius-topleft:3px;
		-moz-border-radius-topright:3px;
		font-weight:bold;
	}
	#switch-preview a:hover {
		background-color:#83B4D5;
		color:#333333;
		-moz-border-radius-topleft:3px;
		-moz-border-radius-topright:3px;
	}
	#switch-preview a {
		color: #2583AD;
		cursor:pointer;
		display:block;
		float:right;
		height:20px;
		margin:5px 8px 0pt 0pt;
		padding:5px 5px 1px;
	}
	#ed_bfbar input {
		font-size:10px;
		padding:1px;
		margin:1px;
	}
	#ed_toolbar input:hover, #ed_bfbar input:hover {
		background-position: 0 5px;
	}
	#bf_footer_preview {
		background:#fff;
		border:1px solid #fff;
		overflow:auto;
		display:none;
		font-size:12px;
	}
	#bf_footer_help {
		background:#f3f3f3;
		border:1px solid #fff;
		overflow:auto;
		display:none;
	}
	#bf_footer_preview, #bf_footer_help, #bf_footer_html {
		zfloat:right;
		zmargin-left:5px;
		zclear:both;
		padding:1em;
	}
	#editorcontainer {
		overflow:hidden;
		height:auto;
	}
	#bf_footer_help h2 {
		margin-top:0;
	}
	#bf_footer_help ul {
		padding:0 0 0 1em;
	}
	</style>

HTML;
}

function wp_ozh_betterfeed_js() {

	global $current_user, $wpdb;

	$blogname = get_bloginfo('name');
	$blogurl = get_bloginfo('url');
	$feedurl = get_bloginfo('rss2_url');
	$last= $wpdb->get_var("SELECT ID from $wpdb->posts WHERE (post_type = 'post') ORDER BY ID DESC LIMIT 0,1");
	$posttitle = get_the_title($last);
	$posturl = get_permalink($last);
	$auth_nick = $current_user->nickname;
	$auth_first = $current_user->first_name;
	$auth_last = $current_user->last_name;
	$categorylinks = get_the_category_list(', ','',$last);
	$categories = strip_tags($categorylinks);
	$comments = get_comments_number($last);
	$taglinks = get_the_term_list($last, 'post_tag', '', ', ', '');
	$tags = strip_tags($taglinks);
	
	$helptext = wp_ozh_betterfeed_help();
	
	

	echo <<<JS
<script type="text/javascript">

function bf_token(what) {

	var comm_text;
	switch ($comments) {
	case 0:
		comm_text = jQuery('#bf_0com').val();
		break;
	case 1:
		comm_text = jQuery('#bf_1com').val();
		break;
	default:
		comm_text = jQuery('#bf_Xcom').val().replace(/%/g, $comments);
	}

	var replace = {
	 'blogname' : '$blogname',
	 'blogurl' : '$blogurl',
	 'feedurl': '$feedurl',
	 'posttitle' : '$posttitle',
	 'posturl' : '$posturl',
	 'id' : $last,
	 'categories' : '$categories',
	 'categorylinks' : '$categorylinks',
	 'tags' : '$tags',
	 'taglinks' : '$taglinks',
	 'comments' : $comments,
	 'comments_text' : comm_text,
	 'wordcount' : 250,
	 'wordcount_remain' : 380,
	 'author_firstname' : '$auth_first',
	 'author_lastname' : '$auth_last',
	 'author_nickname' : '$auth_nick'
	 }
	 
	 for (i in replace) {
		var reg = new RegExp('%%'+i+'%%', 'g');
		what = what.replace(reg,replace[i]);
	 }
	 
	 // date handling
	 // find: date[something] (or date['something'])
	 reg = new RegExp("%%date\\\\['?([^\\\\]]+)'?\\\\]%%", 'g');
	 what = what.replace(reg,function(t,s){return date(s);});
	 
	 // %%<?php stuff ?>%% handling
	 reg = new RegExp("%%<\\\\?php (.*?)\\\\?>%%", 'g');
	 what = what.replace(reg,function(t,s){return "[PHP call: "+s+" ]";});
	 
	 return what;
}


jQuery(document).ready(function(){
	add_bf_buttons();
	add_bf_divs();
	jQuery('#switch-preview').toggle();
	jQuery('#bf_morelink').keyup(function(){bf_detokenize();});
	jQuery('#bf_0com').keyup(function(){bf_detokenize();});
	jQuery('#bf_0com').keyup(function(){bf_detokenize();});
});

function bf_detokenize() {
	jQuery('#bf_footer_preview').html(bf_token(jQuery('#bf_morelink').val()+"\\n"+jQuery('#bf_footer_html').val()));
}

function add_bf_divs() {
	jQuery('#editorcontainer')
		.append('<div id="bf_footer_preview">Preview</div>')
		.append('<div id="bf_footer_help">'+jQuery('#bf_helpdiv').html()+'</div>');
	var h = jQuery('#bf_footer_html').css('height');
	var w = jQuery('#bf_footer_html').css('width');
	jQuery('#bf_footer_preview').css({'height': h, 'min-height': h});
	jQuery('#bf_footer_help').css({'height': h, 'min-height': h});
	jQuery('#switch-preview a').click(function(){
		if (jQuery(this).is('.active')) return;
		if (jQuery(this).attr('id') == 'bf_switchto_html') {
			jQuery('#ed_toolbar input').removeAttr('disabled');
		} else {
			jQuery('#ed_toolbar input').attr('disabled', 'disabled');
		}
		bf_detokenize();
		jQuery('#switch-preview a').removeClass('active');
		jQuery(this).addClass('active');
		// bf_switchto_html -> bf_footer_html
		var target = jQuery(this).attr('id').replace('switchto', 'footer');
		for each (var i in ['#bf_footer_preview', '#bf_footer_help', '#bf_footer_html']) {
			jQuery(i).hide();
		}
		jQuery('#'+target).show();
	});
}


function add_bf_buttons() {
	if(jQuery("#ed_toolbar")) {
		var tokens = ['blogname', 'blogurl', 'feedurl', 'posttitle', 'posturl', 'id', 'date[Y/m/d]', 'categories', 'categorylinks', 'tags', 'taglinks', 'comments', 'comments_text', 'wordcount', 'wordcount_remain', 'author_firstname', 'author_lastname', 'author_nickname']
		var bf_butts_tokens = {};
		var bf_butts_shortcuts = {
			'&copy': '&copy; %%author_nickname%% for <a href="%%blogurl%%">%%blogname%%</a>, %%date[Y]%% ',
			'Post permalink': '<a href="%%posturl%%">Permalink</a> ',
			'Link to comments': '<a href="%%posturl%%#comments">%%comments_text%%</a> ',
			'Add to del.icio.us': 'Add to <a title="Bookmark in del.icio.us" href="http://del.icio.us/post?url=%%posturl%%&amp;title=%%posttitle%%">del.icio.us</a> ',
			'Technorati search': '<a href="http://www.technorati.com/search/%%posturl%%" title="Linking blogs to this article, on Technorati">Who is linking?</a> ',
			'Google search': '<a href="http://www.google.com/blogsearch?hl=en&q=%%posturl%%" title="Linking blogs to this article, on Google">Who is linking?</a> '
		}
		for (var i in tokens) {
			bf_butts_tokens[tokens[i]] = '%%'+tokens[i]+'%%';
		}
		var bfbar2 = bfbar = '';
		for (var i in bf_butts_shortcuts) {
			edButtons[edButtons.length] = new edButton('ozh_bf_'+i, i,bf_butts_shortcuts[i],'','');
			bfbar += '<input type="button" value="'+i+'" onclick="bf_add_tag(this.id);" class="ed_button" title="'+i+'" id="ozh_bf_'+parseInt(edButtons.length-1)+'"/> ';
		}
		for (var i in bf_butts_tokens) {
			edButtons[edButtons.length] = new edButton('ozh_bf_'+i, i,bf_butts_tokens[i],'','');
			bfbar2 += '<input type="button" value="'+i+'" onclick="bf_add_tag(this.id);" class="ed_button" title="Insert '+i+'" id="ozh_bf_'+parseInt(edButtons.length-1)+'"/> ';
		}
		jQuery('#ed_toolbar').append("<br/>Presets: "+bfbar);
		jQuery('#ed_toolbar').append('<div id="ed_bfbar"></div>');
		jQuery('#ed_bfbar').html('<span id="bf_tokens">Tokens</span>: '+bfbar2);
		
	}
}

function bf_add_tag(id) {
	id = id.replace(/ozh_bf_/,'');
	edInsertTag(edCanvas, id);
}

function bf_select(e) {
	edInsertTag(edCanvas, bf_list[e.value]);
}

// http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_date/
function date ( format, timestamp ) {
    // http://kevin.vanzonneveld.net
    // +   original by: Carlos R. L. Rodrigues (http://www.jsfromhell.com)
    // +      parts by: Peter-Paul Koch (http://www.quirksmode.org/js/beat.html)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: MeEtc (http://yass.meetcweb.com)
    // +   improved by: Brad Touesnard
    // +   improved by: Tim Wiel
    // *     example 1: date('H:m:s \\m \\i\\s \\m\\o\\n\\t\\h', 1062402400);
    // *     returns 1: '09:09:40 m is month'
    // *     example 2: date('F j, Y, g:i a', 1062462400);
    // *     returns 2: 'September 2, 2003, 2:26 am'
 
    var a, jsdate=((timestamp) ? new Date(timestamp*1000) : new Date());
    var pad = function(n, c){
        if( (n = n + "").length < c ) {
            return new Array(++c - n.length).join("0") + n;
        } else {
            return n;
        }
    };
    var txt_weekdays = ["Sunday","Monday","Tuesday","Wednesday",
        "Thursday","Friday","Saturday"];
    var txt_ordin = {1:"st",2:"nd",3:"rd",21:"st",22:"nd",23:"rd",31:"st"};
    var txt_months =  ["", "January", "February", "March", "April",
        "May", "June", "July", "August", "September", "October", "November",
        "December"];
 
    var f = {
        // Day
            d: function(){
                return pad(f.j(), 2);
            },
            D: function(){
                t = f.l(); return t.substr(0,3);
            },
            j: function(){
                return jsdate.getDate();
            },
            l: function(){
                return txt_weekdays[f.w()];
            },
            N: function(){
                return f.w() + 1;
            },
            S: function(){
                return txt_ordin[f.j()] ? txt_ordin[f.j()] : 'th';
            },
            w: function(){
                return jsdate.getDay();
            },
            z: function(){
                return (jsdate - new Date(jsdate.getFullYear() + "/1/1")) / 864e5 >> 0;
            },
 
        // Week
            W: function(){
                var a = f.z(), b = 364 + f.L() - a;
                var nd2, nd = (new Date(jsdate.getFullYear() + "/1/1").getDay() || 7) - 1;
 
                if(b <= 2 && ((jsdate.getDay() || 7) - 1) <= 2 - b){
                    return 1;
                } else{
 
                    if(a <= 2 && nd >= 4 && a >= (6 - nd)){
                        nd2 = new Date(jsdate.getFullYear() - 1 + "/12/31");
                        return date("W", Math.round(nd2.getTime()/1000));
                    } else{
                        return (1 + (nd <= 3 ? ((a + nd) / 7) : (a - (7 - nd)) / 7) >> 0);
                    }
                }
            },
 
        // Month
            F: function(){
                return txt_months[f.n()];
            },
            m: function(){
                return pad(f.n(), 2);
            },
            M: function(){
                t = f.F(); return t.substr(0,3);
            },
            n: function(){
                return jsdate.getMonth() + 1;
            },
            t: function(){
                var n;
                if( (n = jsdate.getMonth() + 1) == 2 ){
                    return 28 + f.L();
                } else{
                    if( n & 1 && n < 8 || !(n & 1) && n > 7 ){
                        return 31;
                    } else{
                        return 30;
                    }
                }
            },
 
        // Year
            L: function(){
                var y = f.Y();
                return (!(y & 3) && (y % 1e2 || !(y % 4e2))) ? 1 : 0;
            },
            //o not supported yet
            Y: function(){
                return jsdate.getFullYear();
            },
            y: function(){
                return (jsdate.getFullYear() + "").slice(2);
            },
 
        // Time
            a: function(){
                return jsdate.getHours() > 11 ? "pm" : "am";
            },
            A: function(){
                return f.a().toUpperCase();
            },
            B: function(){
                // peter paul koch:
                var off = (jsdate.getTimezoneOffset() + 60)*60;
                var theSeconds = (jsdate.getHours() * 3600) +
                                 (jsdate.getMinutes() * 60) +
                                  jsdate.getSeconds() + off;
                var beat = Math.floor(theSeconds/86.4);
                if (beat > 1000) beat -= 1000;
                if (beat < 0) beat += 1000;
                if ((String(beat)).length == 1) beat = "00"+beat;
                if ((String(beat)).length == 2) beat = "0"+beat;
                return beat;
            },
            g: function(){
                return jsdate.getHours() % 12 || 12;
            },
            G: function(){
                return jsdate.getHours();
            },
            h: function(){
                return pad(f.g(), 2);
            },
            H: function(){
                return pad(jsdate.getHours(), 2);
            },
            i: function(){
                return pad(jsdate.getMinutes(), 2);
            },
            s: function(){
                return pad(jsdate.getSeconds(), 2);
            },
            //u not supported yet
 
        // Timezone
            //e not supported yet
            //I not supported yet
            O: function(){
               var t = pad(Math.abs(jsdate.getTimezoneOffset()/60*100), 4);
               if (jsdate.getTimezoneOffset() > 0) t = "-" + t; else t = "+" + t;
               return t;
            },
            P: function(){
                var O = f.O();
                return (O.substr(0, 3) + ":" + O.substr(3, 2));
            },
            //T not supported yet
            //Z not supported yet
 
        // Full Date/Time
            c: function(){
                return f.Y() + "-" + f.m() + "-" + f.d() + "T" + f.h() + ":" + f.i() + ":" + f.s() + f.P();
            },
            //r not supported yet
            U: function(){
                return Math.round(jsdate.getTime()/1000);
            }
    };
 
    return format.replace(/[\\\\]?([a-zA-Z])/g, function(t, s){
        if( t!=s ){
            // escaped
            ret = s;
        } else if( f[s] ){
            // a date function exists
            ret = f[s]();
        } else{
            // nothing special
            ret = s;
        }
 
        return ret;
    });
}

</script>
	
JS;


}


function wp_ozh_betterfeed_help() {

	$feed = get_bloginfo('wpurl').'/wp-feed.php';
	echo <<<HELP
	<div style="display:none" id="bf_helpdiv">
	<h2>A Few Guidelines</h2>
	<ul>
	<li><b>Don't style</b><br/>
	Your readers use a wide variety of feed readers that don't render the same. Don't bother with too much CSS style, be raw. Make sure you don't forget to &lt;/close> tags.</li>
	
	<li><b>Don't test in your web browser</b><br/>
	It's very likely that your web browser is not a decent feed reader and it won't show the cool things added by this plugin. I've set up a simple service
	that will show your feed as real feed readers will: <a href="http://planetozh.com/projects/rss2html/">RSS 2 HTML</a>. Feel free to use it to tweak your feed footer till you're happy with it.
	<form action="http://planetozh.com/projects/rss2html/" target="_blank" method="post"><input type="hidden" name="parse" value="1" />	<input type="hidden" name="url" value="$feed"/><input type="submit" value="Check Now"/> (Don't forget to click "Save Changes" first)</form>
	</li>

	<li><b>FeedBurner won't reflect immediately</b><br/>
	If you are a FeedBurner user, keep in mind that changes you'll make here won't reflect immediately, as FeedBurner makes a copy of your feed only when a new post is made.<br/>
	You can still use the "Resyncing" option in your FeedBurner dashboard &rarr; Troubleshootize &rarr; "Resync Now"
	</li>
	
	<li><b>Insert a copyright anti-splogger don't-steal-my-content notice</b><br/>
	Insert a &copy; link. At least content stealers using automated tools will give you a link back.</li>

	<li><b>Date token</b><br/>
	Outputs the post date. You can use any format for this token that matches <a href="http://www.php.net/date">PHP's date()</a> syntax. Examples:<ul><li><code>%%date[Y/m/d]%%</code></li><li><code>%%date[Y]%%</code></li><li><code>%%date[F j, Y, g:i a]%%</code></li></ul></li>
	
	<li><b>PHP Code token</b><br/>
	<em>This is for <b>experienced users only</b>. No support or help will be provided for this feature.</em><br/>
	You can pass any PHP function using the following syntax: <code>%%&lt;?php [php code] ?>%%</code> (it won't produce actual results in the preview)<br/>
	The code must be valid PHP code, including proper escaping and semicolons, as in <a href="http://www.php.net/date">PHP's eval()</a>. Examples:
	<ul><li><code>%%&lt;?php echo "hello"; ?>%%</code></li>
	<li><code>%%&lt;?php somefunction(); ?>%%</code></li>
	<li><code>%%&lt;?php if (sometest()) {echo \$something;} ?>%%</code></li>
	</ul>
	</li>
	
	<li><b>Have fun</b><br/>
	I hope you will find this plugin useful. Like it? Please blog about it so your readers will find about it. Love it? Please consider buying me <a href="http://planetozh.com/exit/donate">a beer</a>:)</li>
	</ul>
	</div>
HELP;
}

function wp_ozh_betterfeed_footer() {
	$src = WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)) . '/bf_icon.gif';
	echo <<<HTML
<style>
#bf_footer_steroids {background:transparent url($src) top right no-repeat;width:18em;margin-bottom:5px;display:block;}
/* hello strider! :) */
#bf_footer_steroids em {font-size:120%;}
</style>
<span id="bf_footer_steroids">Plugin <a href="http://planetozh.com/blog/my-projects/wordpress-plugin-better-feed-rss/">Better Feed</a> by <a href="http://planetozh.com/">Ozh</a><br/>
<em>Your feed on steroids!</em></span>
HTML;
}

?>