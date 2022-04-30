<?php

session_start();
require_once './php/utils/db_conn_handler_script.php';
require_once './php/utils/functions_script.php';
include_once './php/utils/definitions.php';

if(isset($_SESSION["usrblock"]))
    if($_SESSION["usrblock"]==true) header("location: ./profile?user=".$_SESSION["usrname"]."&err=usrblocked");

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
        <link rel="apple-touch-icon" sizes="180x180" href="./resources/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./resources/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./resources/icons/favicon-16x16.png">
        <link rel="manifest" href="./resources/icons/site.webmanifest">
        <title>Imaged | Upload image</title>
    </head>

    <body>

        <header>
            <!-- Header bar vuota -->
        </header>

        <section id="page_main_section">

            <!-- MAIN SECTION -->

            <div class="upload_to_gallery">
                <h3><a href="./" class="login-signup-logo">imaged</a></h3>
                <form class="multi-function-form" action="./php/utils/upload_gallery_script.php" 
                            onsubmit="submit_img_gallery.disabled=true; return true;" method="POST" enctype="multipart/form-data">
                    <input class="text-box input-form" type="text" name="img_title" placeholder="Title...">
                    <input class="text-box input-form" type="text" name="img_desc" placeholder="Description...">
                    <input class="text-box input-form" type="text" name="img_tags" placeholder="Tags...">
                    <input type="checkbox" id="img_hidden" name="img_hidden" value="hidden">
                    <label id="img_is_hidden" for="img_hidden">Private image</label><br>
                    <input type="file" name="gallery_img" accept="image/jpeg, image/png" required>
                    <button class="button form-button site-font" type="submit" name="submit_img_gallery">POST</button>
                    <?php
                        include_once './php/elements/error_messages.php';
                    ?>
                </form>
            </div>

        </section>

        <footer>
            <!-- Footer vuoto -->
        </footer>

    </body>
</html>
