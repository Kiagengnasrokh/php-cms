<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "db_blog"; 

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi ke MySQL gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8");

?>