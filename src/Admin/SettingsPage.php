<?php
namespace WPResetPro\Admin;

defined('ABSPATH') || exit;

class SettingsPage {
    public static function init_settings(): void {
        register_setting('wp_reset_pro', 'wp_reset_pro_options', [
            'type' => 'array',
            'sanitize_callback' => [__CLASS__, 'sanitize'],
            'default' => ['delete_on_uninstall' => 'no'],
        ]);

        add_settings_section('wp_reset_pro_main', __('Main Settings', 'wp-reset-pro'), '__return_false', 'wp_reset_pro');

        add_settings_field('delete_on_uninstall', __('Delete data on uninstall', 'wp-reset-pro'), [__CLASS__, 'field_delete'], 'wp_reset_pro', 'wp_reset_pro_main');
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) wp_die(esc_html__('No access', 'wp-reset-pro'));
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('WP Reset Pro Settings', 'wp-reset-pro'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('wp_reset_pro');
                do_settings_sections('wp_reset_pro');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public static function sanitize($opts): array {
        $out = [];
        $out['delete_on_uninstall'] = (isset($opts['delete_on_uninstall']) && 'yes' === $opts['delete_on_uninstall']) ? 'yes' : 'no';
        if (is_multisite()) {
            update_site_option('wp_reset_pro_delete_data_on_uninstall', $out['delete_on_uninstall']);
        }
        return $out;
    }

    public static function field_delete(): void {
        $opts = get_option('wp_reset_pro_options', ['delete_on_uninstall' => 'no']);
        ?>
        <label>
            <input type="checkbox" name="wp_reset_pro_options[delete_on_uninstall]" value="yes" <?php checked('yes', $opts['delete_on_uninstall'] ?? 'no'); ?> />
            <?php echo esc_html__('Remove plugin data when uninstalling', 'wp-reset-pro'); ?>
        </label>
        <?php
    }
}
