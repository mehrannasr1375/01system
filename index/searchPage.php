<?php
    $world = trim($_GET['s']);
    $world = htmlspecialchars($world, ENT_QUOTES);
    $last_posts = Post::searchPosts($world, true, true, true, 0, 10);
?>




<div class="container pt-6">
    <div class="row">




        <!-- right bar -->
        <div id="search-posts-parent" class="col-10 row">
            <?php
                if ($last_posts) {
                    foreach ($last_posts as $post) {
                        if ($pos = strpos($post->p_content, "--more--"))
                            $content = substr($post->p_content, 0, $pos);
                        else
                            $content = substr($post->p_content, 0, 300);
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
                } else {
                    echo "
                        <div class='badge'>
                            <p>نتیجه ای یافت نشد</p>
                        </div>
                    ";
                }
                ?>

        </div>




        <!-- left bar -->
        <div class="col-2">
            <?php
                require_once "leftbar.php";
            ?>
        </div>





    </div>
</div>



