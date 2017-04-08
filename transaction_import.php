<?php
    /**
    *@file transaction_import.php
    *@author Patrik Reizinger
    *@brief
    *Import .csv.
    */
    session_start();
    if(!$_SESSION['logged_in'])
    {
        header("Location: http://localhost:8090/ExpenseTracker/index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        require 'head.php';
    ?>
    <title>ExpenseTracker - Import Transaction</title>
</head>
<body>
    <?php
        require 'menu.php';
    ?>  
    <div class="content">
    <?php
        $sysMsg = "";
        $ok = 0;
        $csv_ok = 1;
        /*Set columns for .csv import*/
        if($_SERVER['REQUEST_METHOD'] =="POST" and isset($_POST['setcolumns']))
        {
            $insert_col = new mysqli("localhost", "root", "", "expensetracker");
            if($insert_col->connect_errno)
            {
                echo "MySQL Error: " . $insert_col->connect_error . "<BR/>";
            }
            $insert_query = sprintf("UPDATE importcolumns SET DateCol='%d', DescCol='%d', ValCol='%d' WHERE UserID=%d;", max(abs($_POST['date'])-1, 0), max(abs($_POST['des'])-1,0), max(abs($_POST['val'])-1,0), $_SESSION['UserID']);
            $insert_col->query($insert_query);
            $insert_col->close();
        }

        /**@brief Get columns for the form*/
        $getcol = new mysqli("localhost", "root", "", "expensetracker");
        if($getcol->connect_errno)
        {
            echo "MySQL Error: " . $getcol->connect_error . "<BR/>";
        }
        $col_query = sprintf("SELECT DateCol, DescCol, ValCol FROM importcolumns WHERE UserID=%d", $_SESSION['UserID']);
        $getcol->real_query($col_query);
        $col_res = $getcol->use_result();
        $col_row = $col_res->fetch_row();
        $dateCol = $col_row[0];
        $descCol = $col_row[1];
        $valCol = $col_row[2];
        $getcol->close();

        /*Import .csv - file upload*/
        if($_SERVER['REQUEST_METHOD'] =="POST" and isset($_POST['import']))
        {
            $ok = 1;
            $target_directory = "uploads/";
            $file = $target_directory.basename($_FILES["file4upload"]["name"]);
            $file_type = pathinfo($file, PATHINFO_EXTENSION);

            /*if(file_exists($file))
            {
                $sysMsg = "<span class=\"error\">File already exists.</span><BR/><BR/>";
                $ok = 0;
            }*/

            if($file_type != "csv")
            {
                $sysMsg = "<span class=\"error\">The uploaded file is not a CSV file.</span><BR/><BR/>";
                $ok = 0;
            }

            if(!filesize($_FILES["file4upload"]["tmp_name"]))
            {
                $sysMsg = "<span class=\"error\">The selected file was empty, upload incomplete.</span><BR/><BR/>";
                $ok = 0;
            }

            if($ok)
            {
                move_uploaded_file($_FILES["file4upload"]["tmp_name"], $file);
                $sysMsg = "<span class=\"success\">".basename( $_FILES["file4upload"]["name"]). " was successfully uploaded.</span><BR/><BR/>";
            }
        }
    ?>
    
    <?php
        if($ok)
        {   
            /**@brief Extract data*/
            $csv = array_map('str_getcsv', file($file));
            $csv_other = base64_encode($file);
            $count_row = count($csv);
            $count_column = count($csv[0]);
            /*Check if ther is not a comma in the cells = the number of columns is not equal in the whole document*/
            for($i=1; $i < $count_row ; $i++)
            {
                    if( $count_column != count($csv[$i]))
                    {
                        $sysMsg = sprintf("<span class=\"error\">Row $i seems to have a different number of columns, it may contain a (comma) in one of its cells, please check it and try to upload the file again.</span><BR/><BR/>");
                        $csv_ok = 0;
                    }
            }
        }
    ?>

    <form method="POST" class="form" >
            <div style="text-align: left"><label  style="text-align: left; font-family: Helvetica, sans-serif; font-weight: 700;font-variant: small-caps" >CSV column settings</label> <BR/><BR/><BR/>
            <label for="date">Date column</label>
            <input id="date" type="text" name=date value=<?php echo $dateCol+1; ?>>
            <label for="val">Value column</label>
            <input id="val" type="text" name=val value=<?php echo $valCol+1; ?>>
            <label for="des">Description column</label>
            <input id="des" type="text" name=des value=<?php echo $descCol+1; ?>>
            <input type="submit" name=setcolumns>
             </div>
    </form> 
    <BR/><BR/><BR/>
    <form method="POST" class="form" enctype="multipart/form-data">
            <?php
                echo $sysMsg;
            ?>
            <div style="text-align: left"><label for="up" style="text-align: left; font-family: Helvetica, sans-serif; font-weight: 700;font-variant: small-caps" >Upload CSV</label><BR/><BR/>
            <input id="up" type="file" name=file4upload><BR/>
            <input type="submit" name=import>
             </div>
    </form> 
    <?php
        if($ok and $csv_ok)
        {   
            /**@brief Query for drop-down list*/
            $mysqli = new mysqli("localhost", "root", "", "expensetracker");
            if($mysqli->connect_errno)
            {
                echo "MySQL Error: " . $mysqli->connect_error . "<BR/>";
            }
            
            $mysqli->real_query("SELECT CategoryID, CategoryName FROM category ORDER BY CategoryName");
            $result = $mysqli->use_result();
            $list = "";
            while ($row = $result->fetch_row()) 
            {
                $tmp = sprintf("<option value=$row[0]>$row[1]</option>");
                $list = $list.$tmp;
            }
            $list = $list."</select>";
            $mysqli->close();

            /**@brief Duplicate check and auto insert*/         
            $duplicate_exist = 0;
            $automatically_inserted = 0;
            printf("<BR/><BR/><BR/><form  method=\"POST\" class=\"form\"><div class=\"table\"><TABLE>");
            for ($i=0; $i < $count_row ; $i++) 
            { 
                /*Neglect header row*/
                if ($i)
                {
                    /**@brief Get category-description pairs for a specific user*/
                    $category = new mysqli("localhost", "root", "", "expensetracker");
                    if($category->connect_errno)
                    {
                        echo "MySQL Error: " . $category->connect_error . "<BR/>";
                    }
                    $category_query = sprintf("SELECT CategoryID, Description FROM description WHERE UserID=%d", $_SESSION['UserID']);
                    $category->real_query($category_query);
                    $cat_result = $category->use_result();

                    while($cat_row = $cat_result->fetch_row())
                    {
                        /**@brief For each row make a query with read data to identify duplicates*/
                        $duplicate_check = new mysqli("localhost", "root", "", "expensetracker");
                        if($duplicate_check->connect_errno)
                        {
                            echo "MySQL Error: " . $duplicate_check->connect_error . "<BR/>";
                        }
                        $duplicate_query = sprintf("SELECT * FROM transaction WHERE TransactionDate='%s' and TransactionDescription='%s' and TransactionValue='%d' and TransactionOwnerID='%d';",
                                            $csv[$i][$dateCol], strtoupper(str_replace(array('\'', '"'), "",$csv[$i][$descCol])), abs($csv[$i][$valCol]), $_SESSION['UserID']);
                        $duplicate_check->real_query($duplicate_query);
                        $dupl_result = $duplicate_check->use_result();
                        if(!$dupl_result->fetch_row())
                        {
                            if(strpos(strtoupper(str_replace(array('\'', '"'), "",$csv[$i][$descCol])), $cat_row[1] ) !== false)
                            {
                                /**@brief Make auto-insert*/
                                $auto_insert = new mysqli("localhost", "root", "", "expensetracker");
                                if($auto_insert->connect_errno)
                                {
                                    echo "MySQL Error: " . $auto_insert->connect_error . "<BR/>";
                                }
                                $auto_query = sprintf("INSERT INTO transaction(TransactionDate, TransactionDescription, TransactionValue, CategoryID,TransactionOwnerID) VALUES('%s', '%s', '%d', '%d','%d')",
                                    $csv[$i][$dateCol], strtoupper(str_replace(array('\'', '"'), "",$csv[$i][$descCol])), abs($csv[$i][$valCol]), $cat_row[0] , $_SESSION['UserID']);
                                $auto_insert->query($auto_query);
                                $auto_insert->close();
                                $automatically_inserted=1;
                            }
                        }
                        else
                        {
                            $duplicate_exist = 1;
                        }
                        $duplicate_check->close();
                    }
                     $category->close();
                }
                /*If not a duplicate and cannot be inserted automatically, create a table with the entry*/
                if(!$automatically_inserted and !$duplicate_exist)
                {
                    printf("<TR>");
                    for ($j=0; $j < $count_column ; $j++) 
                    { 
                        if($j==$valCol or $j==$descCol or $j==$dateCol)
                        {
                            if(!$i)
                            {
                                printf("<TH>%s</TH>",$csv[$i][$j]);
                            }
                            else
                            {
                                printf("<TD>%s</TD>",$csv[$i][$j]);
                            }
                        }
                    }
                    if(!$i)
                    {
                        printf("<TH style=\"width:150px\">Category</TH>");
                    }
                    else
                    {
                        $temp = "<select name=category".$i.">".$list;
                        printf("<TD>%s</TD>",$temp);
                    }
                    printf("</TR>");
                }
                $automatically_inserted = 0;
                $duplicate_exist = 0;
            }
                printf("</TABLE></div>");
                $text_tmp = "<input type=\"hidden\" name=csv_hidden value=".$csv_other.">";
                printf($text_tmp);
                printf("<input type=\"submit\" name=add></form>");
        }
        /*Handle manual insert*/
        if($_SERVER['REQUEST_METHOD'] =="POST" and isset($_POST['add']))
        {
            /*Hidden form entry cell*/
            $csv = array_map('str_getcsv',file(base64_decode($_POST['csv_hidden'])));
            for($k=0; $k<count($csv); $k++)
            {
                $catName = "category".$k;
                if(isset($_POST[$catName]))
                {
                    $manual_insert = new mysqli("localhost", "root", "", "expensetracker");
                    if($manual_insert->connect_errno)
                    {
                        echo "MySQL Error: " . $manual_insert->connect_error . "<BR/>";
                    }
                    $manual_query = sprintf("INSERT INTO transaction(TransactionDate, TransactionDescription, TransactionValue, CategoryID,TransactionOwnerID) VALUES('%s', '%s', '%d', '%d','%d')",
                        $csv[$k][$dateCol], strtoupper(str_replace(array('\'', '"'), "",$csv[$k][$descCol])), abs($csv[$k][$valCol]), $_POST[$catName] , $_SESSION['UserID']);
                    $manual_insert->query($manual_query);
                    $manual_insert->close();
                }
            }
            $sysMsg =  "<span class=\"success\">Import was successful!</span><BR/><BR/>";
            echo $sysMsg;
        }
    ?>
    </div>
    <?php
        include 'footer.php';
    ?>
    <?php
        /*Delete .csv if column numbers not match*/
        if(!$csv_ok)
        {
            unlink($file);
            die();
        }
    ?>
</body>
</html>