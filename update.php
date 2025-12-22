
<form method="POST" action="update_admin.php">
    <div>
        <label>USername</label>
        <input type="username" name="username" required>
    </div>

    <div>
        <label>New Password</label>
        <input type="password" name="password">
        <small>Leave empty if you don’t want to change it</small>
    </div>

    <div>
        <label>Confirm Password</label>
        <input type="password" name="confirm_password">
    </div>

    <button type="submit">Update Credentials</button>
</form>
