<?php

session_start();    // avvia la sessione
$redirect = null;
if(isset($_SESSION["prevurl"])) $redirect = $_SESSION["prevurl"];
session_unset();    // termina la sessione
session_destroy();  // distruggi le variabili di sessione
if(isset($redirect)) header("location: ".$redirect);
else header("location: ../../");   // ritorna alla HomePage

exit();

?>