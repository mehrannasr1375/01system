<?php
$error = "";
?>
<!------------------------------------------------- PAGE CONTAINER --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
<div id="new-post" class="part row">



    <!-- right -->
    <div class="new-post-right col-9">


        <!-- page title -->
        <div class="d-flex justify-content-start pt-2">
            <i class="fa fa-2x fa-pencil-square-o text-secondary"></i>
            <span id="title">ویرایش پست شماره <?= $p_id ?></span>
        </div>
        <hr>


        <!-- main bar -->
        <div id="newpost">



            <!-- Post hidden id -->
            <input type="hidden" id="update_post_id" value="<?=$p_id?>" />



            <!-- Error Handling -->
            <?php
            if ($error != ""){
                echo "<p class='failure-res'>$error</p>";
            }
            if (isset($_GET['status']) and $_GET['status'] == "success"){
                echo "<p class='success-res'>تغییرات با موفقیت ثبت گردید. در صورت تمایل می توانید آن را ویرایش نمایید!</p>";
            }
            ?>


            <!-- Post Title -->
            <div class="form-group my-5">
                <input type="text" class="form-control" name="p_title" autocomplete="off" placeholder="عنوان پست" value="<?= $p_title ?>" />
            </div>


            <!-- Post Body -->
            <div class="form-group mb-5">
                <textarea class="form-control" id="area" name="p_content"><?= $p_content ?></textarea>
            </div>


            <!-- Cats & Tags -->
            <div class="row">

                <!-- Cats part -->
                <div class="col-6">
                    <div class="new-post-left-part">
                        <p class="n-p-l-title"><i class="fa fa-group"></i>گروه ها</p>
                        <hr class="my-0">
                        <div id="cats-container">
                            <?php
                            $in_db_cat_ids = [];
                            if ($cats)
                                foreach ($cats as $cat)
                                    $in_db_cat_ids[] = $cat->id;

                            if ($cats = PostMeta::allCategoriesByParent(0)) {
                                foreach ($cats as $cat) {
                                    $checked = (in_array($cat->id, $in_db_cat_ids)) ? 'checked' : '';
                                    ?>
                                    <div class="custom-control custom-checkbox shifttoleft1">
                                        <input type="checkbox" name="cats[]" class="custom-control-input" id="<?= $cat->id ?>" value="<?= $cat->id ?>" <?= $checked ?> />
                                        <label class="custom-control-label" for="<?= $cat->id ?>"><?= $cat->title ?></label>
                                    </div>
                                    <?php
                                    if ($cats = PostMeta::allCategoriesByParent($cat->id)) {
                                        foreach ($cats as $cat) {
                                            $checked = (in_array($cat->id, $in_db_cat_ids)) ? 'checked' : '';
                                            ?>
                                            <div class="custom-control custom-checkbox shifttoleft2">
                                                <input type="checkbox" name="cats[]" class="custom-control-input" id="<?= $cat->id ?>" value="<?= $cat->id ?>" <?= $checked ?> />
                                                <label class="custom-control-label" for="<?= $cat->id ?>"><?= $cat->title ?></label>
                                            </div>
                                            <?php
                                            if ($cats = PostMeta::allCategoriesByParent($cat->id)) {
                                                foreach ($cats as $cat) {
                                                    $checked = (in_array($cat->id, $in_db_cat_ids)) ? 'checked' : '';
                                                    ?>
                                                    <div class="custom-control custom-checkbox shifttoleft3">
                                                        <input type="checkbox" name="cats[]" class="custom-control-input" id="<?= $cat->id ?>" value="<?= $cat->id ?>" <?= $checked ?> />
                                                        <label class="custom-control-label" for="<?= $cat->id ?>"><?= $cat->title ?></label>
                                                    </div>
                                                    <?php
                                                    if ($cats = PostMeta::allCategoriesByParent($cat->id)) {
                                                        foreach ($cats as $cat) {
                                                            $checked = (in_array($cat->id, $in_db_cat_ids)) ? 'checked' : '';
                                                            ?>
                                                            <div class="custom-control custom-checkbox shifttoleft4">
                                                                <input type="checkbox" name="cats[]" class="custom-control-input" id="<?= $cat->id ?>" value="<?= $cat->id ?>" <?= $checked ?> />
                                                                <label class="custom-control-label" for="<?= $cat->id ?>"><?= $cat->title ?></label>
                                                            </div>
                                                            <?php
                                                            if ($cats = PostMeta::allCategoriesByParent($cat->id)) {
                                                                foreach ($cats as $cat) {
                                                                    $checked = (in_array($cat->id, $in_db_cat_ids)) ? 'checked' : '';
                                                                    ?>
                                                                    <div class="custom-control custom-checkbox shifttoleft5">
                                                                        <input type="checkbox" name="cats[]" class="custom-control-input" id="<?= $cat->id ?>" value="<?= $cat->id ?>" <?= $checked ?> />
                                                                        <label class="custom-control-label" for="<?= $cat->id ?>"><?= $cat->title ?></label>
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
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Tags part -->
                <div class="col-6">
                    <div class="new-post-left-part">
                        <p class="n-p-l-title"><i class="fa fa-tags"></i>تگ ها</p>
                        <hr class="my-0">
                        <div id="tags-container">
                            <?php
                            $in_db_tag_ids = [];
                            if ($tags)
                                foreach ($tags as $tag)
                                    $in_db_tag_ids[] = $tag->id;
                            if ($tags = PostMeta::allTags()) {
                                foreach ($tags as $tag) {
                                    $checked = (in_array($tag->id, $in_db_tag_ids)) ? 'checked' : '';
                                    ?>
                                    <div class="custom-control custom-checkbox shifttoleft-tag">
                                        <input type="checkbox" name="tags[]" class="custom-control-input" id="<?= $tag->id ?>" value="<?= $tag->id ?>" <?= $checked ?>/>
                                        <label class="custom-control-label" for="<?= $tag->id ?>"><?= $tag->title ?></label>
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


    </div>



    <!-- left -->
    <div class="new-post-left col-3">



        <!-- publish part -->
        <div class="new-post-left-part">

            <!-- part title -->
            <p class="n-p-l-title"><i class="fa fa-save"></i>ثبت</p>
            <hr class="mt-0">

            <!-- part body -->
            <div class="n-p-l-body">
                <!-- chk publish -->
                <div class="d-flex justify-content-start">
                    <div class="d-inline-block"><i class="fa fa-bullhorn pr-2"></i></div>
                    <div class="custom-control custom-checkbox pr-0" style="direction: ltr !important;">
                        <input type="checkbox" class="custom-control-input" id="chk_published" name="chk_published" <?=$published?> />
                        <label class="custom-control-label" for="chk_published">انتشار</label>
                    </div>
                </div>
                <!-- chk allow comment -->
                <div class="d-flex justify-content-start">
                    <div class="d-inline-block"><i class="fa fa-commenting pr-2"></i></div>
                    <div class="custom-control custom-checkbox pr-0" style="direction: ltr !important;">
                        <input type="checkbox" class="custom-control-input" id="chk_allow_comments" name="chk_allow_comments" <?=$allow_comments?> />
                        <label class="custom-control-label" for="chk_allow_comments">قابلیت ارسال نظر</label>
                    </div>
                </div>
                <!-- post visability -->
                <div class="d-flex justify-content-start flex-column mb-3">
                    <div class="d-flex justify-content-start">
                        <div class="d-inline-block"><i class="fa fa-eye-slash pr-2"></i></div>
                        <p>قابل مشاهده برای : </p>
                    </div>
                    <div class="mr-4 p-2 border-right" style="border-width: 3px !important;">

                        <div class="custom-control custom-radio text-vvsm">
                            <input type="radio" class="custom-control-input" id="rdo_visibility-all" name="rdo_access_level" value="0" <?= $access_level == 0 ? 'checked' : '' ?> >
                            <label class="custom-control-label" for="rdo_visibility-all">تمامی کاربران</label>
                        </div>
                        <div class="custom-control custom-radio text-vvsm">
                            <input type="radio" class="custom-control-input" id="rdo_visibility-admins" name="rdo_access_level" value="1" <?= $access_level == 1 ? 'checked' : '' ?> >
                            <label class="custom-control-label" for="rdo_visibility-admins">مدیرها</label>
                        </div>
                        <div class="custom-control custom-radio text-vvsm">
                            <input type="radio" class="custom-control-input" id="rdo_visibility-editors" name="rdo_access_level" value="2" <?= $access_level == 2 ? 'checked' : '' ?> >
                            <label class="custom-control-label" for="rdo_visibility-editors"> ویرایشگرها</label>
                        </div>
                        <div class="custom-control custom-radio text-vvsm">
                            <input type="radio" class="custom-control-input" id="rdo_visibility-admins-editors" name="rdo_access_level" value="3" <?= $access_level == 3 ? 'checked' : '' ?> >
                            <label class="custom-control-label" for="rdo_visibility-admins-editors">مدیرها و ویرایشگرها</label>
                        </div>

                    </div>
                </div>
                <hr class="mb-1">
                <!-- btn save -->
                <div class="d-flex justify-content-between">
                    <i class="fa fa-2x mr-2" id="new_post_i"></i>
                    <div class="form-group d-flex justify-content-end mb-0">
                        <button type="button" id="btn_save_post" data-action="create" class="btn btn-sm btn-info text-vsm">
                            <i class="fa fa-save"></i>
                            ذخیره
                        </button>
                    </div>
                </div>
            </div>

        </div>



        <!-- Image part -->
        <div class="new-post-left-part">

            <!-- part title -->
            <p class="n-p-l-title"><i class="fa fa-picture-o"></i>تصویر شاخص</p>
            <hr class="my-0">

            <!-- part body -->
            <div class="n-p-l-body">

                <div>
                    <div style="display:none;">
                        <input type="file" id="btn-real-upload" class="custom-file-input" name="p_image" accept="image/png,image/jpg,image/jpeg,image/gif" />
                    </div>
                </div>
                <!-- image name variable -->
                <input type="hidden" name="post-img-name" id="post-img-name" value="<?= $p_image_name ?>"/>
                <!-- result text -->
                <div id="post-img-upload-box">
                    <img src="<?= $p_image_thumbnail ?>" >
                    <p id="post-img-upload-text" class="post-img-upload-box">هنوز تصویری انتخاب نشده است</p>
                </div>
                <!-- btn upload -->
                <hr class="mt-0 mb-1">
                <div class="d-flex justify-content-between">
                    <i class="fa fa-2x fa-cloud-upload" id="post-img-upload-i"></i>
                    <input type="button" class="form-control btn btn-outline-secondary-2" id="btn-fake-upload" value="آپلود تصویر">
                </div>

            </div>

        </div>



        <!-- create category part -->
        <div class="new-post-left-part">
            <p class="n-p-l-title"><i class="fa fa-group"></i>ایجاد گروه</p>
            <hr class="my-0">
            <div class="p-2">
                <div id="new-cat">

                    <div class="form-group mt-2">
                        <input class="form-control form-control-sm input-create-category" type="text" id="new_cat_title" placeholder="عنوان گروه" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <select class="form-control form-control-sm input-create-category" id="new_cat_parent_id">
                            <option value="0">بدون والد</option>
                            <?php
                            $output = "";
                            if ($cats = PostMeta::allCategoriesByParent(0)) {
                                foreach ($cats as $cat) {
                                    $output .= "<option value='$cat->id'>$cat->title</option>";
                                    if ($cats2 = PostMeta::allCategoriesByParent($cat->id)) {
                                        foreach ($cats2 as $cat) {
                                            $output .= "<option value='$cat->id'> _____ $cat->title</option>";
                                            if ($cats3 = PostMeta::allCategoriesByParent($cat->id)) {
                                                foreach ($cats3 as $cat) {
                                                    $output .= "<option value='$cat->id'> ............. $cat->title</option>";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            echo $output;
                            ?>
                        </select>
                    </div>
                    <hr class="mb-2">
                    <div class="mb-0">
                        <input type="button" class="form-control btn btn-outline-secondary-2" id="btn_send_cat" value="ذخیره" />
                    </div>

                </div>
            </div>
        </div>



        <!-- create tag part -->
        <div class="new-post-left-part">
            <p class="n-p-l-title"><i class="fa fa-tag"></i>ایجاد تگ</p>

            <hr class="my-0">
            <div class="p-2">
                <div id="new-cat">

                    <div class="form-group mt-2">
                        <input class="form-control form-control-sm input-create-category" type="text" id="new_tag_title" placeholder="عنوان تگ" autocomplete="off">
                    </div>
                    <hr class="mb-2">
                    <div class="mb-0">
                        <input type="button" class="form-control btn btn-outline-secondary-2" id="btn_send_tag" value="ذخیره" />
                    </div>

                </div>
            </div>
        </div>



    </div>



</div>



<!---------------------------------------------------- SCRIPTS ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
<script>
    $(document).ready(function(event){
        // right menu active item
        $("#l3").siblings().removeClass("active");$("#l4").addClass("active");

        // save post via ajax
        $('#btn_save_post').click(function(event){
            let tags_array = [];
            let cats_array = [];
            $("input[name='tags[]']:checked").each(function(i){
                tags_array[i] = $(this).val();
            });
            $("input[name='cats[]']:checked").each(function(i){
                cats_array[i] = $(this).val();
            });
            $("#new_post_i").removeClass('fa-check fa-close fa-cloud-upload processing-spinner fa-tick text-danger');
            let fields = $('#new-post');
            $.ajax({
                url: './controllers/post/store.php',
                method: 'POST',
                data:{
                    'action': 'edit',
                    'p_id': $("#update_post_id").val(),
                    'p_title': fields.find("[name='p_title']").val(),
                    'p_content': CKEDITOR.instances['area'].getData(),
                    'published': fields.find("[name='chk_published']").is(':checked'),
                    'allow_comments': fields.find("[name='chk_allow_comments']").is(':checked'),
                    'access_level': fields.find("[name='rdo_access_level']:checked").val(),
                    'post-img-name': fields.find("[name='post-img-name']").val(),
                    'cats': cats_array,
                    'tags': tags_array
                },
                beforeSend:function(){
                    $("#new_post_i").removeClass('fa-check fa-close fa-cloud-upload text-danger').addClass('processing-spinner text-success');
                },
                success:function(data){
                    if ($.isNumeric(data)){
                        $("#btn_save_post").text('بروزرسانی');
                        $('#title').text(' پست با کد ' + data + ' ثبت گردید . در صورت تمایل میتوانید آن را ویرایش نمایید! ').css('background-color', '#5bc85c').css('color', 'white');
                        $("#new_post_i").removeClass('fa-close processing-spinner text-danger').addClass('fa-check text-success');
                        $('#btn_save_post').data('action', 'update');
                        $('#update_post_id').val(data);
                    }else{
                        $('#title').text('متاسفانه خطایی رخ داده است. لطفا مجددا تلاش نمایید!').css('background-color', '#cf4445').css('color', 'white');
                        $("#new_post_i").removeClass('fa-check processing-spinner text-success').addClass('fa-close text-danger');
                    }
                }
            });
        });

        // save new cat via ajax
        $('#btn_send_cat').click(function(event){
            if ($("#new_cat_title").val() != ""){
                $.ajax({
                    url:'./controllers/post/store.php',
                    method:'POST',
                    dataType:'json',
                    data:{
                        'new_cat_title': $('#new_cat_title').val(),
                        'new_cat_parent_id': $('#new_cat_parent_id').val()
                    },
                    success:function(data){
                        if (data){
                            let cat_id = data.id;
                            let parent_id = data.parent_id;
                            let title = data.title;
                            if (parent_id != 0)
                                $("input[type='checkbox'][id=" + parent_id + "]").parent().after('<div class="custom-control custom-checkbox shifttoleft2"><input type="checkbox" name="cats[]" class="custom-control-input" id="' + cat_id + '" value="' + cat_id + '" /><label class="custom-control-label" for="' + cat_id + '">' + title + '</label></div>');
                            else
                                $('#cats-container').append('<div class="custom-control custom-checkbox shifttoleft1"><input type="checkbox" name="cats[]" class="custom-control-input" id="' + cat_id + '" value="' + cat_id + '" /><label class="custom-control-label" for="' + cat_id + '">' + title + '</label></div>');
                            $("#new_cat_title").val('');
                        }
                    }
                });
            }
        });

        // save new tag via ajax
        $('#btn_send_tag').click(function(event){
            if ($("#new_tag_title").val() != ""){
                $.ajax({
                    url:'./controllers/post/store.php',
                    method:'POST',
                    dataType:'json',
                    data:{
                        'new_tag_title': $('#new_tag_title').val()
                    },
                    success:function (data){
                        if (data){
                            let tag_id = data.id;
                            let title = data.title;
                            if (data)
                                $("#tags-container").append('<div class="custom-control custom-checkbox shifttoleft1"><input type="checkbox" name="tags[]" class="custom-control-input" id="' + tag_id + '" value="' + tag_id + '" /><label class="custom-control-label" for="' + tag_id + '">' + title + '</label></div>');
                            $("#new_tag_title").val('');
                        }
                    }
                });
            }
        });

        //upload post picture btn
        $("#btn-fake-upload").on("click",function() {
            $("#btn-real-upload").click();
        });

        //post image ajax checking
        $("#btn-real-upload").change(function()
        {

            var form_data  =  new FormData();
            var file       =  this.files[0];
            var file_name  =  file.name;
            var file_size  =  file.size;
            var ext        =  file_name.split('.').pop().toLowerCase();

            //check file type
            if (jQuery.inArray(ext, ['jpg','jpeg','png','gif']) === -1) {
                $("#post-img-upload-box").removeClass('text-success').addClass("text-danger").html("فرمت های قابل قبول jpg, jpeg, gif, png");
                $("#post-img-upload-i").removeClass('processing-spinner fa-cloud-upload fa-check text-success').addClass("fa-close text-danger");
                $("#btn-real-upload").val('');
            }

            //check file size
            else if (file_size > 2000000) {
                $("#post-img-upload-box").removeClass('text-success').addClass("text-danger").html("! حجم تصویر ارسالی زیاد است");
                $("#post-img-upload-i").removeClass('processing-spinner fa-cloud-upload fa-check text-success').addClass("fa-close text-danger");
                $("#btn-real-upload").val('');
            }

            //if the file and size was ok then append image to FormData object
            else {
                form_data.append('p_image', file); // add new pair of (key,value) to FormData object
                $.ajax({
                    url         : "../cpanel/controllers/multimedia/store.php",
                    method      : "POST",
                    data        : form_data,
                    enctype     : "multipart/form-data",
                    contentType : false,
                    processData : false,
                    cache       : false,
                    beforeSend  : function() {
                        $("#post-img-upload-i").removeClass('fa-check fa-close fa-cloud-upload text-danger').addClass("processing-spinner text-success");
                        $("#btn-fake-upload").text("در حال پردازش");
                    },
                    success :function (data) {
                        if (data == 'large') { // large
                            $('#post-img-upload-box').html("");
                            $("#post-img-upload-i").removeClass('processing-spinner fa-cloud-upload text-success').addClass("fa-close text-danger");
                            $("#btn-fake-upload").text('آپلود تصویر');
                            $("#btn-real-upload").val('');
                        }
                        else if (data == 'notimage') { // not image
                            $('#post-img-upload-box').html("<p id='post-img-upload-text' class='post-img-upload-box text-danger'>! خطا: تصویر نامعتبر است</p>");
                            $("#post-img-upload-i").removeClass('processing-spinner fa-check text-success').addClass("fa-close text-danger");
                            $("#btn-fake-upload").text('آپلود تصویر');
                            $("#btn-real-upload").val('');
                        }
                        else if (data.indexOf(".") > -1) { // success
                            $('#post-img-upload-box').html("<img src='../includes/images/uploads/posts/260x260/" + data + "'>");
                            $("#post-img-upload-i").removeClass('processing-spinner fa-cloud-upload fa-close text-danger').addClass("fa-check text-success");
                            $("#btn-fake-upload").text('جایگزینی');
                            $("#post-img-name").val(data);
                        }
                        else { // else (error)
                            $('#post-img-upload-box').html("<p id='post-img-upload-text' class='post-img-upload-box text-danger'>! خطا: مشکلی پیش آمده است</p>");
                            $("#post-img-upload-i").removeClass('processing-spinner fa-check text-success').addClass("fa-close text-danger");
                            $("#btn-fake-upload").text('آپلود تصویر');
                            $("#btn-real-upload").val('');
                        }
                    }
                });
            }

        });
    });
</script>



<!---------------------------------------------------- CKEDITOR ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------>
<script src="./ckeditor/ckeditor.js"></script>
<script src="./ckeditor/lang/fa.js"></script>
<script>
    // ClassicEditor.create(document.querySelector('#area'), {language:'fa'});
    CKEDITOR.replace('area', {
        language: 'fa'
    });
</script>