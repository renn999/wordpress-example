<?php get_header(); ?>
		<?php if (have_posts()): while (have_posts()): the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1 class="title"><?php the_title(); ?></h1>
				<div class="entry">
					<?php the_content(__('Read More &raquo;','pixiv')); ?>
					<div class="clearfix"></div>
					<?php wp_link_pages(); ?>
				</div>
			</article>
			<div class="clearfix"></div>
			<?php if ( comments_open() ) comments_template('',true); ?>
			<?php endwhile; ?>
		<?php else: get_template_part('search','failed'); endif; ?>
	</div>
	<?php get_footer(); ?>