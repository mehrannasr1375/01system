<?php
    $world = trim($_GET['s']);
    $world = strip_tags($_GET['s']);
    $world = htmlspecialchars($world);
    $last_posts = Post::searchPosts($world, true, true, true, 0, 10);


    echo '
        <script>
            $("#navbar").css("background-color"
        </script>
    ';
?>




<div id="search-posts-parent" class="container">
    

    <div class="post-con">

    </div>

    <div class="post-con">
        
    </div>

    <div class="post-con">
        
    </div>


</div>



