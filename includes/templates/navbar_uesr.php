

<header class="header">

    <div class="menu-icon" >
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg>
    </div>




    <div class="navbar">
        <!-- user   -->       
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">  <?php echo $_SESSION['username']?> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="profile_page.php?action=edit&id=<?php echo $_SESSION['id']?>">Edit Profile</a></li>
                    <li><a href="logout.php"> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>  
</header>