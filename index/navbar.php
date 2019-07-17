<!-- Navbar -->



<header id="navbar">
    <a href="http://www.01system.ir">
        <div id="logo">
            <img src="includes/images/2.png" style="height: 30px;">
        </div>
    </a>
    <div id="right-menu-toggler">
        <i class="fa fa-bars"></i>
    </div>
    <nav id="nav">
        <div id="login-state">
            <?php
            if (isset($_SESSION['u_name'])){
                $u=$_SESSION['u_name'];
                echo "<span id='sign-in-btn-group'>
                         <a id='btn-profile' class='btn-sign-in' href='management_system/cpanel.php'>
                             <i class='fa fa-user-circle-o'></i> $u
                         </a>
                         <a id='btn-sign-out' class='btn-sign-out'>
                             <i class='fa fa-sign-out'></i> خروج
                         </a>
                      </span>";
            } else
                echo "<span id='sign-in-btn-group'>
                         <a id='btn-signin' class='btn-sign-in'>
                             <i class='fa fa-lock'></i> ورود
                         </a>
                         <a id='btn-signup' class='btn-sign-up'>
                             <i class='fa fa-sign-in'></i> ثبت نام
                         </a>
                      </span>";
            ?>
        </div>
        <ul class="list-unstyled">
            <li><a class="btn1" href="./index.php"><i class="fa fa-home"></i> صفحه اصلی </a></li>
            <li><a class="btn1" href="./index.php#order-sequences"><i class="fa fa-handshake-o"></i>روند سفارش</a></li>
            <li><a class="btn1" href="./index.php#works"><i class="fa fa-sitemap"></i>زمینه های کاری</a></li>
            <li><a class="btn1" href="./index.php#skills"><i class="fa fa-code"></i>مهارت ها</a></li>
            <li><a class="btn1" href="./index.php#last-posts"><i class="fa fa-bold"></i> وبلاگ </a></li>
        </ul>
    </nav>
</header>
