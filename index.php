<?php

session_start();
require_once './php/utils/db_conn_handler_script.php';
require_once './php/utils/functions_script.php';
include_once './php/utils/definitions.php';

?>


<?php
    /*include_once './php/elements/header.php';*/
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
        <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
        <title>Imaged</title>
    </head>
    <body>
        <header>
            <nav>
                <!-- NAV CONTENT - ALL HAS TO CHANGE -->
                <div class="nav-frame">
                    <div class="logo-frame">
                        <a class="logo-link" href="./index">
                            <div class="logo-frame-1">
                                <p class="logo">image</p>
                            </div>
                            <div class="logo-frame-2">
                                <p class="logo">d</p>
                            </div>
                        </a>
                    </div>

                    <div class="searchbox-frame" action="searchbox.php" method="POST">
                        <form class="searchbox-form">
                            <i class='bx bx-x searchbox-clear' ></i>
                            <input type="text" class="searchbox" name="searchbox" placeholder="Search">
                            <i class='bx bx-search-alt searchbox-search'></i>
                        </form>
                    </div>
                    <div class="nav-buttons">      <!-- TODO change name -->
                        <ul class="nav-menu-list">
                        <?php
                            // se l'utente è loggato, mostra il seguente contenuto
                            if(isset($_SESSION["usr"])) {
                                echo "<li class='nav-menu-item'><a href='./profile'>Profile</a></li>";
                                echo "<li class='nav-menu-item'><a href='./php/upload'>Upload image</a></li>";
                                echo "<li class='nav-menu-item'><a href='./php/utils/logout_script'>Log out</a></li>";
                            }
                        ?>
                        </ul>
                    </div>
                    <div class="nav-profile-login-frame">
                    <?php
                            // se l'utente è loggato, mostra il seguente contenuto
                            if(isset($_SESSION["usr"])) {
                                $usrid = $_SESSION["usrid"];    // recupero l'id utente
                                // recupero i dati relativi alla profile pic dell'utente
                                $sql_prof_img = "SELECT * FROM profileimg WHERE usrid = $usrid ;";
                                $query_prof_img = mysqli_fetch_assoc(mysqli_query($conn, $sql_prof_img));

                                echo "<div class='nav-profile-frame'>";
                                // se l'utente non ha impostato alcuna immagine del profilo
                                if($query_prof_img['isset'] == 0)
                                    echo "  <a class='nav-profile-image-frame' href='./profile'>
                                                <img class='nav-profile-img' src='".$DFLT_PROF_IMG."'>
                                            </a>";
                                // se l'utente ha impostato un'immagine del profilo
                                else {
                                    // recupero il nome dell'immagine del profilo
                                    $filename = "./resources/profileimg/profile".$usrid."*";
                                    // recupero l'estensione prendendo il primo match della funzione "glob" e tenendo la parte finale
                                    $file_meta = glob($filename);
                                    $file_ext = explode(".", $file_meta[0]);    // il primo match della ricerca glob è il file corretto
                                    $ext = end($file_ext);   // prendiamo il token dopo il '.', ovvero l'estensione

                                    echo "  <a class='nav-profile-image-frame' href='./profile'>
                                                <img class='nav-profile-img' src='./resources/profileimg/profile".$usrid.".".$ext."?".mt_rand()."'>
                                            </a>";
                                }
                                echo "</div>";
                            }
                            // se l'utente non è loggato, mostra il seguente contenuto
                            else {
                                echo "  <div class='nav-login-frame'>
                                            <li class='nav-menu-item'><a href='./php/signup'>Sign up</a></li>
                                            <li class='nav-menu-item'><a href='./php/login'>Log in</a></li>
                                        </div>";
                            }
                        ?>
                    </div>
                </div>
            </nav>
        </header>
        <div id="page_main_div">

            <!-- MAIN DIV -->

        </div>
    </body>
    <footer>
        <!-- FOOTER CONTENT -->
    </footer>
</html>


<?php
    /*include_once './php/elements/footer.php';*/
?>
