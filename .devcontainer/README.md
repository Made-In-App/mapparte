# Dev container mapparte.com

## Avvio

Dalla root del progetto:

```bash
./start.sh
```

Ferma con `./stop.sh`.

## Dominio locale

Aggiungi nel file **hosts** del sistema:

- **macOS/Linux:** `sudo nano /etc/hosts`
- **Windows:** `C:\Windows\System32\drivers\etc\hosts`

Riga da aggiungere:

```
127.0.0.1   local.mapparte.com
```

Poi apri: **https://local.mapparte.com** (oppure https://localhost).

Al primo accesso il browser mostrerà un avviso sul certificato (il cert è generico): puoi procedere accettando l’eccezione.

## Database

- **Host (dal container):** `host.docker.internal`
- **Database:** `mapparte_dev`
- **User:** `wp_user`
- **Password:** `wp_password`

Se usi MariaDB/MySQL in locale (es. `brew services start mariadb`), crea il database:

```bash
mysql -u wp_user -pwp_password -e 'CREATE DATABASE IF NOT EXISTS mapparte_dev;'
```

Poi importa un dump del sito di produzione in `mapparte_dev` se necessario.
