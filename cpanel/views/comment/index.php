<div id="showcomments">




    <!-- page title --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
    <div class="d-flex justify-content-start pt-2">
        <i class="fa fa-2x fa-comments-o text-secondary"></i>
        <span style="font-size:13px; margin-right:10px;">نظرات</span>
        <span class="text-vvsm text-info mr-3">( <?=$comments_count?> )</span>
    </div>
    <hr class="mb-4">




    <!-- comments list ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------>
    <div class="row">
        <div class="col">
            <div id="coms-container">
                <?php
                if ($comments){
                    foreach ($comments as $comment){ ?>



                        <!-- comment frame ----------------------------------------------------------------------------------------------------------------------------------------------------------------->
                        <div class="com-frame <?= ($comment->parent_id == 0) ? '' : 'mr-5' ; ?>" >


                            <!-- comment header ----------------------------------------------------------------------------------------------------------------------------------------------------------------->
                            <div class="com-header">

                                <!-- comment id -->
                                <span class="text-black-50 ml-4">
                                    <i class="fa fa-hashtag"></i>
                                    <span><?=$comment->id?></span>
                                </span>

                                <!-- comment full name -->
                                <span class="mr-5">
                                    <i class="fa fa-user"></i>
                                    <span><?=$comment->full_name?></span>
                                </span>

                                <!-- comment post title -->
                                <span class="mr-5">
                                    <i class="fa fa-ticket"></i>
                                    <a href="../../../index.php?post=<?=$comment->post_id?>"><?=$comment->post_title?></a>
                                </span>

                                <!-- comment date -->
                                <span>
                                    <i class="fa fa-calendar"></i>
                                    <?php
                                    $hijri = convertDate($comment->time);
                                    echo $hijri['year']."/".$hijri['month_num']."/".$hijri['day']."\t";
                                    ?>
                                </span>

                                <!-- comment time -->
                                <span>
                                    <i class="fa fa-clock-o"></i>
                                    <?= $hijri['hour'].":".$hijri['minute']; ?>
                                </span>

                            </div>


                            <!-- comment body ------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                            <div class="com-body">

                                <!-- parent comment -->
                                <div class="com-rep-part <?= ($comment->parent_id == 0) ? 'd-none' : '' ?>">
                                    <i class="fa fa-reply"></i>
                                    <span class="ml-3">
                                        <?= ($comment->parent_id != 0) ? "#".$comment->parent_id : '' ?>
                                    </span>
                                    <?= $comment->parent_c_text ?>
                                </div>

                                <!-- comment text -->
                                <div class="p-4"><?= $comment->c_text ?></div>

                            </div>


                            <!-- comment footer ----------------------------------------------------------------------------------------------------------------------------------------------------------------->
                            <div class="com-footer d-flex justify-content-end">
                                <a href="./cpanel.php?action=comments&do=destroy&id=<?=$comment->id?>" class="btn-com btn-com-delete header-red">حذف</a>
                                <a href="#" class="btn-com btn-com-reply header-aqua">پاسخ</a>
                                <a href="./cpanel.php?action=comments&do=publish&id=<?=$comment->id?>" class="btn-com btn-com-publish header-blue">انتشار</a>
                            </div>


                        </div>

                        


                    <?php
                    }
                }
                else { ?>
                    <div class="d-flex justify-content-center flex-row p-5">
                        <i class='fa fa-3x fa-frown-o title-aqua'></i>
                        <p class='p-2 text-center title-aqua' style="font-size: 18px;">اینجا خبری نیست!</p>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>




    <!-- pagination --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
    <?php
    if ($comments){?>
        <div class="d-flex justify-content-center my-5">
            <ul class="pagination pagination-sm">
                <?php
                $part = isset($_GET['part']) ? $_GET['part'] : 1;

                $prev = $part - 1;
                if ($part == 1)//back
                    echo "<li class='page-item disabled'><a class='page-link' href='./cpanel.php?action=comments&part={$prev}'>قبلی</a></li>";
                else
                    echo "<li class='page-item'><a class='page-link' href='./cpanel.php?action=comments&part={$prev}''>قبلی</a></li>";


                for ($i=1; $i<=ceil($comments_count / 8); $i++){
                    $active = ($i == $part) ? 'active' : '' ;
                    echo "<li class='page-item $active'><a class='page-link' href='./cpanel.php?action=comments&part={$i}'>{$i}</a></li>";
                }


                $next = $part + 1;
                if ($part == ceil($comments_count/8))//next
                    echo "<li class='page-item disabled'><a class='page-link' href='./cpanel.php?action=comments&part={$next}''>بعدی</a></li>";
                else
                    echo "<li class='page-item'><a class='page-link' href='./cpanel.php?action=comments&part={$next}' >بعدی</a></li>";

                ?>
            </ul>
        </div>
    <?php
    }
    ?>



</div>



<!---------------------------------------------------- SCRIPTS ------------------------------------------------------------------------------------------------------------------------------------------------->
<script>
    $(document).ready(function(event){
        $("#l8").siblings().removeClass("active"); $("#l8").addClass("active");
    })
</script>

