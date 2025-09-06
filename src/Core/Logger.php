<?php
namespace WPResetPro\Core;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

defined('ABSPATH') || exit;

class Logger {
    public static function log(string $level, string $message, array $context = []): void {
        $entry = [
            'time' => time(),
            'user' => get_current_user_id(),
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ];
        $history = get_site_option('wp_reset_pro_history', []);
        $history[] = $entry;
        update_site_option('wp_reset_pro_history', $history);
    }

    public static function get_history(): array {
        $history = get_site_option('wp_reset_pro_history', []);
        return is_array($history) ? $history : [];
    }
}
