<?php
include "connect.php";
$success = false;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['title']) || !isset($_POST['questions']) || !isset($_POST['min_scores'])) {
        $error_message = "Missing required fields (Title, Questions, or Result Ranges).";
    } else {
        $db->begin_transaction();

        try {
            $title = $_POST['title'];
            $description = $_POST['description'] ?? '';
            $source = $_POST['source'] ?? '';
            $stmt_assessment = $db->prepare("INSERT INTO assessments (title, description, source) VALUES (?, ?, ?)");
            $stmt_assessment->bind_param("sss", $title, $description, $source);
            $stmt_assessment->execute();
            $assessment_id = $db->insert_id;
            $stmt_assessment->close();
            $stmt_option = $db->prepare("INSERT INTO options (question_id, option_text, score) VALUES (?, ?, ?)");
            if (!$assessment_id) {
                throw new Exception("Failed to insert assessment title.");
            }


            $questions = $_POST['questions'];
            $options_data = $_POST['options'];
            $stmt_question = $db->prepare("INSERT INTO questions (assessment_id, question_text) VALUES (?, ?)");

            foreach ($questions as $q_index => $question_text) {

                $stmt_question->bind_param("is", $assessment_id, $question_text);
                $stmt_question->execute();
                $question_id = $db->insert_id;

                if (!$question_id) {
                    throw new Exception("Failed to insert question: " . $question_text);
                }
                $option_texts = $options_data[$q_index]['texts'];
                $option_scores = $options_data[$q_index]['scores'];


                if (!empty($option_texts)) {
                    foreach ($option_texts as $o_index => $option_text) {
                        $score = intval($option_scores[$o_index]);

                        $stmt_option->bind_param("isi", $question_id, $option_text, $score);
                        $stmt_option->execute();
                    }
                }
            }

            $stmt_question->close();
            $stmt_option->close();

            $min_scores = $_POST['min_scores'];
            $max_scores = $_POST['max_scores'];
            $interpretations = $_POST['interpretations'];
            $suggestions = $_POST['suggestions'];

            $stmt_results = $db->prepare("INSERT INTO assessment_results (assessment_id, min_score, max_score, interpretation, suggestion) VALUES (?, ?, ?, ?, ?)");
            foreach ($min_scores as $r_index => $min) {

                $min_val = intval($min);
                $max_val = intval($max_scores[$r_index]);
                $interpretation = $interpretations[$r_index];
                $suggestion = $suggestions[$r_index];

                $stmt_results->bind_param("iiiss", $assessment_id, $min_val, $max_val, $interpretation, $suggestion);
                $stmt_results->execute();
            }

            $stmt_results->close();
            $db->commit();
            $success = true;

        } catch (Exception $e) {
            $db->rollback();
            $error_message = "Database error: " . $e->getMessage() . " The assessment was not saved.";
        }
    }
}

$db->close();

if ($success) {
    header("Location: admin-assessment.php?edit=$assessment_id&status=success");
    exit;
} else {
    header("Location: admin-assessment.php?status=error&message=" . urlencode($error_message));
    exit;
}
?>