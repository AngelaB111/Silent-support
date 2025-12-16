<?php
include "connect.php";

$books = $db->query("SELECT * FROM books ORDER BY book_id DESC");

$editMode = false;
$editBook = null;

if (isset($_GET['edit'])) {
    $editMode = true;
    $id = intval($_GET['edit']);
    $stmt = $db->prepare("SELECT * FROM books WHERE book_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $editBook = $stmt->get_result()->fetch_assoc();
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
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel=" stylesheet" href="styles/books.css?v=3">
</head>

<body>

    <?php include("therapist_navbar.php"); ?>

    <h1 class="page-title">Library Dashboard</h1>

    <div class="container">

        <div class="left">
            <div class="list-header">
                <h3>Uploaded Documents</h3>
                <?php if ($editMode): ?>
                    <a href="admin-library.php" class="add-new-btn" title="Add New Book">
                        <i class="fa fa-plus-circle"></i> Add New Book
                    </a>
                <?php endif; ?>
            </div>
            <div class="book-list">
                <?php while ($b = $books->fetch_assoc()): ?>
                    <div class="book-card-admin">
                        <div class="book-info">
                            <strong><?php echo htmlspecialchars($b['title']); ?></strong>
                            <span class="book-author-admin">Author: <?php echo htmlspecialchars($b['author']); ?></span>
                        </div>

                        <div class="actions">
                            <a href="admin-library.php?edit=<?php echo $b['book_id']; ?>" class="edit-btn"
                                title="Edit Book">
                                <i class="fa fa-pencil"></i> Edit
                            </a>

                            <a href="delete-book.php?id=<?php echo $b['book_id']; ?>" class="delete-btn"
                                onclick="return confirm('Are you sure you want to permanently delete the book: <?php echo htmlspecialchars($b['title']); ?>?')">
                                <i class="fa fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

        </div>
        <div class="right">

            <?php if ($editMode && $editBook): ?>

                <h3>Edit Book: <?php echo htmlspecialchars($editBook['title']); ?></h3>

                <div class="cover-preview" id="coverPreview"
                    style="background-image: url('<?php echo htmlspecialchars($editBook['cover_image']); ?>'); background-size: cover; background-position: center;">
                    <span class="preview-text">Click to zoom</span>
                </div>

                <form action="edit-book.php?id=<?php echo $editBook['book_id']; ?>" method="POST"
                    enctype="multipart/form-data">

                    <label for="title">Title:</label>
                    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($editBook['title']); ?>"
                        required>

                    <label for="author">Author:</label>
                    <input type="text" name="author" id="author"
                        value="<?php echo htmlspecialchars($editBook['author']); ?>">
                    <label for="coverInput">Replace Cover Image (optional):</label>
                    <input type="file" name="cover_image" id="coverInput" accept="image/*">

                    <label for="pdf_file">Replace PDF File (optional):</label>
                    <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf">

                    <button class="upload-btn" type="submit" name="update"><i class="fa fa-refresh"></i> Update
                        Book</button>

                    <a href="admin-library.php">
                        <button type="button" class="cancel-btn"><i class="fa fa-times"></i> Cancel</button>
                    </a>
                </form>

            <?php else: ?>

                <h3>Add New Book</h3>

                <div class="cover-preview empty-preview" id="coverPreview">
                    <span class="preview-text">Cover Preview</span>
                </div>

                <form action="add-book.php" method="POST" enctype="multipart/form-data">

                    <label for="title_new">Title:</label>
                    <input type="text" name="title" id="title_new" required>

                    <label for="author_new">Author:</label>
                    <input type="text" name="author" id="author_new">

                    <label for="coverInputNew">Cover Image:</label>
                    <input type="file" name="cover_image" id="coverInput" required accept="image/*">

                    <label for="pdf_file_new">PDF File:</label>
                    <input type="file" name="pdf_file" id="pdf_file_new" required accept="application/pdf">

                    <button type="submit" class="upload-btn"><i class="fa fa-cloud-upload"></i> Upload Document</button>
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

            if (input && preview) {
                input.addEventListener("change", function () {
                    const file = this.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = e => {
                        preview.style.backgroundImage = `url('${e.target.result}')`;
                        preview.style.backgroundSize = "cover";
                        preview.style.backgroundPosition = "center";
                        preview.classList.remove('empty-preview');
                        const previewText = preview.querySelector('.preview-text');
                        if (previewText) previewText.textContent = "Click to zoom";
                    };
                    reader.readAsDataURL(file);
                });
            }

            const popup = document.getElementById("imgPopup");
            const popupImg = document.getElementById("popupImg");
            const closePopup = document.getElementById("closePopup");

            if (preview && popup && popupImg && closePopup) {

                preview.addEventListener("click", function () {
                    const bg = preview.style.backgroundImage;
                    if (bg && bg !== "none") {
                        const url = bg.slice(5, -1).replace(/"/g, "");
                        popupImg.src = url;
                        popup.style.display = "flex";
                    }
                });

                closePopup.addEventListener("click", () => popup.style.display = "none");

                popup.addEventListener("click", e => {
                    if (e.target === popup) popup.style.display = "none";
                });
            }
        });
    </script>
</body>

</html>