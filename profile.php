<?php

session_start();
if(!isset($_SESSION["usr"])) {
    header("location: ./php/login?err=bad_login");
}
require_once './php/utils/db_conn_handler_script.php';
require_once './php/utils/functions_script.php';
require_once './php/utils/definitions.php';

?>

<?php
    include_once './php/elements/header.php';
?>
            <!-- MAIN DIV -->
    <container class="profile-frame">
        <div class="profile-img-section">
            <?php   // ########## SCRIPT che mostra l'immagine di profilo corretta ##########
                $usrid = $_SESSION["usrid"];    // recupero l'id utente
                // recupero i dati relativi alla profile pic dell'utente
                $sql_prof_img = "SELECT * FROM profileimg WHERE usrid = $usrid ;";
                $query_prof_img = mysqli_fetch_assoc(mysqli_query($conn, $sql_prof_img));

                echo "<div class='profile-img-frame'>";
                // se l'utente non ha impostato alcuna immagine del profilo
                if($query_prof_img['isset'] == 0)
                    echo "<img class='profile-img' src='".$DFLT_PROF_IMG."'>";
                // se l'utente ha impostato un'immagine del profilo
                else {
                    // recupero il nome dell'immagine del profilo
                    $filename = "./resources/profileimg/profile".$usrid."*";
                    // recupero l'estensione prendendo il primo match della funzione "glob" e tenendo la parte finale
                    $file_meta = glob($filename);
                    $file_ext = explode(".", $file_meta[0]);    // il primo match della ricerca glob è il file corretto
                    $ext = end($file_ext);   // prendiamo il token dopo il '.', ovvero l'estensione

                    echo "<img class='profile-img' src='./resources/profileimg/profile".$usrid.".".$ext."?".mt_rand()."'>";
                }
                echo "</div>";
            ?>
        </div>
    </container>
    <container class="gallery-frame">
        <div class="gallery-section">
            <h2 id="gallery-title">Your uploads</h2>
            <div class="gallery">
                <!-- GALLERY GOES HERE -->
            </div>
        </div>
    </container>
    <?php
        if(isset($_SESSION["usr"])) { echo"
            <form action='./php/utils/upload_profimg_script.php' method='POST' enctype='multipart/form-data'>
                <input type='file' name='prof_img' accept='image/jpeg, image/png' required>
                <button type='submit' name='submit_prof_img'>UPLOAD IMAGE</button>
            </form>";
            echo "<br>";
            echo "
            <form action='./php/utils/del_profimg_script.php' method='POST'>
                <button type='submit' name='del_prof_img'>DELETE IMAGE</button>
            </form>";
        }
    ?>

    <?php
        if(isset($_GET["err"])) {
            if($_GET["err"]=="bad_ext")
            echo "<p class='err-box'>Only .jpeg, .jpg, .png files are accepted</p>";
            else if($_GET["err"]=="bad_ext")
            echo "<p class='err-box'>Server err, please try again</p>";
            else if($_GET["err"]=="sz_2_lg")
            echo "<p class='err-box'>Max file size is 10MB</p>";
        }
    ?>
            <!-- END MAIN DIV -->

<?php
    include_once './php/elements/footer.php';
?>