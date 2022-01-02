<?php

session_start();
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
        <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
        <script src="./js/ajax/ajax_utils.js"></script>
        <script src="./js/loaders/homepage_gallery_loader.js"></script>
        <script src="./js/searchbox_clear.js"></script>
        <script src="./js/navbar_scroll.js"></script>
        <title>Imaged</title>
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
