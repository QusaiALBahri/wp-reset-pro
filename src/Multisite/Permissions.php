<?php
namespace WPResetPro\Multisite;

defined('ABSPATH') || exit;

class Permissions {
    public static function init(): void {}

    public static function manage_cap(): string {
        return is_multisite() ? 'manage_network' : 'manage_options';
    }

    public static function assert_can_reset(): void {
        if (is_multisite()) {
            if (!is_super_admin()) {
                wp_die(esc_html__('Only network super admins can perform resets on multisite.', 'wp-reset-pro'));
            }
        } else {
            if (!current_user_can('manage_options')) {
                wp_die(esc_html__('Admins only.', 'wp-reset-pro'));
            }
        }
    }
}
