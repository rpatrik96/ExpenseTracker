<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        require 'head.php';
    ?>
    <title>ExpenseTracker - Add Transaction</title>
</head>
<body>
    <?php
        require 'menu.php';
    ?> 
    <div class="content">
    <?php
        $valMsg = $dateMsg = $catMsg = "";
        /*OwnerID can not be set yet*/
        $TransactionDate = $TransactionDescription = $TransactionValue = $CategoryID = $TransactionOwnerID = "";
         $ok = 1;    /*to check validity*/
         $submitted = 0;
        $sysMsg = "";
        if($_SERVER['REQUEST_METHOD'] =="POST" and isset($_POST['add']))
        {
            $submitted = 1;
            /**Input checks*/
            if(empty( $_POST[ 'value' ] ))
            {
                $valMsg = " Value is required!";
                $ok = 0;
            }
            else if(!is_numeric($_POST[ 'value' ] ))
            {
                $valMsg = " Value should be a number!";
                $ok = 0;
            }
            else
            {
                $TransactionValue = abs(test_input($_POST['value']));
            }

            if(empty( $_POST[ 'category' ] ))
            {
                $catMsg = " Category is required!";
                $ok = 0;
            }
            else
            {
                $CategoryID = test_input($_POST['category']);
            }
            if(empty( $_POST[ 'date' ] ))
            {
                $dateMsg = " Date is required!";
                $ok = 0;
            }
            else
            {
                $TransactionDate = test_input($_POST['date']);
            }
            
            $TransactionDescription = test_input($_POST['description']);
        }

        /**Insert new transaction*/
        if($submitted and $ok)
            {
                $submitted = 0;
                $mysqli = new mysqli("localhost", "root", "", "expensetracker");
                if($mysqli->connect_errno)
                {
                    echo "MySQL Error: " . $mysqli->connect_error . "<BR/>";
                }
                $query = sprintf("INSERT INTO transaction(TransactionDate, TransactionDescription, TransactionValue, CategoryID,TransactionOwnerID) VALUES('%s', '%s', '%d', '%d','%d')",
                                    $TransactionDate, $TransactionDescription, $TransactionValue, $CategoryID, $_SESSION['UserID']);
                $mysqli->query($query);
                $mysqli->close();
                $sysMsg = "<span class=\"success\">Transaction was added successfully!</span><BR/><BR/>";
            }

        /*Test function for security*/
        function test_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
    ?>
        <form method="POST" class="form">

                <?php
                    echo $sysMsg;
                ?>

                <label for="val">Value</label> <span class="error"><?php echo $valMsg ?></span>
                <input id="val" type="text" name=value placeholder="Value">

                <label for="cat">Category</label> <span class="error"><?php echo $catMsg ?></span><BR/><BR/>
                        <?php
                            /**Query for the radio buttons*/
                            $mysqli = new mysqli("localhost", "root", "", "expensetracker");
                            if($mysqli->connect_errno)
                            {
                                echo "MySQL Error: " . $mysqli->connect_error . "<BR/>";
                            }
                            $mysqli->real_query("SELECT CategoryID, CategoryName FROM category ORDER BY CategoryName");
                            $result = $mysqli->use_result();

                            while ($row = $result->fetch_row()) 
                            {
                               printf("<input type=\"radio\" name=category value=$row[0]>$row[1]<BR/>");
                            }
                            $mysqli->close();
                        ?>
                <BR/>
                <label for="date" >Date</label> 
                <input id="date" type="date" name=date value="<?php echo date('Y-m-d'); ?>"> <span class="error"><?php echo $dateMsg ?></span>
                <BR/><BR/>
                <label for="desc" >Description</label>
                <input id="desc" type="text" name=description placeholder="Description">

                <input type="submit" name=add>

               
        </form> 
        </div>
    <?php
        include 'footer.php';
    ?>
</body>
</html>