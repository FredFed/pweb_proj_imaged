<?php

session_start();
require_once './db_conn_handler_script.php';
require_once './functions_script.php';
include_once './definitions.php';

// controlla che l'utente sia arrivato qui cliccando il pulsante
if(isset($_POST["del_prof_img"])) {

    // recupero lo usrid dell'utente
    $usrid = $_SESSION["usrid"];

    // recupero il nome dell'immagine del profilo
    $filename = "../../resources/profileimg/profile".$usrid."*";

    // recupero l'estensione prendendo il primo match della funzione "glob" e tenendo la parte finale
    $file_meta = glob($filename);
    $file_get_ext = explode(".", $file_meta[0]);    // il primo match della ricerca glob è il file corretto
    $file_ext = end($file_get_ext);   // prendiamo il token dopo il '.', ovvero l'estensione

    // costruisco il path completo
    $file_path =  "../../resources/profileimg/profile".$usrid.".".$file_ext;

    if(!unlink($file_path)) header("location: ../../profile?err=del_pimg_fail");
    else delete_prof_img($conn, $usrid);    // setto l'entry del DB di immagini profilo a 0 per questo utente

    header("location: ../../profile?del_pimg=success");
    exit();
}
// altrimenti reindirizzalo al suo profilo
else {
    header("location: ../../profile");
    exit();
}

?>