<?php get_header(); ?>
		<?php if (have_posts()): while (have_posts()): the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div id="cat_navi">
					<a href="<?php echo home_url(); ?>" title="<?php _e('Home','pixiv'); ?>"><?php _e('Home','pixiv'); ?></a>
					<?php the_category('', 'multiple'); ?>
					<span><?php the_title(); ?></span>
				</div>
				<div class="clearfix"></div>
				<div id="post_navi">
					<span class="prev"><?php previous_post_link('&laquo; %link') ?></span>
					<span class="home"><a href="<?php echo home_url(); ?>" title="<?php _e('Home','pixiv'); ?>"><?php _e('Home','pixiv'); ?></a></span>
					<span class="next"><?php next_post_link('%link &raquo;') ?></span>
				</div>
				<div id="post_meta">
					<span class="alignleft"><?php the_time(__('M jS, Y','pixiv')); ?><?php edit_post_link(__('Edit','pixiv')); ?></span>
					<span class="alignright"><?php comments_popup_link(__('Comments: 0','pixiv'),__('Comments: 1','pixiv'),__('Comments: %','pixiv')); ?></span>
				</div>
				<div class="clearfix"></div>
				<h1 class="title"><?php the_title(); ?></h1>
				<div id="tag_meta">
					<?php _e('Tags','pixiv'); ?> <span><?php the_tags( '', '', ''); ?></span>
				</div>
				<?php $options = get_option('pixiv_options'); if( $options['sharing_button'] ) { ?>
				<div id="post_share">
					<?php if( $options['share_pl'] ) { ?>
					<a class="pl" href="javascript: void(window.open('http://www.plurk.com/?qualifier=shares&status=' .concat(encodeURIComponent(location.href)) .concat(' ') .concat('(') .concat(encodeURIComponent(document.title)) .concat(')')));" title="<?php _e('Share to Plurk','pixiv'); ?>">Plurk</a>
					<?php } if( $options['share_tw'] ) { ?>
					<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
					<?php } if ( $options['share_+1'] ) { ?>
					<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script><g:plusone size="medium"></g:plusone>
					<?php } if( $options['share_fb'] ) { ?>
					<div id="fb-root"></div><script src="http://connect.facebook.net/zh_TW/all.js#xfbml=1"></script><fb:like href="" send="false" layout="button_count" width="90" show_faces="false" font=""></fb:like>
					<?php } if( $options['share_addtoany'] ) { ?>
						<a class="a2a_dd little_button" href="http://www.addtoany.com/share_save"><?php _e('Share this','pixiv'); ?></a>
						<script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>
					<?php } ?>
				</div>
				<?php } ?>
				<div class="entry">
					<?php the_content(__('Read More &raquo;','pixiv')); ?>
					<div class="clearfix"></div>
					<?php wp_link_pages(); ?>
				</div>
			</article>
			<div class="clearfix"></div>
			<?php comments_template('',true); ?>
			<?php endwhile; ?>
		<?php else: get_template_part('search','failed'); endif; ?>
	</div>
	<?php get_sidebar(); ?>
	<?php get_footer(); ?>