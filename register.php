<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        require 'head.php';
    ?>
    <title>ExpenseTracker - Register</title>
</head>
<body>
    <?php
        require 'menu.php';
    ?>  
    <div class="content">
    <?php
        
            $userMsg = $mailMsg = $pswdMsg ="";
            $UserName = $Email = $Pswd = $Pswd_again ="";
            $submitted = 0; /*to check if really submitted*/
            $ok = 1;    /*to check validity*/
            $sysMsg = "";
            /**Input field checks*/
            if($_SERVER['REQUEST_METHOD'] =="POST" and isset($_POST['register']))
            {
                $submitted = 1;
                if(empty( $_POST[ 'user' ] ))
                {
                    $userMsg = " User Name is required!";
                    $ok = 0;
                }
                else
                {
                    $UserName = test_input($_POST['user']);
                }

                if(empty( $_POST[ 'email' ] ))
                {
                    $mailMsg = " E-mail is required!";
                    $ok = 0;
                }
                else
                {
                    $Email = test_input($_POST['email']);
                }

                if(empty( $_POST[ 'password' ] ) or empty( $_POST[ 'password_again' ] ))
                {
                    $pswdMsg = " Password is required!";
                    $ok = 0;
                }
                elseif( $_POST[ 'password' ]!=$_POST[ 'password_again' ])
                {
                    $pswdMsg = " Passwords do not match!";
                    $ok = 0;
                }
                else
                {
                    $Pswd = test_input($_POST['password']);
                }
            }

            if($submitted and $ok)
            {
                $submitted = 0;
                /**Insert new user*/
                $mysqli = new mysqli("localhost", "root", "", "expensetracker");
                if($mysqli->connect_errno)
                {
                    echo "MySQL Error: " . $mysqli->connect_error . "<BR/>";
                }
                $query = sprintf("INSERT INTO user(UserName, Email, Pswd) VALUES('%s','%s','%s');",
                                $UserName, $Email, $Pswd);
                $mysqli->query($query);
                $mysqli->close();

                /**Get UserID*/
                $getuser_num = new mysqli("localhost", "root", "", "expensetracker");
                if($getuser_num->connect_errno)
                {
                    echo "MySQL Error: " . $getuser_num->connect_error . "<BR/>";
                }
                $userNum = sprintf("SELECT MAX(UserID) FROM user");
                $getuser_num->real_query($userNum);
                $usernum_res = $getuser_num->use_result();
                $num_res=$usernum_res->fetch_row();

                /**Create default table for the user for the .csv import*/
                $importcol = new mysqli("localhost", "root", "", "expensetracker");
                if($importcol->connect_errno)
                {
                    echo "MySQL Error: " . $importcol->connect_error . "<BR/>";
                }
                $import_query = sprintf("INSERT INTO importcolumns(DateCol, DescCol, ValCol, UserID) VALUES (0,1,2, '%d');",$num_res[0]);
                $getuser_num->close();
                $importcol->query($import_query);
                $importcol->close();

                $sysMsg = "<span class=\"success\">Registration process was succesful!</span><BR/><BR/>";
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

                <label for="user_">User Name</label> <span class="error"><?php echo $userMsg ?></span>
                <input id="user_" type="text" name=user placeholder="User name">

                <label for="pswd">Password</label> <span class="error"><?php echo $pswdMsg ?></span>
                <input id="pswd" type="password" name=password  placeholder="Password">

                <label for="pswd_again">Password again</label>
                <input id="pswd_again" type="password" name=password_again  placeholder="Password again">

                <label for="mail" style=>E-mail address</label><span class="error"><?php echo $mailMsg ?></span>
                <input id="mail" type="email" name=email placeholder="E-mail address">

                <input type="submit" name=register>   
        </form> 
        </div>
    <?php
        include 'footer.php';
    ?>
</body>
</html>