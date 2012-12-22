/* Easing */
jQuery.easing.quart = function (x, t, b, c, d) {
	return -c * ((t=t/d-1)*t*t*t - 1) + b;
};

$body = (window.opera) ? ( document.compatMode == "CSS1Compat" ? jQuery('html') : jQuery('body') ) : jQuery('html,body');

/* Mouse hover actions */
var mouseover_tid = [];
var mouseout_tid = [];

jQuery(document).ready(function($){
	/* Menus */
	$('#topmenu li').each(function(index){
			$(this).hover(
				function(){
					var _self = this;
					clearTimeout(mouseout_tid[index]);
					mouseover_tid[index] = setTimeout(function() {
						$(_self).find('ul:eq(0)').slideDown(200);
					}, 100);
				},
				function(){
					var _self = this;
					clearTimeout(mouseover_tid[index]);
					mouseout_tid[index] = setTimeout(function() {
						$(_self).find('ul:eq(0)').slideUp(200);
					}, 100);
				}
	
			);
		});
	$('#topmenu li ul li ul li').parent().prev().addClass('nested');
	$('#topmenu li ul li ul li').parent().parent().addClass('nested');
	$('li.nested ul').hover(function(){
		$(this).prev().addClass('nested_hover');
	},function(){
		$(this).prev().removeClass('nested_hover');
	});
	
	/* Search */
	$("#tag_list").hover(function(){
		$('input#s1').addClass('enable');
		$(this).children().show();
	}, function(){
		$(this).children().hide();
		$('input#s1').removeClass('enable');
	});
	
	$('input#s1').keyup(function(){
		( $(this).val() != '' ) ? $('#search .cancel').fadeIn(600) : $('#search .cancel').fadeOut(600);
	});
	
	$('#search .cancel').click(function(){
		$('input#s1').val('');
	});
	
	/* Page top */
	$('#pagetop,.al_loading').click(function(){
		$body.animate({scrollTop: '0px'}, 800, 'quart');
		return false;
	});
	
	/* AJAX Navi Autoload - width of notification */
	$('.al_loading *').offset({left: $('#main_col').width() / 2 - $('.al_loading *').width() }).fadeIn(300);
});

function pageAJAXNavi(autoLoad){
	var trigger = false;
	
	jQuery('.wp-pagenavi a').click(function(){
		var url = jQuery(this).attr('href');
		load(url);
		return false;
	});
	
	jQuery(window).scroll(function(){
		if ( autoLoad && !trigger && $body.height() - jQuery(window).scrollTop() <= 1000 ) {
			var url = jQuery('.al_next a').attr('href');
			
			if ( typeof url != 'undefined' )
				load(url);
			
			trigger = true;
		}
	});
	
	function load(id){
		jQuery.ajax({
			url: id,
			type: 'GET',
			beforeSend: function(){
				document.body.style.cursor = 'wait';
				if ( !autoLoad )
					jQuery('.wp-pagenavi').html('<span class="loading">' + al_loading + '</span>');
			},
			error: function(request){
				alert(al_error);
			},
			success: function(data){
				if (autoLoad) {
					var content = jQuery('<div></div>').html(data).find('article, .al_loading'),
						next = id.replace(/(.*)paged=|&(.*)/g,'');
					jQuery('#main_col_inner').append(content);
					jQuery('#load' + next).html('<strong>' + next + ' / ' + maxP + '</strong>');
				} else {
					var content = jQuery('<div></div>').html(data).find('#archive_meta, #main_col_inner');
					jQuery('#main_col').html(content);
					jQuery('html,body').animate({scrollTop: jQuery('#main_col').offset().top - 40}, 800, 'quart');
				}
				document.body.style.cursor = 'auto';
			}
		});
		return false;
	}
}