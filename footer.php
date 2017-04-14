<?php
    /**
    *@file footer.php
    *@author Patrik Reizinger
    *@brief
    *Dynamic footer with php date handling.
    */
    echo ('<div class="footer">&#9400; Patrik Reizinger 	  2017-'.date('Y'));
    echo('<form method="POST">
        <input type="submit" style="width:20px; height:20px;background-color: rgb(0, 28, 73);padding: 0px 0px;
            margin: -20px 5px 0px 0px;float:right;" value="" name=blue>
    </form>

    <form method="POST">
        <input type="submit" style="width:20px; height:20px; background-color: rgb(0, 72, 31);padding: 0px 0px;
            margin: -20px 35px 0px 0px; float:right;" value="" name=green>
    </form></div>');
?>
   
