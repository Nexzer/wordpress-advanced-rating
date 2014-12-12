<?php
/*
  Plugin Name: Advanced rating system
  Plugin URI: http://www.yourpluginurlhere.com/
  Version: 1.0
  Author: Alex "Nexzer" Olesen
  Description: A customizeable rating system that allows you to make your own ratings.
 */
//add admin page menu
function addMenu() {
    add_menu_page('Advanced ratings', 'Advanced ratings', 'manage_options', 'advancedratings/adminmenu.php', '', plugins_url('Alex-plugin/icon.png'));
    
}



add_action('admin_menu', 'addMenu');
//add stylesheets and javascript
function theme_name_scripts() {
    wp_enqueue_style("rating stylesheet", plugins_url('stylesheet.css', __FILE__));
    wp_enqueue_script("rating javascript", plugins_url('js.js', __FILE__), array('jquery'));
    wp_localize_script('rating javascript', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php'),'blogId'=>$_GET['p']));
}
add_action('wp_enqueue_scripts', 'theme_name_scripts');
include 'rating.php';
?>