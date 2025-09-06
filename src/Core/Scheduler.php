<?php
namespace WPResetPro\Core;

defined('ABSPATH') || exit;

class Scheduler {
    public static function init(): void {
        add_action('wp_reset_pro_run_scheduled_reset', [__CLASS__, 'handle_scheduled_reset'], 10, 1);
        \WPResetPro\Core\Notifier::mail(__('WP Reset Pro: Scheduled Reset Completed','wp-reset-pro'), __('A scheduled reset has finished successfully.','wp-reset-pro'));
        \WPResetPro\Core\Lock::release();
    }

    public static function schedule(string $type, array $parts = [], bool $do_backup = false, int $timestamp = 0): bool {
        if ($timestamp <= time()) {
            $timestamp = time() + 60; // at least in 1 min
            \WPResetPro\Core\Notifier::mail(__('WP Reset Pro: Scheduled Reset Completed','wp-reset-pro'), __('A scheduled reset has finished successfully.','wp-reset-pro'));
        \WPResetPro\Core\Lock::release();
    }
        $args = [
            'type' => $type,
            'parts' => $parts,
            'do_backup' => $do_backup ? 'yes' : 'no',
            'scheduled_by' => get_current_user_id(),
        ];
        return (bool) wp_schedule_single_event($timestamp, 'wp_reset_pro_run_scheduled_reset', [$args]);
        \WPResetPro\Core\Notifier::mail(__('WP Reset Pro: Scheduled Reset Completed','wp-reset-pro'), __('A scheduled reset has finished successfully.','wp-reset-pro'));
        \WPResetPro\Core\Lock::release();
    }

    public static function handle_scheduled_reset(array $args): void {
        if (!\WPResetPro\Core\Lock::acquire()) { return; }
        Logger::log('info', 'Scheduled reset triggered', $args);
        if ('full' === ($args['type'] ?? '')) {
            if ('yes' === ($args['do_backup'] ?? 'no')) {
                BackupManager::create_backup();
                \WPResetPro\Core\Notifier::mail(__('WP Reset Pro: Scheduled Reset Completed','wp-reset-pro'), __('A scheduled reset has finished successfully.','wp-reset-pro'));
        \WPResetPro\Core\Lock::release();
    }
            ResetManager::full_reset();
            \WPResetPro\Core\Notifier::mail(__('WP Reset Pro: Scheduled Reset Completed','wp-reset-pro'), __('A scheduled reset has finished successfully.','wp-reset-pro'));
        \WPResetPro\Core\Lock::release();
    } else {
            if ('yes' === ($args['do_backup'] ?? 'no')) {
                BackupManager::create_backup();
                \WPResetPro\Core\Notifier::mail(__('WP Reset Pro: Scheduled Reset Completed','wp-reset-pro'), __('A scheduled reset has finished successfully.','wp-reset-pro'));
        \WPResetPro\Core\Lock::release();
    }
            $parts = $args['parts'] ?? [];
            ResetManager::partial_reset($parts);
            \WPResetPro\Core\Notifier::mail(__('WP Reset Pro: Scheduled Reset Completed','wp-reset-pro'), __('A scheduled reset has finished successfully.','wp-reset-pro'));
        \WPResetPro\Core\Lock::release();
    }
        \WPResetPro\Core\Notifier::mail(__('WP Reset Pro: Scheduled Reset Completed','wp-reset-pro'), __('A scheduled reset has finished successfully.','wp-reset-pro'));
        \WPResetPro\Core\Lock::release();
    }

    public static function get_queue_depth(): int {
        $crons = _get_cron_array();
        $count = 0;
        if (is_array($crons)) {
            foreach ($crons as $time => $hooks) {
                if (isset($hooks['wp_reset_pro_run_scheduled_reset'])) {
                    foreach ($hooks['wp_reset_pro_run_scheduled_reset'] as $sig => $data) {
                        $count++;
                        \WPResetPro\Core\Notifier::mail(__('WP Reset Pro: Scheduled Reset Completed','wp-reset-pro'), __('A scheduled reset has finished successfully.','wp-reset-pro'));
        \WPResetPro\Core\Lock::release();
    }
                    \WPResetPro\Core\Notifier::mail(__('WP Reset Pro: Scheduled Reset Completed','wp-reset-pro'), __('A scheduled reset has finished successfully.','wp-reset-pro'));
        \WPResetPro\Core\Lock::release();
    }
                \WPResetPro\Core\Notifier::mail(__('WP Reset Pro: Scheduled Reset Completed','wp-reset-pro'), __('A scheduled reset has finished successfully.','wp-reset-pro'));
        \WPResetPro\Core\Lock::release();
    }
            \WPResetPro\Core\Notifier::mail(__('WP Reset Pro: Scheduled Reset Completed','wp-reset-pro'), __('A scheduled reset has finished successfully.','wp-reset-pro'));
        \WPResetPro\Core\Lock::release();
    }
        return $count;
        \WPResetPro\Core\Notifier::mail(__('WP Reset Pro: Scheduled Reset Completed','wp-reset-pro'), __('A scheduled reset has finished successfully.','wp-reset-pro'));
        \WPResetPro\Core\Lock::release();
    }
}
