<?php

session_start();
if(isset($_SESSION["usrid"])) {
    header("location: ../profile?user=".$_SESSION["usrname"]);
}

?>


<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name=”viewport” content=”width=device-width, initial-scale=1”>
        <link rel="stylesheet" href="../css/styles.css">
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
                include_once './elements/error_messages.php';
            ?>
        </div>
    </body>
</html>