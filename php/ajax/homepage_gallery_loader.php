<?php
// scarica dal DB un certo numero di foto da mostrare nella galleria della homepage

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


$sql_gallery_img = "SELECT * 
                    FROM gallery INNER JOIN users ON gallery.usrId = users.usrId 
                    WHERE imgHidden=0 AND imgBlock=0 
                    ORDER BY imgDate DESC LIMIT $current_limit;";

// nota: non c'è bisogno di prepared statement, poiché i parametri sono garantiti essere interpretati come interi
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
    
    $currentImage = new Image();
    $currentImage->buildImage($entry, false);

    $isOwnImage=false;  // valore default (verrà cambiato se l'utente è l'autore dell'immagine)
    $likeCount=0;       // valore default (verrà cambiato in base ai likes dell'immagine)
    $isLiked=false;     // valore default (verrà cambiato se l'utente ha messo like all'immagine)
    $isSaved=false;     // valore default (verrà cambiato se l'utente ha salvato l'immagine)

    $imgid = $entry["imgId"];   // recupero l'ID dell'immagine corrente
    // se l'utente è loggato, controlla se è il proprietario dell'immagine, se ha like e se l'ha salvata
    if(isset($_SESSION["usrid"])) {
        $curr_user = $_SESSION["usrid"];
        if($curr_user == $entry["usrId"]) $isOwnImage=true;

        // Query che controlla se l'utente ha un like sull'immagine corrente
        $sql = "SELECT * 
                FROM likes
                WHERE usrId='$curr_user' AND imgId='$imgid';";
        $img_info_res = mysqli_query($conn, $sql);  // no stmt (param generati dal server)
        if(mysqli_num_rows($img_info_res) != 0) $isLiked=true;

        // Query che controlla se l'utente ha salvato l'immagine corrente
        $sql = "SELECT * 
                FROM saved 
                WHERE usrId='$curr_user' AND imgId='$imgid';";
        $img_info_res = mysqli_query($conn, $sql);  // no stmt (param generati dal server)
        if(mysqli_num_rows($img_info_res) != 0) $isSaved=true;
    }

    // Query che controlla il numero di likes dell'immagine corrente
    $sql = "SELECT count(*) as likeNum 
            FROM likes 
            WHERE imgId='$imgid';";
    $img_info_res = mysqli_query($conn, $sql);  // no stmt (param generati dal server)
    if(mysqli_num_rows($img_info_res) != 0) {
        $img_info = mysqli_fetch_assoc($img_info_res);
        $likeCount = $img_info["likeNum"];
    }
    // aggiorno le informazioni sull'immagine corrente
    $currentImage->fillImageData($isOwnImage, $likeCount, $isLiked, $isSaved);

    array_push($imgArray, $currentImage);
}
$result = new AjaxResponse($imgArray, false, 0, "");

// restituisce il risultato
echo json_encode($result);
exit();

?>