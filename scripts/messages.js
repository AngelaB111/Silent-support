 
   //private code popup 
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
//Ai response popup :  
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


//     //toggle button code 
// function submitMessage() {
//     const selectedReplyType = document.querySelector('input[name="reply-type"]:checked').value;
//     const messageInput = document.getElementById('message').value.trim();
//     if (messageInput.length === 0) {
//         alert("Please enter your message before submitting.");
//         return; 
//     }
//     if (selectedReplyType === 'human') {
//         console.log("Submitting message for Human Reply.");
//         openPopup1(); 

//     } else if (selectedReplyType === 'ai') {
//         console.log("Submitting message for AI Reply.");
//         getAIReply();

//     } else {
//         console.error("No valid reply type selected.");
//         alert("Please choose whether you want a Human or AI reply.");
//     }
// }
// Function to handle getting the AI category, submitting, and showing the success popup.
function submitMessage() {
    const selectedReplyType = document.querySelector('input[name="reply-type"]:checked').value;
    const messageInput = document.getElementById('message').value.trim();
    
    if (messageInput.length === 0) {
        alert("Please enter your message before submitting.");
        return; 
    }

    if (selectedReplyType === 'ai') {
        // Option 1: User wants an AI reply (handled by existing getAIReply)
        console.log("Submitting message for AI Reply.");
        getAIReply(); 
        return; // Stop the function here.

    } else if (selectedReplyType === 'human') {
        // Option 2: User wants a Human reply. We MUST categorize via AI first.
        console.log("Submitting message for Human Reply. Fetching AI category...");

        // Start the categorization process
        getAICategoryAndSubmit(messageInput);

    } else {
        console.error("No valid reply type selected.");
        alert("Please choose whether you want a Human or AI reply.");
    }
}
// NEW CORE FUNCTION: This combines the AI category fetch and the submission logic
function getAICategoryAndSubmit(content) {
    // 💡 FIX: Declare a variable here so it is available across the entire promise chain scope
    let aiCategory = 'N/A'; 

    // Show a loading indicator on the UI before the fetch
    const submitButton = document.querySelector('.submit-btn span');
    submitButton.innerText = "Analyzing & Submitting...";
    
    let formDataCategory = new FormData();
    formDataCategory.append("content", content);

    // STEP 1: Get AI Category from the server
    fetch("get-category.php", {
        method: "POST",
        body: formDataCategory
    })
    .then(r => r.json())
    .then(categoryData => {
        if (!categoryData.success) {
            // Error in categorization, return a rejected Promise to skip to the catch block
            return Promise.reject("Error categorizing message: " + categoryData.error);
        }

        // 💡 FIX: Store the category in the accessible variable
        aiCategory = categoryData.category;
        
        // Retrieve other form data needed for final submission
        const isPublic = document.getElementById('makePublic').checked ? 'yes' : 'no';

        let formDataSubmit = new FormData();
        formDataSubmit.append("content", content);
        // CRITICAL: Use the AI's category here
        formDataSubmit.append("category", aiCategory); 
        formDataSubmit.append("public", isPublic);

        // STEP 2: Submit the message to the database (submit-message.php)
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
        submitButton.innerText = "Submit Message"; // Reset button text

        if (data.success) {
            // STEP 3: Display success popup with the AI's category
            let iframe = document.querySelector("#popupModal1 iframe");
            
            // 💡 FIX: Use the 'aiCategory' variable stored outside this .then() block
            iframe.src = `success.php?message_id=${data.message_id}&access_code=${data.access_code}&category=${aiCategory}`;
            
            document.getElementById("popupModal1").style.display = "flex";
            document.querySelector(".message-form").reset();

        } else {
            alert("Submission Error: " + data.error);
        }
    })
    .catch(err => {
        submitButton.innerText = "Submit Message"; // Reset button text
        // Ensure the error message is clear, whether it was a string or an Error object
        const errorMessage = typeof err === 'string' ? err : (err.message || "Unknown error");
        alert("Network or submission error: " + errorMessage);
    });
}