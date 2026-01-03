<?php
include('connect.php');
$post_id = $_GET['post_id'] ?? '';
$post_counter_num = $_GET['post_num'] ?? 'N/A';

if (!$post_id) {
    die("Invalid request.");
}

$stmt = $db->prepare("
    SELECT p.*, m.category 
    FROM public_posts p
    INNER JOIN messages m ON p.message_id = m.message_id
    WHERE p.post_id = ?
");

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

    <link rel="stylesheet" href="styles/navbar.css" />

    <link rel="stylesheet" href="styles/public.css">
</head>

<body>

    <?php include("navbar.php") ?>

    <div class="container">

        <div class="post-header-info">

            <div class="title1"> <i class="fa fa-envelope-o" aria-hidden="true"></i> Public Message #<?php echo htmlspecialchars($post_counter_num); ?></div>


      
        <div class="user-message">
            <?php echo nl2br(htmlspecialchars($post['question'])); ?>
        </div>

        <div class="reply-header"><i class="fa fa-comments-o" aria-hidden="true"></i>Reply:</div>

        <div class="reply">
            <?php
            if (!empty($post['answer'])) {
                echo nl2br(htmlspecialchars($post['answer']));
            } else {
                echo '<span class="no-reply">A professional reply is coming soon.</span>';
            }
            ?>
        </div>
  </div>

    </div>


<?php include("footer.php") ?>
</body>

</html>

<script src="scripts/script.js" defer></script>