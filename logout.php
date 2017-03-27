<?php
    session_start();
    $_SESSION['UserID'] = "";
    $_SESSION['UserName'] = "";
    $_SESSION['logged_in'] = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        require 'head.php';
    ?>
    <title>ExpenseTracker - Logout</title>
</head>
<body>
    <?php
        require 'menu.php';
    ?>  
    <div class="content">
        <span class="success">Logout was successful!</span>
    </div>
    <?php
        include 'footer.php';
    ?>
</body>
</html>