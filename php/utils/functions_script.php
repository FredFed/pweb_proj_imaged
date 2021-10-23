<?php

// controlla che non ci siano campi vuoti
function empty_input($username, $email, $password, $rep_password) {
    if(empty($username) || empty($email) || empty($password) || empty($rep_password)) return true;
    else return false;
}

// controlla che non ci siano campi vuoti
function empty_login($username, $password) {
    if(empty($username) || empty($password)) return true;
    else return false;
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

// controlla che la password sia valida
function invalid_password($password) {
    // controlla la lunghezza della password
    if((strlen(utf8_decode($password)))>256) return true;
    return false;
}

// controlla che i due valori per le password siano identici
function unmatching_passwords($password, $rep_password) {
    if($password !== $rep_password) return true;
    return false;
}

// controlla che l'utente non esista già
function existing_username($conn, $username) {
    $sql = "SELECT * FROM users WHERE username = ? ;";  // comando SQL SELECT
    $stmt = mysqli_stmt_init($conn);    // creo un prepared statement

    // preparo lo statement; se la preparazione è fallita, restituisce errore DB
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup?error=usr_ex_db_err");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $username); // binding tra username e statement
    mysqli_stmt_execute($stmt); // eseguo lo statement
    $query_res = mysqli_stmt_get_result($stmt); // recupero risultati query

    // recupero, se presente, i dati dell'utente di nome $username
    if($usr_row = mysqli_fetch_assoc($query_res)) {
        mysqli_stmt_close($stmt);
        return $usr_row;
    }
    // se l'utente non esiste, return false
    mysqli_stmt_close($stmt);
    return false;
}

// controlla che l'email non esista già
function existing_email($conn, $email) {
    $sql = "SELECT * FROM users WHERE email = ? ;"; // comando SQL SELECT
    $stmt = mysqli_stmt_init($conn);    // creo un prepared statement

    // preparo lo statement; se la preparazione è fallita, restituisce errore DB
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup?error=mail_ex_db_err");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);    // binding tra email e statement
    mysqli_stmt_execute($stmt); // eseguo lo statement
    $query_res = mysqli_stmt_get_result($stmt); // recupero risultati query

    // recupero, se presente, i dati dell'utente di mail $email
    if($usr_row = mysqli_fetch_assoc($query_res)) {
        mysqli_stmt_close($stmt);
        return $usr_row;
    }
    
    // se l'utente non esiste, return false
    mysqli_stmt_close($stmt);
    return false;
}

// crea l'utente a partire dai dati recuperati dal signup form
function create_user($conn, $username, $email, $password) {
    // comando SQL INSERT
    $sql = "INSERT INTO users (username, password, email, date) VALUES (?, ?, ?, now());";
    $stmt = mysqli_stmt_init($conn);    // creo un prepared statement

    // preparo lo statement; se la preparazione è fallita, restituisce errore DB
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup?error=usr_create_db_err");
        exit();
    }

    // hashing della password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // binding tra username e statement
    mysqli_stmt_bind_param($stmt, "sss", $username, $password, $email);
    mysqli_stmt_execute($stmt); // eseguo lo statement
    mysqli_stmt_close($stmt);   // chiudo lo statement

    // reindirizzo l'utente in seguito al singup
    header("location: ../../index?signup=success");
    exit();
}

// effettua il login dell'utente a partire dai dati recuperati dal login form
function login_user($conn, $username, $password) {
    // $user_data=false;    // conterrà i dati dell'utente (se questo esiste)

    // se lo username fornito è una mail valida, cerca l'utente per mail
    if(invalid_email($username) === false) $user_data = existing_email($conn, $username);
    // altrimenti, cerca per username
    else $user_data = existing_username($conn, $username);

    // se non esistono utenti con lo username o la mail specificata, torna al login
    if($user_data === false) {
        header("location: ../login?error=usr_no_exists");
        exit();
    }

    // recupero la password effettiva dell'utente
    $usr_real_pswd = $user_data["password"];
    // controllo che la password fornita sia uguale a quella reale
    $is_pswd_correct = password_verify($password, $usr_real_pswd);

    // se la password non era corretta, torna al login
    if($is_pswd_correct === false) {
        header("location: ../login?error=wr_pswd");
        exit();
    }
    // altrimenti, avvia una sessione per l'utente
    else {
        session_start();    // avvia la sessione
        $_SESSION["usr"] = $user_data["username"];  // recupera lo username
        $_SESSION["mail"] = $user_data["email"];    //recupera l'email
        header("location: ../../index");    // rimanda l'utente alla HomePage
        exit();
    }
}
?>