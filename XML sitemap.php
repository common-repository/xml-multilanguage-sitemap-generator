<?php
/*
Plugin Name: XML Multilanguage Sitemap Generator
Plugin URI:  https://wordpress.org/plugins/xml-multilanguage-sitemap-generator
Description: Create a Google friendly multilanguage sitemap in the root of the website. Usable also with single language. Google will say HOORAY! | <a href="https://wordpress.org/plugins/xml-multilanguage-sitemap-generator/#faq" target="_blank">FAQ</a> | <a href="https://wordpress.org/support/plugin/xml-multilanguage-sitemap-generator" target="_blank">Support</a>
Version:     2.0.6
Author:      Marco Giannini
Author URI:  https://marcogiannini.net
Text Domain: xml-multilanguage-sitemap-generator
Requires PHP: 5.5
Domain Path: /languages
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/ 
$xmg_version = 1.5;
add_option('_xmg_version', $xmg_version);

define( '_XMG_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( '_XMG_PLUGIN_URL', 	plugin_dir_url( __FILE__ ) );

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if( !function_exists('is_plugin_active') ) {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

/*
** Add supports to multilanguage.
** Every POT is loaded inside /languages
*/
function _xmg_languages_xml() {
    $plugin_dir = basename(dirname(__FILE__)).'/languages';
    load_plugin_textdomain( 'xml-multilanguage-sitemap-generator', false, $plugin_dir );
}
add_action('plugins_loaded', '_xmg_languages_xml');

/**
 * Porting every options of old version of the plugin. Used for change all options name with a prefix to get rid of conflicts with other plugins.
 * @since 2.0
 * @param array / string of options ID.
 */
function _xmg_port_options($options_id){
	foreach ($options_id as $option) {
		$old_option = str_replace('_xmg_', '', $option);
		$old_option_value = get_option($old_option);
		$is_updated = add_option($option, $old_option_value);
		$new_option_value = get_option($option);

		if($is_updated){
			if($old_option_value === $new_option_value){
				delete_option($old_option);
			}
		}
	}
}

function _xmg_php_error_notices(){
	$class = 'notice notice-error';
	$message = __("I'm sorry but your PHP VERSION is too old! This plugin need at least <b>5.5</b> to work. Contact your server administrator to update it. Your current PHP VERSION is: <b>". PHP_VERSION ."</b>.",'xml-multilanguage-sitemap-generator');
	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );  
}

/**
 * Check what version of PHP is installed in the server. If current version is older than 5.5 please update or XML_MULTILANGUAGE must not work.
 * 
 * @since 2.0.3
 */
function _xmg_error(){
	$error = false;
	if(PHP_VERSION < '5.5'){
		$error = true;
	}
	return $error;
}

/**
 * Check if plugin is updated. Compare old version with current version. If current version is > than old version run _xmg_activation 
 *
 * @since 2.0.1
 */
function _xmg_run_on_update(){
	$current_version = get_plugin_data(__FILE__)['Version'];
	$old_version = get_option('_xmg_version');
	if($current_version > $old_version){
		_xmg_activation();
		update_option('_xmg_version', $current_version);
	}
}

/**
 * Activation function. 
 * @since 2.0
 */
function _xmg_activation(){
	global $xmg_options_list;
	_xmg_port_options($xmg_options_list);
}

function _xmg_avaiable(){
	//Run if is running an update
	_xmg_run_on_update();

	//Run if plugin is activated
	register_activation_hook( __FILE__, '_xmg_activation' );
	/**
	 * Required files for loop and for options.
	 * 
	 */
	require(_XMG_PLUGIN_PATH . '/includes/functions.php');
	require(_XMG_PLUGIN_PATH . '/includes/option.php');
	/**
	 * Start the loop
	 *
	 * @todo don't like how i've managed that. Think on a better solution
	 *
	 */
	add_action('save_post','_xmg_run');
	add_action('delete_post', '_xmg_run');
	add_action('admin_head-toplevel_page_xml_multilanguage_sitemap_generator','_xmg_run');
}

if(!_xmg_error()){
	_xmg_avaiable();
} else {
	unset( $_GET['activate'] );
	add_action('admin_notices', '_xmg_php_error_notices');
	deactivate_plugins( plugin_basename( __FILE__ ) );
}
