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
   
        <link rel="shortcut icon" href="ExpenseTracker.ico">
        <script type="text/javascript" src="fusioncharts/fusioncharts.js"></script>
        <script type="text/javascript" src="fusioncharts/themes/fusioncharts.theme.carbon.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, charset=ISO-8859-1">
        <link rel="stylesheet" type="text/css" href="webpage_style.css">
    <?php
        if(!isset($_SESSION['theme']) or $_SESSION['theme'] == 1)
        {
            $_SESSION['theme'] = 1;
            echo('<link rel="stylesheet" type="text/css" href="blue.css">');
        }
        if($_SESSION['theme'] == 2)
        {
            $_SESSION['theme'] = 1;
            echo('<link rel="stylesheet" type="text/css" href="green.css">');
        }
    ?>