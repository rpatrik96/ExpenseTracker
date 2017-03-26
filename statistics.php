<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <?php
        require 'head.php';
    ?>
<body>
    <?php
        require 'menu.php';
    ?>  
    <div class="content">
    <form method="POST" class="form" >
        <label for="date_min" >Select date</label> 
        <BR/><BR/>
        <input id="date_min" type="date" name=date_min value="<?php echo date('Y-m-d'); ?>">
        <input id="date_max" type="date" name=date_max value="<?php echo date('Y-m-d'); ?>">
        <input type="submit" name=query>
    </form>
    
    <?php
        if($_SERVER['REQUEST_METHOD'] =="POST" and isset($_POST['query']))
        {
            echo('<BR/><BR/><div class="form">');
            $mysqli = new mysqli("localhost", "root", "", "expensetracker");
            if($mysqli->connect_errno)
            {
                echo "MySQL Error: " . $mysqli->connect_error . "<BR/>";
            }
            $query = sprintf("SELECT T.TransactionDate,C.CategoryName, T.TransactionDescription, T.TransactionValue FROM transaction T JOIN category C ON T.CategoryID=C.CategoryID WHERE TransactionOwnerID='%d' and TransactionDate BETWEEN '%s' and '%s' ORDER BY TransactionDate;",$_SESSION['UserID'], $_POST['date_min'], $_POST['date_max']);
            $mysqli->real_query($query);
            $result = $mysqli->use_result();
            printf("<BR/><div class=\"table\"><TABLE>");
            printf("<TR><TH>Date</TH><TH>Category</TH><TH>Description</TH><TH>Value</TH></TR>");
            while($row = $result->fetch_row())
            {
                printf("<TR><TD>$row[0]</TD><TD>$row[1]</TD><TD>$row[2]</TD><TD>$row[3]</TD></TR>");
            }
            printf("</TABLE></div>");
            echo('</div>');
        }
    ?>
    </div>
    <?php
        include 'footer.php';
    ?>
</body>
</html>