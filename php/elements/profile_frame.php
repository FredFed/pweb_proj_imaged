<section class="profile-frame">
                <div class="profile-img-section">
                    <?php   // ########## SCRIPT che mostra l'immagine di profilo corretta ##########
                        $usrid = $_SESSION["usrid"];    // recupero l'id utente
                        // recupero i dati relativi alla profile pic dell'utente
                        $sql_prof_img = "SELECT * FROM profileimg WHERE usrid = $usrid;";
                        $res_prof_img = mysqli_fetch_assoc(mysqli_query($conn, $sql_prof_img));

                        echo "  <div class='profile-img-frame'>";
                            // se l'utente non ha impostato alcuna immagine del profilo
                            if($res_prof_img['isset'] == 0)
                                echo "<img class='profile-img' src='".$DFLT_PROF_IMG."'>";
                            // se l'utente ha impostato un'immagine del profilo
                            else {
                                // recupero il nome dell'immagine del profilo
                                $filename = "./resources/profileimg/profile".$usrid."*";
                                // recupero l'estensione prendendo il primo match della funzione "glob" e tenendo la parte finale
                                $file_meta = glob($filename);
                                $ext = get_ext($file_meta[0]);  // recupero l'estensione del file (il primo match)

                                echo "<img class='profile-img' src='./resources/profileimg/profile".$usrid.".".$ext."?".mt_rand()."'>";
                            }
                            if(isset($_SESSION["usr"])) {
                                /*echo "
                                <form action='./php/utils/del_profimg_script.php' method='POST'>
                                    <button type='submit' name='del_prof_img'>DELETE IMAGE</button>
                                </form>";
                                */
                                // visualizza icona per cambiare immagine profilo
                                echo "  <div class='change-prof-img-frame'>
                                            <form class='form-img-pic' action='./php/utils/upload_profimg_script.php' method='POST' enctype='multipart/form-data'>
                                                <input id='up-img-pic' class='form-img-pic' type='file' name='prof_img' accept='image/jpeg, image/png' required>
                                                <button id='sub-img-pic' class='form-img-pic' type='submit' name='submit_prof_img'>UPLOAD IMAGE</button>
                                            </form>
                                            <i class='bx bxs-camera-plus change-prof-img-icon' ></i>
                                        </div>";
                            }
                        echo "  </div>";
                    ?>
                    
                    <?php
                        if(isset($_GET["err"])) {
                            if($_GET["err"]=="bad_ext")
                            echo "<p class='err-box'>Only .jpeg, .jpg, .png files are accepted</p>";
                            else if($_GET["err"]=="up_err")
                            echo "<p class='err-box'>Server err, please try again</p>";
                            else if($_GET["err"]=="sz_2_lg")
                            echo "<p class='err-box'>Max file size is 10MB</p>";
                        }
                    ?>
                    <a href='./php/utils/logout_script'>Log out</a>
                </div>
</sectionZ>