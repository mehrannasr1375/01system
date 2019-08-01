$(document).ready(function () {


    // login
    $('#btn-sign-in').click(function () {
        console.log('clicked!');
        var uname = $('#user').val();
        var upass = $('#pass').val();
        var remember = true;
        if ($("#remember").prop("checked") == true)
            remember = true;
        else if ($("#remember").prop("checked") == false)
            remember = false;
        if (uname != '' || upass != '') {
            $.ajax({
                url:"actions/login-actions.php",
                method:"POST",
                data:{action:'login',user:uname, pass:upass, remember:remember},
                success:function (data) {
                    if (data == false)
                        $('#response-signin').html('نام کاربری یا کلمه ی عبور اشتباه است!');
                    else if (data == 'max-requests')
                        $('#response-signin').html('حساب شما به طور موقت مسدود شد. لطفا 3 دقیقه ی دیگر مجددا تلاش نمایید ! (جهت امنیت حساب شما)');
                    else if (data == true)
                        location.reload();
                }
            });
        } else
            $('#response-signin').html('نام کاربری یا کلمه ی عبور اشتباه است!');
    });//should check remember chk-box


    // logout
    $('#btn-sign-out').click(function () {
        $.ajax({
            url:"actions/login-actions.php",
            method:"POST",
            data:{action:'logout'},
            success:function (){
                location.reload();
            }
        });
    });


    // forget pass
    $('#btn-forget-mail').click(function () {
        var email = $('#forgetemail').val();
        $('#res-failure-forget').html('لطفا اندکی تامل نمایید ...');
        if (email != '' ) {
            $.ajax({
                url:"actions/login-actions.php",
                method:"POST",
                data:{action:'forgetpass',email:email},
                success:function (data) {
                    if (data == true) {
                        $('#res-failure-forget').html('');
                        $('#res-success-forget').html('ایمیلی حاوی لینک بازیابی رمز عبور برای شما ارسال گردید');
                    } else if (data == 'notexists') {
                        $('#res-success-forget').html('');
                        $('#res-failure-forget').html('خطا: کاربری با این ایمیل ثبت نشده است!');
                    } else if (data == 'sendmailerror') {
                        $('#res-success-forget').html('');
                        $('#res-failure-forget').html('خطا: خطا در ارسال ایمیل. لطفا بعدا تلاش نمایید!');
                    } else if (data == 'servererror') {
                        $('#res-success-forget').html('');
                        $('#res-failure-forget').html('خطا: در حال حاضر سرور قادر با ارسال ایمیل نمی باشد. لطفا بعدا تلاش نمایید!');
                    } else if (data == 'usermaxmails') {
                        $('#res-success-forget').html('');
                        $('#res-failure-forget').html('خطا: شما از حداکثر سقف مجاز تعداد ارسال های خود استفاده نموده اید. لطفا بعدا تلاش نمایید!');
                    } else {
                        $('#res-success-forget').html('');
                        $('#res-failure-forget').html('خطا: خطایی رخ داده است. لطفا مجددا تلاش نمایید!');
                    }
                }
            });
        } else {
            $('#res-success-forget').html('');
            $('#res-failure-forget').html('خطا: لطفا ایمیل خود را وارد نمایید!');
        }
    });


    // chkuser
    $('#username').keyup(function () {
        var username = $('#username').val();
        if (username.length < 5) {
            $('#response-signup').html('نام کاربری باید شامل حداقل 5 کاراکتر باشد!');
        } else {
            $('#response-signup').html('');
            $.ajax({
                url:"actions/login-actions.php",
                method:"POST",
                data:{action:'chkuser',username:username},
                success:function (data) {
                    if (data == 'usernameexists')
                        $('#response-signup').html('این نام کاربری قبلا استفاده شده است!');
                    else if (data == true)
                        $('#response-signup').html('');
                    else
                        $('#response-signup').html('خطایی رخ داده است. بعدا امتحان نمایید!');
                }
            });
        }
    })


    // chkemail
    $('#email').keyup(function () {
        var email = $("#email").val();
        if (email.length < 5)
            $('#response-signup').html('خطا: ایمیل نمی تواند کمتر از چهار کاراکتر باشد!');
        else {
            $('#response-signup').html('');
            $.ajax({
                url:"actions/login-actions.php",
                method:"POST",
                data:{action:'chkemail',email:email},
                success:function (data) {
                    if (data == 'emailexists')
                        $('#response-signup').html('خطا: این ایمیل قبلا استفاده شده است!');
                    else if (data == true)
                        $('#response-signup').html('');
                    else
                        $('#response-signup').html('خطا: خطایی رخ داده است. لطفا بعدا امتحان نمایید!');
                }
            });
        }
    });


    // signup
    $('#btn-sign-up').click(function () {
        var f_name   = $('#f_name').val();
        var l_name   = $('#l_name').val();
        var username = $('#username').val();
        var email    = $('#email').val();
        var pass_1   = $('#pass_1').val();
        var pass_2   = $('#pass_2').val();
        var sex      = $('#sex').val();
        var age      = $('#age').val();
        var description = $('#description').val();

        if (f_name =='' || l_name == '' || pass_1 == '' || pass_2 == '' || sex == '' || age == '' || description == '') {
            $('#response-signup').html('لطفا تمامی فیلدها را پر نمایید!');
            return;
        }
        else if (pass_1 !== pass_2) {
            $('#response-signup').html('دو کلمه ی عبور مشابه هم نیستند!');
            return;
        }
        else if (email.length < 5) {
            $('#response-signup').html('آدرس ایمیل نامعتبر است!');
            return;
        }
        else if (username.length < 5) {
            $('#response-signup').html('نام کاربری نامعتبر است!');
            return;
        }

        // check regex of passwords here

        $.ajax({
            url:"actions/login-actions.php",
            method:"POST",
            data:{action:'signup',f_name:f_name,l_name:l_name,username:username,email:email,pass_1:pass_1,pass_2:pass_2,sex:sex,age:age,description:description},
            success:function (data) {
                if (data == true) {
                    $('#response-signup').html('');
                    $('#response-success').html('مشخصات شما ثبت گردید. لطفا جهت فعالسازی حساب کاربری خود ایمیلتان را چک نمایید.');
                } else if (data == false) {
                    $('#response-success').html('');
                    $('#response-signup').html('خطا: لطفا بعدا امتحان نمایید!');
                } else if (data == 'differentpass') {
                    $('#response-success').html('');
                    $('#response-signup').html('کلمه های عبور با هم برابر نیستند!');
                } else if (data == 'notstrong') {
                    $('#response-success').html('');
                    $('#response-signup').html('رمز عبور به اندازه کافی قوی نیست! رمز عبور باید شامل حداقل یک عدد و یک حرف لاتین باشد.');
                } else if (data == 'counterror') {
                    $('#response-success').html('');
                    $('#response-signup').html('نام کاربری و رمز عبور هر کدام باید شامل حداقل 5 کاراکتر باشند!');
                } else if (data == 'usernameexists') {
                    $('#response-success').html('');
                    $('#response-signup').html('این نام کاربری قبلا ثبت شده است!');
                } else if (data == 'emailexists') {
                    $('#response-success').html('');
                    $('#response-signup').html('این ایمیل قبلا ثبت شده است!');
                } else if (data == 'servererror') {
                    $('#response-success').html('');
                    $('#response-signup').html('servererror');
                } else {
                    $('#response-success').html('');
                    $('#response-signup').html('خطا: خطایی رخ داده است!');
                }
            }
        });
    });




});
