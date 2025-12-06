//Ai response popup code :   
   
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
