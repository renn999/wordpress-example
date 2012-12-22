<?php
if( is_single() ) { ?>
	<aside id="sidebar1" class="sidebar<?php $options = get_option('pixiv_options'); if($options['single_side_width'] != 'normal' || $options['single_layout'] == 'both_three') echo ' slim'; if($options['single_layout'] == 'both_three') echo ' alignleft'; ?>">
		<?php if($options['sidebar_author_info']) get_template_part('sidebar','author'); ?>
		<?php if( ! is_active_sidebar('sidebar1-post') ) {
			if( ! is_active_sidebar('sidebar1-main') )
				get_template_part('sidebar','default');
			else
				dynamic_sidebar('sidebar1-main');
		} else {
			dynamic_sidebar('sidebar1-post');
		} ?>
	</aside>
	<?php if($options['single_layout'] == 'both_three') { ?>
	<aside id="sidebar2" class="sidebar alignright slim">
		<?php if( ! is_active_sidebar('sidebar2-post') ) {
			if( ! is_active_sidebar('sidebar2-main') )
				get_template_part('sidebar','default');
			else
				dynamic_sidebar('sidebar2-main');
		} else {
			dynamic_sidebar('sidebar2-post');
		} ?>
		
	</aside>
	<?php }
} else { ?>
	<aside id="sidebar1" class="sidebar<?php $options = get_option('pixiv_options'); if($options['index_layout'] == 'both_three') echo ' alignleft slim'; ?>">
		<?php
		if( ! is_active_sidebar('sidebar1-main') )
			get_template_part('sidebar','default');
		else
			dynamic_sidebar('sidebar1-main');
		?>
	</aside>
	<?php if($options['index_layout'] == 'both_three') { ?>
	<aside id="sidebar2" class="sidebar alignright slim">
		<?php
		if( ! is_active_sidebar('sidebar2-main') )
			get_template_part('sidebar','default');
		else
			dynamic_sidebar('sidebar2-main');
		?>
	</aside>
	<?php }
} ?>