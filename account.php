<?php
$PageTitle = "TalkHub - Account Settings";
session_start();
 
// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "db.php";
require_once "functions.php";
require_once "config.php";

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
$avatars = array();

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(!empty(trim($_POST["new_password"]))){
        if(strlen(trim($_POST["new_password"])) < PASSWORD_MIN_LEN){
        	$new_password_err = "Password must have atleast ".PASSWORD_MIN_LEN." characters.";
    	}
    	else {
        	$new_password = trim($_POST["new_password"]);
        	
        	 // Validate confirm password
		    if(empty(trim($_POST["confirm_password"]))){
		        $confirm_password_err = "Please confirm the password.";
		    }
		    else {
		        $confirm_password = trim($_POST["confirm_password"]);
		        if(empty($new_password_err) && ($new_password != $confirm_password)){
		            $confirm_password_err = "Password did not match.";
		        }
		    }
        }
        
	    // Check input errors before updating the database
	    if(empty($new_password_err) && empty($confirm_password_err)){
	    	$user_id = $_SESSION["user_id"];
	    	if (setAccountPassword($connect, $user_id, $new_password)){
			    $_SESSION['alerts']['success'] = "Your changes have been saved.";
	    	}
	    	else {
	    		$_SESSION['alerts']['danger'] = "Your changes have not been saved.";
	    	}
	    }
    }

    if ($_SESSION["avatar"] != $_POST["avatar"]){
    	setAvatar($connect, $_SESSION["user_id"], $_POST["avatar"]);
    	$_SESSION["avatar"] = $_POST["avatar"];
    	$_SESSION['alerts']['success'] = "Your changes have been saved.";
    }
    
}

$avatar_dir = 'img/avatars/';
$iterator = new FilesystemIterator($avatar_dir, FilesystemIterator::SKIP_DOTS);
$avatars = iterator_count($iterator);

mysqli_close($connect);

?>
 
<?php include_once('header.php'); ?>
<?php include_once('alerts.php'); ?>

    <div class="wrapper">
		<div class="my-3 p-3 bg-white rounded box-shadow">
	        <h3>Account Settings</h3>
	        <p>Make changes to your account.</p>
	        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				
				
				<div class="form-group">
					<label style="font-weight: bold;">Avatar</label>
					<fieldset>
						<?php for($i = 0; $i < $avatars; $i++):?>
							<input type="radio" name="avatar" class="sr-only" id="avatar_<?= $i ?>" value="<?= $i ?>" <?php echo($i == $_SESSION["avatar"] ? 'checked': '') ?>>
							<label for="avatar_<?= $i ?>">
								<img src="/img/avatars/<?= $i ?>.png" width="48" height="48" alt="">
							</label>
						<?php endfor ?>
					</fieldset>
				</div>
	            <div class="form-group">
	                <label style="font-weight: bold;">Username</label>
					<input class="form-control" type="text" placeholder="<?php echo htmlspecialchars($_SESSION["username"]); ?>" readonly>
	            </div>
	            <div class="form-group">
	                <label style="font-weight: bold;">New Password</label>
	                <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
	                <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
	            </div>
	            <div class="form-group">
	                <label style="font-weight: bold;">Confirm Password</label>
	                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
	                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
	            </div>
				
			    <div class="form-group d-flex justify-content-center">
			        <input type="submit" class="btn btn-primary btn-sm" value="Save Changes">
			        <a class="btn btn-link btn-sm ml-2" href="index.php">Back</a>
			    </div>
	        </form>
		</div>
    </div>    

<?php include_once('footer.php'); ?>