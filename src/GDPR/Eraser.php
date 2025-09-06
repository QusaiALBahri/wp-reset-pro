<?php
namespace WPResetPro\GDPR;

use WPResetPro\Core\Logger;

defined('ABSPATH') || exit;

class Eraser {
    public static function init(): void {
        add_filter('wp_privacy_personal_data_erasers', [__CLASS__, 'register']);
    }
    public static function register($erasers) {
        $erasers['wp-reset-pro'] = [
            'eraser_friendly_name' => __('WP Reset Pro Logs', 'wp-reset-pro'),
            'callback' => [__CLASS__, 'eraser_cb'],
        ];
        return $erasers;
    }
    public static function eraser_cb(string $email, int $page = 1) {
        $user = get_user_by('email', $email);
        $removed = false;
        if ($user) {
            $history = Logger::get_history();
            $filtered = array_filter($history, static function ($entry) use ($user) {
                return (int)($entry['user'] ?? 0) !== (int)$user->ID;
            });
            update_site_option('wp_reset_pro_history', array_values($filtered));
            $removed = true;
        }
        return ['items_removed' => $removed, 'items_retained' => false, 'messages' => [], 'done' => true];
    }
}
