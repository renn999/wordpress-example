<?php
/**
  * Plugin Name: Let's kill IE6
  * Plugin URI: http://www.joychao.cc/280.html
  * Description: IE6下向用户提示升级浏览器,灭掉IE6，我们在行动！Let's kill IE6! www.joychao.cc 
  * Version: 2.0
  * Author: Joy chao
  * Author URI: http://www.joychao.cc
 */
  
add_action('wp_head','add_kill_script',1);
function add_kill_script(){
    echo '<!--[if lt IE 7]>',
		'<link href="'.plugins_url('img/style.css',__FILE__).'" rel="stylesheet" />',
    '<script src="'.plugins_url('lets-kill-ie6.js',__FILE__).'" type="text/javascript"></script>',
    '<![endif]-->';
}
