<?php

session_start();
require_once './db_conn_handler_script.php';
require_once './functions_script.php';
require_once './definitions.php';

if(isset($_POST["img_submitted"])) {
    $img = $_FILES['gallery_img'];
    $img_title = $_POST['img_title'];
    $img_desc = $_POST['img_desc'];
    $img_tags = $_POST['img_tags'];
    $img_hddn = isset($_POST['img_hidden']) ? 1 : 0;

    $img_name = $img['name'];
    $img_tmp_name = $_FILES['gallery_img']['tmp_name']; // TODO normalizzare
    $img_error = $img['error'];
    $img_size = filesize($img_tmp_name);

    // recupero l'estensione (conversione implicita jpg -> jpeg)
    $img_ext = get_ext($img_name);
    if($img_ext == 'jpg') $img_ext = 'jpeg';

    
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
    if($img_size > $MAX_IMG_SIZE || $img_size==null) {
        header("location: ../../upload?err=sz_2_lg");
        exit();
    }

    // ########## SALVATAGGIO E AGGIUNTA ENTRY AL DB #########

    // genero una stringa unica casuale e appendo l'estensione per creare il nome del file
    $img_name_no_ext = generate_key($conn);
    $img_final_name = $img_name_no_ext.'.'.$img_ext;

    // stabilisco se l'utente è loggato o meno
    if(isset($_SESSION["usrid"])) {
        $usrid = $_SESSION["usrid"];
        $usrname = $_SESSION["usrname"];
    }
    else $usrid = null;

    // ricavo il path finale dell'immagine
    if($usrid==null) $img_pre_path = "../../resources/users/default/gallery/";
    else $img_pre_path = "../../resources/users/".$usrid."/gallery/";
    $img_path = $img_pre_path.$img_final_name;

    // aggiorno il database con l'entry relativa all'immagine
    if(!upload_gallery_img($conn, $usrid, $img_final_name, $img_title, $img_desc, $img_tags, $img_hddn)) {
        header("location: ../../upload?err=up_img_err");
        exit();
    }

    // salvo il file all'interno del percorso
    move_uploaded_file($img_tmp_name, $img_path);

    // genero versione cropped da usare per le anteprime in galleria
    $temperr=crop_image($img_path, $img_pre_path.$img_name_no_ext."cropped".'.'.$img_ext, $img_ext, $MAX_GIMG_W, $MAX_GIMG_H);
    if($temperr!="success") {   // se il processo non è andato a buon fine...
        unlink($image_path);    // rimuove l'immagine appena salvata
        header("location: ../../profile?user=".$usrname."&".$temperr);    // reindirizza + mostra errore
        exit();
    }

    // ritorno alla galleria dell'utente (se l'utente è loggato) o alla homepage
    if($usrid) header("location: ../../profile?user=".$usrname."&up_img=success");
    else header("location: ../../image?id=".$img_name_no_ext);    // TODO sostituire con il link dell'immagine
    exit();
}
else {
    header("location: ../../?&err=forbidden");
    exit();
}