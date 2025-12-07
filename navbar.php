<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="flex-container">
    <header class="navbar">

        <div class="nav-left-group">
            <?php if ($current_page !== 'home.php'): ?>
                <button onclick="history.back()" class="back-btn">← Back</button>
            <?php endif; ?>

            <div class="logo">
                <i class="fa fa-circle" onclick="openPopup()"></i>
                <h2>Silent support</h2>
            </div>
        </div>
        <nav class="nav-links">
            <a href="home.php" class="<?php if ($current_page === 'home.php')
                echo 'active'; ?>">Home</a>
            <a href="library.php" class="<?php if ($current_page === 'library.php')
                echo 'active'; ?>">Library</a>
            <a href="assessments.php"
                class="<?php if ($current_page === 'assessments.php')
                    echo 'active'; ?>">Assessments</a>
            <a href="public-page.php" class="<?php if ($current_page === 'public-page.php')
                echo 'active'; ?>">Public
                Q&A</a>
        </nav>
    </header>
</div>

<div id="popupModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closePopup()">&times;</span>
        <iframe src="login.php" frameborder="0"></iframe>
    </div>
</div>