<?php
include('connect.php');

$searchTerm = $_GET['search'] ?? '';
$selectedCategory = $_GET['category'] ?? '';

$categories = [
    'Anxiety/Worry',
    'Depression/Sadness',
    'Grief/Loss',
    'Relationship Issues',
    'Stress/Burnout',
    'Other'
];

$query = "SELECT p.*, m.category FROM public_posts p";
$joins = "INNER JOIN messages m ON p.message_id = m.message_id";
$whereClauses = [];
$params = [];
$paramTypes = '';

if (!empty($selectedCategory) && in_array($selectedCategory, $categories)) {
    $whereClauses[] = "m.category = ?";
    $params[] = $selectedCategory;
    $paramTypes .= 's';
}

if (!empty($searchTerm)) {
    $whereClauses[] = "p.question LIKE ?";
    $params[] = "%" . $searchTerm . "%";
    $paramTypes .= 's';
}

$query .= " " . $joins;

if (!empty($whereClauses)) {
    $query .= " WHERE " . implode(' AND ', $whereClauses);
}

$query .= " ORDER BY p.post_id DESC";

if (!empty($params)) {
    $stmt = $db->prepare($query);

    if (!empty($paramTypes) && !empty($params)) {
        $stmt->bind_param($paramTypes, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $db->query($query);
} ?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">
    <title>Public Questions & Answers</title>

    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">

    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />


    <link rel="stylesheet" href="styles/navbar.css" />
    <link rel="stylesheet" href="styles/public.css?v=7">
</head>

<body>

    <?php include("navbar.php"); ?>

    <h1 class="title">Public Questions & Answers</h1>

    <div class="wrapper">

        <form method="GET" class="filter-controls">

            <div class="search-group">
                <input type="text" name="search" class="search-input" placeholder="Search by keywords..."
                    value="<?php echo htmlspecialchars($searchTerm); ?>">
            </div>

            <div class="category-group">
                <select name="category" class="category-select">
                    <option class="cat" value="">Filter by Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category); ?>">
                            <?php echo ($selectedCategory === $category) ? 'selected' : ''; ?>

                            <?php echo htmlspecialchars($category); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="search-btn">
                <i class="fa fa-filter"></i> Filter Search
            </button>

            <?php if (!empty($searchTerm) || !empty($selectedCategory)): ?>
                <a href="public-page.php" class="clear-search-btn">
                    <i class="fa fa-times"></i> Clear Search/Filters
                </a>
            <?php endif; ?>
        </form>
        <?php if ($result->num_rows === 0): ?>
            <p class="no-results">
                No public questions match your current filters.
                <?php if (!empty($selectedCategory)): ?>
                    <br>Category: **<?php echo htmlspecialchars($selectedCategory); ?>**
                <?php endif; ?>
                <?php if (!empty($searchTerm)): ?>
                    <br>Search: **<?php echo htmlspecialchars($searchTerm); ?>**
                <?php endif; ?>
            </p>
        <?php else: ?>
            <?php while ($row = $result->fetch_assoc()): ?>

                <div class="public-card">
                    <div class="card-content-wrapper">
                        <div class="question"><span class="preview">
                                <?php echo nl2br(htmlspecialchars($row['question'])); ?>
                            </span>
                        </div>

                        <a href="publicview.php?post_id=<?php echo $row['post_id']; ?>" class="view-btn">
                            view reply
                        </a>

                    </div>
                </div>

            <?php endwhile; ?>
        <?php endif; ?>


    </div>

</body>

</html>
<script src="scripts/script.js" defer></script>