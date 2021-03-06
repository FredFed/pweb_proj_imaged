<?php

session_start();
$_SESSION["prevurl"]=$_SERVER["REQUEST_URI"];
require_once './php/utils/db_conn_handler_script.php';
require_once './php/utils/functions_script.php';
require_once './php/utils/definitions.php';

// controllo se l'URL rappresenta un profilo valido
if(!isset($_GET["user"])) header("location: ./page_not_found"); // bad URL
else $profileName = $_GET["user"];

// controllo se il profilo appartiene all'utente loggato
$isOwnProfile = false;  // TRUE se l'utente è il proprietario del profilo, FALSE altrimenti
$priviledge = 0;    // 0: utente/guest, 1: moderatore, 2: admin
$profileBlock = false;
if(isset($_SESSION["usrid"])) {
    $usrid = $_SESSION["usrid"];
    $usrname = $_SESSION["usrname"];
    $priviledge = $_SESSION["usrlvl"];
    if($profileName == $usrname) {
        $isOwnProfile = true;
        $profileId = $usrid;
        $profileLvl = $_SESSION["usrlvl"];
        $profileBlock = $_SESSION["usrblock"];
    }
}

if($isOwnProfile !== true) {
    $sql = "SELECT * FROM users WHERE usrName = ? ;";  // comando SQL SELECT
    $stmt = mysqli_stmt_init($conn);    // creo un prepared statement
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?err=db_err");   // DB error
    }
    mysqli_stmt_bind_param($stmt, "s", $profileName); // binding tra username e statement
    mysqli_stmt_execute($stmt); // eseguo lo statement
    $user_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)); // recupero risultati query
    mysqli_stmt_close($stmt);
    if(!$user_data) header("location: ./page_not_found");   // l'utente non esiste
    else {
        $profileId = $user_data["usrId"];
        $profileLvl = $user_data["usrLvl"];
        $profileBlock = $user_data["usrBlock"];
    }
}

if($profileLvl == 0) $badge = "";  // l'utente base/guest non ha alcun badge
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
        <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
        <script src="./js/ajax/ajax_utils.js"></script>
        <script src="./js/loaders/likes_saves_loader.js"></script>
        <script src="./js/loaders/profile_gallery_loader.js"></script>
        <script src="./js/loaders/user_block_loader.js"></script>
        <script src="./js/loaders/user_mod_loader.js"></script>
        <?php if($isOwnProfile === true) echo "<script src='./js/prof_img_button_handler.js'></script>"; ?>
        <script src="./js/searchbox_clear.js"></script>
        <script src="./js/navbar_interaction.js"></script>
        <link rel="apple-touch-icon" sizes="180x180" href="./resources/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./resources/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./resources/icons/favicon-16x16.png">
        <link rel="manifest" href="./resources/icons/site.webmanifest">
        <title>Imaged | <?php echo ucfirst($profileName); ?></title>
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
                    <?php include_once './php/elements/profile_gallery.php'; ?>
                </div>
            </div>
                    <!-- END MAIN SECTION -->
        </section>
        
        <footer>
            <?php include_once './php/elements/footer.php' ?>   <!-- include il codice del footer -->
        </footer>

    </body>
</html>