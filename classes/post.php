<?php
require_once "base.php";
require_once "PostMetaRelation.php";
class Post extends Base //18 attributes
{
    private $id;
    private $p_title;
    private $p_content;
    private $p_rate;
    private $p_image;
    private $u_id;
    private $published;
    private $allow_comments;
    private $creation_time;
    private $last_modify;
    private $like_count;
    private $dislike_count;
    private $comment_count;
    private $deleted;
    private $u_name;
    private $f_name;
    private $l_name;
    private $access_level;
    private $cats; // not use for construct
    private $tags; // not use for construct


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
        $this->access_level    =   $r;

        $this->cats            =   PostMetaRelation::getPostCategories((int)$a);
        $this->tags            =   PostMetaRelation::getPostTags((int)$a);
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
    }
    public function __get($property)
    {     
//        $keys=["id","p_title","p_content","p_rate","p_image","u_id","published","allow_comments","creation_time","last_modify","like_count","dislike_count","comment_count","deleted"];
//        if(in_array($property, $keys))
            return $this->$property;
//        else
//            die("invalid property get!");
    }


    public static function getAllPosts($published=1, $deleted=0, $limit=0, $start=0, $access_level=0)
    {
        $conn = self::connect();

        $stmt = $conn->prepare("CALL SP_post_getAllPosts(?,?,?,?);");
        $stmt->execute([$published,$deleted,$limit,$start]);
        if ($stmt->rowcount()) {
            $posts = array();
            $rows = $stmt->fetchAll();
            foreach ($rows as $row) {
                $posts[] = new Post(
                    $row['id'],
                    $row['p_title'],
                    $row['p_content'],
                    $row['p_rate'],
                    $row['p_image'],
                    $row['u_id'],
                    $row['published'],
                    $row['allow_comments'],
                    $row['creation_time'],
                    $row['last_modify'],
                    $row['like_count'],
                    $row['dislike_count'],
                    $row['comment_count'],
                    $row['deleted'],
                    $row['u_name'],
                    $row['f_name'],
                    $row['l_name'],
                    $row['access_level']
                );
            }
            $ret = $posts;
        }
        else
            $ret = false;

        self::disconnect($conn);

        return $ret;
    }

    public static function getPostById($post_id)
    {
        $conn = self::connect();

        $query = "CALL SP_Post_getPostById(?);";
        $stmt = $conn->prepare($query);
        $stmt->execute([$post_id]);

        if ($stmt->rowcount()){
            $row = $stmt->fetch();

            $ret = new Post(
                $row['id'],
                $row['p_title'],
                $row['p_content'],
                $row['p_rate'],
                $row['p_image'],
                $row['u_id'],
                $row['published'],
                $row['allow_comments'],
                $row['creation_time'],
                $row['last_modify'],
                $row['like_count'],
                $row['dislike_count'],
                $row['comment_count'],
                $row['deleted'],
                $row['u_name'],
                $row['f_name'],
                $row['l_name'],
                $row['access_level']
            );
        } else
            $ret = false;

        self::disconnect($conn);

        return $ret;
    }

    public static function getPostsByCategory($cat_id,$published=true,$childs=true,$limit=0,$start=0,$access_level=0)
    {
        $conn = self::connect();
        if ($limit>0)
            $limiter=" LIMIT $start,$limit";
        else
            $limiter=" ";
        if ($published==false)
            $condition=" ";
        else
            $condition=" AND published=1";

        if ($post_cats=Post_cat::getPostCatByCatId($cat_id,$childs=true)) {
            $post_ids=" OR tbl_post.id IN (";
            foreach ($post_cats as $post_cat)
                $post_ids.=$post_cat->post_id.",";
            $post_ids=substr($post_ids,0,strlen($post_ids)-1).")";
        }

        $query = "SELECT tbl_post.*,u_name,f_name,l_name FROM tbl_post,tbl_user WHERE tbl_post.u_id=tbl_user.id
                     $condition $post_ids ORDER BY creation_time DESC $limiter;"; //InnerJoin(users & posts)
        $result=$conn->query($query);
        if($result->rowcount()){
            $posts = array();
            foreach ($result->fetchAll() as $row)
            {
                $posts[] = new Post(
                    $row['id'],
                    $row['p_title'],
                    $row['p_content'],
                    $row['p_rate'],
                    $row['p_image'],
                    $row['u_id'],
                    $row['published'],
                    $row['allow_comments'],
                    $row['creation_time'],
                    $row['last_modify'],
                    $row['like_count'],
                    $row['dislike_count'],
                    $row['comment_count'],
                    $row['deleted'],
                    $row['u_name'],
                    $row['f_name'],
                    $row['l_name'],
                    $row['access_level']
                );
            }
            $ret=$posts;
        }
        else
            $ret=false;
        self::disconnect($conn);
        echo $query;
        return $ret;
    }

    public static function getPostsByUserId($user_id,$published=true,$limit=0,$start=0,$access_level=0)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_post_getPostsByUserId(?,?,?,?);");
        $stmt->execute([$user_id,$published,$limit,$start]);
        if ($stmt->rowcount()){
            $posts = array();
            $rows = $stmt->fetchAll();
            foreach ($rows as $row)
            {
                $posts[] = new Post(
                    $row['id'],
                    $row['p_title'],
                    $row['p_content'],
                    $row['p_rate'],
                    $row['p_image'],
                    $row['u_id'],
                    $row['published'],
                    $row['allow_comments'],
                    $row['creation_time'],
                    $row['last_modify'],
                    $row['like_count'],
                    $row['dislike_count'],
                    $row['comment_count'],
                    $row['deleted'],
                    $row['u_name'],
                    $row['f_name'],
                    $row['l_name'],
                    $row['access_level']
                );
            }
            $ret=$posts;
        }
        else
            $ret=false;
        self::disconnect($conn);
        return $ret;
    }

    public static function getTopPosts($limit=5,$access_level='public')
    {
        $conn = self::connect();

        $query = "SELECT tbl_post.*, u_name, f_name, l_name
                    FROM tbl_post, tbl_user 
                    WHERE tbl_post.u_id=tbl_user.id 
                    AND published=1 
                    AND tbl_post.deleted != true 
                    AND p_rate >= 5
                    AND tbl_post.id NOT IN (1) LIMIT $limit";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        if ($stmt->rowcount()) {
            $posts = array();
            $rows = $stmt->fetchAll();
            foreach ($rows as $row) {
                $posts[] = new Post(
                    $row['id'],
                    $row['p_title'],
                    $row['p_content'],
                    $row['p_rate'],
                    $row['p_image'],
                    $row['u_id'],
                    $row['published'],
                    $row['allow_comments'],
                    $row['creation_time'],
                    $row['last_modify'],
                    $row['like_count'],
                    $row['dislike_count'],
                    $row['comment_count'],
                    $row['deleted'],
                    $row['u_name'],
                    $row['f_name'],
                    $row['l_name'],
                    $row['access_level']
                );
            }
            $ret = $posts;
        }
        else
            $ret = false;

        self::disconnect($conn);

        return $ret;
    } //ok

    public static function getLastPosts($limit=5,$access_level='public')
    {
        $conn = self::connect();

        $query = "SELECT tbl_post.*, u_name, f_name, l_name
                    FROM tbl_post, tbl_user
                    WHERE tbl_post.u_id=tbl_user.id 
                    AND published=1 
                    AND tbl_post.deleted != true
                    AND tbl_post.id NOT IN (1) 
                    ORDER BY creation_time DESC
                    LIMIT $limit";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        if ($stmt->rowcount()) {
            $posts = array();
            $rows = $stmt->fetchAll();
            foreach ($rows as $row) {
                $posts[] = new Post(
                    $row['id'],
                    $row['p_title'],
                    $row['p_content'],
                    $row['p_rate'],
                    $row['p_image'],
                    $row['u_id'],
                    $row['published'],
                    $row['allow_comments'],
                    $row['creation_time'],
                    $row['last_modify'],
                    $row['like_count'],
                    $row['dislike_count'],
                    $row['comment_count'],
                    $row['deleted'],
                    $row['u_name'],
                    $row['f_name'],
                    $row['l_name'],
                    $row['access_level']
                );
            }
            $ret = $posts;
        }
        else
            $ret = false;

        self::disconnect($conn);

        return $ret;
    } //ok

    public static function create($p_title,$p_content,$p_image='default_post.jpg',$u_id,$published,$allow_comments,$cats,$tags,$access_level=0)
    {
        $conn = self::connect();

        // FIRST QUERY for insert post & get it`s id
        $creation_time = time();
        $stmt = $conn->prepare("CALL SP_post_insertPost(?,?,?,?,?,?,?,?);");
        $stmt->execute([$p_title,$p_content,$p_image,$u_id,$published,$allow_comments,$creation_time,$access_level]);
        
        if (!$stmt->rowcount()){
            self::disconnect($conn);
            return false;
        }
        $post_id = $stmt->fetch()[0];
        $ret = $post_id;

        // SECOND QUERY for insert cats
        foreach ($cats as $cat_id){
            $stmt = $conn->prepare("INSERT INTO tbl_meta_relation(post_id, post_meta_id) VALUES(?,?)");
            $stmt->execute([$post_id, $cat_id]);
            if (!$stmt->rowcount())
                $ret = false;
        }

        // THIRD QUERY for insert tags
        foreach ($tags as $tag_id){
            $stmt = $conn->prepare("INSERT INTO tbl_meta_relation(post_id, post_meta_id) VALUES(?,?)");
            $stmt->execute([$post_id, $tag_id]);
            if (!$stmt->rowcount())
                $ret = false;
        }

        // FORTH QUERY for insert pic
        Pic::insertPic($p_image, $u_id);

        self::disconnect($conn);

        return $ret;
    } //ok

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
    }

    public static function update($p_id,$p_title,$p_content,$p_image='default_post.jpg',$u_id,$published,$allow_comments,$cats,$tags,$access_level=0)
    {
        $ret = true;

        $conn = self::connect();

        // update post fields
        $last_modify = time();
        $stmt = $conn->prepare("CALL SP_Post_update(?,?,?,?,?,?,?,?,?);");
        $stmt->execute([$p_id,$p_title,$p_content,$p_image,$u_id,$published,$allow_comments,$last_modify,$access_level]);
        if (!$stmt->rowcount()){
            self::disconnect($conn);
            return false;
        }
        $ret = $stmt->fetch()[0];

         // delete old meta relations
         PostMetaRelation::deleteByPostId($p_id);

        // SECOND QUERY for insert cats
        foreach ($cats as $cat){
             $stmt = $conn->prepare("INSERT INTO tbl_meta_relation(post_id,post_meta_id) VALUES (?,?)");
             if (!$stmt->execute([$p_id,$cat]))
                 $ret = false;
         }

        // THIRD QUERY for insert tags
        foreach ($tags as $tag){
             $stmt = $conn->prepare("INSERT INTO tbl_meta_relation(post_id,post_meta_id) VALUES (?,?)");
             if (!$stmt->execute([$p_id,$tag]))
                 $ret = false;
         }

        // FORTH QUERY for insert pic
        Pic::insertPic($p_image, $u_id);

        self::disconnect($conn);

        return $ret;
    } //ok

    public static function searchPosts($query, $titleSearch=true, $contentSearch=true, $published=true, $limit=0, $start=0,$access_level=0)
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
        if ($result -> rowcount()) {
            $posts = array();
            foreach ($result -> fetchAll() as $row) {
                $posts[] = new Post(
                    $row['id'],
                    $row['p_title'],
                    $row['p_content'],
                    $row['p_rate'],
                    $row['p_image'],
                    $row['u_id'],
                    $row['published'],
                    $row['allow_comments'],
                    $row['creation_time'],
                    $row['last_modify'],
                    $row['like_count'],
                    $row['dislike_count'],
                    $row['comment_count'],
                    $row['deleted'],
                    $row['u_name'],
                    $row['f_name'],
                    $row['l_name'],
                    $row['access_level']
                );
            }
            $ret = $posts;
        } else
            $ret = false;
        self::disconnect($conn);
        return $ret;
    }

    public static function getPostscounts()
    {
        $conn = self::connect();

        $stmt = $conn->prepare("CALL SP_post_getPostscount();");
        $stmt->execute();

        self::disconnect($conn);

        if ($stmt->rowcount())
            return $stmt->fetch(PDO::FETCH_ASSOC);
        else
            return false;
    }

    public static function publishPost($p_id,$published=true)
    {
        $ret=true;
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_post_publishPost(?,?);");
        if(!$stmt->execute([$p_id,$published]))
            $ret=false;
        self::disconnect($conn);
        return $ret;
    }

    public static function restorePost($p_id)
    {
        $ret=true;
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_post_restorePost(?);");
        if(!$stmt->execute([$p_id]))
            $ret=false;
        self::disconnect($conn);
        return $ret;
    }

}