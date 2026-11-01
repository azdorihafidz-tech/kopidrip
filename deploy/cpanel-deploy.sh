#!/usr/bin/env bash
set -euo pipefail

REPOPATH="/home/azwc7142/repositories/kopidrip"
DEPLOYPATH="/home/azwc7142/public_html/kopidripsidikalang.com"

if [ -L "$DEPLOYPATH/storage" ]; then
    /bin/rm "$DEPLOYPATH/storage"
fi

/bin/mkdir -p "$DEPLOYPATH/storage"
/bin/cp -R "$REPOPATH/public/." "$DEPLOYPATH/"
/bin/cp "$REPOPATH/deploy/cpanel-index.php" "$DEPLOYPATH/index.php"
