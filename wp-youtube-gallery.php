<?php
/*
Plugin Name: WP Youtube Gallery
Plugin URI: http://www.mrwebsolution.in/
Description: "wp-youtube-gallery" is the very simple plugin for add to youtube gallery (In a easy way) on your site!.
Author: Raghunath
Author URI: http://raghunathgurjar.wordpress.com
Version: 1.1
*/

/*  Copyright 2014  Raghunath Gurjar  (email : raghunath.0087@gmail.com)

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

//Admin "WP Youtube Gallery" Menu Item
add_action('admin_menu','wpyg_sidebar_menu');

function wpyg_sidebar_menu(){

	add_options_page('WP Youtube Gallery','WP Youtube Gallery','manage_options','wpyg-settings','wpyg_sidebar_admin_option_page');

}

//Define Action for register "WP Youtube Gallery" Options
add_action('admin_init','wpyg_sidebar_init');


//Register "WP Youtube Gallery" options
function wpyg_sidebar_init(){
	register_setting('wpyg_sidebar_options','wpyg_active');
	register_setting('wpyg_sidebar_options','wpyg_number_posts');
	register_setting('wpyg_sidebar_options','wpyg_lightbox');
	register_setting('wpyg_sidebar_options','wpyg_iframe_w');
	register_setting('wpyg_sidebar_options','wpyg_iframe_h');
	register_setting('wpyg_sidebar_options','wpyg_per_row_posts');


} 


// Add settings link to plugin list page in admin
        function wpyg_add_settings_link( $links ) {
            $settings_link = '<a href="options-general.php?page=wpyg-settings">' . __( 'Settings', 'wpyg' ) . '</a>';
            array_unshift( $links, $settings_link );
            return $links;
        }

        $plugin = plugin_basename( __FILE__ );
        add_filter( "plugin_action_links_$plugin", 'wpyg_add_settings_link' );

/* 

*Display the Options form for WP Youtube Gallery 

*/

function wpyg_sidebar_admin_option_page(){ ?>

	<div style="width: 80%; padding: 10px; margin: 10px;"> 

	<h1>WP Youtube Gallery Settings</h1>
<!-- Start Options Form -->

	<form action="options.php" method="post" id="wpyg-sidebar-admin-form">
		
		<div id="wpyg-tab-menu"><a id="wpyg-general" class="wpyg-tab-links active" >General</a> <a  id="wpyg-support" class="wpyg-tab-links">Support</a> </div>

	<div class="wpyg-setting">
	<!-- General Setting -->	
	<div class="first wpyg-tab" id="div-wpyg-general">
	<h2>General Settings</h2>
	<p><label><?php _e('Enable:');?></label><input type="checkbox" id="wpyg_active" name="wpyg_active" value='1' <?php if(get_option('wpyg_active')!=''){ echo ' checked="checked"'; }?>/></p>
	
	<p><label><?php _e('Number of Videos :');?></label><input type="text" id="wpyg_number_posts" name="wpyg_number_posts" size="20" value="<?php echo get_option('wpyg_number_posts'); ?>" placeholder="10"></p>
	<p><label><?php _e('Video pre row:');?><label> <input type="text" id="wpyg_per_row_posts" name="wpyg_per_row_posts" size="20" value="<?php echo get_option('wpyg_per_row_posts'); ?>" placeholder="3"></p>	
	  
	   
	   <p><label><?php _e('Iframe Size:');?><label> <input type="text" id="wpyg_iframe_w" name="wpyg_iframe_w" size="10" value="<?php echo get_option('wpyg_iframe_w'); ?>" placeholder="Width">x<input type="text" id="wpyg_iframe_h" name="wpyg_iframe_h" size="10" value="<?php echo get_option('wpyg_iframe_h'); ?>" placeholder="Height"></p>
	   
	    <p><label><?php _e('Enable Lightbox:');?><label> <input type="checkbox" id="wpyg_lightbox" name="wpyg_lightbox" value='1' <?php if(get_option('wpyg_lightbox')!=''){ echo ' checked="checked"'; }?>/></p>  
	</div>
	
	<!-- Support -->
	<div class="last author wpyg-tab" id="div-wpyg-support">
	<h2>Plugin Support</h2>
	
	<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WN785E5V492L4" target="_blank" style="font-size: 17px; font-weight: bold;"><img src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" title="Donate for this plugin"></a></p>
	
	<p><strong>Plugin Author:</strong><br><img src="<?php echo  plugins_url( 'images/raghu.jpg' , __FILE__ );?>" width="75" height="75" class="author"><br><a href="http://raghunathgurjar.wordpress.com" target="_blank">Raghunath Gurjar</a></p>
	<p><a href="mailto:raghunath.0087@gmail.com" target="_blank" class="contact-author">Contact Author</a></p>
	<p><strong>My Other Plugins:</strong><br>
	<ul>
		<li><a href="https://wordpress.org/plugins/protect-wp-admin/" target="_blank">Protect WP-Admin</a></li>
		<li><a href="https://wordpress.org/plugins/wp-social-buttons/" target="_blank">WP Social Buttons</a></li>
		<li><a href="https://wordpress.org/plugins/custom-share-buttons-with-floating-sidebar" target="_blank">Custom Share Buttons with Floating Sidebar</a></li>
		<li><a href="https://wordpress.org/plugins/simple-testimonial-rutator/" target="_blank">Simple Testimonial Rutator</a></li>
		<li><a href="https://wordpress.org/plugins/wp-easy-recipe/" target="_blank">WP Easy Recipe</a></li>
		</ul></p>
	</div>

	</div>
    <span class="submit-btn"><?php echo get_submit_button('Save Settings','button-primary','submit','','');?></span>
    <?php settings_fields('wpyg_sidebar_options'); ?>
	
	</form>

<!-- End Options Form -->
	</div>

<?php
}

require dirname(__FILE__).'/wpyg-class.php';

/** add js into admin footer */
add_action('admin_footer','init_wpyg_admin_scripts');
function init_wpyg_admin_scripts()
{
wp_register_style( 'wpyg_admin_style', plugins_url( 'css/wpyg-admin.css',__FILE__ ) );
wp_enqueue_style( 'wpyg_admin_style' );

echo $script='<script type="text/javascript">
	/* WP Youtube Gallery js for admin */
	jQuery(document).ready(function(){
		jQuery(".wpyg-tab").hide();
		jQuery("#div-wpyg-general").show();
	    jQuery(".wpyg-tab-links").click(function(){
		var divid=jQuery(this).attr("id");
		jQuery(".wpyg-tab-links").removeClass("active");
		jQuery(".wpyg-tab").hide();
		jQuery("#"+divid).addClass("active");
		jQuery("#div-"+divid).fadeIn();
		})
		})
	</script>';

	}	
	
/* 

*Delete the options during disable the plugins 

*/

if( function_exists('register_uninstall_hook') )

	register_uninstall_hook(__FILE__,'wpyg_plugin_uninstall');   

//Delete all Custom Tweets options after delete the plugin from admin
function wpyg_plugin_uninstall(){
	delete_option('wpyg_active');
	delete_option('wpyg_number_posts');
	delete_option('wpyg_per_row_posts');
	delete_option('wpyg_lightbox');
	delete_option('wpyg_iframe_w');
	delete_option('wpyg_iframe_h');


} 
?>
