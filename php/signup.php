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
        <title>Sign Up - Imaged</title>
    </head>
    <body>
        <div id="main_div_signup">
            <div class="container-section-signup">
                <section class="section-signup">
                    <!-- LOGO -->
                    <h1 class="login-signup-logo"> imaged </h1>
                    <!-- FORM -->
                    <div class="div-signup-form">
                        <form id="signup-form" action="./utils/signup_script.php" method="post">
                            <!-- DESCR -->
                            <h2>Sign up to unlock all the best features!</h2>
                            <!-- INPUT ROW -->
                            <input type="text" name="username" placeholder="Username">
                            <input type="text" name="email" placeholder="e-mail">
                            <input type="password" name="pswd" placeholder="Password">
                            <input type="password" name="rep_pswd" placeholder="Repeat Password">
                            <button type="submit" name="submit_signup">Continue</button>
                            <p class="privacy-disclaimer">
                                By clicking "Continue", you are confirming to be aware of our
                                <a href="./../privacy.html" target="_blank">privacy policies</a>
                                and how we collect, use and share your personal data and to 
                                accept them.
                            </p>
                            <p class="login-screen-registration">Already have an account?
                                <a href="./login">Log in!</a>
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
                        echo "<p class=&quoterr-box&quot>Only '.', '-' and '_' are allowed.</p>";
                    else if($_GET["error"]=="invalid_email")
                        echo "<p class=&quoterr-box&quot>Specify a valid email.</p>";
                    else if($_GET["error"]=="invalid_pswd")
                        echo "<p class=&quoterr-box&quot>Password too long.</p>";
                    else if($_GET["error"]=="pswd_no_match")
                        echo "<p class=&quoterr-box&quot>Password fields are not matching.</p>";
                    else if($_GET["error"]=="usr_exists")
                        echo "<p class=&quoterr-box&quot>Username is already taken.</p>";
                    else if($_GET["error"]=="email_exists")
                        echo "<p class=&quoterr-box&quot>An account with this email already exists.</p>";
                    else if($_GET["error"]=="usr_ex_db_err" || $_GET["error"]=="mail_ex_db_err"
                                                            || $_GET["error"]=="usr_create_db_err")
                        echo "<p class=&quoterr-box&quot>Something went wrong, please try again.</p>";
                }
            ?>
        </div>
    </body>
</html>