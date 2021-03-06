<nav>
    <div class="nav-frame">

        <div class="logo-frame">
            <a class="logo-link" href="./">
                <div class="logo-frame-back-1"></div>
                <div class="logo-frame-back-2"></div>
                <div class="logo-frame-back-3"></div>
                <div class="logo-frame-1">
                    <p class="logo l1">i</p>
                    <p class="logo l2">m</p>
                    <p class="logo l3">a</p>
                    <p class="logo l4">g</p>
                    <p class="logo l5">e</p>
                </div>
                <div class="logo-frame-2">
                    <p class="logo l6">d</p>
                </div>
            </a>
        </div>

        <div class="searchbox-frame">
            <form class="searchbox-form" action="search" method="GET">
                <div class="clear-icon-frame"><i class='bx bx-x searchbox-clear'></i></div>
                <input type="text" class="searchbox text-box" name="search" placeholder="Search">
                <button class="search-icon-frame"><i class='bx bx-search-alt searchbox-search'></i></button>
            </form>
        </div>

        <?php
            echo '
            <div class="upload-image-button-frame">
                <a class="upload-link" href="./upload">
                    <div class="upload-image-button button">
                        <i class="bx bx-image-add upload-image-icon"></i>   
                        <p class="button-text">Post</p>
                    </div>
                </a>
            </div>';
        ?>
        
        <div class="nav-profile-login-frame">
        <?php
                // se l'utente è loggato, mostra il seguente contenuto
                if(isset($_SESSION["usrid"])) {
                    $usrid = $_SESSION["usrid"];    // recupero l'id utente
                    $usrname = $_SESSION["usrname"];    // recupero il nome utente
                    // recupero i dati relativi alla profile pic dell'utente
                    $sql_prof_img = "SELECT * FROM profimage WHERE usrId = $usrid ;";
                    $res_prof_img = mysqli_fetch_assoc(mysqli_query($conn, $sql_prof_img));

                    echo "<div class='nav-profile-frame'>";
                    // se l'utente non ha impostato alcuna immagine del profilo
                    if($res_prof_img['piIsSet'] == 0)
                        echo "  <a class='nav-profile-image-frame' href='./profile?user=".$usrname."'>
                                    <img class='nav-profile-img' src='".$DFLT_PROF_IMG."' alt='profile image'>
                                </a>";
                    // se l'utente ha impostato un'immagine del profilo
                    else {
                        // recupero il nome dell'immagine del profilo
                        $filename = "./resources/users/".$usrid."/profile".$usrid."*";
                        // recupero l'estensione prendendo il primo match della funzione "glob" e tenendo la parte finale
                        $file_meta = glob($filename);
                        $ext = get_ext($file_meta[0]);  // recupero l'estensione del file (il primo match)

                        echo "  <a class='nav-profile-image-frame' href='./profile?user=".$usrname."'>
                                    <img class='nav-profile-img' src='./resources/users/".$usrid."/profile".$usrid.".".$ext."?".mt_rand()."' alt='profile image'>
                                </a>";
                    }
                    echo "</div>";
                }
                // se l'utente non è loggato, mostra il seguente contenuto
                else {
                    echo "  <div class='nav-login-frame'>
                                <ul class='nav-login-list'>
                                    <li class='nav-menu-item'><a href='./login'>
                                        <div class='nav-login-button button'>
                                            <i class='bx bx-log-in-circle login-icon' ></i>
                                            <p class='button-text'>Log in</p>
                                        </div>
                                    </a></li>
                                    <li class='nav-menu-item'><a href='./signup'>
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

    <?php
        if(isset($_SESSION["usrid"])) echo '
            <div class="user-menu-frame hidden">
                <ul class="user-menu">
                    <li class="user-menu-item"><a href="./profile?user='.$_SESSION["usrname"].'&gallery=public"><i class="bx bxs-user user-menu-icon"></i>Profile</a></li>
                    <li class="user-menu-item"><a href="./profile?user='.$_SESSION["usrname"].'&gallery=saved"><i class="bx bxs-save user-menu-icon" ></i>Saved</a></li>
                    <li class="user-menu-item user-logout-item"><a href="./php/utils/logout_script.php"><i class="bx bx-log-out-circle user-menu-icon"></i>LogOut</a></li>
                </ul>
            </div>';
    ?>

</nav>