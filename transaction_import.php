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
    <?php
        $dateCol = 0;
        $descCol = 1;
        $valCol = 6;
        $sysMsg = "";
        $ok = 0;
        $csv_ok = 1;
        if($_SERVER['REQUEST_METHOD'] =="POST" and isset($_POST['setcolumns']))
        {
            if(!empty($_POST['date']))
            {
                $dateCol = $_POST['date'];
            }
            if(!empty($_POST['val']))
            {
                $valCol = $_POST['val'];
            }
            if(!empty($_POST['des']))
            {
                $descCol = $_POST['des'];
            }
        }
        if($_SERVER['REQUEST_METHOD'] =="POST" and isset($_POST['import']))
        {
            $ok = 1;
            $target_directory = "uploads/";
            $file = $target_directory.basename($_FILES["file4upload"]["name"]);
            $file_type = pathinfo($file, PATHINFO_EXTENSION);

            if(file_exists($file))
            {
                $sysMsg = "<span class=\"error\">File already exists.</span><BR/><BR/>";
                $ok = 0;
            }

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
            $csv = array_map('str_getcsv', file($file));
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
            <input id="date" type="text" name=date value=<?php echo $dateCol; ?>>
            <label for="val">Value column</label>
            <input id="val" type="text" name=val value=<?php echo $valCol; ?>>
            <label for="des">Description column</label>
            <input id="des" type="text" name=des value=<?php echo $descCol; ?>>
            <input type="submit" name=setcolumns>
             </div>
    </form> 
    <BR/><BR/><BR/>
    <form method="POST" class="form" enctype="multipart/form-data">
            <?php
                echo $sysMsg;
            ?>
            <div style="text-align: left"><label for="up" style="text-align: left; font-family: Helvetica, sans-serif; font-weight: 700;font-variant: small-caps" >Upload CSV</label>
            <input id="up" type="file" name=file4upload>
            <input type="submit" name=import>
             </div>
    </form> 
    <?php
        if($ok and $csv_ok)
        {
            $mysqli = new mysqli("localhost", "root", "", "expensetracker");
            $insert = new mysqli("localhost", "root", "", "expensetracker");
            $desc = new mysqli("localhost", "root", "", "expensetracker");
            if($insert->connect_errno)
            {
                echo "MySQL Error: " . $insert->connect_error . "<BR/>";
            }
            if($mysqli->connect_errno)
            {
                echo "MySQL Error: " . $mysqli->connect_error . "<BR/>";
            }
            if($desc->connect_errno)
            {
                echo "MySQL Error: " . $desc->connect_error . "<BR/>";
            }
            $mysqli->real_query("SELECT CategoryID, CategoryName FROM category ORDER BY CategoryName");
            $result = $mysqli->use_result();
            $list ="<select name=category>";
            while ($row = $result->fetch_row()) 
            {
                $tmp = sprintf("<option value=$row[0]>$row[1]</option>");
                $list = $list.$tmp;
            }
            $list = $list."</select>";

            $desc_query = sprintf("SELECT Description, CategoryID FROM description WHERE UserID=%d", $_SESSION['UserID']);
            $desc->real_query($desc_query);
            $desc_res = $desc->use_result();
            /*$cat_array =sprintf("array(");
            

            while($d_row = $desc_res->fetch_row())
            {
                $cat_array = $cat_array."\"".$d_row[0]."\"=>\"".$d_row[1]."\"";
            }
            $cat_array = $cat_array."\")";
            chop($cat_array," ,");
            print_r($cat_array);*/

            $automatically_inserted = 0;
            printf("<div class=\"table\"><TABLE>");
                for ($i=0; $i < $count_row ; $i++) 
                { 
                    $category = new mysqli("localhost", "root", "", "expensetracker");
                    if($category->connect_errno)
                    {
                        echo "MySQL Error: " . $category->connect_error . "<BR/>";
                    }
                    if ($i)
                    {
                        /*AND Description LIKE '%s'*/
                        /*"%".strtolower($csv[$i][$descCol])."%"*/
                        $category_query = sprintf("SELECT CategoryID, Description FROM description WHERE UserID=%d", $_SESSION['UserID']);
                        $category->real_query($category_query);
                        $cat_result = $category->use_result();

                        while($cat_row = $cat_result->fetch_row())
                        {
                            if(strpos( strtolower($csv[$i][$descCol]), $cat_row[1] ) !== false)
                            {
                                $auto_insert = new mysqli("localhost", "root", "", "expensetracker");
                                if($auto_insert->connect_errno)
                                {
                                    echo "MySQL Error: " . $auto_insert->connect_error . "<BR/>";
                                }
                                $auto_query = sprintf("INSERT INTO transaction(TransactionDate, TransactionDescription, TransactionValue, CategoryID,TransactionOwnerID) VALUES('%s', '%s', '%d', '%d','%d')",
                                    $csv[$i][$dateCol], $csv[$i][$descCol], abs($csv[$i][$valCol]), $cat_row[0] , $_SESSION['UserID']);
                                $auto_insert->query($auto_query);
                                $auto_insert->close();
                                $automatically_inserted=1;
                            }
                        }

                    }
                    if(!$automatically_inserted)
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
                            printf("<TH style=\"width:70px\">Category</TH>");
                        }
                        else
                        {
                            printf("<TD>%s</TD>",$list);
                        }
                        if(!$i)
                        {
                            printf("<TH></TH>");
                        }
                        else
                        {
                            printf("<TD><input name=$j value=Add type=\"submit\"></TD>");
                        }
                        printf("</TR>");
                        $category->close();
                    }
                    $automatically_inserted = 0;
                }
                printf("</TABLE></div>");
                $mysqli->close();
                $insert->close();
                $desc->close();
        }
    ?>
    </div>
    <?php
        include 'footer.php';
    ?>
    <?php
    /*Restore comment, it is needed*/
        //if(!$csv_ok)
        {
            unlink($file);
            die();
        }
    ?>
</body>
</html>