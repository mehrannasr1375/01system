<?php
require_once "base.php";
class User extends Base //20 attributes
{
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
    }//ok
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
    }//ok
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
    }//ok
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
    }//ok
    public static function insertUser($u_name,$u_pass,$u_email,$f_name,$l_name,$age,$sex,$bio,$avatar='avatar_default.png')
    {
        try
        {
            $conn = self::connect();
            $signup_time = time();
            $salt = "10-20/3&poonzdah";
            $hashed_pass = md5($u_pass.$salt);
            $stmt = $conn -> prepare("CALL SP_user_insertUser(?,?,?,?,?,?,?,?,?,?);");
            $stmt -> execute([$u_name,$hashed_pass,$u_email,$f_name,$l_name,$age,$sex,$bio,$avatar,$signup_time]);
            self::disconnect($conn);
            if ($stmt -> rowCount())
                $ret = array(true,"کاربر گرامی: یک ایمیل به آدرس ایمیلتان ارسال گردید. جهت فعالسازی حساب کاربری خود ایمیلتان راچک کرده و روی لینک فعالسازی کلیک نمایید. با تشکر.");
            if ($ret[0])
                if (!User::sendActivationEmail($u_name,$u_email))
                    $ret = array(false,"خطا: مشکلی در ارسال ایمیل تاییدیه رخ داده است. لطفا بعدا امتحان نمایید!");
        }
        catch (PDOException $e)
        {
            $ret = array(false, "DataBase Error Number: $e - Description: ".$e->getMessage());
        }
        return $ret;
    }
    public static function sendActivationEmail($u_name,$u_email)
    {
        $conn = self::connect();

        // 1 = STOP DOS ATTACK FOR MAIL LIMITATION
        $now_time=time();
        $stmt=$conn->prepare("CALL SP_sent_mails_checkEmailExists(?,?);");
        $stmt->execute([$u_email,$now_time]);
        if (  (int)($stmt->fetchColumn())>30 ) {
            self::disconnect($conn);
            return array(false,'خطا:تجاوز از حدکثر محدودیت ارسال ایمیل!');
        }

        // 2 = UPDATE ACTIVATION CODE INTO tbl_user
        $activation_code=rand(1000000,9999999);
        $stmt=$conn->prepare("CALL SP_user_updateActivationCode(?,?);");
        if(!$stmt->execute([$activation_code,$u_name])) {
            self::disconnect($conn);
            return array(false,'خطا:کد فعالسازی کاربر ثبت نشد!');
        }
        $subject="لینک فعالسازی حساب کاربری";
        $content="<p style=\"direction=rtl;\">جهت فعالسازی حساب خود روی این لینک کلیک نمایید:<br/><a href=\"http://localhost/technology-store/?action=activate&username=$u_name&code=$activation_code\" target='_blank'>http://localhost/technology-store/?action=activate&username=$u_name&code=$activation_code</a></p>";

        // 3 = INSERT MAIL TIME TO TBL_SENT_MAILS AND DELETE OLD MAILS
        $stmt=$conn->prepare("CALL SP_sent_mails_insertSendRecord(?,?);");
        $stmt->execute([$u_email,$now_time]);

        if($stmt -> rowCount()) {
            if(self::sendMail($u_email,$subject,$content))
            return array(true,"یک ایمیل حاوی لینک فعالسازی حساب کاربری به ایمیل شما ارسال شد. لطفا ایمیل خود را چک نموده و روی لینک فعالسازی کلیک نمایید.");
        else
            return array(false,"خطا در ارسال ایمیل فعالسازی");
        }

    }//ok
    public static function activateUser($u_name,$activation_code)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_user_activateUser(?,?);");
        $stmt->execute([$u_name,$activation_code]);
        self::disconnect($conn);
        if($stmt->rowCount())
            return true;
        else
            return false;
    }//ok
    public static function authenticateUser($u_name,$u_pass)
    {
        $conn=self::connect();
        $salt="10-20/3&poonzdah";
        $hashed_pass=md5($u_pass.$salt);
        $stmt=$conn->prepare("CALL SP_user_authenticateUser(?,?);");
        $stmt->execute([$u_name,$hashed_pass]);
        self::disconnect($conn);
        $row=$stmt->fetch();
        if($stmt->rowCount())
            return new User($row['id'],$row['u_name'],$row['u_pass'],$row['u_type'],$row['u_rate'],$row['u_email'],$row['f_name'],$row['l_name'],$row['activated'],$row['age'],$row['sex'],$row['bio'],$row['avatar'],$row['signup_time'],$row['activation_code'],$row['post_count'],$row['follower_count'],$row['following_count'],$row['random_hash'], $row['deleted']);
        else
            return false;
    }//ok
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
    }//ok
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
    }//ok
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
    }//ok
    public static function rememberUser($u_email)
    {
        $conn = self::connect();
        $now_time = time();
        $salt = "10-20/3&poonzdah";
        $new_pass = rand(100000,999999);
        $content = <<<EOS
<p style="direction:rtl; font-family: Tahoma;">
با سلام
<br/>
نام کاربری و کلمه ی عبور جدید شما:
<br/>
کلمه عبور جدید : $new_pass<br/>
<br/>
لطفا در اسرع وقت از پنل کاربری خود اقدام به تعویض رمز عبور خود نمایید. باتشکر.
</p>
EOS;
        $new_hashed_pass = md5($new_pass.$salt);
        $stmt = $conn -> prepare("SELECT FUNC_user_checkDosAndChangeUserPass(?,?,?)");
        $stmt -> execute([$u_email,$new_hashed_pass,$now_time]);
        self::disconnect($conn);
        $result = $stmt -> fetch()[0];
        if ($result==1) {
            if (self::sendMail($u_email,'فراموشی کلمه عبور', $content))
                return 1;//1= رمز عبور با موفقیت تغییر یافت!
            else
                return 4;//4= عدم ارسال ایمیل!
        }
        else if ($result==2)
            return 2;//2= در حال حاضر سرور قادر با ارسال ایمیل نمی باشد. لطفا بعدا تلاش نمایید!
        else if ($result==3)
            return 3;//3 = شما از حداکثر سقف مجاز تعداد ارسال های خود استفاده نموده اید. لطفا بعدا تلاش نمایید!
        else
            return false;//0 = کاربری با این ایمیل ثبت نام نکرده است!
    }//ok
    public static function deleteUserById($user_id,$permanent=0)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_user_deleteUserById(?,?);");
        $stmt->execute([$user_id,$permanent]);
        self::disconnect($conn);
        if($stmt->rowCount())
            return true;
        else
            return false;
    }//ok
    public static function changeUserTypeById($id,$u_type)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_user_changeUserTypeById(?,?);");
        $stmt->execute([$id,$u_type]);
        self::disconnect($conn);
        if($stmt->rowCount())
            return true;
        else
            return false;
    }//ok
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
    }//ok
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
    }//ok
    public static function updateUserAvatar($u_id,$new_path)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_user_updateUserAvatar(?,?);");
        $stmt->execute([$new_path,$u_id]);
        self::disconnect($conn);
        if ($stmt->rowCount())
            return true;
        else
            return false;
    }//ok
    public static function updateUserPassByEmail($u_pass,$u_email)
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
    }//ok
    public static function updateUserPassByUserName($u_pass,$u_name)
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

    }//ok
    public static function setRandomHash($u_name,$random_hash)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_user_setRandomHash(?,?);");
        $stmt->execute([$u_name,$random_hash]);
        self::disconnect($conn);
        if($stmt->rowCount())
            return true;
        else
            return false;
    }//ok
    public static function getRandomHash($u_name)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_user_getRandomHash(?);");
        $stmt->execute([$u_name]);
        self::disconnect($conn);
        if($stmt->rowCount())
            return $stmt->fetch()[0];
        else
            return false;
    }//ok
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
    }//ok

}


