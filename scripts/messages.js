 
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
// toggle button 
function submitMessage() {
    const messageInput = document.getElementById('message').value.trim();
    if (messageInput.length === 0) {
        displaySubmissionError("Please enter your message before submitting.");
        return; 
    }
    getAICategoryAndSubmit(messageInput);
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
 aiFlagged = categoryData.flagged;

        
        const isPublic = document.getElementById('makePublic').checked ? 'yes' : 'no';

        let formDataSubmit = new FormData();
        formDataSubmit.append("content", content);
        formDataSubmit.append("category", aiCategory);
formDataSubmit.append("flagged", aiFlagged); 
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