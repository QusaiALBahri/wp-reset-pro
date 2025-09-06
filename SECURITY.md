# Security Policy

- **Supported Versions**: Plugin supports WordPress 6.4+ and PHP 8.1+.
- **Report a Vulnerability**: Email qusai@albahri.org. We follow a 90‑day disclosure policy.
- **Hardening**:
  - Nonces for every state‑changing request.
  - Capability checks for every admin page and action.
  - Escaping on output and sanitization on input (see `src/Core/Security.php`).
  - No direct file access; `defined('ABSPATH')` guards in all PHP files.
  - Minimal privileges: multisite resets require `is_super_admin()`.
