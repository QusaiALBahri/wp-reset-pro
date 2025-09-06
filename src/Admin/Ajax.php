<?php
namespace WPResetPro\Admin;

use WPResetPro\Core\DryRunSimulator;
use WPResetPro\Multisite\Permissions;

defined('ABSPATH') || exit;

class Ajax {
    public static function init(): void {
        add_action('wp_ajax_wp_reset_pro_counts', [__CLASS__, 'counts']);
    }
    public static function counts(): void {
        Permissions::assert_can_reset();
        check_ajax_referer('wp_reset_pro_counts', 'nonce');
        $parts = isset($_POST['parts']) ? array_map('sanitize_text_field', (array) wp_unslash($_POST['parts'])) : [];
        $data = DryRunSimulator::counts($parts);
        wp_send_json_success($data);
    }
}
