<?php get_header();?>
		<div id="archive_meta">
			<?php
			if( is_category() ) {
				printf('<h2>%s</h2>', single_cat_title('', false));
				echo '<span>'; _e('Category','pixiv'); echo '</span>'; ?>
				<div class="cat_list"><?php wp_list_categories('title_li=&depth=1') ?></div>
			<?php } elseif( is_tag() ) {
				printf('<h2>%s</h2>', single_tag_title('', false) );
				echo '<span>'; _e('Tag','pixiv'); echo '</span>'; ?>
				<div class="cat_list"><?php wp_tag_cloud(
				'number=25&largest=16&order=RAND') ?></div>
			<?php } elseif( is_day() ) {
				printf('<h2>%s</h2>', get_the_time(__('M jS, Y','pixiv')));
				echo '<span>'; _e('Archives','pixiv'); echo '</span>'; ?>
				<div class="cat_list"><?php wp_get_archives('type=daily&limit=6') ?></div>
			<?php } elseif( is_month() ) {
				printf('<h2>%s</h2>', get_the_time(__('M, Y','pixiv')));
				echo '<span>'; _e('Archives','pixiv'); echo '</span>'; ?>
				<div class="cat_list"><?php wp_get_archives('type=monthly&limit=9') ?></div>
			<?php } elseif( is_year() ) {
				printf('<h2>%s</h2>', get_the_time(__('Y','pixiv')));
				echo '<span>'; _e('Archives','pixiv'); echo '</span>'; ?>
				<div class="cat_list"><?php wp_get_archives('type=yearly&limit=9') ?></div>
			<?php } elseif( is_author() ) {
				_e('Author Archive','pixiv');
			} elseif( is_search() ) {
				echo '<h2>' . $s . '</h2>'; ?><span><?php $my_query =& new WP_Query("s=$s & showposts=-1"); echo $my_query->post_count;
				_e(' search results', 'pixiv'); ?>
				<div class="cat_list"><?php wp_tag_cloud(
				'number=25&largest=16&order=RAND') ?></div>
			<?php } elseif( isset($_GET['paged']) && !empty($_GET['paged']) ) {
				_e('<h2>Blog Archives</h2>','pixiv');
			}
			?>
		</div>
		<?php get_template_part('loop','index'); ?>
		</div>
	<?php get_footer(); ?>