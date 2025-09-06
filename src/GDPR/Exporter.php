<?php
namespace WPResetPro\GDPR;

use WPResetPro\Core\Logger;

defined('ABSPATH') || exit;

class Exporter {
    public static function init(): void {
        add_filter('wp_privacy_personal_data_exporters', [__CLASS__, 'register']);
    }
    public static function register($exporters) {
        $exporters['wp-reset-pro'] = [
            'exporter_friendly_name' => __('WP Reset Pro Logs', 'wp-reset-pro'),
            'callback' => [__CLASS__, 'exporter_cb'],
        ];
        return $exporters;
    }
    public static function exporter_cb(string $email, int $page = 1) {
        $items = [];
        $user = get_user_by('email', $email);
        if ($user) {
            $history = Logger::get_history();
            foreach ($history as $entry) {
                if ((int)($entry['user'] ?? 0) === (int)$user->ID) {
                    $items[] = [
                        'group_id' => 'wp-reset-pro',
                        'group_label' => __('WP Reset Pro Activity', 'wp-reset-pro'),
                        'item_id' => 'log-' . ($entry['time'] ?? time()),
                        'data' => [
                            ['name' => __('Timestamp', 'wp-reset-pro'), 'value' => gmdate('c', $entry['time'] ?? time())],
                            ['name' => __('Level', 'wp-reset-pro'), 'value' => $entry['level'] ?? 'info'],
                            ['name' => __('Message', 'wp-reset-pro'), 'value' => $entry['message'] ?? ''],
                        ],
                    ];
                }
            }
        }
        return ['data' => $items, 'done' => true];
    }
}
