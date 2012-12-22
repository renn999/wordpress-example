<div id="main_col_inner">
	<?php if (have_posts()): while (have_posts()): the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
			<div class="entry">
				<?php $options = get_option('pixiv_options'); if ( $options['list_layout'] == 'full') { the_content(__('Read More &raquo;','pixiv')); } elseif ( $options['list_layout'] == 'thumbnail' ) { if ( has_post_thumbnail() ) { the_post_thumbnail( array(150,150),array('class' => 'alignleft') ); } the_excerpt(); } ?>
				<div class="clearfix"></div>
				<?php if ($options['list_layout'] != 'title' ) wp_link_pages(); ?>
			</div>
			<footer class="post_date">
				<a href="<?php the_permalink(); ?>"><?php the_time(__('M jS, Y','pixiv')); ?></a>
			</footer>
		</article>
		<?php endwhile; ?>
	<?php else: get_template_part('search','failed'); endif; ?>
	<?php if( $wp_query->max_num_pages > 1 ) { 
		if ( $options['pagenavi_autoload'] ) {
			$wp_query->query_vars['paged'] > 1 ? $current=$wp_query->query_vars['paged'] : $current=1;
			$after=$current+1;
			
			if( $current < $wp_query->max_num_pages ) { ?>
				<div class="al_loading" id="load<?php echo $after ?>">
					<span class="loading"><?php _e('Loading...', 'pixiv'); ?></span>
					<span class="al_next"><?php next_posts_link(); ?></span>
				</div>
				<script type="text/javascript">var maxP = <?php echo $wp_query->max_num_pages ?>;</script>
			<?php }
		} else {
			if ($options['pagenavi_type'] == 'pager') {
				pixiv_pagenavi();
			} elseif (function_exists('wp_pagenavi') && $options['pagenavi_type'] == 'plugin') {
				wp_pagenavi();
			} else { ?>
				<nav class="wp-pagenavi">
					<span class="newer"><?php previous_posts_link(__('&laquo; Newer', 'pixiv')); ?></span>
					<span class="older"><?php next_posts_link(__('Older &raquo;', 'pixiv')); ?></span>
				</nav>
			<?php }
		}
	} ?>
</div>