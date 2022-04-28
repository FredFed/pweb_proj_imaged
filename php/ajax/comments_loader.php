<?php
// scarica dal DB un certo numero di commenti da mostrare nella pagina dell'immagine

session_start();

// includo la connessione al DB ed alcune funzioni di controllo/utilità
require_once '../utils/db_conn_handler_script.php';
require_once '../utils/functions_script.php';
require_once './ajax_classes.php';
require_once '../utils/definitions.php';

$commentInfo = json_decode(file_get_contents('php://input'), true);

if(!$commentInfo) {    // dato non valido
    $result = new AjaxResponse(null, false, -1, "server error: decoding json");
    echo json_encode($result);
    exit();
}

// scorciatoie per i campi dell'oggetto JSON ricevuto
$counter = intval($commentInfo["counter"]);
$increment = intval($commentInfo["increment"]);
$imageId = intval($commentInfo["imageId"]);
$current_limit = $counter + $increment;

$sql = "SELECT *
        FROM comments INNER JOIN users ON comments.usrId = users.usrId INNER JOIN profimage ON users.usrId = profimage.usrId
        WHERE imgId=$imageId
        ORDER BY commentDate DESC LIMIT $current_limit;";

// non c'è bisogno di prepared statements, in quanto i campi sono garantiti essere interi
$comment_res = mysqli_query($conn, $sql);

if(mysqli_num_rows($comment_res) == 0) {   // nessun commento
    $result = new AjaxResponse(null, false, -1, "empty");
    echo json_encode($result);
    exit();
}

// recupero i risultati della query
$commentArray = array();
for($i=0; ($entry=mysqli_fetch_assoc($comment_res)); $i++) {
    if($i<$counter) continue;

    // creo un nuovo oggetto commento
    $currentComment = new Comment();

    $usrid = $entry["usrId"];
    $authorName = $entry["usrName"];
    $authorPImg="";

    // recupero il path per l'immagine profilo
    if(($entry["piIsSet"])==true) {
        // recupero il nome dell'immagine del profilo
        $filename = "../../resources/users/".$usrid."/profile".$usrid."*";
        // recupero l'estensione prendendo il primo match della funzione "glob" e tenendo la parte finale
        $file_meta = glob($filename);
        $ext = get_ext($file_meta[0]);  // recupero l'estensione del file (il primo match)

        $authorPImg = "./resources/users/".$usrid."/profile".$usrid.".".$ext;
    }
    else $authorPImg = $DFLT_PROF_IMG;

    $commentText = $entry["commentText"];
    $commentDate = $entry["commentDate"];

    // costruisco l'oggetto risposta
    $currentComment->buildResult($authorName, $authorPImg, $commentText, $commentDate);

    array_push($commentArray, $currentComment);
}

// recupero il numero totale di commenti
$numComments = 0;
$sql = "SELECT count(*) AS numComments FROM comments WHERE imgId='$imageId';";
$num_comment_res = mysqli_query($conn, $sql);
if(mysqli_num_rows($num_comment_res) != 0) {
    $row = mysqli_fetch_assoc($num_comment_res);
    $numComments = $row["numComments"];
}

$result = new AjaxResponse($commentArray, $numComments, 0, "");

// restituisco il risultato
echo json_encode($result);
exit();

?>