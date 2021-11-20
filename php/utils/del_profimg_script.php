<?php

session_start();
if(!isset($_SESSION["usrid"])) {
    header("location: ../login?error=bad_login");
    exit();
}
require_once './db_conn_handler_script.php';
require_once './functions_script.php';

// controlla che l'utente sia arrivato qui cliccando il pulsante
if(isset($_POST["del_prof_img"])) {

    // recupero lo usrid dell'utente
    $usrid = $_SESSION["usrid"];
    $usrname = $_SESSION["usrname"];

    if(!delete_prof_img($conn)) {
        header("location: ../../profile?user=".$usrname."&err=del_pimg_fail");
        exit();
    }
    else {
        header("location: ../../profile?user=".$usrname."&res=success");
        exit();
    }
}
// altrimenti reindirizzalo al suo profilo
else {
    header("location: ../../profile?user=".$usrname);
    exit();
}

?>