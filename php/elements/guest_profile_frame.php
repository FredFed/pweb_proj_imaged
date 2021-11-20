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
                $filename = "./resources/profileimg/profile".$profileId."*";
                // recupero l'estensione prendendo il primo match della funzione "glob" e tenendo la parte finale
                $file_meta = glob($filename);
                $ext = get_ext($file_meta[0]);  // recupero l'estensione del file (il primo match)
                echo "  <div class='profile-img-frame' style='background-image: url(./resources/profileimg/profile".$profileId.".".$ext."?".mt_rand().");'>";
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
    </div>
</section>