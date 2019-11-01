<?php
require_once "base.php";
class Comment extends Base //9 attributes
{
    private $id;
    private $u_id;
    private $post_id;
    private $parent_id;
    private $mail;
    private $website;
    private $c_text;
    private $time;
    private $enabled;

    private $parent_c_text;
    private $post_title;
    private $full_name;
    private $user_avatar;


    public function __construct($a,$b,$c,$d,$e,$f,$g,$h,$i){
        $this->id         =  (int)$a;
        $this->u_id       =  (int)$b;
        $this->post_id    =  (int)$c;
        $this->parent_id  =  (int)$d;
        $this->mail       =  $e;
        $this->website    =  $f;
        $this->c_text     =  $g;
        $this->time       =  (int)$h;
        $this->enabled    =  (int)$i;

        $this->parent_c_text  =  ($this->parent_id==0) ? '' : Comment::getCommentById($this->parent_id)->c_text;
        $this->post_title     =  ($this->post_id==0) ? '' : Post::getPostById($this->post_id)->p_title;
        $user                 =  User::getUserById($this->u_id);
        $this->full_name      =  ($this->u_id==0) ? '' : $user->u_name;
        $this->user_avatar    =  ($this->u_id==0) ? 'avatar_default.png' : $user->avatar;
    }
    public function __get($property)
    {
        return $this->$property;
    }
    public function __set($key, $value)
    {
        $keys = ["enabled", "c_text"];
        if (in_array($key, $keys)) {
            $conn = self::connect();
            /*$stmt = $conn->prepare("UPDATE tbl_comment SET ? = ? WHERE id = ?");
            $stmt->bindValue(1, $key, PDO::PARAM_STR);
            $stmt->bindValue(2, $value, PDO::PARAM_BOOL);
            $stmt->bindValue(3, $this->id, PDO::PARAM_INT);
            $stmt->execute();*/
            $conn->query("UPDATE tbl_comment SET $key = $value WHERE id = $this->id");
            $this->$key = $value;
            self::disconnect($conn);
        } 
        else
            die("invalid or not accessible property!");
    }


    public static function all($start=0, $count=8)
    {
        $conn = self::connect();

        $stmt = $conn->prepare("SELECT * FROM tbl_comment WHERE enabled=0 ORDER BY time LIMIT ?,?;");
        $stmt->bindValue(1, $start, PDO::PARAM_INT);
        $stmt->bindValue(2, $count, PDO::PARAM_INT);
        $stmt->execute();

        self::disconnect($conn);

        if ($stmt->rowcount()){
            $comments = [];
            foreach ($stmt->fetchAll() as $row)
                $comments[] = new Comment(
                    $row['id'],
                    $row['u_id'],
                    $row['post_id'],
                    $row['parent_id'],
                    $row['mail'],
                    $row['website'],
                    $row['c_text'],
                    $row['time'],
                    $row['enabled']
                );
            return $comments;
        }

        else
            return false;
    } //ok

    public static function count()
    {
        $conn = self::connect();

        $res = $conn->query("SELECT COUNT(*) FROM tbl_comment WHERE enabled=0");

        self::disconnect($conn);

        return $res->fetch()[0];
    } //ok

    public static function getCommentsByPostId($post_id, $parent_id=0)
    {
        $conn = self::connect();

        $stmt = $conn->prepare("SELECT * FROM tbl_comment WHERE post_id=? AND parent_id=? AND `enabled`=1 ORDER BY time DESC");
        $stmt->execute([$post_id, $parent_id]);

        if ($stmt->rowcount()){
            $comments = [];
            foreach ($stmt->fetchAll() as $row){
                $comments[] = new Comment(
                    $row['id'],
                    $row['u_id'],
                    $row['post_id'],
                    $row['parent_id'],
                    $row['mail'],
                    $row['website'],
                    $row['c_text'],
                    $row['time'],
                    $row['enabled']
                );
                $id = $row['id'];
                if ($id !=0 ){
                    $stmt = $conn->prepare("SELECT * FROM tbl_comment WHERE post_id=? AND parent_id=? AND `enabled`=1 ORDER BY time DESC");
                    $stmt->execute([$post_id, $id]);
                    if ($stmt->rowcount())
                        $comments = array_merge($comments, Comment::getCommentsByPostId($post_id, $id));
                }
            }
            $ret = $comments;
        }
        else
            $ret = false;

        self::disconnect($conn);

        return $ret;
    }

    public static function getCommentById($id)
    {
        $conn = self::connect();

        $stmt = $conn->prepare("SELECT * FROM tbl_comment WHERE id=?");
        $stmt->execute([$id]);

        self::disconnect($conn);

        if ($stmt->rowcount()){
            $row = $stmt->fetch();
            return new Comment(
                $row['id'],
                $row['u_id'],
                $row['post_id'],
                $row['parent_id'],
                $row['mail'],
                $row['website'],
                $row['c_text'],
                $row['time'],
                $row['enabled']
            );
        }
        else
            return false;
    }

    public static function insertComment($mail, $website, $c_text, $post_id, $parent_id=0, $u_id, $enabled=0)
    {
        $conn = self::connect();
        $time = time();

        //query 1 : call spam checking function
        $query = "SELECT FUNC_checkCommentSpam(?,?);";
        $stmt = $conn->prepare($query);
        $stmt->execute([$u_id,$time-60]);
        $last_60s_comment_count=(int)$stmt->fetch()[0];

        //query 2 : insert comment after find out that is not spam (:max/min=2)
        if ($last_60s_comment_count <= 2){
            $query = "CALL SP_comment_insertComment(?,?,?,?,?,?,?);";
            $stmt = $conn->prepare($query);
            $stmt->execute([$mail, $website, $c_text, $time, $post_id, $parent_id, $u_id]);
            self::disconnect($conn);
            if ($stmt->rowcount())
                return true;
            else
                return false;
        }
        else {
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
        if ($stmt->rowcount())
            return true;
        else
            return false;
    }

    public static function deleteCommentById($id)
    {
        $conn = self::connect();

        $stmt = $conn->prepare("CALL SP_comment_deleteCommentById(?);");
        $stmt->execute([$id]);

        self::disconnect($conn);

        if ($stmt->rowcount())
            return true;
        else
            return false;
    }

}
