<?php
/**
 * Plugin Name:       WP Reset Pro
 * Description:       Safely perform full or partial resets of a WordPress site with backups, scheduling, and audit history.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      8.1
 * Author:            Qusai Omar AlBahri
 * Author URI:        https://www.albahri.org
 * Text Domain:       wp-reset-pro
 * Domain Path:       /languages
 */

defined('ABSPATH') || exit;

if (!defined('WP_RESET_PRO_FILE')) {
    define('WP_RESET_PRO_FILE', __FILE__);
}
if (!defined('WP_RESET_PRO_PATH')) {
    define('WP_RESET_PRO_PATH', plugin_dir_path(__FILE__));
}
if (!defined('WP_RESET_PRO_URL')) {
    define('WP_RESET_PRO_URL', plugin_dir_url(__FILE__));
}


// Load Composer autoloader if present; otherwise use a lightweight PSR-4 autoloader.
$vendorAutoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($vendorAutoload)) {
    require_once $vendorAutoload;
} else {
    spl_autoload_register(static function ($class) {
        if (str_starts_with($class, 'WPResetPro\\')) {
            $rel = str_replace('WPResetPro\\', '', $class);
            $rel = str_replace('\\', '/', $rel);
            $file = __DIR__ . '/src/' . $rel . '.php';
            if (file_exists($file)) {
                require_once $file;
            }
        }
    });
}


// Bootstrap plugin.
add_action('plugins_loaded', static function () {
    // Load text domain.
    load_plugin_textdomain('wp-reset-pro', false, dirname(plugin_basename(__FILE__)) . '/languages');

    // Init plugin.
    \WPResetPro\Plugin::init();
});

// Activation/Deactivation hooks
register_activation_hook(__FILE__, ['\WPResetPro\Plugin', 'activate']);
register_deactivation_hook(__FILE__, ['\WPResetPro\Plugin', 'deactivate']);
