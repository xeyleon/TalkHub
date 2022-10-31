<?php
$PageTitle = "TalkHub - Create Topic";
session_start();

require_once("db.php");
require_once("functions.php");
require_once("config.php");

$title = $post = $creator = "";
$title_err = $post_err = "";
 
// Process form data
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate topic title
    if(empty(trim($_POST["title"]))){
        $title_err = "Please enter a topic title.";
    }
    elseif (strlen(trim($_POST["title"])) < MIN_TOPIC_TITLE){
        $title_err = "Your topic title must contain atleast ".MIN_TOPIC_TITLE." characters.";
    }
    elseif (strlen(trim($_POST["title"])) > MAX_TOPIC_TITLE){
        $title_err = "Your topic title cannot contain more than ".MAX_TOPIC_TITLE." characters.";
    }
    else {
        $title = trim($_POST["title"]);
    }
    
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

    // Check input errors before inserting in database
    if(empty($title_err) && empty($post_err)){
		
		$user_id = $_SESSION["user_id"];
		if ($topic_id = createTopic($connect, $title, $user_id)){
			createPost($connect, $topic_id, $user_id, $post);
			$_SESSION['alerts']['success'] = "Your topic <b>".$title."</b> was successfully created.";
			header('location: index.php');
		}
		else {
			echo "There was error creating the topic.";
		}
	}

    mysqli_close($connect);
}
?>
 
<?php include_once('header.php'); ?>
<?php 
if($_SERVER["REQUEST_METHOD"] != "POST"){
	include_once('alerts.php');
}
?>
    <div class="wrapper">
		<div class="my-3 p-3 bg-white rounded box-shadow">
	        <h3>Create Topic</h3>
	        <p>Fill the form below to create a topic.</p>
	        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	            <div class="form-group">
	                <label style="font-weight: bold;">Topic Title</label>
	                <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
	                <span class="invalid-feedback"><?php echo $title_err; ?></span>
	            </div>    
	            <div class="form-group">
	                <label style="font-weight: bold;">Topic Post</label>
					<textarea class="form-control <?php echo (!empty($post_err)) ? 'is-invalid' : ''; ?>" name="post" rows="5" cols="80"></textarea>
					<span class="invalid-feedback"><?php echo $post_err; ?></span>
	            </div>
				
	            <div class="form-group d-flex justify-content-center">
	                <input type="submit" class="btn btn-primary btn-sm" value="Create Topic">
	                <input type="reset" class="btn btn-secondary btn-sm ml-2" value="Reset">
					<a class="btn btn-link btn-sm ml-2" href="index.php">Back</a>
	            </div>
	        </form>
		</div>
    </div>

<?php include_once('footer.php'); ?>