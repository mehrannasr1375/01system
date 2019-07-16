<?php
require_once "base.php";
class Post_cat extends Base //2 attributes
{
    protected $post_id;
    protected $cat_id;

    public function __construct($a,$b)
    {
        $this->post_id=(int)$a;
        $this->cat_id=(int)$b;
    }
    public function __get($property)
    {
        $data=["post_id","cat_id"];
        if (in_array($property,$data))
            return $this->$property;
        else
            die("invalid key!");
    }

    public static function getPostCatByPostId($post_id)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_post_cat_getPostCatByPostId(?)");
        $stmt->execute([$post_id]);
        self::disconnect($conn);
        if ($stmt->rowCount()){
            $cats=array();
            foreach($stmt->fetchAll() as $row)
                $cats[]=new Post_Cat($row['post_id'],$row['cat_id']);
            return $cats;
        } 
        else
            return false;
    }//ok
    public static function getPostCatByCatId($cat_id,$childs=true)
    {
        $conn=self::connect();
        $query="SELECT * FROM tbl_post_cat WHERE cat_id=$cat_id";
        if ($childs){
            if ($child_cats=Cat::getCatsByParentId($cat_id)){
                $child_ids="(";
                foreach($child_cats as $child)
                    $child_ids.=$child->id.",";
                $child_ids=substr($child_ids,0,strlen($child_ids)-1).")";
                $query="SELECT * FROM tbl_post_cat WHERE cat_id=$cat_id OR cat_id IN $child_ids";
            }
        }
        $res=$conn->query($query);
        if ($res->rowCount()){
            $cats=array();
            foreach($res->fetchAll() as $row)
                $cats[]=new Post_Cat($row['post_id'],$row['cat_id']);
            $ret=$cats;
        }
        else
            $ret=false;
        self::disconnect($conn);
        return $ret;
    }//ok
    public static function getPostCatByPostAndCat($post_id,$cat_id)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_post_cat_getPostCatByPostAndCat(?,?);");
        $stmt->execute([$post_id,$cat_id]);
        self::disconnect($conn);
        if ($stmt->rowCount())
            return true;
        else
            return false;
    }//ok
    public static function deletePostCatByPostId($post_id)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_post_cat_deletePostCatByPostId(?);");
        $stmt->execute([$post_id]);
        self::disconnect($conn);
        if ($stmt->rowCount())
            return true;
        else
            return false;
    }//ok
    public static function deletePostCatByCatId($cat_id)
    {
        $conn =self::connect();
        $stmt =$conn->prepare("CALL SP_post_cat_deletePostCatByCatId(?);");
        $stmt ->execute([$cat_id]);
        self::disconnect($conn);
        if ($stmt->rowCount())
            return true;
        else
            return false;
    }//ok

}
