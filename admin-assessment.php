<?php
include "connect.php";

$assessments = $db->query("SELECT * FROM assessments ORDER BY assessment_id DESC");

$editMode = false;

if (isset($_GET['edit'])) {
    $editMode = true;

    $id = intval($_GET['edit']);

    $assessment = $db->query("SELECT * FROM assessments WHERE assessment_id=$id")->fetch_assoc();

    $questionsQuery = $db->query("SELECT * FROM questions WHERE assessment_id=$id");
    $questions = [];

    while ($q = $questionsQuery->fetch_assoc()) {

        $optQ = $db->query("SELECT * FROM options WHERE question_id=" . $q['question_id']);
        $q['options'] = $optQ->fetch_all(MYSQLI_ASSOC);
        $questions[] = $q;
    } // === TEMPORARY DEBUG CHECK ===
    if ($editMode && !empty($questions)) {
        echo "";
        echo "";
        echo "";
    }
}
$results = [];
if ($editMode) {
    $resultsQuery = $db->query("SELECT * FROM assessment_results WHERE assessment_id=$id");
    $results = $resultsQuery->fetch_all(MYSQLI_ASSOC);
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Assessments</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/admin-assessments.css?v=5">
</head>

<body>

    <?php include("therapist_navbar.php"); ?>
    <div class="container">

        <div class="left">
            <h3>Assessments :</h3>

            <?php while ($a = $assessments->fetch_assoc()): ?>
                <div class="assessment-card">
                    <strong>#<?php echo $a['title']; ?></strong>

                    <div class="actions">
                        <a href="admin-assessment.php?edit=<?php echo $a['assessment_id']; ?>">
                            <button class="edit-btn">Edit</button>
                        </a>

                        <a href="delete_assessment.php?id=<?php echo $a['assessment_id']; ?>"
                            onclick="return confirm('Delete this assessment?');">
                            <button class="delete-btn">Delete</button>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>

            <button class="add-new-btn" onclick="window.location='admin-assessment.php'">
                + Add New Assessment
            </button>
        </div>

        <div class="right">
            <h3><?php echo $editMode ? "Edit Assessment" : "Assessment"; ?></h3>

            <form
                action="<?php echo $editMode ? "edit_assessment.php?id=" . $assessment['assessment_id'] : "add_assessment.php"; ?>"
                method="POST">

                <label>Title :</label>
                <input type="text" name="title" required value="<?php echo $editMode ? $assessment['title'] : ""; ?>">

                <label>Description :</label>
                <textarea name="description"><?php echo $editMode ? $assessment['description'] : ""; ?></textarea>

                <label>Source (optional):</label>
                <input type="text" name="source" placeholder="ex: Based on the PHQ-9 Assessment"
                    value="<?php echo $editMode ? $assessment['source'] ?? '' : ''; ?>">

                <button type="button" class="add-question-btn" onclick="addQuestion()">+ Add Question</button>
                <div id="questions-container"></div>

                <hr style="margin: 20px 0;">
                <label>Assessment Results:</label>
                <div id="results-container"></div>
                <button type="button" class="add-result-btn" onclick="addResultRow()">+ Add Result Range</button>

                <button type="submit" class="publish-btn">
                    <?php echo $editMode ? "Update Assessment" : "Save & Publish"; ?>
                </button>

            </form>
        </div>

    </div>
    <script>

        window.AssessmentData = <?php
        if ($editMode) {
            echo json_encode($questions);
        } else {
            echo '[]';
        }
        ?>;
        window.IsEditMode = <?php echo $editMode ? 'true' : 'false'; ?>;
        window.ResultData = <?php
        if ($editMode) {
            echo json_encode($results);
        } else {
            echo '[]';
        }
        ?>;
    </script>
    <script src="admin-assessment.js?v=6" defer>
    </script>

</body>

</html>