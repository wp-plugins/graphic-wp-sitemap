<?php
/*
Plugin Name: Graphic WP Sitemap
Plugin URI: http://SocialBlogsiteWebDesign.com/plugins/Graphic-WP-Sitemap
Description: Represents your website's sitemap in a graphic way you can analyze to plan and fix your content for proper crawling by search engines like Google.
Version: 0.9
Author: Sergio Zambrano
Author URI: http://SocialBlogsiteWebDesign.com/about
License: GPL2
*/

/*  Copyright 2011  SERGIO ZAMBRANO  (email : sergio@socialblogsitewebdesign.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
	
//Build the pages List
function graphicSitemapWidget(){
	echo '<div id="Gsm_container"><div id="Gsm_modal_outer">
	<a id="Gsm_fullscreen" name="modal" href="#Gsm_modal_inner">Full screen</a>
	<div id="Gsm_modal_inner" class="Gsm_window">
	<ul id="ia_topmenu">
	<li id="home_li">
	<a href="' . site_url() . '" id="Gsm_homelink" title="Navigate to ' . site_url() . '&rsquo;s website"><strong>' . get_bloginfo('name') . '</strong></a>';
	
	if(function_exists('wp_nav_menu')) {
	wp_nav_menu( array( 'menu_id'=> 'ia_toplevel', 'menu_class'=> false, 'container'=> false ) );
	echo '</li></ul>
	<a href="#" class="close">Close it</a>
	</div>
	</div>
	</div>';
	} else {
		wp_list_pages('sort_column=menu_order&title_li=');
	}
}

/* ----------------- loads Styles ----------------- */

function add_Gsm_stylesheet() {
	$Gsm_JavascriptUrl = plugin_dir_url( __FILE__).'_js/graphic-sitemap.js';
	$Gsm_StyleUrl = plugin_dir_url( __FILE__).'_css/graphic-sitemap.css';
   echo '<link href="' . $Gsm_StyleUrl . '" rel="stylesheet" type="text/css">
';
   echo '<script language="JavaScript" src="' . $Gsm_JavascriptUrl . '" type="text/javascript"></script>
';
   }

//Setup the widget
function setupSitemapWidget(){
	//Hooks
	if (is_admin()) {
add_action('admin_head', 'add_Gsm_stylesheet');
		wp_add_dashboard_widget('graphic-wp-sitemap', 'Graphic WP Widget', 'graphicSitemapWidget');
	}
}

add_action('wp_dashboard_setup', 'setupSitemapWidget' );
?>