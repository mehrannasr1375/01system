$(function () {

    var post_id          =   $("#post_id").val();
    var likecounter      =   $('#like-counter');
    var dislikecounter   =   $('#dislike-counter');
    var like_count       =   likecounter.val();
    var dislike_count    =   dislikecounter.val();
    var popupbox         =   $('#popup-box');
    var popupmsg         =   $('#popup-msg');
    var popupi           =   $('#popup-i');

    //like
    $("#btn-like").on('click', function() {
        $.ajax({
            url:"../actions/show-post-actions.php",
            method:"POST",
            data:{action:'like',post_id:post_id},
            success:function (data) {
                if (data === '1') {     // x => like
                    popupmsg.html('پست لایک شد!');
                    likecounter.html(like_count+1);
                    popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-thumbs-o-up');
                    popupbox.removeClass('border-lr-red').addClass('border-lr-green').slideDown(500).delay(1500).slideUp(200);
                } else if(data === '3') { // dislike => like
                    popupmsg.html('پست لایک شد!');
                    dislikecounter.html((dislike_count>0) ? dislike_count-1:0);
                    likecounter.html(like_count+1);
                    popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-thumbs-o-up');
                    popupbox.removeClass('border-lr-red').addClass('border-lr-green').slideDown(500).delay(1500).slideUp(200);
                } else if(data === '4') { // like => like
                    popupmsg.html('بازخورد لغو گردید!');
                    likecounter.html((like_count>0) ? like_count-1:0);
                    popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-remove');
                    popupbox.removeClass('border-lr-red').addClass('border-lr-green').slideDown(500).delay(1500).slideUp(200);
                } else if(data === 'login') { // not logged in
                    popupmsg.html('باید به حساب خود وارد شوید!');
                    popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-lock');
                    popupbox.removeClass('border-lr-green').addClass('border-lr-red').slideDown(500).delay(1500).slideUp(200);
                } else {
                    popupmsg.html('خطایی رخ داده است!');
                    popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-warning');
                    popupbox.removeClass('border-lr-green').addClass('border-lr-red').slideDown(500).delay(1500).slideUp(200);
                }
            }
        });
    });


    //dislike
    $("#btn-dislike").on('click', function() {
        $.ajax({
            url: "../actions/show-post-actions.php",
            method: "POST",
            data: {action: 'dislike', post_id: post_id},
            success: function (data) {
                if (data === '0') {      // x => dislike
                    dislikecounter.html(dislike_count+1);
                    popupmsg.html('پست دیسلایک شد!');
                    popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-thumbs-o-down');
                    popupbox.removeClass('border-lr-red').addClass('border-lr-green').slideDown(500).delay(1500).slideUp(200);
                } else if (data === '5') { // dislike => dislike
                    popupmsg.html('بازخورد لغو شد!');
                    dislikecounter.html((dislike_count>0) ? dislike_count-1:0);
                    popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-remove');
                    popupbox.removeClass('border-lr-red').addClass('border-lr-green').slideDown(500).delay(1500).slideUp(200);
                } else if (data === '2') { // like => dislike
                    popupmsg.html('پست دیسلایک شد!');
                    dislikecounter.html(dislike_count+1);
                    likecounter.html((like_count>0) ? like_count-1:0);
                    popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-thumbs-o-down');
                    popupbox.removeClass('border-lr-red').addClass('border-lr-green').slideDown(500).delay(1500).slideUp(200);
                } else if(data === 'login') { // not logged in
                    popupmsg.html('باید به حساب خود وارد شوید!');
                    popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-lock');
                    popupbox.removeClass('border-lr-green').addClass('border-lr-red').slideDown(500).delay(1500).slideUp(200);
                } else {
                    popupmsg.html('خطایی رخ داده است!');
                    popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-warning');
                    popupbox.removeClass('border-lr-green').addClass('border-lr-red').slideDown(500).delay(1500).slideUp(200);
                }
            }
        });
    });


    //follow
    $("#btn-follow").on('click', function() {
        $.ajax({
            url:"../actions/show-post-actions.php",
            method:"POST",
            data:{action:'follow',post_id:post_id},
            success:function (data) {
                if (data === '1') {
                    popupmsg.html('درخواست ارسال شد!');
                    popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-send');
                    popupbox.removeClass('border-lr-red').addClass('border-lr-green').slideDown(500).delay(1500).slideUp(200);
                }  else if(data === 'login') {
                    popupmsg.html('باید به حساب خود وارد شوید!');
                    popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-lock');
                    popupbox.removeClass('border-lr-green').addClass('border-lr-red').slideDown(500).delay(1500).slideUp(200);
                } else {
                    popupmsg.html('درخواست قبلا ارسال گردیده است!');
                    popupi.removeClass('fa-thumbs-o-up fa-thumbs-o-down fa-remove fa-warning fa-lock fa-send').addClass('fa-warning');
                    popupbox.removeClass('border-lr-green').addClass('border-lr-red').slideDown(500).delay(1500).slideUp(200);
                }
            }
        });
    });


    //send-comment
    $("#btn-send-comment").click(function () {
        var parent  =  $("#c_answer_id").val();
        var post    =  $("#post_id").val();
        var name    =  $("#c_full_name").val();
        var site    =  $("#c_site").val();
        var email   =  $("#c_email").val();
        var comment =  $("#c_body").val();
        if(name=='' || comment=='')
            $('#comment-title').removeClass('success-res').addClass('failure-res').html('لطفا تمامی فیلدها را پر نمایید!');
        else {
            $.ajax({
                url:'../actions/show-post-actions.php',
                method:'POST',
                data:{action:'send-comment',name:name,site:site,email:email,comment:comment,parent:parent,post:post},
                success:function (data){
                    if (data === 'true')
                        $('#comment-title').removeClass('failure-res').addClass('success-res').html('دیدگاه شما با موفقیت ثبت گردید و پس از بارگذاری مجدد صفحه نمایان خواهد شد.');
                    else {
                        $('#comment-title').removeClass('success-res').addClass('failure-res').html('خطایی رخ داده است. لطفا بعدا امتحان نمایید!');
                    }
                }
            });
        }
    });

});


