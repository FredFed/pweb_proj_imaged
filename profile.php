<?php

session_start();
require_once './php/utils/db_conn_handler_script.php';
require_once './php/utils/functions_script.php';
require_once './php/utils/definitions.php';

// controllo se l'URL rappresenta un profilo valido
if(!isset($_GET["user"])) header("location: ./php/page_not_found"); // bad URL
else $profileName = $_GET["user"];

// controllo se il profilo appartiene all'utente loggato
$isOwnProfile = false;
if(isset($_SESSION["usrid"])) {
    $usrid = $_SESSION["usrid"];
    $usrname = $_SESSION["usrname"];
    if($profileName == $usrname) {
        $isOwnProfile = true;
        $profileId = $usrid;
        $profileLvl = $_SESSION["usrlvl"];
    }
}

if($isOwnProfile !== true) {
    $sql = "SELECT * FROM users WHERE usrName = ? ;";  // comando SQL SELECT
    $stmt = mysqli_stmt_init($conn);    // creo un prepared statement
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./php/page_not_found");   // DB error
    }
    mysqli_stmt_bind_param($stmt, "s", $profileName); // binding tra username e statement
    mysqli_stmt_execute($stmt); // eseguo lo statement
    $user_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)); // recupero risultati query
    if(!$user_data) header("location: ./php/page_not_found");   // l'utente non esiste
    else {
        $profileId = $user_data["usrId"];
        $profileLvl = $user_data["usrLvl"];
    }
}

if($profileLvl == 0) $badge = "";  // l'utente base non ha alcun badge
else if($profileLvl == 1) $badge = "<i class='bx bx-crown lvl-icon'></i>";   // badge moderatore
else if($profileLvl == 2) $badge = "<i class='bx bxs-crown lvl-icon'></i>";  // badge amministratore

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
        <script src="./js/prof_img_button_handler.js"></script>
        <script src="./js/searchbox_clear.js"></script>
        <script src="./js/navbar_scroll.js"></script>
        <script src="./js/loaders/profile_gallery_loader.js"></script>
        <title>Imaged</title>
    </head>
    <body>
        <header>
            <?php
                include_once './php/elements/navbar.php';   // include il codice della navbar
                include_once './php/elements/profile_frame.php';    // include il codice del frame profilo
            ?>
        </header>

        <section id="page_main_section">
            
                    <!-- MAIN SECTION -->
            
            <div class="gallery-frame">
                <div class="gallery-section">
                    <!-- include il codice della galleria da mostrare -->
                    <?php
                        if($isOwnProfile === true) include_once './php/elements/personal_gallery.php';
                        else include_once './php/elements/guest_gallery.php';
                    ?>
                </div>
            </div>
                    <!-- END MAIN DIV -->
        </section>
        
        <footer>
            <?php include_once './php/elements/footer.php' ?>   <!-- include il codice del footer -->
        </footer>

    </body>
</html>