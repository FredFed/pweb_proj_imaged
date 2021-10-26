<?php

session_start();
if(isset($_SESSION["usr"])) {
    header("location: ../index");
}

?>


<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name=”viewport” content=”width=device-width, initial-scale=1”>
        <link rel="stylesheet" href="css/styles.css">
        <title>Log in - Imaged</title>
    </head>
    <body>
        <div id="main_div_login">
            <div class="container-section-login">
                <section class="section-login">
                    <!-- LOGO -->
                    <h1 class="login-signup-logo"> imaged </h1>
                    <!-- FORM -->
                    <div class="div-login-form">
                        <form id="login-form" action="./utils/login_script.php" method="post">
                            <!-- DESCR -->
                            <h2>Sign in</h2>
                            <!-- INPUT ROW -->
                            <input type="text" name="username" placeholder="Username or e-mail">
                            <input type="password" name="pswd" placeholder="Password">
                            <button type="submit" name="submit_login">Sign in</button>
                            <p class="login-screen-registration">Don't have an account?
                                <a href="./signup">Sign up!</a>
                            </p>
                        </form>
                    </div>
                </section>
            </div>

            <?php
                if(isset($_GET["error"])) {
                    if($_GET["error"]=="empty_input")
                        echo "<p class=&quoterr-box&quot>All fields must be filled</p>";
                    else if($_GET["error"]=="invalid_usr")
                        echo "<p class=&quoterr-box&quot>Invalid email/username.</p>";
                    else if($_GET["error"]=="invalid_pswd")
                        echo "<p class=&quoterr-box&quot>Invalid password.</p>";
                    else if($_GET["error"]=="usr_no_exists")
                        echo "<p class=&quoterr-box&quot>The username/email is not of a valid account.</p>";
                    else if($_GET["error"]=="wr_pswd")
                        echo "<p class=&quoterr-box&quot>Password is incorrect.</p>";
                    else if($_GET["error"]=="usr_ex_db_err" || $_GET["error"]=="mail_ex_db_err"
                                                            || $_GET["error"]=="usr_create_db_err")
                        echo "<p class=&quoterr-box&quot>Something went wrong, please try again.</p>";
                }
            ?>

        </div>
    </body>
</html>