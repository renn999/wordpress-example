<div id="archives" class="widget">
	<h3 class="title"><?php _e( 'Archives', 'pixiv' ); ?></h3>
	<ul><?php wp_get_archives( 'type=monthly' ); ?></ul>
</div>
<div id="categories" class="widget">
	<h3 class="title"><?php _e('Categories', 'pixiv'); ?></h3>
	<ul><?php wp_list_categories('depth=1&title_li='); ?></ul>
</div>
<div id="tags" class="widget">
	<h3 class="title"><?php _e('Tag Cloud', 'pixiv'); ?></h3>
	<ul><?php wp_tag_cloud(); ?></ul>
</div>
<div id="meta" class="widget">
	<h3 class="title"><?php _e( 'Meta', 'pixiv' ); ?></h3>
	<ul><?php wp_register(); ?><li><?php wp_loginout(); ?></li><?php wp_meta(); ?></ul>
</div>