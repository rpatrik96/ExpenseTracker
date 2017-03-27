<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        require 'head.php';
    ?>
    <title>ExpenseTracker - Home</title>
</head>
<body>
    <?php
        require 'menu.php';
    ?>  
    <div class="content">
        <h3>What is ExpenseTracker?</h3>
        ExpenseTracker is a unique and convenient way to be up-to-date with your finances.
        <p>With ExpenseTracker you will be able to:
        <ul>
            <li>organize</li>
            <li>add</li>
            <li>import</li>
        </ul>
        data from different sources.
        </p>
        <p>
            You also have the possibility to add a list to make the automatic insert algorithm user specific.
        </p>
    </div>
    <?php
        include 'footer.php';
    ?>
</body>
</html>