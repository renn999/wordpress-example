<div id="comment_area" <?php $options = get_option('pixiv_options'); if( $options['trackback_options'] == 'separate') echo ' class="slim"'; ?>>
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'pixiv' ); ?></p>
	</div>
	<?php return; endif; ?>
	
	<?php if ( have_comments() ) : ?>
		<div class="comment_title">
			<h3 class="title"><?php _e('Comments','pixiv'); ?></h3><span id="comment_number"><?php comments_number('( 0 )','( 1 )','( % )'); ?></span>
		</div>
		<div class="clearfix"></div>
		<ol class="commentlist">
			<?php
			if( $options['trackback_options'] == 'enable') {
				wp_list_comments('callback=pixiv_custom_comments');
			} else {
				wp_list_comments('type=comment&callback=pixiv_custom_comments');
			}
			?>
		</ol>
	<?php
		if (get_option('page_comments')) {
			$comment_pages = paginate_comments_links(array('prev_text' => __('&lt; Prev','pixiv'), 'next_text' => __('Next &gt;','pixiv'),'echo' => 0));
			if ($comment_pages) {
	?>
		<div class="comment-pagenavi">
			<?php echo $comment_pages; ?>
		</div>
	<?php
			}
		}
	?>
	<?php else : if ( ! comments_open() ) : ?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'pixiv' ); ?></p>
	<?php endif; ?>
	
	<?php endif; ?>
	
	<?php
	$args = array(
		'cancel_reply_link' => __('Cancel', 'pixiv'),
	);
	comment_form($args);
	?>
</div>
<?php if( $options['trackback_options'] == 'separate' ) { ?>
	<div id="trackback_area">
		<h3 class="title"><?php _e('Trackbacks','pixiv'); ?></h3>
		<div id="trackback_inner">
			<label for="trackback_url"><?php _e('Trackback URL','pixiv'); ?></label>
			<input type="text" name="trackback_url" id="trackback_url" size="60" value="<?php trackback_url() ?>" readonly="readonly" onfocus="this.select()" />
		</div>
		<?php if(!empty($comments_by_type['pings'])): ?>
		<ol class="trackbacklist">
			<?php wp_list_comments('callback=pixiv_custom_pings'); ?>
		</ol>
		<?php endif; ?>
	</div>
<?php }