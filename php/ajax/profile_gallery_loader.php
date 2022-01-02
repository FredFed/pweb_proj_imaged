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
$galleryUser = $galleryInfo["user"];
$galleryType = $galleryInfo["type"];
$galleryCount = intval($galleryInfo["count"]);
$galleryIncrement = intval($galleryInfo["increment"]);
$current_limit = $galleryCount + $galleryIncrement;


// recupero l'ID corrispondente al nome profilo fornito in modo sicuro (usando prepared statements)
$sql = "SELECT * FROM users WHERE usrName = ? ;";  // comando SQL SELECT
$stmt = mysqli_stmt_init($conn);    // creo un prepared statement
if(!mysqli_stmt_prepare($stmt, $sql)) {
    $result = new AjaxResponse(null, false, -1, "server error: retrieving userID");
    echo json_encode($result);
    exit();
}
mysqli_stmt_bind_param($stmt, "s", $galleryUser); // binding tra username e statement
mysqli_stmt_execute($stmt); // eseguo lo statement
$user_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)); // recupero risultati query
if(!$user_data) {
    $result = new AjaxResponse(null, false, -1, "server error: retrieving userID");
    echo json_encode($result);
    exit();
}
$usrid = $user_data["usrId"];   // ho recuperato l'ID dell'utente
$isOwnGallery = false;  // specifica se la galleria per cui si stanno recuperando le immagini è la propria o di un altro profilo
if(isset($_SESSION["usrid"]))
    if($_SESSION["usrid"] == $usrid) $isOwnGallery = true;


if($galleryType == "public-gallery") {     // immagini per la galleria pubblica
    $sql_gallery_img = "SELECT * FROM gallery WHERE usrId='$usrid' AND imgHidden=0 AND imgBlock=0 ORDER BY imgDate DESC LIMIT $current_limit;";
}
else if($galleryType == "private-gallery") {   // immagini per la galleria privata
    $sql_gallery_img = "SELECT * FROM gallery WHERE usrId='$usrid' AND imgHidden=1 AND imgBlock=0 ORDER BY imgDate DESC LIMIT $current_limit;";
}
else if($galleryType == "saved-gallery") {     // immagini per gli elementi salvati
    $sql_gallery_img = "SELECT * FROM gallery INNER JOIN saved ON gallery.imgId=saved.imgId 
        WHERE saverId='$usrid' AND imgHidden=0 AND imgBlock=0 ORDER BY savingDate DESC LIMIT $current_limit;";
}
else if($galleryType == "blocked-gallery") {   // immagini attualmente bloccate
    $sql_gallery_img = "SELECT * FROM gallery WHERE usrId='$usrid' AND imgBlock=0 ORDER BY imgDate DESC LIMIT $current_limit;";

}
else {  // parametro non valido
    $result = new AjaxResponse(null, $isOwnGallery, -1, "client error: invalid parameter");
    echo json_encode($result);
    exit();
}

// nota: non c'è bisogno di prepared statement, poiché i parametri sono generati dal server stesso
$gallery_img_res = mysqli_query($conn, $sql_gallery_img);

if(mysqli_num_rows($gallery_img_res) == 0) {   // galleria vuota
    $result = new AjaxResponse(null, $isOwnGallery, -1, "empty gallery");
    echo json_encode($result);
    exit();
}

// recupero i risultati della query
$imgArray = array();
for($i=0; ($entry = mysqli_fetch_assoc($gallery_img_res)); $i++) {
    if($i<$galleryCount) continue;
    $currentImage = new Image();
    $currentImage->buildImage($entry, true);
    array_push($imgArray, $currentImage);
}
$result = new AjaxResponse($imgArray, $isOwnGallery, 0, "");

// restituisce il risultato
echo json_encode($result);
exit();

?>