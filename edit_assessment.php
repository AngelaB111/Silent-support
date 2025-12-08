<?php
include "connect.php";

$id = intval($_GET['id']);

$title = $_POST['title'];
$description = $_POST['description'];
$source = $_POST['source'];

$db->query("UPDATE assessments 
            SET title='$title', description='$description', source='$source'
            WHERE assessment_id=$id");

$questions = $_POST['questions'];
$question_ids = $_POST['question_ids'];

$option_texts = $_POST['option_texts'];
$option_ids = $_POST['option_ids'];
$scores = $_POST['scores'];

$optIndex = 0;

foreach ($questions as $index => $qText) {

    $qid = intval($question_ids[$index]);

    if ($qid) {
        $db->query("UPDATE questions SET question_text='$qText'
                    WHERE question_id=$qid");
    } else {
        $db->query("INSERT INTO questions (assessment_id, question_text)
                    VALUES ($id, '$qText')");
        $qid = $db->insert_id;
    }

    for ($i = 0; $i < 4; $i++) {

        if (!isset($option_texts[$optIndex]))
            break;

        $optText = $option_texts[$optIndex];
        $score = intval($scores[$optIndex]);
        $optID = intval($option_ids[$optIndex]);

        if ($optID) {
            $db->query("UPDATE options 
                        SET option_text='$optText', score=$score
                        WHERE option_id=$optID");
        } else {
            $db->query("INSERT INTO options (question_id, option_text, score)
                        VALUES ($qid, '$optText', $score)");
        }

        $optIndex++;
    }
}
$result_ids = $_POST['result_ids'];
$min_scores = $_POST['min_scores'];
$max_scores = $_POST['max_scores'];
$interpretations = $_POST['interpretations'];
$suggestions = $_POST['suggestions'];

foreach ($result_ids as $index => $resultID) {

    $resultID = intval($result_ids[$index]);
    $minScore = intval($min_scores[$index]);
    $maxScore = intval($max_scores[$index]);
    $interpretation = $interpretations[$index];
    $suggestion = $suggestions[$index];

    if ($resultID) {
        $db->query("UPDATE assessment_results
                    SET min_score=$minScore, max_score=$maxScore, 
                        interpretation='$interpretation', suggestion='$suggestion'
                    WHERE result_id=$resultID");
    } else {
        $db->query("INSERT INTO assessment_results 
                    (assessment_id, min_score, max_score, interpretation, suggestion)
                    VALUES ($id, $minScore, $maxScore, '$interpretation', '$suggestion')");
    }
}

header("Location: admin-assessment.php");
exit();
