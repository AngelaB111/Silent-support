<?php
include "connect.php";

$id = intval($_GET['id']);

$title = $_POST['title'];
$description = $_POST['description'] ?? '';
$source = $_POST['source'] ?? '';

// Update assessment
$stmt = $db->prepare(
    "UPDATE assessments SET title=?, description=?, source=? WHERE assessment_id=?"
);
$stmt->bind_param("sssi", $title, $description, $source, $id);
$stmt->execute();
$stmt->close();

// === QUESTIONS & OPTIONS ===
$question_indexes = $_POST['question_indexes'];
$questions = $_POST['questions'];
$question_ids = $_POST['question_ids'];
$options = $_POST['options'];

$stmt_update_q = $db->prepare(
    "UPDATE questions SET question_text=? WHERE question_id=?"
);
$stmt_insert_q = $db->prepare(
    "INSERT INTO questions (assessment_id, question_text) VALUES (?, ?)"
);
$stmt_update_opt = $db->prepare(
    "UPDATE options SET option_text=?, score=? WHERE option_id=?"
);
$stmt_insert_opt = $db->prepare(
    "INSERT INTO options (question_id, option_text, score) VALUES (?, ?, ?)"
);

foreach ($question_indexes as $i => $qIndex) {

    $question_text = $questions[$qIndex];
    $question_id = intval($question_ids[$i]);

    // Update or insert question
    if ($question_id) {
        $stmt_update_q->bind_param("si", $question_text, $question_id);
        $stmt_update_q->execute();
    } else {
        $stmt_insert_q->bind_param("is", $id, $question_text);
        $stmt_insert_q->execute();
        $question_id = $db->insert_id;
    }

    // Skip if no options
    if (!isset($options[$qIndex])) {
        continue;
    }

    $optTexts = $options[$qIndex]['texts'];
    $optScores = $options[$qIndex]['scores'];
    $optIds = $options[$qIndex]['option_ids'];

    foreach ($optTexts as $o => $optText) {

        $score = intval($optScores[$o]);
        $optId = intval($optIds[$o]);

        if ($optId) {
            $stmt_update_opt->bind_param("sii", $optText, $score, $optId);
            $stmt_update_opt->execute();
        } else {
            $stmt_insert_opt->bind_param("isi", $question_id, $optText, $score);
            $stmt_insert_opt->execute();
        }
    }
}

// === RESULTS ===
$result_ids = $_POST['result_ids'];
$min_scores = $_POST['min_scores'];
$max_scores = $_POST['max_scores'];
$interpretations = $_POST['interpretations'];
$suggestions = $_POST['suggestions'];

$stmt_update_r = $db->prepare(
    "UPDATE assessment_results
     SET min_score=?, max_score=?, interpretation=?, suggestion=?
     WHERE result_id=?"
);

$stmt_insert_r = $db->prepare(
    "INSERT INTO assessment_results
     (assessment_id, min_score, max_score, interpretation, suggestion)
     VALUES (?, ?, ?, ?, ?)"
);

foreach ($min_scores as $i => $min) {

    $min = intval($min);
    $max = intval($max_scores[$i]);
    $interp = $interpretations[$i];
    $sugg = $suggestions[$i];
    $rid = intval($result_ids[$i]);

    if ($rid) {
        $stmt_update_r->bind_param("iissi", $min, $max, $interp, $sugg, $rid);
        $stmt_update_r->execute();
    } else {
        $stmt_insert_r->bind_param("iiiss", $id, $min, $max, $interp, $sugg);
        $stmt_insert_r->execute();
    }
}

header("Location: admin-assessment.php?edit=$id&status=updated");
exit;
