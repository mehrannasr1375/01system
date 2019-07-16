<?php
require_once "base.php";
class Like extends Base //3 attributes
{
    public static function likePost($u_id,$p_id,$like_dislike=1)//1=like and 0=dislike
    {
        //-----------------------------------------------------------\\
        //    RETURNED VALUE     |        ALREADY   =>      NOW      \\
        //-----------------------------------------------------------\\
        //           0           |        -         =>       0       \\
        //           1           |        -         =>       1       \\
        //           2           |        1         =>       0       \\
        //           3           |        0         =>       1       \\
        //           4           |        1         =>       -       \\
        //           5           |        0         =>       -       \\
        //-----------------------------------------------------------\\

        $conn=self::connect();
        $stmt=$conn->prepare("SELECT FUNC_like(?,?,?);");
        $stmt->execute([$u_id,$p_id,$like_dislike]);
        self::disconnect($conn);
        $ret=$stmt->fetchColumn()[0];
        if      ($ret==0)
            return 0;
        else if ($ret==1)
            return 1;
        else if ($ret==2)
            return 2;
        else if ($ret==3)
            return 3;
        else if ($ret==4)
            return 4;
        else if ($ret==5)
            return 5;
        else
            return false;
    }//ok

}