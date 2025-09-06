<?php
namespace WPResetPro\Admin;

use WPResetPro\Core\Scheduler;
use WPResetPro\Multisite\Permissions;

defined('ABSPATH') || exit;

class RestRoutes {
    public static function init(): void {
        add_action('rest_api_init', [__CLASS__, 'register']);
    }
    public static function register(): void {
        register_rest_route('wp-reset-pro/v1', '/status', [
            'methods' => 'GET',
            'permission_callback' => [__CLASS__, 'perm'],
            'callback' => [__CLASS__, 'status'],
        ]);
        register_rest_route('wp-reset-pro/v1', '/schedule', [
            'methods' => 'POST',
            'permission_callback' => [__CLASS__, 'perm'],
            'callback' => [__CLASS__, 'schedule'],
            'args' => [
                'type' => ['required'=>true],
                'parts' => ['required'=>false],
                'do_backup' => ['required'=>false],
                'timestamp' => ['required'=>true],
            ],
        ]);
    }
    public static function perm(): bool {
        return current_user_can( is_multisite() ? 'manage_network' : 'manage_options' );
    }
    public static function status($req) {
        $next = wp_next_scheduled('wp_reset_pro_run_scheduled_reset');
        return rest_ensure_response(['next' => $next ?: null]);
    }
    public static function schedule($req) {
        Permissions::assert_can_reset();
        $type = sanitize_text_field($req->get_param('type'));
        $parts = (array) $req->get_param('parts');
        $do_backup = (bool) $req->get_param('do_backup');
        $ts = (int) $req->get_param('timestamp');
        $ok = Scheduler::schedule($type, array_map('sanitize_text_field', $parts), $do_backup, $ts);
        return rest_ensure_response(['scheduled' => (bool) $ok]);
    }
}
