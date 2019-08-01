<?php
require_once ("include_all.php");
/* a fake class for test sql injection  */
/*class Test extends Base
{
    public static function testFunction($id)
    {
        $con = Base::connect();
        $query = "SELECT * FROM tbl_user WHERE id='$id';";
        echo $query."<hr>";
        $res = $con->query($query);
        if ($res->rowCount()) {
            $ret = [];
            foreach ($res->fetchAll() as $row){
                $ret[] = $row;
            }
        } else
            $ret = false;
        Base::disconnect($con);
        return $ret;
    }
}*/
/* sql injection test part
if (isset($_GET['id'])) {
    $result = Test::testFunction($_GET['id']);
    if (!$result)
        echo 'user not exists';
    else
        var_dump($result);  
} else
    echo "no parameters sended! insert id as an query string parameter at the end of url!";
*/
?>

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
            برای دسترسی به صفحه ی اصلی سایت نیز میتوانید از لینک های بالا و پایین صفحه نیز استفاده نمایید.
        </p>
    </div>
<a href="http://www.01system.ir" style="display: block;background-color:#c4a3f5;text-align: center;border-radius: 7px;padding: 20px;margin-bottom: 20px;">01System.ir</a>


