<?php

$dbServername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "imaged";

$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

if(!$conn) {
    die("Connection failure: unable to connect to DB. " . mysqli_connect_error());
}
?>