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
        header("location: ../upload?err=bad_ext");
        exit();
    }

    // se c'è stato un errore nel caricamente dell'immagine, restituisci errore
    if($img_error === true) {
        header("location: ../upload?err=up_err");
        exit();
    }

    if($img_size > $MAX_IMG_SIZE) {
        header("location: ../upload?err=sz_2_lg");
        exit();
    }

    // genero un numero casuale eliminando il punto decimale presente in esso (mediante regex) e appendo l'estensione
    $img_final_name = (preg_replace("/[.]/", "", uniqid("", true))).".".$img_ext;
    $img_path = "../../resources/gallery/".$img_final_name;

    // ########## AGGIUNTA ENTRY AL DB #########

    // stabilisco se l'utente è loggato o meno
    if(isset($_SESSION["usrid"])) $usrid = $_SESSION["usrid"];
    else $usrid = NULL;

    // aggiorno il database con l'entry relativa all'immagine
    if(!upload_gallery_img($conn, $usrid, $img_final_name, $img_title, $img_desc, $img_tags, $img_ls, $img_hddn)) {
        header("location: ../upload?err=up_img_err");
        exit();
    }

    // salvo il file all'interno del percorso
    move_uploaded_file($img_tmp_name, $img_path);

    
    header("location: ../../profile?up_img=success");
    exit();
}
else {
    header("location: ../../profile");
    exit();
}