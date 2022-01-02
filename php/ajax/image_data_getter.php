<?php
// restituisce informazioni su likes e preferiti per l'immagine corrente

session_start();

// includo la connessione al DB ed alcune funzioni di controllo/utilità
require_once '../utils/db_conn_handler_script.php';
require_once '../utils/functions_script.php';
require_once './ajax_classes.php';

// se l'ID dell'immagine manca, restituisci errore
if(!isset($_GET["id"])) {
    $result = new AjaxResponse(null, false, -1, "error: image id missing");
    echo json_encode($result);
    exit();
}
$imgid = $_GET["id"];   // salvo l'ID dell'immagine

$usrid = null;      // se l'utente non è loggato
$isLogged=false;    // valore default (verrà cambiato se l'utente è loggato)
$isOwnImage=false;  // valore default (verrà cambiato se l'utente è l'autore dell'immagine)
$likeCount=0;       // valore default (verrà cambiato in base ai likes dell'immagine)
$isLiked=false;     // valore default (verrà cambiato se l'utente ha messo like all'immagine)
$isSaved=false;     // valore default (verrà cambiato se l'utente ha salvato l'immagine)

// se l'utente è loggato, recupera il suo ID
if(isset($_SESSION["usrid"])) {
    $usrid = $_SESSION["usrid"];
    $isLogged=true;
}

if($usrid != null) {    // se l'utente è loggato, controlla se ha un like sull'immagine e/o l'ha salvata
    // comando SQL SELECT per osservare se l'utente ha già un like sull'immagine
    $sql = "SELECT * 
            FROM liked
            WHERE usrId='$usrid' AND imgId=?;";
    $stmt = mysqli_stmt_init($conn);    // creo un prepared statement
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        $result = new AjaxResponse(null, false, -1, "server error: retrieving user like");
        echo json_encode($result);
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $imgid); // binding tra imgid e statement
    mysqli_stmt_execute($stmt); // eseguo lo statement
    $image_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)); // recupero risultati query
    if($image_data) $isLiked=true;

    // comando SQL SELECT per osservare se l'utente ha già salvato l'immagine
    $sql = "SELECT * 
            FROM saved
            WHERE usrId='$usrid' AND imgId=?;";
    $stmt = mysqli_stmt_init($conn);    // creo un prepared statement
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        $result = new AjaxResponse(null, false, -1, "server error: retrieving user save");
        echo json_encode($result);
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $imgid); // binding tra imgid e statement
    mysqli_stmt_execute($stmt); // eseguo lo statement
    $image_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)); // recupero risultati query
    if($image_data) $isSaved=true;

    // comando SQL SELECT per osservare se l'utente loggato è il proprietario dell'immagine
    $sql = "SELECT * 
            FROM gallery
            WHERE usrId='$usrid' AND imgId=?;";
    $stmt = mysqli_stmt_init($conn);    // creo un prepared statement
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        $result = new AjaxResponse(null, false, -1, "server error: retrieving image author");
        echo json_encode($result);
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $imgid); // binding tra imgid e statement
    mysqli_stmt_execute($stmt); // eseguo lo statement
    $image_author = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)); // recupero risultati query
    if($image_author) $isOwnImage=true; // se ho un risultato, l'utente è il proprietario dell'immagine
}

// comando SQL SELECT per recuperare il numero di likes dell'immagine
$sql = "SELECT count(*) as likeNum 
FROM liked
WHERE imgId=?;";
$stmt = mysqli_stmt_init($conn);    // creo un prepared statement
if(!mysqli_stmt_prepare($stmt, $sql)) {
$result = new AjaxResponse(null, false, -1, "server error: retrieving like number");
echo json_encode($result);
exit();
}
mysqli_stmt_bind_param($stmt, "s", $imgid); // binding tra imgid e statement
mysqli_stmt_execute($stmt); // eseguo lo statement
$image_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)); // recupero risultati query
if(!$image_data) {
    $result = new AjaxResponse(null, false, -1, "server error: retrieving like number");
    echo json_encode($result);
    exit();
}
$likeCount = $image_data["likeNum"];

// restituisco i risultati
$image_data = new ImageInteraction();
$image_data->buildResult($isLogged, $isOwnImage, $likeCount, $isLiked, $isSaved);
$result = new AjaxResponse($image_data, null, 0, "");
echo json_encode($result);  // restituisco il risultato
exit();

?>