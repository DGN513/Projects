<?php
$conn = new mysqli("localhost", "root", "root", "news");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>