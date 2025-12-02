<?php
include "connect.php";

$books = $db->query("SELECT * FROM books ORDER BY book_id DESC");

$editMode = false;
$editBook = null;

if (isset($_GET['edit'])) {
    $editMode = true;
    $id = intval($_GET['edit']);
    $editBook = $db->query("SELECT * FROM books WHERE book_id=$id")->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Library Dashboard</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="styles/navbar.css?v=3">
    <link rel="stylesheet" href="styles/books.css?v=3">
</head>

<body>

    <?php include("therapist_navbar.php"); ?>
    <div class="container">

        <div class="left">
            <h3>Uploaded Documents :</h3>

            <?php while ($b = $books->fetch_assoc()): ?>
                <div class="book-card">
                    <strong><?php echo htmlspecialchars($b['title']); ?></strong><br>
                    Author: <?php echo htmlspecialchars($b['author']); ?><br>

                    <div class="actions">

                        <a href="admin-library.php?edit=<?php echo $b['book_id']; ?>">
                            <button class="edit-btn" type="button">Edit</button>
                        </a>

                        <a href="delete-book.php?id=<?php echo $b['book_id']; ?>"
                            onclick="return confirm('Delete this book?')">
                            <button class="delete-btn" type="button">
                                <img class="deleteImg" src="icons/delete.png" />
                                Delete</button>
                        </a>

                    </div>
                </div>
            <?php endwhile; ?>
            <button onclick="location.href='admin-library.php'" class="add-btn" type="button">

                Add new book</button>

        </div>



        <div class="right">

            <?php if ($editMode && $editBook): ?>

                <h3>Edit Book</h3>

                <div class="cover-preview" id="coverPreview"
                    style="background-image: url('<?php echo htmlspecialchars($editBook['cover_image']); ?>'); background-size: cover; background-position: center;">
                </div>

                <form action="edit-book.php?id=<?php echo $editBook['book_id']; ?>" method="POST"
                    enctype="multipart/form-data">

                    <label>Title:</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($editBook['title']); ?>" required>

                    <label>Author:</label>
                    <input type="text" name="author" value="<?php echo htmlspecialchars($editBook['author']); ?>">

                    <label>Category:</label>
                    <input type="text" name="category" value="<?php echo htmlspecialchars($editBook['category']); ?>">


                    <label>Replace Cover Image (optional):</label>
                    <input type="file" name="cover_image" id="coverInput" accept="image/*">

                    <label>Replace PDF File (optional):</label>
                    <input type="file" name="pdf_file" accept="application/pdf">

                    <button class="upload-btn" type="submit" name="update">Update Book</button>

                    <a href="admin-library.php">
                        <button type="button" class="big-delete">Cancel</button>
                    </a>
                </form>

            <?php else: ?>

                <h3>Add New Book</h3>

                <div class="cover-preview" id="coverPreview"></div>

                <form action="add-book.php" method="POST" enctype="multipart/form-data">

                    <label>Title:</label>
                    <input type="text" name="title" required>

                    <label>Author:</label>
                    <input type="text" name="author">
                    <label>Category:</label>
                    <input type="text" name="category" required>

                    <label>Cover Image:</label>
                    <input type="file" name="cover_image" id="coverInput" required accept="image/*">

                    <label>PDF File:</label>
                    <input type="file" name="pdf_file" required accept="application/pdf">

                    <button type="submit" class="upload-btn">Upload Document</button>
                </form>

            <?php endif; ?>

        </div>

    </div>

    <div id="imgPopup" class="img-popup">
        <span id="closePopup">&times;</span>
        <img id="popupImg" src="">
    </div>

    <script>

        document.addEventListener("DOMContentLoaded", function () {


            const input = document.getElementById("coverInput");
            const preview = document.getElementById("coverPreview");

            if (!preview) return;
            if (!input) return;

            input.addEventListener("change", function () {

                const file = this.files[0];
                if (!file) return;

                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.style.backgroundImage = `url('${e.target.result}')`;
                    preview.style.backgroundSize = "cover";
                    preview.style.backgroundPosition = "center";
                };

                reader.readAsDataURL(file);
            });

        });
        document.addEventListener("DOMContentLoaded", function () {
            const popup = document.getElementById("imgPopup");
            const popupImg = document.getElementById("popupImg");
            const closePopup = document.getElementById("closePopup");

            const preview = document.getElementById("coverPreview");
            if (preview) {
                preview.addEventListener("click", function () {
                    const bg = preview.style.backgroundImage;
                    if (bg && bg !== "none") {
                        const url = bg.slice(5, -2);
                        popupImg.src = url;
                        popup.style.display = "flex";
                    }
                });
            }
            closePopup.addEventListener("click", function () {
                popup.style.display = "none";
            });

            popup.addEventListener("click", function (e) {
                if (e.target === popup) popup.style.display = "none";
            });
        });
    </script>

</body>

</html>