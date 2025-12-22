<?php
require 'connect.php';
if (!isset($_SESSION['Therapist_username'])) {
    die("Unauthorized access");
}

$admin_id = $_SESSION['Therapist_id'];
$username = trim($_POST['username']);
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

$stmt = $db->prepare("UPDATE admin SET username=? WHERE id=?");
$stmt->bind_param("si", $username, $Therapist_id);
$stmt->execute();

if (!empty($password)) {
    if ($password !== $confirm) {
        die("Passwords do not match");
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare("UPDATE admin SET password=? WHERE id=?");
    $stmt->bind_param("si", $hashed, $Therapist_id);
    $stmt->execute();
}

echo "Credentials updated successfully";
