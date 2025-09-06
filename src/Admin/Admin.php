<?php
namespace WPResetPro\Admin;

use WPResetPro\Core\Security;
use WPResetPro\Core\BackupManager;
use WPResetPro\Core\ResetManager;
use WPResetPro\Core\Scheduler;
use WPResetPro\Multisite\Permissions;

defined('ABSPATH') || exit;

class Admin {
    public static function init(): void {
        add_action('load-toplevel_page_wp-reset-pro', [__CLASS__, 'enqueue']);
        add_action('load-wp-reset-pro_page_wp-reset-pro-settings', [__CLASS__, 'enqueue']);
        add_action('load-wp-reset-pro_page_wp-reset-pro-status', [__CLASS__, 'enqueue']);
        add_action('admin_menu', [__CLASS__, 'menu']);
        add_action('admin_post_wp_reset_pro_run', [__CLASS__, 'handle_run']);
        add_action('admin_post_wp_reset_pro_schedule', [__CLASS__, 'handle_schedule']);
        add_action('admin_init', [SettingsPage::class, 'init_settings']);
        add_action('admin_init', [StatusPage::class, 'register']);
    }

    public static function enqueue(): void {
        // Enqueue is now handled inside render() for simplicity.
    }

    public static function menu(): void {
        $cap = Permissions::manage_cap();
        add_menu_page(
            __('WP Reset Pro', 'wp-reset-pro'),
            __('WP Reset Pro', 'wp-reset-pro'),
            $cap,
            'wp-reset-pro',
            [__CLASS__, 'render'],
            'dashicons-image-rotate',
            80
        );
        add_submenu_page('wp-reset-pro', __('Settings', 'wp-reset-pro'), __('Settings', 'wp-reset-pro'), $cap, 'wp-reset-pro-settings', [SettingsPage::class, 'render']);
        add_submenu_page('wp-reset-pro', __('Status/Health', 'wp-reset-pro'), __('Status/Health', 'wp-reset-pro'), $cap, 'wp-reset-pro-status', [StatusPage::class, 'render']);
    }

    public static function render(): void {
        wp_enqueue_style('wp-reset-pro-admin', WP_RESET_PRO_URL . 'assets/css/admin.css', [], \WPResetPro\Plugin::VERSION);
        wp_enqueue_script('wp-reset-pro-admin', WP_RESET_PRO_URL . 'assets/js/admin.js', ['jquery'], \WPResetPro\Plugin::VERSION, true);
        wp_localize_script('wp-reset-pro-admin', 'WPResetProCounts', ['nonce' => wp_create_nonce('wp_reset_pro_counts')]);
        $cap = Permissions::manage_cap();
        if (!current_user_can($cap)) {
            wp_die(esc_html__('Insufficient permissions.', 'wp-reset-pro'));
        }
        ?>
        <div class="wrap wp-reset-pro-wrap">
            <h1><?php echo esc_html__('WP Reset Pro', 'wp-reset-pro'); ?></h1>
            <p class="description"><?php echo esc_html__('Perform full or partial resets with safety checks and optional backups.', 'wp-reset-pro'); ?></p>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('wp_reset_pro_run', 'wp_reset_pro_nonce'); ?>
                <input type="hidden" name="action" value="wp_reset_pro_run">
                <h2><?php echo esc_html__('Reset Options', 'wp-reset-pro'); ?></h2>
                <div id="wp-reset-pro-counts" class="wp-reset-pro-cards"><div class="card"><h3>Preview</h3><ul><li><strong>Posts</strong>: <span data-count="posts">–</span></li><li><strong>Media</strong>: <span data-count="media">–</span></li><li><strong>Terms</strong>: <span data-count="terms">–</span></li><li><strong>Comments</strong>: <span data-count="comments">–</span></li><li><strong>Users</strong>: <span data-count="users">–</span></li><li><strong>Options</strong>: <span data-count="options">–</span></li></ul></div></div>
                <p><label><input type="checkbox" name="parts[]" value="content"> <?php echo esc_html__('Content (posts, media, terms, comments)', 'wp-reset-pro'); ?></label></p>
                <p><label><input type="checkbox" name="parts[]" value="settings"> <?php echo esc_html__('Settings (options, transients)', 'wp-reset-pro'); ?></label></p>
                <p><label><input type="checkbox" name="parts[]" value="users"> <?php echo esc_html__('Users (except current and admin)', 'wp-reset-pro'); ?></label></p>
                <p><label><input type="checkbox" name="wp_reset_pro_do_backup" value="yes"> <?php echo esc_html__('Create backup before reset', 'wp-reset-pro'); ?></label></p>

                <h3><?php echo esc_html__('Type CONFIRM to proceed', 'wp-reset-pro'); ?></h3>
                <input type="text" name="confirm_phrase" required pattern="CONFIRM" placeholder="CONFIRM">
                <p><button id="wp-reset-pro-run" class="button button-primary" type="button"><?php echo esc_html__('Run Reset', 'wp-reset-pro'); ?></button></p>
            <div id="wp-reset-pro-modal" class="wp-reset-pro-modal" hidden>
              <div class="modal-content">
                <h2><?php echo esc_html__('Final Confirmation', 'wp-reset-pro'); ?></h2>
                <p><?php echo esc_html__('This operation is destructive. Please confirm again.', 'wp-reset-pro'); ?></p>
                <p><button type="submit" class="button button-primary"><?php echo esc_html__('Yes, proceed', 'wp-reset-pro'); ?></button>
                   <button type="button" class="button wp-reset-pro-cancel"><?php echo esc_html__('Cancel', 'wp-reset-pro'); ?></button></p>
              </div>
            </div>
            </form>

            <hr/>

            <h2><?php echo esc_html__('Schedule a Reset', 'wp-reset-pro'); ?></h2>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('wp_reset_pro_schedule', 'wp_reset_pro_schedule_nonce'); ?>
                <input type="hidden" name="action" value="wp_reset_pro_schedule">
                <p>
                    <label><?php echo esc_html__('Reset Type', 'wp-reset-pro'); ?>
                        <select name="type">
                            <option value="partial"><?php echo esc_html__('Partial', 'wp-reset-pro'); ?></option>
                            <option value="full"><?php echo esc_html__('Full', 'wp-reset-pro'); ?></option>
                        </select>
                    </label>
                </p>
                <p><label><input type="checkbox" name="parts[]" value="content"> <?php echo esc_html__('Content', 'wp-reset-pro'); ?></label></p>
                <p><label><input type="checkbox" name="parts[]" value="settings"> <?php echo esc_html__('Settings', 'wp-reset-pro'); ?></label></p>
                <p><label><input type="checkbox" name="parts[]" value="users"> <?php echo esc_html__('Users', 'wp-reset-pro'); ?></label></p>
                <p><label><input type="checkbox" name="do_backup" value="yes"> <?php echo esc_html__('Create backup before reset', 'wp-reset-pro'); ?></label></p>
                <p>
                    <label><?php echo esc_html__('Run at (UTC timestamp)', 'wp-reset-pro'); ?>
                        <input type="number" name="timestamp" min="<?php echo esc_attr(time()); ?>" required/>
                    </label>
                </p>
                <p><button class="button" type="submit"><?php echo esc_html__('Schedule Reset', 'wp-reset-pro'); ?></button></p>
            </form>
        </div>
        <?php
    }

    public static function handle_run(): void {
        Permissions::assert_can_reset();
        \WPResetPro\Core\Security::verify_admin_action('wp_reset_pro_run', 'wp_reset_pro_nonce', Permissions::manage_cap());

        // Confirm phrase
        $phrase = isset($_POST['confirm_phrase']) ? sanitize_text_field(wp_unslash($_POST['confirm_phrase'])) : '';
        if ('CONFIRM' !== $phrase) {
            wp_die(esc_html__('Confirmation phrase mismatch.', 'wp-reset-pro'));
        }

        $parts = isset($_POST['parts']) ? array_map('sanitize_text_field', (array) wp_unslash($_POST['parts'])) : [];
        $is_full = count($parts) === 3; // crude heuristic

        if (isset($_POST['wp_reset_pro_do_backup']) && 'yes' === sanitize_text_field(wp_unslash($_POST['wp_reset_pro_do_backup']))) {
            BackupManager::create_backup();
        }

        if (!\WPResetPro\Core\Lock::acquire()) { wp_die(esc_html__('Another reset is running. Try again later.', 'wp-reset-pro')); }
        if ($is_full) {
            ResetManager::full_reset();
        } else {
            ResetManager::partial_reset($parts);
        }

        \WPResetPro\Core\Lock::release();
        \WPResetPro\Core\Notifier::mail(__('WP Reset Pro: Reset Completed','wp-reset-pro'), __('A reset operation has finished successfully.','wp-reset-pro'));
        wp_safe_redirect(admin_url('admin.php?page=wp-reset-pro&done=1'));
        exit;
    }

    public static function handle_schedule(): void {
        Permissions::assert_can_reset();
        \WPResetPro\Core\Security::verify_admin_action('wp_reset_pro_schedule', 'wp_reset_pro_schedule_nonce', Permissions::manage_cap());

        $type = isset($_POST['type']) ? sanitize_text_field(wp_unslash($_POST['type'])) : 'partial';
        $parts = isset($_POST['parts']) ? array_map('sanitize_text_field', (array) wp_unslash($_POST['parts'])) : [];
        $do_backup = isset($_POST['do_backup']) && 'yes' === sanitize_text_field(wp_unslash($_POST['do_backup']));
        $timestamp = isset($_POST['timestamp']) ? (int) $_POST['timestamp'] : (time() + 300);

        Scheduler::schedule($type, $parts, $do_backup, $timestamp);

        wp_safe_redirect(admin_url('admin.php?page=wp-reset-pro-status&scheduled=1'));
        exit;
    }
}
