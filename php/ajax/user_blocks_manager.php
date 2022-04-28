<?php
// aggiungo nel DB l'informazione sul blocco

// includo la connessione al DB ed alcune funzioni di controllo/utilità
require_once '../utils/db_conn_handler_script.php';
require_once './ajax_classes.php';

session_start();

if(!isset($_SESSION["usrid"])) {    // se l'utente non è loggato, lo rimando al login
    $result = new AjaxResponse(null, false, -2, "error: user not logged");
    echo json_encode($result);
    exit();
}
else if($_SESSION["usrlvl"]<2) {   // se l'utente non ha permessi, restituisce errore
    $result = new AjaxResponse(null, false, -3, "error: forbidden");
    echo json_encode($result);
    exit();
}

$request = json_decode(file_get_contents('php://input'), true);
if(!$request) {    // dato in ingresso non valido
    $result = new AjaxResponse(null, false, -1, "server error: decoding json");
    echo json_encode($result);
    exit();
}

// scorciatoie per i dati in ingresso
$action = intval($request["action"]);
$username = $request["username"];

// query di aggiornamento parametrica
$sql = "UPDATE users SET usrBlock=? AND usrLvl=0 WHERE usrName=?;";
$stmt = mysqli_stmt_init($conn);    // creo un prepared statement
// preparo lo statement; se la preparazione è fallita, restituisce errore DB
if(!mysqli_stmt_prepare($stmt, $sql)) {    // errore DB
    $result = new AjaxResponse(null, false, -1, "server error: DB err");
    echo json_encode($result);
    exit();
}
mysqli_stmt_bind_param($stmt, "is", $action, $username);   // effettuo il binding fra statement e parametri
mysqli_stmt_execute($stmt); // eseguo lo statement
mysqli_stmt_close($stmt);   // chiudo lo statement

// restituisci codice di successo
$result = new AjaxResponse(true, null, 0, "");
echo json_encode($result);
exit();

?>