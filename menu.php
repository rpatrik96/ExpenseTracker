 <?php
    echo ('
    <ul class="navbar">
        <li> <a class="active" href="index.php">Home</a> </li>
        <li class="dropdown"> <a class="dropbtn">Transactions</a>
            <div class="dropdown-content"> 
                <a href="transaction_add.php">Add</a>
                <a href="#">Import</a>
                <a href="#">Edit</a>
            </div>
        </li>
        <li class="dropdown"> <a  href="#" class="dropbtn">Categories</a>
            <!--<div class="dropdown-content"> 
                <a href="#">Import</a>
                <a href="#">Edit</a>
            </div> -->
        </li>
        <li class="right"> <a href="#About">About</a> </li>
        <li class="right dropdown"> <a class=" dropbtn">Login</a> 
            <form method="GET" class="dropdown-form">
                    <label for="user_">User Name</label>
                    <input id="user_" type="text" name=user placeholder="User name">
                    <label for="pswd">Password</label>
                    <input type="password" name=password  placeholder="Password">
                    <input type="submit">
                    <div class="note">
                        New to ExpenseTracker? <a href="register.php" style="background-color: #f2f2f2">Register</a>
                    </div>
            </form> 
        <li class="right dropdown"> <a href="register.php">Register</a>    
        </li>
    </ul>
    <div class="header">
       <div class="bottomright"> Let the ca$h flow! </div>
    </div>
    ') ;
    ?>