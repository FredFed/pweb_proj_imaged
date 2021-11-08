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
                            <i class='bx bx-x searchbox-clear'></i>
                            <input type="text" class="searchbox" name="searchbox" placeholder="Search">
                            <i class='bx bx-search-alt searchbox-search'></i>
                        </form>
                    </div>

                    <div class="upload-image-frame">
                        <a class="upload-link" href="./php/upload">
                            <div class="upload-image-button button" tabindex="1">
                                <i class='bx bx-image-add upload-image-icon'></i>
                                <p class="button-text">Post</p>
                            </div>
                        </a>
                    </div>

                    <div class="nav-icons-frame">
                        <?php
                            if(isset($_SESSION["usr"])) { echo "
                                <ul class='nav-icons-list'>
                                    <li>
                                        <a href='#'><i class='bx bx-message nav-icon'></i></a>
                                        <!-- <i class='bx bxs-message-detail nav-icon'></i> -->
                                    </li>
                                    <li>
                                        <a href='#'><i class='bx bx-square nav-icon'></i></a>
                                        <!-- <i class='bx bxs-notification nav-icon'></i> -->
                                    </li>
                                </ul>";
                            }
                        ?>
                    </div>
                    
                    <div class="nav-profile-login-frame">
                    <?php
                            // se l'utente è loggato, mostra il seguente contenuto
                            if(isset($_SESSION["usr"])) {
                                $usrid = $_SESSION["usrid"];    // recupero l'id utente
                                // recupero i dati relativi alla profile pic dell'utente
                                $sql_prof_img = "SELECT * FROM profileimg WHERE usrid = $usrid ;";
                                $res_prof_img = mysqli_fetch_assoc(mysqli_query($conn, $sql_prof_img));

                                echo "<div class='nav-profile-frame'>";
                                // se l'utente non ha impostato alcuna immagine del profilo
                                if($res_prof_img['isset'] == 0)
                                    echo "  <a class='nav-profile-image-frame' href='./profile'>
                                                <img class='nav-profile-img' src='".$DFLT_PROF_IMG."'>
                                            </a>";
                                // se l'utente ha impostato un'immagine del profilo
                                else {
                                    // recupero il nome dell'immagine del profilo
                                    $filename = "./resources/profileimg/profile".$usrid."*";
                                    // recupero l'estensione prendendo il primo match della funzione "glob" e tenendo la parte finale
                                    $file_meta = glob($filename);
                                    $ext = get_ext($file_meta[0]);  // recupero l'estensione del file (il primo match)

                                    echo "  <a class='nav-profile-image-frame' href='./profile'>
                                                <img class='nav-profile-img' src='./resources/profileimg/profile".$usrid.".".$ext."?".mt_rand()."'>
                                            </a>";
                                }
                                echo "</div>";
                            }
                            // se l'utente non è loggato, mostra il seguente contenuto
                            else {
                                echo "  <div class='nav-login-frame'>
                                            <ul class='nav-login-list'>
                                                <li class='nav-menu-item'><a href='./php/login'>
                                                    <div class='nav-login-button button'>
                                                        <i class='bx bx-log-in-circle login-icon' ></i>
                                                        <p class='button-text'>Log in</p>
                                                    </div>
                                                </a></li>
                                                <li class='nav-menu-item'><a href='./php/signup'>
                                                    <div class='nav-signup-button button'>
                                                        <i class='bx bx-spreadsheet signup-icon' ></i>
                                                        <p class='reverse-button-text'>Sign up</p>
                                                    </div>
                                                </a></li>
                                            </ul>
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
