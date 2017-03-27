<?php
    session_start();
    if(!$_SESSION['logged_in'])
    {
	header("Location: http://localhost:8080/ExpenseTracker/index.php");
	exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        require 'head.php';
    ?>
    <title>ExpenseTracker - Edit Category</title>
</head>
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
                    /**List existing categories for the dropdown list*/
                    $mysqli2 = new mysqli("localhost", "root", "", "expensetracker");
                    if($mysqli2->connect_errno)
                    {
                        echo "MySQL Error: " . $mysqli2->connect_error . "<BR/>";
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
                    $mysqli2->close();
                    /**Handle deletes */
                    $help = new mysqli("localhost", "root", "", "expensetracker");
                    if($help->connect_errno)
                    {
                        echo "MySQL Error: " . $help->connect_error . "<BR/>";
                    }
                    $help_query = sprintf("SELECT DescriptionID FROM description WHERE UserID=%d",$_SESSION['UserID']);
                    $help->real_query($help_query);
                    $help_res = $help->use_result();
                    if($_SERVER['REQUEST_METHOD'] =="POST")
                    {
                        while($help_row = $help_res->fetch_row())
                        {
                            if(isset($_POST[$help_row[0]])) /**DescriptionID's are the identificators for the delete buttons*/
                            {
                                $delete_category = new mysqli("localhost", "root", "", "expensetracker");
                                if($delete_category->connect_errno)
                                {
                                    echo "MySQL Error: " . $delete_category->connect_error . "<BR/>";
                                }
                                $del_query = sprintf("DELETE FROM Description WHERE DescriptionID=%d",$help_row[0]);
                                $delete_category->query($del_query);
                                 $delete_category->close();
                            }
                        }
                    }
                    $help->close();

                    /**Handle category add*/
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

                    /**Create tables for the category-specific descriptions*/
                    /**Query for categories*/
                    $mysqli = new mysqli("localhost", "root", "", "expensetracker");
                    if($mysqli->connect_errno)
                    {
                        echo "MySQL Error: " . $mysqli->connect_error . "<BR/>";
                    }
                    $mysqli->real_query("SELECT CategoryID, CategoryName FROM category ORDER BY CategoryName");
                    $result = $mysqli->use_result();
                    while ($row = $result->fetch_row()) 
                    {   
                        /**Query for the descriptions of each category*/
                        $get_category = new mysqli("localhost", "root", "", "expensetracker");
                        if($get_category->connect_errno)
                        {
                            echo "MySQL Error: " . $get_category->connect_error . "<BR/>";
                        }
                        $query = sprintf("SELECT Description, DescriptionID FROM description WHERE CategoryID=%d AND UserID=%d",$row[0],$_SESSION['UserID']);
                        $get_category->real_query($query);
                        $cat = $get_category->use_result();
                        printf("<label for=$row[1]>$row[1]</label><BR/><BR/>");
                        printf("<div class=\"table\"><TABLE>");
                        printf("<TR><TH>Description</TH><TH>Action</TH></TR>");
                        while($cat_row = $cat->fetch_row())
                        {
                            printf("<TR><TD>$cat_row[0]</TD><TD><input name=$cat_row[1] value=Delete type=\"submit\"></TD></TR>");
                        }
                        printf("</TABLE></div><BR/><BR/><BR/>");
                        $get_category->close();
                    }
                    $mysqli->close();
                     /**Test function for security*/
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