<?php
include('connect.php');
header('Content-Type: application/json; charset=utf-8');

$therapist_id = $_SESSION['therapist_id'] ?? null;
if (!$therapist_id) {
    $therapist_id = 1;
}

$message_id = isset($_POST['message_id']) ? (int) $_POST['message_id'] : 0;
$category = isset($_POST['category']) ? trim($_POST['category']) : '';
$reply = isset($_POST['reply']) ? trim($_POST['reply']) : '';

if ($message_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'invalid_message_id']);
    exit;
}

$stmt = $db->prepare("SELECT content, public FROM messages WHERE message_id = ? LIMIT 1");
$stmt->bind_param("i", $message_id);
$stmt->execute();
$msg = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$msg) {
    echo json_encode(['success' => false, 'error' => 'message_not_found']);
    exit;
}

$isPublic = ($msg['public'] === 'yes');
$question = $msg['content'];

$db->begin_transaction();

try {

    if ($category !== '') {
        $u = $db->prepare("UPDATE messages SET category = ? WHERE message_id = ?");
        $u->bind_param("si", $category, $message_id);
        $u->execute();
        $u->close();
    }

    if ($isPublic) {
        $check = $db->prepare("SELECT post_id FROM public_posts WHERE message_id = ? LIMIT 1");
        $check->bind_param("i", $message_id);
        $check->execute();
        $r = $check->get_result()->fetch_assoc();
        $check->close();

        if ($r) {
            $upd = $db->prepare("UPDATE public_posts SET category = ?, question=?, answer = ? WHERE message_id = ?");
            $upd->bind_param("sssi", $category, $question, $reply, $message_id);
            $upd->execute();
            $upd->close();
        } else {
            $ins = $db->prepare("INSERT INTO public_posts (message_id, category, question, answer) VALUES (?, ?, ?, ?)");
            $ins->bind_param("isss", $message_id, $category, $question, $reply);

            $ins->execute();
            $ins->close();
        }
    } else {
        // --- PRIVATE REPLIES BLOCK ---
        $check = $db->prepare("SELECT privatePost_id FROM private_replies WHERE message_id = ? LIMIT 1");
        $check->bind_param("i", $message_id);
        $check->execute();
        $r = $check->get_result()->fetch_assoc();
        $check->close();

        if ($r) {
            // FIX 1: Changed bind_param from "isi" to "si" 
            $upd = $db->prepare("UPDATE private_replies SET reply_content = ? WHERE message_id = ?");
            $upd->bind_param("si", $reply, $message_id);
            $upd->execute();
            $upd->close();
        } else {
            // FIX 2: Changed query to use 2 placeholders and bind_param to "is" 
            $ins = $db->prepare("INSERT INTO private_replies (message_id, reply_content) VALUES (?, ?)");
            $ins->bind_param("is", $message_id, $reply);
            $ins->execute();
            $ins->close();
        }
    }

    // Update the 'reply' column in the main messages table
    $rmsg = $db->prepare("UPDATE messages SET reply = ? WHERE message_id = ?");
    $rmsg->bind_param("si", $reply, $message_id);
    $rmsg->execute();
    $rmsg->close();

    // Set 'replied' status to 'yes'
    $mup = $db->prepare("UPDATE messages SET replied = 'yes' WHERE message_id = ?");
    $mup->bind_param("i", $message_id);
    $mup->execute();
    $mup->close();

    $db->commit();
    echo json_encode(['success' => true]);
    exit;

} catch (Exception $e) {
    $db->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    exit;
}