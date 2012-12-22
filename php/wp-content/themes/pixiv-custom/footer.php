	<div class="clearfix"></div>
	<?php $options = get_option('pixiv_options'); if($options['footer_ads_enable']) { ?>
		<div class="allround"><?php echo html_entity_decode($options['footer_ads_code']); ?></div>
	<?php } ?>
	<footer id="footer">
		<div id="pagetop"><?php _e('Page Top &uarr;','pixiv'); ?></div>
		<?php if($options['footer_menu_enable']) wp_nav_menu(array('theme_location'=>'footer','depth'=>1)); ?>
		<?php if($options['footer_content']) { echo html_entity_decode($options['footer_content']); } else { ?>
		<?php _e('Copyright','pixiv'); ?> &copy; 2011 <?php bloginfo('name'); } ?>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>