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


