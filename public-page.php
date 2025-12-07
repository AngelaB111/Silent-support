<?php
include('connect.php');

$query = "SELECT * FROM public_posts ORDER BY post_id DESC";
$result = $db->query($query);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Public Questions & Answers</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <link rel="stylesheet" href="styles/navbar.css?v=3" />
    <link rel="stylesheet" href="styles/public.css?v=4">
</head>

<body>

    <?php include("navbar.php"); ?>

    <h1 class="title">Public Questions & Answers</h1>

    <div class="wrapper">

        <?php while ($row = $result->fetch_assoc()): ?>

            <div class="public-card">

                <div class="question"><span class="preview">
                    <?php echo nl2br(htmlspecialchars($row['question'])); ?>
                     </span>
                </div>

                <a href="publicview.php?post_id=<?php echo $row['post_id']; ?>" class="view-btn">
                    view reply
                </a>
            </div>

        <?php endwhile; ?>

    </div>

</body>

</html>


<script src="scripts/script.js" defer></script>