<?php

// includo la connessione al DB ed alcune funzioni di controllo/utilità
require_once './db_conn_handler_script.php';
require_once './functions_script.php';

// controlla se l'utente è arrivato a questa pagina compilando il form...
if(isset($_POST["submit_login"])) {
    
    $username = $_POST["username"];
    $password = $_POST["pswd"];

    // ########## GESTIONE ERRORI ##########

    // controlla che non ci siano campi vuoti
    if(empty_login($username, $password) !== false) {
        header("location: ../login?err=empty_input");  // returning the user to the signup form
        exit();     // manually terminating the script
    }
    // controlla se il nome utente è valido
    if(invalid_username($username) !== false && invalid_email($username) !== false) {
        header("location: ../login?err=invalid_usr");
        exit();
    }
    // controlla che la password sia valida
    if(invalid_password($password) !== false) {
        header("location: ../login?err=invalid_pswd");
        exit();
    }

    // effettua il login dell'utente a partire dai dati recuperati dal login form
    if(($temperr=login_user($conn, $username, $password)) != "success") {
        header("location: ../login?".$temperr);
        exit();
    }

    // reindirizza l'utente in seguito al login
    header("location: ../../profile?login=success");    // rimanda l'utente alla HomePage
}
// ... altrimenti, lo reindirizza al login
else {
    header("location: ../login");
    exit();
}
?>