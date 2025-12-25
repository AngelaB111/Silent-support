<?php
include('connect.php');
$sql = "SELECT * FROM assessments";
$result = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Assessments</title>
  <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
  <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="styles/navbar.css" />
  <link rel="stylesheet" href="styles/assessments.css" />
</head>

<body>
  <?php include("navbar.php") ?>

  <div class="assessment-container">
    <h1>Take a quick check</h1>
    <p class="subtitle">Discover early patterns for common mental problems</p>
    <?php
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo '
          <div class="card">
            <h2 class="title">' . htmlspecialchars($row["title"]) . '</h2>
              <p class="desc"> ' . htmlspecialchars($row["description"]) . '</p>
            <form action="take-assessment.php" method="GET">
  <button type="submit" class="take-btn" name="assessment_id" value="' . $row["assessment_id"] . '">Take Assessment</button>
</form>
          </div>
        ';
      }
    } else {
      echo "<p>No assessments available right now.</p>";
    }
    $db->close();
    ?>
    <div class="disclaimer">
      <p><b>Disclaimer:</b>These assessments are for informational purposes only and is not intended to diagnose
        or replace professional mental health care. The results do not constitute a medical diagnosis and should
        not be used as a substitute for consultation with a qualified mental health professional.
        If you are experiencing distress or need support, please contact a licensed mental health provider or
        reach out to a crisis line in your area.</p>
    </div>
  </div>
</body>


<?php include("footer.php") ?>

</html>


<script>
  (function () { if (!window.chatbase || window.chatbase("getState") !== "initialized") { window.chatbase = (...arguments) => { if (!window.chatbase.q) { window.chatbase.q = [] } window.chatbase.q.push(arguments) }; window.chatbase = new Proxy(window.chatbase, { get(target, prop) { if (prop === "q") { return target.q } return (...args) => target(prop, ...args) } }) } const onLoad = function () { const script = document.createElement("script"); script.src = "https://www.chatbase.co/embed.min.js"; script.id = "RxU2NixvO8dnE0x3jXRA7"; script.domain = "www.chatbase.co"; document.body.appendChild(script) }; if (document.readyState === "complete") { onLoad() } else { window.addEventListener("load", onLoad) } })();
</script>
<script src="scripts/script.js" defer></script>