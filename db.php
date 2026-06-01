<?php
$conn = mysqli_connect("localhost","root","","marketplace");

if (!$conn) {
    die("DB Error: " . mysqli_connect_error());
}
?>