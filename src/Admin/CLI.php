<?php
namespace WPResetPro\Admin;

use WPResetPro\Core\BackupManager;
use WPResetPro\Core\ResetManager;
use WPResetPro\Core\Scheduler;
use WP_CLI;

defined('ABSPATH') || exit;

class CLI {
    public static function init(): void {
        if (defined('WP_CLI') && WP_CLI) {
            WP_CLI::add_command('reset-pro', [__CLASS__, 'handle']);
        }
    }
    public static function handle(array $args, array $assoc): void {
        $sub = $args[0] ?? '';
        if ('full' === $sub) {
            if (!empty($assoc['backup'])) BackupManager::create_backup();
            ResetManager::full_reset();
            \WP_CLI::success('Full reset completed');
        } elseif ('partial' === $sub) {
            $parts = [];
            foreach (['content','settings','users'] as $p) {
                if (!empty($assoc[$p])) $parts[] = $p;
            }
            if (!empty($assoc['backup'])) BackupManager::create_backup();
            ResetManager::partial_reset($parts);
            \WP_CLI::success('Partial reset completed: ' . implode(',', $parts));
        } elseif ('schedule' === $sub) {
            $type = $assoc['type'] ?? 'partial';
            $ts = (int) ($assoc['timestamp'] ?? (time()+300));
            $parts = array_filter(explode(',', $assoc['parts'] ?? ''));
            $ok = Scheduler::schedule($type, $parts, !empty($assoc['backup']), $ts);
            \WP_CLI::success($ok ? 'Scheduled' : 'Failed to schedule');
        } else {
            \WP_CLI::line('Usage: wp reset-pro <full|partial|schedule> [--content] [--settings] [--users] [--backup] [--timestamp=UTC] [--type=full|partial] [--parts=comma-list]');
        }
    }
}
