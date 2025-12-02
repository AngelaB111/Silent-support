<?php
include "connect.php";

$id = intval($_GET['id']);

$title = $_POST['title'];
$description = $_POST['description'];

$db->query("UPDATE assessments 
            SET title='$title', description='$description'
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

header("Location: admin-assessment.php");
exit();
