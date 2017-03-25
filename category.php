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
                <form method="POST" class="form">

                <?php
                    echo $sysMsg;
                ?>

                
                <?php
                    $descMsg = "";
                    $mysqli = new mysqli("localhost", "root", "", "expensetracker");
                    $mysqli2 = new mysqli("localhost", "root", "", "expensetracker");
                    $get_category = new mysqli("localhost", "root", "", "expensetracker");
                    $delete_category = new mysqli("localhost", "root", "", "expensetracker");
                    $insert = new mysqli("localhost", "root", "", "expensetracker");
                    $help = new mysqli("localhost", "root", "", "expensetracker");

                    if($mysqli->connect_errno)
                    {
                        echo "MySQL Error: " . $mysqli->connect_error . "<BR/>";
                    }
                    $mysqli2->real_query("SELECT CategoryID, CategoryName FROM category ORDER BY CategoryName");
                    $result2 = $mysqli2->use_result();

                    echo('<label for="desc">Description</label>
                    <input id="desc" type="text" name=desc placeholder="Description">
                    <label for="category">Category</label>');
                    printf("<select name=category>");
                    while ($row2 = $result2->fetch_row()) 
                    {
                        printf("<option value=$row2[0]>$row2[1]</option>");
                    }
                    printf("</select>");
                    echo('<input type="submit" name=add value=Add><BR/><BR/><BR/><BR/>');

                    $help_query = sprintf("SELECT DescriptionID FROM description WHERE UserID=%d",$_SESSION['UserID']);
                    $help->real_query($help_query);
                    $help_res = $help->use_result();
                    if($_SERVER['REQUEST_METHOD'] =="POST")
                    {
                        while($help_row = $help_res->fetch_row())
                        {
                            if(isset($_POST[$help_row[0]]))
                            {
                                $del_query = sprintf("DELETE FROM Description WHERE DescriptionID=%d",$help_row[0]);
                                $delete_category->query($del_query);
                            }
                        }
                    }

                    if($_SERVER['REQUEST_METHOD'] =="POST")
                    {
                        if (isset($_POST['add']))
                            {
                                if (!empty($_POST['desc']))
                                {
                                    $insert = new mysqli("localhost", "root", "", "expensetracker");
                                    if($insert->connect_errno)
                                    {
                                        echo "MySQL Error: " . $insert->connect_error . "<BR/>";
                                    }
                                    $insert_query = sprintf("INSERT INTO Description(UserID, CategoryID, Description) VALUES(%d, %d, '%s')", $_SESSION['UserID'],$_POST['category'],strtoupper(test_input($_POST['desc'])));
                                    $insert->query($insert_query);
                                    $insert->close();
                                    $descMsg = "<span class=\"success\">Action was successful!</span><BR/><BR/><BR/><BR/>";
                                     echo $descMsg;
                                }
                                else
                                {
                                    $descMsg = "<span class=\"error\">Description is needed!</span><BR/><BR/><BR/><BR/>";
                                    echo $descMsg;
                                }
                            }
                    }

                    $mysqli->real_query("SELECT CategoryID, CategoryName FROM category ORDER BY CategoryName");
                    $result = $mysqli->use_result();

                    
                    while ($row = $result->fetch_row()) 
                    {
                        
                        $query = sprintf("SELECT Description, DescriptionID FROM description WHERE CategoryID=%d AND UserID=%d",$row[0],$_SESSION['UserID']);
                        $get_category->real_query($query);
                        $cat = $get_category->use_result();
                        printf("<label for=$row[1]>$row[1]</label><BR/><BR/>");
                        printf("<div class=\"table\"><TABLE>");
                        printf("<TR><TH>Description</TH><TH>Action</TH></TR>");
                        //printf("<textarea id=$row[1] rows=\"10\" cols=\"40\" value=$row[0] name=$row[1]>");
                        while($cat_row = $cat->fetch_row())
                        {
                            printf("<TR><TD>$cat_row[0]</TD><TD><input name=$cat_row[1] value=Delete type=\"submit\"></TD></TR>");
                        }
                        //printf("</textarea><BR/><BR/><BR/>");
                        printf("</TABLE></div><BR/><BR/><BR/>");
                    }
                    

                    $mysqli->close();
                    $mysqli2->close();
                    $get_category->close();
                    $delete_category->close();
                    $help->close();
                    

                     /*Test function for security*/
                    function test_input($data)
                    {
                        $data = trim($data);
                        $data = stripslashes($data);
                        $data = htmlspecialchars($data);
                        return $data;
                    }
                    
                ?>
                <BR/>               
        </form> 
    </div>
    <?php
        
    ?>
    <?php
        include 'footer.php';
    ?>
</body>
</html>