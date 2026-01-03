<?php
include('connect.php');
$searchTerm = $_GET['search'] ?? '';
$query = "SELECT * FROM books";
$whereClauses = [];
$params = [];
$paramTypes = '';
if (!empty($searchTerm)) {
    $whereClauses[] = "title LIKE ? OR author LIKE ?";
    $params[] = "%" . $searchTerm . "%";
    $params[] = "%" . $searchTerm . "%";
    $paramTypes .= 'ss';
}
if (!empty($whereClauses)) {
    $query .= " WHERE " . implode(' AND ', $whereClauses);
}
$query .= " ORDER BY title ASC";


if (!empty($params)) {

    $stmt = $db->prepare($query);
    $stmt->bind_param($paramTypes, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $db->query($query);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Library</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="styles/navbar.css" />
    <link rel="stylesheet" href="styles/library.css?v=5">
</head>

<body>
    <?php include("navbar.php") ?>
    <h1 class="page-title">Library</h1>
    <div class="search-bar-container">
        <form method="GET" class="search-form">
            <input type="text" name="search" class="search-input" placeholder="search for keyword in title or author..."
                value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit" class="search-btn">
                <i class="fa fa-search"></i>
            </button>
            <?php if (!empty($searchTerm)): ?>
                <a href="library.php" class="clear-search-btn" title="Clear Search">
                    <i class="fa fa-times"></i>
                </a>
            <?php endif; ?>
        </form>
    </div>
    <div class="library-container">
        <?php if ($result->num_rows === 0): ?>
            <p class="no-results">No books found matching your search for "<?php echo htmlspecialchars($searchTerm); ?>".
            </p>
        <?php else: ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="book-card">
                    <img src="<?php echo $row['cover_image']; ?>" class="book-cover" alt="Book Cover">
                    <h3 class="book-title"><?php echo $row['title']; ?></h3>
                    <p class="book-author"><?php echo $row['author']; ?></p>
                    <a href="<?php echo $row['pdf_file']; ?>" download class="download-btn">
                        <span class="download"><img src="icons/downloads.png"></span> Download
                    </a>
                </div>
            <?php } ?>
        <?php endif; ?>
    </div>
    <?php include("footer.php") ?>
</body>

</html>

<script>
    (function () { if (!window.chatbase || window.chatbase("getState") !== "initialized") { window.chatbase = (...arguments) => { if (!window.chatbase.q) { window.chatbase.q = [] } window.chatbase.q.push(arguments) }; window.chatbase = new Proxy(window.chatbase, { get(target, prop) { if (prop === "q") { return target.q } return (...args) => target(prop, ...args) } }) } const onLoad = function () { const script = document.createElement("script"); script.src = "https://www.chatbase.co/embed.min.js"; script.id = "RxU2NixvO8dnE0x3jXRA7"; script.domain = "www.chatbase.co"; document.body.appendChild(script) }; if (document.readyState === "complete") { onLoad() } else { window.addEventListener("load", onLoad) } })();
</script>