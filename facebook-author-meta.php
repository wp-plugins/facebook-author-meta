<?php
/**
 * Plugin Name: Facebook Author Meta
 * Plugin URI: https://wordpress.org/plugins/facebook-author-meta/
 * Description: A simple and clean plugin that allows users to add facebook author meta (i.e. Author By info in facebook news feeds) to their websites/blogs.
 * Author: Nazakat ALi
 * Author URI: https://profiles.wordpress.org/nazakatali32
 * Version: 1.1
 * License: GPL2
 */
 /*  Copyright 2014  Nazakat ALi  (email : nazakatali32@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/
defined('ABSPATH') or die("No script kiddies please...!");

/* register menu item */
function facebook_author_meta_admin_menu_setup(){
add_submenu_page(
 'options-general.php', //parent_slug
 'Facebook Author Meta:Settings',//page_title
 'FB Author Meta',//menu_title
 'manage_options',//capability
 'fb-author-meta',//slug
 'facebook_author_meta'//function to use
 );
}
add_action('admin_menu', 'facebook_author_meta_admin_menu_setup'); //menu setup

/* display page content */
function facebook_author_meta() {
 global $submenu;
// access page settings 
 $page_data = array();
 foreach($submenu['options-general.php'] as $i => $menu_item) {
 if($submenu['options-general.php'][$i][2] == 'fb-author-meta')
 $page_data = $submenu['options-general.php'][$i];
 }

// output 
?>
<div class="wrap">
<?php screen_icon();?>
<h2><?php echo $page_data[3];?></h2>
<form id="facebook_author_meta_options" action="options.php" method="post">
<?php
settings_fields('facebook_author_meta_options');
do_settings_sections('fb-author-meta'); 
submit_button('Save options', 'primary', 'facebook_author_meta_options_submit');
?>
 </form>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="36%" valign="top"><h4>Feel free to ask questions on <a href="https://wordpress.org/support/plugin/facebook-author-meta" target="_new">support page</a></h4></td>
    <td valign="top"><h3>Support Me!</h3>
	<h4>Please make a <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=TNFBA9JHH6854&lc=US&item_name=Nazakat%20Ali&currency_code=USD&bn=PP%2dDonationsBF%3alogo11w%2epng%3aNonHosted" target="_new">donation</a> and/or give it a <a href="https://wordpress.org/support/view/plugin-reviews/facebook-author-meta" target="_new">5-star rating.</a><br/>

Your generous support encourages me to continue developing free plugins and other wordpress resources for free. Thanks! </h4></td>
 <tr><td><h3>Live Demo:</h3><div id="fb-root"></div><script>(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";  fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script><div class="fb-post" data-href="https://www.facebook.com/permalink.php?story_fbid=952865031424875&amp;id=811177385593641" data-width="500"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/permalink.php?story_fbid=952865031424875&amp;id=811177385593641"><p>#McLaren #P1 #Hybrid #Sportscar #HD</p>Posted by <a href="https://www.facebook.com/pages/Pakfunwebcom/811177385593641">Pakfunweb.com</a> on <a href="https://www.facebook.com/permalink.php?story_fbid=952865031424875&amp;id=811177385593641">Sunday, June 7, 2015</a></blockquote></div></div></td><td></td></tr>
 </tr>
</table>
</div>
<?php
}
/* settings link in plugin management screen */
function facebook_author_meta_link($actions, $file) {
if(false !== strpos($file, 'fb-author-meta'))
 $actions['settings'] = '<a href="options-general.php?page=fb-author-meta">Settings</a>';
return $actions; 
}
add_filter('plugin_action_links', 'facebook_author_meta_link', 2, 2);

/* register settings */
function facebook_author_meta_init(){

register_setting(
 'facebook_author_meta_options',
 'facebook_author_meta_options',
 'facebook_author_meta_options_validate'
 );

add_settings_section(
 'facebook_author_meta_fields_group',
 'How To Use<hr/>', 
 'facebook_author_meta_desc',
 'fb-author-meta'
 );

add_settings_field(
 'facebook_author_meta_single',
 '1). For Single Post/Pages etc', 
 'facebook_author_meta_field_single',
 'fb-author-meta',
 'facebook_author_meta_fields_group'
 );
 add_settings_field(
 'facebook_author_meta_singuler',
 '2). For All Other Pages', 
 'facebook_author_meta_field_singular',
 'fb-author-meta',
 'facebook_author_meta_fields_group'
 ); add_settings_field(
    'fbm_check_box',
    '<h4 style="width:100%;display:block;">Author\'s facebook profile link</h4>',
    'fbm_check_box_callback',
    'fb-author-meta',
    'facebook_author_meta_fields_group'
 ); add_settings_field(
 'facebook_author_meta_single_linked',
 '1). For Single Post/Pages etc', 
 'facebook_author_meta_field_single_linked',
 'fb-author-meta',
 'facebook_author_meta_fields_group'
 );
 add_settings_field(
 'facebook_author_meta_singuler_linked',
 '2). For All Other Pages', 
 'facebook_author_meta_field_singular_linked',
 'fb-author-meta',
 'facebook_author_meta_fields_group'
 );

}

add_action('admin_init', 'facebook_author_meta_init');

/* validate input */
function facebook_author_meta_options_validate($input){
 global $allowedposttags, $allowedrichhtml;
if(isset($input['authorbox_template']))
 $input['authorbox_template'] = wp_kses_post($input['authorbox_template']);
return $input;
}

/* description text */
function facebook_author_meta_desc(){
echo "<p>Please Read Carefully<br/><b>Note:</b> <br/>The first option set the author name for all posts, pages, etc (i.e. single pages). <br/>The second option sets the author name for all other pages such as archives, hompage, tags, category etc.<br/>If you want to set the same author name to the whole site, then fill both the fields with same name.</p>

<h4>For complete documentation visit plugin site. <a target=\"_new\" href=\"https://wordpress.org/plugins/facebook-author-meta/\">click here</a> or you can contact on <a href=\"https://wordpress.org/support/plugin/facebook-author-meta\" target=\"_new\">support page</a>, Thanks</h4>

";
}

/* filed output */
function facebook_author_meta_field_single() {
 $options = get_option('facebook_author_meta_options');
 $fbmsingle = (isset($options['fbm_single'])) ? $options['fbm_single'] : '';
 $fbmsingle = esc_textarea($fbmsingle); //sanitise output
?>
 <input id="fbm_single" name="facebook_author_meta_options[fbm_single]" class="large-text code"
 value="<?php echo $fbmsingle;?>"/>Default value: Your Public Name i.e name you set in your <a href="<?php echo get_site_url();?>/wp-admin/profile.php" targe="_new">profile</a> => [Display name publicly as].

<?php
}


/* filed output */
function facebook_author_meta_field_singular() {
 $options = get_option('facebook_author_meta_options');
 $fbmsingular = (isset($options['fbm_singular'])) ? $options['fbm_singular'] : '';
 $fbmsingular = esc_textarea($fbmsingular); //sanitise output
?>
 <input id="fbm_singular" name="facebook_author_meta_options[fbm_singular]" class="large-text code"
 value="<?php echo $fbmsingular;?>"/> Default value: none

 
 
 
<?php
}

//Check Box
function  fbm_check_box_callback(){
	$options = get_option('facebook_author_meta_options');
	#$fbm_check_box = (isset($options['fbm_check_box'])) ? $options['fbm_check_box'] : '';
     ?>
   <input type="checkbox" id="fbm_check_box" name="facebook_author_meta_options[fbm_check_box]" 
   value="1" <?php echo checked( 1, $options['fbm_check_box'], false ) ?>/>
   <label for="fbm_check_box">Check this box to enable author's facebook profile link. See Live Demo Below</label><br/>
   If you want to enable facebook author profile link, enter the full url of author's facebook profile e.g.
   <i>https://www.facebook.com/username</i>
     

 
<?php
}



/* filed output */
function facebook_author_meta_field_single_linked() {
 $options = get_option('facebook_author_meta_options');
 $fbmsingle_linked = (isset($options['fbm_single_linked'])) ? $options['fbm_single_linked'] : '';
 $fbmsingle_linked = esc_textarea($fbmsingle_linked); //sanitise output
?>
 <input id="fbm_single_linked" name="facebook_author_meta_options[fbm_single_linked]" class="large-text code"
 value="<?php echo $fbmsingle_linked;?>"/>Default value: none

<?php
}


/* filed output */
function facebook_author_meta_field_singular_linked() {
 $options = get_option('facebook_author_meta_options');
 $fbmsingular_linked = (isset($options['fbm_singular_linked'])) ? $options['fbm_singular_linked'] : '';
 $fbmsingular_linked = esc_textarea($fbmsingular_linked); //sanitise output
?>
 <input id="fbm_singular_linked" name="facebook_author_meta_options[fbm_singular_linked]" class="large-text code"
 value="<?php echo $fbmsingular_linked;?>"/> Default value: none

<?php
}

function fbm_values(){
		function check($input){
	if(!isset($input) or $input == ""){
		
		//return $author_name = the_author();
		//wp 3.1 fix
		$current_user = wp_get_current_user();
		echo $current_user = $current_user->display_name;
	}else {
		
		echo $author_name = $input;
	}}
	
	$fbm_options = get_option( 'facebook_author_meta_options');
	$single_author = trim($fbm_options['fbm_single']);
	$single_author_linked = trim($fbm_options['fbm_single_linked']);
	$singular_author = trim($fbm_options['fbm_singular']);
	$singular_author_linked = trim($fbm_options['fbm_singular_linked']);
	$fbm_check_box = trim($fbm_options['fbm_check_box']);
	

	
	if(is_singular())
	{	if(isset($fbm_check_box) and $fbm_check_box == 1)
		{
				echo "<meta property=\"article:author\" content=\"$single_author_linked\">";
		}
		else
		{
				echo "<meta name=\"author\" content=\"";check($single_author);echo "\"/>";
		}
	}
	else
	{ 
		if(isset($fbm_check_box) and $fbm_check_box == 1)
		{
				echo "<meta property=\"article:author\" content=\"$singular_author_linked\">";
		}
		else
		{
	
			if(isset($singular_author) && $singular_author !== "" )
			{
				echo "<meta name=\"author\" content=\"".$singular_author."\"/>";
			}
		
		}
	}

}
add_action('wp_head', 'fbm_values',3);

add_action('admin_notices', 'fbm_admin_notice');
function fbm_admin_notice() {
if ( current_user_can( 'install_plugins' ) )
   {
     global $current_user ;
        $user_id = $current_user->ID; 
		//Check that the user hasn't already clicked to ignore the message 
     if ( ! get_user_meta($user_id, 'fbm_ignore_notice') ) {
        echo '<div class="updated"><p>';
        printf(__('Go to <a href="options-general.php?page=fb-author-meta">settings</a> page to customize the <b>Facebook Author Meta</b> | <a href="%1$s">[Hide Notice]</a>'), '?fbm_nag_ignore=0');
        echo "</p></div>";
     }
    }
}

add_action('admin_init', 'fbm_nag_ignore');

function fbm_nag_ignore() {
     global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['fbm_nag_ignore']) && '0' == $_GET['fbm_nag_ignore'] ) {
             add_user_meta($user_id, 'fbm_ignore_notice', 'true', true);
     }
}

?>