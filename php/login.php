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
                            <input type="text" name="usrname" placeholder="Username or e-mail">
                            <input type="password" name="pswd" placeholder="Password">
                            <button type="submit" name="submit_login">Sign in</button>
                            <p class="login-screen-registration">Don't have an account?
                                <a href="./signup.php">Sign up!</a>
                            </p>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </body>
</html>