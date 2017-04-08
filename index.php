<?php
    /**
    *@file index.php
    *@author Patrik Reizinger
    *@brief
    *Index page.
    */
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
        
           <?php 
            if ($_SESSION['logged_in'] == 1) 
            {
                echo('<iframe width="450" height="300" style="border:none;"
                 src="https://www.youtube.com/embed/ETxmCCsMoD0?autoplay=1"></iframe>');
            }
            /*else
            {
                echo('<iframe width="450" height="300" style="border:none;" 
                src="https://www.youtube.com/embed/ETxmCCsMoD0"></iframe>');
            }*/
            ?>
        
    </div>
    <?php
        include 'footer.php';
    ?>
</body>
</html>