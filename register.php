<!DOCTYPE html>
<html>
    <?php
        require 'head.php';
    ?>
<body>
    <?php
        require 'menu.php';
    ?>  
    <?php
        
            $userMsg = $mailMsg = $pswdMsg ="";
            $UserName = $Email = $Pswd = $Pswd_again ="";
            $submitted = 0; /*to check if really submitted*/
            $ok = 1;    /*to check validity*/
            $sysMsg = "";
            if($_SERVER['REQUEST_METHOD'] =="POST")
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

                if(empty( $_POST[ 'password' ] ) or empty( $_POST[ 'password' ] ))
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
                /*$UserName = test_input($_POST['user']);
                $Email = test_input($_POST['email']);
                $Pswd = test_input($_POST['password']);
                $Pswd_again = test_input($_POST['password_again']);*/
                $mysqli = new mysqli("localhost", "root", "", "expensetracker");
                if($mysqli->connect_errno)
                {
                    echo "MySQL Error: " . $mysqli->connect_error . "<BR/>";
                }
                $query = sprintf("INSERT INTO user(UserName, Email, Pswd) VALUES('%s','%s','%s')",
                                $UserName, $Email, $Pswd);
                $mysqli->query($query);
                $mysqli->close();
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

                <input type="submit">

               
        </form> 
    <div class="footer">
        &#9400; Patrik Reizinger 	 2017
    </div>
</body>
</html>