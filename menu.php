 <?php
    echo ('
    <ul class="navbar">
        <li> <a class="active" href="#Home">Home</a> </li>
        <li class="dropdown"> 
            <a href="#" class="dropbtn">Statistics</a>
            <div class="dropdown-content">
                <a href="#">Weekly</a>
                <a href="#">Monthly</a>
            </div>
        </li>
        <li class="dropdown"> <a  href="#" class="dropbtn">Import</a>
            <div class="dropdown-content"> 
                <a href="#">From file</a>
                <a href="#">From URL</a>
            </div>
        </li>
        <li class="right"> <a href="#About">About</a> </li>
        <li class="right dropdown"> <a href="#Login" class="dropbtn">Login</a> 
            <form method="GET">
                <div class="dropdown-form">
                    <label for="user_">User Name</label>
                    <input id="user_" type="text" name=user placeholder="User name">
                    <label for="pswd">Password</label>
                    <input type="password" name=password  placeholder="Password">
                    <input type="submit">
                </div>
            </form>    
        </li>
    </ul>
    <div class="header">
       <div class="bottomright"> Let the ca$h flow! </div>
    </div>
    ') ;
    ?>