<?php
/* *********************************************************************************************************
 * ACTION = DASHBOARD (ALEAYS EXISTS)
 * do=changepass
 * do=changebio
 * do=changeavatar
 * do=editprofile
 * do=managerequests
 * default ==> show dashboard
 **********************************************************************************************************/
if (!(isset($_SESSION['u_id']) or $_SESSION['u_type']==1 or $_SESSION['u_type']==2)){
    return;
}
?>

<div class="statusbar"><i class="fa fa-user-circle fa-2x d-inline-block "></i><span class="statusbar-p">پیشخوان</span></div>

<!---------------------------------- DASHBOARD BOXES --------------------------------------------------->
<div id="dashboard">
    <div id="profile">
        <div class="card-3">
            <div class="card-3-header">
                <div class="card-3-baner header-green">
                    <i class="fa fa-user fa-3x text-light pr-1"></i>
                </div>
                <p class="card-3-description">اطلاعات شما</p>
                <h3 class="card-3-title title-green">
                    <small>سطح : مدیر</small>
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
            <a href="" data-toggle="modal" data-target="#your-posts-modal" class="cm-link" id="btn-show-posts">نمایش</a>
        </div>
    </div>
    <div class="card-3">
        <div class="card-3-header">
            <div class="card-3-baner header-green">
                <i class="fa fa-star fa-3x text-light"></i>
            </div>
            <p class="card-3-description">امتیاز شما</p>
            <h3 class="card-3-title title-green">0+
                <small>امتیاز</small>
            </h3>
        </div>
        <hr class="devider"/>
        <div class="card-3-footer">
            <p>تا الان</p>
        </div>
    </div>
    <div class="card-3">
        <div class="card-3-header">
            <div class="card-3-baner header-blue">
                <i class="fa fa-reddit fa-3x text-light"></i>
            </div>
            <p class="card-3-description">درخواست های دوستی از شما</p>
            <h3 class="card-3-title title-blue"><?=$user_info['inquene_followers_count']?>+
                <small>درخواست</small>
            </h3>
        </div>
        <hr class="devider"/>
        <div class="card-3-footer">
            <a href="" data-toggle="modal" data-target="#inquene-followers-modal" class="cm-link" id="btn-show-inquene-followers">پذیرش یا رد در خواست ها</a>
        </div>
    </div>
</div>


<!--------------------------------------- MODALS ---------------------------------------------->

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

<!-- inquene followers modal -->
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

<!-- your posts modal -->
<div class="modal fade" id="your-posts-modal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">پست های شما :</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div id="show-posts-res" class="text-light text-center p-1"></div>
            <div class="modal-body">
                <div id="show-posts-table-container" class="" >

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>

<script> document.addEventListener('DOMContentLoaded', function(event) { $("#l1").siblings().removeClass("active"); $("#l1").addClass("active"); }) </script>
