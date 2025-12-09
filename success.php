<?php
include("connect.php");

$message_id = $_GET['message_id'] ?? '-';
$access_code = $_GET['access_code'] ?? '';
$category = $_GET['category'] ?? 'N/A';
// Determine if the message is public or private based on the access code's presence
$is_public = empty($access_code);
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

            <?php if ($is_public): ?>
                <p class="subtext">
                    Your message has been posted publicly in the Q&A section under the category:
                    <strong style="color: #007bff;"><?= htmlspecialchars($category) ?></strong>
                </p>
                <div class="info-box public-info">
                    <p><strong>Status:</strong> Awaiting Therapist Reply</p>
                    <p>Replies for public messages are posted directly to the Q&A section and typically take up to 24 hours.
                    </p>
                </div>
            <?php else: ?>
                <p class="subtext">
                    Your message has been received privately under the category:
                    <strong style="color: #007bff;"><?= htmlspecialchars($category) ?></strong>
                </p>
                <p class="subtext">
                    <span style="font-weight: bold; color: #cc3333;">PLEASE SAVE THIS CODE.</span>
                    You will need it to retrieve your reply.
                </p>

                <div class="info-box private-info">
                    <p><strong>Message ID :</strong> <span id="messageId"><?= htmlspecialchars($message_id) ?></span></p>
                    <p><strong>Access Code :</strong> <span id="accessCode"><?= htmlspecialchars($access_code) ?></span></p>
                    <p class="small-note">Replies for private messages typically take up to 24 hours.</p>
                </div>
            <?php endif; ?>

            <a href="#" class="btn" onclick="parent.closePopup1()">return </a>

        </div>
    </div>

</body>

</html>
<script src="scripts/script1.js" defer></script>