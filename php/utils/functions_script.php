<?php

// controlla se ci sono campi vuoti
function empty_input($username, $email, $password, $rep_password) {
    if(empty($username) || empty($email) || empty($password) || empty($rep_password)) return true;
    return false;
}

// controlla se il nome utente è valido
function invalid_username($username) {
    // controllo con espressioni regolari
    if(!preg_match(("/^[a-zA-Z0-9-_. ]+$/"), $username)) return true;
    // controlla che il nome utente rispetti le dimensioni massime
    if((strlen(utf8_decode($username)))>20) return true;
    return false;
}

// controlla che la mail sia valida
function invalid_email($email) {
    // using php inbuilt function
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
    if((strlen(utf8_decode($email)))>320) return true;
    return false;
}

// check if password is valid
function invalid_password($password) {
    // checking with regular expression
    if((strlen(utf8_decode($password)))>256) return true;
    return false;
}

// check if password fields are matching
function unmatching_passwords($password, $rep_password) {
    if($password !== $rep_password) return true;
    return false;
}

// check if user already exists
existing_username($conn, $username) {
    $sql = "SELECT * FROM users WHERE username = ? ;";
    // creating a prepared statement
    $stmt = mysqli_stmt_init($conn);
    // if the statement couldn't be prepared, return to the signup with a db error
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=db_err");
        exit();
    }
    // binding username to the statement
    mysqli_stmt_bind_param($stmt, "ss", $username);
    mysqli_stmt_execute($stmt);
    // getting query result
    $query_res = mysqli_stmt_get_result($stmt);

}