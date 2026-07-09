#!/bin/bash

echo "🚀 Setup mapparte.com DevContainer (database locale)..."

# Backup del wp-config.php originale se esiste
if [ -f "wp-config.php" ] && [ ! -f "wp-config-production.php" ]; then
    echo "📋 Backup del wp-config.php originale..."
    cp wp-config.php wp-config-production.php
fi

# Copia la configurazione di sviluppo per database locale
if [ -f "wp-config-dev.php" ]; then
    echo "⚙️  Configurazione per ambiente di sviluppo (database locale)..."
    cp wp-config-dev.php wp-config.php
    
    # Sostituisci l'host del database con quello locale
    sed -i 's/define.*DB_HOST.*/define('\''DB_HOST'\'', '\''host.docker.internal'\'');/' wp-config.php
fi

# Imposta i permessi corretti
echo "🔐 Impostazione permessi..."
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html

# Crea directory per i log se non esistono
mkdir -p /var/log/php
touch /var/log/php_errors.log
chown www-data:www-data /var/log/php_errors.log

echo "✅ Setup completato!"
echo ""
echo "🌐 Sito disponibile:"
echo "   - https://local.mapparte.com (aggiungi 127.0.0.1 local.mapparte.com in /etc/hosts)"
echo "   - https://localhost"
echo ""
echo "🗄️  Database (locale):"
echo "   - Host: host.docker.internal"
echo "   - Database: mapparte_dev"
echo "   - User: wp_user"
echo "   - Password: wp_password"
echo ""
echo "💡 Crea il DB: mysql -u wp_user -pwp_password -e 'CREATE DATABASE IF NOT EXISTS mapparte_dev;'"
