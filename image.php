<?php

session_start();
require_once './php/utils/db_conn_handler_script.php';
require_once './php/utils/functions_script.php';
include_once './php/utils/definitions.php';

// controllo se l'URL rappresenta un'immagine valida
if(!isset($_GET["id"])) header("location: ./page_not_found");   // bad URL
else $imgKey = $_GET["id"].".%";

$sql = "SELECT * FROM gallery WHERE imgName LIKE ? AND imgHidden=0;";
$stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ./index?err=db_err");   // DB error
}
mysqli_stmt_bind_param($stmt, "s", $imgKey); // binding tra chiave immagine e statement
mysqli_stmt_execute($stmt);     // eseguo lo statement
$image_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));    // recupero i risultati della query
mysqli_stmt_close($stmt);
if(!$image_data || ($image_data["imgHidden"]==true && 
        (!isset($_SESSION["usrid"]) || ($image_data["usrId"]!=$_SESSION["usrid"]))) || 
        (($image_data["imgBlock"]==true && $_SESSION["usrlvl"]==0) && (!isset($_SESSION["usrid"]) || 
        $image_data["usrId"]!=$_SESSION["usrid"])))
                header("location: ./page_not_found");  // l'immagine non esiste o è bloccata ed appartiene ad un altro utente
else {
    $imgAuthor = $image_data["usrId"];
    $imgId = $image_data["imgId"];
    $imgName = $image_data["imgName"];
    $imgTitle = $image_data["imgTitle"];
    $imgDesc = $image_data["imgDesc"];
    $imgTags = $image_data["imgTags"];
    $imgBlock = $image_data["imgBlock"];
    $imgHidden = $image_data["imgHidden"];
    $imgDate = $image_data["imgDate"];
}

// recupero l'id dell'utente (se loggato)
if(isset($_SESSION["usrid"])) $usrid = $_SESSION["usrid"];
else $usrid = null;

// se l'utente è il proprietario dell'immagine, memorizza l'informazione
if($usrid==$imgAuthor) $isOwnImage = true;
else $isOwnImage = false;

// recupero il nome utente dell'autore dell'immagine
if($isOwnImage && isset($_SESSION["usrid"])) $authorName = $_SESSION["usrname"];
else {
    $sql = "SELECT * FROM users WHERE usrId='$imgAuthor';";
    $res = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    if($res) $authorName = $res["usrName"];
    else $authorName = "";
}

// se l'utente è loggato, controllare se ha un like/save sull'immagine
$isLiked = false;
$isSaved = false;
if($usrid) {
    // Query che controlla se l'utente ha un like sull'immagine corrente
        $sql = "SELECT * 
                FROM likes
                WHERE usrId='$usrid' AND imgId='$imgId';";
        $img_info_res = mysqli_query($conn, $sql);  // no stmt (param generati dal server)
        if(mysqli_num_rows($img_info_res) != 0) $isLiked=true;

        // Query che controlla se l'utente ha salvato l'immagine corrente
        $sql = "SELECT * 
                FROM saved 
                WHERE usrId='$usrid' AND imgId='$imgId';";
        $img_info_res = mysqli_query($conn, $sql);  // no stmt (param generati dal server)
        if(mysqli_num_rows($img_info_res) != 0) $isSaved=true;
}

// recupero il numero di likes dell'immagine
// Query che controlla il numero di likes dell'immagine corrente
$sql = "SELECT count(*) as likeNum 
        FROM likes 
        WHERE imgId='$imgId';";
$img_info_res = mysqli_query($conn, $sql);  // no stmt (param generati dal server)
if(mysqli_num_rows($img_info_res) != 0) {
    $img_info = mysqli_fetch_assoc($img_info_res);
    $likeCount = $img_info["likeNum"];
}

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
        <script src="./js/loaders/likes_saves_loader.js"></script>
        <script src="./js/like_save_script.js"></script>
        <script src="./js/loaders/comments_loader.js"></script>
        <script src="./js/loaders/image_block_loader.js"></script>
        <script src="./js/searchbox_clear.js"></script>
        <script src="./js/navbar_interaction.js"></script>
        <link rel="apple-touch-icon" sizes="180x180" href="./resources/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./resources/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./resources/icons/favicon-16x16.png">
        <link rel="manifest" href="./resources/icons/site.webmanifest">
        <title>Imaged | <?php echo $imgTitle; ?></title>
    </head>

    <body>
        <header>
            <?php include_once './php/elements/navbar.php' ?>   <!-- include il codice della navbar -->
        </header>

        <section id="page_main_section">

            <!-- MAIN SECTION -->

            <div class="image-container">
                <div class="image-comment-frame">
                    <div class="image-frame">
                        <?php echo "<h2 id='".$imgId."' class='image-title'>".$imgTitle."</h2>"; ?>

                        <?php   if($imgAuthor) echo "<img src='./resources/users/".$imgAuthor."/gallery/".$imgName."' alt='".$imgTitle."' class='image'>";
                                else echo "<img src='./resources/users/default/gallery/".$imgName."' alt='".$imgTitle."' class='image'>"
                        ?>

                        <div class="image-status-bar">
                            <?php
                                if($imgAuthor) {
                                    $likeClass = ""; $likeIcon="bx-heart";
                                    $saveClass = ""; $saveIcon="bx-bookmark";
                                    $blockClass = ""; $blockIcon="";
                                    if($isLiked) {$likeClass = "liked"; $likeIcon = "bxs-heart";}
                                    if($isSaved) {$saveClass = "saved"; $saveIcon = "bxs-bookmark";}
                                    if($imgBlock) {$blockClass = "blocked"; $blockIcon = "block-color";}
                                    echo " <button id='like_".$imgId."' 
                                                            class='gallery-image-buttons like-button ".$likeClass." image-button'><i class='bx ".$likeIcon."'></i></button>";
                                    echo "  <p class='gallery-image-counter image-button'>".$likeCount."</p>";
                                    echo "  <p>&nbsp;&nbsp;</p>";
                                    echo " <button id='save_".$imgId."' 
                                                            class='gallery-image-buttons save-button ".$saveClass." image-button'><i class='bx ".$saveIcon."'></i></button>";
                                    if(isset($_SESSION["usrid"]) && $_SESSION["usrlvl"]>0)
                                    echo "  <button id='block_".$imgId."'
                                                            class='gallery-image-buttons block-button ".$blockClass." image-button'><i class='bx bx-block ".$blockIcon."'></i></button>";
                                }
                            ?>
                        </div>

                        <div class="image-info">
                            <?php if($imgAuthor) echo "<a href='./profile?user=".$authorName."' class='image-author image-desc'>".$authorName."</a>"; ?>
                            <p class="image-desc">&nbsp;-&nbsp;</p>
                            <p class="image-desc"><?php echo $imgDesc; ?>
                            <?php
                                $tags=$imgTags;
                                if(!empty($tags)) {
                                    $tags = preg_replace("/[#$%^&*()+=\-\[\]\';,.\/{}|\":<>?~\\\\]/", ' ', $tags);
                                    $tags = preg_replace("/\s{2,}/", ' ', $tags);
                                    if($tags[0]!=' ') $tags = ' '.$tags;
                                    $tags = preg_replace("/\s/", " #", $tags);
                                }
                            ?>
                            <p class="image-tags">Tags: <?php echo $tags; ?></p>
                        </div>
                    </div>

                    <?php
                        if($imgAuthor) { echo '
                            <div class="input-comment-frame">
                                <input type="text" class="input-comment text-box" name="comment" placeholder="Write your comment">
                                <button class="submit-comment-button button site-font" name="submit_comment">Submit</button>
                            </div>

                            <div class="comment-frame">
                                <p id="comments" class="comment-counter">Comments: </p>
                                <div class="delimiter"></div>
                            </div>';
                        }
                    ?>
                </div>

                <aside class="suggested-container">
                    <div class="suggested-frame">
                        <h2 class="image-title">Popular</h2>
                        <div class="popular-suggested-frame">
                            <?php
                                $sql ="SELECT *, gallery.usrId AS usrId, count(*) AS numLikes 
                                FROM gallery INNER JOIN likes ON gallery.imgId = likes.imgId
                                WHERE gallery.imgHidden=0 AND gallery.imgBlock=0  AND gallery.usrId IS NOT NULL 
                                GROUP BY gallery.imgId 
                                ORDER BY numLikes DESC 
                                LIMIT 4";
                                $query_res = mysqli_query($conn, $sql);

                                while($entry = mysqli_fetch_assoc($query_res)) {
                                    $imageName = preg_replace("/\.[^.]+$/", "", $entry["imgName"]);
                                    echo    "
                                        <a href='./image?id=".$imageName."' style='background-image: url(./resources/users/".$entry["usrId"]."/gallery/".$entry["imgName"].")' class='popular-suggested-image'></a>
                                        <a href='./image?id=".$imageName."'>".$entry["imgTitle"]."</a>
                                    ";
                                }
                            ?>
                        </div>
                    </div>
                    
                    <div class="suggested-frame">
                        <h2 class="image-title">Latest</h2>
                        <div class="popular-suggested-frame">
                            <?php
                                $sql ="SELECT * 
                                FROM gallery 
                                WHERE imgHidden=0 AND imgBlock=0 AND usrId IS NOT NULL 
                                ORDER BY imgDate DESC 
                                LIMIT 4";
                                $query_res = mysqli_query($conn, $sql);

                                while($entry = mysqli_fetch_assoc($query_res)) {
                                    $imageName = preg_replace("/\.[^.]+$/", "", $entry["imgName"]);
                                    echo    "
                                        <a href='./image?id=".$imageName."' style='background-image: url(./resources/users/".$entry["usrId"]."/gallery/".$entry["imgName"].")' class='popular-suggested-image'></a>
                                        <a href='./image?id=".$imageName."'>".$entry["imgTitle"]."</a>
                                    ";
                                }
                            ?>
                        </div>
                    </div>
                </aside>
            </div>

        </section>

        <footer>
            <?php include_once './php/elements/footer.php' ?>   <!-- include il codice del footer -->
        </footer>

    </body>
</html>
