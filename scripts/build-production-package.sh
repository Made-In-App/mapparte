#!/bin/bash
# Crea un archivio con solo i file da mettere in produzione (da estrarre sul server).
# wp-config.php NON è incluso: sul server va tenuto/configurato a parte.

set -e
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
OUT="$ROOT/mapparte-production-$(date +%Y%m%d).tar.gz"
cd "$ROOT"

echo "📦 Creazione pacchetto produzione in $OUT"
echo "   (wp-config.php escluso: va lasciato quello del server)"
echo ""

tar --exclude='.devcontainer' \
    --exclude='scripts' \
    --exclude='start.sh' \
    --exclude='stop.sh' \
    --exclude='wp-config.php' \
    --exclude='wp-content/debug.log' \
    --exclude='wp-content/cache' \
    --exclude='*.md' \
    --exclude='.git' \
    -czvf "$OUT" \
    wp-admin \
    wp-includes \
    wp-content \
    .htaccess \
    index.php \
    wp-activate.php \
    wp-blog-header.php \
    wp-comments-post.php \
    wp-cron.php \
    wp-links-opml.php \
    wp-load.php \
    wp-login.php \
    wp-mail.php \
    wp-settings.php \
    wp-signup.php \
    wp-trackback.php \
    xmlrpc.php \
    license.txt \
    readme.html

echo ""
echo "✅ Fatto: $OUT"
echo "   Sul server: carica e estrai (tar -xzvf ...), poi verifica wp-config.php (DB produzione)."
