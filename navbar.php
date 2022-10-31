<nav class="navbar navbar-fixed-top navbar navbar-light bg-clear" style="padding: 0px 10% 0px 10%;" al>
	<div class="navbar-header pull-left">
	  <div class="d-flex align-items-center p-1 my-2 text-black-50 rounded box-shadow">
	    <img class="mr-3" src="img/avatars/<?= htmlspecialchars($_SESSION["avatar"]) ?>.png" alt="" width="48" height="48">
	    <div class="lh-100">
			<small>Logged in as:</small>
			<h6 class="mb-0 text-black lh-100"><?php echo htmlspecialchars($_SESSION["username"]); ?></h6>
		</div>
	  </div>
	</div>
    <div class="navbar-header pull-right my-2">
		<a href="index.php" class="btn btn-primary navbar-btn btn-xs">Home</a>
		<a href="create_topic.php" class="btn btn-primary navbar-btn btn-xs">Create Topic</a>
		<a href="account.php" class="btn btn-primary navbar-btn btn-xs">Account Settings</a>
		<a href="logout.php" class="btn btn-danger navbar-btn btn-xs">Log Out</a>
	</div>
</nav>