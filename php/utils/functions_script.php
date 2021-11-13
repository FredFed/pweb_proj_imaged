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
        return("fatal");
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
        return("fatal");
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
        return("err=usr_create_db_err");
    }

    // hashing della password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // binding tra username e statement
    mysqli_stmt_bind_param($stmt, "sss", $username, $password, $email);
    mysqli_stmt_execute($stmt); // eseguo lo statement
    mysqli_stmt_close($stmt);   // chiudo lo statement


    // ########## AGGIUNTA IMMAGINE PROFILO ##########

    $sql_img = "SELECT * FROM users WHERE username = ? ;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql_img)) {
        return("err=prof_img_db_err");
    }
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt); // eseguo lo statement
    $res = mysqli_stmt_get_result($stmt);
    // aggiungo profile-image di default
    if($user_data = mysqli_fetch_assoc($res)) {
        $usrid = $user_data['id'];   // recupero id nuovo utente
        // comando SQL INSERT; NOTA: non sono necessari prepared statement: solo valori numerici sicuri
        $sql_prof_img = "INSERT INTO profileimg (usrid, isset) VALUES ('$usrid', 0) ;";
        mysqli_query($conn, $sql_prof_img);
    }

    mysqli_stmt_close($stmt);   // chiudo lo statement

    // logga nell'account appena creato
    session_start();    // avvia la sessione
    $_SESSION["usrid"] = $user_data["id"];  // recupera l'id utente
    $_SESSION["usr"] = $user_data["username"];  // recupera lo username
    $_SESSION["mail"] = $user_data["email"];    //recupera l'email
    return("success");
}

// effettua il login dell'utente a partire dai dati recuperati dal login form
function login_user($conn, $username, $password) {
    // $user_data=false;    // conterrà i dati dell'utente (se questo esiste)

    // se lo username fornito è una mail valida, cerca l'utente per mail
    if(invalid_email($username) === false) $user_data = existing_email($conn, $username);
    // altrimenti, cerca per username
    else $user_data = existing_username($conn, $username);

    // se non esistono utenti con lo username o la mail specificata, torna al login
    if($user_data === false) return("err=usr_no_exists");

    // recupero la password effettiva dell'utente
    $usr_real_pswd = $user_data["password"];
    // controllo che la password fornita sia uguale a quella reale
    $is_pswd_correct = password_verify($password, $usr_real_pswd);

    // se la password non era corretta, torna al login
    if($is_pswd_correct === false) return("err=wr_pswd");

    // se tutto è andato bene, avvia una sessione per l'utente
    session_start();    // avvia la sessione
        $_SESSION["usrid"] = $user_data["id"];  // recupera l'id utente
        $_SESSION["usr"] = $user_data["username"];  // recupera lo username
        $_SESSION["mail"] = $user_data["email"];    //recupera l'email
        return("success");
}


// restituisce l'estensione del file in ingresso
function get_ext($file_name) {
    $temp_ext = explode('.', $file_name);
    $file_ext = strtolower(end($temp_ext));

    return($file_ext);
}

// imposta l'entry relativa all'immagine del profilo dell'utente a 1
function update_prof_img($conn, $usrid) {
    // comando SQL UPDATE
    $sql = "UPDATE profileimg SET isset=1 WHERE usrid='$usrid';";
    mysqli_query($conn, $sql);    // esegue la query nel DB
    // NOTA: non c'è bisogno di prepared statement, perché lo usrid è generato dal DB stesso

    return true;
}

// registra la cancellazione dell'immagine profilo dell'utente e aggiorna il DB
function delete_prof_img($conn, $usrid) {
    // ######### AGGIORNAMENTO DB ##########
    // comendo SQL UPDATE
    $sql = "UPDATE profileimg SET isset=0 WHERE usrid='$usrid';";
    mysqli_query($conn, $sql);    // esegue la query nel DB
    // NOTA: non c'è bisogno di prepared statement, perché lo usrid è generato dal DB stesso


    // ########## ELIMINAZIONE FILE ##########
    // recupero il nome dell'immagine del profilo
    $filename = "../../resources/profileimg/profile".$usrid."*";

    // recupero l'estensione prendendo il primo match della funzione "glob" e tenendo la parte finale
    $file_meta = glob($filename);   // avvia la ricerca nel percorso specificato
    if(!$file_meta) return true; // se non esiste alcuna immagine profilo, restituisci true
    $file_ext = get_ext($file_meta[0]);     // recupero l'estensione del primo match della ricerca glob (il "closer match")

    // costruisco il path completo
    $file_path =  "../../resources/profileimg/profile".$usrid.".".$file_ext;

    if(!unlink($file_path)) return false;

    return true;
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

// elimina il file col path name specificato TODO rimuovere? usato?
function del_file($path) {
    if(!unlink($path)) return true;
    else return false;
}

// aggiunge l'entry relativa all'immagine caricata alla tabella galleria
function upload_gallery_img($conn, $usrid, $img_path, $img_title, $img_desc, $img_tags, $img_is_ls, $img_is_hidden) {
    // Comando SQL INSERT
    $sql = "INSERT INTO gallery (usrId, imgName, imgTitle, imgDesc, imgTags, imgLsMode, imgHidden, imgBlock, imgDate) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 0, now());";
    $stmt = mysqli_stmt_init($conn);    // creo un prepared statement

    // preparo lo statement; se la preparazione è fallita, restituisce errore DB
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        return false;
    }
    // binding tra username e statement
    mysqli_stmt_bind_param($stmt, "issssii", $usrid, $img_path, $img_title, $img_desc, $img_tags, $img_is_ls, $img_is_hidden);
    mysqli_stmt_execute($stmt); // eseguo lo statement
    mysqli_stmt_close($stmt);   // chiudo lo statement

    return true;
}


// ritaglia l'immagine secondo la dimensione specificata
function crop_image($src_path, $dest_path, $file_ext, $max_w, $max_h) {
    // controllo se il path dell'immagine è valido
    if(!file_exists($src_path)) return("err=file_no_exist");

    // recupero le informazioni sull'immagine in base all'estensione
    if($file_ext == "jpeg") {
        $src_img = imagecreatefromjpeg($src_path);
    }
    else if($file_ext == "png") {
        $src_img = imagecreatefrompng($src_path);
    }
    else return("err=img_not_supp");

    $src_w = imagesx($src_img);     // recupero la larghezza originale
    $src_h = imagesy($src_img);     // recupero l'altezza originale

    // se l'altezza è maggiore della larghezza, procedi come segue
    if($src_h > $src_w) {
        // rendo la larghezza quella definitiva e taglio l'altezza di conseguenza
        $reduction_ratio = $max_w/$src_w;
        $new_w = $max_w;
        $new_h = $src_h*$reduction_ratio;
    }
    else if($src_w > $src_h) {
        // rendo l'altezza quella definitiva e taglio la larghezza di conseguenza
        $reduction_ratio = $max_h/$src_h;
        $new_h = $max_h;
        $new_w = $src_w*$reduction_ratio;
    }

    // imposto la dimensione minore, scalando quella maggiore di conseguenza
    $cropped_img = imagecreatetruecolor($new_w, $new_h);
    imagecopyresampled($cropped_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, $src_w, $src_h);
    imagedestroy($src_img); // libero la memoria occupata dalla vecchia immagine

    // effettuo il cropping della dimensione maggiore
    if($new_h > $new_w) {   // se l'altezza è la dimensione maggiore
        $diff = ($new_h - $new_w);  // controllo di quanto l'altezza è maggiore
        $y_crop = round($diff/2);  // vogliamo effettuare il crop metà su, metà giù
        $x_crop = 0;    // la larghezza è già al valore corretto
    }
    else if($new_w > $new_h) {  // se la larghezza è la dimensione maggiore
        $diff = ($new_w - $new_h);  // controllo di quanto la larghezza è maggiore
        $x_crop = round($diff/2);   // vogliamo effettuare il crop metà a sx, metà a dx
        $y_crop = 0;    // l'altezza è già al valore corretto
    }

    // ricavo la versione finale dell'immagine
    $dest_img = imagecreatetruecolor($max_w, $max_h);
    imagecopyresampled($dest_img, $cropped_img, 0, 0, $x_crop, $y_crop, $max_w, $max_h, $max_w, $max_h);
    imagedestroy($cropped_img); // libero la memoria occupata dall'immagine intermedia


    // salvo l'immagine nel percorso finale in base all'estensione
    if($file_ext == "jpeg") {
        imagejpeg($dest_img, $dest_path, 100);
    }
    else if($file_ext == "png") {
        imagepng($dest_img, $dest_path, 0);
    }

    imagedestroy($dest_img);
    return "success";
}


// ritaglia l'immagine secondo la dimensione specificata
function square_image($src_path, $dest_path, $file_ext) {
    // controllo se il path dell'immagine è valido
    if(!file_exists($src_path)) return("err=file_no_exist");

    // recupero le informazioni sull'immagine in base all'estensione
    if($file_ext == "jpeg") {
        $src_img = imagecreatefromjpeg($src_path);
    }
    else if($file_ext == "png") {
        $src_img = imagecreatefrompng($src_path);
    }
    else return("err=img_not_supp");

    $src_w = imagesx($src_img);     // recupero la larghezza originale
    $src_h = imagesy($src_img);     // recupero l'altezza originale

    // se l'altezza è maggiore della larghezza, procedi come segue
    if($src_h > $src_w) {
        // rendo la larghezza quella definitiva e taglio l'altezza di conseguenza
        $max_h = $src_w;
        $max_w = $src_w;
        $reduction_ratio = $max_w/$src_w;
        $new_w = $max_w;
        $new_h = $src_h*$reduction_ratio;
    }
    else {
        // rendo l'altezza quella definitiva e taglio la larghezza di conseguenza
        $max_h = $src_h;
        $max_w = $src_h;
        $reduction_ratio = $max_h/$src_h;
        $new_h = $max_h;
        $new_w = $src_w*$reduction_ratio;
    }

    // imposto la dimensione minore, scalando quella maggiore di conseguenza
    $cropped_img = imagecreatetruecolor($new_w, $new_h);
    imagecopyresampled($cropped_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, $src_w, $src_h);
    imagedestroy($src_img); // libero la memoria occupata dalla vecchia immagine

    // effettuo il cropping della dimensione maggiore
    if($new_h > $new_w) {   // se l'altezza è la dimensione maggiore
        $diff = ($new_h - $new_w);  // controllo di quanto l'altezza è maggiore
        $y_crop = round($diff/2);  // vogliamo effettuare il crop metà su, metà giù
        $x_crop = 0;    // la larghezza è già al valore corretto
    }
    else if($new_w > $new_h) {  // se la larghezza è la dimensione maggiore
        $diff = ($new_w - $new_h);  // controllo di quanto la larghezza è maggiore
        $x_crop = round($diff/2);   // vogliamo effettuare il crop metà a sx, metà a dx
        $y_crop = 0;    // l'altezza è già al valore corretto
    }

    // ricavo la versione finale dell'immagine
    $dest_img = imagecreatetruecolor($max_w, $max_h);
    imagecopyresampled($dest_img, $cropped_img, 0, 0, $x_crop, $y_crop, $max_w, $max_h, $max_w, $max_h);
    imagedestroy($cropped_img); // libero la memoria occupata dall'immagine intermedia


    // salvo l'immagine nel percorso finale in base all'estensione
    if($file_ext == "jpeg") {
        imagejpeg($dest_img, $dest_path, 100);
    }
    else if($file_ext == "png") {
        imagepng($dest_img, $dest_path, 0);
    }

    imagedestroy($dest_img);
    return "success";
}


// restituisce il nome del file senza estensione
function drop_ext($filename) {
    $result = explode('.', $filename);

    return($result[0]);
}
?>