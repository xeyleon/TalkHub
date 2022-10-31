<?php
$PageTitle = "TalkHub - Create Post";
session_start();

require_once("db.php");
require_once("functions.php");
require_once("config.php");


$post = $post_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST"){

	$topic_id = $_POST["tid"];
	
    // Validate post
    if(empty(trim($_POST["post"]))){
        $post_err = "Please enter post.";     
    }
    elseif (strlen(trim($_POST["post"])) < MIN_POST_LEN){
        $post_err = "Your post must contain atleast ".MIN_POST_LEN." characters.";
    }
    elseif (strlen(trim($_POST["post"])) > MAX_POST_LEN){
        $post_err = "Your post cannot contain more than ".MAX_POST_LEN." characters.";
    }
    else {
        $post = $_POST["post"];
    }
    
    if (empty($post_err)){
		if (isTopicExist($connect, $topic_id)) {
			$user_id = $_SESSION["user_id"];
			$content = $_POST["post"];
			createPost($connect, $topic_id, $user_id, $content);
			$_SESSION['alerts']['success'] = "Your message has been posted.";
			header("location: view_topic.php?tid=".$topic_id);
		}
		else {
			// Topic not found
			header("location: index.php");
	    	exit;
		}
	}
	else {
		$_SESSION['alerts']['warning'] = $post_err;
		header("location: view_topic.php?tid=".$topic_id);
	}
}
else {
	header("location: index.php");
	exit;
}

mysqli_close($connect);

?>