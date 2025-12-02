<?php
include('connect.php');

$post_id = $_GET['post_id'] ?? '';

if (!$post_id) {
    die("Invalid request.");
}

$stmt = $db->prepare("SELECT * FROM public_posts WHERE post_id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Post not found.");
}

$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Public Post #<?php echo $post['post_id']; ?></title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="styles/navbar.css?v=3" />
    <link rel="stylesheet" href="styles/publicsingle.css?v=3">
</head>

<body>

    <?php include("navbar.php") ?>

    <div class="container">

        <div class="title">💬 Public Post #<?php echo $post['post_id']; ?></div>

        <div class="user-message">
            <?php echo nl2br(htmlspecialchars($post['question'])); ?>
        </div>

        <div class="reply">
            <?php echo nl2br(htmlspecialchars($post['answer'])); ?>
        </div>

    </div>

</body>

</html>

<script src="scripts/script.js" defer></script>