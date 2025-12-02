<?php
include "connect.php";

$id = intval($_GET['id']);

$db->query("DELETE FROM options 
            WHERE question_id IN (SELECT question_id FROM questions WHERE assessment_id=$id)");

$db->query("DELETE FROM questions WHERE assessment_id=$id");

$db->query("DELETE FROM assessments WHERE assessment_id=$id");

header("Location: admin-assessment.php");
exit();
