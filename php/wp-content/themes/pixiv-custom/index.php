<?php get_header(); ?>
	<?php $options = get_option('pixiv_options'); if ( $options['index_notice_enable'] ) { ?><div class="allround notice"><?php echo html_entity_decode($options['index_notice_content']); ?></div>
	<?php } ?>
	<?php get_template_part('loop','index'); ?>
	</div>
	<?php get_sidebar(); ?>
	<?php get_footer(); ?>