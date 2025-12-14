<?php
include('connect.php');

$total_score = 0;
$assessment_id = null;
$status = "Error";
$suggestion = "An unknown error occurred during submission.";

if (!isset($_POST['assessment_id']) || !isset($_POST['options']) || !is_array($_POST['options'])) {
    $suggestion = "Form submission failed or was incomplete.";
    header("Location: results.php?score=$total_score&status=" . urlencode($status) . "&suggestion=" . urlencode($suggestion));
    exit;
}

$assessment_id = intval($_POST['assessment_id']);

$sql_score = "SELECT score FROM options WHERE option_id = ?";
$stmt_score = $db->prepare($sql_score);

if (!$stmt_score) {
    die("Database error during score preparation: " . $db->error);
}

foreach ($_POST['options'] as $question_id => $option_id) {
    $clean_option_id = intval($option_id);

    $stmt_score->bind_param("i", $clean_option_id);
    $stmt_score->execute();
    $stmt_score->bind_result($score_value);

    if ($stmt_score->fetch()) {
        $total_score += $score_value;
    }
}
$stmt_score->close();

$sql_result = "
    SELECT interpretation, suggestion 
    FROM assessment_results 
    WHERE assessment_id = ? 
    AND ? BETWEEN min_score AND max_score
    ORDER BY min_score ASC
    LIMIT 1
";

$stmt_result = $db->prepare($sql_result);

if (!$stmt_result) {
    die("Database error during result preparation: " . $db->error);
}

$stmt_result->bind_param("ii", $assessment_id, $total_score);
$stmt_result->execute();
$stmt_result->bind_result($interpretation, $suggestion_text);

if ($stmt_result->fetch()) {
    $status = $interpretation;
    $suggestion = $suggestion_text;
} else {
    $status = "Undetermined Result";
    $suggestion = "Could not find a valid interpretation for the score ($total_score). Please contact support.";
}
$stmt_result->close();

$db->close();
header("Location: results.php?score=$total_score&status=" . urlencode($status) . "&suggestion=" . urlencode($suggestion));
exit;
?>