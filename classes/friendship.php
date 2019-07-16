<?php
require_once "base.php";
class Friendship extends Base //6 attributes
{
    private $u_id_1;
    private $u_id_2;
    private $accepted;
    private $follower_u_name; //from tbl_user
    private $follower_email;  //from tbl_user
    private $follower_avatar; //from tbl_user

    public function __construct($a,$b,$c,$d,$e,$f){
        $this->u_id_1=(int)$a;
        $this->u_id_2=(int)$b;
        $this->accepted=(int)$c;
        $this->follower_u_name=$d;
        $this->follower_email=$e;
        $this->follower_avatar=$f;
    }
    public function __get($key){
        return $this->$key;
    }

    public static function getUserFollowers($id,$accepted=0)
    {
        $conn = self::connect();
        $stmt = $conn->prepare("CALL SP_friendship_getUserFollowers(?,?)");
        $stmt->execute([$id,$accepted]);
        self::disconnect($conn);
        if($stmt->rowCount()){
            $friend_array = array();
            foreach($stmt->fetchAll() as $row)
                $friend_array[] = new Friendship($row['u_id_1'],$row['u_id_2'],$row['accepted'],$row['follower_u_name'],$row['follower_email'],$row['follower_avatar']);
            return $friend_array;
        }
        else
            return false;
    }//ok
    public static function getUserFollowings($id,$accepted=1)
    {
        $conn = self::connect();
        $stmt=$conn->prepare("CALL SP_friendship_getUserFollowings(?,?)");
        $stmt->execute([$id,$accepted]);
        if($stmt->rowCount()){
            $friends=$stmt->fetchAll();
            $friend_array=array();
            foreach ($friends as $row)
                $friend_array[]=new Friendship($row['u_id_1'],$row['u_id_2'],$row['accepted'], $row['follower_u_name'], $row['follower_email'], $row['follower_avatar']);
            self::disconnect($conn);
            return $friend_array;
        }
        else
            return false;
    }//ok
    public static function getFollowingUsersIds($id)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_friendship_getFollowingUsersIds(?)");
        $stmt->execute([$id]);
        self::disconnect($conn);
        if($stmt->rowCount()) {
            $following=array();
            foreach($stmt->fetchAll() as $row)
                $following[]=$row['u_id_2'];
            return $following;
        }
        else
            return false;
    }//ok
    public static function sendFollowRequest($sender_u_id,$target_u_id)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_friendship_sendFollowRequest(?,?)");
        $stmt->execute([$sender_u_id,$target_u_id]);
        self::disconnect($conn);
        if ($stmt->rowCount())
            return true;
        else
            return false;
    }//ok
    public static function acceptFollowRequest($sender_u_id,$target_u_id)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_friendship_acceptFollowRequest(?,?)");
        $stmt->execute([$sender_u_id,$target_u_id]);
        self::disconnect($conn);
        if ($stmt->rowCount())
            return true;
        else
            return false;
    }//ok
    public static function rejectFollowRequest($sender_u_id,$target_u_id)
    {
        $conn=self::connect();
        $stmt=$conn->prepare("CALL SP_friendship_rejectFollowRequest(?,?)");
        $stmt->execute([$sender_u_id,$target_u_id]);
        self::disconnect($conn);
        if ($stmt->rowCount())
            return true;
        else
            return false;
    }//ok

}
