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

            // 1. Delete all options first
            $option_delete_query = "DELETE FROM options WHERE question_id = $id";
            if (!$db->query($option_delete_query)) {
                $message = 'Option deletion failed: ' . $db->error . ' Query: ' . $option_delete_query;
                throw new Exception("DB Error");
            }
            // 2. Delete the question
            $question_delete_query = "DELETE FROM questions WHERE question_id = $id";
            if (!$db->query($question_delete_query)) {
                $message = 'Question deletion failed: ' . $db->error . ' Query: ' . $question_delete_query;
                throw new Exception("DB Error");
            }

            $success = true;
            $message = 'Question and options deleted successfully.';

        } else if ($type === 'result') {
            // NOTE: You are deleting from 'assessment_results', but your PHP screenshot shows 'assessment_results' (plural).
            $result_delete_query = "DELETE FROM assessment_results WHERE result_id = $id";
            if (!$db->query($result_delete_query)) {
                $message = 'Result deletion failed: ' . $db->error . ' Query: ' . $result_delete_query;
                throw new Exception("DB Error");
            }

            $success = true;
            $message = 'Result row deleted successfully.';

        } else if ($type === 'option') {
            // Delete the single option
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
        // Keep the specific DB error message if one was set.
        if (!str_contains($message, 'DB Error')) {
            $message = 'Database Error: An unexpected error occurred.';
        }
        $success = false; // Ensure success is false if any error occurred
    }
} else {
    $message = 'Missing or invalid ID or non-POST method.';
}

// Send the response back to the JavaScript
echo json_encode([
    'success' => $success,
    'message' => $message
]);
exit();
?>