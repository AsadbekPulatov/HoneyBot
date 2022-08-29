<?php

$servername = "us-cdbr-east-06.cleardb.net";
$username = "b390739f1d1b38";
$password = "1ff7fe6a";
$database = "heroku_89d2f58e3937fcc";

$connect = new mysqli($servername, $username, $password, $database);

//if ($connect->connect_error) {
//    die("Connection failed: " . $connect->connect_error);
//}
//echo "Connected successfully";