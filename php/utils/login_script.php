<?php

if(isset($_POST["submit_login"])) {
    echo "signed in";
}
else {
    header("location: ../login.php");
}
?>