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
    }
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
    <link rel="stylesheet" href="styles/navbar.css?v=3">
    <link rel="stylesheet" href="styles/admin-assessments.css?v=10">
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

                <button type="button" class="add-question-btn" onclick="addQuestion()">+ Add Question</button>

                <div id="questions-container"></div>

                <button type="submit" class="publish-btn">
                    <?php echo $editMode ? "Update Assessment" : "Save & Publish"; ?>
                </button>
            </form>
        </div>

    </div>

    <script>
        let questionCount = 0;

        <?php if ($editMode): ?>
            let existingQuestions = <?php echo json_encode($questions); ?>;
        <?php else: ?>
            let existingQuestions = [];
        <?php endif; ?>

        window.onload = function () {
            existingQuestions.forEach(q => addQuestion(q));
        };


        function addQuestion(existing = null) {
            questionCount++;

            let qBox = document.createElement("div");
            qBox.className = "question-box";

            qBox.innerHTML = `
        <h4>Q${questionCount} :</h4>

        <input type="hidden" name="question_ids[]" value="${existing ? existing.question_id : ''}">

        <input type="text" name="questions[]" placeholder="Question text"
        value="${existing ? existing.question_text : ''}" required>

        <div class="options">
            <label>Options :</label>
            <button type="button" class="add-option-btn" onclick="addOption(this)">+ Add Option</button>
        </div>
    `;

            document.getElementById("questions-container").appendChild(qBox);

            if (existing && existing.options) {
                existing.options.forEach(opt => addOption(qBox.querySelector(".add-option-btn"), opt));
            }
        }


        function addOption(button, existing = null) {
            let parent = button.parentElement;

            let row = document.createElement("div");
            row.className = "option-row";

            row.innerHTML = `
        <input type="hidden" name="option_ids[]" value="${existing ? existing.option_id : ''}">
        
        <input type="text" name="option_texts[]" placeholder="Option text"
               value="${existing ? existing.option_text : ''}" required>

        <input type="number" name="scores[]" placeholder="Score"
               value="${existing ? existing.score : ''}" required>
    `;

            parent.appendChild(row);
        }
    </script>

</body>

</html>