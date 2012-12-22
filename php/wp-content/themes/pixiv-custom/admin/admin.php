<?php
class pixivOpt {
	private $sections;
	
	private $defaults = array(
		// Header
		'header_menu_new' => 'new',
		'menu_type' => 'main',
		'header_tagline' => '0',
		'use_banner' => '1',
		'random_banner' => '0',
		'tag_suggest' => '1',
		'google_search_enable' => '0',
		'google_search_id' => '',
		'index_notice_enable' => '0',
		'index_notice_content' => '',
		'header_ads_enable' => '0',
		'header_ads_code' => '',
		// Reading
		'index_layout' => 'left_two',
		'single_layout' => 'left_two',
		'single_side_width' => 'slim',
		'list_layout' => 'full',
		'links_color' => '258fb8',
		'font_famliy' => 'meiryo',
		'pagenavi_type' => 'pager',
		'pagenavi_ajax' => '0',
		'pagenavi_autoload' => '0',
		'sharing_button' => '1',
		'share_fb' => '1',
		'share_+1' => '1',
		'share_pl' => '1',
		'share_tw' => '1',
		'share_addtoany' => '1',
		// Comments
		'trackback_options' => 'enable',
		'reply_before_at' => '0',
		'comment_ajax_enable' => '0',
		'comment_ajax_reedit' => '0',
		'comment_ajax_restrict' => '15',
		'comment_ajax_navi' => '0',
		'reply_notify_enable' => '0',
		'reply_notify_admin_email' => '',
		'reply_notify_subject' => '',
		'reply_notify_content' => '',
		// Sidebar
		'sidebar_author_info' => '1',
		'author_name' => '1',
		'author_avatar' => '1',
		'author_desc' => '0',
		'sidebar_facebook' => '0',
		'facebook_content' => '',
		'sidebar_plurk' => '0',
		'plurk_link' => '',
		'sidebar_twitter' => '0',
		'twitter_link' => '',
		'sidebar_rss' => '1',
		'rss_link' => '',
		// Footer
		'footer_menu_enable' => '1',
		'footer_content' => '',
		'footer_ads_enable' => '0',
		'footer_ads_code' => '',
	);
	
	private $checkboxes, $textareas;
	
	public function __construct() {
		$this->checkboxes = array();
		$this->textareas = array();
		
		$this->sections['header'] = __('Header','pixiv');
		$this->sections['reading'] = __('Reading','pixiv');
		$this->sections['comment'] = __('Comments','pixiv');
		$this->sections['sidebar'] = __('Sidebar','pixiv');
		$this->sections['footer'] = __('Footer','pixiv');
		$this->sections['manual'] = __('Manual','pixiv');
		
		$DBOptions = get_option('pixiv_options');
		$defaults = $this->defaults;
			
		if ( !is_array($DBOptions) ) $DBOptions = array();
		
		foreach ( $DBOptions as $key => $value ) {
			if ( isset($DBOptions[$key]) )
				$defaults[$key] = $DBOptions[$key];
		}
		update_option('pixiv_options', $defaults);
		
		add_action( 'admin_menu', array( &$this, 'add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );
	}
	
	public function add_pages() {
		$admin_page = add_theme_page( __('Theme Options','pixiv'), __('Theme Options','pixiv'), 'edit_theme_options', 'pixiv-settings', array( &$this, 'display_page' ) );
		
		add_action( 'admin_print_scripts-' . $admin_page, array( &$this, 'scripts' ) );
		add_action( 'admin_print_styles-' . $admin_page, array( &$this, 'styles' ) );
	}
		
	public function create_setting( $args = array() ) {
		$defaults = array(
			'id'		=> '',
			'title'		=> '',
			'desc'		=> '',
			'type'		=> 'text',
			'section'	=> 'general',
			'choices'	=> array(),
			'class'		=> '',
			'before'	=> '',
			'after'		=> '',
		);
		
		extract( wp_parse_args( $args, $defaults ) );
		
		$field_args = array(
			'type'		=> $type,
			'id'		=> $id,
			'desc'		=> $desc,
			'choices'	=> $choices,
			'label_for'	=> $id,
			'class'		=> $class,
			'before'	=> $before,
			'after'		=> $after,
		);
		
		add_settings_field( $id, $title, array( $this, 'display_settings' ), 'pixiv-settings', $section, $field_args );
		
		if ( $type == 'checkbox' )
			$this->checkboxes[] = $id;
		if ( $type == 'textarea' )
			$this->textareas[] = $id;
	}
	
	public function display_page() { ?>
		<div class="wrap">
			<div class="header">
				<div id="pixiv_switch">
					<h2><?php _e('Theme Options','pixiv'); ?></h2>
					<a id="tab0"><?php _e('Header', 'pixiv'); ?></a>
					<a id="tab1"><?php _e('Reading', 'pixiv'); ?></a>
					<a id="tab2"><?php _e('Comments', 'pixiv'); ?></a>
					<a id="tab3"><?php _e('Sidebar', 'pixiv'); ?></a>
					<a id="tab4"><?php _e('Footer', 'pixiv'); ?></a>
					<a id="tab5"><?php _e('Manual', 'pixiv'); ?></a>
				</div>
			</div>
			<form action="options.php" method="post" id="pixiv" enctype="multipart/form-data">
				<?php
				if ( isset( $_GET['settings-updated'] ) )
					echo '<div class="updated fade"><p><strong>'.__('&radic; Saved','pixiv').'</strong></p></div>';
				?>
				<?php
				settings_fields('pixiv_options');
				do_settings_sections( $_GET['page'] );
				?>
				<p class="center">
					<input type="submit" name="pixiv_options[submit]" class="button-primary" value="<?php esc_attr_e('Save Changes', 'pixiv'); ?>" />
					<input type="submit" name="pixiv_options[reset]" class="button-secondary" onclick="if(confirm('<?php _e('All current settings will be reset to defaults. Are you sure?','pixiv'); ?>')) return true; else return false;" value="<?php esc_attr_e('Reset to Deafults', 'pixiv'); ?>" />
				</p>
			</form>
		</div>
	<?php }
	
	public function display_section() {
	}
	
	public function manual_section() { ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Comment Reply Notification','pixiv'); ?></th>
				<td id="manuals">
					<span style="color: #888;"><?php _e('&lowast; Be sure that your server support mail() function.','pixiv'); ?></span><br />
					<?php _e('You can edit the content of notification by the tags below:','pixiv'); ?><br />
					<ul class="manual">
					<li><code>[blogname]</code> &rarr; <?php _e('The name of your blog.','pixiv'); ?></li>
					<li><code>[postname]</code> &rarr; <?php _e('The name of the post.','pixiv'); ?></li>
					<li><code>[commauthor]</code> &rarr; <?php _e('The author of parent comment.','pixiv'); ?></li>
					<li><code>[replyauthor]</code> &rarr; <?php _e('The author of reply.','pixiv'); ?></li>
					<li><code>[commcontent]</code> &rarr; <?php _e('The content of parent comment.','pixiv'); ?></li>
					<li><code>[replycontent]</code> &rarr; <?php _e('The content of reply.','pixiv'); ?></li>
					<li><code>[commurl]</code> &rarr; <?php _e('The link of the comment.','pixiv'); ?></li>
					</ul>
					<p><?php _e('Use HTML to configure your own notification. You can configure other advanced settings in "function.php", like the rules of sending, or whether to send notification to admin.','pixiv'); ?></p>
				</td>
			</tr>
		</table>
	<?php }
	
	public function display_settings( $args = array() ) {
		extract( $args );
		
		$options = get_option('pixiv_options');
		
		if ( $class != '' )
			echo '<div class="' . $class . '">';
		
		if ( $desc != '' )
			echo $desc . '<br />';
		
		if ( $before != '' )
			echo '<label for="' . $id . '">' . $before . '</label>';
		
		switch ( $type ) {
			case 'checkbox':
				echo '<input type="checkbox" id="' . $id . '" name="pixiv_options[' . $id . ']" value="1"'.checked( $options[$id], 1, false ) . ' />';
				
				break;
			
			case 'select':
				echo '<select name="pixiv_options[' . $id . ']">';
				
				foreach ( $choices as $value => $label )
					echo '<option value="' . $value . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';
					
				echo '</select>';
				
				break;
			
			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input type="radio" name="pixiv_options[' . $id . ']" id="' . $id . $i . '" value="' . $value . '"'. checked( $options[$id], $value, false ) . ' /><label for="' . $id . $i . '" class="radio">' . $label . '</label>';
					$i++;
				}
				
				break;
			
			case 'textarea':
				echo '<textarea id="' . $id . '" name="pixiv_options[' . $id . ']" cols="65%" rows="10">' . $options[$id] . '</textarea>'; 
				
				break;
				
			case 'text':
			default:
				echo '<input type="text" id="' . $id . '" name="pixiv_options[' . $id . ']" value="' . $options[$id] . '" />';
				
				break;
		}
		
		if ( $after != '' )
			echo '<label for="' . $id . '">' . $after . '</label>';
			
		if ( $class != '' )
			echo '</div>';
	}
	
	public function links_color_field() {
		$options = get_option('pixiv_options');
		_e('Choose the link color you like. Default is <code>#258fb8</code>.', 'pixiv');
		echo '<br /><label for="links_color">'.__('Color: #', 'pixiv').'</label>';
		echo '<input type="text" id="links_color" maxlength="6" value="' . $options['links_color'] . '" name="pixiv_options[links_color]" />';
		echo '<div class="color_preview" style="background: #' . $options['links_color'] . ';"></div><a id="color_pick_link" href="#">'.__('Pick a color', 'pixiv').'</a><div id="color_pick"></div>';
	}
	
	public function rn_subject_field() {
		$options = get_option('pixiv_options');
		_e('Custom the content of notification.', 'pixiv');
		echo '<br /><div class="manual">'.
		__('You can use following tags: ', 'pixiv').'<code>[blogname]</code>, <code>[postname]</code>, <code>[commauthor]</code>, <code>[replyauthor]</code>, <code>[commcontent]</code>, <code>[replycontent]</code>, <code>[commurl]</code>. '.
		__('To learn more, please visit "Manual" tab.', 'pixiv').'</div>';
		echo '<label for="reply_notify_subject">'.__('Subject: ', 'pixiv').'</label>';
		echo '<input type="text" id="reply_notify_subject" value="' . $options['reply_notify_subject'] . '" name="pixiv_options[reply_notify_subject]" />';
	}
	
	public function rn_content_field() {
		$options = get_option('pixiv_options');
		echo '<div class="repcon">'.__('Content: ', 'pixiv').'<small>'.__('(HTML is avalible.)', 'pixiv').'</small>';
		echo '<textarea id="reply_notify_content" rows="10" name="pixiv_options[reply_notify_content]">' . $options['reply_notify_content'] . '</textarea>'.__('Preview:', 'pixiv'); ?>
		<div id="notify_preview">
			<div class="subject">
				<strong><?php _e('Subject:','pixiv'); ?></strong>
				<span class="preview"></span>
				<span class="default"><?php _e('(No Title)','pixiv'); ?></span>
			</div>
			<div class="from">
				<strong><?php _e('From:','pixiv'); ?></strong>
				<span class="preview"></span>
				<span class="default"><?php _e('(No Sender)','pixiv'); ?></span>
			</div>
			<div class="content">
				<span class="preview"></span>
				<span class="default"></span>
			</div>
		</div>
	</div>
	<?php }
	
	public function styles() {
		wp_enqueue_style('pixivAdminCSS', get_template_directory_uri().'/admin/admin.css');
		wp_enqueue_style( 'farbtastic' );
	}
	
	public function scripts() {
		wp_enqueue_script('pixivAdminJS', get_template_directory_uri().'/admin/admin.js');
		wp_enqueue_script( 'farbtastic' );
	}
	
	public function register_settings() {
		register_setting( 'pixiv_options', 'pixiv_options', array( &$this, 'validate_settings' ) );
		
		foreach ( $this->sections as $slug => $title )
			if ( $slug == 'manual' )
				add_settings_section( $slug, '', array( &$this, 'manual_section' ), 'pixiv-settings' );
			else
				add_settings_section( $slug, '', array( &$this, 'display_section' ), 'pixiv-settings' );
		
		// Header
		$this->create_setting( array (
			'id'		=> 'header_menu_new',
			'title'		=> __('Menus', 'pixiv'),
			'type'		=> 'select',
			'section'	=> 'header',
			'before'	=> __('Display the main (left) menu ','pixiv'),
			'after'		=> __(' the title.', 'pixiv'),
			'choices'	=> array(
				'old'	=> __('beside','pixiv'),
				'new'	=> __('under','pixiv')
			)
		) );
		
		$this->create_setting( array (
			'id'		=> 'menu_type',
			'desc'		=> __('Select which menu to display.', 'pixiv').' (<a href="'.site_url().'/wp-admin/nav-menu.php" target="_blank">'.__('Edit menus','pixiv').'</a>)',
			'type'		=> 'radio',
			'section'	=> 'header',
			'choices'	=> array(
				'main'	=> __('Main (Left)','pixiv'),
				'slave'	=> __('Subscription (Right)','pixiv'),
				'both'	=> __('Both','pixiv')
			)
		) );
		
		$this->create_setting( array (
			'id'		=> 'header_tagline',
			'title'		=> __('Tagline', 'pixiv'),
			'desc'		=> __('Display the tagline.', 'pixiv'),
			'type'		=> 'checkbox',
			'section'	=> 'header',
			'after'		=> __('Enable', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'use_banner',
			'title'		=> __('Banner', 'pixiv'),
			'desc'		=> __('Display the banner.', 'pixiv'),
			'type'		=> 'checkbox',
			'section'	=> 'header',
			'after'		=> __('Enable', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'random_banner',
			'type'		=> 'checkbox',
			'desc'		=> __('Put the photos (980x200 px) in the folder <code>/headers</code>. When page is refreshed, the photos in the folder will be randomly displayed.', 'pixiv'),
			'section'	=> 'header',
			'after'		=> __('Enable Random Banner', 'pixiv'),
			'class'		=> 'child'
		) );
		
		$this->create_setting( array (
			'id'		=> 'tag_suggest',
			'title'		=> __('Search', 'pixiv'),
			'desc'		=> __('Display search suggestions.', 'pixiv').' (<a href="'.site_url().'/wp-admin/edit-tags.php" target="_blank">'.__('Edit tags', 'pixiv').'</a>)',
			'type'		=> 'checkbox',
			'section'	=> 'header',
			'after'		=> __('Enable', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'google_search_enable',
			'desc'		=> __('Use Google Custom Search instead of default.', 'pixiv'),
			'type'		=> 'checkbox',
			'section'	=> 'header',
			'after'		=> __('ID: ','pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'google_search_id',
			'type'		=> 'text',
			'section'	=> 'header',
		) );
		
		$this->create_setting( array (
			'id'		=> 'index_notice_enable',
			'title'		=> __('Notice', 'pixiv'),
			'desc'		=> __('Display the notice in home page.', 'pixiv').' <small>'.__('(HTML is avalible.)', 'pixiv').'</small>',
			'type'		=> 'checkbox',
			'section'	=> 'header',
			'after'		=> __('Enable', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'index_notice_content',
			'type'		=> 'textarea',
			'class'		=> 'child',
			'section'	=> 'header'
		) );
		
		$this->create_setting( array (
			'id'		=> 'header_ads_enable',
			'title'		=> __('Ads (Header)', 'pixiv'),
			'desc'		=> __('To Display your ads in header, please enter the ads code below. (Max width is 960px)', 'pixiv'),
			'type'		=> 'checkbox',
			'section'	=> 'header',
			'after'		=> __('Enable', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'header_ads_code',
			'type'		=> 'textarea',
			'class'		=> 'child',
			'section'	=> 'header'
		) );
		
		// Reading
		$this->create_setting( array (
			'id'		=> 'index_layout',
			'title'		=> __('Layout','pixiv'),
			'desc'		=> __('Select the layout.', 'pixiv').' (<a href="'.site_url().'/wp-admin/widgets.php" target="_blank">'.__('Edit widgets','pixiv').'</a>)',
			'type'		=> 'radio',
			'section'	=> 'reading',
			'before'	=> __('Home: ','pixiv'),
			'choices'	=> array(
				'left_two'	=> __('2 columns, left sidebar','pixiv'),
				'right_two'	=> __('2 columns, right sidebar','pixiv'),
				'both_three'=> __('3 columns, sidebar on both sides','pixiv')
			)
		) );
		
		$this->create_setting( array (
			'id'		=> 'single_layout',
			'type'		=> 'radio',
			'section'	=> 'reading',
			'before'	=> __('Post: ','pixiv'),
			'choices'	=> array(
				'left_two'	=> __('2 columns, left sidebar','pixiv'),
				'right_two'	=> __('2 columns, right sidebar','pixiv'),
				'both_three'=> __('3 columns, sidebar on both sides','pixiv')
			)
		) );
		
		$this->create_setting( array (
			'id'		=> 'single_side_width',
			'desc'		=> __('Select the width of sidebar in posts. (Only in 2 columns)', 'pixiv'),
			'type'		=> 'radio',
			'section'	=> 'reading',
			'choices'	=> array(
				'slim'	=> __('Slim (Default, 180px)','pixiv'),
				'normal'=> __('Normal (260px)','pixiv'),
			)
		) );
		
		$this->create_setting( array (
			'id'		=> 'list_layout',
			'desc'		=> __('Select the post previews layout that apply to home page & archive pages.','pixiv'),
			'type'		=> 'radio',
			'section'	=> 'reading',
			'choices'	=> array(
				'full'		=> __('Full','pixiv'),
				'thumbnail'	=> __('Excerpt & Thumbnails','pixiv'),
				'title'		=> __('Only Title','pixiv')
			)
		) );
		
		add_settings_field( 'links_color', __('Font', 'pixiv'), array( &$this, 'links_color_field' ), 'pixiv-settings', 'reading' );
		
		$this->create_setting( array (
			'id'		=> 'font_famliy',
			'desc'		=> __('Choose the font you like.', 'pixiv'),
			'type'		=> 'select',
			'section'	=> 'reading',
			'choices'	=> array(
				'meiryo'	=> __('Meiryo (Default, Japanese)', 'pixiv'),
				'segoe'		=> __('Segoe UI (Windows Vista/7)', 'pixiv'),
				'arial'		=> __('Helvetica/Arial (sans serif)', 'pixiv'),
				'georgia'	=> __('Georgia (serif)', 'pixiv'),
				'lucida'	=> __('Lucida Grande/Sans (Mac/Windows)', 'pixiv')
			)
		) );
		
		$this->create_setting( array (
			'id'		=> 'pagenavi_type',
			'title'		=> __('Navigation','pixiv'),
			'desc'		=> __('Select the way of navigation.', 'pixiv'),
			'type'		=> 'radio',
			'section'	=> 'reading',
			'choices'	=> array(
				'pager'	=> __('Built in theme','pixiv'),
				'plugin'	=> __('Plugin (WP-PageNavi)','pixiv'),
				'system'=> __('WordPress Default','pixiv')
			)
		) );
		
		$this->create_setting( array (
			'id'		=> 'pagenavi_ajax',
			'desc'		=> __('You can also enable AJAX, users can turn to other pages without refreshing.', 'pixiv').'<br /><span style="color: #888;">'.__('&lowast; Not compatible with Internet Explorer (IE) 8 and below.', 'pixiv').'</span>',
			'type'		=> 'checkbox',
			'section'	=> 'reading',
			'after'		=> __('Enable', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'pagenavi_autoload',
			'type'		=> 'checkbox',
			'section'	=> 'reading',
			'after'		=> __('Enable Auto-Load', 'pixiv'),
			'class'		=> 'child'
		) );
		
		$this->create_setting( array (
			'id'		=> 'sharing_button',
			'title'		=> __('Sharing Button', 'pixiv'),
			'desc'		=> __('Display sharing buttons in your articles.', 'pixiv'),
			'type'		=> 'checkbox',
			'section'	=> 'reading',
			'after'		=> __('Enable', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'share_fb',
			'type'		=> 'checkbox',
			'section'	=> 'reading',
			'after'		=> __('Facebook', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'share_+1',
			'type'		=> 'checkbox',
			'section'	=> 'reading',
			'class'		=> 'plus1',
			'after'		=> __('Google +1', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'share_pl',
			'type'		=> 'checkbox',
			'section'	=> 'reading',
			'class'		=> 'pl',
			'after'		=> __('Plurk', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'share_tw',
			'type'		=> 'checkbox',
			'section'	=> 'reading',
			'class'		=> 'tw',
			'after'		=> __('Twitter', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'share_addtoany',
			'type'		=> 'checkbox',
			'section'	=> 'reading',
			'class'		=> 'addtoany',
			'after'		=> __('Other sites (AddToAny)', 'pixiv')
		) );
		
		// Comments
		$this->create_setting( array (
			'id'		=> 'trackback_options',
			'title'		=> __('Trackbacks','pixiv'),
			'desc'		=> __('Select the way displaying trackback/pingback. "Separate from comments" doesn\'t take effect when you choose 3 columns layout.', 'pixiv'),
			'type'		=> 'radio',
			'section'	=> 'comment',
			'choices'	=> array(
				'enable'	=> __('Enable','pixiv'),
				'separate'	=> __('Separate from comments','pixiv'),
				'disable'=> __('Disable','pixiv')
			)
		) );
		
		$this->create_setting( array (
			'id'		=> 'reply_before_at',
			'title'		=> __('Reply', 'pixiv'),
			'desc'		=> __('Add "@username" before every reply.', 'pixiv'),
			'type'		=> 'checkbox',
			'section'	=> 'comment',
			'after'		=> __('Enable', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'comment_ajax_enable',
			'title'		=> __('AJAX', 'pixiv'),
			'desc'		=> __('Let users submit/re-edit their comments without refreshing pages. (Original by Willin Kan.)', 'pixiv').'<br /><span style="color: #888;">'.__('&lowast; Not compatible with Internet Explorer (IE) 8 and below.', 'pixiv').'</span>',
			'type'		=> 'checkbox',
			'section'	=> 'comment',
			'after'		=> __('Enable', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'comment_ajax_reedit',
			'type'		=> 'checkbox',
			'section'	=> 'comment',
			'class'		=> 'child ca_reedit',
			'after'		=> __('Enable Re-edit Feature', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'comment_ajax_restrict',
			'type'		=> 'text',
			'section'	=> 'comment',
			'class'		=> 'ca_restrict',
			'before'	=> __('Restriction of submission: ', 'pixiv'),
			'after'		=> __(' sec (0: Disable)', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'comment_ajax_navi',
			'desc'		=> __('You can also enable AJAX, users can turn to other pages without refreshing.', 'pixiv'),
			'type'		=> 'checkbox',
			'section'	=> 'comment',
			'after'		=> __('Enable', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'reply_notify_enable',
			'title'		=> __('Comment Reply Notification', 'pixiv'),
			'desc'		=> __('Notify the author of parent comment via email when replied. (Original by Willin Kan.)', 'pixiv'),
			'type'		=> 'checkbox',
			'section'	=> 'comment',
			'after'		=> __('Enable', 'pixiv')
		) );
		
		add_settings_field( 'reply_notify_subject', '', array( &$this, 'rn_subject_field' ), 'pixiv-settings', 'comment' );
		
		$this->create_setting( array (
			'id'		=> 'reply_notify_admin_email',
			'type'		=> 'text',
			'section'	=> 'comment',
			'class'		=> 'repsend',
			'before'		=> __('From: ', 'pixiv')
		) );
		
		add_settings_field( 'reply_notify_content', '', array( &$this, 'rn_content_field' ), 'pixiv-settings', 'comment' );
		
		// Sidebar
		$this->create_setting( array (
			'id'		=> 'sidebar_author_info',
			'title'		=> __('Author Information', 'pixiv'),
			'desc'		=> __('Display the information of author.', 'pixiv').'(<a href="'.site_url().'/wp-admin/profile.php" target="_blank">'.__('Edit menus', 'pixiv').'</a>)',
			'type'		=> 'checkbox',
			'section'	=> 'sidebar',
			'after'		=> __('Enable', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'author_name',
			'type'		=> 'checkbox',
			'section'	=> 'sidebar',
			'after'		=> __('Name', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'author_avatar',
			'type'		=> 'checkbox',
			'section'	=> 'sidebar',
			'class'		=> 'avatar',
			'after'		=> __('Avatar', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'author_desc',
			'type'		=> 'checkbox',
			'section'	=> 'sidebar',
			'class'		=> 'adesc',
			'after'		=> __('Description', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'sidebar_facebook',
			'desc'		=> __('To use social network, please fill in the values below.', 'pixiv'),
			'type'		=> 'checkbox',
			'section'	=> 'sidebar',
			'after'		=> __('Facebook Link: ', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'facebook_content',
			'type'		=> 'text',
			'section'	=> 'sidebar',
		) );
		
		$this->create_setting( array (
			'id'		=> 'sidebar_plurk',
			'type'		=> 'checkbox',
			'section'	=> 'sidebar',
			'after'		=> __('Plurk ID: ', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'plurk_link',
			'type'		=> 'text',
			'section'	=> 'sidebar',
		) );
		
		$this->create_setting( array (
			'id'		=> 'sidebar_twitter',
			'type'		=> 'checkbox',
			'section'	=> 'sidebar',
			'after'		=> __('Twitter ID: ', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'twitter_link',
			'type'		=> 'text',
			'section'	=> 'sidebar',
		) );
		
		$this->create_setting( array (
			'id'		=> 'sidebar_rss',
			'desc'		=> __('RSS link is optional, default is: ', 'pixiv').'<code>'.get_bloginfo('rss2_url').'</code>',
			'type'		=> 'checkbox',
			'section'	=> 'sidebar',
			'after'		=> __('RSS: ', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'rss_link',
			'type'		=> 'text',
			'section'	=> 'sidebar',
		) );
		
		// Footer
		$this->create_setting( array (
			'id'		=> 'footer_menu_enable',
			'title'		=> __('Footer Menu', 'pixiv'),
			'desc'		=> __('Display the menu in footer.', 'pixiv').' (<a href="'.site_url().'/wp-admin/nav-menu.php" target="_blank">'.__('Edit menus','pixiv').'</a>)',
			'type'		=> 'checkbox',
			'section'	=> 'footer',
			'after'		=> __('Enable', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'footer_content',
			'title'		=> __('Footer Information', 'pixiv'),
			'desc'		=> __('Display the custom information in footer. Default is "Copyright &copy; 2011 Your Site".', 'pixiv').' <small>'.__('(HTML is avalible.)', 'pixiv').'</small>',
			'type'		=> 'textarea',
			'section'	=> 'footer'
		) );
		
		$this->create_setting( array (
			'id'		=> 'footer_ads_enable',
			'title'		=> __('Ads (Footer)', 'pixiv'),
			'desc'		=> __('To display your ads in footer, please enter the ads code below. (Max width is 960px)', 'pixiv'),
			'type'		=> 'checkbox',
			'section'	=> 'footer',
			'after'		=> __('Enable', 'pixiv')
		) );
		
		$this->create_setting( array (
			'id'		=> 'footer_ads_code',
			'type'		=> 'textarea',
			'class'		=> 'child',
			'section'	=> 'footer'
		) );
	}
	
	public function validate_settings( $input ) {
		global $allowedposttags;
		
		$options = get_option('pixiv_options');
		$valid_input = $options;
		
		$submit = ( ! empty( $input['submit'] ) ? true : false );
		$reset = ( ! empty( $input['reset'] ) ? true : false );
		
		if ( $submit ) {
			foreach ( $this->checkboxes as $id ) {
				if ( isset( $options[$id] ) && ! isset( $input[$id] ) )
					$input[$id] = '0';
			}
			
			foreach ( $this->textareas as $id )
				$input[$id] = wp_kses( $input[$id], $allowedposttags );
			
			$valid_input = array_map( 'esc_attr', $input );
			
		} elseif ( $reset ) {
			$valid_input = $this->defaults;
		}
				
		return $valid_input;
	}
}
?>