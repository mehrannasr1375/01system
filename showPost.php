<?php
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
$p_content        =   str_replace("--more--", " ", $post->p_content);
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