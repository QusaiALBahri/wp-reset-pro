<?php
namespace WPResetPro\Core;

defined('ABSPATH') || exit;

class Lock {
    private const OPT = 'wp_reset_pro_lock';
    public static function acquire(int $ttl = 600): bool {
        $now = time();
        $lock = (int) get_site_option(self::OPT, 0);
        if ($lock && $lock > ($now - $ttl)) return false;
        update_site_option(self::OPT, $now);
        return true;
    }
    public static function release(): void {
        delete_site_option(self::OPT);
    }
}
