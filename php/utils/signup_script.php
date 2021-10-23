<?php

// controlla se l'utente è arrivato a questa pagina compilando il form...
if(isset($_POST["submit_signup"])) {

    // recupero tutti i valori dal form di signup
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["pswd"];
    $rep_password = $_POST["rep_pswd"];

    // includo la connessione al DB ed alcune funzioni di controllo/utilità
    require_once './db_conn_handler_script.php';
    require_once './functions_script.php';

    // ########## GESTIONE ERRORI ##########

    // controlla che non ci siano campi vuoti
    if(empty_input($username, $email, $password, $rep_password) !== false) {
        header("location: ../signup?error=empty_input");
        exit();
    }
    // controlla se il nome utente è valido
    if(invalid_username($username) !== false) {
        header("location: ../signup?error=invalid_usr");
        exit();
    }
    // controlla che la mail sia valida
    if(invalid_email($email) !== false) {
        header("location: ../signup?error=invalid_email");
        exit();
    }
    // controlla che la password sia valida
    if(invalid_password($password) !== false) {
        header("location: ../signup?error=invalid_pswd");
        exit();
    }
    // controlla che i due valori per le password siano identici
    if(unmatching_passwords($password, $rep_password) !== false) {
        header("location: ../signup?error=pswd_no_match");
        exit();
    }
    // controlla che l'utente non esista già
    if(existing_username($conn, $username) !== false) {
        header("location: ../signup?error=usr_exists");
        exit();
    }
    // controlla che l'email non esista già
    if(existing_email($conn, $email) !== false) {
        header("location: ../signup?error=email_exists");
        exit();
    }

    // crea l'utente a partire dai dati recuperati dal signup form
    create_user($conn, $username, $email, $password);
}
// ... altrimenti, lo reindirizza alla pagina principale
else {
    header("location: ../../index");
    exit();
}
?>