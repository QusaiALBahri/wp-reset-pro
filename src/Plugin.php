<?php
namespace WPResetPro;

defined('ABSPATH') || exit;

final class Plugin {
    public const VERSION = '1.0.0';
    private static bool $booted = false;

    public static function init(): void {
        if (self::$booted) return;
        self::$booted = true;

        Admin\Admin::init();
        Admin\Ajax::init();
        Admin\RestRoutes::init();
        Admin\CLI::init();
        Core\Security::init();
        Core\Scheduler::init();
        GDPR\Exporter::init();
        GDPR\Eraser::init();
        Multisite\Permissions::init();
    }

    public static function activate(): void {
        // Ensure options exist.
        if (is_multisite()) {
            add_site_option('wp_reset_pro_options', [
                'delete_on_uninstall' => 'no',
            ]);
        } else {
            add_option('wp_reset_pro_options', [
                'delete_on_uninstall' => 'no',
            ]);
        }
    }

    public static function deactivate(): void {
        // Nothing for now. Schedules managed in Scheduler.
    }
}
