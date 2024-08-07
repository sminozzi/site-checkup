<?php

/**
 * Plugin Name: Site Checkup
 * Description: Make Site Checkup
 * Version: 1.02
 * Text Domain: site-checkup
 * Author: Bill Minozzi
 * WordPress username: sminozzi
 * Author URI: http://billminozzi.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
if (!defined('ABSPATH')) {
    exit;
}
$sitecheckup_plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
$sitecheckup_plugin_version = $sitecheckup_plugin_data['Version'];
define('SITECHECKUPPATH', plugin_dir_path(__FILE__));
define('SITECHECKUPURL', plugin_dir_url(__FILE__));
define('SITECHECKUPVERSION', $sitecheckup_plugin_version);
$site_checkup_is_admin = site_checkup_check_wordpress_logged_in_cookie();

// Add a link to the plugin description on the plugins page
add_filter('plugin_row_meta', 'site_checkup_plugin_row_meta', 10, 2);

function site_checkup_plugin_row_meta($links, $file)
{
    // Check if this is our plugin
    //
    //
    if (strpos($file, 'site-checkup.php') !== false) {
        $links[] = '<a href="' . esc_url(admin_url('tools.php?page=site-checkup')) . '">Go to Plugin Dashboard</a>';
    }
    return $links;
}

function site_checkup_check_wordpress_logged_in_cookie()
{
    foreach ($_COOKIE as $key => $value) {
        if (strpos($key, 'wordpress_logged_in_') === 0) {
            return true;
        }
    }
    return false;
}
//
//
function site_checkup_bill_hooking_diagnose()
{
    global $site_checkup_is_admin;
    if ($site_checkup_is_admin and current_user_can("manage_options")) {
        $declared_classes = get_declared_classes();
        foreach ($declared_classes as $class_name) {
            if (strpos($class_name, "Bill_Diagnose") !== false) {
                return;
            }
        }
        $plugin_slug = 'site-checkup';
        $plugin_text_domain = $plugin_slug;
        $notification_url = "https://wpmemory.com/fix-low-memory-limit/";
        $notification_url2 =
            "https://wptoolsplugin.com/site-language-error-can-crash-your-site/";
        require_once dirname(__FILE__) . "/includes/diagnose/class_bill_diagnose.php";
    }
}
add_action("init", "site_checkup_bill_hooking_diagnose", 10);
function site_checkup_bill_hooking_catch_errors()
{
    global $site_checkup_plugin_slug;
    $declared_classes = get_declared_classes();
    foreach ($declared_classes as $class_name) {
        if (strpos($class_name, "bill_catch_errors") !== false) {
            return;
        }
    }
    $site_checkup_plugin_slug = 'site_checkup';
    require_once dirname(__FILE__) . "/includes/catch-errors/class_bill_catch_errors.php";
}
add_action("init", "site_checkup_bill_hooking_catch_errors", 15);
require_once plugin_dir_path(__FILE__) . 'functions/functions.php';
require_once plugin_dir_path(__FILE__) . 'dashboard/dashboard.php';