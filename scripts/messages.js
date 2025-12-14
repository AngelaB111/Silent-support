 
   //private code popup 
   function openPopup1() {
        const content = document.getElementById('message').value.trim();
        const category = document.getElementById('category').value;
        const isPublic = document.getElementById('makePublic').checked ? 'yes' : 'no';

        if (!content) {
            displaySubmissionError("Please write a message.");
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
                    displaySubmissionError("Error: " + data.error);
                }
            })
            .catch(err => displaySubmissionError("Network error: " + err));
    }

    function closePopup1() {
        document.getElementById("popupModal1").style.display = "none";
    }
//Ai response popup :  
   function getAIReply() {
    const content = document.getElementById('message').value.trim();
    const category = document.getElementById('category').value; 

    if (!content) {
        displaySubmissionError("Please write a message before getting an AI reply.");
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

// toggle button 
function submitMessage() {
    const selectedReplyType = document.querySelector('input[name="reply-type"]:checked').value;
    const messageInput = document.getElementById('message').value.trim();
    
    if (messageInput.length === 0) {
        displaySubmissionError("Please enter your message before submitting.");
        return; 
    }

    if (selectedReplyType === 'ai') {
        console.log("Submitting message for AI Reply.");
        getAIReply(); 
        return; 

    } else if (selectedReplyType === 'human') {
        console.log("Submitting message for Human Reply. Fetching AI category...");
        getAICategoryAndSubmit(messageInput);

    } else {
        console.error("No valid reply type selected.");
        displaySubmissionError("Please choose whether you want a Human or AI reply.");
    }
}
//category AI
function getAICategoryAndSubmit(content) {
    let aiCategory = 'N/A'; 
    const submitButton = document.querySelector('.submit-btn span');
    submitButton.innerText = "Analyzing & Submitting...";
    
    let formDataCategory = new FormData();
    formDataCategory.append("content", content);

    fetch("get-category.php", {
        method: "POST",
        body: formDataCategory
    })
    .then(r => r.json())
    .then(categoryData => {
        if (!categoryData.success) {
            return Promise.reject("Error categorizing message: " + categoryData.error);
        }

        aiCategory = categoryData.category;
        
        const isPublic = document.getElementById('makePublic').checked ? 'yes' : 'no';

        let formDataSubmit = new FormData();
        formDataSubmit.append("content", content);
        formDataSubmit.append("category", aiCategory); 
        formDataSubmit.append("public", isPublic);
        return fetch("submit-message.php", {
            method: "POST",
            body: formDataSubmit
        });
    })
    .then(r => {
        if (!r.ok) throw new Error("Server response failed for submission.");
        return r.json();
    })
    .then(data => {
        submitButton.innerText = "Submit Message"; 

        if (data.success) {
            let iframe = document.querySelector("#popupModal1 iframe");
            
            iframe.src = `success.php?message_id=${data.message_id}&access_code=${data.access_code}&category=${aiCategory}`;
            
            document.getElementById("popupModal1").style.display = "flex";
            document.querySelector(".message-form").reset();

        } else {
            displaySubmissionError("Submission Error: " + data.error);
        }
    })
    .catch(err => {
        submitButton.innerText = "Submit Message"; 
        const errorMessage = typeof err === 'string' ? err : (err.message || "Unknown error");
        displaySubmissionError("Network or submission error: " + errorMessage);
    });
}

function displaySubmissionError(message) {
    const statusBox = document.getElementById('submission-status-message');
    
    const messageBox = document.getElementById('message');
    statusBox.textContent = message;
    statusBox.className = 'status-box error';
    statusBox.style.display = 'block';
    messageBox.className= 'message-box error'
    setTimeout(() => {
        statusBox.style.display = 'none';
        statusBox.textContent = '';
    }, 8000); 
}