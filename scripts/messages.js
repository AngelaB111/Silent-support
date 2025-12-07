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
    const category = document.getElementById('category').value; 

    if (!content || category === "choose category that best describes your message") {
        alert("Please write a message and choose a category before getting an AI reply.");
        return;
    }

    document.getElementById("aiReplyBox").innerHTML = 
        '<p style="text-align:center; padding: 20px;"><strong>Generating Response...</strong></p>';
    document.getElementById("aiPopup").style.display = "flex";


    let formData = new FormData();
    formData.append("content", content);
    formData.append("category", category);

    fetch("ai-reply.php", {
        method: "POST",
        body: formData
    })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById("aiReplyBox").innerHTML = data.reply;
            } else {
                document.getElementById("aiReplyBox").innerHTML = 
                    '<h2>AI Error</h2><p>Sorry, the AI service encountered an issue: ' + (data.error || "Unknown Error") + '</p>';
            }
        })
        .catch(err => {
            document.getElementById("aiReplyBox").innerHTML = 
                '<h2>Network Error</h2><p>Could not connect to the AI service. Error details: ' + err + '</p>';
        });
}

    function closeAIPopup() {
        document.getElementById("aiPopup").style.display = "none";
    }

function submitMessage() {
    const selectedReplyType = document.querySelector('input[name="reply-type"]:checked').value;
    const messageInput = document.getElementById('message').value.trim();
    if (messageInput.length === 0) {
        alert("Please enter your message before submitting.");
        return; 
    }
    if (selectedReplyType === 'human') {
        console.log("Submitting message for Human Reply.");
        openPopup1(); 

    } else if (selectedReplyType === 'ai') {
        console.log("Submitting message for AI Reply.");
        getAIReply();

    } else {
        console.error("No valid reply type selected.");
        alert("Please choose whether you want a Human or AI reply.");
    }
}