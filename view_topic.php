<?php 

$PageTitle = "TalkHub - View Topic";
session_start();

require_once "db.php";
require_once "functions.php";
require_once "config.php";


$post = $post_err = "";

if($_SERVER["REQUEST_METHOD"] == "GET"){

	$topic_id = $_GET["tid"];
	if (isTopicExist($connect, $topic_id)) {
		$topic_title = getTopicTitle($connect, $topic_id);
		
		addViewCount($connect, $topic_id);
		
		$posts = array();
		getPosts($connect, $topic_id, $posts);
		
		$PageTitle = "TalkHub - Topic: ".$topic_title;
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
<?php include_once('alerts.php'); ?>

<div class="post_wrapper">
	<div class="my-3 p-3 bg-white rounded box-shadow">
	    <h3 class="border-bottom border-gray pb-2 mb-0">Topic: <?= $topic_title ?></h3>
		<?php if (count($posts) > 0): ?>
		  	<?php foreach ($posts as $post): ?>	
			    <div class="media text-muted pt-3 border-bottom  border-gray">
			      <img src="img/avatars/<?= htmlspecialchars($post["avatar"]) ?>.png" alt="" class="mr-2 rounded" width="48" height="48">
			      <p class="media-body pb-3 mb-0 medium lh-125">
					<a href="view_profile.php?uid=<?= htmlspecialchars($post["user_id"]) ?>">
						<strong class="text-primary"><?= $post["username"] ?></strong>
					</a>
					<br>
			        <?= nl2br($post["post"]) ?>
			      </p>
				  <div class="pull-right">
				  	<small class="pull-right">
				  		Posted on <?= date("M-d-Y H:i:s",strtotime($post["timestamp"])); ?>
				  	</small>
				  </div>
				</div>
			<?php endforeach ?>
		<?php else: ?>
		    <div class="media text-muted pt-3">
		      <p class="media-body pb-3 mb-0 medium lh-125 border-bottom border-gray">
		        No posts found.
		      </p>
			</div>
		<?php endif ?>
	
		<div style="	width: 60%; padding: 10px; margin-left:auto; margin-right:auto;">
	        <form action="create_post.php" method="post">
	            <div class="form-group">
					<textarea class="form-control <?php echo (!empty($post_err)) ? 'is-invalid' : ''; ?>" name="post" rows="5" cols="80"></textarea>
					<span class="invalid-feedback"><?php echo $post_err; ?></span>
	            </div>
				
			    <div class="form-group d-flex justify-content-center my-3">
					<input type="hidden" name="tid" value="<?= htmlspecialchars($_GET["tid"]) ?>">
			        <input type="submit" class="btn btn-primary btn-sm" value="Post">
			        <a class="btn btn-link btn-sm ml-2" href="javascript:history.back()">Back</a>
			    </div>
	        </form>
		</div>
	</div>
</div>

<?php include_once('footer.php'); ?>