<?php
include('connect.php');
date_default_timezone_set('Asia/Beirut');

$message_status = "";
$toast_text = "";

if (isset($_POST['send-reset'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $query = "SELECT * FROM therapist WHERE email = '$email'";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
        $token = bin2hex(random_bytes(16));
        $token_hash = hash("sha256", $token);
        $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

        $sql = "UPDATE therapist SET reset_token_hash = '$token_hash', reset_token_expires_at = '$expiry' WHERE email = '$email'";
        mysqli_query($db, $sql);

        $reset_link = "http://localhost/pi/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Click here to reset your password: " . $reset_link;
        $headers = "From: noreply@silent-support.com";

        if (@mail($email, $subject, $message, $headers)) {
            $message_status = "success";
            $toast_text = "Reset link sent to your email!";
        } else {
            $message_status = "error";
            $toast_text = "Email failed. Check your local SMTP settings.";
        }
    } else {
        $message_status = "error";
        $toast_text = "Email address not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password - Silent Support</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .reset-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        p {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 25px;
        }

        input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #dcdde1;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
            margin-bottom: 20px;
        }

        button {
            width: 100%;
            background-color: #f5d74c;
            color: #2c3e50;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }
        #toast {
            visibility: hidden;
            min-width: 280px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 8px;
            padding: 16px;
            position: fixed;
            z-index: 9999;
            left: 50%;
            transform: translateX(-50%);
            bottom: 30px;
            transition: visibility 0s, opacity 0.5s linear;
            opacity: 0;
        }

        #toast.success {
            background-color: #27ae60;
        }

        #toast.error {
            background-color: #e74c3c;
        }

        #toast.show {
            visibility: visible;
            opacity: 1;
            animation: fadein 0.5s, fadeout 0.5s 3.5s;
        }

        @keyframes fadein {
            from {
                bottom: 0;
                opacity: 0;
            }

            to {
                bottom: 30px;
                opacity: 1;
            }
        }

        @keyframes fadeout {
            from {
                bottom: 30px;
                opacity: 1;
            }

            to {
                bottom: 0;
                opacity: 0;
            }
        }
    </style>
</head>

<body>

    <div id="toast" class="<?php echo $message_status; ?>"
        data-show="<?php echo !empty($toast_text) ? 'true' : 'false'; ?>">
        <?php echo $toast_text; ?>
    </div>

    <div class="reset-container">
        <h2>Forgot Password?</h2>
        <p>Enter your email and we'll send you a recovery link.</p>
        <form method="post">
            <input type="email" name="email" placeholder="Enter your registered email" required>
            <button type="submit" name="send-reset">Send Reset Link</button>
        </form>
        <a href="login.php"
            style="display:block; margin-top:15px; color:#95a5a6; text-decoration:none; font-size:13px;">Back to
            Login</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toast = document.getElementById("toast");
            if (toast.getAttribute('data-show') === 'true') {
                toast.classList.add("show");
                setTimeout(function () {
                    toast.classList.remove("show");
                }, 4000);
            }
        });
    </script>
</body>

</html>