<?php
include "connect.php";

$title = $_POST['title'];
$description = $_POST['description'] ?? "";

$db->query("INSERT INTO assessments (title, description)
           VALUES ('$title', '$description')");

$assessment_id = $db->insert_id;

$questions = $_POST['questions'];
$option_texts = $_POST['option_texts'];
$scores = $_POST['scores'];
$source = $_POST['source'] ?? '';

$optIndex = 0;

foreach ($questions as $qText) {

    $db->query("INSERT INTO questions (assessment_id, question_text)
                VALUES ($assessment_id, '$qText')");
    $question_id = $db->insert_id;

    for ($i = 0; $i < 4; $i++) {

        if (!isset($option_texts[$optIndex]))
            break;

        $optText = $option_texts[$optIndex];
        $score = intval($scores[$optIndex]);

        $db->query("INSERT INTO options (question_id, option_text, score, source)
                    VALUES ($question_id, '$optText', $score, $source)");

        $optIndex++;
    }
}

header("Location: admin-assessment.php");
exit();
