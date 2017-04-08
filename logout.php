<?php
    /**
    *@file logout.php
    *@author Patrik Reizinger
    *@brief
    *Logout screen.
    */
    session_start();
    if(!$_SESSION['logged_in'])
    {
        header("Location: http://localhost:8090/ExpenseTracker/index.php");
        exit();
    }
    unset($_SESSION['UserID']);
    unset($_SESSION['UserName']);
    $_SESSION['theme'] = 1;
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