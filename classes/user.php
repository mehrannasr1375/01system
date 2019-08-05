<?php
require_once "base.php";
class User extends Base
{
    //20
    private $id;
    private $u_name;
    private $u_pass;
    private $u_type;
    private $u_rate;
    private $u_email;
    private $f_name;
    private $l_name;
    private $activated;
    private $age;
    private $sex;
    private $bio;
    private $avatar;
    private $signup_time;
    private $activation_code;
    private $post_count;
    private $follower_count ;
    private $following_count;
    private $random_hash;
    private $deleted;

    public function __construct($a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$k,$l,$m,$n,$o,$p,$q,$r,$s,$t)
    {
        $this->id = (int)$a;
        $this->u_name = $b;
        $this->u_pass = $c;
        $this->u_type = (int)$d;
        $this->u_rate = (int)$e;
        $this->u_email = $f;
        $this->f_name = $g;
        $this->l_name = $h;
        $this->activated = (int)$i;
        $this->age = (int)$j;
        $this->sex = (int)$k;
        $this->bio = $l;
        $this->avatar = $m;
        $this->signup_time = (int)$n;
        $this->activation_code = $o;
        $this->post_count = (int)$p;
        $this->follower_count  = (int)$q;
        $this->following_count = (int)$r;
        $this->random_hash = $s;
        $this->deleted = (int)$t;
    }
    public function __get($property)
    {
        $data = ['id','u_name','u_pass','u_type','u_rate','u_email','f_name','l_name','activated','age','sex','bio','avatar','signup_time','activation_code','post_count','follower_count' ,'following_count','random_hash','deleted'];
        if(in_array($property,$data))
            return $this->$property;
        else
            return 'invalid property';
    }
    public function __set($key,$value)
    {
        $keys = ['u_rate','bio','avatar','deleted'];
        if (in_array($key,$keys)) {
            $conn = self::connect();
            $res = $conn->query("UPDATE tbl_user SET $key='$value' WHERE id = $this->id;");
            if ($res->rowCount()) {
                $this->$key = $value;
                self::disconnect($conn);
                return true;
            } else {
                self::disconnect($conn);
                die("not possible! are you kidding me?");
            }
        }
        else
            return 'not possible!';
    }

    public static function getAllUsers()
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_user_getAllUsers();");
        $stmt->execute();
        self::disconnect($conn);
        if($stmt->rowCount()){
            $users=array();
            foreach($stmt->fetchAll() as $row)
                $users[]=new User($row['id'],$row['u_name'],$row['u_pass'],$row['u_type'],$row['u_rate'],$row['u_email'],$row['f_name'],$row['l_name'],$row['activated'],$row['age'],$row['sex'],$row['bio'],$row['avatar'],$row['signup_time'],$row['activation_code'],$row['post_count'],$row['follower_count'],$row['following_count'],$row['random_hash'], $row['deleted']);
            return $users;
        }
        else
            return false;
    }
    public static function getUserById($id)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_user_getUserById(?);");
        $stmt->execute([$id]);
        self::disconnect($conn);
        if($stmt->rowCount()){
            $row=$stmt->fetch(PDO::FETCH_ASSOC);
            return new User($row['id'],$row['u_name'],$row['u_pass'],$row['u_type'],$row['u_rate'],$row['u_email'],$row['f_name'],$row['l_name'],$row['activated'],$row['age'],$row['sex'],$row['bio'],$row['avatar'],$row['signup_time'],$row['activation_code'],$row['post_count'],$row['follower_count'],$row['following_count'],$row['random_hash'], $row['deleted']);
        }
        else
            return false;
    }
    public static function getUserIdByPostId($id)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_user_getUserIdByPostId(?);");
        $stmt->execute([$id]);
        self::disconnect($conn);
        if($stmt->rowCount())
            return $stmt->fetch()['u_id'];
        else
            return false;
    }
    public static function insertUser($u_name,$u_pass,$u_email,$f_name,$l_name,$age,$sex,$bio,$avatar='avatar_default.png')
    {
        try
        {
            $conn = self::connect();
            $signup_time = time();
            $salt = "10-20/3&poonzdah";
            $hashed_pass = md5($u_pass.$salt);
            $stmt = $conn->prepare("CALL SP_user_insertUser(?,?,?,?,?,?,?,?,?,?);");
            $stmt->execute([$u_name,$hashed_pass,$u_email,$f_name,$l_name,$age,$sex,$bio,$avatar,$signup_time]);
            self::disconnect($conn);
            if ( $stmt->rowCount() )
                $ret = array(true,"کاربر گرامی: یک ایمیل به آدرس ایمیلتان ارسال گردید. جهت فعالسازی حساب کاربری خود ایمیلتان راچک کرده و روی لینک فعالسازی کلیک نمایید. با تشکر.");
            if ( $ret[0] )
                if (!User::sendActivationEmail($u_name, $u_email))
                    $ret = array(false,"خطا: مشکلی در ارسال ایمیل تاییدیه رخ داده است. لطفا بعدا امتحان نمایید!");
        }
        catch (PDOException $e)
        {
            $ret = array(false, "DataBase Error Number: $e - Description: ".$e->getMessage());
        }
        return $ret;
    } //ok
    public static function sendActivationEmail($u_name, $u_email)
    {
        $conn = self::connect();

        // 1 = STOP DOS ATTACK FOR MAIL LIMITATION
        $now_time = time();
        $query = "SELECT COUNT(*) FROM tbl_sent_mails WHERE u_email=? AND `time`>?-3600";
        $stmt = $conn->prepare($query);
        $stmt->execute([$u_email, $now_time]);
        self::disconnect($conn);
        if (  (int)($stmt->fetchColumn()) > 30 )
            return array(false, 'خطا:تجاوز از حدکثر محدودیت ارسال ایمیل!');

        // 2 = UPDATE ACTIVATION CODE INTO tbl_user
        $activation_code = rand(1000000, 9999999);
        $query = "UPDATE tbl_user SET activation_code=? WHERE u_name=?;";
        $stmt = $conn->prepare($query);
        self::disconnect($conn);
        if (!$stmt->execute([$activation_code, $u_name]))
            return array(false, 'خطا:کد فعالسازی کاربر ثبت نشد!');
        $subject = "لینک فعالسازی حساب کاربری";
        if ( DEVELOPING_MODE === true )
            $activate_url = "<a href=\"http://localhost/01system/index.php?action=activate&username=$u_name&code=$activation_code\" target='_blank'>http://localhost/01system/index.php?action=activate&username=$u_name&code=$activation_code</a>";
        else
            $activate_url = "<a href=\"http://www.01system.ir/index.php?action=activate&username=$u_name&code=$activation_code\" target='_blank'>http://www.01system.ir/index.php?action=activate&username=$u_name&code=$activation_code</a>";

        $content = <<<EOS
        <a href="http://www.01system.ir" style="display: block;margin-bottom:0 !important;background-color:#c4a3f5;text-align: center;border-radius: 7px;padding: 20px;margin-bottom: 20px;">01System.ir</a>
            <div style="padding: 30px; background-color: rgba(224,212,249,0.65);">
                <p style="direction:rtl; font-family: Tahoma;line-height: 2">
                    با سلام
                    <br>
                    کاربر گرامی، لطفا جهت فعالسازی حساب کاربری خود روی لینک زیر کلیک نمایید.
                    <br>
                    باتشکر
                    <br>
                    $activate_url
                    </p>
                </p>
            </div>
        <a href="http://www.01system.ir" style="display: block;margin-bottom:0 !important;background-color:#c4a3f5;text-align: center;border-radius: 7px;padding: 20px;margin-bottom: 20px;">01System.ir</a>
EOS;

        // 3 = INSERT MAIL TIME TO TBL_SENT_MAILS AND DELETE OLD MAILS
        $query = "INSERT INTO tbl_sent_mails(u_email,`time`) VALUES(?, ?);
                                        DELETE FROM tbl_sent_mails WHERE `time`<?-3600;";
        $stmt = $conn->prepare($query);
        $stmt->execute([$u_email, $now_time, $now_time]);
        self::disconnect($conn);
        if ($stmt -> rowCount()) {
            if ( self::sendMail($u_email, $subject, $content) )
            return array(true, "یک ایمیل حاوی لینک فعالسازی حساب کاربری به ایمیل شما ارسال شد. لطفا ایمیل خود را چک نموده و روی لینک فعالسازی کلیک نمایید.");
        else
            return array(false, "خطا در ارسال ایمیل فعالسازی");
        }

    } //should update URL for web
    public static function activateUser($u_name, $activation_code)
    {
        $conn = self::connect();
        $stmt = $conn->prepare("CALL SP_user_activateUser(?,?);");
        $stmt->execute([$u_name, $activation_code]);
        self::disconnect($conn);
        if ( $stmt->rowCount() )
            return true;
        else
            return false;
    } //ok
    public static function authenticateUser($u_name, $u_pass)
    {
        // returns an object or false or 'max-requests'

        // delete old records
        $conn = self::connect();
        $stmt = $conn->prepare("DELETE FROM tbl_login_history WHERE username=? AND `time` < DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 3 MINUTE );");
        $stmt->execute([$u_name]);
        self::disconnect($conn);

        // save current request
        $conn = self::connect();
        $stmt = $conn->prepare("INSERT INTO tbl_login_history(`ip`, `username`, `time`) VALUES (?,?, CURRENT_TIMESTAMP);");
        $stmt->execute([$_SERVER['REMOTE_ADDR'], $u_name]);
        self::disconnect($conn);

        // get requersts count
        $conn = self::connect();
        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_login_history WHERE username=? AND `time` > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 3 MINUTE );");
        $stmt->execute([$u_name]);
        self::disconnect($conn);

        // make last decesion (login || fail)
        if ( $stmt->fetch()[0] > 5 )
            die('max-requests') ;
        else {
            $conn = self::connect();
            $salt = "10-20/3&poonzdah";
            $hashed_pass = md5($u_pass.$salt);
            $stmt = $conn->prepare("CALL SP_user_authenticateUser(?,?);");
            $stmt->execute([$u_name, $hashed_pass]);
            self::disconnect($conn);
            $row = $stmt->fetch();
            if ( $stmt->rowCount() )
                return new User($row['id'],$row['u_name'],$row['u_pass'],$row['u_type'],$row['u_rate'],$row['u_email'],$row['f_name'],$row['l_name'],$row['activated'],$row['age'],$row['sex'],$row['bio'],$row['avatar'],$row['signup_time'],$row['activation_code'],$row['post_count'],$row['follower_count'],$row['following_count'],$row['random_hash'], $row['deleted']);
            else
                return false;
        }

    } //ok
    public static function getUserByName($u_name)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_user_getUserByName(?);");
        $stmt->execute([$u_name]);
        self::disconnect($conn);
        $row=$stmt->fetch();
        if($stmt->rowCount())
            return new User($row['id'],$row['u_name'],$row['u_pass'],$row['u_type'],$row['u_rate'],$row['u_email'],$row['f_name'],$row['l_name'],$row['activated'],$row['age'],$row['sex'],$row['bio'],$row['avatar'],$row['signup_time'],$row['activation_code'],$row['post_count'],$row['follower_count'],$row['following_count'],$row['random_hash'], $row['deleted']);
        else
            return false;
    }
    public static function checkUserNameExists($u_name)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("SELECT FUNC_user_checkUserNameExists(?);");
        $stmt->execute([$u_name]);
        self::disconnect($conn);
        if($stmt->fetch()[0]==0)//1:exists && 0:not exists
            return false;
        else
            return true;
    }
    public static function checkEmailExists($u_email)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("SELECT FUNC_user_checkEmailExists(?);");
        $stmt->execute([$u_email]);
        self::disconnect($conn);
        if($stmt->fetch()[0]==0)//1:exists && 0:not exists
            return false;
        else
            return true;
    }
    public static function rememberUser($u_email)
    {
        $conn = self::connect();
        $now_time = time();
        $salt = "10-20/3&poonzdah";
        $new_pass = rand(100000,999999);
        $content = <<<EOS
                        <a href="http://www.01system.ir" style="display: block;margin-bottom:0 !important;background-color:#c4a3f5;text-align: center;border-radius: 7px;padding: 20px;margin-bottom: 20px;">01System.ir</a>
                        <div style="padding: 30px; background-color: rgba(224,212,249,0.65);">
                            <p style="direction:rtl; font-family: Tahoma;line-height: 2">
                                با سلام
                                <br/>
                                 کلمه ی عبور جدید شما در وبسایت <span style="font-weight: bold;">01System.ir</span> :
                                <br/>
                                کلمه عبور جدید : <span style="font-weight: bold;">$new_pass</span><br/>
                                <br/>
                                لطفا در اسرع وقت از پنل کاربری خود اقدام به تعویض رمز عبور خود نمایید و در نگهداری رمز خود کوشا باشید. با تشکر.
                                <br/>
                                برای دسترسی به صفحه ی اصلی سایت نیز می توانید از لینک های بالا و پایین صفحه استفاده نمایید.
                            </p>
                        </div>
                        <a href="http://www.01system.ir" style="display: block;background-color:#c4a3f5;text-align: center;border-radius: 7px;padding: 20px;margin-bottom: 20px;">01System.ir</a>

EOS;
        $new_hashed_pass = md5($new_pass.$salt);


       /* Query :
        *
        * CREATE DEFINER=`systemir_mehran`@`localhost` PROCEDURE `SP_user_checkDosAndChangeUserPass`(IN `_u_email` VARCHAR(45), IN `_time` INT(11), IN `_u_name` VARCHAR(25), IN `_u_pass` VARCHAR(32))
        *  BEGIN
        *      DECLARE mail_count INT;
        *      DECLARE result INT;
        *
        *      START TRANSACTION;
        *
        *          #1 : check mail counts of last hour
        *          SET mail_count = (SELECT count(*) FROM tbl_sent_mails WHERE `time` > (select (_time - 3600))) AND u_email = _u_email;
        *          IF (mail_count > 30) THEN
        *                  set result = 0;
        *          ELSE
        *                  #2 : update password of user
        *                  IF EXISTS(SELECT * FROM tbl_user WHERE u_email=_u_email) THEN
        *                      UPDATE tbl_user SET u_pass=_u_pass WHERE u_name=_u_name;
        *                      set result = 1;
        *                  ELSE
        *                      set result = 0;
        *                  END IF;
        *          END IF;
        *
        *          #3 : insert mail send event to tbl_sent_mails
        *          IF(result = 1) THEN
        *              INSERT INTO tbl_sent_mails(u_email,`time`) VALUES(_u_email,_time) ;
        *              set result = 1;
        *          ELSE
        *              set result = 0;
        *          END IF;
        *
        *          #4 : delete old records of emails table
        *          DELETE FROM tbl_sent_mails WHERE `time`<(select (_time - 3600));
        *
        *          #5 : return result
        *          select result;
        *      COMMIT;
        *  END
        *
        */

        $stmt = $conn -> prepare("SELECT FUNC_user_checkDosAndChangeUserPass(?,?,?)");
        $stmt -> execute([$u_email,$new_hashed_pass,$now_time]);
        self::disconnect($conn);
        $result = $stmt -> fetch()[0];
        if ($result==1) {
            if (self::sendMail($u_email,'فراموشی کلمه عبور', $content))
                return 1;//1= رمز عبور با موفقیت تغییر یافت!
            else
                return 4;//4= عدم ارسال ایمیل!
        } else if ($result==2) {
            return 2;//2= در حال حاضر سرور قادر با ارسال ایمیل نمی باشد. لطفا بعدا تلاش نمایید!
        } else if ($result==3) {
            return 3;//3 = شما از حداکثر سقف مجاز تعداد ارسال های خود استفاده نموده اید. لطفا بعدا تلاش نمایید!
        } else {
            return false;//0 = کاربری با این ایمیل ثبت نام نکرده است!
        }
    } //ok
    public static function deleteUserById($user_id, $permanent=0)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_user_deleteUserById(?,?);");
        $stmt->execute([$user_id,$permanent]);
        self::disconnect($conn);
        if($stmt->rowCount())
            return true;
        else
            return false;
    }
    public static function changeUserTypeById($id, $u_type)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_user_changeUserTypeById(?,?);");
        $stmt->execute([$id,$u_type]);
        self::disconnect($conn);
        if($stmt->rowCount())
            return true;
        else
            return false;
    }
    public static function getUserTypeById($id)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("SELECT FUNC_user_getUserType(?);");
        $stmt->execute([$id]);
        self::disconnect($conn);
        if($stmt->rowCount())
            return $stmt->fetch()[0];
        else
            return false;
    }
    public static function getLikesOfUser($u_id)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_getLikesOfUser(?);");
        $stmt->execute([$u_id]);
        self::disconnect($conn);
        if($stmt->rowCount())
            return $stmt->fetch()[0];
        else
            return false;
    }
    public static function updateUserAvatar($u_id, $new_path)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_user_updateUserAvatar(?,?);");
        $stmt->execute([$new_path,$u_id]);
        self::disconnect($conn);
        if ($stmt->rowCount())
            return true;
        else
            return false;
    }
    public static function updateUserPassByEmail($u_pass, $u_email)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_user_updateUserPassByEmail(?,?);");
        $salt ="10-20/3&poonzdah";
        $hashed_pass=md5($u_pass.$salt);
        $res=$stmt->execute([$hashed_pass,$u_email]);
        self::disconnect($conn);
        if ($res)
            return true;
        else
            return false;
    }
    public static function updateUserPassByUserName($u_pass, $u_name)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_user_updateUserPassByUserName(?,?);");
        $salt ="10-20/3&poonzdah";
        $hashed_pass=md5($u_pass.$salt);
        $stmt->execute([$hashed_pass,$u_name]);
        self::disconnect($conn);
        if ($stmt->rowCount())
            return true;
        else
            return false;

    }
    public static function setRandomHash($u_name, $random_hash)
    {
        $conn = self::connect();
        $stmt = $conn->prepare("CALL SP_user_setRandomHash(?,?);");
        $stmt->execute([$u_name, $random_hash]);
        self::disconnect($conn);
        if ( $stmt->rowCount() )
            return true;
        else
            return false;
    }
    public static function getRandomHash($u_name)
    {
        $conn = self::connect();
        $stmt = $conn->prepare("CALL SP_user_getRandomHash(?);");
        $stmt->execute([$u_name]);
        self::disconnect($conn);
        if ( $stmt->rowCount() )
            return $stmt->fetch()[0];
        else
            return false;
    }
    public static function getUserInformations($u_id)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_getInformationsOfUser(?);");
        $stmt->execute([$u_id]);
        self::disconnect($conn);
        if($stmt->rowCount())
            return $stmt->fetch();
        else
            return false;
    }

}


