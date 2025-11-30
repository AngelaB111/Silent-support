<?php
include "connect.php";

$title = $_POST['title'];
$author = $_POST['author'];

$cover = "uploads/covers/" . uniqid() . "_" . $_FILES['cover_image']['name'];
$pdf = "uploads/pdfs/" . uniqid() . "_" . $_FILES['pdf_file']['name'];

move_uploaded_file($_FILES['cover_image']['tmp_name'], $cover);
move_uploaded_file($_FILES['pdf_file']['tmp_name'], $pdf);

$stmt = $db->prepare("INSERT INTO books (title, author, cover_image, pdf_file) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $title, $author, $cover, $pdf);

$stmt->execute();

header("Location: dashboard.php");
exit();
?>