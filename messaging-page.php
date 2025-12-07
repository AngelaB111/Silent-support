<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Silent Support | Send Anonymous Message</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Georgia&family=Open+Sans:wght@300;400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="styles/navbar.css?v=4" />
    <link rel="stylesheet" href="styles/style.css?v=5" />
</head>

<body>
    <?php include("navbar.php") ?>

    <section class="message-section">
        <h1>send an anonymous message</h1>
        <p class="subtitle">share your thoughts in a safe space</p>

        <form class="message-form">
            <div class="form-card">

                <div class="form-header">
                    <div class="circle"></div>
                    <h2>your message</h2>
                </div>

                <p class="title1">what’s on your mind ? *</p>

                <textarea id="message" placeholder="share your thoughts, feelings or ask a question"></textarea>

                <div class="checkbox-group">
                    <input type="checkbox" id="makePublic">
                    <p>make my message public ? <br>
                        <small>help others by sharing your experience in our public Q&A section</small>
                    </p>
                </div>

                <div class="privacy-box">
                    <h4>privacy notice</h4>
                    <p>we don't collect names, emails, or any identifying info</p>
                    <p>in case you make your message private only you will have access to the reply via message id
                        and Access code (you’ll receive them after submission)</p>
                </div>

                <div class="category-section">
                    <label for="category"><em>category:</em></label>
                    <select id="category">
                        <option disabled selected>choose category that best describes your message</option>
                        <option>Stress</option>
                        <option>Anxiety</option>
                        <option>Relationships</option>
                        <option>Grief</option>
                        <option>Other</option>
                    </select>
                </div>

                <div class="reply-toggle-group">
                    <input type="radio" id="reply-human" name="reply-type" value="human" checked>
                    <label for="reply-human">
                        <img src="icons/send.png" alt="Send Icon">
                        Get Therapist Reply
                    </label>

                    <input type="radio" id="reply-ai" name="reply-type" value="ai">
                    <label for="reply-ai">
                        <img src="icons/ai-icon.png" alt="Robot Icon">
                        Get AI Reply
                    </label>
                </div>

                <button type="button" class="submit-btn" onclick="submitMessage()">
                    <span>Submit Message</span>
                </button>

            </div>
        </form>
    </section>

    <div id="popupModal1" class="modal1">
        <div class="modal-content">
            <span class="close-btn" onclick="closePopup1()">&times;</span>
            <iframe src="success.php" frameborder="0"></iframe>
        </div>
    </div>

    <div id="aiPopup" class="modal1" style="display:none;">
        <div class="modal-content">
            <span class="close-btn" onclick="closeAIPopup()">&times;</span>
            <h1 class="ai-title">Silent Support AI Response: </h1>
            <p class="ai-description"> Thank you for sharing your message in this safe space. This automated response is
                dedicated to offering immediate support and understanding.</p>

            <div id="aiReplyBox"></div>

        </div>
    </div>

</body>

</html>

<script src="scripts/messages.js?v=5" defer></script>
<script src="scripts/script.js" defer></script>