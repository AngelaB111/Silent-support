<?php
include('connect.php');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'invalid_method']);
    exit;
}

$message_id = isset($_POST['message_id']) ? (int) $_POST['message_id'] : 0;
if ($message_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'invalid_id']);
    exit;
}

$db->begin_transaction();
try {
    $d1 = $db->prepare("DELETE FROM public_posts WHERE message_id = ?");
    $d1->bind_param("i", $message_id);
    $d1->execute();
    $d1->close();

    $d2 = $db->prepare("DELETE FROM private_replies WHERE message_id = ?");
    $d2->bind_param("i", $message_id);
    $d2->execute();
    $d2->close();

    $d3 = $db->prepare("DELETE FROM messages WHERE message_id = ?");
    $d3->bind_param("i", $message_id);
    $d3->execute();
    $d3->close();

    $conn->commit();
    echo json_encode(['success' => true]);
    exit;
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    exit;
}
