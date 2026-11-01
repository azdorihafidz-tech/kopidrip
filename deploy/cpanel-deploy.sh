#!/usr/bin/env bash
set -euo pipefail

REPOPATH="/home/azwc7142/repositories/kopidrip"
DEPLOYPATH="/home/azwc7142/public_html/kopidripsidikalang.com"

if [ -e "$DEPLOYPATH/storage" ] && [ ! -L "$DEPLOYPATH/storage" ]; then
    echo "A real storage directory already exists; inspect it before deploying." >&2
    exit 1
fi

if [ -L "$DEPLOYPATH/storage" ]; then
    /bin/rm "$DEPLOYPATH/storage"
fi

/bin/cp -R "$REPOPATH/public/." "$DEPLOYPATH/"
/bin/cp "$REPOPATH/deploy/cpanel-index.php" "$DEPLOYPATH/index.php"
/bin/ln -s "$REPOPATH/storage/app/public" "$DEPLOYPATH/storage"
