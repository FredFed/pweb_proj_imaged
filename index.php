<?php

session_start();
$_SESSION["prevurl"]=$_SERVER["REQUEST_URI"];
require_once './php/utils/db_conn_handler_script.php';
require_once './php/utils/functions_script.php';
include_once './php/utils/definitions.php';

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
        <script src="./js/ajax/ajax_utils.js"></script>
        <script src="./js/loaders/likes_saves_loader.js"></script>
        <script src="./js/loaders/homepage_gallery_loader.js"></script>
        <script src="./js/searchbox_clear.js"></script>
        <script src="./js/navbar_interaction.js"></script>
        <link rel="apple-touch-icon" sizes="180x180" href="./resources/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./resources/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./resources/icons/favicon-16x16.png">
        <link rel="manifest" href="./resources/icons/site.webmanifest">
        <title>Imaged | Home</title>
    </head>

    <body>
        <header>
            <?php include_once './php/elements/navbar.php' ?>   <!-- include il codice della navbar -->
        </header>

        <section id="page_main_section">

            <!-- MAIN SECTION -->

            <div class="gallery-frame">
                <div class="gallery-section">
                    <!-- include il codice della galleria da mostrare -->
                    <?php include_once './php/elements/homepage_gallery.php'; ?>
                </div>
            </div>

        </section>

        <footer>
            <?php include_once './php/elements/footer.php' ?>   <!-- include il codice del footer -->
        </footer>

    </body>
</html>
