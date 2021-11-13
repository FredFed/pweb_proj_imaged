<?php

session_start();    // avvia la sessione
session_unset();    // termina la sessione
session_destroy();  // distruggi le variabili di sessione
header("location: ../../");   // ritorna alla HomePage

exit();

?>