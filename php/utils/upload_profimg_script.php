<?php

session_start();
require_once './db_conn_handler_script.php';
require_once './functions_script.php';

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
    $temp_ext = explode('.', $file_name);   // tokenizzo il nome del file
    $file_ext = strtolower(end($temp_ext));     // recupero solo l'estensione in lowercase

    // impongo un certo insieme di estensioni da rispettare
    $allowed_ext = array('jpg', 'jpeg', 'png');

    // ########## GESTIONE ERRORI ##########

    // se l'estensione non è consentita, restituisci errore
    if(in_array($file_ext, $allowed_ext) === false) {
        echo "File extension not supported";
        exit();
    }

    // se c'è stato un errore nel caricamente dell'immagine, restituisci errore
    if($file_error === true) {
        echo "Error while uploading file, please try again";
        exit();
    }

    // recupero l'id utente per associarlo alla nuova immagine profilo
    $usrid = $_SESSION["usrid"];

    // rinomino il file con un identificatore unico
    $image_name = "profile".$usrid.".".$file_ext;

    // specifico un percorso finale per il file
    $image_path = '../../resources/profileimg/'.$image_name;

    // salvo il file all'interno del percorso
    move_uploaded_file($file_tmp_name, $image_path);

    // comunico al DB che l'utente ha impostato un'immagine del profilo
    update_prof_img($conn, $usrid);

    // il file è stato caricato; reindirizzo l'utente
    header("location: ../../profile?up_pimg=success");

    exit();
}
// altrimenti reindirizzalo al suo profilo
else {
    header("location: ../../profile");
    exit();
}
?>