<?php
include('connect.php');

$query = "SELECT * FROM books";
$result = mysqli_query($db, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Library</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="styles/navbar.css?v=3" />
    <link rel="stylesheet" href="styles/library.css?v=5">
</head>

<body>

    <?php include("navbar.php") ?>
    <h1 class="page-title">Library</h1>

    <div class="search-bar">
        <input type="text" placeholder="search for keyword">
    </div>

    <div class="library-container">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <div class="book-card">

                <img src="<?php echo $row['cover_image']; ?>" class="book-cover" alt="Book Cover">

                <h3 class="book-title"><?php echo $row['title']; ?></h3>
                <p class="book-author"><?php echo $row['author']; ?></p>

                <a href="<?php echo $row['pdf_file']; ?>" download class="download-btn">
                    <span class="download"><img src="icons/downloads.png"></span> Download
                </a>

            </div>

        <?php } ?>
    </div>

</body>

</html>