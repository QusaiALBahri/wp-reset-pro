<?php
namespace WPResetPro\Core;

defined('ABSPATH') || exit;

class DryRunSimulator {
    public static function counts(array $parts): array {
        $out = ['posts'=>0,'media'=>0,'terms'=>0,'comments'=>0,'users'=>0,'options'=>0];
        if (in_array('content', $parts, true)) {
            $out['posts'] = (int) wp_count_posts('post')->publish + (int) wp_count_posts('page')->publish;
            $media = get_posts(['post_type'=>'attachment','posts_per_page'=>1,'fields'=>'ids']);
            $out['media'] = (int) wp_count_attachments();
            $taxes = get_taxonomies([], 'names');
            $term_total = 0;
            foreach ($taxes as $t) {
                $tt = wp_count_terms(['taxonomy'=>$t,'hide_empty'=>false]);
                if (!is_wp_error($tt)) $term_total += (int) $tt;
            }
            $out['terms'] = $term_total;
            $out['comments'] = (int) wp_count_comments()->total_comments;
        }
        if (in_array('users', $parts, true)) {
            $out['users'] = count_users()['total_users'] ?? 0;
        }
        if (in_array('settings', $parts, true)) {
            global $wpdb;
            $out['options'] = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->options} WHERE option_name NOT LIKE '_transient_%' AND option_name NOT LIKE '_site_transient_%'");
        }
        return $out;
    }
}
