<header id="navbar" class="d-flex justify-content-between">




    <!-- Logo --------------------------------------------------------------------------------------------------------------------------------------->
    <a id="logo" href="http://www.01system.ir">
        <img src="includes/images/2.png">
    </a>



    <!-- Nav Links ---------------------------------------------------------------------------------------------------------------------------------->
    <ul id="nav" class="d-none d-lg-flex">
        <li>
            <a class="btn1" href="./index.php"><i class="fa fa-home"></i>صفحه اصلی</a>
        </li>
        <li>
            <a class="btn1" href="./index.php#order-sequences"><i class="fa fa-handshake-o"></i>روند سفارش</a>
        </li>
        <li>
            <a class="btn1" href="./index.php#works"><i class="fa fa-sitemap"></i>زمینه های کاری</a>
        </li>
        <li>
            <a class="btn1" href="./index.php#skills"><i class="fa fa-code"></i>مهارت ها</a>
        </li>
        <li>
            <a class="btn1" href="./index.php#last-posts"><i class="fa fa-bold"></i>وبلاگ</a>
        </li>
        <li>
            <a class="btn1" href="./index.php#last-works"><i class="fa fa-magic"></i>نمونه کارها</a>
        </li>
    </ul>



    <!-- Login Btns --------------------------------------------------------------------------------------------------------------------------------->
    <div id="login-state">
        <?php
            if (isset($_SESSION['u_name']))
                echo "<a id='btn-profile' class='btn-sign-in' href='management_system/cpanel.php'><i class='fa fa-user-circle-o'></i>".$_SESSION["u_name"]."</a>
                      <a id='btn-sign-out' class='btn-sign-out'><i class='fa fa-sign-out'></i>خروج</a>";
            else
                echo "<a id='btn-signin' class='btn-sign-in'><i class='fa fa-lock'></i>ورود</a>
                      <a id='btn-signup' class='btn-sign-up'><i class='fa fa-sign-in'></i>ثبت نام</a>";
        ?>
    </div>



    <!-- Right Menu --------------------------------------------------------------------------------------------------------------------------------->
    <div id="right-menu" class="d-flex justify-content-start flex-column">


        <!-- logo -->
        <a class="mx-auto mb-3" href="http://www.01system.ir">
            <img src="includes/images/2.png">
        </a>
        <div class="hr-2"></div>


        <!-- login btns -->
        <div class="my-2 mb-3 mx-auto">
            <?php
            if (isset($_SESSION['u_name']))
                echo "<a id='btn-profile'  class='btn-user text-dark' href='management_system/cpanel.php'><i class='fa fa-user-circle-o'></i>".$_SESSION["u_name"]."</a>
                      <a id='btn-sign-out' class='btn-sign-out'><i class='fa fa-sign-out'></i>خروج</a>";
            else
                echo "<a id='btn-signin'   class='btn-sign-in'><i class='fa fa-lock'></i>ورود</a>
                      <a id='btn-signup'   class='btn-sign-up text-dark'><i class='fa fa-sign-in'></i>ثبت نام</a>";
            ?>
        </div>
        <div class="hr-2"></div>


        <!-- nav links -->
        <ul id="right-nav" class="pr-2">
            <li>
                <a class="btn1" href="./index.php"><i class="fa fa-home"></i>صفحه اصلی</a>
            </li>
            <li>
                <a class="btn1" href="./index.php#order-sequences"><i class="fa fa-handshake-o"></i>روند سفارش</a>
            </li>
            <li>
                <a class="btn1" href="./index.php#works"><i class="fa fa-sitemap"></i>زمینه های کاری</a>
            </li>
            <li>
                <a class="btn1" href="./index.php#skills"><i class="fa fa-code"></i>مهارت ها</a>
            </li>
            <li>
                <a class="btn1" href="./index.php#last-posts"><i class="fa fa-bold"></i>وبلاگ</a>
            </li>
            <li>
                <a class="btn1" href="./index.php#last-works"><i class="fa fa-magic"></i>نمونه کارها</a>
            </li>
        </ul>


    </div>



    <!-- Right menu Toggler ------------------------------------------------------------------------------------------------------------------------->
    <div id="right-menu-toggler" class="navbar-toggler d-block d-lg-none">
        <i id="right-menu-toggler-i" class="fa fa-bars"></i>
    </div>




</header>
