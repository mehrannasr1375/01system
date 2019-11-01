<?php

// pagination
if (!isset($_GET['part']))
    $part = 1;
elseif (isset($_GET['part']))
    $part = (int)$_GET['part'];

// get all users
$start = ($part-1) * MAX_POSTS_TABLE;
$users = User::all($start, MAX_POSTS_TABLE);


// get users count
$users_count = User::count();

?>



<!------------------------------------------------- PAGE CONTAINER --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
<div id="showusers">



    <!-- page title -->
    <div class="d-flex justify-content-start pt-2">
        <i class="fa fa-2x fa-users text-secondary"></i>
        <span style="font-size:13px; margin-right:10px;">کاربران</span>
        <span class="px-2 text-vvsm">(<?=$users_count?>)</span>
    </div>
    <hr>



    <!-- users table -->
    <div class="overflow-scroll-x" >
        <div class="row">
            <div class="col">
                <table class="table tbl_show_posts">
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
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>




    <!-- pagination -->
    <div class="d-flex justify-content-center my-5">
        <ul class="pagination pagination-sm">
            <?php
            if ($part == 1)//back
                echo '<li class="page-item disabled"><a class="page-link" href="#">قبلی</a></li>';
            else {
                $j = $part-1;
                echo "<li class='page-item'><a class='page-link' href='./cpanel.php?action=users&part=$j'>قبلی</a></li>";
            }


            $Sections = ceil($users_count/MAX_POSTS_TABLE);
            for ($i=1; $i<$Sections; $i++) {
                if ($i == $part)
                    $class = "active";
                else
                    $class = "";
                echo "<li class='page-item $class'><a class='page-link' href='./cpanel.php?action=users&part=$i'>$i</a></li>";
            }


            if ($part == $Sections)//next
                echo "<li class='page-item disabled'><a class='page-link' href='#'>بعدی</a></li>";
            else {
                $j = $part+1;
                echo "<li class='page-item'><a class='page-link' href='./cpanel.php?action=users&part=$j'>بعدی</a></li>";
            }
            ?>
        </ul>
    </div>



</div>



<!---------------------------------------------------- SCRIPTS ---------------------------------------------------------------------------------------------------------------------------------------------------->
<script>
    $(document).ready(function(event){
        $("#l7").siblings().removeClass("active"); $("#l5").addClass("active");
    })
</script>

