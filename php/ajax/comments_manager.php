<?php
// permette all'utente di inserire un commento

// includo la connessione al DB ed alcune funzioni di controllo/utilità
require_once '../utils/db_conn_handler_script.php';
require_once '../utils/functions_script.php';
require_once '../utils/definitions.php';
require_once './ajax_classes.php';

session_start();
if(!isset($_SESSION["usrid"])) {    // se l'utente non è loggato, lo rimando al login
    $result = new AjaxResponse(null, false, -2, "error: user not logged");
    echo json_encode($result);
    exit();
}

$usrId = $_SESSION["usrid"];

$commentInfo = json_decode(file_get_contents('php://input'), true);

if(!$commentInfo) {    // commento non valido
    $result = new AjaxResponse(null, false, -1, "server error: decoding json");
    echo json_encode($result);
    exit();
}

// scorciatoie per i campi dell'oggetto
$commentText = $commentInfo["commentText"];
$imageId = intval($commentInfo["imageId"]);
$dateObj = new DateTime('now');
$date = date_format($dateObj, 'Y-m-d H:i:s');

// inserisco il commento nel DB
$sql = "INSERT INTO comments (usrId, imgId, commentText, commentDate) VALUES ('$usrId', ?, ?, '$date');";
$stmt = mysqli_stmt_init($conn);    // creo un prepared statement
// preparo lo statement; se la preparazione è fallita, restituisce errore DB
if(!mysqli_stmt_prepare($stmt, $sql)) {    // errore DB
    $result = new AjaxResponse(null, false, -1, "server error: DB err");
    echo json_encode($result);
    exit();
}

mysqli_stmt_bind_param($stmt, "is", $imageId, $commentText);   // effettuo il binding fra statement e ID immagine
mysqli_stmt_execute($stmt); // eseguo lo statement
mysqli_stmt_close($stmt);   // chiudo lo statement


// recupero i dati da restituire per aggiungere il commento alla pagina
$select_sql = "SELECT * FROM users INNER JOIN profimage ON users.usrId = profimage.usrId WHERE users.usrId=$usrId;";
$row = mysqli_fetch_assoc(mysqli_query($conn, $select_sql));

// creo un nuovo oggetto commento
$currentComment = new Comment();

$authorName = $row["usrName"];
$authorPImg="";

// recupero il path per l'immagine profilo
if(($row["piIsSet"])==true) {
    // recupero il nome dell'immagine del profilo
    $filename = "../../resources/users/".$usrId."/profile".$usrId."*";
    // recupero l'estensione prendendo il primo match della funzione "glob" e tenendo la parte finale
    $file_meta = glob($filename);
    $ext = get_ext($file_meta[0]);  // recupero l'estensione del file (il primo match)

    $authorPImg = "./resources/users/".$usrId."/profile".$usrId.".".$ext;
}
else $authorPImg = $DFLT_PROF_IMG;

// costruisco l'oggetto risposta
$currentComment->buildResult($authorName, $authorPImg, $commentText, $date);


// recupero il numero totale di commenti
$numComments = 0;
$sql = "SELECT count(*) AS numComments FROM comments WHERE imgId='$imageId';";
$num_comment_res = mysqli_query($conn, $sql);
if(mysqli_num_rows($num_comment_res) != 0) {
    $row = mysqli_fetch_assoc($num_comment_res);
    $numComments = $row["numComments"];
}


// restituisco codice di successo
$result = new AjaxResponse($currentComment, $numComments, 0, "");
echo json_encode($result);
exit();

?>