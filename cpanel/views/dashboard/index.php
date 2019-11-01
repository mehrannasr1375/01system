<?php


/* *********************************************************************************************************
 * ACTION = DASHBOARD (ALEAYS EXISTS)
 * do = changepass
 * do = changebio
 * do = changeavatar
 * do = editprofile
 * do = managerequests
 * default ==> show dashboard
 **********************************************************************************************************/



/*-------------------------------------------------- CHECK ACCESSABILITY ---------------------------------------------------------------------------------------------*/
if (!(isset($_SESSION['u_id']) or $_SESSION['u_type']==1 or $_SESSION['u_type']==2)){
    return;
}
?>


<!---------------------------------------------------- PAGE CONTAINER ------------------------------------------------------------------------------------------------>
<div id="dashboard">



    <!-- page title -->
    <div class="d-flex justify-content-start pt-2">
        <i class="fa fa-2x fa-dashcube text-secondary"></i>
        <span style="font-size:13px; margin-right:10px;">پیشخوان</span>
    </div>
    <hr>



    <!-- dashboard boxes -->
    <div class="d-flex justify-content-center flex-wrap">

        <!-- user info -->
        <div id="profile">
            <div class="card-3">
                <div class="card-3-header">
                    <div class="card-3-baner header-green">
                        <i class="fa fa-user fa-3x text-light pr-1"></i>
                    </div>
                    <p class="card-3-description">اطلاعات شما</p>
                    <h3 class="card-3-title title-green">
                        <small>سطح : <?= $access ?></small>
                    </h3>
                </div>
                <hr class="devider"/>
                <div class="card-3-footer text-center">
                    <a href="" data-toggle="modal" data-target="#change-pass-modal" class="cm-link">تغییر رمز عبور</a> |
                    <a href="" data-toggle="modal" data-target="#change-bio-modal" class="cm-link">تغییر بیوگرافی</a>
                    <a href="" data-toggle="modal" data-target="#change-avatar-modal" class="cm-link d-block">تغییر آواتار</a>
                </div>
            </div>
        </div>

        <!-- followers -->
        <div class="card-3">
            <div class="card-3-header">
                <div class="card-3-baner header-blue">
                    <i class="fa fa-twitter fa-3x text-light"></i>
                </div>
                <p class="card-3-description">دنبال کننده ها</p>
                <h3 class="card-3-title title-blue"><?=$user_info['followers_count']?>+
                    <small>نفر</small>
                </h3>
            </div>
            <hr class="devider"/>
            <div class="card-3-footer">
                <a href="" data-toggle="modal" data-target="#show-followers-modal" class="cm-link" id="btn-show-accepted-followers">نمایش</a>
            </div>
        </div>

        <!-- followings -->
        <div class="card-3">
            <div class="card-3-header">
                <div class="card-3-baner header-purple pr-3">
                    <i class="fa fa-twitter-square fa-3x text-light"></i>
                </div>
                <p class="card-3-description">کسانی که دنبال می کنید</p>
                <h3 class="card-3-title title-purple"><?=$user_info['followings_count']?>+
                    <small>نفر</small>
                </h3>
            </div>
            <hr class="devider"/>
            <div class="card-3-footer">
                <a href="" data-toggle="modal" data-target="#show-followings-modal" class="cm-link" id="btn-show-followings">نمایش</a>
            </div>
        </div>

        <!-- posts -->
        <div class="card-3">
            <div class="card-3-header">
                <div class="card-3-baner header-red">
                    <i class="fa fa-list fa-3x text-light"></i>
                </div>
                <p class="card-3-description">پست های شما</p>
                <h3 class="card-3-title title-red"><?=$user_info['posts_count']?>+
                    <small>پست</small>
                </h3>
            </div>
            <hr class="devider"/>
            <div class="card-3-footer">
                <a href="./cpanel.php?action=showposts" class="cm-link">نمایش</a>
            </div>
        </div>

        <!-- rate -->
        <div class="card-3">
            <div class="card-3-header">
                <div class="card-3-baner header-gold">
                    <i class="fa fa-star fa-3x text-light"></i>
                </div>
                <p class="card-3-description">امتیاز شما</p>
                <h3 class="card-3-title title-gold">
                    <?= $user_info['user_rate'] ?>+
                    <small>امتیاز</small>
                </h3>
            </div>
            <hr class="devider"/>
            <div class="card-3-footer">
                <p>تا الان</p>
            </div>
        </div>

        <!-- friendship requests -->
        <div class="card-3">
            <div class="card-3-header">
                <div class="card-3-baner header-aqua">
                    <i class="fa fa-child fa-3x text-light pr-1"></i>
                </div>
                <p class="card-3-description">درخواست های دوستی از شما</p>
                <h3 class="card-3-title title-aqua"><?=$user_info['inqueue_followers_count']?>+
                    <small>درخواست</small>
                </h3>
            </div>
            <hr class="devider"/>
            <div class="card-3-footer">
                <a href="" data-toggle="modal" data-target="#inquene-followers-modal" class="cm-link" id="btn-show-inquene-followers">پذیرش یا رد در خواست ها</a>
            </div>
        </div>

    </div>



</div>




<!-------------------------------------------------------- MODALS ---------------------------------------------------------------------------------------------------->
<div>


    <!-- change pass modal -->
    <div class="modal fade" id="change-pass-modal">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">رمز کاربری جدید خود را وارد نمایید. دقت نمایید که رمز عبور بایستی شامل حداقل یک حرف لاتین و حداقل شامل 6 کاراکتر باشد.</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div id="pass-res" class="text-light text-center p-1"></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label  class="col-form-label">رمز عبور</label>
                        <input type="password" class="form-control" name="pass1" id="pass1"/>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">تکرار رمز عبور</label>
                        <input type="password" class="form-control" name="pass2" id="pass2" />
                    </div>
                </div>
                <div class="modal-footer  btn-group">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">انصراف</button>
                    <button type="button" class="btn btn-sm btn-outline-success" id="change-pass-btn">ارسال</button>
                </div>
            </div>
        </div>
    </div>


    <!-- change bio modal -->
    <div class="modal fade" id="change-bio-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تغییر بیوگرافی (حداکثر 400 کاراکتر)</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div id="bio-res" class="text-light text-center p-1"></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-form-label">بیوگرافی</label>
                        <textarea class="form-control" name="bio" id="bio"><?=User::getUserById($_SESSION['u_id'])->bio?></textarea>
                    </div>
                </div>
                <div class="modal-footer btn-group">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">انصراف</button>
                    <button type="button" class="btn btn-sm btn-outline-success" name="change-bio-btn" id="change-bio-btn">ارسال</button>
                </div>
            </div>
        </div>
    </div>


    <!-- change avatar modal -->
    <div class="modal fade" id="change-avatar-modal">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">لطفا تصویری با حجم حداکثر 512 کیلو بایت و فرمت jpg یا png و یا gif استفاده نمایید.</h5>
                    <br/>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <p id="avatar-res" class="text-light text-center p-1 mb-3"></p>
                <div class="modal-body">
                    <input type="file" class="custom-file-input" name="avatar" id="avatar" accept="image/png,image/jpg,image/jpeg,image/gif"/>
                    <label class="custom-file-label">انتخاب آواتار جدید</label>
                </div>
                <div class="modal-footer btn-group">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">بازگشت</button>
                </div>
            </div>
        </div>
    </div>


    <!-- show followings modal -->
    <div class="modal fade" id="show-followings-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">افرادی که دنبال می کنید :</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div id="followings-res" class="text-light text-center p-1"></div>
                <div class="modal-body">
                    <div id="followings-table-container"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">انصراف</button>
                </div>
            </div>
        </div>
    </div>


    <!-- show followers modal -->
    <div class="modal fade" id="show-followers-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">افرادی که شما را دنبال می کنند :</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div id="accepted-followers-res" class="text-light text-center p-1"></div>
                <div class="modal-body">
                    <div id="accepted-followers-table-container"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>


    <!-- inqueue followers modal -->
    <div class="modal fade" id="inquene-followers-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">افرادی که از شما درخواست دوستی نموده اند :</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div id="inquene-followers-res" class="text-light text-center p-1"></div>
                <div class="modal-body">
                    <div id="inquene-followers-table-container"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>

    
</div>




<!------------------------------------------------------- SCRIPTS ---------------------------------------------------------------------------------------------------->
<script>
    $(document).ready(function(event){
        // right menu active item
        $("#l1").siblings().removeClass("active"); $("#l1").addClass("active");




        //accept requersts
        function accept() {
            var sender_id = $(this).siblings().find("input[name='sender_id']").val();
            alert(sender_id);
            $.ajax({
                url:'./controllers/dashboard/index.php',
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
                    url:'./controllers/dashboard/index.php',
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
                url:'./controllers/dashboard/index.php',
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
                    url         : './controllers/dashboard/index.php',
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
                url:'./controllers/dashboard/index.php',
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

        //show inqueue followers
        $("#btn-show-inquene-followers").on("click",function(){
            $.ajax({
                url:'./controllers/dashboard/index.php',
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
                url:'../../controllers/dashboard/index.php',
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
    })
</script>
