<div id="showposts">



    <!-- page title ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
    <div class="d-flex justify-content-start pt-2">
        <i class="fa fa-2x fa-list text-secondary"></i>
        <span style="font-size:13px; margin-right:10px;">پست ها</span>
    </div>
    <hr class="mb-0">



    <!-- Tiny btns ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
    <div class="row mb-2">
        <div class="col">
            <ul class="tiny-btns nav nav-tabs nav-justified mt-4 active">
                <li class="nav-item">
                    <a href="./cpanel.php?action=showposts&publish=published" class="nav-link <?= !isset($_GET['publish']) ? 'active' : '' ?> <?= $published_status == 'published' ? 'active' : '' ?>"> منتشر شده ( <?= $publishedcount ?> )</a>
                </li>
                <li class="nav-item">
                    <a href="./cpanel.php?action=showposts&publish=unpublished" class="nav-link <?= $published_status == 'unpublished' ? 'active' : '' ?>"> منتشر نشده ( <?= $unpublishedcount ?> )</a>
                </li>
                <li class="nav-item">
                    <a href="./cpanel.php?action=showposts&publish=deleted" class="nav-link <?= $published_status == 'deleted' ? 'active' : '' ?>"> زباله دان ( <?= $deletedcount ?> )</a>
                </li>
            </ul>
        </div>
    </div>



    <!-- posts table --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
    <div class="overflow-scroll-x">
        <div class="row">
            <div class="col p-0">
                <table class="table tbl_show_posts">



                    <!-- make rows for table if posts exists -->
                    <?php
                    if ($posts)
                    { ?>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>شناسه</th>
                            <th class="text-right">عنوان</th>
                            <th>نویسنده</th>
                            <th style="width:82px;">تاریخ انتشار</th>
                            <th style="width:82px;">آخرین تغییر</th>
                            <th>اعمال</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 1;
                        foreach ($posts as $post){
                            $id = $post->id;
                            $creation_time = convertDate($post->creation_time);
                            $last_modify = convertDate($post->last_modify);
                            ?>
                            <tr class="text-center">
                                <th><?= $i ?></th>
                                <th><?= $id ?></th>
                                <td class="text-right"><?= $post->p_title ?></td>
                                <td><?= $post->u_name ?></td>
                                <td><?= $creation_time['year'] . "/" . $creation_time['month_num'] . "/" . $creation_time['day'] . " " . $creation_time['hour'] . ":" . $creation_time['minute'] ?></td>
                                <td><?= $last_modify['year']  . "/" . $last_modify['month_num']  . "/" . $last_modify['day']  . " " . $last_modify['hour']  . ":" . $last_modify['minute'] ?></td>
                                <td>
                                    <?php
                                    // show (un)published links or deleted links?
                                    if ($published_status == 'deleted') {
                                        ?>
                                        <div class="btn-group">
                                            <a href="./cpanel.php?action=showposts&do=restoredelete&id=<?= $id ?>" class="btn btn-vsm btn-outline-success btn-sm">بازگردانی</a>
                                            <a href="./cpanel.php?action=showposts&do=permanentdelete&id=<?= $id ?>" class="btn btn-vsm btn-outline-danger btn-sm">حذف دائمی</a>
                                        </div>
                                        <?php
                                    }
                                    else {
                                        ?>
                                        <div class="btn-group">
                                            <a href="./cpanel.php?action=showposts&do=publish&id=<?= $id ?>" class="btn btn-vsm btn-outline-success btn-sm">انتشار</a>
                                            <a href="./cpanel.php?action=showposts&do=unpublish&id=<?= $id ?>" class="btn btn-vsm btn-outline-secondary btn-sm">مخفی</a>
                                            <a href="./cpanel.php?action=showposts&do=delete&id=<?= $id ?>" class="btn btn-vsm btn-outline-danger btn-sm">حذف</a>
                                            <a href="./cpanel.php?action=editpost&id=<?= $id ?>" class="btn btn-vsm btn-outline-primary btn-sm">ویرایش</a>
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
                    else{
                        ?>
                        <div class="d-flex justify-content-center flex-row p-5">
                            <i class='fa fa-3x fa-frown-o title-aqua'></i>
                            <p class='p-2 text-center title-aqua' style="font-size: 18px;">اینجا خبری نیست!</p>
                        </div>
                        <?php
                    }
                    ?>



                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <!-- pagination ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
    <div class="d-flex justify-content-center my-5">
        <ul class="pagination pagination-sm">
            <?php
            // back
            if ($part == 1)
                echo '<li class="page-item disabled"><a class="page-link" href="#">قبلی</a></li>';
            else {
                $prev = $part - 1;
                echo "<li class='page-item'><a class='page-link' href='./cpanel.php?action=showposts&publish=$published_status&part=$prev'>قبلی</a></li>";
            }

            for ($i=1; $i<=$total_sections; $i++) {
                $class = ($i == $part) ? 'active' : '';
                echo "<li class='page-item $class'><a class='page-link' href='./cpanel.php?action=showposts&publish=$published_status&part=$i'>$i</a></li>";
            }

            // next
            if ($part == $total_sections)
                echo "<li class='page-item disabled'><a class='page-link' href='#'>بعدی</a></li>";
            else {
                $next = $part + 1;
                echo "<li class='page-item'><a class='page-link' href='./cpanel.php?action=showposts&publish=$published_status&part=$next'>بعدی</a></li>";
            }
            ?>
        </ul>
    </div>



</div>



<!---------------------------------------------------- SCRIPTS --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
<script>
    $(document).ready(function(event){
        $("#l3").siblings().removeClass("active");$("#l3").addClass("active");
    })
</script>