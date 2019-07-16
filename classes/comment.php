<?php
require_once "base.php";
class Comment extends Base //9 attributes
{
    private $id;
    private $full_name;
    private $mail;
    private $website;
    private $c_text;
    private $time;
    private $post_id;
    private $parent_id;
    private $u_id;

    public function __construct($a,$b,$c,$d,$e,$f,$g,$h,$i){
        $this->id = (int)$a;
        $this->full_name = $b;
        $this->mail = $c;
        $this->website = $d;
        $this->c_text = $e;
        $this->time = (int)$f;
        $this->post_id = (int)$g;
        $this->parent_id = (int)$h;
        $this->u_id = (int)$i;
    }
    public function __get($property)
    {
        return $this->$property;
    }

    public static function getAllComments()
    {
        $conn=self::connect();
        $result=$conn->query("CALL SP_comment_getAllComments();");
        self::disconnect($conn);
        if($result->rowCount()){
            $comments=array();
            foreach($result->fetchAll() as $row)
                $comments[]=new Comment($row['id'],$row['full_name'],$row['mail'],$row['website'],$row['c_text'],$row['time'],$row['post_id'],$row['parent_id'],$row['u_id']);
            return $comments;
        }
        else
            return false;
    }//ok
    public static function getCommentsByPostId($post_id,$parent_id=1)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_comment_getCommentsByPostId(?,?);");
        $stmt->execute([$post_id,$parent_id]);
        if($stmt->rowCount()){
            $comments=array(); 
            foreach($stmt->fetchAll() as $row){
                $comments[]=new Comment($row['id'],$row['full_name'],$row['mail'],$row['website'],$row['c_text'],$row['time'],$row['post_id'],$row['parent_id'],$row['u_id']);
                $id_2=$row['id'];
                if($id_2!=1){
                  $stmt=$conn->prepare("CALL SP_comment_getCommentsByPostId(?,?);");
                $stmt->execute([$post_id,$id_2]);
                if($stmt->rowCount())
                    $comments=array_merge($comments,Comment::getCommentsByPostId($post_id,$id_2));  
                }
            }
            $ret=$comments;
        }
        else
            $ret=false;
        self::disconnect($conn);
        return $ret;
    }//ok
    public static function getCommentById($id)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_comment_getCommentById(?);");
        $stmt->execute([$id]);
        self::disconnect($conn);
        if($stmt->rowCount()){
            $row=$stmt->fetch();
            return new Comment($row['id'],$row['full_name'],$row['mail'],$row['website'],$row['c_text'],$row['time'],$row['post_id'],$row['parent_id'],$row['u_id']);
        }
        else
            return false;
    }//ok
    public static function insertComment($full_name, $mail, $website, $c_text, $post_id, $parent_id=1, $u_id)
    {
        $conn=self::connect();
        $time=time();

        //query 1 : call spam checking function
        $query="SELECT FUNC_checkCommentSpam(?,?);";
        $stmt=$conn->prepare($query);
        $stmt->execute([$u_id,$time-60]);
        $last_60s_comment_count=(int)$stmt->fetch()[0];

        //query 2 : insert comment after find out that is not spam (:max/min=2)
        if($last_60s_comment_count <= 2){
            $query="CALL SP_comment_insertComment(?,?,?,?,?,?,?,?);";
            $stmt=$conn->prepare($query);
            $stmt->execute([$full_name, $mail, $website, $c_text, $time, $post_id, $parent_id, $u_id]);
            self::disconnect($conn);
            if($stmt->rowCount())
                return true;
            else
                return false;
        } else {
            self::disconnect($conn);
            return false;
        }
    }
    public static function deleteCommentsByPostId($post_id)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_comment_deleteCommentsByPostId(?);");
        $stmt->execute([$post_id]);
        self::disconnect($conn);
        if ($stmt->rowCount())
            return true;
        else
            return false;
    }//ok
    public static function deleteCommentById($id)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_comment_deleteCommentById(?);");
        $stmt->execute([$id]);
        self::disconnect($conn);
        if ($stmt->rowCount())
            return true;
        else
            return false;
    }//ok
}
