<?php

session_start();
require_once './php/utils/db_conn_handler_script.php';
require_once './php/utils/functions_script.php';
require_once './php/utils/definitions.php';

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
        <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@700&display=swap" rel="stylesheet">
        <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
        <script src="./js/ajax/ajax_utils.js"></script>
        <script src="./js/searchbox_clear.js"></script>
        <script src="./js/navbar_interaction.js"></script>
        <link rel="apple-touch-icon" sizes="180x180" href="./resources/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./resources/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./resources/icons/favicon-16x16.png">
        <link rel="manifest" href="./resources/icons/site.webmanifest">
        <title>Imaged | <?php if(isset($_GET["search"])) echo $_GET["search"]; else echo "Search"; ?> </title>
    </head>

    <body>
        <header>
            <?php include_once './php/elements/navbar.php' ?>   <!-- include il codice della navbar -->
        </header>

        <section id="page_main_section">

            <!-- MAIN SECTION -->
            <div class="search-section">
                <h1 class="search-banner">Search results for "<?php echo $_GET["search"] ?>":</h1>
                <div class="search-delimiter"></div>
                <div class="search-results">

                    <div class="results-section">
                        <h2 class="search-result-type">Profiles:</h2>
                        <div class="search-results-frame">
                            <?php
                                if(isset($_GET["search"])) {
                                    $search = $_GET["search"];
                                    $searchString = "%$search%";
                                    $sql = "SELECT * FROM users INNER JOIN profimage ON users.usrId = profimage.usrId WHERE usrName LIKE ?;";
                                    $stmt = mysqli_stmt_init($conn);
                                    mysqli_stmt_prepare($stmt, $sql);
                                    mysqli_stmt_bind_param($stmt, "s", $searchString);
                                    mysqli_stmt_execute($stmt);
                                    $results = mysqli_stmt_get_result($stmt);
                                    mysqli_stmt_close($stmt);
                                    
                                    while(($entry = mysqli_fetch_assoc($results))) {
                                        // getting the profile image path
                                        if($entry["piIsSet"]==true) {
                                            // recupero il nome dell'immagine del profilo
                                            $filename = "./resources/users/".$entry["usrId"]."/profile".$entry["usrId"]."*";
                                            // recupero l'estensione prendendo il primo match della funzione "glob" e tenendo la parte finale
                                            $file_meta = glob($filename);
                                            $ext = get_ext($file_meta[0]);  // recupero l'estensione del file (il primo match)

                                            $userImg = "./resources/users/".$entry["usrId"]."/profile".$entry["usrId"].".".$ext;
                                        }
                                        else $userImg = $DFLT_PROF_IMG;
                                        echo    "
                                                <div class='profile-search-result'>
                                                    <a href='./profile?user=".$entry["usrName"]."'>
                                                        <img src='".$userImg."' class='profile-search-image' alt='search result'>
                                                    </a>
                                                    <a href='./profile?user=".$entry["usrName"]."'>".$entry["usrName"]."</a>
                                                </div>
                                        ";
                                    }
                                }
                                else echo "<p>No usernames matched your search criteria</p>";
                            ?>
                        </div>
                        <div class="search-delimiter"></div>
                    </div>

                    <div class="results-section">
                        <h2 class="search-result-type">Images by title:</h2>
                        <div class="search-results-frame">
                            <?php
                                if(isset($_GET["search"])) {
                                    $search = $_GET["search"];
                                    $searchString = "%$search%";
                                    $sql = "SELECT * FROM gallery WHERE imgTitle LIKE ? AND usrId IS NOT NULL;";
                                    $stmt = mysqli_stmt_init($conn);
                                    mysqli_stmt_prepare($stmt, $sql);
                                    mysqli_stmt_bind_param($stmt, "s", $searchString);
                                    mysqli_stmt_execute($stmt);
                                    $results = mysqli_stmt_get_result($stmt);
                                    mysqli_stmt_close($stmt);
                                    
                                    while(($entry = mysqli_fetch_assoc($results))) {
                                        $imageName = preg_replace("/\.[^.]+$/", "", $entry["imgName"]);
                                        echo    "
                                                <div class='image-search-result'>
                                                <a href='./image?id=".$imageName."' style='background-image: url(./resources/users/".$entry["usrId"]."/gallery/".$entry["imgName"].")' class='image-search-image'></a>
                                                    <a href='./image?id=".$imageName."'>".$entry["imgTitle"]."</a>
                                                </div>
                                        ";
                                    }
                                }
                                else echo "<p>No images matched your search criteria</p>";
                            ?>
                        </div>
                        <div class="search-delimiter"></div>
                    </div>

                    <div class="results-section">
                        <h2 class="search-result-type">Images by tags:</h2>
                        <div class="search-results-frame">
                            <?php
                                    if(isset($_GET["search"])) {
                                        $search = $_GET["search"];
                                        $searchString = "%$search%";
                                        $sql = "SELECT * FROM gallery WHERE imgTags LIKE ? AND usrId IS NOT NULL;";
                                        $stmt = mysqli_stmt_init($conn);
                                        mysqli_stmt_prepare($stmt, $sql);
                                        mysqli_stmt_bind_param($stmt, "s", $searchString);
                                        mysqli_stmt_execute($stmt);
                                        $results = mysqli_stmt_get_result($stmt);
                                        mysqli_stmt_close($stmt);
                                        
                                        while(($entry = mysqli_fetch_assoc($results))) {
                                            $imageName = preg_replace("/\.[^.]+$/", "", $entry["imgName"]);
                                            echo    "
                                                    <div class='image-search-result'>
                                                    <a href='./image?id=".$imageName."' style='background-image: url(./resources/users/".$entry["usrId"]."/gallery/".$entry["imgName"].")' class='image-search-image'></a>
                                                        <a href='./image?id=".$imageName."'>".$entry["imgTitle"]."</a>
                                                    </div>
                                            ";
                                        }
                                    }
                                    else echo "<p>No images matched your search criteria</p>";
                                ?>
                        </div>
                        <div class="search-delimiter"></div>
                    </div>

                </div>
            </div>

        </section>

        <footer>
            <?php include_once './php/elements/footer.php' ?>   <!-- include il codice del footer -->
        </footer>

    </body>
</html>
