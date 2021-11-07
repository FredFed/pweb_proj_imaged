<?php

    session_start();
    require_once './utils/db_conn_handler_script.php';
    require_once './utils/functions_script.php';
    include_once './utils/definitions.php';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="upload_to_gallery">
        <form action="./utils/upload_gallery_script.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="img_title" placeholder="Title...">
            <input type="text" name="img_desc" placeholder="Description...">
            <input type="text" name="img_tags" placeholder="Tags...">
            <input type="checkbox" name="img_ls" value="landscape">
            <label id="img_is_landscape" for="img_ls">Landscape mode</label></br>
            <input type="checkbox" name="img_hidden" value="hidden">
            <label id="img_is_hidden" for="img_hidden">Private image</label></br>
            <input type="file" name="gallery_img" accept="image/jpeg, image/png" required>
            <button type="submit" name="submit_img_gallery">POST</button>
        </form>
    </div>
</body>
</html>