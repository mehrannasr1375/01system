<?php
session_start();

include "../../include_all.php";

//@@ return if user not logged in @@
if (!isset($_SESSION['u_id']))
    die ("error");


//@@ change password @@
if ($_POST['action']=="change_pass"){
    $pass1=$_POST['pass1'];
    $pass2=$_POST['pass2'];
    if(strlen($pass1)<6 or $pass1=="" or $pass2=="")
        die ("emptyfield");
    elseif ($pass1!=$pass2)
        die ("notequal");
    else {
        $res=User::updateUserPassByUserName($pass1, $_SESSION['u_name']);
        if($res == true)
            die ("changed");
        else
            die ("error");
    }
}


//@@ change bio @@
elseif ($_POST['action']=="change_bio"){
    $user=User::getUserByName($_SESSION['u_name']);
    $bio=$_POST['bio'];
    $user->bio=$bio;
    //if($res == true)
        die ("changed");
    //else
        //die ("error");
}


//@@ change avatar @@
elseif (isset($_POST['avatar'])){
    $upload_object=new Upload('avatar');
    if($upload_object->checkImg(512000)===true){
        if($result=$upload_object->resizeImg(1120,630)){
            $p_image=$upload_object->fileNameNew;
        } else {
            $error=$result;
            $p_image=POST_IMG_DEFAULT;
        }
    }
    else {
        $p_image=POST_IMG_DEFAULT;
    }
}



//@@ show followers @@
elseif ($_POST['action']=="show-accepted-followers"){
    $u_id=(int)$_SESSION['u_id'];
    $accepted_followers=Friendship::getUserFollowers($u_id,1);
    if(!$accepted_followers){
        die("empty");
    } else {
        $i=1;
        $output="<table class='table tbl-small table-striped table-light text-center'>"."<thead class=''><tr><th>#</th><th>آواتار</th><th>نام کاربری</th><th>ایمیل</th></tr></thead>";
        foreach ($accepted_followers as $row){
            $output.="<tr>";
            $output.="<td>".$i."</td>";
            $output.="<td><img class='avatar-sm' src='../includes/images/avatars/".$row->follower_avatar."'/></td>";
            $output.="<td>".$row->follower_u_name."</td>";
            $output.="<td>".$row->follower_email ."</td>";
            $output.="</tr>";
            $i++;
        }
        $output.="</table>";
        die("$output");
    }
}



//@@ show inquene followers @@
elseif ($_POST['action']=="show-inquene-followers"){
    $u_id=(int)$_SESSION['u_id'];
    $accepted_followers=Friendship::getUserFollowers($u_id,0);
    if(!$accepted_followers){
        die("empty");
    } else {
        $i=1;
        $output="<table class='table tbl-small table-striped table-light text-center'>"."<thead class=''><tr><th>#</th><th>آواتار</th><th>نام کاربری</th><th>اعمال</th></tr></thead>";
        foreach ($accepted_followers as $row){
            $output.="<tr>";
            $output.="<td>".$i."</td>";
            $output.="<td><img class='avatar-sm' src='../includes/images/avatars/".$row->follower_avatar."'/></td>";
            $output.="<td>".$row->follower_u_name."</td>";
            $output.="<td>"."<div class='btn-group btn-group-sm'><a class='btn btn-outline-success' href='#'>پذیرش</a><a  class='btn btn-outline-danger' href='#'>رد</a></div>"."</td>";
            $output.="</tr>";
            $i++;
        }
        $output.="</table>";
        die("$output");
    }
}



//@@ show followings @@
elseif ($_POST['action']=="show-followings"){
    $u_id=(int)$_SESSION['u_id'];
    $followings=Friendship::getUserFollowings($u_id);
    if(!$followings){
        die("empty");
    } else {
        $i=1;
        $output="<table class='table tbl-small table-striped table-light text-center'>"."<thead class=''><tr><th>#</th><th>آواتار</th><th>نام کاربری</th><th>ایمیل</th></tr></thead>";
        foreach ($followings as $row){
            $output.="<tr>";
            $output.="<td>".$i."</td>";
            $output.="<td><img class='avatar-sm' src='../includes/images/avatars/".$row->follower_avatar."'/></td>";
            $output.="<td>".$row->follower_u_name."</td>";
            $output.="<td>".$row->follower_email ."</td>";
            $output.="</tr>";
            $i++;
        }
        $output.="</table>";
        die("$output");
    }
}



//@@ show posts @@
elseif ($_POST['action']=="show-posts"){
    $u_id=(int)$_SESSION['u_id'];
    $posts=Post::getPostsByUserId($u_id,1);
    if(!$posts){
        die("empty");
    } else {
        $i=1;
        $output="<table class='table tbl-small table-striped table-light text-center'>"."<thead class=''><tr><th>#</th><th>شناسه</th><th class='min-190'>عنوان</th><th>آخرین تغییر</th><th>اعمال</th></tr></thead>";
        foreach ($posts as $row){
            $last_modify=convertDate($row->last_modify);
            $id=$row->id;
            $output.="<tr>";
            $output.="<td>".$i."</td>";
            $output.="<td>".$row->id."</td>";
            $output.="<td>".$row->p_title."</td>";
            $output.="<td>".$last_modify['year']."/".$last_modify['month_num']."/".$last_modify['day']." - ".$last_modify['hour'].":".$last_modify['minute']."</td>";
            $output.="<td>"."<div class='btn-group btn-group-sm'><a href='/technology-store/management_system/cpanel.php?action=newpost&do=editpost&id=$id' class='btn btn-outline-primary'>ویرایش</a><a href='/technology-store/management_system/cpanel.php?action=showposts&do=delete&id=$id' class='btn btn-outline-danger'>حذف</a></div>"."</td>";
            $output.="</tr>";
            $i++;
        }
        $output.="</table>";
        die("$output");
    }
}





//@@  @@
//@@  @@
//@@  @@
//@@  @@
//@@  @@
