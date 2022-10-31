<?php 

$PageTitle = "TalkHub - View Profile";
// Initialize the session
session_start();

// Include config file
require_once "db.php";
require_once "functions.php";
require_once "config.php";


if($_SERVER["REQUEST_METHOD"] == "GET"){

	$user_id = $_GET["uid"];
	if (isUserExist($connect, $user_id)) {
		$profile = array();
		getUserProfile($connect, $user_id, $profile);
		$PageTitle = "TalkHub - Profile: ".$profile['username'];
	}
	else {
		// Topic not found
		header("location: index.php");
    	exit;
	}
	
	mysqli_close($connect);
}

?>

<?php include_once('header.php'); ?>

<div class="wrapper">

	<div class="my-3 p-3 bg-white rounded box-shadow">
		<h3 class="border-bottom border-gray pb-2 mb-0">User Profile: <?= htmlspecialchars($profile['username']) ?></h3>           
		<div class="d-flex flex-column align-items-center text-center pt-3">
	        <img src="img/avatars/<?= htmlspecialchars($profile["avatar"]) ?>.png" alt="Admin" class="rounded-circle" width="150">
	        <div class="mt-3">
				<center><h4><?= htmlspecialchars($profile['username']) ?></h4></center>
				<p class="text-secondary mb-1"><b>Total Posts:</b> <?= htmlspecialchars($profile["post_count"]) ?></p>
				<p class="text-secondary font-size-sm"><b>Joined:</b> <?= date("M-d-Y",strtotime($profile["registration"])); ?></p>
				<p class="text-secondary font-size-sm"><b>Last Online:</b> <?= $profile["last_login"] != null ? date("M-d-Y",strtotime($profile["last_login"])) : "N/A"; ?></p>
	          <button class="btn btn-primary btn-sm disabled">Follow</button>
	          <button class="btn btn-outline-primary btn-sm disabled">Message</button>
			  <a class="btn btn-link btn-sm" href="javascript:history.back()">Back</a>
			  <div>
			  
			  </div>
	        </div>
		</div>
	</div>
</div