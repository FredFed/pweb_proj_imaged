<?php

// controlla se l'utente è arrivato a questa pagina compilando il form...
if(isset($_POST["submit_login"])) {
    
    $username = $_POST["username"];
    $password = $_POST["pswd"];

    // includo la connessione al DB ed alcune funzioni di controllo/utilità
    require_once './db_conn_handler_script.php';
    require_once './functions_script.php';

    // ########## GESTIONE ERRORI ##########

    // controlla che non ci siano campi vuoti
    if(empty_login($username, $password) !== false) {
        header("location: ../login?error=empty_input");  // returning the user to the signup form
        exit();     // manually terminating the script
    }
    // controlla se il nome utente è valido
    if(invalid_username($username) !== false && invalid_email($username) !== false) {
        header("location: ../login?error=invalid_usr");
        exit();
    }
    // controlla che la password sia valida
    if(invalid_password($password) !== false) {
        header("location: ../login?error=invalid_pswd");
        exit();
    }

    // effettua il login dell'utente a partire dai dati recuperati dal login form
    login_user($conn, $username, $password);
}
// ... altrimenti, lo reindirizza al login
else {
    header("location: ../login");
    exit();
}
?>