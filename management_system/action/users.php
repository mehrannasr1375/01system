<?php
/* *********************************************************************************************
 *       ACTION = USERS    (ALWAYS EXISTS ON THIS SCRIPT)
 *       DO = upgrade
 *       DO = dowgrade
 *       DO = delete
 *       ID = X             (USE WITH TOP ITEM)
 ***********************************************************************************************/
if (!(isset($_SESSION['u_id']) or $_SESSION['u_type']==1 )){
    return;
}

// upgrade user
if(isset($_GET['do']) and $_GET['do']=='upgrade' and isset($_GET['id'])){
    $user=(int)User::getUserTypeById($_GET['id']);
    if ($user>1)
        User::changeUserTypeById($_GET['id'], $user-1);
    //showUsers();
}

// downgrade user
if(isset($_GET['do']) and $_GET['do']=='downgrade' and isset($_GET['id'])){
    $user=(int)User::getUserTypeById($_GET['id']);
    if ($user<3)
        User::changeUserTypeById($_GET['id'], $user+1);
    //showUsers();
}

// delete user
if(isset($_GET['do']) and $_GET['do']=='delete' and isset($_GET['id'])){
    $res=User::deleteUserById($_GET['id'],0);
    showUsers();
}

// default action
else {
    showUsers();
}


function showUsers(){
?>
<div class="statusbar"><i class="fa fa-list fa-2x d-inline-block"></i><span class="statusbar-p">کاربران</span></div>

<div id="showusers">
    <div class="overflow-scroll-x" > <!--users table-->
        <div class="row">
            <div class="col p-0">
                <table class="table">
                    <thead class="thead-light">
                    <tr class="text-center">
                        <th>#</th>
                        <th>آواتار</th>
                        <th>نام کاربری</th>
                        <th>نام و نام خانوادگی</th>
                        <th>فعال</th>
                        <th>سطح</th>
                        <th>اعمال</th>
                        <th>ایمیل</th>
                        <th>ثبت</th>
                        <th>پستها</th>
                        <th>فالوورها</th>
                        <th>دوستان</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $users = User::getAllUsers();
                    if ($users){
                        $i=1;
                        foreach ($users as $user){
                            $id               =  $user->id;
                            $avatar           =  $user->avatar;
                            $u_name           =  $user->u_name;
                            $f_l_name         =  $user->f_name." ".$user->l_name;
                            $u_email          =  $user->u_email;
                            $activated        =  ($user->activated) ? $activated='فعال':$activated='غیر فعال' ;
                            $u_type           =  $user->u_type;
                            $post_count       =  $user->post_count;
                            $follower_count   =  $user->follower_count;
                            $following_count  =  $user->following_count;
                            $signup_time      =  convertDate($user->signup_time);
                            ?>
                            <tr class="text-center">
                                <th><?=$i?></th>
                                <td><img class="avatar-sm" src='../includes/images/uploads/avatars/<?=$avatar?>'/></td>
                                <td><?=$u_name?></td>
                                <td class="min-190"><?=$f_l_name?></td>
                                <td class="min-80"><?=$activated?></td>
                                <td><?=$u_type?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="./cpanel.php?action=users&do=upgrade&id=<?=$id?>" class="btn btn-vsm btn-outline-success btn-sm">ارتقا سطح</a>
                                        <a href="./cpanel.php?action=users&do=downgrade&id=<?=$id?>" class="btn btn-vsm btn-outline-secondary btn-sm">کاهش سطح</a>
                                        <a href="./cpanel.php?action=users&do=delete&id=<?=$id?>" class="btn btn-vsm btn-outline-danger btn-sm">حذف</a>
                                    </div>
                                </td>
                                <td class="min-150"><?=$u_email?></td>
                                <td class="min-150"><?=$signup_time['year']."/".$signup_time['month_num']."/".$signup_time['day']." - ".$signup_time['hour'].":".$signup_time['minute']?></td>
                                <td><?=$post_count?></td>
                                <td><?=$follower_count?></td>
                                <td><?=$following_count?></td>

                                
                            </tr>
                        <?php
                            $i++;
                        }
                    }
                    else {
                        echo "<p class='p-2 text-center'>کاربری وجود ندارد </p>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!--users table-->
</div> <!--users container-->

<script> document.addEventListener('DOMContentLoaded', function(event) { $("#l4").siblings().removeClass("active"); $("#l4").addClass("active"); }) </script>
<?php
}
?>
