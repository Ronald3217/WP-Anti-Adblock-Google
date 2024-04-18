<?php
/*
 * Plugin Name: WP Anti AdBlock Google - Google Adsense AdBlock Integration
 * Plugin URI: mailto:ronald3217@gmail.com
 * Description: Add Google Adsense AdBlock Integration
 * Version:           0.1.0
 * Requires at least: 6.2.2
 * Requires PHP:      7.4
 * Author: Ronald Villagrán
 * Author URI: mailto:ronald3217@gmail.com
 * Text Domain: wp_anti_adblock_google_ronaldvillagran
 * License: GPL v2 or later
 * Licence URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (file_exists(dirname(__FILE__) . '/cmb2/init.php')) {
    require_once dirname(__FILE__) . '/cmb2/init.php';
} elseif (file_exists(dirname(__FILE__) . '/CMB2/init.php')) {
    require_once dirname(__FILE__) . '/CMB2/init.php';
}

require_once dirname(__FILE__) . '/inc/options-page.php';

function wpaag_enqueue_scripts()
{
    $options = get_option('wpaag_plugin_options');
    $enabled = $options['wpaag_enabled'];
    $error_enabled = $options['wpaag_error_enabled'];
    if ($enabled) {
        $scriptId = $options['wpaag_adsense_id'];
        $scriptNonce = $options['wpaag_nonce_key'];
        $message_code = $options['wpaag_adsense_code'];
        $error_code = $options['wpaag_error_code'];
        $myPluginParams = array(
            'wpaag_nonce_key' => $scriptNonce,
            'wpaag_adsense_code' => $message_code,
        );
        wp_enqueue_script('wpaag_script', "https://fundingchoicesmessages.google.com/i/${scriptId}?ers=1", array(), null, true);
        wp_add_inline_script('wpaag_script', $message_code, 'after');
        wp_enqueue_script('wpaag_plugin', plugins_url('/inc/js/plugin.js', __FILE__), array(), null, true);
        wp_add_inline_script('wpaag_plugin', 'var myPluginParams = ' . wp_json_encode($myPluginParams), 'before');
        if($error_enabled){
            wp_add_inline_script('wpaag_plugin', $error_code, 'after');
        }
    }
}

add_action('wp_enqueue_scripts', 'wpaag_enqueue_scripts');
?>