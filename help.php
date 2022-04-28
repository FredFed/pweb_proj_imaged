<?php

session_start();
require_once './php/utils/db_conn_handler_script.php';
require_once './php/utils/functions_script.php';
require_once './php/utils/definitions.php';

?>



<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/styles.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@600&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@700&display=swap" rel="stylesheet">
        <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
        <script src="./js/searchbox_clear.js"></script>
        <script src="./js/navbar_interaction.js"></script>
        <link rel="apple-touch-icon" sizes="180x180" href="./resources/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./resources/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./resources/icons/favicon-16x16.png">
        <link rel="manifest" href="./resources/icons/site.webmanifest">
        <title>Imaged | Help</title>
    </head>

    <body>
        <header>
            <?php include_once './php/elements/navbar.php' ?>   <!-- include il codice della navbar -->
        </header>

        <section id="page_main_section">

            <!-- MAIN SECTION -->
            <div class="help-section">
                <h1 class="help-banner">Guida all'utilizzo del sito:</h1>
                <div class="help-delimiter"></div>
                <p class="help-text">
                    imaged è una piattaforma sociale di condivisione di contenuti, nello specifico IMMAGINI e FOTOGRAFIE.<br>
                    OGNI VISITATORE può utilizzare imaged: essendo prima di tutto un servizio di hosting di immagini,<br>
                    l'utente NON REGISTRATO che volesse utilizzare imaged per conservare online le proprie foto è libero<br>
                    di farlo (ricorda di copiare il link!!!); è solo con la REGISTRAZIONE, però, che si SBLOCCANO le migliori<br>
                    funzionalità.<br>
                    L'utente REGISTRATO ha a disposizione DUE GALLERIE PERSONALI: una PUBBLICA (visibile a tutti) ed una PRIVATA<br>
                    (visibile esclusivamente a sé, utilizzabile come archivio personale).<br>
                    I POST pubblicati nella galleria PUBBLICA di ogni utente vanno a costituire la galleria "GLOBALE" che troviamo<br>
                    nella HOMEPAGE, contenente tutti i post pubblici di ogni utente.
                    Gli utenti REGISTRATI hanno a propria disposizione anche una galleria aggiuntiva cui vengono aggiunte le<br>
                    IMMAGINI SALVATE mediante l'apposita funzione.<br>
                    <br>
                    imaged permette di interagire con gli utenti mediante "LIKES" e "COMMENTI" ai loro POSTS.<br>
                    Per mettere un like a un'immagine o salvarla, è possibile VISITARNE la PAGINA o utilizzare la MINIATURA<br>
                    disponibile nella HOMEPAGE/GALLERIA.<br>

                    Ogni utente deve attenersi a rispettare le regole del buon senso per quanto riguarda le immagini nella galleria<br>
                    pubblica: se un utente pubblica materiali sconvenienti, AMMINISTRATORI e MODERATORI possono BLOCCARE tale<br>
                    materiale, rendendolo VISIBILE SOLTANTO ai PROPRIETARI nella loro GALLERIA PRIVATA (permettendogli di salvare<br>
                    l'immagine, anziché eliminarla).<br>
                    <br>
                    In caso di particolari problematiche, agli AMMINISTRATORI è anche BLOCCARE gli UTENTI, facendo sì che NON POSSANO più<br>
                    PUBBLICARE immagini sulla piattaforma.<br>
                    Compito degli AMMINISTRATORI è inoltre ELEGGERE i MODERATORI (e RIMUOVERLI se necessario).<br>
                    <br>
                    Gli utenti possono utilizzare anche una funzione di RICERCA, disponibile in quasi ogni pagina del sito, che permette, inserita<br>
                    una parola, di cercare UTENTI che abbiano uno username simile a tale parola o IMMAGINI che la contengano nel TITOLO o fra i TAGS.<br>
                    <br><br>
                    Queries per generare le tabelle:<br>
                    <br>
                    CREATE TABLE comments(
                        commentId int(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                        usrId int(9) NOT NULL,
                        imgId int(9) NOT NULL,
                        commentText varchar(2000) NOT NULL,
                        commentDate DATETIME NOT NULL
                    );<br>
                    <br>
                    CREATE TABLE gallery(
                        imgId int(9) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                        usrId int(9),
                        imgName varchar(255) NOT NULL,
                        imgTitle varchar(100) NOT NULL,
                        imgDesc varchar(2000),
                        imgTags varchar(1000),
                        imgHidden tinyint(1) NOT NULL,
                        imgBlock tinyint(1) NOT NULL,
                        imgDate DATETIME NOT NULL
                    );<br>
                    <br>
                    CREATE TABLE keyvalues(
                        keyValue varchar(8) NOT NULL UNIQUE
                    );<br>
                    <br>
                    CREATE TABLE likes(
                        imgId int(9) NOT NULL,
                        usrId int(9) NOT NULL,
                        likeDate DATETIME NOT NULL
                    );<br>
                    <br>
                    CREATE TABLE profimage(
                        piId int(9) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                        usrId int(9) NOT NULL,
                        piIsSet tinyint(1) NOT NULL
                    );<br>
                    <br>
                    CREATE TABLE saved(
                        imgId int(9) NOT NULL,
                        usrId int(9) NOT NULL,
                        saveDate DATETIME NOT NULL
                    );<br>
                    <br>
                    CREATE TABLE users(
                        usrId int(9) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                        usrName varchar(20) NOT NULL,
                        usrMail varchar(320) NOT NULL,
                        usrPswd varchar(256) NOT NULL,
                        usrLvl int(1) NOT NULL,
                        usrBlock tinyint(1) NOT NULL,
                        usrDate DATETIME NOT NULL
                    );<br>
                    <br>
                    ACCOUNTS:<br>
                    Amministratore: username: "mario"; password: "mario"<br>
                    Moderatore: username: "luigi"; password: "luigi"<br>
                    Alcuni utenti base: (yoshi, yoshi), (toad, toad), (peach, peach)<br>
                </p>
            </div>

        </section>

        <footer>
            <?php include_once './php/elements/footer.php' ?>   <!-- include il codice del footer -->
        </footer>

    </body>
</html>
