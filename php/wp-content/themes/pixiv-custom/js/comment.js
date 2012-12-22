// Insert strings to comment form
function insertStr(string){	
	var textBox = document.getElementById('comment');
	if (document.selection){
		textBox.focus();
		sel = document.selection.createRange();
		sel.text = string;
	} else if (textBox.selectionStart != 'undefined' && textBox.selectionEnd != 'undefined'){
		var startPos = textBox.selectionStart;
			endPos = textBox.selectionEnd;
		textBox.value = textBox.value.substring(0, startPos) + string + textBox.value.substring(endPos, textBox.value.length);
	} else {
		textBox.value += string;
	}
	textBox.focus();
}

// Insert reply
function reply(object){
	 var replyID = object.attr('id');
	 	 replyName = object.attr('title');
	 	 
	 var string = '<a href="#' + replyID + '">@' + replyName.replace(/\t|\n|\r\n/g, "") + '</a> \n';
	 insertStr(string);
}

// Insert quotes
function quote(author,commentID,commentBody){
	var authorID = author.attr('id');
		authorName = author.attr('title');
		commentBodyID = commentBody.attr('id');
		comment = commentBody.html();
	
	var string = '<blockquote cite="#' + commentBodyID + '">';
		string += '\n<strong><a href="#' + commentID + '">' + authorName.replace(/\t|\n|\r\n/g, "") + '</a> :</strong>';
		string += comment.replace(/\t/g, "");
		string += '</blockquote>\n';
		
		insertStr(string);
}

// Active reply function
function replyAction(){
	jQuery('.comment-reply-link').click(function(){
		var author = jQuery(this).parent().parent().children('.alignleft').children('.author');
		reply(author);
	});
	jQuery('#cancel-comment-reply-link').click(function(){
		jQuery('#comment').val('');
	});
}

// Commentlist AJAX Navigation
function commAJAXNavi() {
	jQuery('.comment-pagenavi a').click(function(){
		jQuery.ajax({
			url: jQuery(this).attr('href'),
			type: 'GET',
			beforeSend: function(){
				document.body.style.cursor = 'wait';
				jQuery('.comment-pagenavi').html('<span class="loading">' + al_loading + '</span>');
			},
			error: function(request){
				alert(al_error);
			},
			success: function(data){
				var content = jQuery('<div></div>').html(data).find('.commentlist, .comment-pagenavi');
				jQuery('.commentlist, .comment-pagenavi').remove();
				jQuery('#respond').before(content);
				jQuery('html,body').animate({scrollTop: jQuery('#comment_area').offset().top - 30}, 800, "quart");
				document.body.style.cursor = 'auto';
			}
		});
		return false;
	});
}

// Define elements
jQuery(document).ready(function($){
	$('.comment-quote-link').click(function(){
		var author = $(this).parent().parent().children('.alignleft').children('.author');
		var commentID = $(this).parent().parent().parent().attr('id');
		var commentBody = $(this).parent().parent().next().next();
		quote(author,commentID,commentBody);
	});
});