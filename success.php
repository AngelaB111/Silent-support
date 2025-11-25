<?php
include("connect.php");
$message_id = $_GET['message_id'] ?? '-';
$access_code = $_GET['access_code'] ?? '-';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Message Sent | Silent Support</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Georgia&family=Open+Sans:wght@300;400;600&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="styles/navbar.css?v=3" />
    <link rel="stylesheet" href="styles/success.css?v=3" />
</head>

<body>

    <div class="container">
        <div class="card">
            <div class="check-icon" style="color: #f5d75a;">✔</div>
            <h1>Message Sent Successfully</h1>
            <p class="subtext">
                your message has been received, please save the<br>retrieval code to check the reply later
            </p>

            <div class="info-box">
                <p><strong>Message ID :</strong> <span id="messageId"><?= htmlspecialchars($message_id) ?></span></p>
                <p><strong>Access Code :</strong> <span id="accessCode"><?= htmlspecialchars($access_code) ?></span></p>

            </div>

            <a href="#" class="btn" onclick="parent.closePopup1()">return </a>

        </div>
    </div>

</body>

</html>
<script src="script/script1.js" defer></script>