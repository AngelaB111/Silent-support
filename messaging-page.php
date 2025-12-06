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
    <link rel="stylesheet" href="styles/style.css?v=3" />
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

                <button type="button" class="submit-btn" onclick="openPopup1()">
                    <span><img src="icons/send.png"></span>
                    <span> submit message</span>
                </button>
                <button type="button" class="submit-btn" onclick="getAIReply()">
                    <span><img src="icons/robot.png"></span>
                    <span> get AI reply </span>
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
            <div id="aiReplyBox"></div>
        </div>
    </div>

</body>

</html>
<script>
    function openPopup1() {
        const content = document.getElementById('message').value.trim();
        const category = document.getElementById('category').value;
        const isPublic = document.getElementById('makePublic').checked ? 'yes' : 'no';

        if (!content || category === "choose category that best describes your message") {
            alert("Please write a message and choose a category.");
            return;
        }

        let formData = new FormData();
        formData.append("content", content);
        formData.append("category", category);
        formData.append("public", isPublic);

        fetch("submit-message.php", {
            method: "POST",
            body: formData
        })
            .then(r => r.json())
            .then(data => {

                if (data.success) {
                    let iframe = document.querySelector("#popupModal1 iframe");
                    iframe.src = `success.php?message_id=${data.message_id}&access_code=${data.access_code}`;
                    document.getElementById("popupModal1").style.display = "flex";
                    document.querySelector(".message-form").reset();

                } else {
                    alert("Error: " + data.error);
                }
            })
            .catch(err => alert("Network error: " + err));
    }

    function closePopup1() {
        document.getElementById("popupModal1").style.display = "none";
    }

    function getAIReply() {
        const content = document.getElementById('message').value.trim();

        if (!content) {
            alert("Please write a message first.");
            return;
        }

        let formData = new FormData();
        formData.append("content", content);

        fetch("ai-reply.php", {
            method: "POST",
            body: formData
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById("aiReplyBox").innerText = data.reply;
                    document.getElementById("aiPopup").style.display = "flex";
                } else {
                    document.getElementById("aiReplyBox").innerText = "AI Error: " + data.error;
                    document.getElementById("aiPopup").style.display = "flex";
                }
            })
            .catch(err => {
                document.getElementById("aiReplyBox").innerText = "Network error." + err;
                document.getElementById("aiPopup").style.display = "flex";
            });
    }

    function closeAIPopup() {
        document.getElementById("aiPopup").style.display = "none";
    }

</script>


<script src="scripts/script.js" defer></script>