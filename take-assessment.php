<?php
include('connect.php');

if (!isset($_GET['assessment_id'])) {
  die("No assessment selected.");
}
$assessment_id = intval($_GET['assessment_id']);

$assessment_sql = "SELECT title, source FROM assessments WHERE assessment_id = $assessment_id";
$assessment_result = $db->query($assessment_sql);
$assessment = $assessment_result->fetch_assoc();
$questions_sql = "SELECT * FROM questions WHERE assessment_id = $assessment_id";

$Source = $assessment['source'] ?? '';
$questions_result = $db->query($questions_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
  <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="styles/navbar.css?v=3" />
  <link rel="stylesheet" href="styles/assessments.css?v=5">

  <title><?php echo htmlspecialchars($assessment['title']); ?></title>

</head>

<body>

  <div class="nav1">
    <?php include("navbar.php") ?>
  </div>
  <div class="take-assessment">

    <h1 class="assessment-title"><?php echo htmlspecialchars($assessment['title']); ?></h1>

    <form action="submit_assessment.php" method="POST">
      <input type="hidden" name="assessment_id" value="<?php echo $assessment_id; ?>">

      <?php
      if ($questions_result->num_rows > 0) {
        $q_num = 1;
        while ($q = $questions_result->fetch_assoc()) {
          echo '<div class="question-block">';
          echo '<div class="question">Question #' . $q_num . ': ' . htmlspecialchars($q['question_text']) . '</div>';

          $options_sql = "SELECT * FROM options WHERE question_id = " . $q['question_id'];
          $options_result = $db->query($options_sql);

          if ($options_result->num_rows > 0) {
            while ($opt = $options_result->fetch_assoc()) {
              echo '<label class="option">
            <input type="radio" name="options[' . $q['question_id'] . ']" value="' . $opt['option_id'] . '" required>
            ' . htmlspecialchars($opt['option_text']) . '
          </label>';
            }
          }
          echo '</div>';
          $q_num++;
        }
      } else {
        echo "<p>No questions available for this assessment.</p>";
      }

      $db->close();
      ?>

      <button type="submit" class="submit-assessment">Submit</button>
    </form>
    <?php if (!empty($Source)): ?>
      <p class="assessment-source">
        <strong>Source:</strong> <?php echo htmlspecialchars($Source); ?>
      </p>
    <?php endif; ?>
  </div>

</body>

</html>

<script src="script/script.js" defer></script>