<?php

// signup management
if(isset($_POST["submit_signup"])) {
    // getting all the values from our signup form and escaping dubious characters
    $username = mysqli_real_escape_string($conn, $_POST["usrname"]);
    $email = mysqli_real_escape_string($conn, $_POST["mail"]);
    $password = mysqli_real_escape_string($conn, $_POST["pswd"]);
    $rep_password = mysqli_real_escape_string($conn, $_POST["rep_pswd"]);

    // including our db connection variable and util functions
    require_once './db_conn_handler_script.php';
    require_once './functions_script.php';

    // basic error handling in form compilation

    // check if there is empty input
    if(empty_input($username, $email, $password, $rep_password) !== false) {
        // empty imput error
        header("location: ../signup.php?error=empty_input");  // returning the user to the signup form
        exit();     // manually terminating the script
    }
    // check if username is valid
    if(invalid_username($username) !== false) {
        header("location: ../signup.php?error=invalid_usr");
        exit();
    }
    // check if email is valid
    if(invalid_email($email) !== false) {
        header("location: ../signup.php?error=invalid_email");
        exit();
    }
    // check if password is valid
    if(invalid_password($password) !== false) {
        header("location: ../signup.php?error=invalid_pswd");
        exit();
    }
    // check if password fields are matching
    if(unmatching_passwords($password, $rep_password) !== false) {
        header("location: ../signup.php?error=pswd_no_match");
        exit();
    }
    // check if user already exists
    if(existing_username($conn, $username) !== false) {
        header("location: ../signup.php?error=usr_exists");
        exit();
    }
    // check if email is already used
    if(existing_email($conn, $email) !== false) {
        header("location: ../signup.php?error=email_exists");
        exit();
    }

    // after passing all error-tests, the user might now be created
    create_user($conn, $username, $email, $password);
}
else {
    header("location: ../signup.php");
}
?>