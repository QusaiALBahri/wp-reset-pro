<?php
namespace WPResetPro\Core;

defined('ABSPATH') || exit;

class Security {
    public static function init(): void {}

    public static function verify_admin_action(string $action, string $nonce_field, string $cap): void {
        if (!isset($_POST[$nonce_field]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[$nonce_field])), $action)) {
            wp_die(esc_html__('Security check failed (nonce).', 'wp-reset-pro'));
        }
        if (!current_user_can($cap)) {
            wp_die(esc_html__('Insufficient permissions.', 'wp-reset-pro'));
        }
    }

    public static function sanitize_bool($value): string {
        return (isset($value) && ('1' === $value || 'yes' === $value || true === $value)) ? 'yes' : 'no';
    }
}
