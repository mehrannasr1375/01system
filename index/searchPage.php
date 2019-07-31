<?php
    $world = htmlspecialchars( trim($_POST['search-text']), ENT_QUOTES );
    $last_posts = Post::searchPosts($world, true, true, true, 0, 10);
    $count = 0;
    if (is_array($last_posts)) 
        $count = count($last_posts);
    $empty_flag = false;    
    if ($world == '') 
        $empty_flag = true;    
?>




<div class="container pt-6">
    <div class="row">



        <!-- right bar -->
        <div id="search-posts-parent" class="col-12 col-md-9">
            <?php
                if ($count > 0 && $empty_flag == false) {   
                    echo "<div class='alert alert-info mb-3'><p><span>". $count. " نتیجه یافت شد! </p></div>";
                    foreach ($last_posts as $post) {
                        ?>
                            <a href="./?post=<?=$post->id?>">
                                <div class="card col-12">
                                    <div class="row no-gutters">
                                        <div class="col-2">
                                            <img src="./includes/images/uploads/posts/260x260/<?=$post->p_image?>" class="card-img" alt="post image">
                                        </div>
                                        <div class="col-10">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= $post->p_title ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php
                    }
                } 
                else 
                    echo "<div class='alert alert-danger mb-5'><span>نتیجه ای یافت نشد!</span></div>";
                ?>

        </div>



        <!-- left bar -->
        <?php require_once "leftbar.php"; ?>



    </div>
</div>



