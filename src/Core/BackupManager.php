<?php
namespace WPResetPro\Core;

use ZipArchive;
use wpdb;

defined('ABSPATH') || exit;

class BackupManager {
    public static function maybe_backup_before_reset(): ?string {
        $doBackup = isset($_POST['wp_reset_pro_do_backup']) && 'yes' === sanitize_text_field(wp_unslash($_POST['wp_reset_pro_do_backup']));
        if (!$doBackup) return null;
        return self::create_backup();
    }

    public static function create_backup(): string {
        $upload_dir = wp_upload_dir();
        $backup_dir = trailingslashit($upload_dir['basedir']) . 'wp-reset-pro-backups';
        wp_mkdir_p($backup_dir);

        $timestamp = gmdate('Ymd-His');
        $zip_path = $backup_dir . '/backup-' . $timestamp . '.zip';

        if (!class_exists('ZipArchive')) {
            wp_die(esc_html__('PHP ZipArchive is required to create backups. Please enable the zip extension.', 'wp-reset-pro'));
        }
        $zip = new ZipArchive();
        if (true !== $zip->open($zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            wp_die(esc_html__('Failed to create backup archive.', 'wp-reset-pro'));
        }

        // Add uploads dir.
        $root = realpath($upload_dir['basedir']);
        if ($root) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            foreach ($files as $file) {
                $filePath = $file->getRealPath();
                $localName = 'uploads/' . ltrim(str_replace($root, '', $filePath), '/\\');
                if ($file->isDir()) {
                    $zip->addEmptyDir($localName);
                } else {
                    $zip->addFile($filePath, $localName);
                }
            }
        }

        // Add DB dump.
        $sql = self::dump_database();
        $zip->addFromString('database.sql', $sql);

        $zip->close();

        Logger::log('info', 'Backup created', ['file' => $zip_path]);
        return $zip_path;
    }

    private static function dump_database(): string {
        global $wpdb;
        $tables = $wpdb->get_col('SHOW TABLES');
        $sqlParts = [];
        foreach ($tables as $table) {
            $create = $wpdb->get_row("SHOW CREATE TABLE {$table}", ARRAY_N);
            if ($create && isset($create[1])) {
                $sqlParts[] = $create[1] . ';';
            }
            $rows = $wpdb->get_results("SELECT * FROM {$table}", ARRAY_A);
            foreach ($rows as $row) {
                $vals = array_map(static function ($val) use ($wpdb) {
                    if (is_null($val)) return 'NULL';
                    return "'" . esc_sql($val) . "'";
                }, array_values($row));
                $sqlParts[] = 'INSERT INTO `' . esc_sql($table) . '` VALUES(' . implode(',', $vals) . ');';
            }
        }
        return implode("\n\n", $sqlParts);
    }
}
