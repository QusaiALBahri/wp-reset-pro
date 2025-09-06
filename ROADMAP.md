# WP Reset Pro — Roadmap (v1.1 → v1.4)

## v1.1 (UX & Safety Polish)
- ✅ Live **Dry‑Run Preview** (content/settings/users counts) before reset.
- ✅ **Enhanced UI** cards, warnings, and confirm modal.
- ✅ **Email notification** after (scheduled) reset.
- ✅ **WP‑CLI**: `wp reset-pro full|partial` with flags and `--backup`.
- ✅ **REST API (protected)**: GET /status, POST /schedule (admin only).
- ✅ **Locking** to prevent concurrent resets.

## v1.2 (Schedulers & Observability)
- Optional **Action Scheduler** integration (if installed): retries + DLQ.
- PSR‑3 logger adapter; file/DB log rotation + `wp reset-pro logs` commands.
- Site Health integration section & surface more metrics/latency probes.

## v1.3 (Policies & Automation)
- Reset **policies** (retention windows, blackout windows).
- Programmatic **rules** (e.g., purge media older than N days; prune orphaned terms).
- **Export/Import** plugin settings JSON.

## v1.4 (Enterprise)
- Multi‑factor confirmation for destructive operations.
- Per‑site policy templates in multisite.
- Webhooks (signed) on reset lifecycle events.
