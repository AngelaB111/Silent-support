<?php
include('connect.php');

if (!isset($_SESSION['Therapist_id'])) {
    die("Unauthorized access");
}

$admin_id = $_SESSION['Therapist_id'];

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirm = trim($_POST['confirm_password'] ?? '');

$success_msg = [];
$error_msg = [];

if (!empty($username)) {
    $stmt_user = $db->prepare("UPDATE therapist SET username=? WHERE Therapist_Id=?");
    $stmt_user->bind_param("si", $username, $admin_id);

    if ($stmt_user->execute()) {
        $_SESSION['Therapist_username'] = $username;
        $success_msg[] = "Username updated successfully.";
    } else {
        $error_msg[] = "Failed to update username: " . $stmt_user->error;
    }
}

if (!empty($password)) {
    if ($password !== $confirm) {
        $error_msg[] = "Passwords do not match!";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt_pw = $db->prepare("UPDATE therapist SET password=? WHERE Therapist_Id=?");
        $stmt_pw->bind_param("si", $hashed, $admin_id);

        if ($stmt_pw->execute()) {
            $success_msg[] = "Password updated successfully.";
        } else {
            $error_msg[] = "Failed to update password: " . $stmt_pw->error;
        }
    }
}

if (!empty($success_msg)) {
    $_SESSION['success_msg'] = implode(" ", $success_msg);
}

if (!empty($error_msg)) {
    $_SESSION['error_msg'] = implode(" ", $error_msg);
}

header("Location: update.php");
exit();
