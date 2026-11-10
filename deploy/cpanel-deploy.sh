#!/usr/bin/env bash
set -euo pipefail

REPOPATH="/home/azwc7142/repositories/kopidrip"
DEPLOYPATH="/home/azwc7142/public_html/kopidripsidikalang.com"

if [ -L "$DEPLOYPATH/storage" ]; then
    /bin/rm "$DEPLOYPATH/storage"
fi

/bin/mkdir -p "$DEPLOYPATH/storage"
/bin/cp -R "$REPOPATH/public/." "$DEPLOYPATH/"
/bin/mkdir -p "$DEPLOYPATH/storage/editorial"
/bin/cp -R "$REPOPATH/storage/app/public/editorial/." "$DEPLOYPATH/storage/editorial/"
/bin/cp "$REPOPATH/deploy/cpanel-index.php" "$DEPLOYPATH/index.php"
