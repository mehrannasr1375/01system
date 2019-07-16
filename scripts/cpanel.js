//accept requersts
function accept() {
    var sender_id = $(this).siblings().find("input[name='sender_id']").val();
    alert(sender_id);
    $.ajax({
    url:'../management_system/action/dashboard_actions.php',
    method:'POST',
    data:{action:'accept', sender_id:sender_id},
    success:function (data) {
        if (data === true)
            $(this).parent.html("پذیرفته شد!");
        else if (data === false) {
            $(this).parent.html("رد شد!");
        }
    }
});
};

//reject requersts
/*$("#").on("click",function(){
    $.ajax({
        url:'../management_system/action/dashboard_actions.php',
        method:'POST',
        data:{action:'reject'},
        success:function (data){
            if (data == "empty")
                $('#').html().removeClass('bg-success').addClass('bg-danger');
            else {
                $('#').html();
            }
        }
    });
});*/

$(document).ready(function(){

    //waiting spinner for load page
    setTimeout(function(){
        $('#left').fadeIn(200);
        $('#spinner').fadeOut(500);
    }, 300);

    //change password modal
    $('#change-pass-btn').on("click",function(){
        var pass1=$("#pass1").val();
        var pass2=$("#pass2").val();
        if(pass1 == '' || pass1.length<6)
            $('#pass-res').addClass('bg-danger').text('پسورد نمی تواند خالی و یا کمتر از 6 کاراکتر باشد!');
        if(pass1 !== pass2)
            $('#pass-res').addClass('bg-danger').text('پسورد ها با هم مطابقت ندارند!');
        else {
                $.ajax({
                    url:'../management_system/action/dashboard_actions.php',
                    method:'POST',
                    data:{action:'change_pass',pass1:pass1,pass2:pass2},
                    success:function (data){
                        if (data == "emptyfield")
                            $('#pass-res').html('پسورد نمی تواند خالی و یا کمتر از 6 کاراکتر باشد!').removeClass('bg-success').addClass('bg-danger');
                        else if(data == "notequal")
                            $('#pass-res').html('دو کلمه ی عبور با هم مطابقت ندارند!').removeClass('bg-success').addClass('bg-danger');
                        else if(data == "changed")
                            $('#pass-res').html('رمز عبور با موفقیت تغییر یافت!').removeClass('bg-danger').addClass('bg-success');
                        else if(data == "notstrong")
                            $('#pass-res').html('رمز عبور به اندازه کافی قوی نیست! رمز عبور باید شامل حداقل یک عدد و یک حرف لاتین باشد.').removeClass('bg-success').addClass('bg-danger');
                        else
                            $('#pass-res').html('............').removeClass('bg-success').addClass('bg-danger');
                    }
                });
        }
    });

    //change bio modal
    $('#change-bio-btn').on("click",function(){
        var bio=$("#bio").val();
        $.ajax({
            url:'../management_system/action/dashboard_actions.php',
            method:'POST',
            data:{action:'change_bio',bio:bio},
            success:function (data){
                if (data == "changed")
                    $('#bio-res').html('بیوگرافی با موفقیت تغییر یافت!').removeClass('bg-danger').addClass('bg-success');
                else
                    $('#bio-res').html('خطایی رخ داده است! لطفا بعدا امتحان نمایید.').removeClass('bg-success').addClass('bg-danger');
            }
        });

    });

    //change avatar modal
    $("#avatar").change(function() {
        var form_data  =  new FormData();
        var avatar     =  this.files[0];
        var file_name  =  avatar.name;
        var file_size  =  avatar.size;
        var ext        =  file_name.split('.').pop().toLowerCase();
        form_data.append('avatar', avatar);
        form_data.append('action', 'change_avatar');
        if (jQuery.inArray(ext, ['jpg','jpeg','png','gif']) === -1) {
            $("#avatar-res").removeClass('bg-success').addClass('bg-danger').text("خطا: تصویر نامعتبر است!");
        } else if(file_size > 2000000) {
            $("#avatar-res").removeClass('bg-success').addClass('bg-danger').text("خطا: حجم تصویر زیاد است!");
        } else {
            $.ajax({
                url         : '../management_system/action/dashboard_actions.php',
                method      : 'POST',
                data        : form_data,
                enctype     : 'multipart/form-data',
                contentType : false,
                processData : false,
                cache       : false,
                beforeSend  : function(){
                    $("#avatar-res").removeClass('bg-danger').addClass("bg-success").text("در حال پردازش ... ");
                },
                success:function (data) {
                    if (data == "changed")
                        $('#avatar-res').html('آواتار شما با موفقیت تغییر یافت!').removeClass('bg-danger').addClass('bg-success');
                    else if(data == 'large') {
                        $("#avatar-res").removeClass('bg-success').addClass('bg-danger').text("خطا: حجم تصویر زیاد است!");
                    } else if(data == 'problemtosaveimage') {
                        $("#avatar-res").removeClass('bg-success').addClass('bg-danger').text("خطا: اشکال در ذخیره تصویر!");
                    } else {
                        // $("#avatar-res").removeClass('bg-success').addClass('bg-danger').text("خطا: مشکلی پیش آمده است!");
                        $("#avatar-res").removeClass('bg-success').addClass('bg-danger').text(data);
                    }

                }
            });
        }
    });

    //show accepted followers
    $("#btn-show-accepted-followers").on("click",function(){
        $.ajax({
            url:'../management_system/action/dashboard_actions.php',
            method:'POST',

            data:{action:'show-accepted-followers'},
            success:function (data){
                if (data == "empty")
                    $('#accepted-followers-res').html('دنبال کننده ای وجود ندارد!').removeClass('bg-success').addClass('bg-danger');
                else {
                    $('#accepted-followers-table-container').html(data);
                }
            }
        });
    });

    //show inquene followers
    $("#btn-show-inquene-followers").on("click",function(){
        $.ajax({
            url:'../management_system/action/dashboard_actions.php',
            method:'POST',
            data:{action:'show-inquene-followers'},
            success:function (data){
                if (data == "empty")
                    $('#inquene-followers-res').html('درخواستی وجود ندارد!').removeClass('bg-success').addClass('bg-danger');
                else {
                    $('#inquene-followers-table-container').html(data);
                }
            }
        });
    });

    //show followigns
    $("#btn-show-followings").on("click",function(){
        $.ajax({
            url:'../management_system/action/dashboard_actions.php',
            method:'POST',
            data:{action:'show-followings'},
            success:function (data){
                if (data == "empty")
                    $('#followings-res').html('شما کسی را دنبال نمی کنید!').removeClass('bg-success').addClass('bg-danger');
                else {
                    $('#followings-table-container').html(data);
                }
            }
        });
    });

    //show posts
    $("#btn-show-posts").on("click",function(){
        $.ajax({
            url:'../management_system/action/dashboard_actions.php',
            method:'POST',
            data:{action:'show-posts'},
            success:function (data){
                if (data == "empty")
                    $('#show-posts-res').html('پستی وجود ندارد!').removeClass('bg-success').addClass('bg-danger');
                else {
                    $('#show-posts-table-container').html(data);
                }
            }
        });
    });

    //upload post picture btn
    $("#btn-fake-upload").on("click",function() {
        $("#btn-real-upload").click();
    });

    //post image ajax checking
    $("#btn-real-upload").change(function() {
        var form_data  =  new FormData();
        var file       =  this.files[0];
        var file_name  =  file.name;
        var file_size  =  file.size;
        var ext        =  file_name.split('.').pop().toLowerCase();
        //check file type
        if (jQuery.inArray(ext, ['jpg','jpeg','png','gif']) === -1) {
            $("#upload-res-text").removeClass('text-success').addClass("text-danger").html("! تصویر اشتباه");
            $("#post-img-upload-status").removeClass('processing-spinner fa-check text-success').addClass("fa-close text-danger");
            $("#btn-real-upload").val('');
        }
        //check file size
        else if(file_size > 2000000) {
            $("#upload-res-text").removeClass('text-success').addClass("text-danger").html("! حجم تصویر ارسالی زیاد است");
            $("#post-img-upload-status").removeClass('processing-spinner fa-check text-success').addClass("fa-close text-danger");
            $("#btn-real-upload").val('');
        }
        //if the file and size was ok then append image to FormData object
        else {
            form_data.append('p_image', file); // add new pair of (key,value) to FormData object
            $.ajax({
                url         : "../management_system/action/check_and_upload_img.php",
                method      : "POST",
                data        : form_data,
                enctype     : "multipart/form-data",
                contentType : false,
                processData : false,
                cache       : false,
                beforeSend  : function(){
                    $("#post-img-upload-status").removeClass('fa-check fa-close text-danger').addClass("processing-spinner text-success");
                    $("#upload-res-text").removeClass('text-danger').addClass("text-success").text("در حال پردازش ... ");
                },
                success :function (data) {
                    if(data == 'large') {
                        $("#post-img-upload-status").removeClass('processing-spinner fa-check text-success').addClass("fa-close text-danger");
                        $("#upload-res-text").removeClass('text-success').addClass("text-danger").text("! خطا: حجم تصویر زیاد است");
                        $("#btn-real-upload").val('');
                    } else if(data == 'notimage') {
                        $("#post-img-upload-status").removeClass('processing-spinner fa-check text-success').addClass("fa-close text-danger");
                        $("#upload-res-text").removeClass('text-success').addClass("text-danger").text("! خطا: تصویر نامعتبر است");
                        $("#btn-real-upload").val('');
                    } else if(data.toLowerCase().indexOf(".") > -1) {
                        $("#post-img-upload-status").removeClass('processing-spinner fa-close text-danger').addClass("fa-check text-success");
                        $("#upload-res-text").removeClass('text-danger').addClass("text-success").text("! تصویر با موفقیت آپلود شد");
                        $("#post-img-name").val(data);
                    } else {
                        $("#post-img-upload-status").removeClass('processing-spinner fa-check text-success').addClass("fa-close text-danger");
                        $("#upload-res-text").removeClass('text-success').addClass("text-danger").text("! خطا: مشکلی پیش آمده است");
                        $("#btn-real-upload").val('');
                    }
                }
            });
        }


    });

    //multimedia upload pic btn
    $("#upload-pic-fake").on("click",function() {
        $("#upload-pic-real").click();
    });

    //multimedia upload via ajax
    $("#upload-pic-real").change(function() {
        var form_data  =  new FormData();
        var file       =  this.files[0];
        var file_name  =  file.name;
        var file_size  =  file.size;
        var ext        =  file_name.split('.').pop().toLowerCase();
        //check file type
        if (jQuery.inArray(ext, ['jpg','jpeg','png','gif']) === -1) {
            $("#pic-res-text").removeClass('text-success').addClass("text-danger").html("تصویر اشتباه!");
            $("#pic-res-i").removeClass('processing-spinner fa-check text-success').addClass("fa-close text-danger");
            $("#upload-pic-real").val('');
        }
        //check file size
        else if(file_size > 2000000) {
            $("#pic-res-text").removeClass('text-success').addClass("text-danger").html("حجم تصویر ارسالی زیاد است!");
            $("#pic-res-i").removeClass('processing-spinner fa-check text-success').addClass("fa-close text-danger");
            $("#upload-pic-real").val('');
        }
        //if the file and size was ok then append image to FormData object
        else {
            form_data.append('pic', file); // add new pair of (key,value) to FormData object
            $.ajax({
                url         : "../management_system/action/check_and_upload_img.php",
                method      : "POST",
                data        : form_data,
                enctype     : "multipart/form-data",
                contentType : false,
                processData : false,
                cache       : false,
                beforeSend  : function() {
                    $("#pic-res-i").removeClass('fa-check fa-close text-danger').addClass("processing-spinner text-success");
                    $("#pic-res-text").removeClass('text-danger').addClass("text-success").text("در حال پردازش ... ");
                },
                success :function (data) {
                    if(data == 'large') {
                        $("#pic-res-i").removeClass('processing-spinner fa-check text-success').addClass("fa-close text-danger");
                        $("#pic-res-text").removeClass('text-success').addClass("text-danger").text("خطا: حجم تصویر زیاد است!");
                        $("#upload-pic-real").val('');
                    } else if(data == 'notimage') {
                        $("#pic-res-i").removeClass('processing-spinner fa-check text-success').addClass("fa-close text-danger");
                        $("#pic-res-text").removeClass('text-success').addClass("text-danger").text("خطا: تصویر نامعتبر است!");
                        $("#upload-pic-real").val('');
                    } else if(data.toLowerCase().indexOf(".jpg") > -1 || data.toLowerCase().indexOf(".png") > -1 || data.toLowerCase().indexOf(".gif") > -1) {
                        $("#pic-res-i").removeClass('processing-spinner fa-close text-danger').addClass("fa-check text-success");
                        $("#pic-res-text").removeClass('text-danger').addClass("text-success").text("تصویر با موفقیت آپلود شد!");
                        $("#upload-pic-real").val();
                        var newitem =
                            "<div class='img-frame'>\n" +
                            "                    <div class='img-pic-container'>\n" +
                            "                        <img class='pic-preview' src='/includes/images/uploads/multimedia/thumbnail/'"+data+"/>\n" +
                            "                        <div class='img-overlay'>\n" +
                            "                           <span data-link='/includes/images/uploads/multimedia/thumbnail/"+ data +"' class='overlay-item'>2*2</span>\n" +
                            "                           <span data-link='/includes/images/uploads/multimedia/4x3/"+ data +"' class='overlay-item'>3*4</span>\n" +
                            "                           <span data-link='/includes/images/uploads/multimedia/16x9/"+ data +"' class='overlay-item'>9*16</span>\n" +
                            "                           <span data-link='' class='overlay-item' >حذف</span>\n" +
                            "                        </div>\n" +
                            "                    </div>\n" +
                            "                </div>"

                            +"<script>"+
                                "    $(\".overlay-item\").click(function () {\n" +
                                "        var $temp = $(\"<input>\");\n" +
                                "        $(\"body\").append($temp);\n" +
                                "        $temp.val($(this).attr(\"data-link\")).select();\n" +
                                "        document.execCommand(\"copy\");\n" +
                                "        $temp.remove();\n" +
                                "        $('#popup-msg').html('لینک در کلیپ بورد کپی شد!');\n" +
                                "        $('#popup-i').removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-check');\n" +
                                "        $('#popup-box-c').removeClass('border-lr-red').addClass('border-lr-green').slideDown(500).delay(1500).slideUp(200);\n" +
                                "    });"
                            +"</script>";
                        $("#no-img").css('display','none');
                        $("#img-container").append(newitem);
                    } else {
                        $("#pic-res-i").removeClass('processing-spinner fa-check text-success').addClass("fa-close text-danger");
                        $("#pic-res-text").removeClass('text-success').addClass("text-danger").text("خطا: مشکلی پیش آمده است!");
                        $("#upload-pic-real").val('');
                    }
            }
            });
        }
    });

    //copy link to clipboard - multimedia
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

});

