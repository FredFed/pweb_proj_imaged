<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name=”viewport” content=”width=device-width, initial-scale=1”>
        <link rel="stylesheet" href="css/styles.css">
        <title>Imaged</title>
    </head>
    <body>
        <header>
            <nav>
                <!-- NAV CONTENT - ALL HAS TO CHANGE -->
                <div id="easynav">
                    <ul>
                    <li><a href="./index.php">Index</a></li>
                    <?php
                        // se l'utente è loggato, mostra il seguente contenuto
                        if(isset($_SESSION["usr"])) {
                            echo "<li><a href='./php/logout.php'>Log out</a></li>";
                        }
                        // se l'utente non è loggato, mostra il seguente contenuto
                        else {
                            echo "<li><a href='./php/signup.php'>Sign up</a></li>";
                            echo "<li><a href='./php/login.php'>Log in</a></li>";
                        }
                    ?>
                    </ul>
                </div>
            </nav>
        </header>
        <div id="page_main_div">