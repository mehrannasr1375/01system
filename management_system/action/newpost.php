<?php
/* *******************************************************************************************************************
 *       ACTION = NEWPOST -> ALWAYS EXISTS ON THIS SCRIPT
 *       DO = EDITPOST      (USE WITH BOTTOM ITEM)
 *       ID = X             (USE WITH TOP ITEM)
 * ******************************************************************************************************************/

if (!(isset($_SESSION['u_id']) or $_SESSION['u_type']==1 or $_SESSION['u_type']==2)) {
    return;
}

// insert or update post
elseif (isset($_POST['send_post'])) {
    $error           = "";
    $p_title         = $_POST['p_title'];
    $p_content       = $_POST['p_content'];
    $p_image         = (isset($_POST['post-img-name']) && $_POST['post-img-name']!=0) ? $_POST['post-img-name']:POST_IMG_DEFAULT;
    $published       = isset($_POST['published']) ? 1:0;
    $allow_comments  = isset($_POST['allow_comments']) ? 1:0;
    $cats            = [];
    foreach (Cat::getAllCats() as $category) {
        $cat = "cat".$category->id;
        if (isset($_POST[$cat]))
            $cats[] = $category->id;
    }

    // update post
    if (isset($_GET['do']) && $_GET['do']=='editpost') {
        $res = (int)Post::updatePost($_GET['id'], $p_title, $p_content,$p_image,$_SESSION['u_id'],$published,$allow_comments,$cats);
        if ($res)
            header("Location:./cpanel.php?action=newpost&do=editpost&id=".$_GET['id']."&status=success");
        //show show posts page if update was not successfull
        else
            header("Location:./cpanel.php?action=showposts");
    }

    // insert post
    else if (isset($_GET['action']) && $_GET['action'] == 'newpost') {
        $res = (int)Post::insertPost($p_title,$p_content,$p_image,$_SESSION['u_id'],$published,$allow_comments,$cats);
        //show edit post page if insert was successfull
        if ($res)
            header("Location:./cpanel.php?action=newpost&do=editpost&id=$res&status=success");

        else { //show new post page if insert was not successfull (error in insert)
            $p_title          =  isset($_POST['p_title']) ? $_POST['p_title']:'';
            $p_content        =  isset($_POST['p_content']) ? $_POST['p_content']:'';
            $published        =  isset($_POST['published']) ? 'checked':$published='unchecked';
            $allow_comments   =  isset($_POST['allow_comments']) ? 'checked':'unchecked';

            show_form('new',null,$res,$p_title,$p_content,null,null,$published,$allow_comments);
        }
    }
}

// show edit post form
elseif (isset($_GET['do']) and $_GET['do']=='editpost' and isset($_GET['id'])) {
    show_form('edit', $_GET['id'], "");
}

// show insert post form on default
else
    show_form('new', null, "");


function show_form($action, $post_id=0, $error='', $p_title='', $p_content='', $p_image='', $cats=[], $published='checked', $allow_comments='checked')
{
    if ($action=='new') {
        $status         = "ایجاد پست جدید";
    }
    elseif ($action=='edit') {
        $status         =  "ویرایش";
        $post_object    =  Post::getPostById($post_id);
        $p_title        =  $post_object->p_title;
        $p_content      =  $post_object->p_content;
        $p_image        =  $post_object->p_image ? $post_object->p_image : 0;
        $published      =  $post_object->published ? 'checked' : '';
        $allow_comments =  $post_object->allow_comments ? 'checked' : '';
    }
    ?>
    <div class="statusbar"><i class="fa fa-plus-circle fa-2x d-inline-block"></i><span class="statusbar-p"><?=$status?></span></div>

    <!-- Show Images Modal Btn -->
    <button type="button" class="btn btn-outline-info img-btn" id="show_images-btn" data-toggle="modal" data-target="#show-images-modal">آخرین تصاویر</button>
    <!-- show Images Modal -->
    <?php
    $pics_count = Pic::getPicsCountOfUser($_SESSION['u_id']);
    $part = isset($_GET['part']) ? $_GET['part']:1;
    $pics = Pic::getPicsOfUser($_SESSION['u_id'], MAX_PICS_GRID, $part*10-10);
    ?>
    <div class="modal fade" id="show-images-modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header ">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="img-container">
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
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="newpost">
        <form method="post" enctype="multipart/form-data">

            <!-- Error Handling -->
            <?php if($error!="") {echo "<p class='failure-res'>$error</p>"; } ?>
            <?php if(isset($_GET['status']) and $_GET['status']=="success") {echo "<p class='success-res'>تغییرات با موفقیت ثبت گردید. در صورت تمایل می توانید آن را ویرایش نمایید!</p>"; } ?>

            <!-- Post Title -->
            <div class="form-group my-4">
                <label class="col-form-label b-homa m-2">عنوان پست :</label>
                <input type="text" class="form-control" name="p_title" value="<?=$p_title?>"/>
            </div>

            <!-- Post Body -->
            <div class="form-group mb-5">
                <label class="col-form-label b-homa m-2">متن پست :</label>
                <textarea class="form-control" id="area" name="p_content"><?=$p_content?></textarea>
            </div>

            <!-- Post Cats -->
            <div class="mb-5 mr-4">
                <label class="b-homa">گروه ها :</label>
                <div id="cats-container">
                    <?php
                    $cats = Cat::getCatsByParentId(1);
                    if($action=='new') {
                        foreach ($cats as $cat) {
                            ?>
                            <div class="custom-control custom-checkbox shifttoleft1">
                                <input type="checkbox" name="cat<?=$cat->id?>" class="custom-control-input" id="<?=$cat->id?>" value="<?=$cat->id?>"/>
                                <label class="custom-control-label" for="<?=$cat->id?>"><?=$cat->cat_name?></label>
                            </div>
                        <?php
                            if ($cats = Cat::getCatsByParentId($cat->id)){
                                foreach ($cats as $cat) {
                                    ?>
                                    <div class="custom-control custom-checkbox shifttoleft2">
                                        <input type="checkbox" name="cat<?=$cat->id?>" class="custom-control-input" id="<?=$cat->id?>" value="<?=$cat->id?>"/>
                                        <label class="custom-control-label" for="<?=$cat->id?>"><?=$cat->cat_name?></label>
                                    </div>
                                    <?php
                                    if ($cats = Cat::getCatsByParentId($cat->id)){
                                        foreach ($cats as $cat) {
                                            ?>
                                            <div class="custom-control custom-checkbox shifttoleft3">
                                                <input type="checkbox" name="cat<?=$cat->id?>" class="custom-control-input" id="<?=$cat->id?>" value="<?=$cat->id?>"/>
                                                <label class="custom-control-label" for="<?=$cat->id?>"><?=$cat->cat_name?></label>
                                            </div>
                                            <?php
                                            if ($cats = Cat::getCatsByParentId($cat->id)){
                                                foreach ($cats as $cat) {
                                                    ?>
                                                    <div class="custom-control custom-checkbox shifttoleft4">
                                                        <input type="checkbox" name="cat<?=$cat->id?>" class="custom-control-input" id="<?=$cat->id?>" value="<?=$cat->id?>"/>
                                                        <label class="custom-control-label" for="<?=$cat->id?>"><?=$cat->cat_name?></label>
                                                    </div>
                                                    <?php
                                                    if ($cats = Cat::getCatsByParentId($cat->id)){
                                                        foreach ($cats as $cat) {
                                                            ?>
                                                            <div class="custom-control custom-checkbox shifttoleft5">
                                                                <input type="checkbox" name="cat<?=$cat->id?>" class="custom-control-input" id="<?=$cat->id?>" value="<?=$cat->id?>"/>
                                                                <label class="custom-control-label" for="<?=$cat->id?>"><?=$cat->cat_name?></label>
                                                            </div>
                                                            <?php
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    elseif ($action=='edit') {
                        if(!empty($cats)) {
                            $tmp = Post::getPostById($post_id)->cats;
                            foreach ($cats as $cat) {
                                $actived = in_array($cat->id, $tmp) ? " checked='checked' " : " " ;
                                ?>
                                <div class="custom-control custom-checkbox shifttoleft1">
                                    <input type="checkbox" name="cat<?=$cat->id?>" class="custom-control-input" id="<?=$cat->id?>" value="<?=$cat->id?>" <?=$actived?>/>
                                    <label class="custom-control-label" for="<?=$cat->id?>"><?=$cat->cat_name?></label>
                                </div>
                                <?php
                                if ($cats = Cat::getCatsByParentId($cat->id)){
                                    foreach ($cats as $cat) {
                                        $actived = in_array($cat->id, $tmp) ? " checked='checked' " : " " ;
                                        ?>
                                        <div class="custom-control custom-checkbox shifttoleft2">
                                            <input type="checkbox" name="cat<?=$cat->id?>" class="custom-control-input" id="<?=$cat->id?>" value="<?=$cat->id?>" <?=$actived?>/>
                                            <label class="custom-control-label" for="<?=$cat->id?>"><?=$cat->cat_name?></label>
                                        </div>
                                        <?php
                                        if ($cats = Cat::getCatsByParentId($cat->id)){
                                            foreach ($cats as $cat) {
                                                $actived = in_array($cat->id, $tmp) ? " checked='checked' " : " " ;
                                                ?>
                                                <div class="custom-control custom-checkbox shifttoleft3">
                                                    <input type="checkbox" name="cat<?=$cat->id?>" class="custom-control-input" id="<?=$cat->id?>" value="<?=$cat->id?>" <?=$actived?>/>
                                                    <label class="custom-control-label" for="<?=$cat->id?>"><?=$cat->cat_name?></label>
                                                </div>
                                                <?php
                                                if ($cats = Cat::getCatsByParentId($cat->id)){
                                                    foreach ($cats as $cat) {
                                                        $actived = in_array($cat->id, $tmp) ? " checked='checked' " : " " ;
                                                        ?>
                                                        <div class="custom-control custom-checkbox shifttoleft4">
                                                            <input type="checkbox" name="cat<?=$cat->id?>" class="custom-control-input" id="<?=$cat->id?>" value="<?=$cat->id?>" <?=$actived?>/>
                                                            <label class="custom-control-label" for="<?=$cat->id?>"><?=$cat->cat_name?></label>
                                                        </div>
                                                        <?php
                                                        if ($cats = Cat::getCatsByParentId($cat->id)){
                                                            foreach ($cats as $cat) {
                                                                $actived = in_array($cat->id, $tmp) ? " checked='checked' " : " " ;
                                                                ?>
                                                                <div class="custom-control custom-checkbox shifttoleft5">
                                                                    <input type="checkbox" name="cat<?=$cat->id?>" class="custom-control-input" id="<?=$cat->id?>" value="<?=$cat->id?>"  <?=$actived?>/>
                                                                    <label class="custom-control-label" for="<?=$cat->id?>"><?=$cat->cat_name?></label>
                                                                </div>
                                                                <?php
                                                                if ($cats = Cat::getCatsByParentId($cat->id)){
                                                                    foreach ($cats as $cat) {
                                                                        $actived = in_array($cat->id, $tmp) ? " checked='checked' " : " " ;
                                                                        ?>
                                                                        <div class="custom-control custom-checkbox shifttoleft5">
                                                                            <input type="checkbox" name="cat<?=$cat->id?>" class="custom-control-input" id="<?=$cat->id?>" value="<?=$cat->id?>"  <?=$actived?>/>
                                                                            <label class="custom-control-label" for="<?=$cat->id?>"><?=$cat->cat_name?></label>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </div>

            <!-- Image -->
            <div class="mb-5">
                <label class="text-sm m-2 b-homa">تصویر شاخص : ( فرمت های قابل قبول: jpg, jpeg, gif, png  )</label>
                <div>
                    <div style="display:none;">
                        <input type="file" id="btn-real-upload" class="custom-file-input" name="p_image" accept="image/png,image/jpg,image/jpeg,image/gif"/>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="d-flex mt-2" id="upload-photo-post">
                        <a class="btn-upload" id="btn-fake-upload"><i class="fa fa-upload"></i>آپلود تصویر </a>
                        <div class="pb-3">
                            <i id="post-img-upload-status" class="fa fa-2x"></i>
                        </div>

                        <p id="upload-res-text" class="upload-res-text" style="direction:ltr;"><?php if ($p_image == 0) { echo "! هنوز تصویری انتخاب نشده است"; } else { echo "$p_image"; }  ?></p>

                    </div>
                </div>
            </div>

            <!-- Image name variable -->
            <input type="hidden" name="post-img-name" id="post-img-name" value="<?=$p_image?>"/>

            <!-- Publish Post Check Box -->
            <div class="mb-3">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="chk_published" name="published" <?=$published?>/>
                    <label class="custom-control-label mr-2 b-homa" for="chk_published">پست انتشار یابد .</label>
                </div>
            </div>

            <!-- Allow Comments Check Box -->
            <div class="mb-5">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="chk_allow_comments" name="allow_comments" <?=$allow_comments?>/>
                    <label class="custom-control-label mr-2 b-homa" for="chk_allow_comments">ارسال نظر برای این پست فعال باشد .</label>
                </div>
            </div>

            <!-- Post Send -->
            <div class="form-group">
                <button type="submit" class="btn btn-outline-info text-light-2 b-homa" name="send_post">ارسال</button>
            </div>

        </form>
    </div>
    <script>document.addEventListener('DOMContentLoaded',function(event){$("#l3").siblings().removeClass("active");$("#l3").addClass("active");})</script>
    <?php
}
?>
