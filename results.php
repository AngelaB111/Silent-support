<?php
$score = $_GET['score'];
$status = $_GET['status'];
$suggestion = $_GET['suggestion'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Your Results</title>
    
  <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
  <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
 
  <link rel="stylesheet" href="styles/navbar.css?v=3" />
    <link rel="stylesheet" href="styles/results.css?v=3">
</head>

<body>
    
    <?php include("navbar.php") ?>

    <div class="result-container">
        <div class="result-card">
            <h1>Your Results : <span><?php echo $score; ?></span></h1>

            <p><strong>Status:</strong> <?php echo $status; ?></p>

            <p><strong>Suggestions based on results :</strong> <?php echo $suggestion; ?></p>

            <div class="result-buttons">
                <a href="assessments.php" class="btn retake" >Retake Assessment</a>
                <a href="messaging-page.php" class="btn message" >Send a Message</a>
            </div>
        </div>
    </div>

</body>

</html>