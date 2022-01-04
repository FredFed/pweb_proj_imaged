<?php
// aggiunge nel DB l'informazione sul save

// includo la connessione al DB ed alcune funzioni di controllo/utilità
require_once '../utils/db_conn_handler_script.php';
require_once './ajax_classes.php';


session_start();

if(!isset($_SESSION["usrid"])) {    // se l'utente non è loggato, lo rimando al login
    $result = new AjaxResponse(null, false, -2, "error: user not logged");
    echo json_encode($result);
    exit();
}

$usrid = $_SESSION["usrid"];   // recupero l'ID utente
$imageInfo = json_decode(file_get_contents('php://input'), true);

if(!$imageInfo) {    // dato in ingresso non valido
    $result = new AjaxResponse(null, false, -1, "server error: decoding json");
    echo json_encode($result);
    exit();
}

// scorciatoie per i dati in ingresso
$imageId = $imageInfo["imageId"];
$imageAction = $imageInfo["imageAction"];

if($imageAction == "save") {
    $sql = "INSERT INTO saved (usrId, imgId, likeDate) VALUES ('$usrid', ?, now());";
    $stmt = mysqli_stmt_init($conn);    // creo un prepared statement
    // preparo lo statement; se la preparazione è fallita, restituisce errore DB
    if(!mysqli_stmt_prepare($stmt, $sql)) {    // errore DB
        $result = new AjaxResponse(null, false, -1, "server error: DB err");
        echo json_encode($result);
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $imageId);   // effettuo il binding fra statement e ID immagine
    mysqli_stmt_execute($stmt); // eseguo lo statement
    mysqli_stmt_close($stmt);   // chiudo lo statement
}
else if($imageAction == "unsave") {
    sleep(3);
    $sql = "DELETE FROM saved WHERE usrId='$usrid' AND imgId=?;";
    $stmt = mysqli_stmt_init($conn);    // creo un prepared statement
    // preparo lo statement; se la preparazione è fallita, restituisce errore DB
    if(!mysqli_stmt_prepare($stmt, $sql)) {    // errore DB
        $result = new AjaxResponse(null, false, -1, "server error: DB err");
        echo json_encode($result);
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $imageId);   // effettuo il binding fra statement e ID immagine
    mysqli_stmt_execute($stmt); // eseguo lo statement
    mysqli_stmt_close($stmt);   // chiudo lo statement
}
else {    // azione non valida
    $result = new AjaxResponse(null, false, -1, "server error: invalid action");
    echo json_encode($result);
    exit();
}

// restituisci codice di successo
$result = new AjaxResponse(null, null, 0, "");
echo json_encode($result);
exit();

?>