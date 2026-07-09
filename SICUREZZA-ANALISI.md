# Come hanno bucato il sito – e se è ancora vulnerabile

## Come è entrato l’attaccante

I due file backdoor che abbiamo rimosso **non fanno parte** del plugin ufficiale Simple Author Box (su WordPress.org). Nel plugin ufficiale non compaiono e non vengono mai inclusi con `require`/`include`. Quindi sono stati **aggiunti dopo**, in uno di questi modi:

### 1. **Plugin installato da fonte non ufficiale (più probabile)**  
Il plugin “Simple Author Box” potrebbe essere stato installato da:
- un sito di plugin “nulled” o crackati,
- un download da un link ricevuto per email,
- un pacchetto “bundle” da siti terzi.

In questi casi il file ZIP può essere **già modificato** e contenere i file malevoli (`admin-config-section.php` e `admin-module-index.php`). Installando quel pacchetto, si installa anche il backdoor.

### 2. **Accesso già compromesso (FTP, hosting, WordPress)**  
Se l’attaccante aveva già accesso (FTP, pannello hosting, o account WordPress admin), ha potuto:
- caricare i due file nella cartella del plugin, oppure
- modificare/caricare un plugin esistente.

In quel caso la “porta d’ingresso” è stata un’altra (password rubata, altro plugin vulnerabile, ecc.).

### 3. **Vulnerabilità di un altro componente**  
Un altro plugin o il tema potevano avere una vulnerabilità (es. caricamento file, RCE) usata per scrivere quei file dentro `simple-author-box`.  
Simple Author Box 2.59 ha una CVE nota (CVE-2023-3601, IDOR in versioni &lt; 2.52): quella **non** permette di caricare file, solo di leggere dati utente; la tua versione 2.59 è già corretta per quella CVE.

---

## Cosa facevano i backdoor

- Erano **PHP eseguibili direttamente** via URL, ad es.:  
  `https://www.mapparte.com/wp-content/plugins/simple-author-box/.../admin-config-section.php`
- Contenevano payload **cifrati** (base64 + decrittazione) e `eval()` per eseguire codice arbitrario.
- Tipico uso: **SEO poisoning / cloaking**: in base a User-Agent (es. bot Google) o altri parametri mostrano pagine spam (es. “Breathable Leggings…”) ai motori di ricerca e contenuto normale agli utenti.

Quindi: prima qualcuno ha messo quei file sul server (con uno dei modi sopra), poi quei file sono stati usati per il cloaking segnalato da Aruba.

---

## Il sito è ancora vulnerabile?

**Stato attuale (dopo le nostre modifiche):**

| Controllo | Esito |
|-----------|--------|
| Backdoor in Simple Author Box | **Rimossi** i due file malevoli |
| WordPress core | **Aggiornato** a 6.9.1 |
| wp-cron.php | **Pulito** (nessuna modifica sospetta) |
| wp-content/mu-plugins | **Assente** (nessun plugin nascosto) |
| Altri eval/base64 sospetti in wp-content | **No** (solo librerie note: Post SMTP, ACF, FakerPress, ecc.) |
| .htaccess | **Pulito** (solo regole standard WP) |

Quindi **le backdoor conosciute sono state rimosse** e non risultano altre porte aperte evidenti nel codice che hai in locale.

Rimangono comunque possibili rischi se:
- In produzione è ancora presente una **vecchia copia** del plugin con i due file (va sovrascritta con la copia bonificata o il plugin va reinstallato da WordPress.org).
- Le **password** (WordPress, FTP, hosting, DB) non sono state cambiate dopo la compromissione (l’attaccante potrebbe rientrare con le stesse credenziali).
- Un **altro plugin/tema** ha una vulnerabilità non ancora corretta (va tenuto tutto aggiornato).

---

## Cosa fare per non essere di nuovo bucato

1. **Plugin e tema**  
   - Installa/aggiorna i plugin **solo da WordPress.org** (o da fonti ufficiali verificate).  
   - Evita plugin “nulled”, crackati o da siti sconosciuti.

2. **Simple Author Box in produzione**  
   - Carica la versione **bonificata** (senza i due file) oppure disinstalla e reinstalla **solo** da “Aggiungi plugin” → “Simple Author Box” su WordPress.org.

3. **Password**  
   - Cambia **tutte** le password: WordPress (admin e altri utenti), FTP, pannello hosting, database, email collegate al dominio.

4. **Aggiornamenti**  
   - Tieni **WordPress, temi e plugin** sempre aggiornati; controlla almeno una volta al mese.

5. **Accessi**  
   - Controlla gli **utenti WordPress** (ruoli, amministratori) ed elimina account sconosciuti o inutili.

6. **Dopo il deploy**  
   - In Google Search Console richiedi la **rimozione/ricalcolo** delle URL spam (come indicato nel piano di bonifica).

---

In sintesi: **come hanno bucato** = molto probabilmente plugin da fonte non ufficiale già infetto, oppure accesso (FTP/WordPress/hosting) già compromesso. **Se è ancora vulnerabile** = no, le backdoor che abbiamo trovato sono state rimosse e il core è aggiornato; per restare al sicuro serve cambiare le password, usare solo plugin ufficiali e tenere tutto aggiornato in produzione.
