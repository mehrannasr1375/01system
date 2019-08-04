<?php
include "../include_all.php";
session_start();
if ( isset($_POST['action']) )
{


    // login
    if ( $_POST['action'] == 'login' ) {
        $user = User::authenticateUser($_POST['user'], $_POST['pass']);
        if ( is_object($user) ) {
            $_SESSION['u_id']    =  $user -> id;
            $_SESSION['u_name']  =  $user -> u_name;
            $_SESSION['u_type']  =  $user -> u_type;
            $_SESSION['f_name']  =  $user -> f_name;
            $_SESSION['l_name']  =  $user -> l_name;
            $_SESSION['avatar']  =  $user -> avatar;

            if ( isset($_POST['remember']) ) {
                setcookie("remember", $user->user_name,time()+REMEMBER_TIME,null,null,null,true);
                $random_hash = md5(rand(1000000, 10000000));
                setcookie("hash", $random_hash,time()+REMEMBER_TIME,null,null,null,true);
                User::setRandomHash($user->user_name, $random_hash);
            }
            die(true);
        } else
            die(false);
    }



    // logout
    else if ( $_POST['action'] == 'logout' ) {
        unset($_SESSION['u_id']);
        unset($_SESSION['u_name']);
        unset($_SESSION['u_type']);
        unset($_SESSION['f_name']);
        unset($_SESSION['l_name']);
        unset($_SESSION['avatar']);
    }



    // forget pass
    else if ( $_POST['action'] == 'forgetpass' ) {
        if (isset($_POST['email'])) {
            $result = User::rememberUser($_POST['email']);
            if      ($result == 0)
                die('notexists');
            else if ($result == 1)
                die(true);
            else if ($result == 2)
                die('servererror');
            else if ($result == 3)
                die('usermaxmails');
            else if ($result == 4)
                die('sendmailerror');
        } else
            die(false);
    }



    // check user
    else if ( $_POST['action'] == "chkuser" ) {
        if (isset($_POST['username'])) {
            if (User::checkUserNameExists($_POST['username']))
                die('usernameexists');
            else
                die(true);
        } else
            die(false);
    }



    // check email
    else if ( $_POST['action'] == 'chkemail' ) {
        if (isset($_POST['email'])) {
            if (User::checkEmailExists($_POST['email']))
                die('emailexists');
            else
                die(true);
        } else
            die(false);
    }



    // sign up
    else if ( $_POST['action']=="signup" && isset($_POST['f_name']) && isset($_POST['l_name']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['pass_1']) && isset($_POST['pass_2']) && isset($_POST['sex']) &&isset($_POST['age']) && isset($_POST['description']) ){
        $f_name       =  $_POST['f_name'];
        $l_name       =  $_POST['l_name'];
        $username     =  $_POST['username'];
        $email        =  $_POST['email'];
        $pass_1       =  $_POST['pass_1'];
        $pass_2       =  $_POST['pass_2'];
        $sex          =  $_POST['sex'];
        $age          =  $_POST['age'];
        $description  =  $_POST['description'];

        #1  password
        if ($pass_1 != $pass_2)
            die('differentpass');
        elseif (!preg_match("/([a-z][0-9])/", $pass_1))
            die('notstrong');

        #2  define sex
        if ($sex == 'man')
            $sex = 1;
        else
            $sex = 0;

        #3  username
        if (User::checkUserNameExists($_POST['username']))
            die('usernameexists');

        #4  email
        if (User::checkEmailExists($_POST['email']))
            die('emailexists');

        #5  length of strings
        if (strlen($username)<5 || strlen($pass_1)<5 || strlen($email)<5)
            die('counterror');

        #6  insert user
        $res = User::insertUser($username,$pass_1,$email,$f_name,$l_name,$age,$sex,$description);
        if ($res)
            die(true);
        else
            die("$res[1]");

    }



    // activate user
    else if ( isset($_GET['action']) && $_GET['action']=='activate' ) {
        $username = htmlspecialchars(trim($_GET['username']), ENT_QUOTES);
        $code = htmlspecialchars(trim($_GET['code']), ENT_QUOTES);
        $res = User::activateUser($username, $code);
        if ($res) {
            echo " حساب کاربری شما با موفقیت فعال گردید! ";
        } else {
            echo " خطایی رخ داده است! ";
        }

    }



}