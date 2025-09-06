# WP Reset Pro

A production‑ready reset tool for WordPress with **full/partial reset**, **optional backups**, **scheduling**, **role‑based access**, **history log**, and a **Status/Health** page.

## Requirements
- WordPress 6.4+
- PHP 8.1+

## Features
1. Full site reset (database + uploads) with multi‑step confirmations.
2. Partial reset (content only, settings only, users only).
3. Optional backup (DB dump + uploads zip) before destructive operations.
4. Reset scheduling via WP‑Cron (maintenance windows).
5. Role‑based access: only admins; multisite requires super admin for resets.
6. Reset history tracking with timestamps and operator identity.
7. Status/Health page with environment and safety checks.

## Safety
- Strong confirmation dialog requires typing a phrase and ticking checkboxes.
- Nonces + capability checks on all actions.
- Option to create a backup before any reset.

## GDPR
Implements exporter/eraser for plugin data (reset history/events).

## Install
1. Copy folder to `wp-content/plugins/wp-reset-pro/`.
2. Run `composer dump-autoload` (if developing).
3. Activate in **Plugins**.

## Developer Notes
- PSR‑4 namespaces under `WPResetPro\`.
- See `src/Admin/SettingsPage.php` for Settings API usage.
- See `src/Core/ResetManager.php` for reset operations.

## Uninstall
If **Delete data on uninstall** is enabled, all plugin data (options, logs, cron hooks) will be removed.
