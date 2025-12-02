<?php
include "connect.php";

$id = intval($_GET['id']);

$book = $db->query("SELECT * FROM books WHERE book_id=$id")->fetch_assoc();

$title = $_POST['title'];
$author = $_POST['author'];
$category = $_POST['category'];   // <-- NEW

$cover = $book['cover_image'];
$pdf = $book['pdf_file'];

if (!empty($_FILES['cover_image']['name'])) {
    $cover = "uploads/covers/" . uniqid() . "_" . $_FILES['cover_image']['name'];
    move_uploaded_file($_FILES['cover_image']['tmp_name'], $cover);
}

if (!empty($_FILES['pdf_file']['name'])) {
    $pdf = "uploads/books/" . uniqid() . "_" . $_FILES['pdf_file']['name'];
    move_uploaded_file($_FILES['pdf_file']['tmp_name'], $pdf);
}

$stmt = $db->prepare("UPDATE books 
                      SET title=?, author=?, category=?, cover_image=?, pdf_file=? 
                      WHERE book_id=?");

$stmt->bind_param("sssssi", $title, $author, $category, $cover, $pdf, $id);
$stmt->execute();

header("Location: admin-library.php");
exit();
?>