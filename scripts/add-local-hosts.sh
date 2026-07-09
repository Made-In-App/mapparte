#!/bin/bash
# Aggiunge local.mapparte.com a /etc/hosts (richiede password admin)

ENTRY="127.0.0.1   local.mapparte.com"
HOSTS="/etc/hosts"

if grep -q 'local.mapparte.com' "$HOSTS"; then
    echo "✅ local.mapparte.com è già presente in $HOSTS"
    exit 0
fi

echo "Aggiungo $ENTRY a $HOSTS (ti verrà chiesta la password)..."
echo "$ENTRY" | sudo tee -a "$HOSTS" > /dev/null && echo "✅ Fatto. Puoi usare https://local.mapparte.com" || echo "❌ Errore"
