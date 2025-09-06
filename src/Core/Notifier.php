<?php
namespace WPResetPro\Core;

defined('ABSPATH') || exit;

class Notifier {
    public static function mail(string $subject, string $message): void {
        $admin = get_option('admin_email');
        if ($admin) {
            wp_mail($admin, $subject, $message);
        }
    }
}
