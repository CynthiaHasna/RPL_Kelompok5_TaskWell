<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database_name = "taskwell"; 
$port = 3307;

$koneksi = new mysqli($hostname, $username, $password, $database_name, $port);

if($koneksi->connect_error) {
    echo "koneksi database rusak";
    die ("eror");
    } else {

}

?>