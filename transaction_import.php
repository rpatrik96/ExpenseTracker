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
        $sysMsg = "";
        $ok = 0;
        $csv_ok = 1;
        if($_SERVER['REQUEST_METHOD'] =="POST")
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

    <form method="POST" class="form" enctype="multipart/form-data">
            <?php
                echo $sysMsg;
            ?>
            <div style="text-align: left"><label for="up" style="text-align: left; font-family: Helvetica, sans-serif; font-weight: 700;font-variant: small-caps" >Upload CSV</label>
            <input id="up" type="file" name=file4upload>
            <input type="submit">
             </div>
    </form> 
    <?php
        if($ok and $csv_ok)
        {
            printf("<div class=\"table\"><TABLE>");
                for ($i=0; $i < $count_row ; $i++) 
                { 
                    if($i==0)
                    {
                        printf("<TH>");
                    }
                    else
                    {
                        printf("<TR>");
                    }
                    for ($j=0; $j < $count_column ; $j++) 
                    { 
                        printf("<TD>%s</TD>",$csv[$i][$j]);
                    }
                    if($i==0)
                    {
                        printf("</TH>");
                    }
                    else
                    {
                        printf("</TR>");
                    }
                }
                printf("</TABLE></div>");
        }
    ?>
    </div>
    <?php
        include 'footer.php';
    ?>
    <?php
        if(!$csv_ok)
        {
            unlink($file);
            die();
        }
    ?>
</body>
</html>