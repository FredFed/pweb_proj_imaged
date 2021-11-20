<div class="profile-frame">
    <div class="profile-img-section">
        <?php   // ########## SCRIPT che mostra l'immagine di profilo corretta ##########
        
            // recupero i dati relativi alla profile pic dell'utente
            $sql_prof_img = "SELECT * FROM profimage WHERE usrId = $profileId;";
            $res_prof_img = mysqli_fetch_assoc(mysqli_query($conn, $sql_prof_img));

            // se l'utente non ha impostato alcuna immagine del profilo
            if($res_prof_img['piIsSet'] == 0)
                echo "  <div class='profile-img-frame' style='background-image: url(".$DFLT_PROF_IMG.");'>";
            // se l'utente ha impostato un'immagine del profilo
            else {
                // recupero il nome dell'immagine del profilo
                $filename = "./resources/profileimg/profile".$profileId."*";
                // recupero l'estensione prendendo il primo match della funzione "glob" e tenendo la parte finale
                $file_meta = glob($filename);
                $ext = get_ext($file_meta[0]);  // recupero l'estensione del file (il primo match)
                echo "  <div class='profile-img-frame' style='background-image: url(./resources/profileimg/profile".$profileId.".".$ext."?".mt_rand().");'>";
            }
                if($isOwnProfile) {
                    // visualizza icona per cambiare immagine profilo
                    echo "  <form id='form-img-pic' class='form-img-pic' action='./php/utils/upload_profimg_script.php' method='POST' enctype='multipart/form-data'>
                                <input id='up-img-pic' class='form-img-pic' type='file' name='prof_img' accept='image/jpeg, image/png' required>
                                <button id='sub-img-pic' class='form-img-pic' type='submit' name='submit_prof_img'>
                                    <i class='bx bxs-camera-plus change-prof-img-icon' ></i>
                                </button>
                            </form>";
                }
                echo "  </div>";
            /* echo "
                    <form action='./php/utils/del_profimg_script.php' method='POST'>
                        <button type='submit' name='del_prof_img'>DELETE IMAGE</button>
                    </form>";
            */
        ?>
        <a href='./php/utils/logout_script'>Log out</a>
    </div>

    <div class="profile-info-section">
        <p class="profile-name"><?php echo $profileName; echo $badge; ?><br></p>
        <p class="profile-desc">
            <?php if(($numPosts = posts_number($conn, $profileId)) != -1) echo $numPosts." post"; ?>
            <?php if(($usrRank = user_rank($numPosts)) != -1) echo ("  •  ".$usrRank); ?>
        </p>
    </div>

    <?php
        include_once './php/elements/error_messages.php';
    ?>

</div>