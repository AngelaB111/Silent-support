<?php
include('connect.php');
date_default_timezone_set('Asia/Beirut'); 

if (isset($_POST['send-reset'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $query = "SELECT * FROM therapist WHERE email = '$email'";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {

        $token = bin2hex(random_bytes(16));
        $token_hash = hash("sha256", $token);
        $expiry = date("Y-m-d H:i:s", time() + 60 * 30); // 30 mins from now

        $sql = "UPDATE therapist SET reset_token_hash = '$token_hash', reset_token_expires_at = '$expiry' WHERE email = '$email'";
        mysqli_query($db, $sql);

        $reset_link = "http://localhost/pi/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Click here to reset your password: " . $reset_link;
        $headers = "From: noreply@silent-support.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "Reset link sent to your email!";
        } else {
            echo "Error sending email. (Check your SMTP settings)";
        }
    }
}
?>

<form method="post">
    <input type="email" name="email" placeholder="Enter your registered email" required>
    <button type="submit" name="send-reset">Send Reset Link</button>
</form>