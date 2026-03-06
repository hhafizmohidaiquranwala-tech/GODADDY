<?php
$host = 'localhost';
$user = 'rsoa_rsoa378_42';
$pass = '123456';
$dbname = 'rsoa_rsoa378_42';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
