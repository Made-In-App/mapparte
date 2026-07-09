# Deploy in produzione (mapparte.com su Aruba)

Cosa **copiare** sul server e cosa **non** copiare.

---

## Da copiare (upload su hosting)

### 1. Core WordPress (aggiornato 6.9.1)
- **wp-admin/** (tutta la cartella)
- **wp-includes/** (tutta la cartella)
- **File nella root:**  
  `index.php`, `wp-activate.php`, `wp-blog-header.php`, `wp-comments-post.php`, `wp-cron.php`, `wp-links-opml.php`, `wp-load.php`, `wp-login.php`, `wp-mail.php`, `wp-settings.php`, `wp-signup.php`, `wp-trackback.php`, `xmlrpc.php`, `license.txt`, `readme.html`

### 2. Contenuti e tema
- **wp-content/themes/** (tutti i temi, incluso mapparte)
- **wp-content/uploads/** (tutti i file caricati)
- **wp-content/languages/** (se presente)

### 3. Plugin (bonificati)
- **wp-content/plugins/** — **tranne**: sul server verifica che **Simple Author Box** sia assente o reinstallato da WordPress.org. In locale i due file backdoor sono già stati rimossi; se carichi la cartella `simple-author-box` così com’è (senza quei due file) va bene, altrimenti disinstalla dal pannello e reinstalla da “Aggiungi plugin”.

### 4. File di configurazione
- **.htaccess** (nella root) — quello in progetto è pulito (solo regole WordPress standard).

---

## Non copiare

- **wp-config.php** — Sul server deve restare (o essere ricreato) con i dati **produzione**:
  - `DB_NAME`, `DB_USER`, `DB_PASSWORD`, `DB_HOST` (quelli Aruba, es. `89.46.111.111`)
  - **Niente** `WP_HOME` / `WP_SITEURL` (o devono puntare a `https://www.mapparte.com`).
  - Se in produzione vuoi le stesse correzioni fatte in locale (NONCE_SALT unico, ACF_PRO_KEY, WP_DEBUG_DISPLAY), copia solo quelle righe nel wp-config del server, non l’intero file con DB locale.

- **Cartelle/file solo per sviluppo locale:**
  - `.devcontainer/`
  - `scripts/`
  - `start.sh`, `stop.sh`
  - `BONIFICA-MAPPARTE-AZIONI.md`, `DEPLOY-PRODUZIONE.md` (opzionali, non servono al sito)

- **File di log / cache locale:**
  - `wp-content/debug.log` (se presente)
  - `wp-content/cache/` (se presente, spesso il server la rigenera)

---

## Riepilogo veloce

| Cosa | Azione |
|------|--------|
| wp-admin, wp-includes, *.php in root | **Carica** (sovrascrivi) |
| wp-content/themes, wp-content/uploads | **Carica** |
| wp-content/plugins | **Carica** (plugin senza i 2 file backdoor di Simple Author Box) |
| .htaccess | **Carica** (sovrascrivi con quello pulito) |
| wp-config.php | **Non sostituire** sul server; eventualmente integra a mano le fix (NONCE_SALT, ACF_PRO_KEY, debug) |

---

## Dopo l’upload

1. Controlla che il sito risponda su `https://www.mapparte.com`.
2. In **Impostazioni → Generali** verifica che “Indirizzo sito WordPress” e “Indirizzo del sito” siano `https://www.mapparte.com`.
3. Aggiorna plugin e tema dal pannello se ci sono aggiornamenti.
4. Rispondi ad Aruba confermando la bonifica (vedi BONIFICA-MAPPARTE-AZIONI.md).
