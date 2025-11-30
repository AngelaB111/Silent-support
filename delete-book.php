<?php
include "connect.php";

$id = $_GET['id'];
$db->query("DELETE FROM books WHERE book_id=$id");

header("Location: admin-library.php");
exit();
?>