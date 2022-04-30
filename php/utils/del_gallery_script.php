<?php

session_start();
if(!isset($_SESSION["usrid"])) {
    header("location: ../../login?error=bad_login");
    exit();
}
require_once './db_conn_handler_script.php';
require_once './functions_script.php';

// controlla che l'utente sia arrivato qui cliccando il pulsante
if(isset($_POST["del_img"]) && isset($_SESSION["usrid"]) && $_SESSION["usrid"]==$_POST["img_author"]) {

    // recupero lo usrid dell'utente
    $usrid = $_SESSION["usrid"];
    $usrname = $_SESSION["usrname"];
    $imgId = $_POST["img_id"];

    if(!delete_gallery_img($conn, $imgId, $usrid)) {
        header("location: ../../profile?user=".$usrname."&err=del_img_fail");
        exit();
    }
    else {
        header("location: ../../profile?user=".$usrname."&del_img_res=success");
        exit();
    }
}
// altrimenti reindirizzalo al suo profilo
else {
    header("location: ../../?err=del_unauth");
    exit();
}

?>