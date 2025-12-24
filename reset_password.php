<?php
require 'connect.php';
date_default_timezone_set('Asia/Beirut');

$message_status = "";
$toast_text = "";
$show_form = true;

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $token_hash = hash("sha256", $token);

    $query = "SELECT * FROM therapist WHERE reset_token_hash = '$token_hash'";
    $result = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($result);

    if (!$user || strtotime($user['reset_token_expires_at']) <= time()) {
        $show_form = false;
        $message_status = "error";
        $toast_text = "This link is invalid or has expired.";
    }
} else {
    header("Location: login.php");
    exit;
}

if (isset($_POST['submit_new_password'])) {
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $therapist_id = $user['Therapist_Id'];

        $update_query = "UPDATE therapist SET 
                         password = '$hashed_password', 
                         reset_token_hash = NULL, 
                         reset_token_expires_at = NULL 
                         WHERE Therapist_Id = '$therapist_id'";

        if (mysqli_query($db, $update_query)) {
            $message_status = "success";
            $toast_text = "Success! Password changed. Redirecting to login...";
            // Optional: Auto-redirect after 3 seconds
            header("refresh:3;url=login.php");
        } else {
            $message_status = "error";
            $toast_text = "Database error. Please try again.";
        }
    } else {
        $message_status = "error";
        $toast_text = "Passwords do not match.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password - Silent Support</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .reset-container { background: #ffffff; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); width: 100%; max-width: 400px; text-align: center; }
        h2 { color: #2c3e50; margin-bottom: 20px; }
        .form-group { text-align: left; margin-bottom: 15px; }
        label { display: block; font-size: 13px; color: #7f8c8d; margin-bottom: 5px; }
        input[type="password"] { width: 100%; padding: 12px 15px; border: 1px solid #dcdde1; border-radius: 6px; box-sizing: border-box; font-size: 16px; }
        button { width: 100%; background-color: #f5d74c; color: #2c3e50; padding: 12px; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer; margin-top: 10px; }
        
        /* Toast Notification */
        #toast {
            visibility: hidden; min-width: 280px; background-color: #333; color: #fff; text-align: center;
            border-radius: 8px; padding: 16px; position: fixed; z-index: 9999; left: 50%;
            transform: translateX(-50%); bottom: 30px; opacity: 0; transition: opacity 0.5s;
        }
        #toast.success { background-color: #27ae60; }
        #toast.error { background-color: #e74c3c; }
        #toast.show { visibility: visible; opacity: 1; animation: fadein 0.5s, fadeout 0.5s 3.5s; }

        @keyframes fadein { from { bottom: 0; opacity: 0; } to { bottom: 30px; opacity: 1; } }
        @keyframes fadeout { from { bottom: 30px; opacity: 1; } to { bottom: 0; opacity: 0; } }
    </style>
</head>
<body>

    <div id="toast" class="<?php echo $message_status; ?>" data-show="<?php echo !empty($toast_text) ? 'true' : 'false'; ?>">
        <?php echo $toast_text; ?>
    </div>

    <div class="reset-container">
        <?php if ($show_form): ?>
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
                <button type="submit" name="submit_new_password">Update Password</button>
            </form>
        <?php else: ?>
            <h2>Link Expired</h2>
            <p>Sorry, this reset link is no longer valid.</p>
            <a href="forgot_password.php" style="color:#f5d74c; text-decoration:none;">Request a new link</a>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toast = document.getElementById("toast");
            if (toast.getAttribute('data-show') === 'true') {
                toast.classList.add("show");
                setTimeout(function() {
                    toast.classList.remove("show");
                }, 4000);
            }
        });
    </script>
</body>
</html>