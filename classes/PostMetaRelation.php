<?php
require_once "base.php";
class PostMetaRelation extends Base
{

    protected $post_id;
    protected $post_meta_id;


    public function __construct($post_id, $post_meta_id)
    {
        $this->post_id = (int)$post_id;
        $this->post_meta_id = (int)$post_meta_id;
    }
    public function __get($property)
    {
        $data = ["post_meta_id", "post_id"];
        if (in_array($property, $data))
            return $this->$property;
        else
            die("invalid key!");
    }


    public static function getPostCategories($post_id)
    {
        $conn = self::connect();

        $stmt = $conn->prepare("CALL SP_PostMetaRelation_PostCategories(?)");
        $stmt->execute([$post_id]);

        self::disconnect($conn);

        if ($stmt->rowcount()){
            $cats = array();
            foreach ($stmt->fetchAll() as $row)
                $cats[] = new PostMeta(
                    $row['id'],
                    $row['title'],
                    $row['parent'],
                    $row['meta_type']
                );
            return $cats;
        }
        else
            return false;
    }//ok

    public static function getPostTags($post_id)
    {
        $conn = self::connect();

        $stmt = $conn->prepare("CALL SP_PostMetaRelation_PostTags(?)");
        $stmt->execute([$post_id]);

        self::disconnect($conn);

        if ($stmt->rowcount()){
            $cats = array();
            foreach ($stmt->fetchAll() as $row)
                $cats[] = new PostMeta(
                    $row['id'],
                    $row['title'],
                    $row['parent'],
                    $row['meta_type']
                );
            return $cats;
        }
        else
            return false;
    }//ok





    public function create($post_meta_id, $post_id)
    {
        $conn = self::connect();

        $stmt = $conn->prepare("INSERT INTO tbl_meta_relation(pos_meta_id,post_id) VALUES (?,?)");
        $stmt->execute([$post_meta_id, $post_id]);

        self::disconnect($conn);

        return true;
    }

    public static function getPostCategoriesByCategoryId($cat_id,$childs=true)
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
        if ($res->rowcount()){
            $cats=array();
            foreach($res->fetchAll() as $row)
                $cats[]=new Post_Cat($row['post_id'],$row['cat_id']);
            $ret=$cats;
        }
        else
            $ret=false;
        self::disconnect($conn);
        return $ret;
    }

    public static function getPostCatByPostAndCat($post_id,$cat_id)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_post_cat_getPostCatByPostAndCat(?,?);");
        $stmt->execute([$post_id,$cat_id]);
        self::disconnect($conn);
        if ($stmt->rowcount())
            return true;
        else
            return false;
    }

    public static function deleteByPostId($post_id)
    {
        $conn = self::connect();

        $stmt = $conn->prepare("DELETE FROM tbl_meta_relation WHERE post_id=?");
        $stmt->execute([$post_id]);

        self::disconnect($conn);

        if ($stmt->rowcount())
            return true;
        else
            return false;
    } //ok

    public static function deletePostCategoriesByCategoryId($cat_id)
    {
        $conn = self::connect();

        $stmt = $conn->prepare("DELETE FROM tbl_meta_relation WHERE post_meta_id=?");
        $stmt->execute([$cat_id]);

        self::disconnect($conn);

        if ($stmt->rowcount())
            return true;
        else
            return false;
    }


































}
