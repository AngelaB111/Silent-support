<?php
include "connect.php";
if (!isset($_SESSION['Therapist_username'])) {
    die("Unauthorized access");
}
$categoryQuery = $db->query("SELECT category, COUNT(*) AS total FROM messages GROUP BY category");
$categories = [];
$categoryCounts = [];
while ($row = $categoryQuery->fetch_assoc()) {
    $categories[] = $row['category'];
    $categoryCounts[] = $row['total'];
}

$urgentData = $db->query("SELECT SUM(flagged='yes') as u, SUM(flagged='no') as n FROM messages")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Therapist Analytics Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/navbar1.css">
    <link rel="stylesheet" href="styles/analytics.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <?php include('therapist_navbar.php');
    ?>
    <div class="container pb-5">
        <div class="row g-4">
            <div class="col-md-8">
                <div class="card p-3">
                    <h5 class="card-title text-center">Messages per Category</h5>
                    <div class="chart-container"><canvas id="categoryChart"></canvas></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h5 class="card-title text-center">Urgent vs Normal</h5>
                    <div class="chart-container"><canvas id="urgentChart"></canvas></div>
                </div>
            </div>

        </div>

        <script src="scripts/dashboard_charts.js"></script>
        <script>
            const phpData = {
                categories: <?= json_encode($categories) ?>,
                categoryCounts: <?= json_encode($categoryCounts) ?>,
                urgent: <?= (int) $urgentData['u'] ?>,
                normal: <?= (int) $urgentData['n'] ?>,
            };

            initCharts(phpData);
        </script>
</body>

</html>