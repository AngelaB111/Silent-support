let selectedId = null;

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
      document.getElementById('editCategory').value = data.category;
      document.getElementById('detailContent').innerText = data.content;
      document.getElementById('replyText').value = data.reply ?? '';

      document.getElementById('detailPublic').innerText = data.public === 'yes' ? 'Public' : 'Private';
      document.getElementById('detailPublic').style.display = 'inline';

      document.getElementById('detailFlagged').innerText = data.flagged === 'yes' ? 'URGENT' : '';
      document.getElementById('detailFlagged').style.display = data.flagged === 'yes' ? 'inline-block' : 'none';

      document.getElementById('detailReplied').innerText = data.replied === 'yes' ? 'Answered' : 'Pending';
      document.getElementById('detailReplied').style.display = 'inline';
    })
    .catch(err => alert('Could not load message: ' + err.message));
}

function saveReply() {
  if (!selectedId) return alert('Select a message first.');

  const reply = document.getElementById('replyText').value.trim();
  const category = document.getElementById('editCategory').value.trim();

  if (!reply.length && !confirm('Reply is empty. Mark as answered anyway?')) return;

  const form = new FormData();
  form.append('message_id', selectedId);
  form.append('category', category);
  form.append('reply', reply);

  fetch('save_reply.php', { method: 'POST', body: form })
    .then(r => r.json())
    .then(res => {
      if (res.success) {
        alert('Reply saved.');
        document.getElementById('detailReplied').innerText = 'Answered';
        const card = document.querySelector(`.message-card[data-id="${selectedId}"] .status`);
        if (card) { card.className = 'status yes'; card.innerText = 'Answered'; }
      } else {
        alert('Save failed: ' + (res.error || 'unknown'));
      }
    })
    .catch(e => alert('Network error: ' + e.message));
}

function deleteMessage() {
  if (!selectedId) return alert('Select a message first.');
  if (!confirm('Delete this message permanently?')) return;

  fetch('delete_message.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'message_id=' + encodeURIComponent(selectedId)
  })
    .then(r => r.json())
    .then(res => {
      if (res.success) {
        alert('Message deleted.');
        const card = document.querySelector(`.message-card[data-id="${selectedId}"]`);
        if (card) card.remove();

        document.getElementById('messageDetail').style.display = 'none';
        document.getElementById('emptyState').style.display = 'flex';
        selectedId = null;
      } else {
        alert('Delete failed: ' + (res.error || 'unknown'));
      }
    })
    .catch(e => alert('Network error: ' + e.message));
}
