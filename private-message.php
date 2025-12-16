<?php
include('connect.php');

$id = $_GET['message_id'] ?? '';
$code = $_GET['access_code'] ?? '';

if (!$id || !$code) {
    die("Invalid request.");
}
$stmt = $db->prepare("SELECT * FROM messages WHERE message_id = ? AND access_code = ?");
$stmt->bind_param("is", $id, $code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("<div style='font-family:Georgia;text-align:center;margin-top:100px;'>Message not found or code incorrect.</div>");
}
$message = $result->fetch_assoc();
$stmt->close();

$stmt1 = $db->prepare("SELECT reply_content FROM private_replies WHERE message_id = ?");
$stmt1->bind_param("i", $id);
$stmt1->execute();
$result1 = $stmt1->get_result();
$reply = $result1->fetch_assoc();
$stmt1->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="styles/navbar.css?v=3" />
    <link rel="stylesheet" href="styles/styleprivate.css?v=4" />
    <title>Message #<?php echo htmlspecialchars($message['message_id']); ?></title>
</head>

<body>
    <?php include("navbar.php") ?>
    <span class="body1">
        <div class="container">
            <div class="title">Private Message </div>

            <div class="user-message">
                <?php echo nl2br(htmlspecialchars($message['content'])); ?>
            </div>

            <?php if (!empty($reply['reply_content'])): ?>
                <div class="reply">
                    <?php echo nl2br(htmlspecialchars($reply['reply_content'])); ?>
                </div>
            <?php else: ?>
                <div class="reply no-reply">The therapist has not replied yet.</div>
            <?php endif; ?>
        </div>
    </span>
</body>

</html>