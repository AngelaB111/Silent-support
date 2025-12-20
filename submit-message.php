<?php
header('Content-Type: application/json');
require 'connect.php';

$content = $_POST['content'] ?? '';
$category = $_POST['category'] ?? '';
$public = ($_POST['public'] ?? 'no') === 'yes' ? 'yes' : 'no';
$flagged = (isset($_POST['flagged']) && strtolower($_POST['flagged']) === 'yes') ? 'yes' : 'no';

$dangerKeywords = ['kill myself', 'end my life', 'suicide', 'self harm', 'cutting'];
foreach ($dangerKeywords as $word) {
    if (stripos($content, $word) !== false) {
        $flagged = 'yes';
        break;
    }
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!$content || !$category) {
    echo json_encode(['success' => false, 'error' => 'Content required']);
    exit;
}

$access_code = ($public === 'no') ? strtoupper(substr(md5(uniqid()), 0, 6)) : '';
$stmt = $db->prepare("INSERT INTO messages (access_code, category, content, public, flagged, replied) VALUES (?, ?, ?, ?, ?, 'no')");
$stmt->bind_param("sssss", $access_code, $category, $content, $public, $flagged);
if ($stmt->execute()) {
    $message_id = $stmt->insert_id;
    echo json_encode(['success' => true, 'message_id' => $message_id, 'access_code' => $access_code]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
