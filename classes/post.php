<?php
require_once "base.php";
class Post extends Base //18 attributes
{
    private $id;
    private $p_title;
    private $p_content;
    private $p_rate;
    private $p_image ;
    private $u_id;
    private $published;
    private $allow_comments;
    private $creation_time;
    private $last_modify;
    private $like_count;
    private $dislike_count;
    private $comment_count;
    private $deleted;
    private $u_name;   //from tbl_user
    private $f_name;   //from tbl_user
    private $l_name;   //from tbl_user
    private $cats;     //from tbl_user

    public function __construct($a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$k,$l,$m,$n,$o,$p,$q,$r)
    {
        $this->id              =   (int)$a;
        $this->p_title         =   $b;
        $this->p_content       =   $c;
        $this->p_rate          =   (int)$d;
        $this->p_image         =   $e;
        $this->u_id            =   (int)$f;
        $this->published       =   (int)$g;
        $this->allow_comments  =   (int)$h;
        $this->creation_time   =   (int)$i;
        $this->last_modify     =   (int)$j;
        $this->like_count      =   (int)$k;
        $this->dislike_count   =   (int)$l;
        $this->comment_count   =   (int)$m;
        $this->deleted         =   (int)$n;
        $this->u_name          =   $o;
        $this->f_name          =   $p;
        $this->l_name          =   $q;
        $this->cats            =   $r;
    }
    public function __set($key,$value)
    {
        $keys = ["p_title","p_content","p_rate","p_image","u_id","published","allow_comments","creation_time","last_modify","like_count","dislike_count","comment_count","deleted"];
        if (in_array($key, $keys)) {
            $conn = self::connect();
            $stmt = $conn->prepare("CALL SP_post_set(?,?,?);");
            $stmt->execute([$this->id,$key,$value]);
            $this->$key = $value;
            self::disconnect($conn);
        } 
        else
            die("invalid property!");
    }//ok
    public function __get($property)
    {     
//        $keys=["id","p_title","p_content","p_rate","p_image","u_id","published","allow_comments","creation_time","last_modify","like_count","dislike_count","comment_count","deleted"];
//        if(in_array($property, $keys))
            return $this->$property;
//        else
//            die("invalid property get!");
    }//ok

    public static function getAllPosts($published=1,$deleted=0,$limit=0,$start=0)
    {
        $conn = self::connect();
        $stmt = $conn->prepare("CALL SP_post_getAllPosts(?,?,?,?);");
        $stmt->execute([$published,$deleted,$limit,$start]);
        if($stmt->rowCount()) {
            $posts=array();
            $rows=$stmt->fetchAll();
            foreach($rows as $row) {
                if($category_rows=Post_Cat::getPostCatByPostId($row['id']))
                    foreach($category_rows as $category_row)
                        $row['cats'][]=$category_row->cat_id;
                else
                    $row['cats']=null;
                $posts[]=new Post($row['id'],$row['p_title'],$row['p_content'],$row['p_rate'],$row['p_image'],$row['u_id'],$row['published'],$row['allow_comments'],$row['creation_time'],$row['last_modify'],$row['like_count'],$row['dislike_count'],$row['comment_count'],$row['deleted'],$row['u_name'],$row['f_name'],$row['l_name'],$row['cats']);
            }
            $ret=$posts;
        }
        else
            $ret=false;
        self::disconnect($conn);
        return $ret;
    }//ok
    public static function getPostById($post_id)
    {
        $conn = self::connect();
        $query = "CALL SP_Post_getPostById(?);";
        $stmt = $conn->prepare($query);
        $stmt->execute([$post_id]);
        if ($stmt->rowCount()) {
            $row = $stmt->fetch();
            $row['cats']=[];
            if($cats = Post_Cat::getPostCatByPostId($row['id'])) {
                foreach ($cats as $cat) {
                    $row['cats'][] = $cat -> cat_id;
                }
            }
            $ret = new Post($row['id'],$row['p_title'],$row['p_content'],$row['p_rate'],$row['p_image'],$row['u_id'],$row['published'],$row['allow_comments'],$row['creation_time'],$row['last_modify'],$row['like_count'],$row['dislike_count'],$row['comment_count'],$row['deleted'],$row['u_name'],$row['f_name'],$row['l_name'],$row['cats']);
        } else
            $ret = false;
        self::disconnect($conn);
        return $ret;
    }//ok
    public static function getPostsByCategory($cat_id,$published=true,$childs=true,$limit=0,$start=0)
    {
        $conn=self::connect();
        if($limit>0)
            $limiter=" LIMIT $start,$limit";
        else
            $limiter=" ";
        if($published==false)
            $condition=" ";
        else
            $condition=" AND published=1";

        if($post_cats=Post_cat::getPostCatByCatId($cat_id,$childs=true)) {
            $post_ids=" OR tbl_post.id IN (";
            foreach($post_cats as $post_cat)
                $post_ids.=$post_cat->post_id.",";
            $post_ids=substr($post_ids,0,strlen($post_ids)-1).")";
        }

        $query="SELECT tbl_post.*,u_name,f_name,l_name FROM tbl_post,tbl_user WHERE tbl_post.u_id=tbl_user.id
                     $condition $post_ids ORDER BY creation_time DESC $limiter;"; //InnerJoin(users & posts)
        $result=$conn->query($query);
        if($result->rowCount()){
            $posts=array();
            foreach($result->fetchAll() as $row)
            {
                if($category_rows=Post_Cat::getPostCatByPostId($row['id']))
                    foreach($category_rows as $category_row)
                        $row['cats'][]=$category_row->cat_id;
                else
                    $row['cats']=null;
                $posts[]=new Post($row['id'],$row['p_title'],$row['p_content'],$row['p_rate'],$row['p_image'],$row['u_id'],$row['published'],$row['allow_comments'],$row['creation_time'],$row['last_modify'],$row['like_count'],$row['dislike_count'],$row['comment_count'],$row['deleted'],$row['u_name'],$row['f_name'],$row['l_name'],$row['cats']);
            }
            $ret=$posts;
        }
        else
            $ret=false;
        self::disconnect($conn);
        echo $query;
        return $ret;
    }//ok
    public static function getPostsByUserId($user_id,$published=true,$limit=0,$start=0)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_post_getPostsByUserId(?,?,?,?);");
        $stmt->execute([$user_id,$published,$limit,$start]);
        if($stmt->rowCount()){
            $posts=array();
            $rows=$stmt->fetchAll();
            foreach($rows as $row)
            {
                if($category_rows=Post_Cat::getPostCatByPostId($row['id']))
                    foreach($category_rows as $category_row)
                        $row['cats'][]=$category_row->cat_id;
                else
                    $row['cats']=null;
                $posts[]=new Post($row['id'],$row['p_title'],$row['p_content'],$row['p_rate'],$row['p_image'],$row['u_id'],$row['published'],$row['allow_comments'],$row['creation_time'],$row['last_modify'],$row['like_count'],$row['dislike_count'],$row['comment_count'],$row['deleted'],$row['u_name'],$row['f_name'],$row['l_name'],$row['cats']);

            }
            $ret=$posts;
        }
        else
            $ret=false;
        self::disconnect($conn);
        return $ret;
    }//ok
    public static function insertPost($p_title,$p_content,$p_image='default_post.jpg',$u_id,$published,$allow_comments,$cats)
    {
        $conn=self::connect();
        $creation_time=time();
        $stmt=$conn->prepare("CALL SP_post_insertPost(?,?,?,?,?,?,?);");
        $stmt->execute([$p_title,$p_content,$p_image,$u_id,$published,$allow_comments,$creation_time]);
        if(!$stmt->rowCount()){
            self::disconnect($conn);
            return false;
        }
        $post_id=$stmt->fetch()[0];
        $ret=$post_id;

        //SECOND QUERY for insert cats
        foreach($cats as $cat){
            $stmt=$conn->prepare("CALL SP_post_cat_insertPostCats(?,?);");
            $stmt->execute([$post_id,$cat]);
            if (!$stmt->rowCount())
                $ret=false;
        }
        self::disconnect($conn);
        return $ret;
    }//ok
    public static function deletePostById($id, $permanent=false)
    {
        $conn = self::connect();
        if($permanent)
            $query = "DELETE FROM tbl_post WHERE id=:id";
        else
            $query = "UPDATE tbl_post SET deleted=1 WHERE id=:id";
        $stmt = $conn->prepare($query);
        $stmt -> bindParam(":id", $id);
        if (!$stmt -> execute())
            $ret = false;
        else
            $ret = true;
        self::disconnect($conn);
        return $ret;
    }//ok
    public static function updatePost($p_id,$p_title,$p_content,$p_image,$u_id,$published,$allow_comments,$cats)
    {
        $ret = true;
        $conn = self::connect();
        $last_modify = time();
        $stmt = $conn->prepare("CALL SP_post_updatePost(?,?,?,?,?,?,?,?);");
        if(!$stmt->execute([$p_id,$p_title,$p_content,$p_image,$u_id,$published,$allow_comments,$last_modify]))
            $ret = false;
        if (!Post_Cat::deletePostCatByPostId($p_id))
            $ret = false;
        foreach($cats as $cat){
            $stmt = $conn->prepare("CALL SP_post_cat_insertPostCats(?,?);");
            if (!$stmt->execute([$p_id,$cat]))
                $ret = false;
        }
        self::disconnect($conn);
        return $ret;
    }//ok
    public static function searchPosts($query, $titleSearch=true, $contentSearch=true, $published=true, $limit=0, $start=0)
    {
        $conn = self::connect();

        if ($limit > 0)
            $limiter = "LIMIT $start,$limit";
        else
            $limiter = " ";

        if ($titleSearch and $contentSearch)
            $condition = "(p_title LIKE '%$query%' OR p_content LIKE '%$query%')";
        else if ($titleSearch)
            $condition = "p_title LIKE '%$query%'";
        else if ($contentSearch)
            $condition = "p_content LIKE '%$query%'";
        else
            return false;

        if ($published == true)
            $condition .= " AND published=1";

        $query = "SELECT tbl_post.*,u_name,f_name,l_name FROM tbl_post,tbl_user 
                  WHERE tbl_post.u_id=tbl_user.id AND $condition ORDER BY creation_time DESC $limiter"; //InnerJoin(user & post)
        $result = $conn -> query($query);
        if ($result -> rowCount()) {
            $posts = array();
            foreach ($result -> fetchAll() as $row) {
                if ($cats = Post_Cat::getPostCatByPostId($row['id'])) {
                    foreach ($cats as $cat) {
                        $row['cats'][] = $cat -> cat_id;
                    }
                }
                $posts[] = new Post($row['id'],$row['p_title'],$row['p_content'],$row['p_rate'],$row['p_image'],$row['u_id'],$row['published'],$row['allow_comments'],$row['creation_time'],$row['last_modify'],$row['like_count'],$row['dislike_count'],$row['comment_count'],$row['deleted'],$row['u_name'],$row['f_name'],$row['l_name'],$row['cats']);
            }
            $ret = $posts;
        } else
            $ret = false;
        self::disconnect($conn);
        return $ret;
    }
    public static function getTopPosts($limit=5)
    {
        $conn = self::connect();
        $query = "SELECT * FROM tbl_post WHERE published=1 AND p_rate >= 5 ";
        $stmt = $conn -> prepare($query);
        $stmt -> execute();
        if($stmt -> rowCount()){
            $res = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            $posts = array();
            foreach ($res as $row){
                $posts[] = new Post($row);
            }
            $ret = $posts;
        }
        else
            $ret = false;
        self::disconnect($conn);
        return $ret;
    }//ok
    public static function getPostsCounts()
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_post_getPostsCount();");
        $stmt->execute();
        if($stmt->rowCount())
            $ret=$stmt->fetch(PDO::FETCH_ASSOC);
        else
            $ret=false;
        self::disconnect($conn);
        return $ret;
    }//ok
    public static function publishPost($p_id,$published=true)
    {
        $ret=true;
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_post_publishPost(?,?);");
        if(!$stmt->execute([$p_id,$published]))
            $ret=false;
        self::disconnect($conn);
        return $ret;
    }//ok
    public static function restorePost($p_id)
    {
        $ret=true;
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_post_restorePost(?);");
        if(!$stmt->execute([$p_id]))
            $ret=false;
        self::disconnect($conn);
        return $ret;
    }//ok

}