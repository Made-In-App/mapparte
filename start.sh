#!/bin/bash

echo "🚀 Avvio ambiente di sviluppo mapparte.com..."

# Verifica che Docker sia in esecuzione
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker non è in esecuzione. Avvia Docker Desktop e riprova."
    exit 1
fi

# Verifica che MariaDB sia in esecuzione
if ! brew services list | grep mariadb | grep started > /dev/null; then
    echo "⚠️  MariaDB non è in esecuzione. Avvio MariaDB..."
    brew services start mariadb
    sleep 3
fi

# Avvia il dev container
echo "📦 Avvio DevContainer..."
cd .devcontainer
docker compose up -d

if [ $? -eq 0 ]; then
    echo "✅ DevContainer avviato con successo!"
    echo "🌐 WordPress disponibile su:"
    echo "   https://local.mapparte.com   (aggiungi in /etc/hosts: 127.0.0.1 local.mapparte.com)"
    echo "   https://localhost"
    echo "🗄️  Database: localhost:3306 (wp_user/wp_password, database: mapparte_dev)"
    echo "💾 Cache: WordPress Transients (no Redis needed)"
    echo ""
    echo "Per fermare l'ambiente usa: ./stop.sh"
else
    echo "❌ Errore nell'avvio del DevContainer"
    exit 1
fi
