jQuery(document).ready(function($){
	// jQuery 1.6 compatibility
	if(!$.prop)
		$.fn.prop = $.fn.attr;
		
	$('form#pixiv h3').remove();
	
	// Google Custom Search
	cloneNremove( $('#google_search_id'), $('#google_search_enable') );
	// Sharing buttons
	cloneNremove( $('.plus1, .pl, .tw, .addtoany, .scode'), $('#share_fb') );
	$('#share_fb').parent().wrapInner('<div id="share_ex" class="child"></div>');
	// Comments
	cloneNremove( $('.ca_restrict'), $('#comment_ajax_reedit') );
	cloneNremove( $('.repsend, .repcon'), $('#reply_notify_subject') );
	$('#reply_notify_subject').parent().wrapInner('<div class="child"></div>');
	// Sidebar
	cloneNremove( $('.avatar, .adesc'), $('#author_name') );
	cloneNremove( $('#facebook_content'), $('#sidebar_facebook') );
	cloneNremove( $('#plurk_link'), $('#sidebar_plurk') );
	cloneNremove( $('#twitter_link'), $('#sidebar_twitter') );
	cloneNremove( $('#rss_link'), $('#sidebar_rss') );
	$('#sidebar_facebook').parent().wrapInner('<div id="side_fb"></div>');
	$('#sidebar_plurk').parent().wrapInner('<div id="side_pl"></div>');
	$('#sidebar_twitter').parent().wrapInner('<div id="side_tw"></div>');
	$('#sidebar_rss').parent().wrapInner('<div id="side_rss"></div>');
	cloneNremove( $('#side_fb, #side_pl, #side_tw, #side_rss'), $('#author_name') );
	$('#author_name').parent().wrapInner('<div id="side_ex" class="child"></div>');
	
	hideChild( $('#use_banner'), $('#random_banner') );
	hideChild( $('#index_notice_enable'), $('#index_notice_content') );
	hideChild( $('#header_ads_enable'), $('#header_ads_code') );
	hideChild( $('#pagenavi_ajax'), $('#pagenavi_autoload') );
	hideChild( $('#sharing_button'), $('#share_ex'), true );
	hideChild( $('#comment_ajax_enable'), $('#comment_ajax_reedit') );
	hideChild( $('#reply_notify_enable'), $('#reply_notify_subject') );
	hideChild( $('#sidebar_author_info'), $('#side_ex'), true );
	hideChild( $('#footer_ads_enable'), $('#footer_ads_code') );
	
	/* Set width of labels */
	var label1 = new Array();
		label1[0] = $('label[for="reply_notify_subject"]').width(),
		label1[1] = $('label[for="reply_notify_admin_email"]').width(),
	
	label1 = label1.sort();
	
	$('label[for="reply_notify_subject"], label[for="reply_notify_admin_email"]').width(label1[1]);
	
	var label2 = new Array();
		label2[0] = $('label[for="sidebar_facebook"]').width(),
		label2[1] = $('label[for="sidebar_plurk"]').width(),
		label2[2] = $('label[for="sidebar_twitter"]').width();
	
	label2 = label2.sort();
	
	$('label[for="sidebar_facebook"], label[for="sidebar_plurk"], label[for="sidebar_twitter"]').width(label2[2]);
	
	$('#manuals').clone().appendTo($('#notify_preview .content span.default'));
	
	/* ------------ Before Loading ------------ */
	
	/* Tab control */
	var cookie = $.cookie('pixiv_tabs') || 0;
	showTab(cookie);
	
	$("#pixiv_switch a").click(function(){
		var tab = $(this).prop('id').replace(/tab/,'');
		showTab(tab);
	});
	
	/* Hide disabled child options */
	function hideChild(object, child, table){
		var value = object.prop('checked');
		
		if (table != true)
			var child = child.parent();
		
		object.parent().addClass('object');
		
		value ? child.slideDown() : child.slideUp();
		
		$(object).click(function(){
			var value = object.prop('checked');
			value ? child.slideDown() : child.slideUp();
		});
	}
	
	/* Move the child options */
	function cloneNremove(object, target){
		var target = target.parent();
		object.appendTo(target);
	}
	
	/* Detect colors */
	function previewColor(object,target){
		var hex = object.val();
		target.css('backgroundColor', '#' + hex);
		
		( hex == '' ) ? target.hide() : target.show();
	}
	previewColor($('#links_color'),$('#links_color').next());
	$('#links_color').keyup(function(){
		previewColor($('#links_color'),$('#links_color').next());
	});
	
	/* Instant preview */
	function previewContent(object,target){
		var content = object.val();
			preview = target.children('span.preview');
			defaultValue = target.children('span.default');
		
		( content != '' ) ? defaultValue.hide() : defaultValue.show();
			
		preview.html(content);
	}
	previewContent($('#reply_notify_subject'),$('#notify_preview .subject'));
	previewContent($('#reply_notify_admin_email'),$('#notify_preview .from'));
	previewContent($('#reply_notify_content'),$('#notify_preview .content'));
	$('#reply_notify_subject').keyup(function(){
		previewContent($('#reply_notify_subject'),$('#notify_preview .subject'));
	});
	$('#reply_notify_admin_email').keyup(function(){
		previewContent($('#reply_notify_admin_email'),$('#notify_preview .from'));
	});
	$('#reply_notify_content').keyup(function(){
		previewContent($('#reply_notify_content'),$('#notify_preview .content'));
	});
	
	/* Color picker */
	var farbtastic;
	
	function pickColor(color){
		var color = color.replace(/#/,'');
		$('#links_color').val(color);
		$('.color_preview').css('backgroundColor', '#' + color);
		farbtastic.setColor(color);
	}
	$.farbtastic( '#color_pick', function(color){pickColor(color);} );
	
	$('.color_preview').hover(function(){
		$(this).children('span').fadeTo(300, 1);
	}, function(){
		$(this).children('span').fadeTo(300, 0);
	});
	
	$('.color_preview, #color_pick_link').toggle(function(){
		$('#color_pick').slideDown();
	}, function(){
		$('#color_pick').slideUp();
	});
});

function showTab(id){
	jQuery('#pixiv_switch a').removeClass('current');
	jQuery('#pixiv_switch a').eq(id).addClass('current');
	jQuery('form#pixiv table').hide();
	jQuery('form#pixiv table').eq(id).fadeIn();
	jQuery.cookie('pixiv_tabs',id,{expires: 0.5});
}

/**
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') {
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString();
        }
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else {
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};