<?php
include('connect.php');
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Therapist Dashboard</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="styles/navbar.css?v=3">
    <link rel="stylesheet" href="styles/dashboard.css?v=5">
</head>

<body>
    <?php include("therapist_navbar.php"); ?>

    <div class="container">
        <aside class="left-panel">
            <header>
                <h2>Messages</h2>
            </header>

            <div id="messagesContainer" class="messages-container">
                <?php
                $sql = "SELECT message_id, access_code, category, content, public, flagged, replied FROM messages ORDER BY message_id DESC";
                $res = mysqli_query($db, $sql);

                while ($row = mysqli_fetch_assoc($res)) {
                    $id = (int) $row['message_id'];
                    $category = htmlspecialchars($row['category']);
                    $preview = htmlspecialchars(mb_strimwidth($row['content'], 0, 120, "..."));
                    $publicText = ($row['public'] === 'yes') ? 'Public' : 'Private';
                    $replied = ($row['replied'] === 'yes') ? 'Answered' : 'Pending';
                    $flagged = $row['flagged'];

                    echo <<<HTML
<div class="message-card" data-id="{$id}" onclick="selectMessage({$id}, this)">
  <div class="card-top">
    <div class="left">
      <div class="msg-id">#{$id}</div>
      <div class="cat-badge">{$category}</div>
    </div>
    <div class="right">
      <div class="status {$row['replied']}">{$replied}</div>
HTML;
                    if ($flagged === "yes") {
                        echo '<div class="urgent">URGENT</div>';
                    }
                    echo <<<HTML
    </div>
  </div>
  <div class="card-body">
    <p class="preview">{$preview}</p>
  </div>
  <div class="card-foot">
    <small>{$publicText}</small>
  </div>
</div>
HTML;
                } ?>
            </div>
        </aside>

        <main class="right-panel">
            <div class="detail-card">
                <div id="emptyState" class="empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2v4" stroke="#bbb" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <p>Select a message to view details</p>
                </div>

                <div id="messageDetail" class="message-detail" style="display:none;">
                    <div class="detail-header">
                        <h2>Message #<span id="detailMessageId"></span></h2>
                    </div>
                    <div class="detail-body">
                        <p class="label">Category (edit)</p>
                        <input id="editCategory" type="text" placeholder=" " />
                        <div class="detail-status">
                            <span id="detailPublic" style="display:none;"></span>
                            <span id="detailFlagged" class="urgent" style="display:none;"></span>
                            <span id="detailReplied" style="display:none;"></span>
                        </div>

                        <p class="label">Message</p>
                        <div id="detailContent" class="detail-content"></div>

                        <p class="label">Reply</p>
                        <textarea id="replyText" placeholder="Write your reply here..."></textarea>

                        <div class="detail-actions">
                            <button id="saveReplyBtn" class="btn" onclick="saveReply()">
                                <img class="sendImg" src="icons/send.png" /> Send Reply
                            </button>
                            <button id="deleteBtn" class="btn" onclick="deleteMessage()">
                                <img class="deleteImg" src="icons/delete.png" /> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="scripts/dashboard.js"></script>
</body