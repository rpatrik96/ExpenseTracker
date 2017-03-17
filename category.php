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
                In the following textboxes You can specify items which will be used to order transactions to categories automatically.
                Please do not use one expression in more boxes and use a comma as a separator charakter.
                <form method="POST" class="form">

                <?php
                    echo $sysMsg;
                ?>

                
                <?php
                    $mysqli = new mysqli("localhost", "root", "", "expensetracker");
                    $get_category = new mysqli("localhost", "root", "", "expensetracker");
                    if($mysqli->connect_errno)
                    {
                        echo "MySQL Error: " . $mysqli->connect_error . "<BR/>";
                    }
                    $mysqli->real_query("SELECT CategoryID, CategoryName FROM category ORDER BY CategoryName");
                    $result = $mysqli->use_result();

                    
                    while ($row = $result->fetch_row()) 
                    {
                        $query = sprintf("SELECT Description FROM description WHERE CategoryID=%d AND UserID=%d",$row[0],$_SESSION['UserID']);
                        $get_category->real_query($query);
                        $cat = $get_category->use_result();
                        printf("<label for=$row[1]>$row[1]</label><BR/><BR/>");
                        printf("<textarea id=$row[1] rows=\"10\" cols=\"40\" value=$row[0] name=$row[1]>");
                        while($cat_row = $cat->fetch_row())
                        {
                            printf("$cat_row[0], ");
                        }
                        printf("</textarea><BR/><BR/><BR/>");
                    }
                    
                    $mysqli->close();
                    $get_category->close();
                ?>
                <BR/>

                <input type="submit">

               
        </form> 
    </div>
    <?php
        include 'footer.php';
    ?>
</body>
</html>