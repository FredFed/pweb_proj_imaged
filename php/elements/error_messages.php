<?php

    // errori di upload immagine
    if(isset($_GET["err"])) {
        if($_GET["err"]=="bad_ext")
        echo "<p class='err-box'>Only .jpeg, .jpg, .png files are accepted</p>";
        else if($_GET["err"]=="up_err")
        echo "<p class='err-box'>Server err, please try again</p>";
        else if($_GET["err"]=="sz_2_lg")
        echo "<p class='err-box'>Max file size is 10MB</p>";
    }

    // errori di registrazione
    if(isset($_GET["err"])) {
        if($_GET["err"]=="empty_input")
            echo "<p class='err-box'>All fields must be filled</p>";
        else if($_GET["err"]=="invalid_usr")
            echo "<p class='err-box'>Only '.', '-' and '_' are allowed.</p>";
        else if($_GET["err"]=="invalid_email")
            echo "<p class='err-box'>Specify a valid email.</p>";
        else if($_GET["err"]=="invalid_pswd")
            echo "<p class='err-box'>Password too long.</p>";
        else if($_GET["err"]=="pswd_no_match")
            echo "<p class='err-box'>Password fields are not matching.</p>";
        else if($_GET["err"]=="usr_exists")
            echo "<p class='err-box'>Username is already taken.</p>";
        else if($_GET["err"]=="email_exists")
            echo "<p class='err-box'>An account with this email already exists.</p>";
        else if($_GET["err"]=="usr_ex_db_err" || $_GET["err"]=="mail_ex_db_err"
                                                || $_GET["err"]=="usr_create_db_err")
            echo "<p class='err-box'>Something went wrong, please try again.</p>";
    }

    // errori di login
    if(isset($_GET["err"])) {
        if($_GET["err"]=="bad_login")
            echo "<p class='err-box'>You have to log in first</p>";
        if($_GET["err"]=="empty_input")
            echo "<p class='err-box'>All fields must be filled</p>";
        else if($_GET["err"]=="invalid_usr")
            echo "<p class='err-box'>Invalid email/username.</p>";
        else if($_GET["err"]=="invalid_pswd")
            echo "<p class='err-box'>Invalid password.</p>";
        else if($_GET["err"]=="usr_no_exists")
            echo "<p class='err-box'>The username/email is not of a valid account.</p>";
        else if($_GET["err"]=="wr_pswd")
            echo "<p class='err-box'>Password is incorrect.</p>";
        else if($_GET["err"]=="usr_ex_db_err" || $_GET["err"]=="mail_ex_db_err"
                                                || $_GET["err"]=="usr_create_db_err")
            echo "<p class='err-box'>Something went wrong, please try again.</p>";
    }
?>