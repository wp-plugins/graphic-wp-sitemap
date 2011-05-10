<?php
/*
Plugin Name: Downloads box
Plugin URI: http://SocialBlogsiteWebDesign.com/plugins/download-box
Description: Takes care of your download links styling, adding the proper icon for each filetype, asks the user to register first, and more.
Version: 2.0
Author: Sergio Zambrano
Author URI: http://SocialBlogsiteWebDesign.com/wordpress-plugins/downloads-box
License: GPL2
*/

/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

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
$DBox_dir_url = WP_PLUGIN_URL . '/' . str_replace( basename( __FILE__), '' , plugin_basename(__FILE__));
$DBox_dir_path = WP_PLUGIN_DIR .'/'. str_replace( basename( __FILE__), '' , plugin_basename(__FILE__));

if ( ! defined( 'ABSPATH' ) )
	die( "Can't load this file directly" );

class MyDownloadbox
{
	function __construct() {
		add_action( 'admin_init', array( $this, 'action_admin_init' ) );
	}
	
	function action_admin_init() {
		// only hook up these filters if we're in the admin panel, and the current user has permission
		// to edit posts and pages
		if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
			add_filter( 'mce_buttons', array( $this, 'filter_mce_button' ) );
			add_filter( 'mce_external_plugins', array( $this, 'filter_mce_plugin' ) );
		}
	}
	
	function filter_mce_button( $buttons ) {
		// add a separation before our button, here our button's id is "mydownloadsbox_button"
		array_push( $buttons, '|', 'mydownloadsbox_button' );
		return $buttons;
	}
	
	function filter_mce_plugin( $plugins ) {
		global $DBox_dir_url;
		// this plugin file will work the magic of our button
		$plugins['mydownloadsbox'] = $DBox_dir_url . 'mydownloadsbox_plugin.js';
		return $plugins;
	}
}

$mydownloadsbox = new MyDownloadbox();


/* ----------------- loads Styles ----------------- */

if (!is_admin()) {
	add_action('wp_print_styles', 'add_downloadsbox_stylesheet');
	
		 /* Enqueue style-file, if it exists.*/
	function add_downloadsbox_stylesheet() {
		global $DBox_dir_url;
		global $DBox_dir_path;
		$myStyleUrl = $DBox_dir_url . 'download-icons.css';
		$myStyleFile = $DBox_dir_path . 'download-icons.css';
		if ( file_exists($myStyleFile) ) {
			wp_register_style('myStyleSheets', $myStyleUrl);
			wp_enqueue_style( 'myStyleSheets');
		}
	}
}


/* ------------------ LIST BUILDER ---------------------*/

function downloads_repltxt( $content, $title, $nocredit ) {
	
	$pattern = '/(href=\".+?)(?:\.([a-z0-9]{2,12}))(\.(?:[a-z0-9]{2,4}))?(\?.*)?\"/';
	$content = preg_replace($pattern, '$1.$2$3$4" class="icon-$2"', $content);
if ( !is_null( $content )) {
	if ( is_user_logged_in() && !is_feed() ) {
		$pattern = '/(href=)/';
		$content = preg_replace($pattern, 'title="Click to download this file" $1', $content);

if ( !strpos( $content, 'alt=' ) ) {
		$pattern = '/(title=)/';
		$content = preg_replace($pattern, 'alt="Click to download this file" $1', $content);
}
	
	} else {
	
		$pattern = '/href=\".*?\"/';
		$content = preg_replace($pattern, 'href="' . get_option('siteurl') . '/wp-login.php?action=register&redirect_to=' . get_permalink() . '"', $content);

		$pattern = '/(href=)/';
		$content = preg_replace($pattern, 'title="You need to register or login to download this file" $1', $content);

		$pattern = '/(title=)/';
		$content = preg_replace($pattern, 'alt="You need to register or login to download this file" $1', $content);
		
	}
}
	
	if ($nocredit !== 'true' ) $content .= '<p class="dlb_linklove"><small>Downloads Box by <a href="http://socialblogsitewebdesign.com" alt="Social Blogsites and Wordpress plugins" title="Social Blogsites and Wordpress plugins" >SocialBlogsiteWebDesign.com</a></small></p>';
		
		return $content;
}



/* ------------------ SHORTCODE ---------------------*/

function downloads_box_func( $atts, $content = null, $nocredit ) {
	
	extract( shortcode_atts( array( 'title' => 'the following files' ), $atts ) );
	$content = downloads_repltxt($content, $title, $nocredit, $downloads_box_description);
		
if ( is_user_logged_in() ) {
		return "<div class='downloads'><div id='callout_cont'><div id='callout'>You&lsquo;ve just got<br/>access to these<br/>downloads<a href='javascript://return null;' onclick='this.parentNode.parentNode.style.display=&quot;none&quot;;'> close</a></div></div><h4>Download <strong>" . $title . '</strong></h4>' . $content . '</div>';
	} else {
			return '<div class="downloads"><h4>Download <strong>' . $title . '</strong></h4>' . $content . '</div>';	
	}
}
		
add_shortcode( 'downloads_box', 'downloads_box_func' );


/* ------------------ LINKS JAVASCRIPT TO HEADER ---------------------*/

if ( !is_admin() ) {
   wp_register_script('downloads_box_frontend', $DBox_dir_url . 'mydownloadsbox_frontend.js', array('jquery') );
   // enqueue the script
   wp_enqueue_script('downloads_box_frontend');
}

/* ------------------ some functions ---------------------*/

function urlize($foundfile, $downloads_folder, $wppath) {
	$filepath = $wppath . '/' . $downloads_folder . '/' . $foundfile;
    return get_option('siteurl') . substr($filepath, strlen($wppath));
}

/* ------------------ WIDGET ---------------------*/


define(DOWNLOADBOX_WIDGET_ID, "widget_downloadsbox");

function list_downloads($downloads_folder = 'downloads', $downloads_page_based, $title ='Downloads', $downloads_box_nocredit, $downloads_box_description) {
	$downloads_path = ABSPATH . $downloads_folder;
	global $post;
	
if ($downloads_page_based) {
	$downloads_subfolder = '/'. sanitize_title($post->post_name);
	$downloads_folder .= $downloads_subfolder;
	$downloads_path .= $downloads_subfolder;
}
	if ($handle = opendir($downloads_path)) {
		$downloads_list = '<ul class="downloads downloads_list">';
		if ($downloads_box_description) $downloads_list .='<p>' . $downloads_box_description . '</p>';

		while (false !== ($foundfile = readdir($handle))) {
			$foundfile_path = $downloads_path. '/' . $foundfile;
			if(!is_dir($foundfile_path)) {
				$foundfile_url = urlize($foundfile, $downloads_folder, ABSPATH);
				$downloads_list .= '<li><a href="' . $foundfile_url . '">' . $foundfile . '</a></li>';
			}
		}
		closedir($handle);
		$downloads_list .= '</ul>';

		
		$downloads_finaltxt = downloads_repltxt ($downloads_list, $title, $downloads_box_nocredit, $downloads_box_description);


	} else {
		die('<p>No files found at specified path. Make sure to use a path relative to your Wordpress installation. E.g. if your files are in www.yoursite.com/blog/downloads and your WP installation is the &ldquo;blog&rdquo; directory, enter &ldquo;<b>downloads</b>&rdquo; only (no quotation marks). Use &ldquo;<b>wordpress/downloads</b>&rdquo;. If your WP installation is under the root of your domain instead. Check out <a href="http://socialblogsitewebdesign.com/wordpress-plugins/downloads-box">Downloads Box</a> for help</p>');
	}	
	return $downloads_finaltxt;
}

function widget_downloadsbox($args) {
  extract($args, EXTR_SKIP);
  $options = get_option(DOWNLOADBOX_WIDGET_ID);

  // Query the saved downloads url option
  $title = $options["title"];
  $downloads_folder = $options["downloads_folder"];
  $downloads_page_based = $options["downloads_page_based"];
  $downloads_box_description = $options["downloads_folder_description"];
  $downloads_box_nocredit = $options["downloads_folder_nocredit"];

  echo $before_widget ;
  echo $before_title . $title . $after_title;
  echo list_downloads($downloads_folder, $downloads_page_based, $title, $downloads_box_nocredit, $downloads_box_description);
  echo $after_widget;
}

function widget_downloadsbox_init() {
  wp_register_sidebar_widget(DOWNLOADBOX_WIDGET_ID, 
  	__('Downloads List'), 'widget_downloadsbox');
  wp_register_widget_control(DOWNLOADBOX_WIDGET_ID, __('Downloads List'), 'widget_downloadsbox_control');
}

  function widget_downloadsbox_control() {
  $options = get_option(DOWNLOADBOX_WIDGET_ID);
  if (!is_array($options)) {
    $options = array();
  }

  $widget_data = $_POST[DOWNLOADBOX_WIDGET_ID];
  if ($widget_data['submit']) {
    $options['title'] = $widget_data['title'];
    $options['downloads_folder'] = $widget_data['downloads_folder'];
    $options['downloads_page_based'] = $widget_data['downloads_page_based'];
    $options['downloads_folder_description'] = $widget_data['downloads_folder_description'];
    $options['downloads_folder_nocredit'] = $widget_data['downloads_folder_nocredit'];

    update_option(DOWNLOADBOX_WIDGET_ID, $options);
  }

  // Render form
  $title = $options['title'];
  $downloads_folder = $options['downloads_folder'];
  $downloads_page_based = $options['downloads_page_based'];
  $downloads_box_description = $options['downloads_folder_description'];
  $downloads_box_nocredit = $options['downloads_folder_nocredit'];
  
?>
  <p>
    <label for="<?php echo DOWNLOADBOX_WIDGET_ID;?>-title">
      Enter Downloads box title
    </label>
    <input class="widefat" type="text" 
      name="<?php echo DOWNLOADBOX_WIDGET_ID; ?>[title]" 
      id="<?php echo DOWNLOADBOX_WIDGET_ID;?>-title" 
      value="<?php echo $title; ?>">
  </p>
  <p>
    <label for="<?php echo DOWNLOADBOX_WIDGET_ID;?>-downloads-folder">
      List files in folder:
    </label>
    <input class="widefat" type="text" 
      name="<?php echo DOWNLOADBOX_WIDGET_ID; ?>[downloads_folder]" 
      id="<?php echo DOWNLOADBOX_WIDGET_ID;?>-downloads-folder" 
      value="<?php echo $downloads_folder; ?>">
      <small>(relative to your Wordpress installation)</small>
  </p>
  <p>
        <label for="<?php echo DOWNLOADBOX_WIDGET_ID;?>-downloads-page-based">
           Page based:
       </label>
        <input name="<?php echo DOWNLOADBOX_WIDGET_ID; ?>[downloads_page_based]" id="<?php echo DOWNLOADBOX_WIDGET_ID;?>-downloads-page-based" type="checkbox" value="true" <?php if ($downloads_page_based == 'true' ) echo ' checked="checked" '; ?> />
        <small>(shows downloads for the current page only)</small>
  </p>
  <p>
    <label for="<?php echo DOWNLOADBOX_WIDGET_ID;?>-downloads-folder-description">
      Enter downloads description <small>(optional)</small>
    </label>
    <textarea class="widefat" type="text" cols="30" rows="4" name="<?php echo DOWNLOADBOX_WIDGET_ID; ?>[downloads_folder_description]" 
      id="<?php echo DOWNLOADBOX_WIDGET_ID;?>-downloads-folder-description"><?php echo $downloads_box_description; ?></textarea>
  </p>

  <p>
    <label for="<?php echo DOWNLOADBOX_WIDGET_ID;?>-downloads-folder-nocredit">
    	Remove love link:
    </label>
    <input name="<?php echo DOWNLOADBOX_WIDGET_ID; ?>[downloads_folder_nocredit]" type="checkbox" value="true" <?php if ($downloads_box_nocredit == 'true' ) echo ' checked="checked" '; ?> />
    </p>
    <input type="hidden" name="<?php echo DOWNLOADBOX_WIDGET_ID; ?>[submit]" value="1">
	
<?php
}

// Register widget to WordPress
add_action("plugins_loaded", "widget_downloadsbox_init");

?>
