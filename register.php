<?php
$PageTitle = "TalkHub - Register";
session_start();

require_once("db.php");
require_once("functions.php");
require_once("config.php");

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    }
    elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } 
    elseif (strlen(trim($_POST["username"])) > MAX_USERNAME_LEN){
        $username_err = "Username cannot be longer than ".MAX_USERNAME_LEN." characters.";
    } 
    else {
    	if (isUserNameExist($connect, trim($_POST["username"]))){
    		$username_err = "This username is already taken.";
    	}
    	else {
    		$username = trim($_POST["username"]);
    	}
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < PASSWORD_MIN_LEN){
        $password_err = "Password must have atleast ".PASSWORD_MIN_LEN." characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        if (registerAccount($connect, $username, $password)){
        	$_SESSION['alerts']['success'] = "Your account was successfully registered.";
	        header("location: login.php");
        }
        else {
        	$_SESSION['alerts']['danger'] = "Your account was not successfully registered.";
        }
    }
    
    mysqli_close($connect);
}
?>
 
<?php include_once('header.php'); ?>

    <div class="wrapper">
		<div class="my-3 p-3 bg-white rounded box-shadow">
	        <h3>Account Registration</h3>
	        <p>Fill the form below to register an account.</p>
	        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	            <div class="form-group">
	                <label style="font-weight: bold;">Username</label>
	                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
	                <span class="invalid-feedback"><?php echo $username_err; ?></span>
	            </div>    
	            <div class="form-group">
	                <label style="font-weight: bold;">Password</label>
	                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
	                <span class="invalid-feedback"><?php echo $password_err; ?></span>
	            </div>
	            <div class="form-group">
	                <label style="font-weight: bold;">Confirm Password</label>
	                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
	                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
	            </div>
				<center>
		            <div class="form-group">
		                <input type="submit" class="btn btn-primary btn-sm" value="Submit">
		                <input type="reset" class="btn btn-secondary btn-sm ml-2" value="Reset">
		            </div>
		            <p>Already have an account? <a href="login.php">Login here</a>.</p>
				</center>
	        </form>
		</div>
    </div>

<?php include_once('footer.php'); ?>