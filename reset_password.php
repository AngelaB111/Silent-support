<?php
require 'connect.php';
date_default_timezone_set('Asia/Beirut');

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $token_hash = hash("sha256", $token);

    $query = "SELECT * FROM therapist WHERE reset_token_hash = '$token_hash'";
    $result = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($result);

    if (!$user || strtotime($user['reset_token_expires_at']) <= time()) {
        die("This link is invalid or has expired.");
    }
}

if (isset($_POST['submit_new_password'])) {
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $update_query = "UPDATE therapist SET 
                         password = '$hashed_password', 
                         reset_token_hash = NULL, 
                         reset_token_expires_at = NULL 
                         WHERE Therapist_Id = " . $user['Therapist_Id'];

        mysqli_query($db, $update_query);
        echo "Success! Password changed. <a href='login.php'>Login here</a>";
    } else {
        echo "Passwords do not match.";
    }
}
?>
<!doctype html>
<html>

<head>
    <link rel="stylesheet" href="styles/admin.css">
    <title>Reset Password</title>
</head>

<body>
    <div class="profile-card">
        <h2>Set New Password</h2>
       
        <form method="POST">
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" name="reset_password" class="save-btn">Update Password</button>
        </form>
    </div>
</body>

</html>