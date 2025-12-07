<?php
$current_page = basename($_SERVER['PHP_SELF']);
?> 

<div class="flex-container">
    <header class="navbar">
        <div class="logo">
            <i class="fa fa-circle"></i>
            <h2>Silent support</h2>
        </div>
        <nav class="nav-links">
            <a  class="<?php if ($current_page === 'dashboard.php')
                echo 'active'; ?>" href="dashboard.php">Home</a>
            <a  class="<?php if ($current_page === 'admin-library.php')
                echo 'active'; ?>" href="admin-library.php">Library</a>
            <a  class="<?php if ($current_page === 'admin-assessment.php')
                echo 'active'; ?>" href="admin-assessment.php">Assessments</a>
        </nav>
    </header>
</div>