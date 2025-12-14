<?php
include('connect.php');
$errors = array();

if (isset($_POST['submit'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = $_POST['password'];

  if (empty($username))
    $errors[] = "Username is required";
  if (empty($password))
    $errors[] = "Password is required";

  if (count($errors) == 0) {
    $query = "SELECT * FROM therapist WHERE username='$username'";
    $results = mysqli_query($db, $query);

    if (mysqli_num_rows($results) == 1) {
      $user = mysqli_fetch_assoc($results);
      if (password_verify($password, $user['password'])) {
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";

        echo "<script>
        // Close popup (if still visible)
        if (window.top.document.getElementById('popupModal')) {
            window.top.document.getElementById('popupModal').style.display = 'none';
        }

        // Redirect whole page
        window.top.location.href = 'dashboard.php';
      </script>";
        exit();

      } else {
        $errors[] = "Wrong username/password combination";
      }
    } else {
      $errors[] = "Wrong username/password combination";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
  <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="styles/login.css?v=4" />
</head>

<body>
  <div class="container">
    <form action="login.php" method="post" class="sign-in-form">
      <?php if (count($errors) > 0): ?>
        <div class="server-error-box">
          <?php foreach ($errors as $error): ?>
            <p><?php echo $error ?></p>
          <?php endforeach ?>
        </div>
      <?php endif ?>
      <div>
        <img class="icon1" src="icons/icon.png" />
      </div>
      <h2 class="title">Sign In</h2>
      <div class="input-field">
        <input type="text" name="username" placeholder="username" class="box" />
      </div>
      <div class="input-field">
        <input type="password" name="password" placeholder="Password" class="box" />
      </div>

      <input type="submit" name="submit" value="Login" class="btn login" />
    </form>
  </div>
</body>

</html>
<script src="scripts/script.js" defer></script>