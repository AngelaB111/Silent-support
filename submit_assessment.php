<?php
include('connect.php');

$score = 0;

// Loop through POSTed answers
foreach ($_POST as $key => $answer_id) {
    if (strpos($key, 'question_') === 0) {
        // Get the score for this answer
        $sql = "SELECT score_value FROM answers WHERE answer_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $answer_id);
        $stmt->execute();
        $stmt->bind_result($score_value);
        $stmt->fetch();
        $stmt->close();

        $score += $score_value;
    }
}

// Determine status based on score
if ($score < 50) {
    $status = "Normal";
    $suggestion = "Keep healthy habits and self-care.";
} elseif ($score < 120) {
    $status = "Mild";
    $suggestion = "Consider journaling and relaxation techniques.";
} elseif ($score < 200) {
    $status = "Moderate";
    $suggestion = "Talking to a counselor may help.";
} else {
    $status = "Severe";
    $suggestion = "Please seek professional support soon.";
}

// Send results to next page
header("Location: results.php?score=$score&status=$status&suggestion=" . urlencode($suggestion));
exit;
?>
