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
        <title>Imaged</title>
    </head>

    <body>

        <header>
            <!-- Header bar vuota -->
        </header>

        <section id="page_main_section">

            <!-- MAIN DIV -->

            <div class="upload_to_gallery">
                <form action="./php/utils/upload_gallery_script.php" method="POST" enctype="multipart/form-data">
                    <input type="text" name="img_title" placeholder="Title...">
                    <input type="text" name="img_desc" placeholder="Description...">
                    <input type="text" name="img_tags" placeholder="Tags...">
                    <input type="checkbox" name="img_ls" value="landscape">
                    <label id="img_is_landscape" for="img_ls">Landscape mode</label></br>
                    <input type="checkbox" name="img_hidden" value="hidden">
                    <label id="img_is_hidden" for="img_hidden">Private image</label></br>
                    <input type="file" name="gallery_img" accept="image/jpeg, image/png" required>
                    <button type="submit" name="submit_img_gallery">POST</button>
                </form>
            </div>

        </section>

        <footer>
            <!-- Footer vuoto -->
        </footer>

    </body>
</html>
