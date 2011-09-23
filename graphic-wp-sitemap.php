<?php
/*
Plugin Name: Graphic WP Sitemap
Plugin URI: http://SocialBlogsiteWebDesign.com/plugins/Graphic-WP-Sitemap
Description: Represents your website's sitemap in a graphic way you can analyze to plan and fix your content for proper crawling by search engines like Google.
Version: 1.0
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
	<div id="Gsm_modal_inner" class="Gsm_window">
	<a id="Gsm_fullscreen" name="modal" href="#">Full screen</a>
	<ul id="ia_topmenu">
	<li id="home_li">
	<a href="' . site_url() . '" id="Gsm_homelink" title="Navigate to ' . site_url() . '&rsquo;s website"><strong>' . get_bloginfo('name') . '</strong></a>';
	
	if(function_exists('wp_nav_menu')) {
	wp_nav_menu( array( 'menu_id'=> 'ia_toplevel', 'menu_class'=> false, 'container'=> false ) );
	} else {
		wp_list_pages('sort_column=menu_order&title_li=');
	}
	echo '</li></ul>
	<a href="#" class="close">Close it</a>';
	if ($Gsm_nocredit !== 'TRUE' )
		echo '<p class="Gsm_linklove">Graphic WP Sitemap by <a href="http://socialblogsitewebdesign.com" alt="Graphic Wordpress Sitemap for mindmap or information architecture styled sitemap" title="Social Blogsites and Wordpress plugins" >SocialBlogsiteWebDesign.com</a></p>';
echo '</div>
	</div>
	</div>';
if (is_admin) echo'<table width="100%" border="0" cellspacing="15" cellpadding="0">
  <tr><td align="bottom">You are invited to donate if you find this plugin useful to help to get the next release sooner!</td><td align="bottom">&hellip;Or get the best feature of the future release NOW. It will allow you to show this sitemap in any page or sidebar you whish.</td><tr>
    <td><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="JNJTRUWD4UT8U">
<input type="image" src="https://www.paypalobjects.com/WEBSCR-640-20110429-1/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110429-1/en_US/i/scr/pixel.gif" width="1" height="1">
</form></td>
    <td><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="ADQYQCEMLXYWA">
<input type="image" src="https://www.paypalobjects.com/WEBSCR-640-20110429-1/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110429-1/en_US/i/scr/pixel.gif" width="1" height="1">
</form></td>
  </tr>
</table>';
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