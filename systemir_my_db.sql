-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 16, 2019 at 11:22 PM
-- Server version: 5.6.41
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `systemir_my_db`
--
CREATE DATABASE IF NOT EXISTS `systemir_my_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `systemir_my_db`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `SP_cat_deleteCatById`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_cat_deleteCatById` (IN `_cat_id` INT)  BEGIN
	DELETE FROM tbl_cat WHERE id=_cat_id;
END$$

DROP PROCEDURE IF EXISTS `SP_cat_getAllCats`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_cat_getAllCats` ()  BEGIN
	SELECT * FROM tbl_cat where tbl_cat.id not in(1) ORDER BY tbl_cat.id ;
END$$

DROP PROCEDURE IF EXISTS `SP_cat_getCatById`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_cat_getCatById` (IN `_cat_id` INT)  BEGIN
	SELECT * FROM tbl_cat WHERE id=_cat_id;
END$$

DROP PROCEDURE IF EXISTS `SP_cat_getCatsByParentId`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_cat_getCatsByParentId` (IN `_cat_parent` INT)  BEGIN
	SELECT * FROM tbl_cat WHERE cat_parent=_cat_parent AND tbl_cat.id not in(1);
END$$

DROP PROCEDURE IF EXISTS `SP_cat_getCatsCount`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_cat_getCatsCount` ()  BEGIN
	SELECT count(id)
		from tbl_cat;
END$$

DROP PROCEDURE IF EXISTS `SP_cat_insertCat`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_cat_insertCat` (IN `_cat_name` VARCHAR(20) CHARSET utf8, IN `_cat_parent` INT)  BEGIN
	INSERT INTO tbl_cat(cat_name, cat_parent) VALUES(_cat_name,_cat_parent);
END$$

DROP PROCEDURE IF EXISTS `SP_comment_deleteCommentById`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_comment_deleteCommentById` (IN `_id` INT)  BEGIN
	DELETE FROM tbl_comment WHERE id=_id;
END$$

DROP PROCEDURE IF EXISTS `SP_comment_deleteCommentsByPostId`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_comment_deleteCommentsByPostId` (IN `_id` INT)  BEGIN
	DELETE FROM tbl_comment WHERE post_id=_id;
END$$

DROP PROCEDURE IF EXISTS `SP_comment_getAllComments`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_comment_getAllComments` ()  BEGIN
	SELECT * FROM tbl_comment ORDER BY time DESC;
END$$

DROP PROCEDURE IF EXISTS `SP_comment_getCommentById`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_comment_getCommentById` (IN `_id` INT)  BEGIN
	SELECT * FROM tbl_comment WHERE id=_id;
END$$

DROP PROCEDURE IF EXISTS `SP_comment_getCommentsByPostId`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_comment_getCommentsByPostId` (IN `_post_id` INT, IN `_parent_id` INT)  BEGIN
	SELECT * FROM tbl_comment WHERE post_id=_post_id AND parent_id=_parent_id AND id>1 ORDER BY time DESC;
END$$

DROP PROCEDURE IF EXISTS `SP_comment_insertComment`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_comment_insertComment` (IN `_full_name` VARCHAR(45) CHARSET utf8, IN `_mail` VARCHAR(45), IN `_website` VARCHAR(45), IN `_c_text` VARCHAR(1500) CHARSET utf8, IN `_time` INT, IN `_post_id` INT, IN `_parent_id` INT, IN `_u_id` INT)  BEGIN
	
    INSERT INTO tbl_comment(full_name,   mail,   website,  c_text,  `time`,  post_id,  parent_id,  u_id) 
					 VALUES(_full_name, _mail,  _website, _c_text,  _time,  _post_id, _parent_id, _u_id);
    
END$$

DROP PROCEDURE IF EXISTS `SP_friendship_acceptFollowRequest`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_friendship_acceptFollowRequest` (IN `_user_1` INT, IN `_user_2` INT)  BEGIN
	UPDATE tbl_friendship SET accepted = 1 WHERE u_id_1 = _user_1 AND u_id_2 = _user_2;
END$$

DROP PROCEDURE IF EXISTS `SP_friendship_getFollowingUsersIds`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_friendship_getFollowingUsersIds` (IN `_id` INT)  BEGIN
	SELECT u_id_2 FROM tbl_friendship WHERE u_id_1=_id AND accepted=1;
END$$

DROP PROCEDURE IF EXISTS `SP_friendship_getUserFollowers`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_friendship_getUserFollowers` (IN `_id` INT, IN `_accepted` TINYINT(1))  BEGIN
	SELECT  tbl_friendship.u_id_1,
			tbl_friendship.u_id_2,
            tbl_friendship.accepted,
			tbl_user.u_name  AS follower_u_name,
			tbl_user.u_email AS follower_email,
			tbl_user.avatar  AS follower_avatar
	FROM tbl_friendship, tbl_user
	WHERE u_id_2=_id
	AND tbl_friendship.u_id_1 = tbl_user.id
	AND tbl_friendship.accepted=_accepted;
END$$

DROP PROCEDURE IF EXISTS `SP_friendship_getUserFollowings`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_friendship_getUserFollowings` (IN `_id` INT, IN `_accepted` TINYINT(1))  BEGIN
	SELECT  tbl_friendship.u_id_1,
			tbl_friendship.u_id_2,
            tbl_friendship.accepted,
			tbl_user.u_name  AS follower_u_name,
			tbl_user.u_email AS follower_email,
			tbl_user.avatar  AS follower_avatar
	FROM tbl_friendship, tbl_user
	WHERE u_id_1=_id
	AND tbl_friendship.u_id_2 = tbl_user.id
	AND tbl_friendship.accepted=_accepted;
END$$

DROP PROCEDURE IF EXISTS `SP_friendship_rejectFollowRequest`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_friendship_rejectFollowRequest` (IN `_user_1` INT, IN `_user_2` INT)  BEGIN
	DELETE FROM tbl_friendship WHERE u_id_1 = _user_1 AND u_id_2 = _user_2;
END$$

DROP PROCEDURE IF EXISTS `SP_friendship_sendFollowRequest`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_friendship_sendFollowRequest` (IN `_sender` INT, IN `_target` INT)  BEGIN
	IF (NOT EXISTS(SELECT * FROM tbl_friendship WHERE u_id_1=_sender AND u_id_2=_target)) THEN 
    	INSERT INTO tbl_friendship(u_id_1,u_id_2,accepted) VALUES (_sender, _target, 0);
    END IF;
END$$

DROP PROCEDURE IF EXISTS `SP_getInformationsOfUser`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_getInformationsOfUser` (IN `_u_id` INT)  BEGIN

select
        
    (SELECT count(tbl_friendship.u_id_1) 
		from tbl_friendship where u_id_2=_u_id and accepted=1) as followers_count,

    (SELECT count(tbl_friendship.u_id_1) 
		from tbl_friendship where u_id_1=_u_id and accepted=1) as followings_count,

	(SELECT count(tbl_friendship.u_id_1)
		from tbl_friendship where u_id_2=_u_id and accepted=0) as inquene_followers_count,
        
	(SELECT count(tbl_post.id)
		from tbl_post where u_id=_u_id) as posts_count,
        
	(SELECT count(tbl_pic.id)
	from tbl_pic where u_id=_u_id) as images_count;
	
    
END$$

DROP PROCEDURE IF EXISTS `SP_getLikesOfUser`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_getLikesOfUser` (IN `_u_id` INT)  BEGIN
	SELECT COUNT(*) FROM tbl_like where u_id_2 = _u_id;
END$$

DROP PROCEDURE IF EXISTS `SP_pic_deletePicById`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_pic_deletePicById` (IN `_id` INT)  BEGIN
	DELETE FROM tbl_pic WHERE id=_id;
END$$

DROP PROCEDURE IF EXISTS `SP_pic_deletePicsOfUser`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_pic_deletePicsOfUser` (IN `_u_id` INT)  BEGIN
	DELETE FROM tbl_pic WHERE u_id=_u_id;
END$$

DROP PROCEDURE IF EXISTS `SP_pic_getPicsCountOfUser`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_pic_getPicsCountOfUser` (IN `_u_id` INT)  BEGIN
	SELECT COUNT(*) FROM tbl_pic WHERE u_id=_u_id;    
END$$

DROP PROCEDURE IF EXISTS `SP_pic_getPicsOfUser`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_pic_getPicsOfUser` (IN `_u_id` INT, IN `_limit` INT, IN `_start` INT)  BEGIN
	
	IF( _limit > 0) THEN
		SELECT * FROM tbl_pic ORDER BY `date` DESC LIMIT _start,_limit;    
	ELSE 
		SELECT * FROM tbl_pic  ORDER BY `date` DESC;
	END IF;

END$$

DROP PROCEDURE IF EXISTS `SP_pic_insertPic`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_pic_insertPic` (IN `_pic_name` VARCHAR(45), IN `_u_id` INT, IN `_date` INT)  BEGIN
	INSERT INTO tbl_pic(pic_name, u_id, date) VALUES(_pic_name,_u_id,_date);
END$$

DROP PROCEDURE IF EXISTS `SP_post_cat_deletePostCatByCatId`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_post_cat_deletePostCatByCatId` (IN `_id` INT)  BEGIN
	DELETE FROM tbl_post_cat WHERE cat_id=_id;
END$$

DROP PROCEDURE IF EXISTS `SP_post_cat_deletePostCatByPostId`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_post_cat_deletePostCatByPostId` (IN `_id` INT)  BEGIN
	DELETE FROM tbl_post_cat WHERE post_id=_id;
END$$

DROP PROCEDURE IF EXISTS `SP_post_cat_getPostCatByPostAndCat`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_post_cat_getPostCatByPostAndCat` (IN `_post_id` INT, IN `_cat_id` INT)  BEGIN
	SELECT * FROM tbl_post_cat WHERE post_id=_post_id AND cat_id=_cat_id;
END$$

DROP PROCEDURE IF EXISTS `SP_post_cat_getPostCatByPostId`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_post_cat_getPostCatByPostId` (IN `_id` INT)  BEGIN
	SELECT * FROM tbl_post_cat WHERE post_id=_id;
END$$

DROP PROCEDURE IF EXISTS `SP_post_cat_insertPostCats`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_post_cat_insertPostCats` (IN `_post_id` INT(11), IN `_cat_id` INT(11))  BEGIN
	INSERT INTO tbl_post_cat(post_id,cat_id) VALUES (_post_id,_cat_id);
END$$

DROP PROCEDURE IF EXISTS `SP_post_getAllPosts`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_post_getAllPosts` (IN `_published` BOOLEAN, IN `_deleted` BOOLEAN, IN `_limit` INT, IN `_start` INT)  BEGIN
	
	IF( _limit > 0) THEN
		SELECT tbl_post.*, u_name, f_name, l_name
        FROM tbl_post, tbl_user
		WHERE 
			tbl_post.u_id=tbl_user.id 
            AND published=_published 
            AND tbl_post.deleted=_deleted
            AND tbl_post.id not in (1)
        ORDER BY creation_time DESC 
        LIMIT _start,_limit;    
   
        
	ELSE 
		SELECT tbl_post.*, u_name, f_name, l_name
        FROM tbl_post, tbl_user
		WHERE tbl_post.u_id=tbl_user.id 
            AND published=_published
            AND tbl_post.deleted=_deleted
            AND tbl_post.id not in (1)
            ORDER BY creation_time DESC;
	END IF;

END$$

DROP PROCEDURE IF EXISTS `SP_Post_getPostById`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_Post_getPostById` (IN `_p_id` INT)  BEGIN
	SELECT tbl_post.*,
			tbl_user.u_name, 
            tbl_user.f_name,
            tbl_user.l_name
	FROM tbl_post, tbl_user
	WHERE tbl_post.u_id=tbl_user.id AND tbl_post.id= _p_id;
END$$

DROP PROCEDURE IF EXISTS `SP_post_getPostsByUserId`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_post_getPostsByUserId` (IN `_u_id` INT, IN `_published` INT, IN `_limit` INT, IN `_start` INT)  BEGIN
	
	IF( _limit > 0) THEN
		SELECT tbl_post.*, u_name, f_name, l_name
        FROM tbl_post, tbl_user
		WHERE 
			tbl_post.u_id=tbl_user.id 
            AND published=_published 
            AND u_id=_u_id
        ORDER BY creation_time DESC 
        LIMIT _start,_limit;    
	ELSE 
    
		SELECT tbl_post.*, u_name, f_name, l_name
        FROM tbl_post, tbl_user
		WHERE tbl_post.u_id=tbl_user.id 
            AND published=_published
            AND u_id=_u_id
            ORDER BY creation_time DESC;
	END IF;

END$$

DROP PROCEDURE IF EXISTS `SP_post_getPostsCount`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_post_getPostsCount` ()  BEGIN

	DECLARE _published, _unpublished, _deleted INT default 0;
    
	SELECT COUNT(tbl_post.id) INTO _published    FROM   tbl_post   WHERE   published=1    AND    deleted=0 ;     
	SELECT COUNT(tbl_post.id) INTO _unpublished  FROM   tbl_post   WHERE   published=0    AND    deleted=0 ;
	SELECT COUNT(tbl_post.id) INTO _deleted      FROM   tbl_post   WHERE   deleted=1 ;  
    
    SELECT  _published as published , _unpublished as unpublished  , _deleted as deleted  ;
		
END$$

DROP PROCEDURE IF EXISTS `SP_post_insertPost`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_post_insertPost` (IN `_p_title` VARCHAR(50) CHARSET utf8, IN `_p_content` TEXT CHARSET utf8, IN `_p_image` VARCHAR(100), IN `_u_id` INT, IN `_published` TINYINT(1), IN `_allow_comments` TINYINT(1), IN `_creation_time` INT)  begin
INSERT INTO tbl_post(			    p_title,
                                        p_content,
                                        p_image,
                                        u_id,
                                        published,      
                                        allow_comments,
                                        creation_time
                                       )
                                VALUES (_p_title,
										_p_content,
                                        _p_image,
                                        _u_id,
                                        _published,
                                        _allow_comments,
                                        _creation_time 
                                      );
SELECT LAST_INSERT_ID();
end$$

DROP PROCEDURE IF EXISTS `SP_post_publishPost`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_post_publishPost` (IN `_p_id` INT, IN `_published` INT)  BEGIN
	UPDATE tbl_post SET 
						published=_published
                    WHERE tbl_post.id=_p_id;
END$$

DROP PROCEDURE IF EXISTS `SP_post_restorePost`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_post_restorePost` (IN `_p_id` INT)  BEGIN
	UPDATE tbl_post SET 
						deleted=0
                    WHERE tbl_post.id=_p_id;
END$$

DROP PROCEDURE IF EXISTS `SP_post_set`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_post_set` (IN `_property_name` VARCHAR(20), IN `_property_value` VARCHAR(20), IN `_id` INT)  BEGIN	
    UPDATE tbl_post SET _property_name = _property_value 
    WHERE id = _id ;
END$$

DROP PROCEDURE IF EXISTS `SP_post_updatePost`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_post_updatePost` (IN `_p_id` INT(11), IN `_p_title` VARCHAR(50) CHARSET utf8, IN `_p_content` TEXT CHARSET utf8, IN `_p_image` VARCHAR(100) CHARSET utf8, IN `_u_id` INT(11), IN `_published` TINYINT(1), IN `_allow_comments` TINYINT(1), IN `_last_modify` INT(11))  BEGIN
	UPDATE tbl_post SET 
						p_title=_p_title,
						p_content=_p_content,
						p_image=_p_image,
						u_id=_u_id,
						published=_published,
						allow_comments=_allow_comments,
						last_modify=_last_modify
                    WHERE tbl_post.id=_p_id;
END$$

DROP PROCEDURE IF EXISTS `SP_sent_mails_checkEmailExists`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_sent_mails_checkEmailExists` (IN `_u_email` VARCHAR(45), IN `_now_time` INT(11))  BEGIN
	SELECT COUNT(*) FROM tbl_sent_mails WHERE u_email=_u_email AND 'time'>_now_time-3600;
END$$

DROP PROCEDURE IF EXISTS `SP_sent_mails_insertSendRecord`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_sent_mails_insertSendRecord` (IN `_u_email` VARCHAR(45), IN `_time` INT(11))  BEGIN
	INSERT INTO tbl_sent_mails(u_email,time) VALUES(_u_email, _time);
    DELETE FROM tbl_sent_mails WHERE time<_time-3600;
END$$

DROP PROCEDURE IF EXISTS `SP_user_activateUser`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_user_activateUser` (IN `_u_name` VARCHAR(25), IN `_activation_code` VARCHAR(32))  BEGIN
	IF(EXISTS(SELECT * FROM tbl_user WHERE u_name=_u_name AND activation_code=_activation_code)) THEN
		UPDATE tbl_user SET activated=1 WHERE u_name=_u_name;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `SP_user_authenticateUser`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_user_authenticateUser` (IN `_u_name` VARCHAR(25), IN `_u_pass` VARCHAR(32))  BEGIN
	SELECT * FROM tbl_user WHERE u_name =_u_name AND u_pass =_u_pass AND activated = 1;
END$$

DROP PROCEDURE IF EXISTS `SP_user_changeUserTypeById`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_user_changeUserTypeById` (IN `_id` INT, IN `_u_type` INT)  BEGIN
	UPDATE tbl_user SET u_type=_u_type WHERE id=_id;
END$$

DROP PROCEDURE IF EXISTS `SP_user_checkDosAndChangeUserPass`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_user_checkDosAndChangeUserPass` (IN `_u_email` VARCHAR(45), IN `_time` INT(11), IN `_u_name` VARCHAR(25), IN `_u_pass` VARCHAR(32))  BEGIN
	DECLARE mail_count INT;
    DECLARE result INT;
    
    
	START TRANSACTION;    
		#1 : check mail counts of last hour
		SET mail_count = (SELECT count(*) FROM tbl_sent_mails WHERE `time` > (select (_time - 3600)));
		
		IF (mail_count > 30) THEN
				set result = 0;
			
		ELSE
				#2 : update password of user
				IF EXISTS(SELECT * FROM tbl_user WHERE u_email=_u_email) THEN
					UPDATE tbl_user SET u_pass=_u_pass WHERE u_name=_u_name;
					set result = 1;
				ELSE 
					set result = 0;
				END IF;
		END IF;
		
		#3 : insert mail send event to tbl_sent_mails
		IF(result = 1) THEN
			INSERT INTO tbl_sent_mails(u_email,`time`) VALUES(_u_email,_time) ;
			set result = 1;
		ELSE 
			set result = 0;
		END IF;    
		
		#4 : delete old records of emails table
		DELETE FROM tbl_sent_mails WHERE `time`<(select (_time - 3600));
		
		#5 : return result
		select result;
	COMMIT;
    
    
END$$

DROP PROCEDURE IF EXISTS `SP_user_deleteUserById`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_user_deleteUserById` (IN `_id` INT, IN `_permanent` INT)  BEGIN

	IF (NOT EXISTS(SELECT * FROM tbl_user WHERE id=_id AND u_type=1)) THEN 
		IF(_permanent = 0) THEN
		UPDATE tbl_user SET deleted=1 WHERE id=_id;
		END IF;
		IF(_permanent = 1) THEN
			DELETE FROM tbl_user WHERE id=_id;
		END IF;
    END IF;
    
END$$

DROP PROCEDURE IF EXISTS `SP_user_getAllUsers`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_user_getAllUsers` ()  BEGIN
	SELECT * FROM tbl_user ORDER BY id ;
END$$

DROP PROCEDURE IF EXISTS `SP_user_getRandomHash`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_user_getRandomHash` (IN `_u_name` VARCHAR(20))  BEGIN
	SELECT random_hash FROM tbl_user WHERE u_name=_u_name;
END$$

DROP PROCEDURE IF EXISTS `SP_user_getUserById`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_user_getUserById` (IN `_id` INT)  BEGIN
	SELECT * FROM tbl_user WHERE id=_id;
END$$

DROP PROCEDURE IF EXISTS `SP_user_getUserByName`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_user_getUserByName` (IN `_u_name` VARCHAR(25))  BEGIN
	SELECT * FROM tbl_user WHERE u_name=_u_name;
END$$

DROP PROCEDURE IF EXISTS `SP_user_getUserIdByPostId`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_user_getUserIdByPostId` (IN `_id` INT)  BEGIN
	SELECT * FROM tbl_user,tbl_post WHERE tbl_user.id = tbl_post.u_id AND tbl_post.id =_id;
END$$

DROP PROCEDURE IF EXISTS `SP_user_insertUser`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_user_insertUser` (IN `_u_name` VARCHAR(25) CHARSET utf8, IN `_u_pass` VARCHAR(32) CHARSET utf8, IN `_u_email` VARCHAR(45), IN `_f_name` VARCHAR(45) CHARSET utf8, IN `_l_name` VARCHAR(45) CHARSET utf8, IN `_age` TINYINT(2), IN `_sex` TINYINT(1), IN `_bio` VARCHAR(450) CHARSET utf8, IN `_avatar` VARCHAR(45) CHARSET utf8, IN `_signup_time` INT(11))  BEGIN
	INSERT INTO 
    tbl_user( u_name,u_pass,u_email,f_name,l_name,age,sex,bio,avatar,signup_time)  
    VALUES  (_u_name,_u_pass,_u_email,_f_name,_l_name,_age,_sex,_bio,_avatar,_signup_time);
END$$

DROP PROCEDURE IF EXISTS `SP_user_set`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_user_set` (IN `_propertyname` VARCHAR(20), IN `_propertyvalue` VARCHAR(250), IN `_id` INT)  BEGIN	
	declare a varchar(30);
    set a = (CONCAT(' ',_propertyname,' '));
    UPDATE tbl_user SET a = _propertyvalue WHERE id = _id;
END$$

DROP PROCEDURE IF EXISTS `SP_user_setRandomHash`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_user_setRandomHash` (IN `_u_name` VARCHAR(20), IN `_random_hash` VARCHAR(32))  BEGIN
	UPDATE tbl_user SET random_hash=_random_hash WHERE u_name=_u_name;
END$$

DROP PROCEDURE IF EXISTS `SP_user_updateActivationCode`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_user_updateActivationCode` (IN `_activation_code` VARCHAR(32), IN `_u_name` VARCHAR(25))  BEGIN
	UPDATE tbl_user SET activation_code=_activation_code WHERE u_name=_u_name;
END$$

DROP PROCEDURE IF EXISTS `SP_user_updateUserAvatar`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_user_updateUserAvatar` (IN `_path` VARCHAR(45), IN `_u_id` INT(11))  BEGIN
	UPDATE tbl_user SET avatar=_path WHERE id=_u_id;
END$$

DROP PROCEDURE IF EXISTS `SP_user_updateUserPassByEmail`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_user_updateUserPassByEmail` (IN `_u_pass` VARCHAR(32), IN `_u_email` VARCHAR(45))  BEGIN
	UPDATE tbl_user SET u_pass=_u_pass WHERE u_email=_u_email;
END$$

DROP PROCEDURE IF EXISTS `SP_user_updateUserPassByUserName`$$
CREATE DEFINER=`systemir`@`localhost` PROCEDURE `SP_user_updateUserPassByUserName` (IN `_u_pass` VARCHAR(32), IN `_u_name` VARCHAR(25))  BEGIN
UPDATE tbl_user SET u_pass=_u_pass WHERE u_name=_u_name;
END$$

--
-- Functions
--
DROP FUNCTION IF EXISTS `FUNC_checkCommentSpam`$$
CREATE DEFINER=`systemir`@`localhost` FUNCTION `FUNC_checkCommentSpam` (`_u_id` INT, `_time` INT) RETURNS TINYINT(1) BEGIN

    declare com_counts int;
    
	if(exists(select * from tbl_comment where time > _time-120 and u_id=_u_id)) then
		set com_counts = (select count(id) from tbl_comment where tbl_comment.`time` > _time and u_id=_u_id);
    else
		set com_counts = 0;
    end if;
    
	RETURN com_counts;

END$$

DROP FUNCTION IF EXISTS `FUNC_like`$$
CREATE DEFINER=`systemir`@`localhost` FUNCTION `FUNC_like` (`_u_id` INT, `_p_id` INT, `_like_dislike` INT) RETURNS TINYINT(1) BEGIN
	declare result int default 0;
	declare tmp int;
			
    IF(EXISTS(SELECT * FROM tbl_like WHERE p_id=_p_id AND u_id=_u_id)) THEN
			set tmp = (select like_dislike from tbl_like where p_id=_p_id AND u_id=_u_id);
			if(tmp = 0) then #already disliked
					if(_like_dislike = 1) then
						update tbl_like set like_dislike=1 where p_id=_p_id AND u_id=_u_id;
                        set result = 3;
					else
						delete from tbl_like where p_id=_p_id AND u_id=_u_id;
                        set result = 5;
					end if;    
			else #already liked
					if(_like_dislike = 1) then
						delete from tbl_like where p_id=_p_id AND u_id=_u_id;
                        set result = 4;
					else
						update tbl_like set like_dislike=0 where p_id=_p_id AND u_id=_u_id;
                        set result = 2;
					end if; 
			end if;
	ELSE
			INSERT INTO tbl_like(p_id,u_id,like_dislike) VALUES (_p_id,_u_id,_like_dislike);
            if (_like_dislike = 0) then
				set result = 0;
            else
				set result = 1;
            end if;    
	END IF; 
    return result;
END$$

DROP FUNCTION IF EXISTS `FUNC_user_checkDosAndChangeUserPass`$$
CREATE DEFINER=`systemir`@`localhost` FUNCTION `FUNC_user_checkDosAndChangeUserPass` (`_email` VARCHAR(45), `_pass` VARCHAR(32), `_time` INT(11)) RETURNS TINYINT(1) BEGIN
	DECLARE _user_mail_count INT;
	DECLARE _server_mail_count INT;
    DECLARE _time_hour INT; 
    DECLARE _time_min INT; 
    set _time_hour = ( select(_time-3600) );
    set _time_min  = ( select(_time-180) );
    
		#1 : delete old records of emails table
		DELETE FROM tbl_sent_mails WHERE `time` < _time_hour;
        
        #2 : calculate mail counts
        set _server_mail_count = (SELECT COUNT(*) FROM tbl_sent_mails);
        set _user_mail_count   = (SELECT COUNT(*) FROM tbl_sent_mails WHERE u_email = _email AND `time` > _time_min);
        
		IF (_user_mail_count < 3) THEN #3 : check mail counts of last hour of current user
			IF (_server_mail_count < 30) THEN #4 : check mail counts of all time
				IF (EXISTS(SELECT * FROM tbl_user WHERE u_email = _email)) THEN #5 : check mail exists
					UPDATE tbl_user SET u_pass = _pass WHERE u_email = _email; #6 : update pass
					INSERT INTO tbl_sent_mails(u_email,`time`) VALUES(_email, _time); #7 : update mail counts
					return 1;									
				ELSE
					RETURN 0;
				END IF;    
			ELSE    
				RETURN 2;
			END IF;
        ELSE
			RETURN 3;
		END IF;
END$$

DROP FUNCTION IF EXISTS `FUNC_user_checkEmailExists`$$
CREATE DEFINER=`systemir`@`localhost` FUNCTION `FUNC_user_checkEmailExists` (`_u_email` VARCHAR(45)) RETURNS BIT(1) BEGIN
	IF(EXISTS (SELECT * FROM tbl_user WHERE u_email=_u_email)) THEN
		return 1;
    ELSE
		return 0;
    END IF;
END$$

DROP FUNCTION IF EXISTS `FUNC_user_checkUserNameExists`$$
CREATE DEFINER=`systemir`@`localhost` FUNCTION `FUNC_user_checkUserNameExists` (`_u_name` VARCHAR(25)) RETURNS BIT(1) BEGIN
	IF(EXISTS (SELECT * FROM tbl_user WHERE u_name=_u_name)) THEN
		return 1;
    ELSE
		return 0;
    END IF;
END$$

DROP FUNCTION IF EXISTS `FUNC_user_getUserType`$$
CREATE DEFINER=`systemir`@`localhost` FUNCTION `FUNC_user_getUserType` (`_id` INT) RETURNS TINYINT(1) BEGIN

	return (SELECT u_type FROM tbl_user WHERE id=_id);

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cat`
--

DROP TABLE IF EXISTS `tbl_cat`;
CREATE TABLE IF NOT EXISTS `tbl_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `cat_parent` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `FK_Self_Join_Cat_id` (`cat_parent`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `tbl_cat`
--

INSERT INTO `tbl_cat` (`id`, `cat_name`, `cat_parent`) VALUES
(1, '1', 1),
(8, 'نرم افزار', 1),
(9, 'سخت افزار', 1),
(10, 'برنامه نویسی', 8),
(12, 'طراحی و توسعه وب', 8),
(13, 'نرم افزارهای تخصصی', 8),
(14, 'تعمیرات', 9),
(15, 'رباتیک', 1),
(17, 'علوم انسانی', 1),
(18, 'فلسفه و منطق', 17),
(19, 'تاریخ', 17),
(20, 'پزشکی', 1),
(21, 'C#', 10),
(22, 'PHP', 10),
(23, 'JavaScript', 10),
(24, 'Node.JS', 10),
(25, 'Html & Css', 12),
(26, 'Bootstrap', 12),
(27, 'jQuery', 12),
(28, 'ترفندها', 8),
(29, 'قطعات الکترونیکی', 9),
(30, 'الکترونیک', 1),
(31, 'الکترونیک پایه', 30),
(32, 'java', 10),
(33, 'ارزهای دیجیتال', 1),
(34, 'بیت کوین', 33),
(35, 'تکنولوژی', 1);

--
-- Triggers `tbl_cat`
--
DROP TRIGGER IF EXISTS `tbl_cat_BEFORE_INSERT`;
DELIMITER $$
CREATE TRIGGER `tbl_cat_BEFORE_INSERT` BEFORE INSERT ON `tbl_cat` FOR EACH ROW BEGIN
	IF EXISTS ( SELECT * FROM tbl_cat WHERE cat_name=NEW.cat_name AND cat_parent=NEW.cat_parent) THEN
		SIGNAL SQLSTATE '45000';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_comment`
--

DROP TABLE IF EXISTS `tbl_comment`;
CREATE TABLE IF NOT EXISTS `tbl_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(45) COLLATE utf8_persian_ci DEFAULT NULL,
  `mail` varchar(45) COLLATE utf8_persian_ci DEFAULT NULL,
  `website` varchar(45) COLLATE utf8_persian_ci DEFAULT NULL,
  `c_text` varchar(1500) COLLATE utf8_persian_ci NOT NULL,
  `time` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT '0',
  `u_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_Comment_Post` (`post_id`),
  KEY `FK_Self_idx` (`parent_id`),
  KEY `FK_Self` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `tbl_comment`
--

INSERT INTO `tbl_comment` (`id`, `full_name`, `mail`, `website`, `c_text`, `time`, `post_id`, `parent_id`, `u_id`) VALUES
(1, '1', '1', '1', '1', 1, 1, 1, 1);

--
-- Triggers `tbl_comment`
--
DROP TRIGGER IF EXISTS `trg_decrease_comment_count_to_tbl_post`;
DELIMITER $$
CREATE TRIGGER `trg_decrease_comment_count_to_tbl_post` AFTER DELETE ON `tbl_comment` FOR EACH ROW BEGIN
	UPDATE tbl_post SET tbl_post.comment_count = (tbl_post.comment_count) - 1
	WHERE tbl_post.id = old.post_id;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_increase_comment_count_to_tbl_post`;
DELIMITER $$
CREATE TRIGGER `trg_increase_comment_count_to_tbl_post` AFTER INSERT ON `tbl_comment` FOR EACH ROW BEGIN
	UPDATE tbl_post SET tbl_post.comment_count = (tbl_post.comment_count) + 1
	WHERE tbl_post.id = NEW.post_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_friendship`
--

DROP TABLE IF EXISTS `tbl_friendship`;
CREATE TABLE IF NOT EXISTS `tbl_friendship` (
  `u_id_1` int(11) NOT NULL,
  `u_id_2` int(11) NOT NULL,
  `accepted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`u_id_2`,`u_id_1`),
  KEY `FK_Friendship_User_1` (`u_id_1`),
  KEY `FK_Friendship_User_2` (`u_id_2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `tbl_friendship`
--

INSERT INTO `tbl_friendship` (`u_id_1`, `u_id_2`, `accepted`) VALUES
(1, 1, 0),
(2, 1, 0),
(1, 2, 1);

--
-- Triggers `tbl_friendship`
--
DROP TRIGGER IF EXISTS `tbl_friendship_AFTER_DELETE`;
DELIMITER $$
CREATE TRIGGER `tbl_friendship_AFTER_DELETE` AFTER DELETE ON `tbl_friendship` FOR EACH ROW BEGIN
	UPDATE tbl_user SET tbl_user.follower_count = tbl_user.follower_count - 1
    WHERE OLD.u_id_2 = tbl_user.id;
    UPDATE tbl_user SET tbl_user.following_count = tbl_user.following_count - 1
    WHERE OLD.u_id_1 = tbl_user.id;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `tbl_friendship_AFTER_UPDATE`;
DELIMITER $$
CREATE TRIGGER `tbl_friendship_AFTER_UPDATE` AFTER UPDATE ON `tbl_friendship` FOR EACH ROW BEGIN
	IF(NEW.accepted=1) THEN
		UPDATE tbl_user SET tbl_user.follower_count = tbl_user.follower_count + 1
        WHERE NEW.u_id_2 = tbl_user.id;
        UPDATE tbl_user SET tbl_user.following_count = tbl_user.following_count + 1
        WHERE NEW.u_id_1 = tbl_user.id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_like`
--

DROP TABLE IF EXISTS `tbl_like`;
CREATE TABLE IF NOT EXISTS `tbl_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `u_id` int(11) DEFAULT NULL,
  `p_id` int(11) DEFAULT NULL,
  `like_dislike` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_like_post` (`p_id`),
  KEY `FK_like_user` (`u_id`)
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `tbl_like`
--

INSERT INTO `tbl_like` (`id`, `u_id`, `p_id`, `like_dislike`) VALUES
(123, 1, 31, 1),
(168, 1, 24, 1),
(170, 1, 27, 1),
(174, 1, 28, 1),
(177, 1, 37, 1);

--
-- Triggers `tbl_like`
--
DROP TRIGGER IF EXISTS `tbl_like_AFTER_DELETE`;
DELIMITER $$
CREATE TRIGGER `tbl_like_AFTER_DELETE` AFTER DELETE ON `tbl_like` FOR EACH ROW BEGIN
    DECLARE _tmp_like    INT;
	DECLARE _tmp_dislike INT;
    SET _tmp_like    = (SELECT tbl_post.like_count    FROM tbl_post WHERE OLD.p_id=tbl_post.id);
    SET _tmp_dislike = (SELECT tbl_post.dislike_count FROM tbl_post WHERE OLD.p_id=tbl_post.id);
    
    
    
    IF (OLD.like_dislike=1) THEN
		IF (_tmp_like>0) THEN
			UPDATE tbl_post SET tbl_post.like_count = (tbl_post.like_count)-1
			WHERE OLD.p_id = tbl_post.id;
		END IF;
    END IF;
    IF (OLD.like_dislike=0) THEN
		IF (_tmp_dislike>0) THEN
			UPDATE tbl_post SET tbl_post.dislike_count = (tbl_post.dislike_count)-1
			WHERE OLD.p_id = tbl_post.id;
        END IF;
    END IF;
    
    
    
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `tbl_like_AFTER_INSERT`;
DELIMITER $$
CREATE TRIGGER `tbl_like_AFTER_INSERT` AFTER INSERT ON `tbl_like` FOR EACH ROW BEGIN
	IF (NEW.like_dislike=1) THEN
			UPDATE tbl_post SET tbl_post.like_count = (tbl_post.like_count)+1
			WHERE NEW.p_id = tbl_post.id;
    END IF;
    IF (NEW.like_dislike=0) THEN
			UPDATE tbl_post SET tbl_post.dislike_count = (tbl_post.dislike_count)+1
			WHERE NEW.p_id = tbl_post.id;
    END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `tbl_like_AFTER_UPDATE`;
DELIMITER $$
CREATE TRIGGER `tbl_like_AFTER_UPDATE` AFTER UPDATE ON `tbl_like` FOR EACH ROW BEGIN


	IF (NEW.like_dislike=1 AND OLD.like_dislike=0) THEN
			UPDATE tbl_post SET tbl_post.like_count = (tbl_post.like_count)+1
			WHERE NEW.p_id = tbl_post.id;
            UPDATE tbl_post SET tbl_post.dislike_count = (tbl_post.dislike_count)-1
			WHERE NEW.p_id = tbl_post.id;
    END IF;
    IF (NEW.like_dislike=0 AND OLD.like_dislike=1) THEN
			UPDATE tbl_post SET tbl_post.like_count = (tbl_post.like_count)-1
			WHERE NEW.p_id = tbl_post.id;
            UPDATE tbl_post SET tbl_post.dislike_count = (tbl_post.dislike_count)+1
			WHERE NEW.p_id = tbl_post.id;
    END IF;
    
    
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pic`
--

DROP TABLE IF EXISTS `tbl_pic`;
CREATE TABLE IF NOT EXISTS `tbl_pic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pic_name` varchar(45) COLLATE utf8_persian_ci DEFAULT NULL,
  `u_id` int(11) DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `FK_pic_user_idx` (`u_id`)
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `tbl_pic`
--

INSERT INTO `tbl_pic` (`id`, `pic_name`, `u_id`, `date`) VALUES
(138, '1548319252.1714743845.jpg', 1, 1548319252),
(140, '1548518793.1810284964.jpg', 1, 1548518793),
(141, '1548603507.533948664.jpg', 1, 1548603507),
(148, '1549780958.377086770.png', 1, 1549780958),
(149, '1549784474.1610138316.png', 1, 1549784475),
(150, '1552768828.1822202966.jpg', 1, 1552768829),
(151, '1554968680.1130644086.png', 1, 1554968680),
(152, '1554968752.137303162.jpg', 1, 1554968752),
(153, '1558699527.1843308346.jpg', 1, 1558699527),
(154, '1563260702.1268777526.jpg', 1, 1563260703),
(155, '1563260898.474079144.jpg', 1, 1563260898),
(156, '1563260972.1721927266.jpg', 1, 1563260972),
(157, '1563261130.1062237276.jpg', 1, 1563261131),
(158, '1563261232.2139925625.jpg', 1, 1563261232),
(159, '1563261317.234943611.jpg', 1, 1563261317),
(160, '1563261372.413186373.jpg', 1, 1563261372),
(161, '1563261457.911845008.jpg', 1, 1563261457),
(162, '1563261520.590964048.jpg', 1, 1563261521),
(163, '1563261568.303383620.jpg', 1, 1563261568),
(164, '1563261615.2081000542.jpg', 1, 1563261616),
(165, '1563261682.766734265.jpg', 1, 1563261682),
(166, '1563261752.1361638568.jpg', 1, 1563261752),
(167, '1563262053.53667242.jpg', 1, 1563262054);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_post`
--

DROP TABLE IF EXISTS `tbl_post`;
CREATE TABLE IF NOT EXISTS `tbl_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_title` varchar(50) COLLATE utf8_persian_ci NOT NULL,
  `p_content` text COLLATE utf8_persian_ci NOT NULL,
  `p_rate` tinyint(1) DEFAULT NULL,
  `p_image` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL,
  `u_id` int(11) DEFAULT NULL,
  `published` tinyint(1) DEFAULT '1',
  `allow_comments` tinyint(1) DEFAULT '1',
  `creation_time` int(11) DEFAULT NULL,
  `last_modify` int(11) DEFAULT NULL,
  `like_count` tinyint(5) DEFAULT '0',
  `dislike_count` tinyint(5) DEFAULT '0',
  `comment_count` tinyint(5) DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_post_user` (`u_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `tbl_post`
--

INSERT INTO `tbl_post` (`id`, `p_title`, `p_content`, `p_rate`, `p_image`, `u_id`, `published`, `allow_comments`, `creation_time`, `last_modify`, `like_count`, `dislike_count`, `comment_count`, `deleted`) VALUES
(1, '1', '1', NULL, 'post_default.jpg', 1, 0, 1, NULL, NULL, 0, 0, 0, 1),
(24, 'رفع مشکل رایت پروتکت در فلش مموری', '<p>امروز قصد داریم یکی از مشکلات رایج فلش مموریها را با نام رایت پروتکت بررسی و راه حل برطرف کردن آنرا برای شما ارائه دهیم.</p>\r\n\r\n<p>--more--</p>\r\n\r\n<h3>Write Protect به چه معناست؟</h3>\r\n\r\n<p style=\"text-align:justify\">رایت پروتکت در اصطلاح کامپیوتر به معنی محافظت شدن در برابر نوشتن می باشد. در فلش مموریها ارور رایت پروتکت باعث می شود که سیستم شما از نوشتن اطلاعات در داخل فلش مموری جلوگیری نماید و شما نتوانید اطلاعاتی را درون فلش مموری خود وارد کنید و هنگام انتقال اطلاعات به داخل فلش مموری با ارور write protect&nbsp;مواجه شوید.</p>\r\n\r\n<h3>دلایل بوجود آمدن ارور رایت پروتکت :</h3>\r\n\r\n<p style=\"text-align:justify\">۱- گاهی اوقات ممکن است به علت آلود شده سیستم کامپیوتر شما به ویروس باشد، که در فلش مموری مشکل write protect را ایجاد می کند که بعد از پاکسازی کامپیوتر از ویروس نیز این ارور همچنان پا برجا خواهد ماند.</p>\r\n\r\n<p style=\"text-align:justify\"><br />\r\n۲- جدیدا نرم افزارهایی برای محافظ از فلش مموری در برابر ویروسها به بازار ارائه شده اند، کار این نرم افزار اینگونه است که با تغییراتی که در فلش مموری ایجاد می کنند از نوشته شدن هرگونه اطلاعات درون فلش مموری جلوگیری کنند. که با اینکار خود از ورود ویروسها و فایلهای مخرب به داخل فلش جلوگیری کند. در بعضی مواقع کاربر پس از نصب این نرم افزار گزینه محافظت از فلش مموری را در نرم افزار فعال می کند و امکان دارد قبل از غیر فعال کردن آن نرم افزار را از روی سیستم خود پاک کرده و یا بنا به دلایلی نرم افزار نتواند کار خود را به درستی انجام دهد. در اینگونه موارد نیز هنگام انتقال اطلاعات به داخل فلش مموری با ارور write protect مواجه خواهید شد.</p>\r\n\r\n<p><br />\r\n۳- گاهی اوقات نیز کاربران برای جداسازی فلش مموری از پورت یو اس بی از گزینه سیو ریمو استفاده نمی کنند که این خود نیز در بعضی مواقع موجود ایجاد مشکلاتی در ریجستر فلسش مموری شده و مشکل رایت پرتکت را ایجاد کرده.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h3>راه حل برطرف کردن مشکل write protect</h3>\r\n\r\n<p>1 . بر طرف کردن مشکل رایت پروتکت از طریق رجیستری سیستم</p>\r\n\r\n<p>ابتدا استارت خود را باز کرده و در قسمت سرچ ان این کلمه را وارد کنید regedit.exe.</p>\r\n\r\n<p>(اگر ویندوز 7 بود همان استارت را باز کنید و این جمله را بزنید اگر نه کلمه ی regedit خالی را در قسمت RUN ویندوز XP وارد کنید و ادامه ی کار). به ترتیب به این ادرس ها یا فایل ها بروید:<br />\r\n<br />\r\nHKEY_LOCAL_MACHINE\\SYSTEM\\CurrrentControlSet\\Control\\StroageDevicePolicies<br />\r\n<br />\r\nپس از رفتن به این مسیر در پنجره ی ریجستری سمت راست ان بر روی REG_DWORD کلیک کنید. سپس باید عدد ۰ را در ان جا تایپ کنید<br />\r\n۱ = Write Protect On<br />\r\n0 = Write Protect Off<br />\r\nحالا OK را بزنید و از پنجره ی ریجستری خارج شوید حالا تمام برنامه هایی که به این فلش USB یا sd کارت وصل هستند را قطع کنید مثل انتی ویروس و ... . حالا سعی کنید که فلش را فرمت کنید.</p>\r\n\r\n<p>۲ . راه حل فرمت کردن فلش مموری از طریق Disk Management</p>\r\n\r\n<p style=\"text-align:justify\">گاهی ممکن است که این راه حل پاسخ گوی مشکل شما نباشد . در این موارد شما می بایستی راه حل های دیگر را امتهان کنید برای این کار ابتدا روی my Computer کلیک راست کنید سپس بر روی گزینه management کلیک کنید سپس روی گزینه Disk Management کلیک کنید بعد از این کار در کادر سمت راست درایو فلش مموری خود را پیدا کرده و پس از کلیک راست کردن بر روی ان گزنه فرمت را انتخواب کنید . با این روش شما بدون در نظر گرفتن یک سری از ارور ها به سیستم دستور می دهید که فلش مموری را فرمت کند.</p>\r\n\r\n<p>۳ . فرمت کردن فلش مموری به وسیله بوت ویندوز</p>\r\n\r\n<p style=\"text-align:justify\">در بعضی از موارد ممکن است این روش نیز برای پاسخ گویی به شما مناسب نباشد در این گونه موارد شما می توانید از فرمت کردن فلش مموری به وسیله بوت ویندوز اقدام کنید . برای این کار شما باید ابتدا سدی ویندوز را در درون دستگاه قرار دهید و شروع به نصب ویندوز کنید . مراحل نصب ویندوز را ادامه دهید مراحل نصب را تا زمانی که از شما سوال برای فرمت کردن درایو می پرسد طبق معمول انجام دهید در این مرحله شما درایو فلش مموری خود را انتخواب و دستور فرمت ان را صادر کنید . با این کار فلش مموری شما به احتما قوی فرمت شده و مشکل شما برطرف می شود.</p>\r\n\r\n<p>۴ . فرمت کردن فلش مموری از طریق سیستم عامل لینوکس</p>\r\n\r\n<p style=\"text-align:justify\">ولی اگر باز هم مشکل شما پا بر جا بود شما می توانید از فرمت فلش مموری توسط لینوکس استفاده کنید .سیستم عامل لینوکس قابلیت بسیار بالای در فرمت فلش مموری های معیوب را دارا می باشد . هنگامی که شما به سیستم علمل لینوکس درخواست فرمت کردن فلش مموری را صادر می کنید سیستم عامل شروع به فرمت کردن فلش مموری می کنند و هر جا که با مشکلی رو به رو می شود (منظور از مشکل مشکلات سخت افزاری ) ان مشکل را به شما گزارش می کنی و مشکلات نرم افزاری فلش مموری را نیز پس از گزارش برطرف می کند.</p>\r\n\r\n<p>۵ . حل مشکل رایت پروتکت به وسیله نرم افزار usbdefender</p>\r\n\r\n<p>نرم افزاری نیز برای بر طرف کردن مشکل رایت پروتکت وجود دارد با نام usbdefender نرم افزار رو نصب کنید اگر گزینه پروتکت فعال بود ان را غیر فعال کنید.<br />\r\n<br />\r\nنرم افزار phison_ps22xx_formatter_v2.10.0.2 نیز برای حل مشکل رایت پروتکت وجود دارد.</p>\r\n', NULL, '1554968752.137303162.jpg', 1, 1, 1, 1514918102, 1557582487, 13, 0, 0, 0),
(27, 'افزایش بازدیدکنندگان وب سایت', '<p>غلب کسب و کار ها تمایل دارند در دنیای اینترنت برای خود وب سایتی داشته باشند . راه اندازی وب سایت برای مشاغل مختلف ، سود و بازدهی زیادی را به همراه خواهد…</p>', NULL, '1548251960.1673082718.jpg', 1, 0, 1, 1515123283, 1548251972, 0, 2, 0, 0),
(28, 'مقایسه ورژن های مختلف PHP', '<p>سال ۲۰۱۵ مهم ترین سال برای PHP می باشد. یازده سال پس از زمانی که PHP5 منتشر شد.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p style=\"text-align:justify\">PHP7&nbsp;&nbsp;سرعت چند برابر&nbsp;و امکانات بسیار بیشتری را&nbsp;&nbsp;نسبت به ورژن های قبلی به همراه آورده است.&nbsp;PHP در نسخه ی 7&nbsp; از موتور Zend Engine 3.0 استفاده کرده است که در آن تقریبا میزان استفاده از رم نسبت به نسخه های قبلی&nbsp;۵۰% کاهش یافته است و این باعث می شود که در آن واحد&nbsp;کاربران بیشتری بتوانند از برنامه شما&nbsp;روی سخت افزارهای ضعیفتر هم دیدن کنند.&nbsp;PHP7 با توجه به حجم کاری امروز طراحی شده و بازنویسی &nbsp;شده است.&nbsp;کامپایلر PHP7 نیز کاملا بازنویسی مجدد شده و در قسمت مدیریت حافظه هم&nbsp;سعی شده است تا با سساختار داده ای&nbsp;stack کار شود تا heap، که طبیعتا سرعت پردازش سریع&zwnj;تری را به دنبال خواهد داشت.</p>\r\n\r\n<p>در ادامه برخی از مهم ترین و پرکاربرد ترین ویژگی های این ویرایش از PHP را بررسی می کنیم:</p>\r\n\r\n<h3>افزایش کارایی یا Performance</h3>\r\n\r\n<p style=\"text-align: justify;\">کارایی مهم ترین دلیلی است که شما باید سرور خود را وقتی ورژن stable انتشار یافت ، به PHP7 بروز کنید . تغییرات ایجاد شده در PHP&nbsp;&nbsp;باعث شده است که PHP7 تغریبا با HHVM هم سرعت شود و شما نیازی به نصب HHVM در سرور خود نداشته باشید.</p>\r\n\r\n<blockquote>\r\n<p style=\"text-align:justify\">در پرانتز باید گفت که:&nbsp;HHVM یک ماشین مجازی متن باز است که بوسیله&nbsp;آن می توان برنامه هایی&nbsp;به زبان های&nbsp;PHP و HACK نوشت. ماشین مجازی HHVM بجای کامپایل کردن مستقیم PHP به ++C، دو زبان PHP و Hack را به bytecode تبدیل می کند. سپس این bytecode توسط کامپایلر JIT به کد ماشین X64 تبدیل می شود. این فرآیند کامپایل اجازه می دهد تا شما یک باینری کامپایل شده را اجرا کنید تا بتوانید عملکرد PHP و HACK خود را بالاتر&nbsp; و بالاتر&nbsp;ببرید.</p>\r\n</blockquote>\r\n\r\n<p style=\"text-align:justify\">این تغییرات در نسخه ی جدید PHP بسیار مهم اند! اکثر اپلیکیشن های ساخته شده با PHP5.6 حداقل دو برابر سریع تر اند .</p>\r\n\r\n<p style=\"text-align:justify\">در نمودار زیر تعداد درخواست های قابل پذیرش در واحد زمان(ثانیه) در ورژن های مختلف PHP نشان داده شده اند.</p>\r\n\r\n<p><img alt=\"php7_graph-c863bf78\" src=\"http://mhr-developer.com/wp-content/uploads/2015/11/php7_graph-c863bf78.jpg\" /></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h3>&nbsp;</h3>\r\n', NULL, '1548251662.392394052.jpg', 1, 0, 1, 1516018788, 1549267297, 3, 2, 0, 0),
(31, 'معماری mvc چیست ؟', '<p>در این معماری در واقع ما دارای ترجمه ای از قسمت های مهم معماری به شکل زیر هستیم:</p>\r\n\r\n<ul>\r\n	<li>MODEL : وظیفه کار با پایگاه داده را بر عهده دارد.</li>\r\n	<li>VIEW : وظیفه ارتباط با کاربر نهایی را بر عهده دارد.</li>\r\n	<li>Control : وظیفه کنترل View و Model و نحوه ارتباط آن دو را با هم بر عهده دارد.</li>\r\n</ul>\r\n\r\n<h3>مقدمه</h3>\r\n\r\n<p>تولید کنندگان نرم افزار نیز تلاش می کنند تا نرم افزاری تولید کنند تا بتواند اکثر نیاز های متقاضیان را به بهترین نحو ممکن تامین کند ودر همین راستا در تلاش هستند که روند تولید نرم افزار را به سمتی بکشانند که ساختار استاندارد و تائید شده ای داشته باشد. شاید بتوان گفت که دوران کد نویسی به پایان رسیده و همه چیز به سمت زیر ساخت ها و بنیان نهادن چارچوب های استاندارد وپیروی از آن ها در امر تولید بهتر نرم افزار در حرکت است.</p>\r\n\r\n<p>اجازه دهید ببینیم خصوصیات یک نرم افزار خوب چیست ؟&nbsp;<br />\r\nنام بردن تمامی خصوصیات یک نرم افزار خوب در این مقال نمی گنجد اما تعداد محدود و مهمی از آنها عبارتند از:</p>\r\n\r\n<ul>\r\n	<li>قابل حمل بودن</li>\r\n	<li>قابل استفاده مجدد بودن</li>\r\n	<li>قابل تغییر بودن</li>\r\n	<li>بهینه بودن از لحاط حافظه و زمان (زمان مهمتر از حافظه)</li>\r\n</ul>\r\n\r\n<h3>مسئله</h3>\r\n\r\n<p>بهتر است وجود مسئله را با یک مثال نشان دهم فرض کنید نرم افزاری برای شرکتی نوشتید که یک بخش آن مقدار سود وزیان شرکت را در سال های مختلف بر اساس ارقام بیان میکند . حال صاحب برنامه پس از مدتی ازشما می خواهد برنامه را طوری تغییر دهید که همین اطلاعات را به گونه های دیگری مثلا نمودار های مختلف ( میله ای ، دایره ای و ...) در اختیار داشته باشد و یا حتی بخواهد آنها را به فرمت خاصی و در فایل های خاصی ذخیره کند . در این مواقع چطور مشکل را حل میکنید؟ همانطور که گفته شد یکی از خصوصیات نرم افزار خوب قابل تغییر بودن آن میباشد. فرض کنید که برنامه را به این شکل طراحی کردید:</p>\r\n\r\n<p>همانطور که در شکل نیز نشان داده شده است تمامی اعمال اعم از دریافت داده ها که مهمترین بخش است و همچنین پردازش آن ها همگی در یک فرم طراحی و پیاده سازی شده اند، و دقیقا مشکل همینجا نمایان می شود . ارتباط مستقیم با منبع داده بر قرار کردن جدا از اینکه مشکلات امنیتی دارد که بحث در مورد آن خارج از این مقال است ، باعث میشود که دست برنامه نویس را برای تغییرات آتی دربرنامه ببندد. چون داده درون خود فرم از منبع داده و به صورت مستقیم خوانده می شود پس دسترسی به داده های خوانده شده وجود ندارد . یا حداقل متحمل سربار زیادی می باشد.</p>\r\n\r\n<blockquote>\r\n<p>MVC&nbsp; مخفف سه کلمه Model View Controller هست . در واقع MVC بر روی معماری های چند لایه ای جهت جداسازی قسمت های مختلف برنامه و به طور دقیق تر جدا کردن بخش ها منطقی برنامه اعم از دیتا ، permission ها ، چک کردن صحت داده ها و .... از لایه Presentation layer یا در واقع همان لایه ای که مستقیما با کاربر نهایی (End user) در ارتباط است ،قرار میگیرد. پس بر اساس توضیحات فوق می توانیم هر یک از بخش های معماری MVC یعنی Model و View و controller را به شکل زیر تعریف کنیم.</p>\r\n</blockquote>\r\n\r\n<p>1. <strong>Model&nbsp;</strong></p>\r\n\r\n<p>در واقع بار اصلی معماری MVC بر عهده این بخش است . این بخش می تواند با داده ها در ارتباط باشد .الزاماً منظور از داده حتما ارتباط با پایگاه های داده همچون MSSQL و Access و ... نیست ، حتی منبع داده ها در بخش Model می تواند یک آرایه از اعداد و یا هر چیز دیگری باشد . همچنین Model وظیفه چک کردن داده ها جهت صحت درستی داده ها را هم بر عهده دارد (در این زمینه همکاری بیشتری با بخش Controller دارد) و همینطور وظایف دیگری که در مثال ها ی عملی که در آینده خواهم زد بیشتر آشنا خواهید شد.</p>\r\n\r\n<p>2. <strong>View&nbsp;</strong></p>\r\n\r\n<p>این بخش که در واقع همان بخش Presentation Layer در معماری 3 لایه میباشد وظیفه بر قراری ارتباط با کاربر نهایی و گرفتن داده از کاربر و نمایش داده های اماده با کاربراز طریق برقراری ارتباط با دو بخش دیگر یعنی Model و controller است . در واقع نکته مهمی که در بخش View باید مد نظر داشت این است که این لایه مسئول کنترل صحت داده های وارد شده از طریق کاربر و همچنین مسئول صحت داده های نشان داده شده به کاربر نیست . در واقع این بخش با داده های خام کار میکند . یک مثال ساده خیلی از برنامه نویسان هنگامی که در فرم Login برنامه ،کاربر کلمه عبور خود را وارد میکند ، در همان فرم Login اقدام به چک کردن پسورد مبنی بر صحت آن و ... می کنند . که این عمل در معماری MVC قابل قبول نیست . در واقع برای حل مسئله فوق در معماری MVC در فرم Login هنگامی که کاربر کلمه عبور را وارد کرد و دکمه Login یا ورود را زد ، کلمه عبور داده شده بدون هیچ گونه اعمالی اعم از Encrypt کردن و ... به بخش های دیگر فرستاده میشود و فقط یک نتیجه ساده مبنی بر این که کاربر اجازه ورود دارد یا خیر را از بخش های دیگر دریافت میکند که بر اساس آن اجازه ورود کاربر به برنامه داده میشود .</p>\r\n\r\n<p>3. <strong>Controller&nbsp;</strong></p>\r\n\r\n<p>این بخش همانطور که از اسم آن مشخص است یک بخش کنترل کننده می باشد ، و در واقع واسطی بین دو بخش Model و View میباشد. حال ببینیم روند اجرای برنامه در معماری MVC به چه نحوی خواهد بود . در معماری MVC روند کلی برنامه (جزئیات را در ادامه خواهید دید) به این شکل است که کاربر تقاضای خود را از طریق واسط های برنامه نویسی (نظیر Form ها و User Control ها و .. ) از برنامه (از بخش View)درخواست می کند . بخش View در خواست ها را به بخش Controller فرستاده و این بخش با برقراری ارتباط با بخش Model در خواست های کاربر را پردازش کرده و پس از پایان پردازش زمانی که خروجی درخواست داده شده آماده گردید بخش Controller بخش View را آگاه می سازد تا خود را بر اسا س تغییرات جدید که اصطلاحاً در معماری MVC به آن حال Model می گویند ، به روز سازد . در واقع چیزی که باعث میشود تا بخش Controller به بخش View اطلاع دهد که باید حالت جدید model را دریافت کند و خود را Update کند این است که بخش View باید قبلا خودش را در بخش Model اصطلاحا Register کرده باشد که البته عمل Register کردن توسط بخش Controller انجام میگیرد . نحوه register کردن بخش View به معماری آن محیط و همچنین زبانی که توسط آن برنامه را گسترش میدهید و همچنین قابلیت های آن زبان بستگی دارد.&nbsp;</p>\r\n', NULL, '1554968680.1130644086.png', 1, 1, 1, 1515088814, 1554968682, 1, 1, 0, 0),
(36, 'ماسفت و کاربردهای آن', '<p style=\"text-align:justify\">ماسفت یا ترانزیستور تحت اثر میدان ( metal&ndash;oxide&ndash;semiconductor field-effect transistor ٫ MOSFET ) معروف&zwnj;ترین ترانزیستور&nbsp;در مدارهای الکترونیکی است.</p>\r\n\r\n<p style=\"text-align:justify\">--more--</p>\r\n\r\n<p style=\"text-align:justify\">در ترانزیستور اثر میدان ( FET ) چنان که از نام اش پیداست، پایهٔ کنترلی(GATE)، جریانی مصرف نمی&zwnj;کند و تنها با اعمال ولتاژ و ایجاد میدان درون نیمه رسانا، جریان عبوری از FET کنترل می&zwnj;شود. از همین روی ورودی این مدار هیچ اثر بارگذاری بر روی طبقات تقویت قبلی نمی&zwnj;گذارد و امپدانس بسیار بالایی دارد. عمده تفاوت ماسفت با ترانزیستور JFET در این است که گیت ترانزیستورهای ماسفت توسط لایه&zwnj;ای از اکسید سیلیسیم (SiO2) از کانال مجزا شده است.</p>\r\n\r\n<p style=\"text-align:justify\">مدارهای مجتمع بر پایهٔ فناوری ترانزیستورهای تحت اثر میدان&nbsp;MOS را می&zwnj;توان بسیار ریزتر و ساده&zwnj;تر از مدارهای مجتمع بر پایهٔ ترانزیستورهای دوقطبی ساخت، بی آن که نیازی به مقاومت، دیود یا دیگر قطعه&zwnj;های الکترونیکی داشته باشند. همین ویژگی، تولیدِ انبوi آن&zwnj;ها را آسان می&zwnj;کند، چندان که هم اکنون بیش&zwnj;تر از ۸۵ درصد از مدارهای مجتمع، بر پایه ی فناوریِ MOSFET ها طراحی و ساخته می&zwnj;شوند.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1><strong>ساختمان و ساختار ماسفت ها:</strong></h1>\r\n\r\n<p style=\"text-align:justify\">ماسفت دارای سه پایه گیت (G) درین (D) و سورس (S) می باشد.</p>\r\n\r\n<p style=\"text-align:justify\">FET&zwnj;&nbsp;دارای سه پایه با نام&zwnj;های درین (D)، سورس (S) و گیت (G) است. پایه گیت، جریان عبوری از درین به سورس را کنترل می&zwnj;کند. فت&zwnj;ها دارای دو نوع N-channel&nbsp;و P-channel&nbsp; هستند. در فت نوع N-channel زمانی که گیت نسبت به سورس مثبت باشد جریان از درین به سورس عبور می&zwnj;کند. FET&zwnj; ها معمولاً بسیار حساس بوده و حتی با الکتریسیته ساکن بدن نیز تحریک می&zwnj;گردند. به همین دلیل نسبت به نویز بسیار حساس هستند. یکی از انواع ترانزیستورهای تحت اثر میدان&nbsp;MOSFET&zwnj;ها هستند. (ترانزیستور اثرمیدانی نیمه&zwnj;رسانای اکسید فلز=Metal Oxide Semiconductor Field Effect Transistor)</p>\r\n\r\n<p style=\"text-align:justify\">نکته: یکی از اساسی&zwnj;ترین مزیت&zwnj;های ماسفت&zwnj;ها نویز کمتر آن&zwnj;ها در مدار است.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>تست ماسفت با مولتی متر</h1>\r\n\r\n<p style=\"text-align:justify\">FET&zwnj;&nbsp;ها در ساخت فرستنده باند FM رادیو نیز کاربرد فراوانی دارند. برای تست کردن فت N-channel با مولتی متر، ابتدا پایه گیت را پیدا می&zwnj;کنیم. یعنی پایه&zwnj;ای که نسبت به دو پایه دیگر در یک جهت مقداری رسانایی دارد و در جهت دیگر مقاومت آن بی نهایت است. معمولاً مقاومت بین پایه درین و گیت از مقاومت پایه درین و سورس بیشتر است که از این طریق می&zwnj;توان پایهٔ درین را از سورس تشخیص داد.</p>\r\n', NULL, 'post_default.jpg', 1, 0, 1, 1519032811, 1563286251, 6, 2, 0, 0),
(37, 'تشریح عملکرد مدار VRM مادربرد (قسمت 1)', '<p>در این مجموعه پست ها سعی داریم تا به درک درستی از نحوه ی عملکرد مدار PWM در مادربردها برسیم. در ابتدا مفاهیمی از دنیای دیجیتال را بیان خواهیم نمود و در ادامه با ارائه مثال هایی درک این مفاهیم را برایتان آسان تر می نماییم.</p>\r\n\r\n<p>--more--</p>\r\n\r\n<h3>وظیفه کلی</h3>\r\n\r\n<p>در یک بیان کلی، این مدار وظیفه دارد تا ولتاژ دریافتی 12 ولت از کانکتور چهار پین ATX12 را به ولتاژ مورد نیاز برای cpu، رم، چیپست و ... تبدیل نماید. این تبدیل ولتاژ به وسیله ی مبدل های dc به dc و با روش سوییچینگ انجام می پذیرد. (مشابه همان روش سوییچینگی که در پاورهای SMPS وجود دارد)</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>کنترلر اصلی برای این عملیات PWM Controler نام دارد (PWM= Pulse Width Modulation). این آیسی پالس هایی مربعی ایجاد می نماید که این پالس تعیین می کند در هر لحظه ی واحد کدام فاز در حالت فعال و کدام فازها در حالت غیر فعال قرار گیرند. تعداد این فازها در مادر بردهای مختلف متفاوت است. مثلا اکثر مادربردهای معمولی موجود در بازار برای تغذیه cpu خود دارای 3 یا 4 فاز می باشند. اما مادربرد های حرفه ای تر و با دوام تری هم با تعداد فازهای بیشتر وجود دارند.&nbsp;</p>\r\n\r\n<p>به طور کلی مادربردهایی که تعداد فازهای بیشتری دارند نسبت به سایرین با دوام تر اند. همان طوری که تعداد فازهای مادربوردهای گیمینگ موجود در بازار گاها به 10 فاز نیز می رسد.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h3>چرا تکنیک PWM ؟</h3>\r\n\r\n<p>قبل از بررسی این که چرا از این تکنیک در مدار &nbsp;vrm استفاده شده است، لازم است تا مفاهیمی را در رابطه با یک سیگنال دیجیتال فراگیرید. PWM مخفف Pulse Width Modulation به معنای مدولاسیون پهنای باند و در اصل اصطلاحی برای توصیف یک سیگنال دیجیتال می باشد.</p>\r\n\r\n<p>در تصویر زیر یک سیگنال دیجیتال نمایش داده شده است:</p>\r\n\r\n<p><img alt=\"تصویر 1\" src=\"/includes/images/uploads/multimedia/4x3/1548603507.533948664.jpg\" /></p>\r\n\r\n<p>در تصویر بالا، سیگنالی را در محور مختصات دوبعدی نمایش داده ایم&nbsp;و محور افقی را به عنوان زمان و محور عمودی را تغییرات سیگنال در نظر گرفته ایم، در این صورت با چنین نموداری می توانیم مشخص نماییم که یک سیگنال دیجیتال در هر لحظه چه وضعیتی دارد.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>دو وضعیت اصلی این سیگنال عبارتند از :&nbsp;</p>\r\n\r\n<p>1- حالت High یا OnTime : حالتی که سیگنال در حداکثر میزان خود قرار گرفته است. معمولا وقتی این حالت نمایش داده می شود که سطح ولتاژ خط در آن لحظه 3.3 یا 5 ولت باشد (در اکثر مدارات دیجیتال از این دو سطح ولتاژ در داخل مدار استفاده می شود)</p>\r\n\r\n<p>2- حالت Low : حالتی که سیگنال در حداقل مقدار خود قرار گرفته. (نمایانگر سطح ولتاژ 0 ولت یا بدون پتانسیل)</p>\r\n\r\n<p>در تصویر فوق زمان هایی که سیگنال در حالت Low قرار دارد با رنگ نارنجی، و زمان هایی که سیگنال در حالت High قرار دارد با رنگ قرمز نمایش داده شده اند.</p>\r\n\r\n<p>اگر سیگنالی نصف زمان را در حالت High&nbsp;و نصف دیگر آن را در حالت Low باشد، می&shy;&zwnj;گوییم سیگنال Duty Cycle یا زمان کاری برابر با 50 درصد دارد. به همین ترتیب اگر سیگنالی 25 درصد از زمان را در حالت High باشد و 75 درصد در حالت Low، گوییم که سیگنال دارای Duty Cycle برابر با 25 درصد می باشد. در تصویر زیر به ترتیب از بالا به پایین سیگنال ها دارای Duty Cycle های 50 ، 75 و 25 درصد می باشند.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><img alt=\"تصویر 1\" src=\"/includes/images/uploads/multimedia/4x3/1548518793.1810284964.jpg\" /></p>\r\n\r\n<p>حال برای درک دلیل استفاده از تکنیک PWM &nbsp;به مثال زیر توجه کنید:</p>\r\n\r\n<p>تصور کنید بار یک لامپ 10 وات از باتری تامین می&zwnj;شود. در این حالت باتری ۱۰ وات از قدرت را تامین می&zwnj;کند، و لامپ این ۱۰ وات را به نور و گرما تبدیل می&zwnj;کند. فرض کنید که تلفات توان در هیچ جای دیگر در مدار نداریم.</p>\r\n\r\n<p>اگر ما یه کم نور لامپ می&zwnj;خواستیم، پس آن فقط ۵ وات از توان را جذب می&zwnj;کند، ما می&zwnj;توانیم یک مقاومت سری قرار دهیم تا ۵ وات توان را جذب کند، سپس لامپ می&zwnj;تواند ۵ وات دیگر را جذب کند. این کار می&zwnj;کند، اما اتلاف توان در مقاومت نه تنها باعث می&zwnj;شود که آن بسیار داغ شود، بلکه باعث اتلاف توان خواهد شد در حالیکه باتری هنوز ۱۰ وات را تامین می&zwnj;کند.</p>\r\n\r\n<p>راه حل این مشکل تغییر دوره کاری با استفاده از سوئیچ روشن و خاموش سریع لامپ است به طوری که آن را تنها در نیمی از زمان روشن و نیم دیگر خاموش می&zwnj;کند. آن گاه به طور متوسط توان گرفته شده توسط لامپ تنها ۵ وات است، و توان متوسط تامین شده توسط باتری هم تنها ۵ وات خواهد بود.</p>\r\n\r\n<p>اگر ما می&zwnj;خواستیم ۶ وات توان در لامپ اعمال شود، ما می&zwnj;توانیم سوئیچ را برای زمان بیشتری روشن بگذاریم نسبت به زمانی که خاموش است، آن گاه توان کمی بیشتر به طور متوسط به لامپ تحویل داده خواهد شد. این روشن - خاموش کردن سوئیچ، PWM نامیده می&zwnj;شود. مقدار توان انتقال یافته به بار متناسب با درصدی از زمان است که بار روشن است.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h3>برخی دیگر از نمونه&shy;&zwnj; کاربردهای PWM</h3>\r\n\r\n<p>میزان روشنایی یک LED را می&shy;توان با تنظیم درصد زمان کار کنترل کرد. با استفاده از یک ال ای دی RGB می&shy; توان میزان استفاده از هر رنگ را برای رسیدن به ترکیب رنگ مطلوب کنترل کرد. این کار با تنظیم میزان روشنایی انجام می&shy;شود.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>اگر میزان روشنایی هر سه رنگ به یک میزان باشد نتیجه یک نور سفید با روشنایی متغیر خواهد بود. اگر رنگ آبی و سبز به یک اندازه ترکیب شوند نتیجه نوری به رنگ سبز کله غازی است. به عنوان یک مثال پیچیده&shy;&zwnj;تر فرض کنید رنگ قرمز را در حالت کاملاً روشن (زمان کار 100%) ، سبز را با زمان کار 50% و آبی در حالت کاملاً خاموش قرار دهیم در این صورت نتیجه نوری به رنگ نارنجی خواهد بود.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>فرکانس موج مربعی در زمان کنترل LED باید به اندازه کافی در حالت high قرار گیرد تا میزان تیرگی مطلوب حاصل شود. یک موج با زمان کار 20% در فرکانس 1هرتز در همان حال که خاموش و روشن می&shy;شود از دید چشمان شما کاملاً واضح خواهد بود. یک موج با زمان کار 20% و فرکانس 100هرتز یا بیشتر فقط مقداری تیره&zwnj;&shy;تر از حالت کاملاً روشن دیده می&zwnj;&shy;شود. در حقیقت اگر داشتن LED کم نور هدف شما باشد دوره تناوب و یا فاصله زمانی نباید خیلی زیاد باشد.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h4>کنترل زاویه&shy;&zwnj;ی سروو موتور&nbsp; با PWM</h4>\r\n\r\n<p>علاوه بر کاربرد بالا از مدولاسیون پهنای پالس می&shy; توان برای کنترل زاویه&shy;&zwnj;ی موتورهای Servo متصل به یک قطعه&shy;&zwnj;ی مکانیکی مانند یک بازوی رباتیکی استفاده کرد. در این موتورها یک شفت (محور متحرک) وجود دارد که براساس خط کنترل خود تغییر زاویه می &shy;دهد.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<blockquote>\r\n<p><em><strong>&nbsp;مدولاسیون پهنای پالس ، استفاده از یک روش کنترل میزان توان به بار است. در واقع PWM تکنیکی است که به کمک آن می&zwnj;توانیم مقدار ولتاژ پایه&zwnj;های خروجی کنترلر (مثلاً&nbsp;کنترلر PWM و یا &nbsp;آیسی درایو موتور ) و فرکانس آن را کنترل کنیم.</strong></em></p>\r\n</blockquote>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>در ادامه قصد داریم تا در رابطه با اجزای موجود در مدار VRM یک مادربورد آشنا بشویم ....</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>منابع: wikipedia ،&nbsp;learn.sparkfun</p>\r\n', NULL, '1550063246.963262000.jpg', 1, 1, 1, 1518175864, 1554968087, 9, 1, 0, 0),
(38, 'آموزش jQuery در عمل (قسمت 1)', '<p>در این مجموعه از پست ها سعی بر آن داریم&nbsp;تا جی کوئری را به صورت حرفه ای و از پایه آموزش دهیم. با ما همراه باشید ....</p>\r\n\r\n<p>--more--</p>\r\n\r\n<h3>مقدمه</h3>\r\n\r\n<p>یکی از طبعات طراحی css آن بود که کدهای خام html را از استایل های مربوط به نحوه ی نمایش صفحات جدا می کرد. css با گسترده ی وسیعی از سلکتورها (selectors)، برای انتخاب و دستکاری عناصر صفحه ی وب به سرعت در بین برنامه نویسان و طراحان وب&nbsp;محبوب شد. یکی از مهمترین دلایل این محبوبیت، همین سادگی، قابل فهم بودن و در عین حال قدرت این سلکتورها بود. jQuery نیز&nbsp;از همین این سلکتورها جهت انتخاب و دستکاری&nbsp;عناصر صفحه وب بهره می گیرد.</p>\r\n\r\n<p>نحوه ی عملکرد&nbsp;کلی jQuery انتخاب عناصر صفحه ی html و سپس انجام عملیات مختلف بر روی آن هاست.</p>\r\n\r\n<p>&nbsp;</p>\r\n', NULL, '2147483647', 1, 0, 1, 1548769021, 1548769472, 0, 0, 0, 0),
(39, 'آشنایی با زبان برنامه نویسی جاوا (بخش اول)', '<p>در این پست سعی بر آن داریم تا شما را با زبان برنامه نویسی جاوا آشنا سازیم و با روال اجرای یک برنامه جاوا از نوشتن تا اجرای آن آشنا شویم. --more--</p>\r\n\r\n<h3>روال کلی اجرای یک برنامه جاوا</h3>\r\n\r\n<p style=\"text-align:justify\"><img alt=\"\" src=\"/includes/images/uploads/multimedia/4x3/1549784474.1610138316.png\" style=\"height:450px; width:400px\" /></p>\r\n\r\n<p style=\"text-align:justify\">ابتدا برنامه ی نوشته شده به زبان&nbsp;Java را در فایلی با پسوند&nbsp;<strong>java.</strong>&nbsp;ذخیره می&zwnj;کنیم و سپس توسط کامپایلر Java ، آن را کامپایل می&zwnj;کنیم. (نگران نباشید! در ادامه با مفهوم کامپایل شدن&nbsp;آشنا می شویم). در پروسه کامپایل اگر خطای<strong>&nbsp;</strong>دستوری&nbsp;در برنامه وجود داشته باشد مشخص می&zwnj;شود و برنامه کامپایل نمی&zwnj;شود. اما اگر خطایی در کار نباشد، برنامه کامپایل شده و کامپایلر Java فایلی با پسوند<strong>&nbsp;</strong>class.&nbsp;ایجاد می&zwnj;کند.&nbsp;اگر این فایل را اجرا کنیم با کدهایی ناخوانا مواجه می&zwnj;گردیم. به این کدها،&nbsp;<strong>کدهای میانی&nbsp;</strong>یا&nbsp;<strong>بایت کد</strong>&nbsp;گفته می&zwnj;شود.</p>\r\n\r\n<p style=\"text-align:justify\">برای <strong>تبدیل بایت کد به کد قابل فهم برای پردازنده</strong>، از ابزاری به نام JRE (مخفف Java Runtime Environment یا محیط اجرای جاوا) استفاده می&zwnj;کنیم.&nbsp;این ابزار برنامه ای به نام JVM (مخفف Java Virtual Machine یا ماشین مجازی جاوا) دارد که وظیفه تبدیل بایت کد به کد قابل فهم برای پردازنده را بر عهده دارد. در واقع می&zwnj;توان بایت کد را به هر سیستم عاملی منتقل نمود و برای اجرای بایت کد&nbsp;سیستم عامل مقصد تنها باید نرم افزار JRE را بر روی خودش نصب داشته باشد.</p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;</p>\r\n\r\n<p>در یک نگاه کلی JDK شامل موارد عنوان شده در تصویر زیر می باشد:</p>\r\n\r\n<p><img alt=\"\" src=\"/includes/images/uploads/multimedia/4x3/1549780958.377086770.png\" style=\"height:468px; width:624px\" /></p>\r\n\r\n<p style=\"text-align:justify\">همان طور که در شکل بالا نشان داده شده است، یکی از ابزارهای موجود در JDK، ابزاری به نام javac می&zwnj;باشد، که وظیفه کامپایل کد را بر عهده دارد، که کمی بالاتر به فرآیند کامپایل پرداخته شد. قبل از ادامه بحث برای درک بیشتر موضوع توضیحات بیشتری در رابطه با مفهوم کامپایلر و کامپایل شدن ارائه می&zwnj;دهیم. با بخش های مختلف JDK در ادامه و در مباحث تخصصی تر بیشتر آشنا خواهیم شد.</p>\r\n\r\n<h3 style=\"text-align:justify\">کامپایلر چیست؟</h3>\r\n\r\n<p style=\"text-align:justify\">شاید تا کنون شنیده باشید که سیستم های کامپیوتری که برنامه های ما را اجرا می&zwnj;کنند، تنها قادر به درک 0 و 1 ها هستند، برای مثال فرض کنید قرار است دو عدد با هم جمع شوند، تمام مراحل این عملیات درقالب مجموعه ای از 0 و 1 ها که قابل فهم برای پردازنده می&zwnj;باشد به سیستم کامپیوتری داده می&zwnj;شود. (محاسبات در سطح باینری انجام می شوند - یعنی هر عملیات محاسباتی&nbsp;در مبنای 0 و 1 ها انجام می پذیرد).&nbsp;اولین زبان&zwnj;های برنامه نویسی هم که با پیدایش کامپیوترها به وجود آمدند، از این مکانیزم بهره می بردند. به وضوح می توان دریافت که کدنویسی به این صورت به هیچ عنوان کار&nbsp;ساده ای نیست و همچنین فهم این کد برای برنامه نویس بسیار مشکل می&zwnj;باشد، پس با گذر زمان و تکامل زبان های برنامه نویسی اصطلاحا زبان&zwnj;های برنامه&zwnj;نویسی سطح بالا (مانند Java و ++C) به وجود آمدند.</p>\r\n\r\n<p style=\"text-align:justify\">سطح بالا به این مفهوم است که ساختار این زبان ها نزدیک به زبان انسان و درک و فهم این زبان&zwnj;ها برای انسان و به ویژه برنامه نویس خیلی راحت تر و ساده تر می&zwnj;باشد. برای مثال برای جمع دو عدد 3 و 1 &nbsp;به سادگی از عبارت 1+3&nbsp;استفاده می&zwnj;شود، و دیگر نیازی نیست که برنامه نویس اعداد و عملیات را به صورت باینری تبدیل کرده و اجرا نماید. حال تا اینجا کدی داریم که قابل درک برای انسان بوده ولی خود ماشین به تنهایی نمی تواند آن را درک کند. لذا برای تبدیل این کد به کدهای قابل درک برای ماشین (یا همان 0 و 1 ها)، نیاز به پیاده سازی یک مکانیزم وجود دارد.&nbsp;لذا این نیاز با&nbsp;یک برنامه یا اصطلاحا مترجم براورده می شود.&nbsp;این برنامه دستورات سطح بالا را به دستوراتی که قابل فهم برای پردازنده است، تبدیل کند.&nbsp;فرایند ترجمه خود به دو صورت کامپایل شدن و تفسیر شدن&nbsp;پیاده سازی شده است که در ادامه ی پست ها به تفاوت های میان آن دو خواهیم پرداخت.</p>\r\n\r\n<p style=\"text-align:justify\">فرایند کامپایل شده در زبان هایی مثل ++C یا #C با زبان&nbsp;Java اندکی متفاوت است. در زبان ++C وقتی عملیات کامپایل انجام می&zwnj;شود، کدهای نوشته شده مستقیما به کدهایی که برای پردازنده قابل فهم است، تبدیل می&zwnj;شود. برای مثال در سیستم عامل ویندوز وقتی یک برنامه به زبان ++C نوشته می&zwnj;شود، کامپایلر کدهای نوشته شده را به کدهای قابل فهم برای پردازنده تبدیل کرده و خروجی را در قالب فایل با پسوند exe. ایجاد می&zwnj;کند. ولی اگر این فایل خروجی را در سیستم عاملی مثل لینوکس اجرا کنیم با خطا مواجه می&zwnj;شویم چون پردازنده سیستم عامل لینوکس، کدهای کامپایل شده برای پردازنده سیستم عامل ویندوز را متوجه نمی&zwnj;شود، لذا باید دوباره کد را نوشته و در سیستم عامل لینوکس کامپایل کنیم تا کد کامپایل شده مناسب برای پردازنده این سیستم عامل تولید شود. پس اینجا با مشکلی مواجه هستیم که در هر سیستم عامل باید به صورت مجزا کد کامپایل شود و کد نوشته شده در یک سیستم عامل هنگام اجرا در سیستم عامل های دیگه با خطا مواجه می&zwnj;شود ولی در زبانی مانند Java عملیات کامپایل، کد نوشته شده را مستقیما به کدی که برای پردازنده قابل فهم باشد تبدیل نمی&zwnj;کند، بلکه آن را به کد میانی یا اصطلاحا بایت کد (Byte Code) تبدیل می&zwnj;کند. مزیت استفاده از این روش آن است&nbsp;که می&zwnj;توان بایت کد تولید شده را&nbsp;به هر سیستم عاملی منتقل کرد و خروجی آن را مشاهده نمود یا به عبارت دیگر این کار باعث می شود که وابستگی به نوع سیستم عامل وجود نداشته باشد و کد نوشته شده روی همه ی پلتفرم ها اجرا شود (ویژگی Cross Platform). این نکته یکی از کلیدی ترین و مهم ترین ویژگی های زبان برنامه نویسی Java می باشد.</p>\r\n\r\n<blockquote>\r\n<p>کامپایل شدن یعنی تبدیل دستورات سطح بالا (مثل دستور جمع کردن دو عدد ب صورت 1+2)، به دستورات قابل فهم برای پردازنده.</p>\r\n</blockquote>\r\n\r\n<h3>نسخه های جاوا</h3>\r\n\r\n<p>جاوا در سه نسخه رسمی ارائه شده است. این سه نسخه عبارت اند از:</p>\r\n\r\n<ul>\r\n	<li>\r\n	<p style=\"text-align:justify\"><strong>JAVA SE</strong> : مخفف&nbsp;<strong>Standard Edition</strong>&nbsp;است و همانطور که از نامش پیداست، نسخه پایه و استاندارد جاوا است و برای نوشتن هر برنامه&zwnj;ی جاوا به این نسخه احتیاج است.</p>\r\n	</li>\r\n	<li style=\"text-align:justify\"><strong>JAVA ME </strong>: مخفف&nbsp;<strong>Micro Edition</strong>&nbsp;است. نسخه&zwnj;ای برای نوشتن برنامه روی سخت افزارهای خاص مانند لوازم خانگی، موبایل، اسباب بازی&zwnj;ها و ... است. امروزه این نسخه از جاوا با وجود موبایل&zwnj;های هوشمند، کمتر مورد استفاده قرار می&zwnj;گیرد.</li>\r\n	<li style=\"text-align:justify\"><strong>JAVA EE</strong> : مخفف&nbsp;<strong>Enterprise Edition</strong>&nbsp;است. نسخه&zwnj;ی مدرن و سازمانی جاوا است. از این نسخه برای نوشتن برنامه&zwnj;های روی سرور استفاده می&zwnj;شود. در کل Java EE&nbsp;مجموعه ای از تکنولوژی&zwnj;ها&nbsp;است و یادگیری این نسخه از جاوا سخت و زمان&zwnj;بر است و باید سال&zwnj;ها به طور عملی کار شود تا بتوان خود را به عنوان یک Java EE کار حرفه&zwnj;ای معرفی کرد.</li>\r\n</ul>\r\n\r\n<h3>از کدام نسخه شروع کنیم؟</h3>\r\n\r\n<p>در ابتدا باید&nbsp;باید نسخه Java SE را یاد بگیریم. زیرا برای کار کردن با هر یک از دو نسخه دیگر، باید Java SE را بلد باشیم.</p>\r\n', NULL, '1549722229.1061337146.png', 1, 1, 1, 1518433302, 1549786559, 3, 1, 0, 0),
(41, 'اثر پیزوالکتریک چیست؟', '<p style=\"text-align: justify;\">لغت پیزوالکتریک یعنی الکتریسیته&zwnj;ی ناشی از فشار که از لغت یونانی به معنای فشردن گرفته شده است. به بیان ساده&nbsp;پیزوالکتریک&zwnj;ها موادی هستند که در صورت اعمال فشار یا تنش به آن&zwnj;ها، بار الکتریکی در سطوح خاصی از آن&zwnj;ها ظاهر می&zwnj;شود. این پدیده، اثر پیزوالکتریک مستقیم (Direct piezoelectric Effect) نام دارد که یک فرآیند قابل&zwnj;برگشت است، یعنی بطور معکوس هرگاه ماده&zwnj;ای با این خاصیت، در یک میدان الکتریکی واقع شود، ابعاد آن تغییر می&zwnj;کند (Reverse Piezoelectric Effect). در صورت وارون شدن جهت اعمال تنش یا فشار، جهت قطبش بارهای الکتریکی نیز معکوس می&zwnj;شود و با تغییر در جهت میدان الکتریکی اعمال&zwnj;شده جهت تغییر ابعاد ماده نیز، تغییر می&zwnj;یابد.</p>\r\n\r\n<p style=\"text-align: justify;\"><strong>&nbsp;</strong>اثر پیزوالکتریک در کریستالها (بلورها)، برخی از سرامیک ها و اجسام زیستی مانند استخوان DNA و پروتئین ها روی می دهد. در پیزو الکتریک انرژی ها به هم تبدیل می شوند، به همین خاطر می توانیم از آن به عنوان سنسور بسیار حساس استفاده کنیم. این ویژگی به آن&zwnj;ها اجازه می&zwnj;دهد به عنوان حسگرهای مکانیکی عمل کنند. به این علت که آن&zwnj;ها در پاسخ به فشار مکانیکی جریان الکتریکی تولید می&zwnj;کنند.</p>\r\n\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n', NULL, '0', 1, 0, 1, 1549833502, NULL, 0, 0, 0, 0),
(42, '', '', NULL, '0', 1, 1, 1, 1550053131, NULL, 0, 0, 0, 1),
(46, 'کاربرد دو ماسفت به عنوان سوییچ', '<p style=\"text-align:justify\">همان طوری که احتمالا می دانید، در برخی از مدارات الکترونیکی، از دو ماسفت به صورت موازی&nbsp;و به اصطلاح&nbsp; back-to-back به عنوان سوییچ استفاده شده است.</p>\r\n\r\n<p>این نحوه ی استفاده از ماسفت ها در تغذیه ی ورودی&nbsp;لپ تاپ ها زیاد به چشم می خورد.</p>\r\n\r\n<p style=\"text-align:justify\">اما نکته ی حائز اهمیت در این نوع کاربرد، این است که چرا از دو ماسفت استفاده کنیم در حالی که این عمل با یک ماسفت هم قابل انجام است؟</p>\r\n\r\n<p style=\"text-align:justify\">با ما باشید تا به آن بپردازیم.--more--</p>\r\n\r\n<h3>مقایسه ماسفت با ترانزیستورها</h3>\r\n\r\n<p>همان طوری که احتمالا می دانید، ماسفت ها نسل بهتر و پرکاربردتر ترانزیستورها هستند که امروزه در بسیاری از مدارات جای ترنزیستورها را گرفته اند.</p>\r\n\r\n<p style=\"text-align:justify\">علت اصلی آن هم حساس بودنشان به ولتاژ برای باز شدن مسیر از&nbsp; Drain به Source و مقاومت کم آن ها از Drain به Source است. در حالیکه ترانزیستورها وابسته به جریان اند تا مسیر جریان از Drain به Source باز شود، یعنی جریان مصرف می کنندو همچنین نسبت به ماسفت ها&nbsp;مقاومت بین دو پایه ی&nbsp;Drain و Source شان معمولا زیادتر است.</p>\r\n\r\n<p>ابتدا اندکی به تصویر زیر نگاه کنید:</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p style=\"text-align:justify\">این تصویر قسمت ورودی تغذیه ی لپ تاپ را نشان می دهد. همان طوری که مشخص است، برق ورودی از آداپتور( که معمولا 19 ولت یا 18.5 ولت مستقیم است)، در ابتدای ورود به مادربرد لپ تاپ از دو ماسفت میگذرد. این دو ماسفت حکم یک کلید را دارند تا در مواقع ضروری بسته شده و جریان برق وارد مادربرد نگردد.</p>\r\n\r\n<p style=\"text-align:justify\">پایه های Gate این دو ماسفت&nbsp;با ic راه انداز مادربرد (که گاها با نام های IO، Startup chip هم شناخته می شود)&nbsp;کنترل می شوند. بدین صورت که Startup چیپ مادربرد ولتاژ ورودی را خوانده و مثلا اگر میزان آن بیشتر از ولتاژ مورد نیاز ورودی مادربرد باشد، Gate یکی از ماسفت ها رابسته و اجازه ی عبور جریان داده نمی شود.&nbsp;&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n', NULL, '1558699527.1843308346.jpg', 1, 0, 1, 1552764901, 1558699582, 0, 0, 0, 0);
INSERT INTO `tbl_post` (`id`, `p_title`, `p_content`, `p_rate`, `p_image`, `u_id`, `published`, `allow_comments`, `creation_time`, `last_modify`, `like_count`, `dislike_count`, `comment_count`, `deleted`) VALUES
(47, 'ترجمه مقاله وایت پیپر بیت کوین', '<p style=\"text-align:justify\">بیت کوین به عنوان اولین ارز رمزنگاری شده و پرچم دار این صنعت؛ همچنان مورد توجه افراد زیادی در دنیا قرار دارد و جدا از علاقه مردم به خرید و فروش این کوین و کسب سود؛ توسعه دهندگان و کدنویسان زیادی در سرتاسر دنیا روی شبکه این ارز کار&nbsp; میکنند. پروژه های زیادی برای بهبود بلاکچین بیت کوین راه اندازی شده و حتی رقبای جدی و قوی در بازار شروع به رشد کردند. اما سوال اینجاست که اولین شخصی که این ایده به ذهنش رسید چه نقشه ای و برنامه ای برای هدف خود داشته است؟</p>\r\n\r\n<p>--more--</p>\r\n\r\n<p style=\"text-align:justify\">در این مقاله وایت پیپر بیت کوین که شامل اهداف و نقشه راه ای ارز است و سال ۲۰۰۸ توسط فرد ناشناسی به نام ساتوشی ناکاموتو به دنیا معرفی شد؛ ترجمه و در اختیار فارسی زبانان گذاشته شده است. مطالعه این مقاله و بررسی این ارز دیجیتالی میتواند به تمام دوستداران بیت کوین؛ مسیر روشن تری برای ادامه راه نشان دهد.</p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;</p>\r\n\r\n<h1 style=\"text-align:justify\">چکیده مطلب</h1>\r\n\r\n<p style=\"text-align:justify\"><strong>بیت کوین&nbsp;</strong>یک نسخه کاملا همتا به همتا از پول نقد الکترونیک است که سبب انجام پرداختهای آنلاین می شود طوریکه مستقیما از یک طرف به طرف دیگر فرستاده می شود و نیازی به گذر از یک موسسه مالی (نهاد مرکزی واسطه) نیست. بخشی از این راه حل را امضاهای دیجیتال&nbsp;فراهم می کنند اما اگر هنوز هم نیاز به یک شخص ثالث مطمئن باشد تا از خرج شدن دوباره&nbsp;ممانعت به عمل آید، در واقع مزایای اصلی این سیستم از دست می رود. این شبکه تراکنش ها را با تبدیل آنها به یک زنجیره مستمر بر پایه الگوریتم گواه اثبات کار&nbsp;(proof of work) هش محور برچسب زمانی می زند و سابقه ای را ایجاد می کند که بدون انجام دوباره گواه اثبات کار قابل تغییر نیست. طولانی ترین زنجیره نه تنها به عنوان گواه توالی رویداد های مشاهده شده عمل می کند بلکه ثابت می کند که از بزرگترین استخر قدرت پردازشی CPU تشکیل شده است. تا زمانی که اکثریت قدرت سی پی یو توسط نود هایی کنترل شود که در حمله به شبکه همکاری نمی کنند، بلدترین زنجیره ایجاد خواهد شد و مهاجمان را عقب می گذارند. این شبکه خودش نیازمند کمترین ساختار می باشد. پیام ها بر مبنای بهترین تلاش انتشار می یابند، نود ها می توانند شبکه را ترک کنند یا دوباره به خواست خود به آن ملحق شوند و طولانی ترین زنجیره اثبات کار را به عنوان مدرک آنچه اتفاق افتاده بپذیرند.</p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;</p>\r\n\r\n<h1 style=\"text-align:justify\">مقدمه</h1>\r\n\r\n<p style=\"text-align:justify\">تجارت بر روی اینترنت تقریبا به طور انحصاری متکی بر موسسات مالی شده است که این موسسات به عنوان شخص ثالث مورد اعتماد برای پردازش پرداخت های الکترونیک عمل می کنند. در حالی که این سیستم برای اکثریت تراکنش ها به خوبی عمل می کند اما این سیستم یک ضعف ذاتی دارد و آن&nbsp;<em><strong>وابستگی به اعتماد</strong>&nbsp;</em>است.&nbsp;تراکنش های کاملا غیر قابل برگشت در این مدل امکان پذیر نیست زیرا موسسات مالی این قدرت را دارند تا تراکنشی را به اختیار خود برگشت بزنند. هزینه این میانجی گری به افزایش هزینه تراکنش ها ختم می شود و امکان تراکنش های غیر رسمی کوچک را از میان بر می دارد و همچنین هزینه بیشتری برای فقدان توانایی پرداخت های غیر قابل برگشت موجود خواهد بود.&nbsp;با مطرح کردن امکان برگشت، نیاز به اعتماد بیشتر احساس می شود. در این حالت بازرگانان محتاط می شوند و درصد معینی از کلاه برداری غیر قابل اجتناب خواهد شد. این هزینه ها و شک و تردید ها در مورد پرداخت را می توان حضوری و با استفاده از پول فیزیکی حل کرد اما هیچ مکانیسمی برای انجام پرداختی بر روی یک کانال ارتباطی بدون نیاز به شخص ثالث موجود نیست.</p>\r\n\r\n<p style=\"text-align:justify\">آنچه امروزه مورد نیاز است یک سیستم پرداخت الکترونیک بر اساس تکنولوژی رمزنگاری است که جایگزین اعتماد شود و به هر دو طرف مشتاق اجازه دهد که مستقیما با همدیگر تراکنش داشته باشند و در این میان نیاز به شخص ثالث مورد اعتماد نباشد. تراکنش هایی که برگشت آنها از لحاظ محاسباتی غیر عملی است از فروشندگان در برابر تقلب محافظت می کنند. در این رساله، راه حلی برای مشکل&nbsp;<strong>دوبار خرج کردن</strong>&nbsp;با استفاده از سرور برچسب زمانی همتا به همتا پیشنهاد می شود و با استفاده از این راه حل، مدرک محاسباتی ترتیب زمانی تراکنش ها تولید می شود. این سیستم تا زمانی که نود های صادق مجموعا کنترل بیشتر قدرت CPU را به نسبت نود های مهاجم در دست داشته باشند، ایمن خواهد بود.</p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;</p>\r\n\r\n<h1 style=\"text-align:justify\">تراکنش ها</h1>\r\n\r\n<p>ما یک کوین دیجیتالی را به عنوان زنجیره ای از امضا های دیجیتال تعریف می کنیم. هر مالکی با استفاده از امضای دیجیتال هش بلاک قبلی، کوین و کلید عمومی فرد بعدی را انتقال می دهد و&nbsp;این موارد را به انتهای تراکنش جدید اضافه می کند. گیرنده وجه می تواند امضا ها را تایید کند و زنجیره مالکیت تایید شود.</p>\r\n\r\n<p><img alt=\"\" src=\"/includes/images/uploads/multimedia/4x3/1563260702.1268777526.jpg\" style=\"height:500px; width:667px\" /></p>\r\n\r\n<p style=\"text-align:justify\">مشکل البته این است که دریافت کننده وجه نمی تواند تایید کند که یکی از مالکان این کوین را دو بار خرج کرده است یا نه. یک راه حل رایج، معرفی یک مقام متمرکز مورد اعتماد است که هر تراکنش را مورد بررسی قرار می دهد. بعد از تایید هر تراکنش، کوین باید به محل ضرابخانه (جایی که کوین جدید تولید می شود) برگردانده شود تا کوین جدیدی صادر شود و تنها&nbsp;<strong>کوین هایی که مستقیما از ضرابخانه صادر شده اند مورد اعتماد هستند</strong>. مشکل این راه حل این است که سرنوشت کل سیستم پول بستگی به شرکتی دارد که کوین را ضرب می کند و هر تراکنشی باید از طریق آنها انجام شود، درست مانند یک بانک.</p>\r\n\r\n<p style=\"text-align:justify\">اما ما به روشی نیاز داریم که دریافت کننده وجه بداند که مالکین قبلی پیش تر هیچ تراکنشی را امضا نکرده اند. در اینجا منظور از اولین تراکنش، آن تراکنشی است که شمرده می شود و بنابراین به تلاش های بعدی برای خرج کردن دوباره اهمیت داده نمی شود.&nbsp;تنها راه برای تایید غیاب یک تراکنش، اگاهی از همه تراکنش ها می باشد.&nbsp;در مدل ضرابخانه، آن شرکت ضرب کننده از تمامی تراکنش ها آگاه بود و تصمیم می گرفت که کدام یک از آنها اول صورت گرفته است. برای انجام این کار بدون نیاز به یک شخص مورد اعتماد، تراکنش ها باید به شیوه عمومی اعلام شوند و در اینجا نیاز به سیستمی است تا مشارکان بر روی یک تاریخچه مجزا در مورد سفارش دریافتی توافق کنند. گیرنده وجه نیاز به مدرکی دارد که ثابت کند در زمان هر تراکنش، اکثریت نود ها بر آن تراکنش توافق داشته و آن را اولین تراکنش محسوب کرده اند.</p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;</p>\r\n\r\n<h1><strong>سرور برچسب زمانی (</strong><strong>Timestamp Server</strong><strong>)</strong></h1>\r\n\r\n<p style=\"text-align:justify\">راه حل پیشنهاد شده در بالا با یک سرور برچسب زمانی شروع می شود و این سرور با برداشتن هش یک بلاک برای برچسب زمانی زدن و انتشار گسترده هش کار می کند و این شبیه کار یک روزنامه است.&nbsp;برچسب زمانی ثابت می کند که داده ها در آن زمان به منظور کنترل هش وجود داشته اند. هر برچسب زمانی شامل برچسب زمانی قبلی در هش خود می باشد که یک زنجیره را شکل می دهد و هر برچسب زمانی اضافه شده برچسب های پیشین را تقویت می کند.</p>\r\n\r\n<p style=\"text-align:justify\"><img alt=\"\" src=\"/includes/images/uploads/multimedia/16x9/1563260898.474079144.jpg\" style=\"height:375px; width:667px\" /></p>\r\n\r\n<h1><strong>گواه اثبات کار (Proof of Work)</strong></h1>\r\n\r\n<p style=\"text-align:justify\">برای پیاده سازی یک سرور برچسب زمانی توزیع شده در یک مبنای همتا به همتا، لازم است که از یک سیستم گواه اثبات کار مشابه Hashcash که توسط Adam Back اختراع شده، استفاده کرد. گواه اثبات کار شامل جستجوی ارزش در زمانی است که هش صورت می گیرد مانند&nbsp;<strong>SHA-256</strong>&nbsp;که در آن هش با عدد صفر بیت شروع می شود. متوسط کار مورد نیاز در تعداد صفر بیتی های مورد نیاز نمایان گر است و می تواند با اجرای یک هش مجزا تایید شود.</p>\r\n\r\n<p style=\"text-align:justify\">برای شبکه برچسب زمانی، گواه اثبات کار با افزایش یک عدد اختیاری در بلاک پیاده سازی می شود و این ادامه می یابد تا زمانی که ارزشی پیدا شود که به هش بلاک، صفر بیت مورد نیاز را بدهد. زمانی که تلاش سی پی یو برای راضی کردن گواه اثبات کار گسترش یافت، بلاک بدون انجام دوباره کار، قابل تغییر نخواهد بود. از آنجا که بلاک های بعدی در زنجیره دنبال آن قرار می گیرند، کار تغییر بلاک شامل تغییر دوباره همه بلاک های بعد از آن است.</p>\r\n\r\n<p style=\"text-align:justify\"><img alt=\"\" src=\"/includes/images/uploads/multimedia/16x9/1563260972.1721927266.jpg\" style=\"height:375px; width:667px\" /></p>\r\n\r\n<p style=\"text-align:justify\">گواه اثبات کار همچنین مشکل تعیین نماینده را در اکثریت تصمیم گیری ها حل می کند. اگر اکثریت بر اساس مدل یک رای برای هر آی پی آدرس باشد، این می تواند توسط هر فردی که قادر به تخصیص آی پی های زیاد باشد دستخوش تغییر شود. گواه اثبات کار اساسا دارای مدل یک رای برای هر سی پی یو می باشد. تصمیم اکثریت توسط طولانی ترین زنجیره ارائه داده می شود که عظیم ترین تلاش گواه اثبات کار در آن زنجیره سرمایه گذاری شده است. اگر اکثریت قدرت سی پی یو توسط نود های صادق کنترل شود، این زنجیره صادق سریعتر از همه رشد می کند و زنجیره های رقیب و خرابکار را کنار می زند. یک مهاجم برای تغییر بلاک قبلی مجبور است که گواه اثبات کار آن و همه بلاک های بعد از آن را دوباره انجام دهد و بعد از این کارهاست که می تواند از کار نود های صادق سبقت گیرد و پیش بیافتد. در ادامه خواهید دید که احتمال موفقیت یک مهاجم با افزایش بلاک ها به طور فزاینده ای کاهش می یابد.</p>\r\n\r\n<p style=\"text-align:justify\">برای جبران سرعت سخت افزاری در حال افزایش و تغییر علاقه به مدیریت کردن نود ها به مرور زمان، سختی گواه اثبات کار توسط یک میانگین متحرک که عدد متوسطی از بلاک ها را در ساعت هدف می گیرد، تعیین می شود. اگر بلاک ها خیلی سریع تولید شده باشند، سختی نیز افزایش پیدا می کند.</p>\r\n\r\n<h1 style=\"text-align:justify\">شبکه</h1>\r\n\r\n<p>مراحل راه اندازی شبکه به شرح زیر می باشد:</p>\r\n\r\n<p><strong>۱. تراکنش های جدید به همه نود ها فرستاده میشود.</strong></p>\r\n\r\n<p><strong>۲. هر نود تراکنش جدید را در یک بلاک قرار می دهد.</strong></p>\r\n\r\n<p><strong>۳. هر نود برای پیدا کردن صحت تراکنش با الگوریتم گواه اثبات کار شروع به فعالیت میکند.</strong></p>\r\n\r\n<p><strong>۴. وقتی که نودی به پاسخ درست رسید؛ بلاک را برای همه نود ها انتشار می دهد.</strong></p>\r\n\r\n<p><strong>۵. نود های دیگر تنها زمانی بلاک را می پذیرند که صحت تراکنش های آن را بپذیرند و قبلا خرج نشده باشد.</strong></p>\r\n\r\n<p><strong>۶. نود ها پذیرش بلاک را با کار کردن روی ایجاد بلاک بعدی در زنجیره ابراز می کنند؛ در این حالت از هش بلاک پذیرفته شده به عنوان هش قبلی استفاده می شود.</strong></p>\r\n\r\n<p style=\"text-align:justify\">نودها همیشه طولانی ترین زنجیره را به عنوان زنجیره درست تلقی می کنند و به کار کردن برای گسترش آن ادامه می دهند. اگر دو نود نسخه های متفاوتی از بلاک بعدی را به طور همزمان انتشار دهند، بعضی از نود ها یکی از این نسخه ها را زودتر دریافت می کنند. در این حالت این نود ها بر روی اولین نسخه ای که دریافت می کنند، کار خواهند کرد اما در صورتی که نسخه دیگر طویل تر شود، آن را ذخیره خواهند کرد. زمانی که گواه اثبات کار بعدی پیدا شود چنین رابطه ای بر هم زده می شود و یکی از این شاخه ها طویل تر خواهد شد. در این حالت، نود هایی که بر روی شاخه دیگر کار کرده اند به شاخه طولانی تر انتقال خواهند یافت.</p>\r\n\r\n<p style=\"text-align:justify\">انتشار تراکنش جدید لازم نیست که به تمامی نود ها برسد. زمانی که این تراکنش ها به نود های کافی برسند، طولی نخواهد کشید که تبدیل به یک بلاک خواهند شد.&nbsp;اگر یک نود بلاکی را دریافت نکند، در زمان دریافت بلاک بعدی آن را تقاضا می کند و غیاب یک بلاک را تشخیص می دهد.</p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;</p>\r\n\r\n<h1 style=\"text-align:justify\">انگیزه</h1>\r\n\r\n<p style=\"text-align:justify\">معمولا اولین تراکنش در یک بلاک تراکنش خاصی است که کوین جدیدی را شروع می کند و خالق بلاک، مالک آن خواهد شد. این انگیزه ای را برای نود ها ایجاد می کند تا از شبکه پشتیبانی کنند و شیوه ای را فراهم می کند که در ابتدا به توزیع کوین ها به داخل حلقه پرداخته شود زیرا مقامی مرکزی برای صادر کردن آنها وجود ندارد. اضافه شدن یکنواخت مقدار ثابتی از کوین های جدید قابل مقایسه با استخراج گران طلا است که منابع را برای افزودن طلا به چرخه مصرف می کنند. در مثال ما زمان، سی پی یو و الکتریسیته مصرف می شود.</p>\r\n\r\n<p style=\"text-align:justify\">این انگیزه را همچنین می توان از طریق کارمزد های تراکنش تامین وجه کرد. اگر ارزش خروجی یک تراکنش کمتر از ارزش ورودی آن باشد، این تفاوت به صورت کارمزد یک تراکنش خواهد بود که به ارزش انگیزه بلاک محتوی تراکنش اضافه می شود. وقتی که مقدار از قبل تعیین شده ای از کوین ها به چرخه داخل شدند، انگیزه را می توان تماما از کارمزد های تراکنش تامین کرد و کاملا از تورم آزاد شد.</p>\r\n\r\n<p style=\"text-align:justify\">این انگیزه ممکن است به نود ها کمک کند که صادق باقی بمانند. اگر یک مهاجم طماع قادر باشد که قدرت سی پی یو بیشتری از نود های صادق جمع کند، او باید بین استفاده از آن برای فریب مردم با پس گرفتن و دزدیدن پرداختی های خود و یا استفاده از آن برای تولید کوین های جدید یکی را انتخاب کند.&nbsp;<strong>برای چنین شخصی پیروی از قوانین سود بیشتری خواهد داشت زیرا تخلف از قوانین و ایجاد کوین های جدید برای آن فرد، سیستم را تضعیف خواهد کرد و اعتبار ثروت آن فرد را نیز از میان می برد.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>احیای فضای هارد دیسک</h1>\r\n\r\n<p style=\"text-align:justify\">زمانی که آخرین تراکنش در یک کوین زیر بلاک های کافی پنهان شد، تراکنش های خرج شده قبلی را می توان رها کرد تا در فضای دیسک ذخیره شود. برای تسهیل این کار بدون شکستن هش بلاک، تراکنش ها به صورت درخت Merkle&nbsp;در خواهند آمد که تنها ریشه آن در هش بلاک داخل شده است. بلاک های قدیمی را می توان با بریدن شاخه های درخت فشرده کرد. هش های داخلی لازم نیست که ذخیره شوند.</p>\r\n\r\n<p style=\"text-align:justify\"><img alt=\"\" src=\"/includes/images/uploads/multimedia/16x9/1563261130.1062237276.jpg\" style=\"height:432px; width:768px\" />یک هدر بلاک بدون تراکنش حدود ۸۰ بایت است. اگر فرض کنیم که بلاک ها هر ده دقیقه ایجاد شوند، این مقدار در سال ۴.۲ MB خواهد شد. معمولا سیستم های کامپیوتری که در سال ۲۰۰۸ فروخته میشدند ۲ گیگ رم دارند و بنا بر قانون مور (Moore) می توان پیش بینی کرد که رشد حال حاضر ۱.۲ GB در سال می باشد؛ حتی اگر هدر بلاک ها هم در حافظه نگه داری شوند مشکل ذخیره پیش نخواهد آمد.</p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;</p>\r\n\r\n<h1 style=\"text-align:justify\">تایید پرداخت تسهیل شده</h1>\r\n\r\n<p style=\"text-align:justify\">تایید پرداختی ها بدون راه اندازی یک نود کامل نیز ممکن است. یک کاربر تنها نیاز است که یک کپی از هدر بلاک های درازترین زنجیره گواه اثبات کار را نگه دارد که این کاربر می تواند با بررسی نود های شبکه به این کپی دست یابد و قانع شود که او طولانی ترین زنجیره را دارد. این کاربر باید شاخه Merkle که تراکنش را به بلاکی که در آن برچسب زمانی شده مرتبط می کند، حفظ کند. او نمی تواند تراکنش را به تنهایی بررسی کند بلکه با مرتبط کردن آن به مکانی در زنجیره می تواند آن را انجام دهد؛ او می تواند ببیند که یک نود شبکه آن تراکنش را پذیرفته است و بلاک های اضافه شده بعد از آن بیشتر مورد پذیرش شبکه می باشد.</p>\r\n\r\n<p style=\"text-align:justify\"><img alt=\"\" src=\"/includes/images/uploads/multimedia/16x9/1563261232.2139925625.jpg\" style=\"height:432px; width:768px\" />چنین تاییدی تا زمانی که نود های صادق شبکه را کنترل می کنند، قابل اعتماد است. اما زمانی که مهاجم در شبکه بیشتر قدرت بگیرد، این تایید آسیب پذیر خواهد بود. در حالی که نود های شبکه می توانند تراکنش ها را به تنهایی تایید کنند؛ این روش تسهیل شده می تواند توسط یک مهاجم مورد سوء استفاده قرار بگیرد و تا زمانی که بر شبکه سیطره دارد، قادر به ایجاد تراکنش های جعلی خواهد بود. یک استراتژی برای محافظت در برابر این تهدید، پذیرفتن هشدار از جانب نود های شبکه می باشد که این هشدار ها در زمانی داده می شود که نود ها بلاک نامعتبری را شناسایی می کنند. این بلاک های نامعتبر نرم افزار کاربر را به فعالیت وا می دارند تا کل بلاک و تراکنش های هشدار داده شده را دانلود کند و این ناسازگاری را تایید کند. کسب و کار هایی که پیوسته پرداختی دریافت می کنند احتمالا هنوز بخواهند که نود های خودشان را برای امنیت مستقل تر و تایید سریعتر راه اندازی کنند.</p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;</p>\r\n\r\n<h1 style=\"text-align:justify\">ترکیب و تقسیم ارزش</h1>\r\n\r\n<p style=\"text-align:justify\">اگرچه مدیریت کوین ها به صورت فردی ممکن است اما انجام تراکنش جداگانه برای هر یک سنت (دلار) انتقالی دشوار می باشد. با اجازه دادن به تقسیم و ترکیب ارزش، تراکنش ها شامل ورودی ها و خروجی های متعددی می شوند. معمولا یا یک ورودی مجزا از تراکنش بزرگتر قبلی موجود خواهد بود یا ورودی های متعددی مقادیر کوچک تر را ترکیب می کنند و حداکثر نیز دو خروجی موجود خواهد بود: یکی برای پرداختی و دیگری برای بازگرداندن تغییر به فرستنده در صورتی که چنین تغییری موجود باشد.</p>\r\n\r\n<p style=\"text-align:justify\"><img alt=\"\" src=\"/includes/images/uploads/multimedia/16x9/1563261317.234943611.jpg\" style=\"height:493px; width:876px\" />البته این گنجایش خروجی باید مورد توجه قرار بگیرد که در آن یک تراکنش به چندین تراکنش وابسته است و آن تراکنش ها نیز بر بسیاری دیگر وابسته می باشند که البته این موضوع در اینجا مشکل نیست. هرگز نیاز به استخراج یک کپی کاملا مستقل از تاریخچه تراکنش نیست.</p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;</p>\r\n\r\n<h1 style=\"text-align:justify\">حریم خصوصی</h1>\r\n\r\n<p style=\"text-align:justify\">مدل بانکداری سنتی با محدودیت دسترسی اطلاعات برای طرفین و تبدیل خود به یک شخص ثالث مورد اعتماد، تا حدودی حریم خصوصی یجاد می کند. نیاز به اعلام همه تراکنش ها به صورت عمومی این مدل را در اینجا غیر ممکن می سازد اما هنوز هم می توان حریم خصوصی را با تجزیه جریان اطلاعات در مکان دیگر حفظ کرد و کلید های عمومی را ناشناس نگه داشت. عموم می توانند ببینند که فردی در حال فرستادن مقداری به فرد دیگر است اما اطلاعاتی که تراکنش را به فرد خاصی مرتبط کند، موجود نیست. این مشابه همان سطح اطلاعاتی است که توسط صرافی های سهام بیرون داده می شود که زمان و اندازه ترید های فردی که به آن tape گفته می شود، در معرض عموم قرار می گیرد اما مشخص نمی شود که طرفین تراکنش چه کسانی هستند.</p>\r\n\r\n<p style=\"text-align:justify\"><img alt=\"\" src=\"/includes/images/uploads/multimedia/16x9/1563261372.413186373.jpg\" style=\"height:300px; width:533px\" /></p>\r\n\r\n<p style=\"text-align:justify\">یک جفت کلید جدید نیز به عنوان یک محافظ اضافی برای هر تراکنش به کار می رود و این باعث می شود که تراکنش ها به یک مالک مشترک مرتبط نشوند. در مورد تراکنش های با ورودی متعدد چنین ارتباطی هنوز غیر قابل اجتناب است و مالکیت ورودی ها توسط یک فرد خاص را برملا می کند. خطر در اینجاست که اگر مالک یک کلید آشکار شود، ارتباط می تواند تراکنش های دیگری را متعلق به همان مالک هستند، برملا سازد.</p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;</p>\r\n\r\n<h1 style=\"text-align:justify\">محاسبات</h1>\r\n\r\n<p style=\"text-align:justify\">ما این سناریو را مورد بررسی قرار می دهیم که یک مهاجم سعی کند زنجیره دیگری را سریعتر از زنجیره اصلی (درست) ایجاد کند. حتی اگر چنین کاری انجام شود، سیستم را در معرض تغییراتی مانند&nbsp;<strong>ایجاد ارزش به صورت غیر منتظره یا برداشت پولی که هرگز متعلق به مهاجم نبوده</strong>، قرار نخواهد داد. نود ها یک تراکنش نامعتبر را به عنوان پرداختی نمی پذیرند و نود های صادق هرگز بلاکی را که محتوی آن است، قبول نخواهند کرد. یک مهاجم تنها می تواند سعی کند که یکی از تراکنش های خود را تغییر دهد تا پولی را که اخیرا خرج کرده، دوباره خرج کند.</p>\r\n\r\n<p style=\"text-align:justify\"><strong>رقابت بین زنجیره صادق و زنجیره مهاجم را می توان به عنوان Binomial Random Walk توصیف کرد. اگر زنجیره اصلی یک بلاک پیدا کند و رهبری خود را به اندازه +1 افزایش دهد و اگر زنجیره مهاجم به اندازه یک بلاک گسترش یابد و خلا را&nbsp; به اندازه -1 تغییر دهد، شکست حاصل خواهد شد و هک صورت می گیرد.</strong></p>\r\n\r\n<p style=\"text-align:justify\">احتمال اینکه یک مهاجم بتواند کمبود ایجاد شده را جبران کند مشابه مسئله Gambler`s Ruin (نابودی قمارباز) می باشد. فرض کنید یک قمارباز که اعتبار نامحدودی دارد از یک کسری شروع می کند و احتمالا به دفعات نامحدود بازی می کند تا سر به سر شود. ما می توانیم احتمال سر به سر شدن او را محاسبه کنیم یا اگر به بحث خودمان برگردیم می توانیم احتمال اینکه یک مهاجم به زنجیره اصلی برسد را به صورت زیر محاسبه کنیم.</p>\r\n\r\n<p style=\"text-align:justify\">خب فرض کنیم P احتمال اینکه نود اصلی بلاک بعدی را پیدا کند، q احتمال اینکه مهاجم بلاک بعدی را پیدا کند و qz&nbsp;احتمال اینکه مهاجم بتواند z بلاک عقب افتاده را جبران کند.</p>\r\n\r\n<p style=\"text-align:justify\">اگر p بزرگتر از q باشد، احتمال به صورت نمایی و در حد زیاد کاهش می یابد زیرا تعداد بلاک هایی که مهاجم قصد جبران آن را دارد، افزایش پیدا می کند. در این حالت احتمالات بر ضد مهاجم است و اگر او در اوایل کار یک خیزش خوش شانسانه به سمت جلو نداشته باشد، شانسش بسیار کاهش می یابد و خیلی عقب می افتد.</p>\r\n\r\n<p style=\"text-align:justify\">حال به بررسی مدت زمانی می پردازیم که گیرنده یک تراکنش جدید باید منتظر باشد قبل از اینکه به اندازه کافی مطمئن شود که فرستنده نمی تواند تراکنش را تغییر دهد. فرض می کنیم فرستنده مهاجمی است که می خواهد گیرنده را قانع کند که برایش پرداختی ارسال کرده است، سپس این مهاجم بعد از مدتی پرداختی را برای خود بر می گرداند. وقتی که چنین چیزی روی می دهد، گیرنده هشدار دریافت می کند اما فرستنده امیدوار است که برای این کار دیر شده باشد.</p>\r\n\r\n<p style=\"text-align:justify\">گیرنده یک جفت کلید جدید را ایجاد می کند و کلید عمومی را به زودی و قبل از امضا به فرستنده می فرستد. این مانع از آماده کردن یک زنجیره بلاک پیش از موعد توسط فرستنده می شود. فرستنده این را با کار کردن پیوسته روی زنجیره انجام می دهد تا زمانی که به اندازه کافی خوش شانس باشد و پیش بیافتد و سپس تراکنش را در آن لحظه انجام خواهد داد. زمانی که تراکنش فرستاده شد، فرستنده ناصادق شروع به کار کردن سری بر روی زنجیره موازی می کند که شامل نسخه دیگری از تراکنش او است.</p>\r\n\r\n<p style=\"text-align:justify\">گیرنده تا زمانی که تراکنش به یک بلاک اضافه می شود، صبر می کند و z بلاک بعد از آن متصل می شود. گیرنده مقدار پیشرفت دقیق مهاجم را نمی داند اما فرض می کند که بلاک های صادق زمان مورد انتظار متوسط برای هر بلاک به طول بیانجامند. پیشرفت احتمالی مهاجم توزیع Poisson با ارزش مورد انتظار خواهد بود:</p>\r\n\r\n<p style=\"text-align:justify\"><img alt=\"\" src=\"/includes/images/uploads/multimedia/16x9/1563261457.911845008.jpg\" style=\"height:190px; width:338px\" /></p>\r\n\r\n<p style=\"text-align:justify\">برای محاسبه احتمال اینکه مهاجم هنوز بتواند به جبران برسد، تراکم Poisson برای هر مقدار پیشرفتی که مهاجم می توانسته انجام دهد در احتمال جبران از آن نقطه ضرب می کنیم:</p>\r\n\r\n<p style=\"text-align:justify\"><img alt=\"\" src=\"/includes/images/uploads/multimedia/16x9/1563261520.590964048.jpg\" style=\"height:200px; width:356px\" /></p>\r\n\r\n<p style=\"text-align:justify\">برای اجتناب از جمع کردن دنباله نامحدود توزیع به تنظیم دوباره می پردازیم&hellip;</p>\r\n\r\n<p style=\"text-align:justify\"><img alt=\"\" src=\"/includes/images/uploads/multimedia/16x9/1563261568.303383620.jpg\" style=\"height:200px; width:356px\" /></p>\r\n\r\n<p>حال به فرمول های بالا را در زبان C تبدیل به کد میکنیم&hellip;.</p>\r\n\r\n<p><img alt=\"\" src=\"/includes/images/uploads/multimedia/16x9/1563261615.2081000542.jpg\" style=\"height:493px; width:876px\" /></p>\r\n\r\n<p>تعدادی از نتایج را اجرا می کنیم و می بینیم که احتمال با z به طور نمایی کاهش می یابد.</p>\r\n\r\n<p><img alt=\"\" src=\"/includes/images/uploads/multimedia/16x9/1563261682.766734265.jpg\" style=\"height:666px; width:500px\" /></p>\r\n\r\n<p>حل برای p کمتر از 0.1 درصد &hellip;</p>\r\n\r\n<p><img alt=\"\" src=\"/includes/images/uploads/multimedia/16x9/1563261752.1361638568.jpg\" style=\"height:400px; width:500px\" /></p>\r\n\r\n<h1>نتیجه گیری</h1>\r\n\r\n<p style=\"text-align:justify\">در این طرح سیستمی برای تراکنش های الکترونیک بدون نیاز به اعتماد پیشنهاد شد. این نوشتار از قالب معمول کوین های ساخته شده از امضا های دیجیتال شروع شد که این مدل کنترل قاطع مالکیت را فراهم می کند اما بدون روشی برای اجتناب از حمله ی دوباره خرج کردن،&nbsp;این مدل ناقص خواهد بود. برای حل این مشکل یک شبکه همتا به همتا پیشنهاد شد که از گواه اثبات کار برای ثبت تاریخچه عمومی تراکنش ها استفاده می کرد. تغییر این تراکنش ها سریعا از لحاظ محاسباتی برای مهاجم غیر عملی می شود به شرطی که نود های صادق اکثریت قدرت سی پی یو را کنترل کنند. این شبکه به دلیل ساختار غیر متمرکز آن قوی می باشد. نود ها همه با هم با هماهنگی کار می کنند. آنها نیازی به شناخته شدن ندارند زیرا پیام ها به مکان خاصی فرستاده نمی شوند و تنها نیاز است که بر مبنای بهترین تلاش تحویل داده شوند. نود ها می توانند شبکه را ترک کنند و به میل خود دوباره به آن بپیوندند و زنجیره گواه اثبات کار را به عنوان مدرک آنچه که در غیاب آنها انجام گرفته بپذیرند. آنها با قدرت پردازشی خود رای می دهند و پذیرش بلاک های معتبر را با کار بر روی&nbsp; توسعه آنها نشان می دهند. همچنین رد بلاک های نامعتبر با امتناع از کار بر روی آنها انجام می شود. هر گونه قانون و انگیزه مورد نیاز را می توان با استفاده از الگوریتم اجماع اعمال کرد.</p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;</p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;</p>\r\n\r\n<p style=\"text-align:justify\">منابع:&nbsp;mihanblockchain و ویکی پدیا و bitcoin.com</p>\r\n', NULL, '1563262053.53667242.jpg', 1, 1, 1, 1563262062, 1563262377, 0, 0, 0, 0);

--
-- Triggers `tbl_post`
--
DROP TRIGGER IF EXISTS `tbl_post_AFTER_DELETE`;
DELIMITER $$
CREATE TRIGGER `tbl_post_AFTER_DELETE` AFTER DELETE ON `tbl_post` FOR EACH ROW BEGIN
	UPDATE tbl_user SET tbl_user.post_count = tbl_user.post_count - 1
    WHERE 	OLD.u_id = tbl_user.id;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `tbl_post_AFTER_INSERT`;
DELIMITER $$
CREATE TRIGGER `tbl_post_AFTER_INSERT` AFTER INSERT ON `tbl_post` FOR EACH ROW BEGIN
	UPDATE tbl_user SET tbl_user.post_count = (tbl_user.post_count) +1
    WHERE tbl_user.id = NEW.u_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_post_cat`
--

DROP TABLE IF EXISTS `tbl_post_cat`;
CREATE TABLE IF NOT EXISTS `tbl_post_cat` (
  `post_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  PRIMARY KEY (`cat_id`,`post_id`),
  KEY `FK_PostCat_Cat` (`cat_id`),
  KEY `FK_PostCat_Post` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `tbl_post_cat`
--

INSERT INTO `tbl_post_cat` (`post_id`, `cat_id`) VALUES
(27, 8),
(28, 8),
(31, 8),
(38, 8),
(39, 8),
(24, 9),
(36, 9),
(37, 9),
(41, 9),
(46, 9),
(27, 10),
(28, 10),
(31, 10),
(38, 10),
(39, 10),
(27, 12),
(28, 12),
(31, 12),
(38, 12),
(37, 14),
(41, 14),
(46, 14),
(28, 22),
(31, 22),
(38, 27),
(24, 28),
(36, 29),
(41, 29),
(46, 29),
(36, 30),
(37, 30),
(41, 30),
(46, 30),
(36, 31),
(41, 31),
(39, 32),
(47, 33),
(47, 34),
(47, 35);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sent_mails`
--

DROP TABLE IF EXISTS `tbl_sent_mails`;
CREATE TABLE IF NOT EXISTS `tbl_sent_mails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `u_email` varchar(45) COLLATE utf8_persian_ci NOT NULL,
  `time` varchar(45) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=313 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `tbl_sent_mails`
--

INSERT INTO `tbl_sent_mails` (`id`, `u_email`, `time`) VALUES
(311, 'nasrmehran77@gmail.com', '1561798013'),
(312, 'nasrmehran77@gmail.com', '1561798076');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `u_name` varchar(25) COLLATE utf8_persian_ci DEFAULT NULL,
  `u_pass` varchar(32) COLLATE utf8_persian_ci DEFAULT NULL,
  `u_type` tinyint(1) DEFAULT '3',
  `u_rate` tinyint(1) DEFAULT NULL,
  `u_email` varchar(45) COLLATE utf8_persian_ci DEFAULT NULL,
  `f_name` varchar(45) COLLATE utf8_persian_ci DEFAULT NULL,
  `l_name` varchar(45) COLLATE utf8_persian_ci DEFAULT NULL,
  `activated` tinyint(1) DEFAULT '0',
  `age` tinyint(2) DEFAULT NULL,
  `sex` tinyint(1) DEFAULT '1',
  `bio` varchar(450) COLLATE utf8_persian_ci DEFAULT NULL,
  `avatar` varchar(45) COLLATE utf8_persian_ci DEFAULT NULL,
  `signup_time` int(11) DEFAULT NULL,
  `activation_code` varchar(32) COLLATE utf8_persian_ci DEFAULT NULL,
  `post_count` int(11) DEFAULT '0',
  `follower_count` tinyint(5) DEFAULT '0',
  `following_count` tinyint(5) DEFAULT '0',
  `random_hash` varchar(32) COLLATE utf8_persian_ci DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_name_UNIQUE` (`u_name`),
  UNIQUE KEY `email_UNIQUE` (`u_email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `u_name`, `u_pass`, `u_type`, `u_rate`, `u_email`, `f_name`, `l_name`, `activated`, `age`, `sex`, `bio`, `avatar`, `signup_time`, `activation_code`, `post_count`, `follower_count`, `following_count`, `random_hash`, `deleted`) VALUES
(1, 'mehran', 'bce0e7ef782b9cf5e9f2399face18b0d', 1, 5, 'nasrmehran77@gmail.com', 'mehran', 'nasr', 1, 22, 1, 'مهران نصر هستم 23 ساله دارای مدرک کارشناسی نرم افزار. برنامه نویسی را از سال 91 شروع کردم و حدود 5 سال هست که پروژه های برنامه نویسی و طرای وب را بصورت فریلنسینگ دنبال می کنم. تخصص بنده بیشتر در طراحی و پیاده سازی دیتابیس، برنامه نویسی سمت سرور و کلاینت، و طراحی و پیاده سازی وب سایت ها می باشد. اما در حوزه های لینوکس، شبکه، تعمیرات تخصصی لپ تاپ، کامپیوتر و تجهیزات جانبی هم فعالیت می کنم...', '1555156476.306122010.jpg', 1541100263, '8140014', 8, 23, 7, '4444', 0),
(2, 'ali', 'f0e60eb923dd87cc97028d698b937944', 2, 3, 'mrparadohx1397@gmail.com', 'mehrab', 'bbb', 1, 23, 1, 'new fgsdfhbio is here', 'avatar_default.png', 1546677774, '8506408', 0, 0, 0, NULL, 0),
(3, 'ehsan1368', 'e88da599bb96cc2672acb10e1cf56991', 3, NULL, 'mrparadox1397@gmail.com', 'ehsan', 'hannanzadeh', 0, 29, 1, 'کارشناسی ارشد امنیت، برنامه نویس کامپیوتر', 'avatar_default.png', 1555062591, '9161040', 0, 0, 0, NULL, 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_cat`
--
ALTER TABLE `tbl_cat`
  ADD CONSTRAINT `FK_Self_Join_Cat` FOREIGN KEY (`cat_parent`) REFERENCES `tbl_cat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_comment`
--
ALTER TABLE `tbl_comment`
  ADD CONSTRAINT `FK_Comment_Post` FOREIGN KEY (`post_id`) REFERENCES `tbl_post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Self` FOREIGN KEY (`parent_id`) REFERENCES `tbl_comment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_friendship`
--
ALTER TABLE `tbl_friendship`
  ADD CONSTRAINT `FK_friendship_user_1` FOREIGN KEY (`u_id_1`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_friendship_user_2` FOREIGN KEY (`u_id_2`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_like`
--
ALTER TABLE `tbl_like`
  ADD CONSTRAINT `FK_like_post` FOREIGN KEY (`p_id`) REFERENCES `tbl_post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_like_user` FOREIGN KEY (`u_id`) REFERENCES `tbl_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbl_post`
--
ALTER TABLE `tbl_post`
  ADD CONSTRAINT `FK_post_user` FOREIGN KEY (`u_id`) REFERENCES `tbl_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbl_post_cat`
--
ALTER TABLE `tbl_post_cat`
  ADD CONSTRAINT `FK_postCat_cat` FOREIGN KEY (`cat_id`) REFERENCES `tbl_cat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_postCat_post` FOREIGN KEY (`post_id`) REFERENCES `tbl_post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
