<?php
/* *************************************************************************************************************************
 *  ACTION = MULTIMEDIA
 *  PART = X
 ***************************************************************************************************************************/
$pics_count = Pic::getPicsCountOfUser($_SESSION['u_id']);
if (isset($_GET['part']))
    $part = $_GET['part'];
else
    $part = 1;
$start        =  $part*10-10;
$pics         =  Pic::getPicsOfUser($_SESSION['u_id'],MAX_PICS_GRID, $start);
$picsSections =  ceil((int)$pics_count/MAX_PICS_GRID);
?>
<div class="statusbar"><i class="fa fa-picture-o fa-2x d-inline-block"></i><span class="statusbar-p">چندرسانه ای</span></div>
<div id="multimedia">

    <div id="draggable-area" class="p-4"> <!--GRAGGABLE AREA-->
<!--        <div id="drag-text">تصویر را به اینجا بکشید و رها کنید +</div>-->
        <div style="display:none;">
            <input type="file" id="upload-pic-real" class="custom-file-input" name="pic" accept="image/png,image/jpg,image/jpeg,image/gif"/>
        </div>
        <div>
            <a id="upload-pic-fake" class="btn-upload py-2 px-4"><i class="fa fa-upload"></i>آپلود تصویر </a>
            <i id="pic-res-i" class="fa fa-2x"></i>
            <p id="pic-res-text" class="p-1">تصویر مورد نظر را انتخاب نمایید</p>
        </div>
    </div> <!--DRAGGABLE AREA-->

    <div class="mr-3 mt-5"> <!--PAGINATION-->
        <ul class="pagination pagination-sm">
            <?php
            if ($part == 1)
                echo "<li class='page-item disabled'><a class='page-link' href=''>قبلی</a></li>";
            else {
                $j = $part-1;
                echo "<li class='page-item'><a class='page-link' href='../management_system/cpanel.php?action=multimedia&part=$j'>قبلی</a></li>";
            }
            for ($i=1; $i<=$picsSections; $i++) {
                if($i == $part)
                    $class = "active";
                else
                    $class = "";
                echo "<li class='page-item $class'><a class='page-link' href='../management_system/cpanel.php?action=multimedia&part=$i'>$i</a></li>";
            }
            if ($part == $picsSections)
                echo "<li class='page-item disabled'><a class='page-link' href=''>بعدی</a></li>";
            else {
                $j = $part+1;
                echo "<li class='page-item'><a class='page-link' href='../management_system/cpanel.php?action=multimedia&part=$j'>بعدی</a></li>";
            }
            ?>
        </ul>
    </div> <!--PAGINATION-->

    <div id="img-container"> <!--IMAGES GRID-->
        <?php
        if ($pics_count == 0) {
            echo "<p id='no-img' class='margin-auto text-black-50'>تصویری وجود ندارد!</p>";
        } else {
            foreach ($pics as $pic) {
                $pl1 = "/includes/images/uploads/multimedia/thumbnail/$pic->pic_name";
                $pl2 = "/includes/images/uploads/multimedia/4x3/$pic->pic_name";
                $pl3 = "/includes/images/uploads/multimedia/16x9/$pic->pic_name";
                ?>
                <div class='img-frame'>
                    <div class='img-pic-container'>
                        <img class='pic-preview' src='<?=$pl1?>' />
                        <div class='img-overlay'>
                            <span data-link="<?=$pl1?>" class='overlay-item'>2*2</span>
                            <span data-link="<?=$pl2?>" class='overlay-item'>3*4</span>
                            <span data-link="<?=$pl3?>" class='overlay-item'>9*16</span>
                            <span data-link="" class='overlay-item' >حذف</span>
                        </div>
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div> <!--IMAGES GRID-->

</div>
<script> document.addEventListener('DOMContentLoaded', function(event) { $("#l5").siblings().removeClass("active"); $("#l5").addClass("active"); }) </script>