<section class="profile-frame">
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
                $filename = "./resources/users/".$profileId."/profile".$profileId."*";
                // recupero l'estensione prendendo il primo match della funzione "glob" e tenendo la parte finale
                $file_meta = glob($filename);
                $ext = get_ext($file_meta[0]);  // recupero l'estensione del file (il primo match)
                echo "  <div class='profile-img-frame'
                             style='background-image: url(./resources/users/".$profileId."/profile".$profileId.".".$ext."?".mt_rand().");'>";
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
    </div>

    <div class="profile-info-section">
        <h2 class="profile-name"><?php echo ucfirst($profileName); echo $badge; ?><br></h2>
        <p class="profile-desc">
            <?php if(($numPosts = posts_number($conn, $profileId)) != -1) echo $numPosts." posts"; ?>
            <?php   if($profileLvl==1) echo ("  •  moderator");
                    else if($profileLvl==2) echo ("  •  admin");
                    else if(($usrRank = user_rank($numPosts)) != -1) echo ("  •  ".$usrRank);
            ?>
        </p>
    </div>

    <div class="profile-admin-icons-frame">
        <?php
            // showing block icons to admins
            if(!($isOwnProfile) && $priviledge==2 && $profileLvl<2) {
                if($profileBlock) {$block_status="blocked"; $block_icon="bx bxs-lock";}
                else {$block_status="unblocked"; $block_icon="bx bx-lock-open";}
                echo "<button class='block-button ".$block_status."'><i class='block-icon ".$block_icon."'></i></button>";
            }
            else if(($isOwnProfile) && ($profileBlock)) echo "<p class='block-message'>You are blocked: you cannot post new images</p>";

            // showing mod icons to admins
            if(!($isOwnProfile) && $priviledge==2 && $profileLvl<2) {
                if($profileLvl==0) {$status=""; $icon="bx bx-crown";}
                else {$status="mod"; $icon="bx bxs-crown";}
                echo "<button class='mod-button ".$status."'><i class='mod-icon ".$icon."'></i></button>";
            }
        ?>
    </div>

    <?php
        include_once './php/elements/error_messages.php';
    ?>

</section>