<?php
    $last_posts = Post::getLastPosts(5);
    $top_posts  = Post::getTopPosts(5);
?>


<!-- left sidebar -->
<div id="leftbar" class="col-12 col-lg-3 bg-light">


    <!-- search -->
    <div class="left-part">
        <div class="left-part-header">
            <p>جستجو در مطالب</p>
        </div>
        <form method="post" action="/01system/index.php" class="m-0 p-0">
            <div class="left-part-body">
                <div class="input-group">
                    <input type="text" id="search" name="search-text" class="form-control" placeholder="جستجو کنید ..." autocomplete="off"/>
                    <div class="input-group-append">
                        <button id="btn-search" name="btn-search" class="input-group-text"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <!-- last posts -->
    <div class="left-part">
        <div class="left-part-header">
            <p>آخرین مطالب</p>
        </div>
        <div class="left-part-body">
            <?php
            if (count((array)$last_posts)>=1 && $last_posts!==false) {
                foreach ($last_posts as $last_post) {
                    ?>
                    <a href="/?post=<?=$last_post->id?>" class="left-post-con d-flex">
                        <img class="left-post-img" src="includes/images/uploads/posts/260x260/<?=$last_post->p_image?>" />
                        <div class="left-post-title">
                            <p><?=$last_post->p_title?></p>
                            <span></span>
                        </div>
                    </a>
                    <?php
                }
            }
            ?>
        </div>
    </div>


    <!-- top posts -->
    <div class="left-part">
        <div class="left-part-header">
            <p>برترین مطالب</p>
        </div>
        <div class="left-part-body">
            <?php
            if (count((array)$top_posts)>=1 && $top_posts!==false) {
                foreach ($top_posts as $top_post) {
                    ?>
                    <a href="/?post=<?=$top_post->id?>" class="left-post-con d-flex">
                        <img class="left-post-img" src="includes/images/uploads/posts/260x260/<?=$top_post->p_image?>" />
                        <div class="left-post-title">
                            <p><?=$top_post->p_title?></p>
                            <span></span>
                        </div>
                    </a>
                    <?php
                }
            }
            ?>
        </div>
    </div>


</div>