#!/usr/bin/env bash
#
# sync-prod-to-staging.sh — pull the production DB + uploads down into
# staging so you can test code changes against real content.
#
# Safe direction: prod is the source of truth; staging is clobbered.
# DO NOT reverse this — a misrun would wipe prod content with stale
# staging data. (The inverse direction exists only as the one-time
# bootstrap in scripts/bootstrap-prod.sh.)
#
# Usage:
#   ./scripts/sync-prod-to-staging.sh
#
# Requires the same env vars as bootstrap-prod.sh.

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

TS="$(date -u +%Y%m%d-%H%M%S)"
REMOTE_TMP="/tmp/pethoven-sync-${TS}"
DUMP_FILE="${REMOTE_TMP}/prod.sql"

echo "==> Sync prod → staging at ${TS}"
echo "    Source: ${PROD_URL}"
echo "    Target: ${STAGING_URL}  (will be overwritten)"

ssh_cmd "mkdir -p '${REMOTE_TMP}'"

echo "==> [remote] Backing up current staging DB before overwrite"
ssh_cmd "wp db export '${REMOTE_TMP}/staging-backup.sql' --path='${STAGING_DIR}' --add-drop-table"

echo "==> [remote] Dumping prod DB"
ssh_cmd "wp db export '${DUMP_FILE}' --path='${PROD_DIR}' --add-drop-table"

echo "==> [remote] Importing prod DB into staging"
ssh_cmd "wp db reset --yes --path='${STAGING_DIR}' && wp db import '${DUMP_FILE}' --path='${STAGING_DIR}'"

echo "==> [remote] Rewriting URLs ${PROD_URL} → ${STAGING_URL} inside staging DB"
ssh_cmd "wp search-replace '${PROD_URL}' '${STAGING_URL}' --all-tables --skip-columns=guid --path='${STAGING_DIR}'"

PROD_HOST="${PROD_URL#https://}"; PROD_HOST="${PROD_HOST#http://}"
STAGING_HOST="${STAGING_URL#https://}"; STAGING_HOST="${STAGING_HOST#http://}"
echo "==> [remote] Rewriting hostnames ${PROD_HOST} → ${STAGING_HOST}"
ssh_cmd "wp search-replace '${PROD_HOST}' '${STAGING_HOST}' --all-tables --skip-columns=guid --path='${STAGING_DIR}'"

echo "==> [remote] Mirroring wp-content/uploads from prod to staging"
ssh_cmd "rsync -a --delete '${PROD_DIR}/wp-content/uploads/' '${STAGING_DIR}/wp-content/uploads/'"

echo "==> [remote] Flushing caches + rewrite rules on staging"
ssh_cmd "wp cache flush --path='${STAGING_DIR}' || true"
ssh_cmd "wp rewrite flush --path='${STAGING_DIR}' || true"

echo "==> Done. Staging now mirrors prod content. Verify: ${STAGING_URL}"
