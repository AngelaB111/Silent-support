<?php
include('connect.php');
if (!isset($_SESSION['Therapist_username'])) {
    die("Unauthorized access");
}
$filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$sql_where = '';

if ($filter === 'pending') {
    $sql_where = "WHERE replied = 'no'";
} elseif ($filter === 'answered') {
    $sql_where = "WHERE replied = 'yes'";
} elseif ($filter === 'Urgent') {
    $sql_where = "WHERE flagged = 'yes'";
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Therapist Dashboard</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="styles/navbar1.css?v=5">
    <link rel="stylesheet" href="styles/dashboard.css?v=7">
</head>

<body>
    <?php include("therapist_navbar.php"); ?>

    <div class="container">
        <aside class="left-panel">
            <header>
                <h2 class="messages">Messages</h2>
            </header>

            <div class="message-filters">
                <a href="?status=all" class="filter-tab <?php echo $filter === 'all' ? 'active' : ''; ?>">All</a>
                <a href="?status=Urgent"
                    class="filter-tab <?php echo $filter === 'Urgent' ? 'active' : ''; ?>">Urgent</a>

                <a href="?status=pending"
                    class="filter-tab <?php echo $filter === 'pending' ? 'active' : ''; ?>">Pending</a>

                <a href="?status=answered"
                    class="filter-tab <?php echo $filter === 'answered' ? 'active' : ''; ?>">Answered</a>

            </div>

            <div id="messagesContainer" class="messages-container">
                <?php
                $sql = "SELECT message_id, access_code, category, content, public, flagged, replied FROM messages $sql_where ORDER BY message_id DESC";
                $res = mysqli_query($db, $sql);

                while ($row = mysqli_fetch_assoc($res)) {
                    $id = (int) $row['message_id'];
                    $category = htmlspecialchars($row['category']);
                    $preview = htmlspecialchars(mb_strimwidth($row['content'], 0, 120, "..."));
                    $publicText = ($row['public'] === 'yes') ? 'Public' : 'Private';
                    $repliedStatus = $row['replied'];
                    $repliedText = ($repliedStatus === 'yes') ? 'Answered' : 'Pending';
                    $flagged = $row['flagged'];

                    echo <<<HTML
<div class="message-card" data-id="{$id}" data-replied="{$repliedStatus}" onclick="selectMessage({$id}, this)">
  <div class="card-top">
    <div class="left">
      <div class="msg-id">#{$id}</div>
      <div class="cat-badge">{$category}</div>
    </div>
    <div class="right">
      <div class="status {$repliedStatus}">{$repliedText}</div>
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

                        <div class="detail-status">
                            <input type="hidden" id="detailRepliedStatus" value="">
                            <span id="detailPublic" style="display:none;"></span>
                            <span id="detailFlagged" class="urgent" style="display:none;"></span>
                            <span id="detailReplied" style="display:none;"></span>
                        </div>

                        <p class="label">Message</p>
                        <div id="detailContent" class="detail-content"></div>

                        <p class="label">Reply</p>
                        <textarea id="replyText" placeholder="Write your reply here..."></textarea>

                        <div id="replyLockedMessage" class="reply-locked" style="display:none;">
                            This message has been answered.
                        </div>

                        <div id="detailActions" class="detail-actions">
                            <button id="saveReplyBtn" class="btn" onclick="saveReply()">
                                <i class="fa fa-paper-plane "></i> Send Reply
                            </button>
                            <button id="generateAiBtn" class="btn ai-btn" onclick="generateAiReply()">
                                <i class="fa fa-magic"></i> Generate AI Reply
                            </button>
                            <button id="deleteBtn" class="btn" onclick="deleteMessage()">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div id="custom-dialog-backdrop" class="dialog-backdrop">
        <div id="dialog-content" class="dialog-content">
            <h3 id="dialog-title"></h3>
            <p id="dialog-message"></p>
            <div class="dialog-actions">
                <button id="dialog-cancel-btn" class="dialog-cancel" style="display:none;">Cancel</button>
                <button id="dialog-ok-btn" class="dialog-ok">OK</button>
            </div>
        </div>
    </div>
    <script src="scripts/dashboard.js"></script>
</body>

</html>