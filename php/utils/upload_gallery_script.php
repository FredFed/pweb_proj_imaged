<?php

session_start();
require_once './db_conn_handler_script.php';
require_once './functions_script.php';
require_once './definitions.php';

if(isset($_POST["submit_img_gallery"])) {
    $img = $_FILES['gallery_img'];
    $img_title = $_POST['img_title'];
    $img_desc = $_POST['img_desc'];
    $img_tags = $_POST['img_tags'];
    $img_ls = isset($_POST['img_ls']) ? 1 : 0;
    $img_hddn = isset($_POST['img_hidden']) ? 1 : 0;

    $img_name = $img['name'];
    $img_tmp_name = $_FILES['gallery_img']['tmp_name']; // TODO normalizzare
    $img_size = $img['size'];
    $img_error = $img['error'];

    // recupero l'estensione
    $img_ext = get_ext($img_name);

    
    // ########## GESTIONE ERRORI ##########

    // se l'estensione non è consentita, restituisci errore
    if(in_array($img_ext, $allowed_ext) === false) {
        header("location: ../../upload?err=bad_ext");
        exit();
    }

    // se c'è stato un errore nel caricamente dell'immagine, restituisci errore
    if($img_error === true) {
        header("location: ../../upload?err=up_err");
        exit();
    }

    // se la dimensione è eccessiva, restituisci errore
    if($img_size > $MAX_IMG_SIZE) {
        header("location: ../../upload?err=sz_2_lg");
        exit();
    }

    // ########## SALVATAGGIO E AGGIUNTA ENTRY AL DB #########

    // genero una stringa unica casuale e appendo l'estensione per creare il nome del file
    $img_final_name = generate_key($conn).'.'.$img_ext;

    // stabilisco se l'utente è loggato o meno
    if(isset($_SESSION["usrid"])) {
        $usrid = $_SESSION["usrid"];
        $usrname = $_SESSION["usrname"];
    }
    else $usrid = NULL;

    // ricavo il path finale dell'immagine
    if($usrid==NULL) $img_path = "../../resources/users/default/".$img_final_name;
    else $img_path = "../../resources/users/".$usrid."/gallery/".$img_final_name;

    // aggiorno il database con l'entry relativa all'immagine
    if(!upload_gallery_img($conn, $usrid, $img_final_name, $img_title, $img_desc, $img_tags, $img_ls, $img_hddn)) {
        header("location: ../../upload?err=up_img_err");
        exit();
    }

    // salvo il file all'interno del percorso
    move_uploaded_file($img_tmp_name, $img_path);

    // ritorno alla galleria dell'utente (se l'utente è loggato) o alla homepage
    if($usrid) header("location: ../../profile?user=".$usrname."&up_img=success");
    else header("location: ../../");    // TODO sostituire con il link dell'immagine
    exit();
}
else {
    header("location: ../../");
    exit();
}