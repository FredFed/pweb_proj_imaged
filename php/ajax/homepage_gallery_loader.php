<?php
// scarica dal DB un certo numero di foto da mostrare nella galleria del profilo

session_start();

// includo la connessione al DB ed alcune funzioni di controllo/utilità
require_once '../utils/db_conn_handler_script.php';
require_once '../utils/functions_script.php';
require_once './ajax_classes.php';

$galleryInfo = json_decode(file_get_contents('php://input'), true);

if(!$galleryInfo) {    // tipo galleria non valido
    $result = new AjaxResponse(null, false, -1, "server error: decoding json");
    echo json_encode($result);
    exit();
}

// scorciatoie per i campi dell'oggetto JSON ricevuto
$galleryCount = intval($galleryInfo["count"]);
$galleryIncrement = intval($galleryInfo["increment"]);
$current_limit = $galleryCount + $galleryIncrement;


$sql_gallery_img = "SELECT * FROM gallery WHERE imgHidden=0 AND imgBlock=0 ORDER BY imgDate DESC LIMIT $current_limit;";

// nota: non c'è bisogno di prepared statement, poiché i parametri sono generati dal server stesso
$gallery_img_res = mysqli_query($conn, $sql_gallery_img);

if(mysqli_num_rows($gallery_img_res) == 0) {   // galleria vuota
    $result = new AjaxResponse(null, false, -1, "empty gallery");
    echo json_encode($result);
    exit();
}

// recupero i risultati della query
$imgArray = array();
for($i=0; ($entry = mysqli_fetch_assoc($gallery_img_res)); $i++) {
    if($i<$galleryCount) continue;
    else {
        $currentImage = new Image();
        $currentImage->buildImage($entry);
        array_push($imgArray, $currentImage);
    }
}
$result = new AjaxResponse($imgArray, false, 0, "");

// restituisce il risultato
echo json_encode($result);
exit();

?>