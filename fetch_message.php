<?php
header('Content-Type: application/json');
require 'connect.php';
// ob_clean();
error_reporting(E_ERROR | E_PARSE);

if (!isset($_GET['message_id'])) {
    echo json_encode(["error" => "No message ID"]);
    exit;
}

$id = intval($_GET['message_id']);
$stmt = $db->prepare("SELECT * FROM messages WHERE message_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$message = $result->fetch_assoc();

if (!$message) {
    echo json_encode(["error" => "Message not found"]);
    exit;
}
echo json_encode($message);

exit;
