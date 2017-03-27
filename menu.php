 <?php
    $sysMsg = "";
    $row = "";
    /*First webpage entry -> variable not set*/
    if(!isset($_SESSION['logged_in']))
    {
        $_SESSION['logged_in'] = 0;
    }
    /**Login process*/
    if(!$_SESSION['logged_in'] and $_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['login']))
    {
        $_SESSION['logged_in'] = 0;
        $mysqli = new mysqli("localhost", "root", "", "expensetracker");
        if($mysqli->connect_errno)
        {
            echo "MySQL Error: " . $mysqli->connect_error . "<BR/>";
        }
        $query = sprintf("SELECT UserID, UserName, Pswd FROM user WHERE UserName='%s'",$_POST['user']);
        $mysqli->real_query($query);
        $result = $mysqli->use_result();
        $row = $result->fetch_row();
        if(!$row)
        {
            $sysMsg = "<span class=\"error\">User name is invalid!</span>";
        }
        elseif($_POST['user'] == $row[1] and sha1($_POST['password']) == $row[2])
        {
            $_SESSION['UserID'] = $row[0];
            $_SESSION['UserName'] = $row[1];
            $_SESSION['logged_in'] = 1;
            $submitted = 1;
        }
        else
        {
            $sysMsg = "<span class=\"error\">Password does not match!</span>";
        }
    }
    /**Print out the menu bar*/
    echo ('
    <ul class="navbar">
        <li> <a class="active" href="index.php">Home</a> </li>
        ');
        if(!$_SESSION['logged_in'])
        {
        echo('<li class="right"> <a href="#About">About</a> </li>
        <li class="right dropdown"> <a class=" dropbtn">Login</a> 
            <form method="POST" class="dropdown-form">');
                   printf(" <label for=\"user_\">User Name</label> $sysMsg
                    <input id=\"user_\" type=\"text\" name=user placeholder=\"User name\">
                    <label for=\"pswd\">Password</label>
                    <input type=\"password\" name=password  placeholder=\"Password\">
                    <input type=\"submit\" name=login>
                    <div class=\"note\">
                        New to ExpenseTracker? <a href=\"register.php\" style=\"background-color: #f2f2f2\">Register</a>
                    </div>
            </form> 
            <li class=\"right dropdown\"> <a href=\"register.php\">Register</a>    
        </li>");
        }
        else
        {
            echo('<li class="dropdown"> <a class="dropbtn">Transactions</a>
            <div class="dropdown-content"> 
                <a href="transaction_add.php">Add</a>
                <a href="transaction_import.php">Import</a>
                <a href="statistics.php">Statistics</a>
            </div>
        </li>
        <li class="dropdown"> <a  href="category.php" class="dropbtn">Categories</a>
            <!--<div class="dropdown-content"> 
                <a href="#">Import</a>
                <a href="#">Edit</a>
            </div> -->
        </li>');
            printf("<li class=\"right\"> <a href=\"#About\">About</a> </li>
                    <li class=\"right dropdown\"> <a class=\" dropbtn\">%s</a> 
                    <form action=\"logout.php\" class=\"dropdown-form\">
                        <input type=\"submit\" value=\"Logout\">
                    </form>
                        ",$_SESSION['UserName']);
        }
        echo('
    </ul>
    <div class="header">
       <div class="bottomright"> Let the ca$h flow! </div>
    </div>
    ') ;
    ?>