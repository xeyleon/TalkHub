<?php

$PageTitle = "TalkHub";
session_start();

require_once "db.php";
require_once "functions.php";
require_once "config.php";

$topics = array();
getTopics($connect, $topics);

mysqli_close($connect);

?>

<?php include_once('header.php'); ?>

<script>
	function topicClick(element){
		window.location = element.getAttribute("data-href")
	}
</script>

<?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true): ?>

	<center>
		<p>A place to discuss about anything!</p>
		<p>
			<a href="login.php" class="btn btn-primary">Login</a>
			<a href="register.php" class="btn btn-primary">Register</a>
		</p>
	</center>
	
<?php else: ?>

	<?php include_once('alerts.php'); ?>
	
	<div class="container" style="width: 85%;">
		<div class="col-12 col-sm-12 col-md-12">
	      <div class="card rounded">
	        <div class="card-body">
	          <div class="media-list position-relative">
	            <div class="table-responsive rounded" tabindex="1" style="height: auto; overflow: hidden; outline: none;">
	              <table class="table table-hover table-bordered border-primary">
	                <thead class="thead thead-dark" style="opacity: 80%;">
	                  <tr>
	                    <th style="width:70%">Topic</th>
	                    <th style="width:15%">Started By</th>
	                    <th style="text-align:center;">Views</th>
						<th style="text-align:center;">Posts</th>
	                  </tr>
	                </thead>
	                <tbody>
				  	<?php if (count($topics) > 0): ?>
					  	<?php foreach ($topics as $topic): ?>
		                  <tr onclick="topicClick(this)" class='clickable-row' data-href='view_topic.php?tid=<?= $topic["topic_id"] ?>'>
		                    <td class="text-truncate" style="width:70%; vertical-align: middle;">
								<strong class="text-info"><?= $topic["title"] ?></strong>
							</td>
		                    <td class="text-truncate" style="width:15%; vertical-align: middle;">
								<strong class="text-primary"><?= $topic["username"] ?></strong>
								<div><small>on <?= date("M-d-Y",strtotime($topic["created_at"])); ?></small></div>
							</td>
		                    <td class="text-truncate" style="text-align:center; "><?= $topic["views"] ?>
								<div><small>Views</small></div>
							</td>
							<td class="text-truncate" style="text-align:center;"><?= $topic["posts"] ?>
								<div><small>Posts</small></div>
							</td>
		                  </tr>
						<?php endforeach ?>
					<?php else: ?>
						<td colspan="4">No topics exists.</td>
					<?php endif ?>
	                </tbody>
	              </table>
	            </div>
	          </div>
	        </div>
	      </div>
	    </div>
	</div>

<?php endif ?>

<?php include_once('footer.php'); ?>