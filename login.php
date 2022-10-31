<?php
$PageTitle = "TalkHub - Login";
session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
require_once "db.php";
require_once "functions.php";
 
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
if ($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if (empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    }
    else {
        $username = trim($_POST["username"]);
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    }
    else {
        $password = trim($_POST["password"]);
    }
    
    if(empty($username_err) && empty($password_err)){
    
    	if (loginAccount($connect, $username, $password)){
		    $_SESSION['alerts']['success'] = "You are now logged in.";
		    header("location: index.php");
    	}
    	else {
    		$_SESSION['alerts']['danger'] = "Invalid username or password.";
    		header("location: login.php");
    	}
    }

	mysqli_close($connect);
}
?>
 
<?php include_once('header.php'); ?>
<?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true): ?>
	<?php
	 	if ($_SERVER["REQUEST_METHOD"] != "POST"){
			include_once('alerts.php'); 
		}
	?>
<?php endif ?>

<div class="wrapper">
	<div class="my-3 p-3 bg-white rounded box-shadow">
        <h3>Login</h3>
        <p>Please fill in your credentials to login.</p>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label style="font-weight: bold;">Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label style="font-weight: bold;">Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
			<center>
				<div class="form-group justify-content-center my-3" >
	                <input type="submit" class="btn btn-primary btn-sm" value="Login">
	            </div>
	            <p>Don't have an account? <a href="register.php">Register an account</a>.</p>
			</center>
        </form>
	</div>
</div>

<?php include_once('footer.php'); ?>