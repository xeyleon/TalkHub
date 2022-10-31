<script>
	// Fade out alerts
	document.addEventListener("DOMContentLoaded", () => {
		var i = 7;
		setTimeout(()=>{
			document.querySelectorAll(".alert").forEach(element =>
			{
				var k = setInterval(()=>{
				    if (i <= 0) {
				      clearInterval(k);
					  element.remove();
				    } else {
				      element.style.opacity = i / 10;
				      i--;
				    }		
				},100)
			});
		}, 1500);
	});
</script>

<?php if (isset($_SESSION['alerts'])): ?>
	<div class="col-md-10 col-md-offset-1">
	<?php foreach($_SESSION['alerts'] as $type => $alert): ?>
		<?php if ($type == 'success'): ?>
		    <div class="alert alert-success alert-custom" role="alert">
				<p class="t-0 p-0 y-0"><?= $alert; ?></p>
			</div>
		<?php endif ?>
		<?php if ($type == 'warning'): ?>
		    <div class="alert alert-warning alert-custom" role="alert">
				<p><?= $alert; ?></p>
			</div>
		<?php endif ?>
		<?php if ($type == 'danger'): ?>
		    <div class="alert alert-danger alert-custom" role="alert">
				<p><?= htmlspecialchars($alert); ?></p>
			</div>
		<?php endif ?>
	<?php endforeach ?>
	</div>
	<?php unset($_SESSION['alerts']); ?>
<?php endif ?>