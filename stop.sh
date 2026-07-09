#!/bin/bash

echo "🛑 Fermo ambiente di sviluppo mapparte.com..."

# Ferma il dev container
echo "📦 Fermo DevContainer..."
cd .devcontainer
docker compose down

if [ $? -eq 0 ]; then
    echo "✅ DevContainer fermato con successo!"
    echo ""
    echo "Per riavviare l'ambiente usa: ./start.sh"
else
    echo "❌ Errore nel fermare il DevContainer"
    exit 1
fi
