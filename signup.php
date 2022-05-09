<?php

session_start();
if(isset($_SESSION["usrid"])) {
    header("location: ./profile?user=".$_SESSION["usrname"]);
}

?>


<!DOCTYPE html>
<html lang="it">
<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/styles.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@600&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@700&display=swap" rel="stylesheet">
        <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
        <link rel="apple-touch-icon" sizes="180x180" href="./resources/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./resources/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./resources/icons/favicon-16x16.png">
        <link rel="manifest" href="./resources/icons/site.webmanifest">
        <script src="./js/form_button.js"></script>
        <title>Imaged | Sign up</title>
    </head>
    <body>
        <div id="main_div_signup">
            <div class="container-section-signup">
                <section class="section-login-signup">
                    <!-- LOGO -->
                    <div class="form-header">
                        <h3><a href="./" class="login-signup-logo">imaged</a></h3>
                        <form id="back-button-form" action="<?php if(isset($_SESSION)) echo $_SESSION["prevurl"]; else echo "./"; ?>" method="post">
                            <button class="back-button" type="submit" value="Go back">
                                <i class='bx bx-left-arrow-alt'></i>
                            </button>
                        </form>
                    </div>
                    <!-- FORM -->
                    <div class="div-signup-form">
                        <form id="signup-form" class="multi-function-form" action="./php/utils/signup_script.php" method="post">
                            <!-- DESCR -->
                            <h2 class="login-signup-disclaimer">Sign up to unlock all the best features!</h2>
                            <!-- INPUT ROW -->
                            <input class="text-box input-form" type="text" name="username" placeholder="Username">
                            <input class="text-box input-form" type="text" name="email" placeholder="e-mail">
                            <input class="text-box input-form" type="password" name="pswd" placeholder="Password">
                            <input class="text-box input-form" type="password" name="rep_pswd" placeholder="Repeat Password">
                            <button class="button form-button site-font" type="submit" name="submit_signup">Sign Up</button>
                            <p class="login-screen-registration">Already have an account?
                                <a href="./login">Log in!</a>
                            </p>
                            <?php
                                include_once './php/elements/error_messages.php';
                            ?>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </body>
</html>