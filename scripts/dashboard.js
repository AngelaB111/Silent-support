let selectedId = null;
const dialogBackdrop = document.getElementById('custom-dialog-backdrop');
const dialogContent = document.querySelector('#custom-dialog-backdrop .dialog-content'); 
const dialogTitle = document.getElementById('dialog-title');
const dialogMessage = document.getElementById('dialog-message');
const dialogOkBtn = document.getElementById('dialog-ok-btn');
const dialogCancelBtn = document.getElementById('dialog-cancel-btn');

function showAlert(message, title = 'Alert', type = 'info') {
    return new Promise(resolve => {
        dialogTitle.innerText = title;
        dialogMessage.innerText = message;
        dialogContent.className = 'dialog-content ' + type; 
        dialogCancelBtn.style.display = 'none';
        dialogOkBtn.innerText = 'OK'; 
        dialogBackdrop.style.display = 'flex';
        dialogOkBtn.onclick = () => {
            dialogBackdrop.style.display = 'none';
            resolve(); 
        };
    });
}


function showConfirm(message, title = 'Confirmation') {
    return new Promise(resolve => {
        dialogTitle.innerText = title;
        dialogMessage.innerText = message;
        dialogContent.className = 'dialog-content warning'; 
        dialogCancelBtn.style.display = 'inline-block';
        dialogOkBtn.innerText = 'Confirm'; 
        dialogBackdrop.style.display = 'flex';
        dialogOkBtn.onclick = () => {
            dialogBackdrop.style.display = 'none';
            resolve(true); 
        };

        dialogCancelBtn.onclick = () => {
            dialogBackdrop.style.display = 'none';
            resolve(false); 
        };
    });
}

function selectMessage(id, clickedElem) {
  selectedId = id;

  document.querySelectorAll('.message-card').forEach(c => c.classList.remove('active'));
  if (clickedElem) clickedElem.classList.add('active');

  fetch('fetch_message.php?message_id=' + encodeURIComponent(id))
    .then(r => r.json())
    .then(data => {
      const emptyState = document.getElementById('emptyState');
      const messageDetail = document.getElementById('messageDetail');
      emptyState.style.display = 'none';
      messageDetail.style.display = 'flex';
      
      document.getElementById('detailMessageId').innerText = data.message_id;
      document.getElementById('detailContent').innerText = data.content;
      document.getElementById('replyText').value = data.reply ?? '';
      
      document.getElementById('detailPublic').innerText = data.public === 'yes' ? 'Public' : 'Private';
      document.getElementById('detailPublic').style.display = 'inline';

      document.getElementById('detailFlagged').innerText = data.flagged === 'yes' ? 'URGENT' : '';
      document.getElementById('detailFlagged').style.display = data.flagged === 'yes' ? 'inline-block' : 'none';

      document.getElementById('detailReplied').innerText = data.replied === 'yes' ? 'Answered' : 'Pending';
      document.getElementById('detailReplied').style.display = 'inline';
    })
    .catch(err => showAlert('Could not load message: ' + err.message, 'Network Error', 'error')); 
}

async function saveReply() {
  if (!selectedId) {
    return showAlert('Select a message first.', 'Action Required', 'info'); 
  }

  const reply = document.getElementById('replyText').value.trim();

  if (!reply.length) {
    const confirmation = await showConfirm('Reply is empty. Mark as answered anyway?', 'Confirm Empty Reply');
    if (!confirmation) {
      return; 
    }
  }

  const form = new FormData();
  form.append('message_id', selectedId);
  form.append('reply', reply);

  fetch('save_reply.php', { method: 'POST', body: form })
    .then(r => r.json())
    .then(res => {
      if (res.success) {
        showAlert('Reply saved.', 'Success', 'success'); 
        
        document.getElementById('detailReplied').innerText = 'Answered';
        const card = document.querySelector(`.message-card[data-id="${selectedId}"] .status`);
        if (card) { card.className = 'status yes'; card.innerText = 'Answered'; }
      } else {
        showAlert('Save failed: ' + (res.error || 'unknown'), 'Save Error', 'error'); 
      }
    })
    .catch(e => showAlert('Network error: ' + e.message, 'Network Error', 'error')); 
}

async function deleteMessage() {
  if (!selectedId) {
    return showAlert('Select a message first.', 'Action Required', 'info'); 
  }
  
  const confirmation = await showConfirm('Delete this message permanently?', 'Confirm Deletion');
  if (!confirmation) {
    return; 
  }

  fetch('delete_message.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'message_id=' + encodeURIComponent(selectedId)
  })
    .then(r => r.json())
    .then(res => {
      if (res.success) {
        showAlert('Message deleted.', 'Success', 'success'); 
        
        const card = document.querySelector(`.message-card[data-id="${selectedId}"]`);
        if (card) card.remove();

        document.getElementById('messageDetail').style.display = 'none';
        document.getElementById('emptyState').style.display = 'flex';
        selectedId = null;
      } else {
        showAlert('Delete failed: ' + (res.error || 'unknown'), 'Delete Error', 'error'); 
      }
    })
    .catch(e => showAlert('Network error: ' + e.message, 'Network Error', 'error')); 
}

async function generateAiReply() {
    const detailContent = document.getElementById('detailContent');
    const replyTextarea = document.getElementById('replyText');
    const generateAiBtn = document.getElementById('generateAiBtn');

    const userMessage = detailContent.innerText.trim();

    if (!userMessage) {
        showCustomDialog('Error', 'No message content available to send to AI.', 'Error');
        return;
    }

    const originalHtml = generateAiBtn.innerHTML;
    generateAiBtn.disabled = true;
    generateAiBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Generating...';

    const messageId = document.getElementById('detailMessageId').innerText;

    try {
        const response = await fetch('generate-reply.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `content=${encodeURIComponent(userMessage)}&message_id=${encodeURIComponent(messageId)}`
        });

        const result = await response.json();

        if (result.success) {
            replyTextarea.value = result.reply.trim();
            showCustomDialog('Success', 'AI response generated and placed in the reply box.', 'Success');
        } else {
            console.error("AI Generation Error:", result.error);
            showCustomDialog('Error', `Failed to generate AI reply: ${result.error}`, 'Error');
        }

    } catch (error) {
        console.error('Network or Fetch Error:', error);
        showCustomDialog('Error', 'A network error occurred while contacting the AI service.', 'Error');
    } finally {
        
        generateAiBtn.innerHTML = originalHtml;
        generateAiBtn.disabled = false;
    }
}