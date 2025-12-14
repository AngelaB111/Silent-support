<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Retrieve your reply</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="styles/navbar.css?v=3" />
    <link rel="stylesheet" href="styles/retrieve.css?v=4">
</head>

<body>
    <?php include("navbar.php") ?>
    <div class="container">
        <h1>Retrive your reply</h1>
        <div class="form-box">
            <label for="message-id">Message ID :</label>
            <input type="text" id="message-id" placeholder="enter your message id">

            <div id="submission-status-message" class="status-box" style="display:none;"></div>
            <label for="access-code">Access Code:</label>
            <input type="text" id="access-code" placeholder="enter your access code">
        </div>
        <div class="button-group">
            <button class="custom-btn">Get Reply</button>
            <button class="custom-btn" onclick="location.href='messaging-page.php'">send a new message</button>
        </div>

    </div>
</body>

</html>
<script>
    document.querySelector(".custom-btn").addEventListener("click", function () {
        const id = document.getElementById("message-id").value.trim();
        const code = document.getElementById("access-code").value.trim();

        if (!id || !code) {
            displaySubmissionError("Please enter both Message ID and Access Code.");
            return;
        }
        window.location.href = `private-message.php?message_id=${id}&access_code=${code}`;
    });

    function displaySubmissionError(message) {
        const accessCodeBox = document.getElementById('access-code');
        const idBox = document.getElementById('message-id');
        accessCodeBox.className = 'accessCode-box error';
        idBox.className = 'id-box error'
        const statusBox = document.getElementById('submission-status-message');
        statusBox.textContent = message;
        statusBox.className = 'status-box error';
        statusBox.style.display = 'block';

    }
</script>

<script src="scripts/script.js" defer></script>