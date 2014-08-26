<?php include('globals.php'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title></title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href='<?php echo ($isAdmin ? "../" : "") ?>libs/bootstrap/css/bootstrap.min.css' />
		<link rel="stylesheet" href="<?php echo ($isAdmin ? "../" : "") ?>libs/flatly.css" />
		<script src="<?php echo ($isAdmin ? "../" : "") ?>libs/jquery-1.11.1.min.js"></script>
		<script src="<?php echo ($isAdmin ? "../" : "") ?>libs/bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo ($isAdmin ? "../" : "") ?>libs/xdate.js"></script>
		<script src="/libs/modernizr.js"></script>
		<script src="/libs/jquery.cookie.js"></script>
		<script src="<?php echo ($fayeBase . "/client.js") ?>"></script>
		<script>
			//window.onerror = function(){ return true; }
		</script>
	</head>
<body>
	<div class="navbar navbar-default">
	  <div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="#">
			<img src="/img/title.png" alt="title" border="0" width="250" />
		</a>
	  </div>
	  <div class="navbar-collapse collapse navbar-responsive-collapse">
		<ul class="nav navbar-nav">
		  <li class="active" style="display:none;"><a href="#">Active</a></li>
		  <li><a href="<?php echo (!$isAdmin ? "../" : "") ?>index.php">Home</a></li>
		  <li class="dropdown" style="display:none;">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
			<ul class="dropdown-menu">
			  <li><a href="#">Action</a></li>
			  <li><a href="#">Another action</a></li>
			  <li><a href="#">Something else here</a></li>
			  <li class="divider"></li>
			  <li class="dropdown-header">Dropdown header</li>
			  <li><a href="#">Separated link</a></li>
			  <li><a href="#">One more separated link</a></li>
			</ul>
		  </li>
		</ul>
		<form class="navbar-form navbar-left" style="display:none;">
		  <input type="text" class="form-control col-lg-8" placeholder="Search">
		</form>
		<ul class="nav navbar-nav navbar-right" style="display:none;">
		  <li><a href="#">Link</a></li>
		  <li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
			<ul class="dropdown-menu">
			  <li><a href="#">Action</a></li>
			  <li><a href="#">Another action</a></li>
			  <li><a href="#">Something else here</a></li>
			  <li class="divider"></li>
			  <li><a href="#">Separated link</a></li>
			</ul>
		  </li>
		</ul>
	  </div>
	</div>
	<style>
		div.navbar{ margin-bottom: 5px; }
		.navbar-default .navbar-toggle span.icon-bar{ background-color: #ff4b72; }
	</style>
	
	<div id="msgError" class="alert alert-dismissable alert-danger">
	  <button type="button" class="close" data-dismiss="alert">x</button>
	  <h4></h4><p></p>
	</div>
	<div id="msgSuccess" class="alert alert-dismissable alert-success">
	  <button type="button" class="close" data-dismiss="alert">x</button>
	  <h4></h4><p></p>
	</div>
	<div id="msgInfo" class="alert alert-dismissable alert-info">
	  <button type="button" class="close" data-dismiss="alert">x</button>
	  <h4></h4><p></p>
	</div>
	<script>
		function setErrorMessage(msg, title){ showMessage($("#msgError"), msg, title); }
		function setSuccessMessage(msg, title) { showMessage($("#msgSuccess"), msg, title); }
		function setInfoMessage(msg, title) { showMessage($("#msgInfo"), msg, title); }
		function showMessage(ctrl, msg, title){
			hideMessages();
			if(title && title.length > 0) { ctrl.find("h4").html(title).show(); }
			ctrl.find("p").html(msg);
			ctrl.show();
		}
		function hideMessages(){
			$("#msgError").hide();
			$("#msgSuccess").hide();
			$("#msgInfo").hide();
		}
	</script>
	<style>
		#msgError, #msgSuccess, #msgInfo {display: none; margin: 0 14px;}
		.alert h4{display:none;}
	</style>
	
<script>
	$(function(){
		isDoneSet = '<?php if(isset($_GET['done'])){ echo $_GET['done']; } ?>';
		isDoneSet = isDoneSet == '' ? false : true;
		cookieShowId = $.cookie('<?php echo $Cookie_ShowId; ?>');
		cookieShowUserId = $.cookie('NEAshowUserId');
		if('<?php echo $isAdmin; ?>' != '1' && !isDoneSet){
			
		}
	});
</script>