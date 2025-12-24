<?php
// delete_item.php (REVISED FOR ERROR CHECKING)

include "connect.php";
header('Content-Type: application/json');

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$type = isset($_POST['type']) ? $_POST['type'] : '';

$success = false;
$message = 'Invalid request.';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id > 0) {
    try {
        if ($type === 'question') {

            $option_delete_query = "DELETE FROM options WHERE question_id = $id";
            if (!$db->query($option_delete_query)) {
                $message = 'Option deletion failed: ' . $db->error . ' Query: ' . $option_delete_query;
                throw new Exception("DB Error");
            }
            $question_delete_query = "DELETE FROM questions WHERE question_id = $id";
            if (!$db->query($question_delete_query)) {
                $message = 'Question deletion failed: ' . $db->error . ' Query: ' . $question_delete_query;
                throw new Exception("DB Error");
            }

            $success = true;
            $message = 'Question and options deleted successfully.';

        } else if ($type === 'result') {
            $result_delete_query = "DELETE FROM assessment_results WHERE result_id = $id";
            if (!$db->query($result_delete_query)) {
                $message = 'Result deletion failed: ' . $db->error . ' Query: ' . $result_delete_query;
                throw new Exception("DB Error");
            }

            $success = true;
            $message = 'Result row deleted successfully.';

        } else if ($type === 'option') {
            $option_delete_query = "DELETE FROM options WHERE option_id = $id";
            if (!$db->query($option_delete_query)) {
                $message = 'Option deletion failed: ' . $db->error . ' Query: ' . $option_delete_query;
                throw new Exception("DB Error");
            }

            $success = true;
            $message = 'Option deleted successfully.';

        } else {
            $message = 'Invalid item type.';
        }

    } catch (Exception $e) {
        if (!str_contains($message, 'DB Error')) {
            $message = 'Database Error: An unexpected error occurred.';
        }
        $success = false; }
} else {
    $message = 'Missing or invalid ID or non-POST method.';
}

echo json_encode([
    'success' => $success,
    'message' => $message
]);
exit();
?>