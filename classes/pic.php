<?php
require_once "base.php";
class Pic extends Base
{
    private $id;
    private $pic_name;
    private $u_id;
    private $date;


    public function __construct($a,$b,$c,$d){
        $this->id       = (int)$a;
        $this->pic_name = $b;
        $this->u_id     = (int)$c;
        $this->date     = (int)$d;
    }
    public function __get($property){
        return $this->$property;
    }


    public static function insertPic($pic_name, $u_id)
    {
        $conn = self::connect();

        $date = time();
        $stmt = $conn->prepare("CALL SP_pic_insertPic(?,?,?)");
        try {
            $stmt->execute([$pic_name,$u_id,$date]);
        }
        catch (PDOException $e){
            return false;
        }

        self::disconnect($conn);

        if ($stmt->rowcount())
            return true;
        else
            return false;
    }//ok

    public static function deletePicById($pic_id)
    {
        $conn = self::connect();
        $stmt = $conn->prepare("CALL SP_pic_deletePicById(?)");
        $stmt->execute([$pic_id]);
        self::disconnect($conn);
        if ($stmt->rowcount())
            return true;
        else
            return false;
    }//ok

    public static function deletePicByName($pic_name)
    {
        $conn = self::connect();
        $stmt = $conn->prepare("DELETE FROM tbl_pic WHERE pic_name=?");
        $stmt->execute([$pic_name]);
        self::disconnect($conn);
        if ($stmt->rowcount())
            return true;
        else
            return false;
    }//ok

    public static function deletePicsOfUser($user_id)
    {
        $conn = self::connect();
        $stmt = $conn->prepare("CALL SP_pic_deletePicsOfUser(?)");
        $stmt->execute([$user_id]);
        self::disconnect($conn);
        if ($stmt->rowcount())
            return true;
        else
            return false;
    }//ok

    public static function getPicsOfUser($u_id, $limit=0, $start=1)
    {
        $conn = self::connect();

        $stmt = $conn->prepare("CALL SP_pic_getPicsOfUser(?,?,?)");
        $stmt->execute([$u_id,$limit,$start]);

        if ($stmt->rowcount()) {
            $ret = array();
            foreach ($stmt->fetchAll() as $res) {
                $ret[] = new Pic($res['id'], $res['pic_name'], $res['u_id'], $res['date']);
            }
        }
        else
            $ret = false;

        self::disconnect($conn);

        return $ret;
    }//ok

    public static function getPicscountOfUser($u_id)
    {
        $conn = self::connect();

        $stmt = $conn->prepare("CALL SP_Pic_GetPicsCountOfUser(?)");
        $stmt->execute([$u_id]);

        if ($stmt->rowcount()) {
            $ret = $stmt->fetch();
        }
        else
            $ret = 0;

        self::disconnect($conn);

        return $ret;
    }//ok

}