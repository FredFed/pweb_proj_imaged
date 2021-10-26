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

    // recupero, se presente, i dati dell'utente avente mail $email
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

    // ########## CREAZIONE UTENTE ##########

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


    // ########## AGGIUNTA IMMAGINE PROFILO ##########

    $sql_img = "SELECT * FROM users WHERE username = ? ;";
    $stmt2 = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt2, $sql_img)) {
        header("location: ../signup?error=prof_img_db_err");
        exit();
    }
    mysqli_stmt_bind_param($stmt2, "s", $username);
    mysqli_stmt_execute($stmt2); // eseguo lo statement
    $res = mysqli_stmt_get_result($stmt2);
    // aggiungo profile-image di default
    if($usr_data = mysqli_fetch_assoc($res)) {
        $usrid = $usr_data['id'];   // recupero id nuovo utente
        // comando SQL INSERT; NOTA: non sono necessari prepared statement: solo valori numerici sicuri
        $sql_prof_img = "INSERT INTO profileimg (usrid, isset) VALUES ('$usrid', 0) ;";
        $query_res = mysqli_query($conn, $sql_prof_img);
    }

    mysqli_stmt_close($stmt2);   // chiudo lo statement
    

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
        $_SESSION["usrid"] = $user_data["id"];  // recupera l'id utente
        $_SESSION["usr"] = $user_data["username"];  // recupera lo username
        $_SESSION["mail"] = $user_data["email"];    //recupera l'email
        header("location: ../../index");    // rimanda l'utente alla HomePage
        exit();
    }
}

// imposta l'entry relativa all'immagine del profilo dell'utente a 1
function update_prof_img($conn, $usrid) {
    // comando SQL UPDATE
    $sql = "UPDATE profileimg SET isset=1 WHERE usrid='$usrid';";
    mysqli_query($conn, $sql);    // esegue la query nel DB
    // NOTA: non c'è bisogno di prepared statement, perché lo usrid è generato dal DB stesso

    return(true);
}

// registra la cancellazione dell'immagine profilo dell'utente e aggiorna il DB
function delete_prof_img($conn, $usrid) {
    // comendo SQL UPDATE
    $sql = "UPDATE profileimg SET isset=0 WHERE usrid='$usrid';";
    mysqli_query($conn, $sql);    // esegue la query nel DB
    // NOTA: non c'è bisogno di prepared statement, perché lo usrid è generato dal DB stesso

    return(true);
}

// recupera l'estensione dell'immagine profilo dell'utente
function get_pimg_ext($usrid) {
    // recupero il nome dell'immagine del profilo
    $filename = "../../resources/profileimg/profile".$usrid."*";
    // recupero l'estensione prendendo il primo match della funzione "glob" e tenendo la parte finale
    $file_meta = glob($filename);
    $file_ext = explode(".", $file_meta[0]);    // il primo match della ricerca glob è il file corretto
    $ext = end($file_ext);   // prendiamo il token dopo il '.', ovvero l'estensione

    return($ext);
}

// elimina il file col path name specificato
function del_file($path) {
    if(!unlink($path)) return(true);
    else return(false);
}
?>