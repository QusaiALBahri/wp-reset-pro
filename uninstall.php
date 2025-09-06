<?php
/**
 * Uninstall for WP Reset Pro
 */
defined('WP_UNINSTALL') || exit;

$opt = get_site_option('wp_reset_pro_delete_data_on_uninstall', 'no');
if ('yes' !== $opt) {
    return;
}

delete_site_option('wp_reset_pro_options');
delete_site_option('wp_reset_pro_history');
delete_site_option('wp_reset_pro_schedule');

// Clear cron events.
if (function_exists('wp_clear_scheduled_hook')) {
    wp_clear_scheduled_hook('wp_reset_pro_run_scheduled_reset');
}
