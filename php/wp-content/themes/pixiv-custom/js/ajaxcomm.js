jQuery(document).ready(function($){
	var loading = '<div id="loading">' + txt1 + '</div>',
		err = '<div id="error">Error!</div>',
		success = '<span class="comm_success">' + txt3,
		edit_link = '<a href="#edit" class="comm_aedit" title="' + edt1 + '">' + edt2 + '</a></span>';
	
	// jQuery 1.6 compatibility
	if(!$.prop)
		$.fn.prop = $.fn.attr;
		
	// initalize
	//$('#loading, #error').remove();
	$('#comment').after(loading + err);
	$('#loading, #error').hide();
	
	$body = (window.opera) ? ( document.compatMode == "CSS1Compat" ? $('html') : $('body') ) : $('html,body'); // opera fix
	$number1 = $('#comment_number');
	$number2 = $('#post_meta .alignright a');
	
	// submit
	$('#commentform').bind('submit', normal);
	
	function normal(){
		var parent = $('#comment_parent').attr('value');
		$.ajax({
			url: $(this).attr('action'),
			type: $(this).attr('method'),
			data: $(this).serialize(),
			beforeSend: function(){
				$('#loading').fadeIn(300);
				$('#submit').prop('disabled', true).fadeTo(300, 0.5);
			},
			error: function(request){
				var data = request.responseText.match(/<p>(.*)<\/p>/);
				$('#loading').hide();
				$('#error').html(data[1]).fadeIn(300);
				setTimeout( function(){ $('#submit').prop('disabled', false).fadeTo(500, 1); $('#error').fadeOut(500) }, 2500);
			},
			success: function(data){
				var content = $('<ol>').html(data);
				
				if ( typeof parent != 'undefined' && parent != '0' ) {
					var new_comm = content.find('#comment-' + parent).children('ul.children').children('li:last');
					new_comm.appendTo('#comment-' + parent).children('ul.children');
				} else {
					var new_comm = content.find('ol.commentlist').children('li:last');
					new_comm.appendTo( $('.commentlist') );
				}
				var respond = content.find('#respond');
				$('#respond').remove();
				$('.commentlist').after(respond);
				
				new_comm.find('.comment_meta .alignright').remove();
				edit_mode ? new_comm.append(success + edit_link) : new_comm.append(success + '</span>');
				new_comm.css({right: -100 + '%', opacity: 0}).animate({right: 0, opacity: 1}, 2500, "quart");
				$body.animate({ scrollTop: new_comm.offset().top - 300}, 300);
				$('#loading, #error').hide();
				$('#comment').val('');
				countdown();
				
				na = parseInt($number1.text().match(/\d+/));
				$number1.text($number1.text().replace(na, na + 1));
				nb = parseInt($number2.text().match(/\d+/));
				$number2.text($number2.text().replace(nb, nb + 1));
			}
		});
		return false;
	}
	
	// re-edit
	$('.comm_aedit').click(function(){
		var edit_id = $(this).parent().parent().prop('id').replace(/\D/g,'');
		$('#respond').addClass('edit').insertAfter('#comment-' + edit_id);
		$('#cancel-comment-reply-link').show();
		$('.form-submit').append('<input id="comment_edit_ID" type="hidden" name="comment_edit_ID" value="' + edit_id + '" />');
		$('#commentform').unbind('submit').bind('submit', edit);
		$body.animate({ scrollTop: $('#respond').offset().top - 180}, 400);
		$('#comment').focus();
	});
	
	$('#respond.edit').find('#cancel-comment-reply-link').click(function(){
		$('#respond').removeClass('edit');
		$('#cancel-comment-reply-link').hide();
		$('#comment_edit_ID').remove();
		$('#commentform').unbind('submit').bind('submit', normal);
		$('#respond').appendTo('#comment_area');
	});
	
	function edit(){
		var edit_id = $('#respond').prev().prop('id');
		$.ajax({
			url: '?action=ac_edit',
			type: 'POST',
			data: $(this).serialize(),
			beforeSend: function(){
				$('#loading').fadeIn(300);
				$('#submit').prop('disabled', true).fadeTo(300, 0.5);
			},
			error: function(request){
				var data = request.responseText.match(/<p>(.*)<\/p>/);
				$('#loading').hide();
				$('#error').html(data[1]).fadeIn(300);
				setTimeout( function(){ $('#submit').prop('disabled', false).fadeTo(500, 1); $('#error').fadeOut(500) }, 2500);
			},
			success: function(data){
				var content = $('<ol>').html(data),
					edt_comm = content.find('#' + edit_id);
				
				var respond = content.find('#respond');
				$('#respond').remove();
				$('.commentlist').after(respond);
				
				$('#' + edit_id).replaceWith(edt_comm);
				$('#' + edit_id).append(success + edit_link);
				$('#' + edit_id).css({right: -100 + '%', opacity: 0}).animate({right: 0, opacity: 1}, 2500, "quart");
				$('#comment').val('');
				countdown();
			}
		});
		return false;
	}
	
	// restrict submit interval
	var waitO = wait;
	function countdown(){
		if ( wait > 0 ){
			$('#submit').val(wait).prop('disabled', true).fadeTo(100, 0.5);
			wait--;
			setTimeout(countdown, 1000);
		} else {
			$('#submit').val(submit_val).prop('disabled', false).fadeTo(500, 1);
			wait = waitO;
		}
	}
});