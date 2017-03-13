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
        <li class="right"> <a href="#Login">Login</a> </li>
    </ul>
    <div class="header">
       <div class="bottomright"> Let the ca$h flow! </div>
    </div>
    ') ;
    ?>