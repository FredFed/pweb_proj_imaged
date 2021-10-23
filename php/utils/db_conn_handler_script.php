<?php

// setto le variabili necessarie per la connessione al DB MySQL
$dbServername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "imaged";

// salvo un riferimento alla connessione nella variabile $conn
$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

if(!$conn) {    // se la connessione al DB fallisce, uccidi il processo e logga un errore
    die("Connection failure: unable to connect to DB. " . mysqli_connect_error());
}
?>