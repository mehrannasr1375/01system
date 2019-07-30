<?php
    $world = trim($_POST['search-text']);
    $world = htmlspecialchars($world, ENT_QUOTES);
    $last_posts = Post::searchPosts($world, true, true, true, 0, 10);
    $count = count($last_posts);
?>




<div class="container pt-6">
    <div class="row">




        <!-- right bar -->
        <div id="search-posts-parent" class="col-9">
            <?php
                if ($last_posts)
                {
                    echo "<div class='alert alert-info'><p>"
                        . $count
                        . " نتیجه یافت شد! </p></div>";
                    foreach ($last_posts as $post) {
                        if ($pos = strpos($post->p_content, "--more--"))
                            $content = substr($post->p_content,0, $pos);
                        else
                            $content = substr($post->p_content,0,300);
                        $rate = $post->p_rate;
                        ?>
                        <a href="/index.php?post=<?=$post->id?>">
                            <div class="card col-6">
                                <div class="row no-gutters">
                                    <div class="col-md-4">
                                        <img src="/01system/includes/images/uploads/posts/260x260/1548251662.392394052.jpg" class="card-img" alt="...">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= $post->p_title ?></h5>
                                            <p class="card-text"></p>
                                            <p class="card-text"><small class="text-muted"></small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php
                    }
                }
                else {
                    echo "
                        <div class='alert alert-danger'>
                            <span class='font-weight-bold'>خطا : </span>
                            <span>نتیجه ای یافت نشد!</span>
                        </div>
                    ";
                }
                ?>

        </div>




        <!-- left bar -->
        <?php
            require_once "leftbar.php";
        ?>






    </div>
</div>



