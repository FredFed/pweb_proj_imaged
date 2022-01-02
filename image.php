<?php

session_start();
require_once './php/utils/db_conn_handler_script.php';
require_once './php/utils/functions_script.php';
include_once './php/utils/definitions.php';

// controllo se l'URL rappresenta un'immagine valida
if(!isset($_GET["id"])) header("location: ./php/page_not_found");   // bad URL
else $imgKey = $_GET["id"].".%";

$sql = "SELECT * FROM gallery WHERE imgName LIKE ? AND imgBlock=0 AND imgHidden=0;";
$stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ./?err=db_err");   // DB error
}
mysqli_stmt_bind_param($stmt, "s", $imgKey); // binding tra chiave immagine e statement
mysqli_stmt_execute($stmt);     // eseguo lo statement
$image_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));    // recupero i risultati della query
if(!$image_data) header("location: ./php/page_not_found");  // l'immagine non esiste
else {
    $imgAuthor = $image_data["usrId"];
    $imgId = $image_data["imgId"];
    $imgName = $image_data["imgName"];
    $imgTitle = $image_data["imgTitle"];
    $imgDesc = $image_data["imgDesc"];
    $imgTags = $image_data["imgTags"];
    $imgBlock = $image_data["imgBlock"];
    $imgHidden = $image_data["imgHidden"];
    $imgDate = $image_data["imgDate"];
}

// recupero l'id dell'utente (se loggato)
if(isset($_SESSION["usrid"])) $usrid = $_SESSION["usrid"];
else $usrid = null;

// se l'immagine è bloccata/nascosta e l'utente non è il proprietario, mostra 404
if(($imgBlock==true || $imgHidden==true) && $usrid!=$imgAuthor) header("location: ./php/page_not_found");

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

            <div class="image-container">
                <?php echo "<img src='./resources/users/".$imgAuthor."/gallery/".$imgName."' alt=''>"; ?>
            </div>

        </section>

        <footer>
            <?php include_once './php/elements/footer.php' ?>   <!-- include il codice del footer -->
        </footer>

    </body>
</html>
