<?php
namespace WPResetPro\Core;

defined('ABSPATH') || exit;

class ResetManager {

    public static function full_reset(): void {
        self::delete_content();
        self::delete_settings();
        self::delete_users();
        self::delete_uploads();
        Logger::log('warning', 'Full site reset executed');
    }

    public static function partial_reset(array $parts): void {
        if (in_array('content', $parts, true)) {
            self::delete_content();
        }
        if (in_array('settings', $parts, true)) {
            self::delete_settings();
        }
        if (in_array('users', $parts, true)) {
            self::delete_users();
        }
        Logger::log('warning', 'Partial reset executed', ['parts' => $parts]);
    }

    private static function delete_content(): void {
        // Delete posts (including attachments), terms, comments.
        $post_ids = get_posts(['post_type' => 'any', 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids']);
        foreach ($post_ids as $pid) {
            wp_delete_post($pid, true);
        }
        // Terms
        $taxonomies = get_taxonomies([], 'names');
        foreach ($taxonomies as $tax) {
            $terms = get_terms(['taxonomy' => $tax, 'hide_empty' => false]);
            if (is_wp_error($terms)) continue;
            foreach ($terms as $term) {
                wp_delete_term($term->term_id, $tax);
            }
        }
        // Comments
        $comments = get_comments(['number' => 0]);
        foreach ($comments as $c) {
            wp_delete_comment($c->comment_ID, true);
        }
    }

    private static function delete_settings(): void {
        global $wpdb;
        // Remove all options except core siteurl/home/admin_email and users_can_register/date/timezone settings.
        $protected = [
            'siteurl','home','admin_email','users_can_register','start_of_week','timezone_string',
        ];
        $like = $wpdb->esc_like($wpdb->prefix) . 'user_roles'; // keep roles
        $options = $wpdb->get_col("SELECT option_name FROM {$wpdb->options} WHERE option_name NOT IN ('" . implode("','", array_map('esc_sql', $protected)) . "') AND option_name NOT LIKE '%transient_%' AND option_name <> '{$like}'");
        foreach ($options as $opt) {
            delete_option($opt);
        }
        // Transients
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%' OR option_name LIKE '_site_transient_%'");
    }

    private static function delete_users(): void {
        // Delete all users except current and 'admin' superusers.
        $current = get_current_user_id();
        $users = get_users(['fields' => ['ID', 'user_login']]);
        foreach ($users as $user) {
            if ((int)$user->ID === (int)$current) continue;
            if ('admin' === $user->user_login) continue;
            require_once ABSPATH . 'wp-admin/includes/user.php';
            wp_delete_user($user->ID);
        }
    }

    private static function delete_uploads(): void {
        $upload_dir = wp_upload_dir();
        $root = $upload_dir['basedir'];
        if (is_dir($root)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($files as $fileinfo) {
                $todo = ($fileinfo->isDir()) ? 'rmdir' : 'unlink';
                @$todo($fileinfo->getRealPath());
            }
        }
    }
}
