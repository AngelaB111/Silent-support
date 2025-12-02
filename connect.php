<?php
session_start();

$db = mysqli_connect('localhost', 'root', '', 'silents_db'); 

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
?>