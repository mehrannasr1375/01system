<?php
@session_start();
require_once __DIR__ . "/../../../include_all.php";


/* *****************************************************************************************************
 * action =
 *             change_pass
 *             change_bio
 *             avatar
 *             show-accepted-followers
 *             show-inquene-followers
 *             accept , reject
 *             show-followings
 *             show-posts
 *
 * ***************************************************************************************************/


if (!isset($_SESSION['u_id'])){
    die ("error");
}


// show dashboard
if (!isset($_POST['action'])){
    require_once __DIR__ . "/../../views/dashboard/index.php";
    return;
}


// change password
else if ($_POST['action'] == "change_pass"){
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    if (strlen($pass1)<6 or $pass1=="" or $pass2=="")
        die("emptyfield");
    else if ($pass1 != $pass2)
        die("notequal");
    else if (!preg_match("/([a-z][0-9])/", $pass1))
        die("notstrong");
    else{
        $res = User::updateUserPassByUserName($pass1, $_SESSION['u_name']);
        if ($res == true)
            die("changed");
        else
            die("error");
    }
}


// change bio
else if ($_POST['action'] == "change_bio"){
    $user = User::getUserByName($_SESSION['u_name']);
    $bio = $_POST['bio'];
    $user->bio = $bio;
    die("changed");
}


// change avatar
else if ($_POST['action'] == 'change_avatar'){
    $upload_object = new Upload('avatar');
    if ($upload_object->checkImg(2000000)[0] == true){
        if ($upload_object->resizeAndSaveImg(300,300,"../../includes/images/uploads/avatars/",80)) {
            $user = User::getUserById($_SESSION['u_id']);
            if ($user->avatar="$upload_object->fileNameNew"){
                $_SESSION['avatar'] = $upload_object->fileNameNew;
                die("changed");
            }
        }
         else
            die("problemtosaveimage");
    }
    else
        die("large");
}


// show followers
else if ($_POST['action'] == "show-accepted-followers"){
    $u_id = $_SESSION['u_id'];
    $accepted_followers = Friendship::getUserFollowers($u_id,1);
    if(!$accepted_followers)
        die("empty");
    else {
        $i = 1;
        $output = "<table class='table tbl-small table-striped table-light text-center'>"."<thead class=''><tr><th>#</th><th>آواتار</th><th>نام کاربری</th><th>ایمیل</th></tr></thead>";
        foreach ($accepted_followers as $row){
            $output .= "<tr>";
            $output .= "<td>$i</td>";
            $output .= "<td><img class='avatar-sm' src='../includes/images/uploads/avatars/$row->follower_avatar'/></td>";
            $output .= "<td>$row->follower_u_name</td>";
            $output .= "<td>$row->follower_email</td>";
            $output .= "</tr>";
            $i++;
        }
        $output .= "</table>";
        die("$output");
    }
}


// show inqueue followers
else if ($_POST['action'] == "show-inquene-followers"){
    $u_id = (int)$_SESSION['u_id'];
    $not_accepted_followers = Friendship::getUserFollowers($u_id,0);
    if (!$not_accepted_followers)
        die("empty");
    else{
        $i = 1;
        $output = "<table class='table tbl-small table-striped table-light text-center'>"."<thead class=''><tr><th>#</th><th>آواتار</th><th>نام کاربری</th><th>اعمال</th></tr></thead>";
        foreach ($not_accepted_followers as $row){
            $output .= "<tr>";
            $output .= "<td>$i</td>";
            $output .= "<td><img class='avatar-sm' src='../includes/images/uploads/avatars/$row->follower_avatar'/></td>";
            $output .= "<td>$row->follower_u_name</td>";
            $output .= "<td><div class='btn-group btn-group-sm'><a onclick='accept();' class='btn btn-outline-success btn-accept' href='#'>پذیرش</a>"."<input type='hidden' name='sender_id' value='$row->u_id_1'/>"."<a  class='btn btn-outline-danger btn-reject' href='#'>رد</a></div>"."</td>";
            $output .= "</tr>";
            $i++;
        }
        $output .= "</table>";
        die("$output");
    }
}


// accept follower
else if ($_POST['action'] == "accept"){
    $res = Friendship::acceptFollowRequest($_POST['sender_id'],$_SESSION['u_id']);
    if ($res) {
        die(true);
    } else {
        die(false);
    }
}


// reject follower
else if ($_POST['action'] == "reject"){
}


// show following users
else if ($_POST['action'] == "show-followings"){
    $u_id = (int)$_SESSION['u_id'];
    $followings = Friendship::getUserFollowings($u_id);
    if (!$followings){
        die("empty");
    } 
    else{
        $i = 1;
        $output = "<table class='table tbl-small table-striped table-light text-center'>"."<thead class=''><tr><th>#</th><th>آواتار</th><th>نام کاربری</th><th>ایمیل</th></tr></thead>";
        foreach ($followings as $row) {
            $output .= "<tr>";
            $output .= "<td>$i</td>";
            $output .= "<td><img class='avatar-sm' src='../includes/images/uploads/avatars/$row->follower_avatar'/></td>";
            $output .= "<td>$row->follower_u_name</td>";
            $output .= "<td>$row->follower_email</td>";
            $output .= "</tr>";
            $i++;
        }
        $output .= "</table>";
        die("$output");
    }
}


require_once __DIR__ . "/../../views/dashboard/index.php";


