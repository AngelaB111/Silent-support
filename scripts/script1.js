function openPopup1() {
    const content = document.getElementById('message').value.trim();
    const category = document.getElementById('category').value;
    const isPublic = document.getElementById('makePublic').checked ? 'yes' : 'no';

    if (!content || category === "choose category that best describes your message") {
        alert('Please fill in your message and select a category.');
        return;
    }

    // send to PHP
    const formData = new FormData();
    formData.append('content', content);
    formData.append('category', category);
    formData.append('public', isPublic);

    fetch('submit_message.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const iframe = document.querySelector('#popupModal1 iframe');
                iframe.src = `success.php?message_id=${data.message_id}&access_code=${data.access_code}`;
               
                document.getElementById("popupModal1").style.display = "flex";
             
                document.querySelector('.message-form').reset();
            } else {
                alert('Error: ' + (data.error || 'Unknown'));
            }
        })
        .catch(err => alert('Network error: ' + err.message));
}

function closePopup1() {
    document.getElementById("popupModal1").style.display = "none";
}
