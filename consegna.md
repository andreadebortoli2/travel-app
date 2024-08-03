:libri: esercizio di oggi: Travel App
nome repo: travel-app
Crea il diario di viaggio delle tue vacanze estive
:freccetta: Obiettivo
Compiere delle scelte progettuali e saperle motivare, documentare e presentare (comprese eventuali criticità emerse).
:pagina_frontale: Traccia
Sviluppa una Web per la pianificazione e gestione del tuo viaggio. Suddividi il viaggio in giornate in cui ogni giornata riporta le tappe da visitare. Inserisci dettagli (titolo, descrizione, data etc), immagini, cibo, curiosità o qualsiasi altra info.
Le tappe del viaggio saranno anche visualizzate su mappa tramite un servizio a tua scelta.
Implementa una funzionalità per tenere traccia della progressione delle tappe del tuo viaggio (anche quando chiudi la pagina).
Aggiungi personalizzazioni e funzionalità a tuo piacimento. Alcuni esempi:
aprire dettaglio viaggio o immagini in una modale
possibilità di aggiungere note a una tappa durante il viaggio
possibilità di inserire una valutazione (ad es. da 1 a 5 stelle) su una tappa
Carica il codice su un servizio di hosting a tua scelta per condividere l’app.
Milestone:
:uno: Progettazione e pianificazione
Definire i requisiti funzionali e non funzionali dell'app e creare un wireframe dell'interfaccia utente. Progettare una soluzione tecnologica adeguata. Pianificare le attività da svolgere per arrivare alla deadline.
:due: Setup ambiente e progetto
Configurare l’ambiente di sviluppo se necessario e definire un setup di progetto (strumenti, build tools, git etc..). Inizializzare il progetto.
:tre: Definizione di uno stato (struttura dei dati)
Definire la struttura dati idonea a rappresentare un viaggio e tutti i suoi dettagli, nonché le scelte e i dati inseriti dall’utente.
:tre: Sviluppo layout e componenti UI
Sviluppo della struttura di layout e dei componenti della pagina.
:quattro: Logica applicativa
Logica di render dell’interfaccia a partire dal dato strutturato. Aggiungi le varie modalità di visualizzazione e interazioni.
:cinque: Persistenza
Implementare la funzionalità per salvare la progressione delle tappe (es. LocalStorage o backend) e garantire che i dati siano mantenuti anche dopo la chiusura della pagina.
:sei: Deployment
Caricare il codice su un servizio di hosting a tua scelta (Netlify, Vercel, Heroku, ecc.).
:sette: Documentazione
Scrivere un README da aggiungere alla repository per documentare requisiti, installazione e funzionalità.
:stella2: Più viaggi! (BONUS)
Visualizzare più di un viaggio selezionandoli da un menu.
:stella2: PWA (BONUS 2)
Integra alcune funzionalità che caratterizzano una Progressive Web Application e che rendono la tua app simile a un’app nativa! Abbiamo parlato di questo durante una masterclass sulle PWA (Progressive Web Apps)
Buon kata e buone vacanze! :ninja:


-----------------------------------------------------------------------


Poi ragazzi cercate sul World Wide Web che è tanto Wide!
Qui alcune altre risorse e consgili:
Scelte tecniche
Ci sono diversi approcci con cui sviluppare l’applicazione. Ad esempio potresti voler usare un framework (come vuejs) oppure semplice plain js. In ogni caso ricorda che suddividere il codice in moduli è sempre una buona pratica. Valuta l’utilizzo di un build tool (ad es. vite) per organizzare e ottimizzare il codice.
Risorse
JavaScript modules
PWA (Progressive Web Applications) e https://boolean.notion.site/PWA-Progressive-Web-Apps-32aa768a4cf24469b9aa7cbb8f550270
https://dev.to/donnierich/api-key-management-on-netlify-4il9


-----------------------------------------------------------------------


## Steps:
- define database structure (drawio) ✅
- create database and migrations ✅
- add foreign keys ✅
- create seeders ✅
- expose get api ✅
- show data on client side: ✅
    - show trips and days on home page ✅
    - show stops on day page ✅
- create form to generate trip ✅
- set controller to create trip and expose post api ✅
- create form to update trip ✅
- set controller to update trip and expose post api ✅
- create form to delete trip ✅
- set controller to delete trip and expose post api ✅
- create form to generate day ✅
- set controller to create day and expose post api ✅
- create form to update day ✅
- set controller to update day and expose post api ✅
- create form to delete day ✅
- set controller to delete day and expose post api ✅
- create form to generate stop ✅
- set controller to create stop and expose post api ✅
- create form to update stop ✅ (separate ratings?) 🤔 
- set controller to update stop and expose post api ✅
- create form to delete stop ✅
- set controller to delete stop and expose post api ✅
- code refactoring  (general)
- style all pages
- DEPLOYMENT
- DOCUMENTATION