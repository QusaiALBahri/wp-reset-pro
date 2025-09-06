#!/usr/bin/env bash
set -euo pipefail

PLUGIN=wp-reset-pro
OUT=dist

# Extract version from main plugin header
VER=$(grep -E "Version:\s*[0-9]+\.[0-9]+\.[0-9]+" ${PLUGIN}/wp-reset-pro.php | awk '{print $2}')

rm -rf "$OUT" && mkdir -p "$OUT/$PLUGIN"

# Optional composer optimize (no-op if composer not installed)
if [ -f "${PLUGIN}/composer.json" ] && command -v composer >/dev/null 2>&1; then
  (cd "${PLUGIN}" && composer dump-autoload -o || true)
fi

rsync -a "${PLUGIN}/" "$OUT/$PLUGIN/"   --exclude ".git"   --exclude ".github"   --exclude "node_modules"   --exclude "tests"   --exclude ".phpcs.xml"   --exclude "composer.lock"   --exclude "CHANGELOG.md"   --exclude "ROADMAP.md"

( cd "$OUT" && zip -r "../${PLUGIN}-v${VER}.zip" "$PLUGIN" >/dev/null )
echo "Built ${PLUGIN}-v${VER}.zip in $(pwd)"
