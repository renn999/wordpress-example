<div id="authorinfo" class="widget<?php $options = get_option('pixiv_options'); if($options['single_side_width'] == 'normal') echo ' normal'; ?>">
	<?php if($options['author_avatar']) echo '<div class="ava">'.get_avatar(get_the_author_meta('user_email'), ( $options['single_side_width'] == 'normal' ) ? 70 : 174 ).'</div>'; ?>
	<div class="data">
	<?php if($options['author_name']) { ?><div class="name"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php printf(__( '%s'), get_the_author() ); ?>
	</a></div><?php } ?>
	<?php if($options['author_desc']) { ?><div class="desc"><?php the_author_meta('description'); ?> </div><?php } ?>
	<?php if($options['sidebar_facebook'] && $options['facebook_content']) { ?>
	<div class="little_button author fb"><a target="_blank" href="<?php echo($options['facebook_content']) ?>"><?php _e('Join my Facebook','pixiv'); ?></a></div>
	<?php } ?>
	<?php if($options['sidebar_plurk'] && $options['plurk_link']) { ?>
	<div class="little_button author pl"><a target="_blank" href="http://www.plurk.com/<?php echo($options['plurk_link']) ?>/invite"><?php _e('Follow my Plurk','pixiv'); ?></a></div>
	<?php } ?>
	<?php if($options['sidebar_twitter'] && $options['twitter_link']) { ?>
	<div class="little_button author tw"><a target="_blank" href="http://twitter.com/<?php echo($options['twitter_link']) ?>"><?php _e('Follow my Twitter','pixiv'); ?></a></div>
	<?php } ?>
	<?php if($options['sidebar_rss']) { ?>
	<div class="little_button author rss"><a target="_blank" href="<?php if($options['rss_link']) { echo($options['rss_link']); }else { bloginfo('rss2_url'); } ?>"><?php _e('Subscribe RSS','pixiv'); ?></a></div>
	<?php } ?>
	</div>
</div>