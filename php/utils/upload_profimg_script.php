<?php

session_start();
if(!isset($_SESSION["usrid"])) {
    header("location: ../login?err=bad_login");
    exit();
}
require_once './db_conn_handler_script.php';
require_once './functions_script.php';
require_once './definitions.php';

// controlla che l'utente sia arrivato qui cliccando il pulsante del cambio immagine
if(isset($_POST['submit_prof_img'])) {

    // recupero le informazioni sul file
    $file = $_FILES["prof_img"];
    $file_name = $file["name"];
    $file_tmp_name = $file["tmp_name"];
    $file_size = $file["size"];
    $file_error = $file["error"];
    $file_type = $file["type"];

    // recupero l'estensione
    $file_ext = get_ext($file_name);

    $usrname = $_SESSION["usrname"];    // recupero il nome utente


    // ########## GESTIONE ERRORI ##########

    // se l'estensione non è consentita, restituisci errore
    if(in_array($file_ext, $allowed_ext) === false) {
        header("location: ../../profile?usr=".$usrname."&err=bad_ext");
        exit();
    }

    // se c'è stato un errore nel caricamente dell'immagine, restituisci errore
    if($file_error === true) {
        header("location: ../../profile?usr=".$usrname."&err=up_err");
        exit();
    }

    // se la dimensione è maggiore di quella consentita, restituisci errore
    if($file_size > $MAX_IMG_SIZE) {
        header("location: ../../profile?usr=".$usrname."&err=sz_2_lg");
        exit();
    }


    // ########## UPLOAD IMMAGINE ##########

    // recupero l'id utente per associarlo alla nuova immagine profilo
    $usrid = $_SESSION["usrid"];

    // rinomino il file con un identificatore unico
    $image_name = "profile".$usrid.".".$file_ext;

    // specifico un percorso finale per il file
    $image_path = '../../resources/profileimg/'.$image_name;

    // elimino la vecchia immagine di profilo eventualmente presente
    if(!delete_prof_img($conn)) header("location: ../../profile?usr=".$usrname."&err=no_repl");

    // comunico al DB che l'utente ha impostato un'immagine del profilo
    update_prof_img($conn);

    // salvo il file all'interno del percorso
    move_uploaded_file($file_tmp_name, $image_path);

    // ridimensiono l'immagine per adattarla alla risoluzione pro-pic
    $temperr=square_image($image_path, $image_path, $file_ext);
    if($temperr!="success") {   // se il processo non è andato a buon fine...
        unlink($image_path);    // rimuove l'immagine appena salvata
        header("location: ../../profile?usr=".$usrname."&".$temperr);    // reindirizza + mostra errore
        exit();
    } 

    // il file è stato caricato; reindirizzo l'utente
    header("location: ../../profile?usr=".$usrname."&up_pimg=success");
    exit();
}
// altrimenti reindirizzalo al suo profilo
else {
    header("location: ../../profile?usr=".$usrname);
    exit();
}
?>