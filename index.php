<?php
/* show post or show home  * show page depends on 'post=x' parameter on query string */
require_once ("include_all.php");
session_start();
if (isset($_COOKIE['remember'])) {
    if ($user = User::getUserByName($_COOKIE['u_name'])) {
        if (isset($_COOKIE['hash'])) {
            $hash_1 = $_COOKIE['hash'];
            $hash_2 = User::getRandomHash($_COOKIE['u_name']);
            if ($hash_1 == $hash_2) {
                $_SESSION['u_id']    =  $user ->  id;
                $_SESSION['u_name']  =  $user ->  u_name;
                $_SESSION['u_type']  =  $user ->  u_type;
                $_SESSION['f_name']  =  $user ->  f_name;
                $_SESSION['l_name']  =  $user ->  l_name;
                $_SESSION['avatar']  =  $user ->  avatar;
                setcookie("remember",$user->u_name,time()+REMEMBER_TIME,null ,null ,null ,true);
            }
        }
    }
}
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>صفحه اصلی</title>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-139533490-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-139533490-1');
    </script>
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <link rel="stylesheet" href="styles/font-awesome.min.css">
    <link rel="stylesheet" href="styles/animate.css">
    <link rel="stylesheet" href="styles/owl.carousel.min.css" />
    <link rel="stylesheet" href="styles/css.css">
    <link rel="stylesheet" href="styles/rtl.css">
    <link rel="stylesheet" href="styles/devicon.min.css">
</head>
<body id="home">
<!--<div id="spinner"></div>-->
<div id="body" style="display:block;">
    <!------------------------------------------------  HEADER  -------------------------------------------------->
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
                <li><a class="btn1" href="./index.php#order-sequences"><i class="fa fa-chain"></i>روند سفارش</a></li>
                <li><a class="btn1" href="./index.php#works"><i class="fa fa-product-hunt"></i>زمینه های کاری</a></li>
                <li><a class="btn1" href="./index.php#skills"><i class="fa fa-product-hunt"></i>مهارت ها</a></li>
                <li><a class="btn1" href="./index.php#last-posts"><i class="fa fa-bold"></i> وبلاگ </a></li>
            </ul>
        </nav>
    </header>
    <!-----------------------------------------------  SHOW POST  ------------------------------------------------>
    <?php
    if (isset($_GET['post']) and is_numeric($_GET['post'])) {
        $post_id          =   $_GET['post'];
        $post             =   Post::getPostById($post_id);
        $user_id          =   $post->u_id;
        $user_name        =   $post->u_name;
        $user             =   User::getUserById($user_id);
        $stars_count      =   (int)$user->u_rate;
        $followers_count  =   $user->follower_count;
        $bio              =   $user->bio;
        $u_avatar         =   $user->avatar;
        $signup_time      =   convertDate($user->signup_time);
        $p_content        =   str_replace("--more--"," ",$post->p_content);
    ?>
        <!-- post image -->
        <div>
            <div id="p-header-container">
                <div class="p-header">
                    <p>
                        <?=$post->p_title?>
                        <br/>
                        <span class="post-date">
                            <i class="fa fa-clock-o"></i>
                            نوشته شده در :
                            <?php $creation = convertDate($post->creation_time);echo $creation['day'] . " " . $creation['month_name'] . " " . $creation['year'];echo " ساعت " . $creation['minute'] . " : " . $creation['hour']; ?>
                            <span class="mr-3">
                                <i class="fa fa-user"></i>
                                توسط
                                <?=$user_name?>
                            </span>
                        </span>
                    </p>


                </div>
            </div>
            <div class="p-img-container shadow">
                <img src="./includes/images/uploads/posts/1024x500/<?=$post->p_image?>">
            </div>
        </div>

        <!--  post categories  -->
        <div class="container">
            <div class="p-cats">
            <p class="text-vsm mb-3">نوشته شده در گروه : </p>
            <?php
            if ($cats_ids=$post->cats){
                foreach ($cats_ids as $cat_id){
                    $cat_object=Cat::getCatById($cat_id);
                    $cat_name=$cat_object->cat_name;
                    echo "<a class='cat'><i class='fa fa-tag p-1'></i>$cat_name</a>";
                }
            } else
                echo "<p class='cat'><i class='fa fa-tag p-1'></i>بدون دسته بندی</p>";
            ?>
        </div>
        </div>

        <!-- post content -->
        <div class="container">
            <p class="p-content"><?=$p_content?></p>
        </div>

        <!-- liking panel -->
        <div class="liking-section">
            <div class="dislike-container" id="btn-dislike">
                <i class="fa fa-thumbs-down"></i>
                <i class="like-outer fa fa-circle-thin"></i>
                <span id="dislike-counter"><?=$post->dislike_count?></span>
            </div>
            <div class="like-container" id="btn-like">
                <i class="fa fa-heart"></i>
                <i class="like-outer fa fa-circle-thin"></i>
                <span id="like-counter"><?=$post->like_count?></span>
            </div>
        </div>

        <!-- about-author part -->
        <div class="comments container shadow-lg">
            <div class="comments-baner">
                درباره نویسنده
            </div>
            <div class="author-frame row">
                <div class="author-right col-12 col-md-4">
                    <img class="author-avatar" src="includes/images/uploads/avatars/<?=$u_avatar?>" />
                    <div id="stars">
                        <?php
                        for($i=0; $i<$stars_count; $i++){
                            echo "<img src='includes/images/star.png'/>";
                        }
                        ?>
                    </div>
                    <p><?=$user->u_name?></p><small>کاربر ادمین</small>
                    <br/>
                    <small> ثبت شده در: </small>
                    <br/>
                    <small><?=$signup_time['year']."/".$signup_time['month_num']."/".$signup_time['day']." - ".$signup_time['hour'].":".$signup_time['minute']?></small>
                    <hr/>
                    <small> دنبال کننده ها: <?=$followers_count?></small>
                </div>
                <div class="author-left col-12 col-md-8">
                    <p><?=$bio?></p>
                    <button class='btn btn-sm btn-outline-secondary p-2 mt-4' id='btn-follow'>
                        <i class='fa fa-send-o'></i>  دنبال کردن
                    </button>
                </div>
            </div>
        </div>

        <!-- show comments-part -->
        <div class="comments container shadow-lg">

            <div class="comments-baner ">
                دیدگاه ها
            </div>

            <?php
            $comments = Comment::getCommentsByPostId($post_id);
            if($comments) {
                foreach ($comments as $comment) {
                    $creation_time=convertDate($comment->time);
                    if ($comment->parent_id == 1) $class=" ";
                    else $class="mr-7";
                    ?>
                    <div class="comment-frame row <?=$class?>">
                        <div class="comment-right col-12 col-md-3 col-xl-2">
<!--                            <i class="fa fa-user-circle-o fa-3x p-3"></i>-->
                            <img class="img-comment-avatar" src="includes/images/uploads/avatars/<?=User::getUserById($comment->u_id)->avatar?>" />
                            <p>
                                <?php
                                echo $comment->full_name;
                                if ($class == "mr-7") {
                                    $comment_father_author=Comment::getCommentById($comment->parent_id)->full_name;
                                    echo "<span class='text-black-50 '> در پاسخ به </span>".$comment_father_author."<span class='text-black-50'> گفته : </span>";
                                } else
                                    echo "<span class='text-black-50'> گفته : </span>";
                                ?>
                            </p>
                            <br/>
                            <p class="text-vsm">
                                <?=$creation_time['year']."/".$creation_time['month_num']."/".$creation_time['day']." - ".$creation_time['hour'].":".$creation_time['minute']?>
                            </p>
                        </div>
                        <div class="comment-left col-12 col-md-9 col-xl-10">
                            <p><?=$comment->c_text?></p>
                            <div class="comment-footer">
                                <a href="#comment-target" class="btn btn-info btn-reply-comment" onclick="changeAnswer(<?=$comment->id?>,'<?=$comment->full_name?>');"> ارسال پاسخ <i class="fa fa-reply"></i></a>
                            </div>
                        </div>
                    </div>
                <?php
                }
            } else
                echo "<p class='text-center text-black-50 pt-3'>در رابطه با این پست نظری وجود ندارد</p>";
            ?>
        </div>

        <!-- send comment-part -->
        <?php
        if ($post->allow_comments) 
        {
        ?>
            <div id="send-comment" class="send-comment container shadow-lg">
                <div class="comments-baner" id="comment-target">ارسال دیدگاه</div>
                <p id="comment-title"></p><!--====(title)====-->
                <input type="hidden" id="c_answer_id" name="c_answer_id" value="0" />
                <?php
                if (isset($_GET['post']))
                    echo "<input type='hidden' name='post_id' value='".$_GET["post"]."' id='post_id' />";
                else 
                    echo "<input type='hidden' name='post_id' value='0' id='post_id' />";
                ?>
                <div class="form-group row mb-2">
                    <label class="col-md-2 offset-md-1 text-right pr-4">نام:</label>
                    <div class="col-md-7">
                        <input type="text" id="c_full_name" class="form-control text-center" placeholder="نام و نام خانوادگی" />
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-md-2 offset-md-1 text-right pr-4">وب سایت:</label>
                    <div class="col-md-7">
                        <input type="text" id="c_site" class="form-control text-center" placeholder="www.example.com" />
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label class="col-md-2 offset-md-1 text-right pr-4">ایمیل:</label>
                    <div class="col-md-7">
                        <input type="email" id="c_email" class="form-control text-center" placeholder="host@domain.com" />
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label class="col-md-2 offset-md-1 text-right pr-4">نظر:</label>
                    <div class="col-md-7">
                        <input type="textarea" id="c_body" class="form-control text-center text-area" placeholder="متن نظر" />
                    </div>
                </div>
                <?php
                //submit btn
                if (!isset($_SESSION['u_id']))
                    echo '<P class="text-center">برای ارسال نظر باید به حساب کاربری خود وارد شوید!</P>';
                else
                    echo '<button type="button" id="btn-send-comment" class="btn btn-info center">ارسال</button>';
                ?>
            </div>
        <?php
        }
        ?>
        <!------------------------------------------------   HOME PAGE   ---------------------------------------------->
    <?php
    } //end if for (show post page)
    else {
    ?>
        <!-- main slider -->
        <section id="slider">
            <div id="top-carousel" class="carousel slide" data-ride="carousel" data-pause="false" data-interval="3000">
                <div class="carousel-inner">
                    <div class="carousel-item active" style="background-image: url('includes/images/slider/s1.jpg')">
                        <div class="carousel-overlay">
                            <div class="carousel-content text-center wow fadeInUp" data-wow-delay=".5s">
                                <h2 class="carousel-title">برنامه نویسی سمت سرور و کلاینت</h2>
                                <p class="carousel-text">
                                    طراحی و پیاده سازی انواع CMS ها، وبسایت ها، پایگاه های داده و اپلیکیشن های تحت وب با رعایت اصول مهندسی نرم افزار و امنیت بالا
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item " style="background-image: url('includes/images/slider/s3.jpg')">
                        <div class="carousel-overlay">
                            <div class="carousel-content text-center wow fadeInUp" data-wow-delay=".5s">
                                <h2 class="carousel-title">وبسایت های مدرن، سریع و واکنشگرا</h2>
                                <p class="carousel-text">
                                    طراحی واکنشگرا یک روش طراحی وب است که هدف از آن نمایش مناسب صفحه ی وب در طیف گسترده‌ای از دستگاه‌ها، (از تلفن‌های همراه گرفته تا نمایشگرهای بزرگ و عریض) می باشد.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item " style="background-image: url('includes/images/slider/s2.jpg')">
                        <div class="carousel-overlay">
                            <div class="carousel-content text-center wow fadeInUp" data-wow-delay=".5s">
                                <h2 class="carousel-title">توسعه ی کسب و کار با وبسایت</h2>
                                <p class="carousel-text">
                                    کسب و کار و خدمات خود را با وب سایت شخصی توسعه دهید!
                                </p>
                            </div>
                        </div>
                    </div>

                    <a class="carousel-control-prev" href="#top-carousel" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next" href="#top-carousel" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </a>

                    <ol class="carousel-indicators">
                        <li data-target="#top-carousel" data-slide-to="0" class="active"></li>
                        <li data-target="#top-carousel" data-slide-to="1"></li>
                        <li data-target="#top-carousel" data-slide-to="2"></li>
                    </ol>
                </div>
            </div>
        </section>

        <!-- order sequences -->
        <section id="order-sequences">
            <h3 class="header-1 text-light my-5">راه اندازی وب سایت شخصی شما!</h3>
            <h3 class="header-2 text-white-50 mb-3">روال کاری</h3>
            <div class="container">
                <div class="row position-relative">
                    <hr class="dashed-hr d-none d-lg-block wow fadeIn" data-wow-delay="1.4s"/>
                    <div class="card col-12 col-md-4 col-lg-3 wow fadeIn" data-wow-delay=".2s">
                    <span class="fa-stack fa-3x m-auto">
                        <i class="fa fa-circle fa-stack-2x text-nice-blue"></i>
                        <i class="fa fa-stack-1x fa-phone text-black-50"></i>
                    </span>
                        <div class="card-body text-center">
                            <h6 class="card-title mb-3">تماس و مشاوره</h6>
                            <hr class="bg-white-50"/>
                            <p class="sm-font text-white-50">
                                از طریق تماس، ایمیل و یا ارسال پیامک شما و هماهنگی ما با شما جهت برقراری جلسه ی دیدار حضوری
                            </p>
                        </div>
                    </div>
                    <div class="card col-12 col-md-4 col-lg-3 wow fadeIn" data-wow-delay=".5s">
                    <span class="fa-stack fa-3x m-auto">
                        <i class="fa fa-circle fa-stack-2x text-nice-blue"></i>
                        <i class="fa fa-stack-1x fa-handshake-o text-black-50"></i>
                    </span>
                        <div class="card-body text-center">
                            <h6 class="card-title mb-3">دیدار حضوری</h6>
                            <hr class="bg-white-50"/>
                            <p class="sm-font text-white-50">
                                جهت بررسی ویژگی های مورد نیاز و مورد درخواست بهمراه پیش پرداخت و عقد قرارداد و همچنین بررسی میزان زمان مورد نیاز جهت تکمیل پروژه
                            </p>
                        </div>
                    </div>
                    <div class="card col-12 col-md-4 col-lg-3 wow fadeIn" data-wow-delay=".8s">
                    <span class="fa-stack fa-3x m-auto">
                        <i class="fa fa-circle fa-stack-2x text-nice-blue"></i>
                        <i class="fa fa-thumbs-o-up fa-stack-1x text-black-50"></i>
                    </span>
                        <div class="card-body text-center">
                            <h6 class="card-title mb-3">برگزاری جلسه نمایش و تایید شما</h6>
                            <hr class="bg-white-50"/>
                            <p class="sm-font text-white-50">
                                جلسه ای جهت بررسی قابلیت ها و ویژگی های بصری وب سایت با حضور شما و همچنین آموزش و معرفی قابلیت های وب سایت به شما
                            </p>
                        </div>
                    </div>
                    <div class="card col-12 col-md-4 col-lg-3 wow fadeIn" data-wow-delay="1.1s">
                    <span class="fa-stack fa-3x m-auto">
                        <i class="fa fa-circle fa-stack-2x text-nice-blue"></i>
                        <i class="fa fa-stack-1x fa-check text-black-50"></i>
                    </span>
                        <div class="card-body text-center">
                            <h6 class="card-title mb-3">تست و راه اندازی نهایی</h6>
                            <hr class="bg-white-50"/>
                            <p class="sm-font text-white-50">
                                تست و بررسی عملکرد وبسایت از نظر سرعت، امنیت و واکنش پذیری آن در صفحات نمایش گوناگون با ابزارها و وب سایت های معتبر در این زمینه و در نهایت قرار دادن وب سایت روی هاست.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- works -->
        <section id="works">
            <div class="container mb-8">
                <h3 class="header-1 text-dark mb-8">زمینه های کاری</h3>
                <div class="row justify-content-center">
                    <div class="wow fadeInUp col-12 col-sm-6 col-md-6 col-lg-3" data-wow-delay=".4s">
                        <div class="card-2">
                            <span class="fa-stack fa-3x">
                                <i class="fa fa-circle-thin fa-stack-2x text-warning"></i>
                                <i class="fa fa-magic fa-stack-1x "></i>
                            </span>
                            <div class="card-body text-center">
                                <h6 class="card-title mb-3">طراحی و پیاده سازی سیستم های مدیریت محتوا (CMS)</h6>
                                <hr/>
                                <p class="sm-font text-black-50">
                                    طراحی و پیاده سازی انواع سیستم های مدیریت محتوا از طراحی دیتابیس تا تست و بررسی نهایی.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="wow fadeInUp col-12 col-sm-6 col-md-6 col-lg-3" data-wow-delay=".6s">
                        <div class="card-2">
                            <span class="fa-stack fa-3x">
                                <i class="fa fa-circle-thin fa-stack-2x text-warning"></i>
                                <i class="fa fa-connectdevelop fa-stack-1x "></i>
                            </span>
                            <div class="card-body text-center ">
                                <h6 class="card-title mb-3">طراحی و پیاده سازی وب سایت های شیک ، مدرن و واکنش گرا</h6>
                                <hr/>
                                <p class="sm-font text-black-50">
                                    طراحی و پیاده سازی انواع وب سایت ها با به روزترین ویژگی ها و شیک ترین رابط ها.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="wow fadeInUp col-12 col-sm-6 col-md-6 col-lg-3" data-wow-delay=".8s">
                        <div class="card-2">
                            <span class="fa-stack fa-3x">
                                <i class="fa fa-circle-thin fa-stack-2x text-warning"></i>
                                <i class="fa fa-code fa-stack-1x "></i>
                            </span>
                            <div class="card-body text-center ">
                                <h6 class="card-title mb-3">انجام پروژه های برنامه نویسی تحت وب</h6>
                                <hr/>
                                <p class="sm-font text-black-50">
                                    پیاده سازی انواع پروژه های تحت وب به زبان ها و فریمورک های مختلف، از جمله php، asp، node.js، laravel و ...
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="wow fadeInUp col-12 col-sm-6 col-md-6 col-lg-3" data-wow-delay="1s">
                        <div class="card-2">
                            <span class="fa-stack fa-3x">
                                <i class="fa fa-database fa-stack-1x text-dark"></i>
                                <i class="fa fa-circle-thin fa-stack-2x text-warning"></i>
                            </span>
                            <div class="card-body text-center">
                                <h6 class="card-title mb-3">طراحی پایگاه داده ی اختصاصی</h6>
                                <hr/>
                                <p class="sm-font text-black-50">
                                    طراحی و پیاده سازی و پشتیبانی از انواع پایگاه های داده متناسب با کاربردهای خاص.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- skills -->
        <section id="skills">
            <div class="row">
                <div class="col-0 col-lg-3 wow fadeInRight" id="my-skills-container-right" data-wow-delay="0.4s">
                    <p id="right-text" class=" fadeIn" data-wow-delay="1.2s">
                        مهارت هایی که تا کنون بدست آورده ام :
                    </p>
                </div>
                <div class="col-12 col-lg-9 row d-flex justify-content-center" id="my-skills-container-left">
                    <div class="col-5 col-lg-3 skill-part wow fadeIn" data-wow-delay=".2s">
                <span class="float-right fa-stack fa-2x m-auto">
                    <i class="fa fa-circle fa-stack-2x text-dark"></i>
                    <i class="di devicon-php-plain fa-stack-1x"></i>
                </span>
                        <h4 class="skill-part-h">Php</h4>

                    </div>
                    <div class="col-5 col-lg-3 skill-part wow fadeIn" data-wow-delay=".6s">
                <span class="float-right fa-stack fa-2x m-auto">
                    <i class="fa fa-circle fa-stack-2x text-dark"></i>
                    <i class="fa fa-database fa-stack-1x"></i>
                </span>
                        <h4 class="skill-part-h">SQL Server</h4>

                    </div>
                    <div class="col-5 col-lg-3 skill-part wow fadeIn" data-wow-delay=".5s">
                <span class="float-right fa-stack fa-2x m-auto">
                    <i class="fa fa-circle fa-stack-2x text-dark"></i>
                    <i class="di devicon-mysql-plain fa-stack-1x"></i>
                </span>
                        <h4 class="skill-part-h">MySQL</h4>

                    </div>
                    <div class="col-5 col-lg-3 skill-part wow fadeIn" data-wow-delay=".6s">
                <span class="float-right fa-stack fa-2x m-auto">
                    <i class="fa fa-circle fa-stack-2x text-dark"></i>
                    <i class="di devicon-csharp-line fa-stack-1x"></i>
                </span>
                        <h4 class="skill-part-h">#C</h4>

                    </div>
                    <div class="col-5 col-lg-3 skill-part wow fadeIn" data-wow-delay="1.4s">
                <span class="float-right fa-stack fa-2x m-auto">
                    <i class="fa fa-circle fa-stack-2x text-dark"></i>
                    <i class="di devicon-javascript-plain fa-stack-1x"></i>
                </span>
                        <h4 class="skill-part-h">Java Script</h4>

                    </div>
                    <div class="col-5 col-lg-3 skill-part wow fadeIn" data-wow-delay=".8s">
                <span class="float-right fa-stack fa-2x m-auto">
                    <i class="fa fa-circle fa-stack-2x text-dark"></i>
                    <i class="di devicon-jquery-plain fa-stack-1x"></i>
                </span>
                        <h4 class="skill-part-h">jQuery</h4>

                    </div>
                    <div class="col-5 col-lg-3 skill-part wow fadeIn" data-wow-delay=".9s">
                <span class="float-right fa-stack fa-2x m-auto">
                    <i class="fa fa-circle fa-stack-2x text-dark"></i>
                    <i class="di devicon-bootstrap-plain fa-stack-1x"></i>
                </span>
                        <h4 class="skill-part-h">BootStrap</h4>

                    </div>
                    <div class="col-5 col-lg-3 skill-part wow fadeIn" data-wow-delay=".5s">
                <span class="float-right fa-stack fa-2x m-auto">
                    <i class="fa fa-circle fa-stack-2x text-dark"></i>
                    <i class="di devicon-laravel-plain  fa-stack-1x"></i>
                </span>
                        <h4 class="skill-part-h">Laravel</h4>

                    </div>
                    <div class="col-5 col-lg-3 skill-part wow fadeIn" data-wow-delay=".7s">
                <span class="float-right fa-stack fa-2x m-auto">
                    <i class="fa fa-circle fa-stack-2x text-dark"></i>
                    <i class="di devicon-html5-plain fa-stack-1x"></i>
                </span>
                        <h4 class="skill-part-h">HTML</h4>

                    </div>
                    <div class="col-5 col-lg-3 skill-part wow fadeIn" data-wow-delay="1.2s">
                <span class="float-right fa-stack fa-2x m-auto">
                    <i class="fa fa-circle fa-stack-2x text-dark"></i>
                    <i class="di devicon-css3-plain fa-stack-1x"></i>
                </span>
                        <h4 class="skill-part-h">CSS</h4>

                    </div>
                    <div class="col-5 col-lg-3 skill-part wow fadeIn" data-wow-delay=".7s">
                <span class="float-right fa-stack fa-2x m-auto">
                    <i class="fa fa-circle fa-stack-2x text-dark"></i>
                    <i class="di devicon-linux-plain fa-stack-1x"></i>
                </span>
                        <h4 class="skill-part-h">Linux</h4>

                    </div>
                    <div class="col-5 col-lg-3 skill-part wow fadeIn" data-wow-delay="1.1s">
                <span class="float-right fa-stack fa-2x m-auto">
                    <i class="fa fa-circle fa-stack-2x text-dark"></i>
                    <i class="di devicon-wordpress-plain fa-stack-1x"></i>
                </span>
                        <h4 class="skill-part-h">Wordpress</h4>

                    </div>
                    <div class="col-5 col-lg-3 skill-part wow fadeIn" data-wow-delay=".6s">
                <span class="float-right fa-stack fa-2x m-auto">
                    <i class="fa fa-circle fa-stack-2x text-dark"></i>
                    <i class="di devicon-github-original fa-stack-1x"></i>
                </span>
                        <h4 class="skill-part-h">GitHub</h4>

                    </div>
                    <div class="col-5 col-lg-3 skill-part wow fadeIn" data-wow-delay=".8s">
                <span class="float-right fa-stack fa-2x m-auto">
                    <i class="fa fa-circle fa-stack-2x text-dark"></i>
                    <i class="di devicon-gitlab-plain fa-stack-1x"></i>
                </span>
                        <h4 class="skill-part-h">GitLab</h4>

                    </div>
                    <div class="col-5 col-lg-3 skill-part wow fadeIn" data-wow-delay="1.3s">
                <span class="float-right fa-stack fa-2x m-auto">
                    <i class="fa fa-circle fa-stack-2x text-dark"></i>
                    <i class="fa fa-laptop fa-stack-1x"></i>
                </span>
                        <h4 class="skill-part-h">Hardware Repairs</h4>

                    </div>
                    <div class="col-5 col-lg-3 skill-part wow fadeIn" data-wow-delay=".5s">
                <span class="float-right fa-stack fa-2x m-auto">
                    <i class="fa fa-circle fa-stack-2x text-dark"></i>
                    <i class="di devicon-photoshop-plain fa-stack-1x"></i>
                </span>
                        <h4 class="skill-part-h">Photoshop</h4>

                    </div>
                    <div class="col-5 col-lg-3 skill-part wow fadeIn" data-wow-delay="1.2s">
                <span class="float-right fa-stack fa-2x m-auto">
                    <i class="fa fa-circle fa-stack-2x text-dark"></i>
                    <i class="di devicon-apache-plain-wordmark fa-stack-1x"></i>
                </span>
                        <h4 class="skill-part-h">Apache</h4>

                    </div>
                
                
                <div class="col-5 col-lg-3 skill-part wow fadeIn" data-wow-delay="1.4s">
                    <span class="float-right fa-stack fa-2x m-auto">
                        <i class="fa fa-circle fa-stack-2x text-dark"></i>
                        <i class="di devicon-angularjs-plain fa-stack-1x"></i>
                    </span>
                    <h4 class="skill-part-h">Angular</h4>
                </div>
                
                
            </div>
        </section>

        <!-- last-posts -->
        <section id="last-posts" class="last-posts">
            <div class="container-fluid pt-5">
                <h3 class="header-1 text-dark">آخرین مطالب </h3>
                <div class="container">
                    <div class="owl-carousel wow fadeIn" data-wow-delay=".4s">
                        <?php
                        if ($last_posts = Post::getAllPosts(1, 0, 6, 0)) {
                            foreach ($last_posts as $post) {
                                if ($pos = strpos($post->p_content, "--more--"))
                                    $content = substr($post->p_content, 0, $pos);
                                else
                                    $content = substr($post->p_content, 0, 300);
                                $rate = $post->p_rate;
                                ?>
                                <div class="p-card">
                                    <div class="p-card-img-container">
                                        <img class="p-card-img" src="includes/images/uploads/posts/260x260/<?=$post->p_image?>" />
                                        <div class="p-card-content">
                                            <?=$content?>
                                        </div>
                                        <div class="p-card-liking">
                                        <span class="m-3">
                                            <p>+<?=$post->like_count?></p>
                                            <i class="fa fa-heart"></i>
                                        </span> |
                                            <span class="m-3">
                                            <p>+<?=$post->dislike_count?></p>
                                            <i class="fa fa-heart-o"></i>
                                        </span> |
                                            <span class="m-3">
                                            <p>+<?=$post->comment_count?></p>
                                            <i class="fa fa-reply"></i>
                                        </span>
                                        </div>
                                    </div>

                                    <a href="./?post=<?= $post->id ?>">
                                        <div class="p-card-title">
                                            <?=$post->p_title?>
                                        </div>
                                    </a>

                                    <hr/>

                                    <div class="p-card-footer">
                                        <div class="p-card-date"><?php $creation = convertDate($post->creation_time);echo $creation['day'] . " " . $creation['month_name'] . " " . $creation['year'];echo " ساعت " . $creation['minute'] . " : " . $creation['hour']; ?></div>
                                        <a class="p-card-btn" href="./?post=<?= $post->id ?>">
                                        <span>
                                            <i class="fa fa-arrow-left fa-2x"></i>
                                        </span>
                                        </a>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- be a writer -->
        <section id="writer">
            <div>
                <h3 class="header-1 text-dark mb-8 wow fadeIn" data-wow-delay=".3s">خودتان هم می توانید نویسنده شوید!</h3>

                <div class="row justify-content-center align-items-center">
                    <div class="wow fadeInUp" data-wow-delay=".6s">
                        <p class="text-vsm text-black-50">با کلیک بر روی دکمه ی ثبت نام در بالای مرورگر و تکمیل فرم خودتان نویسنده شوید و پست های تخصصی در زمینه ی تخصص های خود یا خدماتتان را با دیگر کاربران به اشتراک بگذارید!</p>
                        <p class="text-vvsm mt-2">به همین راحتی!</p>
                    </div>
                    <div class="wow fadeInLeft" data-wow-delay=".6s">
                        <img src="includes/images/be-author.png" alt="">
                    </div>
                </div>
            </div>
        </section>

    <?php } //end elseif for (show home page) ?>
    <!------------------------------------------------   COMMON   ----------------------------------------------->

    <!-- footer -->
    <footer id="footer">
        <p class="text-center pt-5 pb-4">طراحی و توسعه از 01system . کلیه ی حقوق محفوظ است &copy;</p></p>
        <hr class="bg-white-50"/>
        <div class="p-2 text-center">
            <a href="https://t.me/MehranNasr1375" class="text-white-50"><i class="fa fa-telegram fa-2x mx-2"></i></a>
            <a href="https://www.instagram.com/mehrannasr1375" class="text-white-50"><i class="fa fa-instagram fa-2x mx-2"></i></a>
            <a href="https://github.com/mehrannasr1375" class="text-white-50"><i class="fa fa-github fa-2x mx-2"></i></a>
            <a href="https://www.linkedin.com/in/mehran-nasr" class="text-white-50"><i class="fa fa-linkedin fa-2x mx-2"></i></a>
            
            <!--<a href="#" class="text-white-50"><i class="fa fa-google-plus fa-2x mx-2"></i></a>-->
            <!--<a href="#" class="text-white-50"><i class="fa fa-twitter fa-2x mx-2"></i></a>-->
            <!--<a href="#" class="text-white-50"><i class="fa fa-youtube fa-2x mx-2"></i></a>-->
            
            <a href="#" class="text-white-50"><i class="fa fa-whatsapp fa-2x mx-2"></i></a>
            <br>        
            <a href="#" class="phone-i text-white-50"><i class="fa fa-mobile fa-2x mx-2"></i>09035438619</a>
        </div>
    </footer>

    <!-- login form -->
    <div id="sign-in-overlay">
        <div id="login-frame">
            <div>
                <div class="login-close">
                    <i class="fa fa-close fa-2x "></i>
                </div>
                <span class="fa-stack fa-3x login-top-i">
                    <i class="fa fa-circle fa-stack-2x text-white"></i>
                    <i class="fa fa-user-circle fa-stack-1x text-dark"></i>
                </span>
                <div class="login-title">
                    <p>ورود به حساب کاربری</p>
                </div>
            </div>
            <p id="response-signin"></p>
            <div>
                <div class="form-group m-0">
                    <label class="col-form-label text-vsm p-0">نام کاربری :</label>
                    <input type="text" class="" id="user" name="user" autocomplete="off" />
                </div>
                <div class="form-group mt-0">
                    <label class="col-form-label text-vsm p-0">رمز عبور :</label>
                    <input type="password" class="" id="pass" name="pass" autocomplete="off" />
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="remember" checked="checked"/>
                    <label class="custom-control-label mb-3 text-vvsm" for="remember"> مرا به خاطر بسپار </label>
                </div>
                <div class="text-center text-sm">
                    <input type="button" id="btn-sign-in" class="btn btn-outline-success-2" value="ورود"/><br/>
                    <a class="btn-signup" id="sign-up">ثبت نام</a>
                    |
                        <a class="btn-forget" id="btn-forget">فراموشی کلمه عبور</a>
                </div>
            </div>
        </div>
    </div>

    <!-- sign-up form -->
    <div id="sign-up-overlay">
        <div id="sign-up-frame">
            <div>
                <div class="login-close">
                    <i class="fa fa-close fa-2x"></i>
                </div>
                <span class="fa-stack fa-3x login-top-i">
                    <i class="fa fa-circle fa-stack-2x text-white"></i>
                    <i class="fa fa-circle-o-notch fa-stack-1x text-dark"></i>
                </span>
                <div class="login-title">
                    <p>ایجاد حساب کاربری</p>
                </div>
            </div>
            <p id="response-signup"></p>
            <p id="response-success"></p>
            <div class="login-body">
                <table>
                    <tr>
                        <td>
                            <input type="text" id="f_name" placeholder="نام" autocomplete="off"/>
                        </td>
                        <td>
                            <input type="text" id="l_name" placeholder="نام خانوادگی" autocomplete="off"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" id="username" placeholder="نام کاربری" autocomplete="off"/>
                        </td>
                        <td>
                            <input type="text" id="email" placeholder="ایمیل" autocomplete="off"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="password" id="pass_1" placeholder="رمز عبور" autocomplete="off"/>
                        </td>
                        <td>
                            <input type="password" id="pass_2" placeholder="تکرار رمز عبور" autocomplete="off"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <select id="sex" style="width: 100%;height: 38px" name="sex">
                                <option value="man">مرد</option>
                                <option value="woman">زن</option>
                            </select>
                        </td>
                        <td>
                            <input id="age" type="text" id="age" placeholder="سن" autocomplete="off"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input id="description" multiple="multiple" style="width: 100%;height: 80px" type="text" name="bio" placeholder="بیوگرافی(450 کاراکتر)" autocomplete="off"/>
                        </td>
                    </tr>
                </table>
                <input type="button" id="btn-sign-up" class="btn btn-outline-success-2" value="ثبت نام"/>
            </div>
        </div>
    </div>

    <!-- forget-password form -->
    <div id="forget-overlay">
        <div id="forget-frame">
            <div>
                <div class="login-close">
                    <i class="fa fa-close fa-2x"></i>
                </div>
                <span class="fa-stack fa-3x login-top-i">
                    <i class="fa fa-circle fa-stack-2x text-white"></i>
                    <i class="fa fa-question fa-stack-1x text-dark-50"></i>
                </span>
                <div class="login-title">
                    <p>فراموشی رمز عبور</p>
                </div>
            </div>
            <p id="res-failure-forget" class="mt-3 text-center text-light bg-danger"></p>
            <p id="res-success-forget" class="mt-3 text-center text-light bg-success"></p>
            <div class="login-body text-center">
                <input type="text" id="forgetemail" class="my-4" style="width: 100%" placeholder="جهت بازیابی رمز عبور آدرس ایمیل خود را وارد نمایید:"/>
                <input type="button" id="btn-forget-mail" name="btn-forget-mail" class="btn btn-outline-success-2" value="بازیابی رمز عبور"/>
            </div>
        </div>
    </div>

    <!-- go top -->
    <a href="#home" id="go-to-top"><i class="fa fa-chevron-up"></i></a>

    <!-- popup box -->
    <div id="popup-box" class="border-lr-red">
        <div class="popup-right">
            <i id="popup-i" class="fa fa-2x"></i>
        </div>
        <div id="popup-msg" class="">
            ابتدا باید به حساب کاربری خود وارد شوید!
        </div>
    </div>

    <!--scripts-->
    <script src="scripts/jquery-3.3.1.js"></script>
    <script src="scripts/popper.min.js"></script>
    <script src="scripts/bootstrap.min.js"></script>
    <script src="scripts/owl.carousel.min.js"></script>
    <script src="scripts/wow.min.js"></script>
    <script src="scripts/smooth-scroll.min.js"></script>
    <script src="scripts/js.js"></script>
    <script src="scripts/show-post.js"></script>
    <script src="scripts/login.js"></script>
    <script src="scripts/changeAnswer.js"></script>
</div>
</body>
</html>