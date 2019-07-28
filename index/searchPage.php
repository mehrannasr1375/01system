<?php

    $world = trim($_GET['s']);
    $world = htmlspecialchars($world, ENT_QUOTES);
    $last_posts = Post::searchPosts($world, true, true, true, 0, 10);


    echo '
        <script>
            $("#navbar").css("background-color", "black");
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



