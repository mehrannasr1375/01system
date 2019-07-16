<?php
require_once "base.php";
class Cat extends Base //3 attributes
{
    private $id;
    private $cat_name;
    private $cat_parent;

    public function __construct($a,$b,$c){
        $this->id=(int)$a;
        $this->cat_name=$b;
        $this->cat_parent=(int)$c;
    }
    public function __get($property)
    {
        $data = ["id", "cat_name","cat_parent"];
        if (in_array($property,$data))
            return $this->$property;
        else
            die("invalid key:'$property'");
    }

    public static function getAllCats()
    {
        $conn=self::connect();
        $result=$conn->query("CALL SP_cat_getAllCats();");
        if ($result){
            $cats=array();
            foreach($result->fetchAll() as $row)
                $cats[]=new Cat($row['id'],$row['cat_name'],$row['cat_parent']);
            $ret=$cats;
        }
        else
            $ret=false;
        self::disconnect($conn);
        return $ret;
    }//ok
    public static function getCatById($id)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_cat_getCatById(?)");
        $stmt->execute([$id]);
        self::disconnect($conn);
        if ($stmt->rowCount()){
            $result=$stmt->fetch();
            return new Cat($result['id'],$result['cat_name'],$result['cat_parent']);
        }
        else
            return false;
    }//ok
    public static function deleteCatById($id)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_cat_deleteCatById(?)");
        $stmt->execute([$id]);
        self::disconnect($conn);
        if ($stmt->rowCount())
            return true;
        else
            return false;
    }//ok
    public static function getCatsByParentId($id)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_cat_getCatsByParentId(?)");
        $stmt->execute([$id]);
        self::disconnect($conn);
        if($stmt->rowCount()){
            $cats=array();
            foreach ($stmt->fetchAll() as $row)
                $cats[]=new Cat($row['id'],$row['cat_name'],$row['cat_parent']);
            return $cats;
        }
        else
            return false;
    }//ok
    public static function insertCat($cat_name,$cat_parent=1)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_cat_insertCat(?,?)");
        try {
            $stmt->execute([$cat_name,$cat_parent]);
        }
        catch (PDOException $e){
            return false;
        }
        self::disconnect($conn);
        if ($stmt->rowCount())
            return true;
        else
            return false;
    }//ok
    public static function getCatsCount()
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_cat_getCatsCount()");
        $stmt->execute();
        self::disconnect($conn);
        if($stmt->rowCount())
            return $stmt->fetch()[0]-1;
    }//ok

}

