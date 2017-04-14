<?php 
    /**
    *@file head.php
    *@author Patrik Reizinger
    *@brief
    *Including icon, .css, and the FusionCharts library for the graphs.
    */
    /**Links are including the icon, charackter set and the used graphical libraries,
    I am using FusionchartssSite XT, but the version which is 
    ONLY FOR PERSONAL, BUT NOT FOR COMMERCIAL PURPOSES
    http://www.fusioncharts.com/*/
    ?>

        <script type="text/javascript" src="fusioncharts/fusioncharts.js"></script>
        <script type="text/javascript" src="fusioncharts/themes/fusioncharts.theme.carbon.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, charset=ISO-8859-1">
        <link rel="stylesheet" type="text/css" href="webpage_style.css">
    <?php
        /*Theme change handle - form in footer.php*/
        if($_SERVER['REQUEST_METHOD'] =="POST" and isset($_POST['blue']))
        {
            if($_SESSION['logged_in'])
            {
                $mysqli = new mysqli("localhost", "root", "", "expensetracker");
                if($mysqli->connect_errno)
                {
                    echo "MySQL Error: " . $mysqli->connect_error . "<BR/>";
                }
                $query = sprintf("UPDATE user SET Theme='%d' WHERE UserID=%d;", 1, $_SESSION['UserID']);
                $mysqli->query($query);
                $mysqli->close();
            }
            $_SESSION['theme'] = 1;
        }
        if($_SERVER['REQUEST_METHOD'] =="POST" and isset($_POST['green']))
        {
            if($_SESSION['logged_in'])
            {
                $mysqli = new mysqli("localhost", "root", "", "expensetracker");
                if($mysqli->connect_errno)
                {
                    echo "MySQL Error: " . $mysqli->connect_error . "<BR/>";
                }
                $query = sprintf("UPDATE user SET Theme='%d' WHERE UserID=%d;", 2, $_SESSION['UserID']);
                $mysqli->query($query);
                $mysqli->close();
            }
            $_SESSION['theme'] = 2;
        }
        if(!isset($_SESSION['theme']) or $_SESSION['theme'] == 1)
        {
            echo('<link rel="shortcut icon" href="logo_blue.ico">');
           // $_SESSION['theme'] = 1;
            echo('<link rel="stylesheet" type="text/css" href="blue.css">');
        }
        elseif($_SESSION['theme'] == 2)
        {
            echo('<link rel="shortcut icon" href="logo_green.ico">');
           // $_SESSION['theme'] = 2;
            echo('<link rel="stylesheet" type="text/css" href="green.css">');
        }
        else
        {
            
        }
    ?>