<?php include('header.php'); ?>
<?php if(!isset($_GET['sqid'])){ die('Missing id'); } ?>

<div class="outerContainer">
	<h3>Results</h3>
	<p>asdf</p>
	
	<a href="home.php?lastSQID=<?php echo $_GET['sqid']; ?>" class="btn btn-lg btn-block btn-info" type="button">Proceed</a>
</div>

<style>
	.outerContainer{ margin: 0 14px; }
</style>

<?php include('footer.php'); ?>