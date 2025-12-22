<?php
require 'connect.php';
if (!isset($_SESSION['Therapist_username'])) {
    die("Unauthorized access");
}
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Therapist Dashboard</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="styles/navbar1.css">
    <link rel="stylesheet" href="styles/admin.css?v=4">
</head>

<body>

    <?php include("therapist_navbar.php"); ?>

    <div class="profile-card">
        <h2>Account Settings</h2>

        <form method="POST" action="update_admin.php">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password">
                <small>Leave empty if you don’t want to change it</small>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password">
            </div>

            <button type="submit" class="save-btn">
                Update Credentials
            </button>
        </form>
    </div>
    <?php if (isset($_SESSION['success_msg'])): ?>
        <div id="toast" class="toast-notification success">
            <i class="fa fa-check-circle"></i>
            <span><?php echo $_SESSION['success_msg'];
            unset($_SESSION['success_msg']); ?></span>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('toast');
                toast.classList.add('show');
                setTimeout(() => { toast.classList.remove('show'); }, 3000);
            }, 100);
        </script>
    <?php endif; ?>
</body>