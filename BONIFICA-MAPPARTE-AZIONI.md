# Piano di bonifica mapparte.com – cosa fare

Il dominio è stato sospeso da Aruba per **SEO poisoning/cloaking** (malware). Di seguito cosa è stato fatto e cosa devi fare tu per riattivare il sito e rispondere ad Aruba.

---

## ✅ Fatto (sul codice in locale)

- **Trovate e rimosse 2 backdoor** nel plugin **Simple Author Box**:
  - `wp-content/plugins/simple-author-box/inc/elementor/admin-config-section.php` (payload cifrato + `eval`)
  - `wp-content/plugins/simple-author-box/assets/src/js/utils/admin-module-index.php` (altro backdoor con `base64_decode` + `eval`)
- Il file `.htaccess` in root è pulito (solo regole standard WordPress).

---

## 📋 Cosa fare adesso (in ordine)

### 1. Backup e upload del sito bonificato

- Fai un **backup completo** del sito attuale su server (se hai ancora accesso) e del database.
- Sostituisci i file sul **hosting Aruba** con questa copia bonificata (cartella del progetto dove hai rimosso i file malevoli).
- **Non** ripristinare l’intera vecchia struttura dal backup pre-bonifica: usa solo **contenuti** (es. export/import) e file che hai verificato.

### 2. Plugin Simple Author Box

- **Opzione A (consigliata):** Disinstalla completamente il plugin **Simple Author Box** da WordPress (Plugin → disattiva → elimina). Se ti serve la funzionalità, reinstalla una copia pulita da [wordpress.org/plugins/simple-author-box](https://wordpress.org/plugins/simple-author-box).
- **Opzione B:** Se tieni il plugin, tieni solo la versione attuale **dopo** la rimozione dei due file (già fatta in locale). Controlla che sul server non esistano più:
  - `wp-content/plugins/simple-author-box/inc/elementor/admin-config-section.php`
  - `wp-content/plugins/simple-author-box/assets/src/js/utils/admin-module-index.php`

### 3. Hardening e password

- **Reset password** del pannello di gestione dominio (Aruba):  
  [Modifica login e password – Aruba](https://www.aruba.it/supporto/assistenza/gestione-dominio.aspx) (come indicato nella loro email).
- **Cambia tutte le password** di:
  - WordPress (admin e altri utenti con privilegi)
  - Database MySQL
  - FTP / gestione file hosting
  - Email collegate al dominio
- Controlla **utenti WordPress**: elimina utenti sconosciuti o ruoli “amministratore” non autorizzati.

### 4. Aggiornamenti WordPress

- Aggiorna **WordPress** all’ultima versione.
- Aggiorna **temi e plugin** all’ultima versione.
- Rimuovi plugin e temi non usati; se un plugin non è aggiornato da oltre 6 mesi, valuta di sostituirlo.

### 5. Verifica cloaking (per Aruba)

Aruba chiede di verificare le risposte con diversi User-Agent:

- Da browser “normale” e da strumento che simuli il bot di Google (User-Agent di Googlebot), controlla che:
  - La stessa URL restituisca **lo stesso tipo di contenuto** (niente pagine “normali” per gli utenti e pagine spam per i bot).
- L’URL segnalata da Aruba  
  `https://www.mapparte.com/485453/Breathable-Leggings-For-Yoga-Regular-Plus-Size-Pants-For-Gym`  
  **non** deve più restituire contenuti (dovrebbe dare 404 dopo la bonifica). Puoi documentare con screenshot o breve descrizione da allegare alla risposta.

### 6. Rispondere ad Aruba

- Rispondi alla **stessa email** di Aruba (alla richiesta di sospensione) indicando:
  - Analisi del codice e del database eseguita.
  - File malevoli individuati (plugin Simple Author Box, due file backdoor) e rimossi.
  - Verifica di `.htaccess` e file modificati di recente.
  - Bonifica del CMS e aggiornamento di componenti (WordPress, temi, plugin).
  - Reset password di gestione dominio e degli accessi al sito.
- Chiedi la **riattivazione** del dominio dopo aver completato questi passaggi.

### 7. Google Search Console

- Dopo la riattivazione del sito:
  - Accedi a [Google Search Console](https://search.google.com/search-console).
  - Usa **Rimuovi URL** o le funzioni di **indicizzazione** per segnalare la bonifica e richiedere la rimozione/aggiornamento dei contenuti spam (come indicato da Aruba: “Richiesta di rimozione dei contenuti spam da Google Search Console una volta completata la bonifica”).

### 8. Prevenzione (come da Aruba)

- Antivirus e antispyware aggiornati sui PC da cui pubblichi.
- Nessun uso di software “crackato” o fonti non ufficiali.
- Controllo periodico degli utenti e degli accessi WordPress.
- Aggiornamenti mensili di WordPress, temi e plugin.
- Sostituire temi/plugin senza aggiornamenti da oltre 6 mesi.

---

## Riepilogo per la risposta ad Aruba

Puoi scrivere qualcosa del genere:

> Abbiamo eseguito l’analisi del codice sorgente e individuato backdoor nel plugin WordPress “Simple Author Box” (file [nome file 1] e [nome file 2]). Tali file sono stati rimossi e il plugin è stato disinstallato/reinstallato pulito. Sono stati verificati .htaccess e file modificati di recente, aggiornati WordPress, temi e plugin, effettuato il reset delle password di gestione dominio e degli accessi al sito. La verifica con diversi user-agent conferma l’assenza di cloaking. Chiediamo la riattivazione del dominio.

---

## Note tecniche

- **Tipo di attacco:** backdoor PHP (payload cifrato + `eval`), tipico di SEO poisoning/cloaking.
- **Punto di ingresso probabile:** plugin Simple Author Box (versione compromessa o installazione non ufficiale).
- **.htaccess:** controllato, nessuna regola sospetta.

Se vuoi, il passo successivo può essere una checklist operativa (comando per upload, elenco plugin da aggiornare, ecc.) in base a come lavori sul server (FTP, SSH, pannello Aruba).
