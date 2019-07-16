<?php
/* ***************************************************************************************************************
 *       ACTION = SHOWPOSTS -> ALWAYS EXISTS ON THIS SCRIPT
 *       DO = delete                  ||
 *       DO = publish                 ||
 *       DO = restoredelete           ||
 *       DO = permanentdelete         ||
 *       DO = unpublish               \/
 *       ID = X               (USE WITH TOP ITEM)
 *       (edit has been reffered to 'newpost.php')
 * **********************************************************************************************************************/
if (!(isset($_SESSION['u_id']) or $_SESSION['u_type']==1 or $_SESSION['u_type']==2)){
    return;
}

// delete post
elseif(isset($_GET['do']) and $_GET['do']=='delete' and isset($_GET['id'])){
    $res=Post::deletePostById($_GET['id'], false);
    if ($res)
        header("Location: ./cpanel.php?action=showposts");
}

// restore deleted post
elseif(isset($_GET['do']) and $_GET['do']=='restoredelete' and isset($_GET['id'])){
    $res = Post::restorePost($_GET['id']);
    if ($res)
        header("Location: ./cpanel.php?action=showposts");
}

// permanent delete post
elseif(isset($_GET['do']) and $_GET['do']=='permanentdelete' and isset($_GET['id']) ){
    $res=Post::deletePostById($_GET['id'], true);
    if ($res)
        header("Location: ./cpanel.php?action=showposts");
}

// publish post
elseif(isset($_GET['do']) and $_GET['do']=='publish' and isset($_GET['id'])){
    $res=Post::publishPost($_GET['id'],1);
    if ($res)
        header("Location: ./cpanel.php?action=showposts");
}

// unpublish post
elseif(isset($_GET['do']) and $_GET['do']=='unpublish' and isset($_GET['id'])){
    $res=Post::publishPost($_GET['id'],0);
    if ($res)
        header("Location: ./cpanel.php?action=showposts");
}

// default action on this page
else{
    showPosts();
}



function showPosts($msg="",$cssClass="success")
{
    $post_counts_array  =  Post::getPostsCounts();
    $publishedCount     =  $post_counts_array['published'];
    $unpublishedCount   =  $post_counts_array['unpublished'];
    $deletedCount       =  $post_counts_array['deleted']-1;
    if (!isset($posts))
        $posts = Post::getAllPosts(1, 0, MAX_POSTS_TABLE, 0);
    if (!isset($_GET['part']))
        $part = 1;
    elseif (isset($_GET['part']))
        $part = (int)$_GET['part'];
?>
    <div class="statusbar">
        <i class="fa fa-list fa-2x d-inline-block"></i><span class="statusbar-p">پست ها</span>
    </div>
    <?php
    if($msg != '')
        echo "<div class='$cssClass'>$msg</div>";
    ?>
    <div id="showposts">
        <div class="row py-2 px-3">
            <div class="col p-0 margin-auto"> <!--dropdown-->
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle btn-sm" type="button" data-toggle="dropdown">
                        نمایش بر اساس
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item btn-sm" href="./cpanel.php?action=showposts&published=published"><i class="fa fa-globe"></i>منتشر شده ها<span class="text-success"> <?=$publishedCount?> </span></a>
                        <a class="dropdown-item btn-sm" href="./cpanel.php?action=showposts&published=unpublished"><i class="fa fa-lock"></i>منتشر نشده ها<span class="text-success"> <?=$unpublishedCount?> </span></a>
                        <a class="dropdown-item btn-sm" href="./cpanel.php?action=showposts&deleted=deleted"><i class="fa fa-recycle"></i>زباله دان<span class="text-success"> <?=$deletedCount?> </span></a>
                    </div>
                </div>
            </div> <!--dropdown-->

            <div class="margin-auto">  <!--pagination-->
                <div class="col p-0">
                    <ul class="pagination pagination-sm">
                        <?php //@@  PAGINATION  @@
                        if (isset($_GET['published']) and $_GET['published']=='unpublished'){
                            if($part==1)//back
                                echo '<li class="page-item disabled"><a class="page-link" href="#">قبلی</a></li>';
                            else {
                                $j = $part-1;
                                echo "<li class='page-item'><a class='page-link' href='./cpanel.php?action=showposts&published=unpublished&part=$j'>قبلی</a></li>";
                            }

                            $unpublishedPostsSections = ceil((int)$unpublishedCount / MAX_POSTS_TABLE);
                            for($i=1; $i<=$unpublishedPostsSections; $i++) {
                                if($i == $part)
                                    $class = "active";
                                else
                                    $class = "";
                                echo "<li class='page-item $class'><a class='page-link' href='./cpanel.php?action=showposts&published=unpublished&part=$i'>$i</a></li>";
                            }

                            if($part==$unpublishedPostsSections)//next
                                echo "<li class='page-item disabled'><a class='page-link' href='#'>بعدی</a></li>";
                            else {
                                $j=$part+1;
                                echo "<li class='page-item'><a class='page-link' href='./cpanel.php?action=showposts&published=unpublished&part=$j'>بعدی</a></li>";
                            }
                        }

                        else {
                            if($part==1)
                                echo '<li class="page-item disabled"><a class="page-link" href="#">قبلی</a></li>';
                            else {
                                $j=$part-1;
                                echo "<li class='page-item'><a class='page-link' href='./cpanel.php?action=showposts&published=published&part=$j'>قبلی</a></li>";
                            }

                            $publishedPostsSections = ceil((int)$publishedCount / MAX_POSTS_TABLE);
                            for($i=1; $i<=$publishedPostsSections; $i++) {
                                if($i == $part)
                                    $class = "active";
                                else
                                    $class = "";
                                echo "<li class='page-item $class'><a class='page-link' href='./cpanel.php?action=showposts&published=published&part=$i'>$i</a></li>";
                            }

                            if($part==$publishedPostsSections)
                                echo "<li class='page-item disabled'><a class='page-link' href='#'>بعدی</a></li>";
                            else {
                                $j=$part+1;
                                echo "<li class='page-item'><a class='page-link' href='./cpanel.php?action=showposts&published=published&part=$j'>بعدی</a></li>";
                            }
                        }


                        ?>
                    </ul>
                </div>
            </div> <!--pagination-->
        </div>
        <div class="overflow-scroll-x"><!--posts table-->
            <div class="row">
                <div class="col p-0">
                    <table class="table">
                        <thead class="thead-light">
                        <tr class="text-center">
                            <th>#</th>
                            <th class="min-150">عنوان</th>
                            <th>نویسنده</th>
                            <th class="min-150">تاریخ انتشار</th>
                            <th class="min-150">آخرین تغییر</th>
                            <th class="min-190">اعمال</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        //@@=========   GET POST TABLE CONTENTS FROM DB   ==========@@
                        //GET POSTS OF 'PUBLISHED, UNPUBLISHED, DELETED' (find out from qs)
                        $recyclebin_page_active_flag = false;
                        $start = ($part-1) * MAX_POSTS_TABLE;
                        if(isset($_GET['published']) and $_GET['published']=='unpublished') {
                            $recyclebin_page_active_flag=false;
                            $posts = Post::getAllPosts(0, 0, MAX_POSTS_TABLE, $start);
                        }
                        elseif(isset($_GET['published']) and $_GET['published']=='published') {
                            $recyclebin_page_active_flag = false;
                            $posts = Post::getAllPosts(1, 0, MAX_POSTS_TABLE, $start);
                        }
                        elseif(isset($_GET['deleted']) and $_GET['deleted']=='deleted') {
                            $recyclebin_page_active_flag = true;
                            $p1= Post::getAllPosts(1, 1, MAX_POSTS_TABLE, $start);
                            $p2= Post::getAllPosts(0, 1, MAX_POSTS_TABLE, $start);
                            if (is_array($p1) && is_array($p2))
                                $posts = array_merge($p1, $p2);
                            else if (is_array($p1) && !is_array($p2))
                                $posts = $p1;
                            else if (is_array($p2) && !is_array($p1))
                                $posts = $p2;
                            else
                                $posts = array();
                        }

                        //  make rows for table if posts exists
                        if ($posts) {
                            $i = 1;
                            foreach ($posts as $post) {
                                $id            = $post->id;
                                $p_title       = $post->p_title;
                                $u_name        = $post->u_name;
                                $creation_time = convertDate($post->creation_time);
                                $last_modify   = convertDate($post->last_modify);
                                ?>
                                <tr class="text-center">
                                    <th><?=$i?></th>
                                    <td><?=$p_title?></td>
                                    <td><?=$u_name?></td>
                                    <td><?=$creation_time['year']."/".$creation_time['month_num']."/".$creation_time['day']." - ".$creation_time['hour'].":".$creation_time['minute']?></td>
                                    <td><?=$last_modify['year']  ."/".$last_modify['month_num']  ."/".$last_modify['day']  ." - ".$last_modify['hour']  .":".$last_modify['minute']?></td>
                                    <td>
                                        <?php
                                        if($recyclebin_page_active_flag) {?>
                                        <div class="btn-group">
                                            <a href="./cpanel.php?action=showposts&do=restoredelete&id=<?=$id?>" class="btn btn-vsm btn-outline-success btn-sm">بازگردانی</a>
                                            <a href="./cpanel.php?action=showposts&do=permanentdelete&id=<?=$id?>" class="btn btn-vsm btn-outline-danger btn-sm">حذف دائمی</a>
                                        </div>
                                        <?php
                                        }
                                        else {
                                        ?>
                                        <div class="btn-group">
                                            <a href="./cpanel.php?action=showposts&do=publish&id=<?=$id?>" class="btn btn-vsm btn-outline-success btn-sm">انتشار</a>
                                            <a href="./cpanel.php?action=showposts&do=unpublish&id=<?=$id?>" class="btn btn-vsm btn-outline-secondary btn-sm">مخفی</a>
                                            <a href="./cpanel.php?action=showposts&do=delete&id=<?=$id?>" class="btn btn-vsm btn-outline-danger btn-sm">حذف</a>
                                            <a href="./cpanel.php?action=newpost&do=editpost&id=<?=$id?>" class="btn btn-vsm btn-outline-primary btn-sm">ویرایش</a>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php
                                $i++;
                            }
                        }
                        else
                            echo "<p class='p-2 text-center'>پستی وجود ندارد </p>";
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!--posts table-->
    </div>

    <script> document.addEventListener('DOMContentLoaded', function(event) { $("#l2").siblings().removeClass("active"); $("#l2").addClass("active"); }) </script>
<?php
}
?>

