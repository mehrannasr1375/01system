<?php
    $post_id          =   (int)$_GET['post'];
    $post             =   Post::getPostById($post_id);
    $user_id          =   $post->u_id;
    $user_name        =   $post->u_name;
    $user             =   User::getUserById($user_id);
    $stars_count      =   $user->u_rate;
    $followers_count  =   $user->follower_count;
    $bio              =   $user->bio;
    $u_avatar         =   $user->avatar;
    $u_type           =   $user->u_type == 1 ? 'Admin':'Editor';
    $signup_time      =   convertDate($user->signup_time);
    $p_content        =   str_replace("--more--", " ", $post->p_content );
    $creation         =   convertDate($post->creation_time);
?>



<!-- post image ------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
<div>
    <div id="p-header-container">
        <div class="p-header">
            <h1 class="mb-3"><?=$post->p_title?></h1>
            <span class="post-date pt-5">
                    <i class="fa fa-calendar-times-o"></i>
                    <?= $creation['day'] . " " . $creation['month_name'] . " " . $creation['year'] ; ?>
                    <i class="fa fa-clock-o mr-4"></i>
                    <?= $creation['minute'] . " : " . $creation['hour']; ?>
                    <span class="mr-4">
                        <i class="fa fa-user"></i>
                        <?= $user_name ?>
                    </span>
                </span>
        </div>
    </div>
    <div class="p-img-container shadow">
        <img src="./includes/images/uploads/posts/1024x500/<?=$post->p_image?>">
    </div>
</div>



<!-- post content $ details ------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
<div class="container">



    <!--  post categories  -------------------------------------------------------------------------------------------------------------------------------------------------------------->
    <div class="p-cats">
        <p class="text-vvsm">نوشته شده در گروه : </p>
        <?php
            if ($cats = $post->cats)
                foreach ($cats as $cat)
                    echo "<a class='cat'><i class='fa fa-sitemap p-1'></i>" . $cat->title . "</a>";
            else
                echo "<p class='cat'><i class='fa fa-sitemap p-1'></i>بدون دسته بندی</p>";
        ?>
    </div>



    <!--  right & left sidebars  -------------------------------------------------------------------------------------------------------------------------------------------------------->
    <div class="row">



        <!--  rightbar  ----------------------------------------------------------------------------------------------------------------------------------------------------------------->
        <div class="col-12 col-lg-9">



            <!-- post content ----------------------------------------------------------------------------------------------------------------------------------------------------------->
            <div class="container px-0 px-md-3">
                <p class="p-content"><?=$p_content?></p>
            </div>



            <!-- liking panel ----------------------------------------------------------------------------------------------------------------------------------------------------------->
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



            <!-- post details & actions ----------------------------------------------------------------------------------------------------------------------------------------------------------->
            <div>



                <!-- post tags -------------------------------------------------------------------------------------------------------------------------------------------------------------->
                <div class="p-con">
                    <div class="p-title">برچسب ها</div>
                    <div class="">
                        <div class="">
                            <?php
                            if ($tags = $post->tags)
                                foreach ($tags as $tag)
                                    echo "<a class='cat'><i class='fa fa-tag p-1'></i>" . $tag->title . "</a>";
                            else
                                echo "<p class='cat'><i class='fa fa-tag p-1'></i>بدون برچسب</p>";
                            ?>
                        </div>
                    </div>
                </div>



                <!-- about-author part ------------------------------------------------------------------------------------------------------------------------------------------------------>
                <div class="p-con">


                    <!-- title -->
                    <div class="p-title">درباره نویسنده</div>


                    <!-- user details -->
                    <div class="row">

                        <!-- user avatar -->
                        <div class="col-2">
                            <img class="author-img" src="includes/images/uploads/avatars/<?=$u_avatar?>" />
                        </div>

                        <!-- user details -->
                        <div class="col-10">
                            <div class="d-flex justify-content-between text-center justify-content-start flex-column flex-lg-row mr-4 mr-lg-0 pr-5 pr-lg-4">
                                <div class="d-flex flex-column ml-4">
                                    <p class="font-weight-bold"><?=$user_name?></p>
                                    <small><?= $u_type ?></small>
                                </div>
                                <button class='btn-reply-comment' id='btn-follow'><span class="px-3"><?=$followers_count?></span> دنبال کردن <i class='fa fa-send-o px-2'></i></button>
                                <div class="d-flex flex-column text-center mt-2">
                                    <div id="stars">
                                        <?php for ($i=0; $i<$stars_count; $i++) echo "<img src='includes/images/star.png'/>"; ?>
                                    </div>
                                </div>
                            </div>
                            <hr style=""/>
                        </div>

                    </div>


                    <!-- user bio -->
                    <p class="text-vsm p-3 text-justify"><?=$bio?></p>


                </div>



                <!-- show comments-part ----------------------------------------------------------------------------------------------------------------------------------------------------->
                <div class="p-con">
                    <div class="p-title">دیدگاه ها</div>
                    <?php
                    $comments = Comment::getCommentsByPostId($post_id);
                    if ($comments){
                        foreach ($comments as $comment){
                            $creation_time = convertDate($comment->time);
                            ?>
                            <div class="comment-frame d-flex justify-content-between flex-column <?=$class = ($comment->parent_id == 0) ? " " : "mr-7"?>">


                                <!-- comment avatar -->
                                <img src="includes/images/uploads/avatars/<?=User::getUserById($comment->u_id)->avatar?>" class="comment-img"/>


                                <!-- comment header -->
                                <div class="comment-header d-flex flex-column flex-lg-row justify-content-between">
                                    <span>
                                        <?php
                                        echo "<span class='font-weight-bold'>$comment->full_name</span>";
                                        if ($class == "mr-7") {
                                            $comment_father_author = Comment::getCommentById($comment->parent_id)->full_name;
                                            echo "<span> در پاسخ به </span>".$comment_father_author."<span class='text-black-50'> گفته : </span>";
                                        }
                                        else
                                            echo "<span> گفته : </span>";
                                        ?>
                                    </span>
                                    <span style="direction: ltr !important;">
                                        <?= $creation_time['hour'].":".$creation_time['minute']?><i class="fa fa-clock-o ml-1 mr-4"></i>
                                        <?= $creation_time['year']."/".$creation_time['month_num']."/".$creation_time['day']?><i class="fa fa-calendar mr-4 ml-1"></i>
                                        <?= $comment->website ?><i class="fa fa-internet-explorer ml-1"></i>
                                    </span>
                                </div>


                                <!-- comment body -->
                                <div class="comment-body">
                                    <?= $comment->c_text ?>
                                </div>


                                <!-- comment footer -->
                                <div class="d-flex justify-content-end p-2">
                                        <a href="#comment-target" class="btn-reply-comment" onclick="changeAnswer(<?=$comment->id?>,'<?=$comment->full_name?>');"> ارسال پاسخ <i class="fa fa-reply"></i></a>
                                </div>


                            </div>
                        <?php
                        }
                    }
                    else
                        echo "<p class='text-center text-black-50 pt-3'>در رابطه با این پست دیدگاهی وجود ندارد!</p>";
                    ?>
                </div>



                <!-- send comment-part ------------------------------------------------------------------------------------------------------------------------------------------------------>
                <div id="send-comment" class="p-con">
                    <div class="p-title" id="comment-target">
                        ارسال دیدگاه
                    </div>
                    <?php
                    if ($post->allow_comments && isset($_SESSION['u_id']))
                    {
                    ?>
                        <p id="comment-title" class="text-vsm"></p>
                        <input type="hidden" id="c_answer_id" name="c_answer_id" value="0" />

                        <?php
                        if (isset($_GET['post']))
                            echo "<input type='hidden' name='post_id' value='".$_GET["post"]."' id='post_id' />";
                        else
                            echo "<input type='hidden' name='post_id' value='0' id='post_id' />";
                        ?>


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

                        <button type="button" id="btn-send-comment" class="btn-reply-comment center">ارسال</button>
                    <?php
                    }
                    else if (!isset($_SESSION['u_id'])){
                        echo "<p class=' text-center text-black-50'>برای ارسال دیدگاه باید به حساب کاربری خود وارد شوید!</p>";
                    }
                    ?>
                </div>



            </div>



        </div>



        <!--  leftbar  ------------------------------------------------------------------------------------------------------------------------------------------------------------------>
        <?php require_once "leftbar.php"; ?>



    </div>



</div>



<!-- scripts ------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
<script>
    $(document).ready(function () {


        var post_id          =   $("#post_id").val();
        var likecounter      =   $('#like-counter');
        var dislikecounter   =   $('#dislike-counter');
        var like_count       =   likecounter.val();
        var dislike_count    =   dislikecounter.val();
        var popupbox         =   $('#popup-box');
        var popupmsg         =   $('#popup-msg');
        var popupi           =   $('#popup-i');


        // like
        $("#btn-like").on('click', function(){
            console.log(post_id);
            $.ajax({
                url:"./actions/show-post-actions.php",
                method:"POST",
                data:{
                    action:'like',
                    post_id:post_id
                },
                success:function (data){
                    if (data === '1'){     // x => like
                        popupmsg.html('پست لایک شد!');
                        likecounter.html(like_count+1);
                        popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-thumbs-o-up');
                        popupbox.removeClass('border-lr-red').addClass('border-lr-green').slideDown(500).delay(1500).slideUp(200);
                    }
                    else if (data === '3') { // dislike => like
                        popupmsg.html('پست لایک شد!');
                        dislikecounter.html((dislike_count>0) ? dislike_count-1:0);
                        likecounter.html(like_count+1);
                        popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-thumbs-o-up');
                        popupbox.removeClass('border-lr-red').addClass('border-lr-green').slideDown(500).delay(1500).slideUp(200);
                    }
                    else if (data === '4') { // like => like
                        popupmsg.html('بازخورد لغو گردید!');
                        likecounter.html((like_count>0) ? like_count-1:0);
                        popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-remove');
                        popupbox.removeClass('border-lr-red').addClass('border-lr-green').slideDown(500).delay(1500).slideUp(200);
                    }
                    else if (data === 'login') { // not logged in
                        popupmsg.html('باید به حساب خود وارد شوید!');
                        popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-lock');
                        popupbox.removeClass('border-lr-green').addClass('border-lr-red').slideDown(500).delay(1500).slideUp(200);
                    }
                    else {
                        popupmsg.html('خطایی رخ داده است!');
                        popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-warning');
                        popupbox.removeClass('border-lr-green').addClass('border-lr-red').slideDown(500).delay(1500).slideUp(200);
                    }
                }
            });
        });


        // dislike
        $("#btn-dislike").on('click', function(){
            $.ajax({
                url:"./actions/show-post-actions.php",
                method:"POST",
                data:{
                    action: 'dislike',
                    post_id: post_id
                },
                success:function (data){
                    if (data === '0') {      // x => dislike
                        dislikecounter.html(dislike_count+1);
                        popupmsg.html('پست دیسلایک شد!');
                        popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-thumbs-o-down');
                        popupbox.removeClass('border-lr-red').addClass('border-lr-green').slideDown(500).delay(1500).slideUp(200);
                    }
                    else if (data === '5') { // dislike => dislike
                        popupmsg.html('بازخورد لغو شد!');
                        dislikecounter.html((dislike_count>0) ? dislike_count-1:0);
                        popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-remove');
                        popupbox.removeClass('border-lr-red').addClass('border-lr-green').slideDown(500).delay(1500).slideUp(200);
                    }
                    else if (data === '2') { // like => dislike
                        popupmsg.html('پست دیسلایک شد!');
                        dislikecounter.html(dislike_count+1);
                        likecounter.html((like_count>0) ? like_count-1:0);
                        popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-thumbs-o-down');
                        popupbox.removeClass('border-lr-red').addClass('border-lr-green').slideDown(500).delay(1500).slideUp(200);
                    }
                    else if (data === 'login') { // not logged in
                        popupmsg.html('باید به حساب خود وارد شوید!');
                        popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-lock');
                        popupbox.removeClass('border-lr-green').addClass('border-lr-red').slideDown(500).delay(1500).slideUp(200);
                    }
                    else {
                        popupmsg.html('خطایی رخ داده است!');
                        popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-warning');
                        popupbox.removeClass('border-lr-green').addClass('border-lr-red').slideDown(500).delay(1500).slideUp(200);
                    }
                }
            });
        });


        // follow
        $("#btn-follow").on('click', function(){
            $.ajax({
                url:"./actions/show-post-actions.php",
                method:"POST",
                data:{
                    action:'follow',
                    post_id:post_id
                },
                success:function (data){
                    if (data === '1') {
                        popupmsg.html('درخواست ارسال شد!');
                        popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-send');
                        popupbox.removeClass('border-lr-red').addClass('border-lr-green').slideDown(500).delay(1500).slideUp(200);
                    }
                    else if (data === 'login') {
                        popupmsg.html('باید به حساب خود وارد شوید!');
                        popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-lock');
                        popupbox.removeClass('border-lr-green').addClass('border-lr-red').slideDown(500).delay(1500).slideUp(200);
                    }
                    else {
                        popupmsg.html('درخواست قبلا ارسال گردیده است!');
                        popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-warning');
                        popupbox.removeClass('border-lr-green').addClass('border-lr-red').slideDown(500).delay(1500).slideUp(200);
                    }
                }
            });
        });


        // send-comment
        $("#btn-send-comment").click(function (){
            var parent  =  $("#c_answer_id").val();
            var post    =  $("#post_id").val();
            var site    =  $("#c_site").val();
            var email   =  $("#c_email").val();
            var comment =  $("#c_body").val();
            if (comment=='')
                $('#comment-title').removeClass('success-res').addClass('failure-res').html('لطفا تمامی فیلدها را پر نمایید!');
            else{
                $.ajax({
                    url:'./actions/show-post-actions.php',
                    method:'POST',
                    data:{
                        action:'send-comment',
                        site:site,
                        email:email,
                        comment:comment,
                        parent:parent,
                        post:post
                    },
                    success:function (data){
                        if (data === 'true')
                            $('#comment-title').removeClass('failure-res').addClass('success-res').html('دیدگاه شما با موفقیت ثبت گردید و پس از تایید مدیریت نمایان خواهد شد.');
                        else{
                            $('#comment-title').removeClass('success-res').addClass('failure-res').html('خطایی رخ داده است. لطفا بعدا امتحان نمایید!');
                        }
                    }
                });
            }
        });


    });
</script>