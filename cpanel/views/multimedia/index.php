<div id="multimedia">



    <!-- page title ---------------------------------------------------------------------------------------------------------------------------------------------------------->
    <div class="d-flex justify-content-start pt-2">
        <i class="fa fa-2x fa-picture-o text-secondary"></i>
        <span style="font-size:13px; margin-right:10px;">چند رسانه ای</span>
    </div>
    <hr>



    <!-- draggable area -------------------------------------------------------------------------------------------------------------------------------------------------------->
    <div id="draggable-area">
        <!-- real upload file fileld -->
        <div style="display:none;">
            <input type="file" id="upload-pic-real" class="custom-file-input" name="pic" accept="image/png,image/jpg,image/jpeg,image/gif"/>
        </div>
        <!-- upload box -->
        <div class="d-flex justify-content-around">
            <a id="upload_pic_fake" class="btn-upload"><i class="fa fa-cloud-upload"></i>آپلود تصویر </a>
            <i id="upload_pic_i" class="fa fa-2x"></i>
            <p id="upload_pic_text">تصویر مورد نظر را انتخاب نمایید</p>
        </div>
    </div>



    <!-- images container ----------------------------------------------------------------------------------------------------------------------------------------------------------->
    <div id="img-container">
        <?php
        if ($pics_count == 0) {
            echo "<p id='no-img' class='margin-auto text-black-50'>تصویری وجود ندارد!</p>";
        } else {
            foreach ($pics as $pic) {
                $pl1 = "./../includes/images/uploads/multimedia/thumbnail/$pic->pic_name";
                $pl2 = "./../includes/images/uploads/multimedia/4x3/$pic->pic_name";
                $pl3 = "./../includes/images/uploads/multimedia/16x9/$pic->pic_name";
                ?>
                <div class='img-frame'>
                    <div class='img-pic-container'>
                        <img class='pic-preview' src='<?=$pl1?>' />
                        <div class='img-overlay'>
                            <span data-link="<?=$pl1?>" class='overlay-item overlay-item-cp'>2*2</span>
                            <span data-link="<?=$pl2?>" class='overlay-item overlay-item-cp'>3*4</span>
                            <span data-link="<?=$pl3?>" class='overlay-item overlay-item-cp'>9*16</span>
                            <span class="btn-del-img overlay-item" data-name="<?=$pic->pic_name?>" >حذف</span>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>



    <!-- pagination ------------------------------------------------------------------------------------------------------------------------------------------------------------>
    <div class="d-flex justify-content-center mt-3 mb-1">
        <ul class="pagination pagination-sm">
            <?php

            if ($part == 1)
                echo "<li class='page-item disabled'><a class='page-link' href=''>قبلی</a></li>";
            else {
                $prev = $part-1;
                echo "<li class='page-item'><a class='page-link' href='../cpanel/cpanel.php?action=multimedia&part=$prev'>قبلی</a></li>";
            }

            for ($i=1; $i<=$total_sections; $i++) {
                if($i == $part)
                    $class = "active";
                else
                    $class = "";
                echo "<li class='page-item $class'><a class='page-link' href='../cpanel/cpanel.php?action=multimedia&part=$i'>$i</a></li>";
            }

            if ($part == $total_sections)
                echo "<li class='page-item disabled'><a class='page-link' href=''>بعدی</a></li>";
            else {
                $next = $part+1;
                echo "<li class='page-item'><a class='page-link' href='../cpanel/cpanel.php?action=multimedia&part=$next'>بعدی</a></li>";
            }

            ?>
        </ul>
    </div>



</div>



<!---------------------------------------------------- SCRIPTS --------------------------------------------------------------------------------------------------------------->
<script>
    $(document).ready(function(event){
        // right menu active item
        $("#l7").siblings().removeClass("active"); $("#l6").addClass("active");
    
        // copy link to clipboard
        $("#img-container").on("click", ".overlay-item-cp", function () {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(this).attr("data-link")).select();
            document.execCommand("copy");
            $temp.remove();
            $('#popup-msg').html('لینک در کلیپ بورد کپی شد!');
            $('#popup-i').removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-check');
            $('#popup-box-c').removeClass('border-lr-red').addClass('border-lr-green').slideDown(500).delay(1500).slideUp(200);
        });

        // upload pic fake btn
        $("#upload_pic_fake").on("click",function() {
            $("#upload-pic-real").click();
        });

        // upload via ajax
        $("#upload-pic-real").change(function() {
            var form_data  =  new FormData();
            var file       =  this.files[0];
            var file_name  =  file.name;
            var file_size  =  file.size;
            var ext        =  file_name.split('.').pop().toLowerCase();
            //check file type
            if (jQuery.inArray(ext, ['jpg','jpeg','png','gif']) === -1) {
                $("#upload_pic_text").removeClass('text-success').addClass("text-danger").html("تصویر اشتباه!");
                $("#upload_pic_i").removeClass('processing-spinner fa-check text-success').addClass("fa-close text-danger");
                $("#upload-pic-real").val('');
            }
            //check file size
            else if (file_size > 4000000) {
                $("#upload_pic_text").removeClass('text-success').addClass("text-danger").html("حجم تصویر ارسالی زیاد است!");
                $("#upload_pic_i").removeClass('processing-spinner fa-check text-success').addClass("fa-close text-danger");
                $("#upload-pic-real").val('');
            }
            //if the file and size was ok then append image to FormData object
            else {
                form_data.append('pic', file); // add new pair of (key,value) to FormData object
                $.ajax({
                    url         : "../cpanel/controllers/multimedia/store.php",
                    method      : "POST",
                    data        : form_data,
                    enctype     : "multipart/form-data",
                    contentType : false,
                    processData : false,
                    cache       : false,
                    beforeSend:function(){
                        $("#upload_pic_i").removeClass('fa-check fa-close text-danger').addClass("processing-spinner-sm text-success");
                        $("#upload_pic_text").removeClass('text-danger').addClass("text-success").text("در حال پردازش ... ");
                    },
                    success:function(data){
                        if (data == 'large'){
                            $("#upload_pic_i").removeClass('processing-spinner-sm fa-check text-success').addClass("fa-close text-danger");
                            $("#upload_pic_text").removeClass('text-success').addClass("text-danger").text("خطا: حجم تصویر زیاد است!");
                            $("#upload-pic-real").val('');
                        }
                        else if (data == 'notimage'){
                            $("#upload_pic_i").removeClass('processing-spinner-sm fa-check text-success').addClass("fa-close text-danger");
                            $("#upload_pic_text").removeClass('text-success').addClass("text-danger").text("خطا: تصویر نامعتبر است!");
                            $("#upload-pic-real").val('');
                        }
                        else if (data.toLowerCase().indexOf(".jpg") > -1 || data.toLowerCase().indexOf(".png") > -1 || data.toLowerCase().indexOf(".gif") > -1){
                            $("#upload_pic_i").removeClass('processing-spinner-sm fa-close text-danger').addClass("fa-check text-success");
                            $("#upload_pic_text").removeClass('text-danger').addClass("text-success").text("تصویر با موفقیت آپلود شد!");
                            $("#upload-pic-real").val('');

                            var newitem =`<div class='img-frame'>
                                <div class='img-pic-container'>
                                    <img class='pic-preview' src='./../includes/images/uploads/multimedia/thumbnail/${data}' />
                                    <div class='img-overlay'>
                                       <span data-link='./../includes/images/uploads/multimedia/thumbnail/${data}' class='overlay-item overlay-item-cp'>2*2</span>
                                       <span data-link='./../includes/images/uploads/multimedia/4x3/${data}' class='overlay-item overlay-item-cp'>3*4</span>
                                       <span data-link='./../includes/images/uploads/multimedia/16x9/${data}' class='overlay-item overlay-item-cp'>9*16</span>
                                       <span class='btn-del-img overlay-item' data-name='' >حذف</span>
                                    </div>
                                </div>
                            </div>`;

                            $(".overlay-item").click(function () {
                                var $temp = $("<input>");
                                $("body").append($temp);
                                $temp.val($(this).attr("data-link")).select();
                                document.execCommand("copy");
                                $temp.remove();
                                $('#popup-msg').html('لینک در کلیپ بورد کپی شد!');
                                $('#popup-i').removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-check');
                                $('#popup-box-c').removeClass('border-lr-red').addClass('border-lr-green').slideDown(500).delay(1500).slideUp(200);
                            });
                            $("#no-img").css('display','none');
                            $("#img-container").prepend(newitem);
                        }
                        else{
                            $("#upload_pic_i").removeClass('processing-spinner-sm fa-check text-success').addClass("fa-close text-danger");
                            $("#upload_pic_text").removeClass('text-success').addClass("text-danger").text("خطا: مشکلی پیش آمده است!");
                            $("#upload-pic-real").val('');
                        }
                    }
                });
            }
        });

        // delete image
        $("#img-container").on("click", ".btn-del-img" ,function(){
            let button = $(this);
            $.ajax({
                url:"../cpanel/controllers/multimedia/destroy.php",
                method:"POST",
                data:{
                    action : 'destroy',
                    name : $(this).data('name')
                },
                success:function (data){
                    if (data == 'true'){
                        button.closest('.img-frame').remove();
                        $('#popup-msg').html('تصویر از دیتابیس و سرور شد!');
                        $('#popup-i').removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-check');
                        $('#popup-box-c').removeClass('border-lr-red').addClass('border-lr-green').slideDown(500).delay(1500).slideUp(200);
                    }
                }
            });
        });
    });
</script>