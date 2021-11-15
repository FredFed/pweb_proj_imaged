<?php

session_start();
if(!isset($_SESSION["usrid"])) {
    header("location: ./php/login?err=bad_login");
}
require_once './php/utils/db_conn_handler_script.php';
require_once './php/utils/functions_script.php';
require_once './php/utils/definitions.php';
$home_path = '.';   // percorso per la cartella principale del server
if(isset($_GET["id"])) sleep(3);

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
        <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
        <script src="./js/ajax/ajax_utils.js"></script>
        <script src="./js/prof_img_button_handler.js"></script>
        <script src="./js/searchbox_clear.js"></script>
        <script src="./js/navbar_scroll.js"></script>
        <script src="./js/loaders/profile_gallery_loader.js"></script>
        <title>Imaged</title>
    </head>
    <body>
        <header>
            <?php include_once './php/elements/navbar.php' ?>   <!-- include il codice della navbar -->
            <?php include_once './php/elements/profile_frame.php' ?>    <!-- include il codice del frame profilo -->
        </header>

        <section id="page_main_section">
            
                    <!-- MAIN DIV -->
            
            <div class="gallery-frame">
                <div class="gallery-section">
                    <div class="gallery-selector-frame">
                        <button id="public-gallery-selector" class="gallery-selector" type="button">
                            <i class='bx bx-world gallery-selector-icon'></i>
                            Public
                        </button>
                        <button id="private-gallery-selector" class="gallery-selector" type="button">
                            <i class='bx bxs-hide gallery-selector-icon'></i>
                            Hidden
                        </button>
                        <button id="saved-gallery-selector" class="gallery-selector" type="button">
                            <i class='bx bxs-bookmark-alt gallery-selector-icon'></i>
                            Saved
                        </button>
                    </div>
                    <div class="gallery-container">
                        <div class="gallery-delimiter-upper"></div>
                        <div id="public-gallery" class="gallery"></div>
                        <div id="private-gallery" class="gallery"></div>
                        <div id="saved-gallery" class="gallery"></div>
                    </div>
                </div>
            </div>
                    <!-- END MAIN DIV -->
        </section>
        
        <footer>
        <!-- FOOTER CONTENT -->
        </footer>

    </body>
</html>