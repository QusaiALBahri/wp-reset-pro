<?php
namespace WPResetPro\Admin;

use WPResetPro\Core\Scheduler;
use WPResetPro\Core\Logger;
use WPResetPro\Plugin;

defined('ABSPATH') || exit;

class StatusPage {
    public static function register(): void {}

    public static function render(): void {
        if (!current_user_can('manage_options')) wp_die(esc_html__('No access', 'wp-reset-pro'));
        $queue = Scheduler::get_queue_depth();
        $last_cron = wp_next_scheduled('wp_reset_pro_run_scheduled_reset');
        $history = Logger::get_history();
        $latency = self::ping_latency();
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('WP Reset Pro — Status/Health', 'wp-reset-pro'); ?></h1>
            <table class="widefat striped">
                <tbody>
                <tr><th><?php echo esc_html__('Plugin Version', 'wp-reset-pro'); ?></th><td><?php echo esc_html(Plugin::VERSION); ?></td></tr>
                <tr><th><?php echo esc_html__('Queue Depth', 'wp-reset-pro'); ?></th><td><?php echo esc_html($queue); ?></td></tr>
                <tr><th><?php echo esc_html__('Next Scheduled Reset', 'wp-reset-pro'); ?></th><td><?php echo esc_html($last_cron ? gmdate('c', $last_cron) : __('None', 'wp-reset-pro')); ?></td></tr>
                <tr><th><?php echo esc_html__('Loopback Latency (ms)', 'wp-reset-pro'); ?></th><td><?php echo esc_html($latency); ?></td></tr>
                </tbody>
            </table>

            <h2><?php echo esc_html__('Recent Activity', 'wp-reset-pro'); ?></h2>
            <ol>
                <?php foreach (array_reverse(array_slice($history, -20)) as $h): ?>
                    <li>
                        <code><?php echo esc_html(gmdate('Y-m-d H:i:s', $h['time'] ?? time())); ?></code>
                        — <strong><?php echo esc_html($h['level'] ?? 'info'); ?></strong> —
                        <?php echo esc_html($h['message'] ?? ''); ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
        <?php
    }

    private static function ping_latency(): int {
        $start = microtime(true);
        $res = wp_remote_get(home_url('/'), ['timeout' => 5]);
        $elapsed = (int) ((microtime(true) - $start) * 1000);
        return $elapsed;
    }
}
