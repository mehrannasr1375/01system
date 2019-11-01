<?php


/*** CLASS ******************************************************************************************************************************************************
 * an equivalent of 'tbl_post_meta'
 * for categories and tags
 ***************************************************************************************************************************************************************/


require_once "base.php";
class PostMeta extends Base
{

    private $id;
    private $title;
    private $parent;
    private $meta_type;


    public function __construct($a, $b, $c, $d){
        $this->id = (int)$a;
        $this->title = $b;
        $this->parent = (int)$c;
        $this->meta_type = $d;
    }//ok
    public function __get($property)
    {
        $data = ["id", "title", "parent", "meta_type"];
        if (in_array($property, $data))
            return $this->$property;
        else
            die("invalid key:'$property'");
    }//ok


    public static function all($type)
    {
        $conn = self::connect();

        $stmt = $conn->prepare("CALL SP_PostMeta_All(?)");
        $stmt->execute([$type]);

        if ($stmt->rowcount()){
            $post_metas = array();
            foreach ($stmt->fetchAll() as $row)
                $post_metas[] = new PostMeta(
                    $row['id'],
                    $row['title'],
                    $row['parent'],
                    $row['meta_type']
                );
            $ret = $post_metas;
        }
        else
            $ret = false;

        self::disconnect($conn);

        return $ret;
    }//ok

    public static function allCategoriesByParent($id)
    {
        $conn = self::connect();

        $stmt = $conn->prepare("CALL SP_PostMeta_AllCategoriesByParent(?)");
        $stmt->execute([$id]);

        self::disconnect($conn);

        if ($stmt->rowcount()){
            $post_metas = array();
            foreach ($stmt->fetchAll() as $row)
                $post_metas[] = new PostMeta(
                    $row['id'],
                    $row['title'],
                    $row['parent'],
                    $row['meta_type']
                );
            return $post_metas;
        }
        else
            return false;
    }//ok

    public static function allCategoriesIdsByParent($id)
    {
        $conn = self::connect();

        $stmt = $conn->prepare("CALL SP_PostMeta_AllCategoriesByParent(?)");
        $stmt->execute([$id]);

        self::disconnect($conn);

        if ($stmt->rowcount()){
            $ret= [];
            foreach ($stmt->fetchAll() as $row)
                $ret[] = $row['id'];
            return $ret;
        }
        else
            return false;
    }//ok

    public static function getCategoriesCount()
    {
        $conn = self::connect();

        $res = $conn->query("CALL SP_PostMeta_getCategoriescount()");

        self::disconnect($conn);

        if ($res->rowcount())
            return $res->fetchColumn();
        else
            return 0;
    }//ok

    public static function allTags()
    {
        $conn = self::connect();

        $result = $conn->query("CALL SP_Tag_All()");
        self::disconnect($conn);

        if ($result->rowcount()){
            $post_metas = array();
            foreach ($result->fetchAll() as $row)
                $post_metas[] = new PostMeta(
                    $row['id'],
                    $row['title'],
                    $row['parent'],
                    $row['meta_type']
                );
            return $post_metas;
        }
        else
            return false;
    }//ok

    public static function delete($id)
    {
        $conn = self::connect();

        $stmt = $conn->prepare("DELETE FROM tbl_post_meta WHERE id = ?");
        $stmt->execute([$id]);

        self::disconnect($conn);

        if ($stmt->rowcount())
            return $id;
        else
            return false;
    } //ok

    public static function create($type='category', $title, $parent=0)
    {
        $conn = self::connect();

        // first query
        $stmt = $conn->prepare("INSERT INTO tbl_post_meta(meta_type, title, parent) VALUES(?,?,?)");
        $stmt->execute([$type, $title, $parent]);
        if (!$stmt->rowcount())
            return false;

        // second query
        $stmt = $conn->prepare("SELECT LAST_INSERT_ID()");
        $stmt->execute();

        self::disconnect($conn);

        if ($stmt->rowcount())
            return $stmt->fetch()[0];
        else
            return false;
    } //ok


    public static function exists($type='category', $title, $parent=0)
    {
        $conn = self::connect();

        $stmt = $conn->prepare("SELECT * FROM tbl_post_meta WHERE meta_type=? AND title=? AND parent=?");
        $stmt->execute([$type, $title, $parent]);

        self::disconnect($conn);

        if ($stmt->rowcount())
            return true;
        else
            return false;
    }


























}

