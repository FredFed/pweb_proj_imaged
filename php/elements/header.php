<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/styles.css">
        <title>Imaged</title>
    </head>
    <body>
        <header>
            <nav>
                <!-- NAV CONTENT - ALL HAS TO CHANGE -->
                <div id="easynav">
                    <ul>
                    <li><a href="./index">Index</a></li>
                    <?php
                        // se l'utente è loggato, mostra il seguente contenuto
                        if(isset($_SESSION["usr"])) {
                            echo "<li><a href='./profile'>Profile</a></li>";
                            echo "<li><a href='./php/utils/logout_script'>Log out</a></li>";
                        }
                        // se l'utente non è loggato, mostra il seguente contenuto
                        else {
                            echo "<li><a href='./php/signup'>Sign up</a></li>";
                            echo "<li><a href='./php/login'>Log in</a></li>";
                        }
                    ?>
                    </ul>
                </div>
            </nav>
        </header>
        <div id="page_main_div">