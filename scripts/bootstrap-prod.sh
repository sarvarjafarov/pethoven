#!/usr/bin/env bash
#
# bootstrap-prod.sh — ONE-TIME clone of staging → production.
#
# Copies the staging site's database and uploads directory to production,
# rewrites URLs, and flushes caches. After this runs once, prod is a
# point-in-time snapshot of staging. From then on, content is edited on
# prod, and `sync-db.sh` pulls prod → staging for testing.
#
# Usage:
#   ./scripts/bootstrap-prod.sh
#
# Requires env:
#   SSH_HOST, SSH_USER, SSH_PORT      — Hostinger SSH target (shared for both sites)
#   STAGING_DIR                       — absolute path to opossum public_html on the server
#   PROD_DIR                          — absolute path to pethoven.com public_html on the server
#   STAGING_URL                       — e.g. https://seashell-opossum-486356.hostingersite.com
#   PROD_URL                          — e.g. https://pethoven.com
#
# The script assumes WP-CLI is installed on the Hostinger shared server.
# (Hostinger's managed WP hosting includes it at /usr/local/bin/wp.)

set -euo pipefail

: "${SSH_HOST:?SSH_HOST is required}"
: "${SSH_USER:?SSH_USER is required}"
: "${SSH_PORT:=65002}"
: "${STAGING_DIR:?STAGING_DIR is required}"
: "${PROD_DIR:?PROD_DIR is required}"
: "${STAGING_URL:?STAGING_URL is required}"
: "${PROD_URL:?PROD_URL is required}"

ssh_cmd() {
	ssh -o StrictHostKeyChecking=no -p "$SSH_PORT" "${SSH_USER}@${SSH_HOST}" "$@"
}

echo "==> Confirming you really want to overwrite PRODUCTION."
echo "    Source (staging): $STAGING_URL"
echo "    Target (prod):    $PROD_URL"
echo "    This will WIPE the prod database and uploads."
printf "    Type 'BOOTSTRAP PROD' to continue: "
read -r confirm
if [ "$confirm" != "BOOTSTRAP PROD" ]; then
	echo "Aborted." >&2
	exit 1
fi

TS="$(date -u +%Y%m%d-%H%M%S)"
REMOTE_TMP="/tmp/pethoven-bootstrap-${TS}"
DUMP_FILE="${REMOTE_TMP}/staging.sql"

echo "==> [remote] Creating temp dir ${REMOTE_TMP}"
ssh_cmd "mkdir -p '${REMOTE_TMP}'"

echo "==> [remote] Backing up CURRENT prod DB before wipe (safety)"
ssh_cmd "wp db export '${REMOTE_TMP}/prod-backup-before-bootstrap.sql' --path='${PROD_DIR}' --add-drop-table"

echo "==> [remote] Dumping staging DB"
ssh_cmd "wp db export '${DUMP_FILE}' --path='${STAGING_DIR}' --add-drop-table"

echo "==> [remote] Importing staging DB into prod"
ssh_cmd "wp db reset --yes --path='${PROD_DIR}' && wp db import '${DUMP_FILE}' --path='${PROD_DIR}'"

echo "==> [remote] Rewriting URLs ${STAGING_URL} → ${PROD_URL} inside prod DB"
ssh_cmd "wp search-replace '${STAGING_URL}' '${PROD_URL}' --all-tables --skip-columns=guid --path='${PROD_DIR}'"

# The bare hostname (without protocol) covers srcset attributes + any
# stored canonical refs. Safer to run both passes.
STAGING_HOST="${STAGING_URL#https://}"; STAGING_HOST="${STAGING_HOST#http://}"
PROD_HOST="${PROD_URL#https://}"; PROD_HOST="${PROD_HOST#http://}"
echo "==> [remote] Rewriting hostnames ${STAGING_HOST} → ${PROD_HOST}"
ssh_cmd "wp search-replace '${STAGING_HOST}' '${PROD_HOST}' --all-tables --skip-columns=guid --path='${PROD_DIR}'"

echo "==> [remote] Mirroring wp-content/uploads from staging to prod"
ssh_cmd "rsync -a --delete '${STAGING_DIR}/wp-content/uploads/' '${PROD_DIR}/wp-content/uploads/'"

echo "==> [remote] Flushing caches + rewrite rules on prod"
ssh_cmd "wp cache flush --path='${PROD_DIR}' || true"
ssh_cmd "wp rewrite flush --path='${PROD_DIR}' || true"

echo "==> [remote] Leaving prod backup at: ${REMOTE_TMP}/prod-backup-before-bootstrap.sql"
echo "==> Done. Prod is now a snapshot of staging at ${TS}."
echo "    Verify: ${PROD_URL}"
