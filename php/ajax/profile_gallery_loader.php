<?php
// scarica dal DB un certo numero di foto da mostrare nella galleria del profilo

session_start();
if(!isset($_SESSION["usr"])) {
    header("location: ../login?err=bad_login");
}

// includo la connessione al DB ed alcune funzioni di controllo/utilità
require_once '../utils/db_conn_handler_script.php';
require_once '../utils/functions_script.php';
require_once './ajax_classes.php';

$usrid = $_SESSION["usrid"];    // recupero il nome utente
$galleryInfo = json_decode(file_get_contents('php://input'), true);

if(!$galleryInfo) {    // tipo galleria non valido
    $result = new AjaxResponse(null, -1, "server error: decoding json");
    echo json_encode($result);
    exit();
}

// scorciatoie per i campi dell'oggetto JSON ricevuto
$galleryType = $galleryInfo["type"];
$galleryIncrement = intval($galleryInfo["increment"]);

if($galleryType == "public-gallery") {     // immagini per la galleria pubblica
    $sql_gallery_img = "SELECT * FROM gallery WHERE usrId='$usrid' ORDER BY imgDate DESC;";
}
else if($galleryType == "private-gallery") {   // immagini per la galleria privata
    $sql_gallery_img = "SELECT * FROM gallery WHERE usrId='$usrid' AND imgHidden=1 AND imgBlock=0 ORDER BY imgDate DESC LIMIT '$galleryIncrement';";
}
else if($galleryType == "saved-gallery") {     // immagini per gli elementi salvati

}
else if($galleryType == "blocked-gallery") {   // immagini attualmente bloccate
    $sql_gallery_img = "SELECT * FROM gallery WHERE usrId='$usrid' AND imgBlock=0 ORDER BY imgDate DESC LIMIT '$galleryIncrement';";

}
else {  // parametro non valido
    $result = new AjaxResponse(null, -1, "client error: invalid parameter");
    echo json_encode($result);
    exit();
}

// nota: non c'è bisogno di prepared statement, poiché i parametri sono generati dal server stesso
$gallery_img_res = mysqli_query($conn, $sql_gallery_img);

if(empty($gallery_img_res)) {   // galleria vuota
    $result = new AjaxResponse(null, -1, "empty gallery");
    echo json_encode($result);
    exit();
}

// recupero i risultati della query
$imgArray = array();
for($i=0; ($entry = mysqli_fetch_assoc($gallery_img_res)); $i++) {
    if($i<$galleryIncrement) continue;
    $currentImage = new Image();
    $currentImage->buildImage($entry);
    array_push($imgArray, $currentImage);
}

while($entry = mysqli_fetch_assoc($gallery_img_res)) {
    $currentImage = new Image();
    $currentImage->buildImage($entry);
    array_push($imgArray, $currentImage);
}
$result = new AjaxResponse($imgArray, 0, "");

// restituisce il risultato
echo json_encode($result);
exit();

?>