<?php 
	require_once "core/Online.php";
	$Online = new Online();
	$players = $Online->getUsersOnline();
?>
<!DOCTYPE html>
<html>
<head>
	<title>ChangeMeWebsiteTitle</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="core/main.css">
	<script>
		function register()
		{
			$.post("core/Register.php",
				{
					signup_button: document.getElementsByName("signup_button")[0].value,
					signup_username: document.getElementsByName("signup_username")[0].value,
					signup_email: document.getElementsByName("signup_email")[0].value,
					signup_password: document.getElementsByName("signup_password")[0].value,
					signup_password_repeat: document.getElementsByName("signup_password_repeat")[0].value
				},
				function (data)
				{
					$("#info").html(data);
				}
			);
		}
	</script>
	<style type="text/css">
		.horde, .alliance {float: left;}

		.total, .horde, .alliance { text-align: center; }

	    .horde { background-color: red; width: <?php echo round($Online->horde_width); ?>%; }

	    .alliance {	background-color: royalblue; width: <?php echo round($Online->alliance_width); ?>%;}

	    .total { border: 1px solid black; }

	    .total_title { float: left; }
	</style>
</head>
<body>



<div class="container">
	<div class="col-sm-6 signup_form">
		
		<div class="jumbotron">
			<h3 class="text-center">Signup</h3>
			<form method="POST" accept-charset="utf-8">
				<div class="form-group">
					<label>Userame</label>
					<input type="text" class="form-control" name="signup_username" placeholder="Username for the account" required>
				</div>
				<div class="form-group">
					<label>Email</label>
					<input type="text" class="form-control" name="signup_email" placeholder="Email for the account" required>
				</div>
				<div class="form-group">
					<label>Password</label>
					<input type="password" class="form-control" name="signup_password" placeholder="Password" required>
				</div>
				<div class="form-group">
					<label>Repeat Password</label>
					<input type="password" class="form-control" name="signup_password_repeat" placeholder="Repeat Password" required>
				</div>

				<div id="info"></div>

				<div class="form-group">
					<input type="button" name="signup_button"  class="btn btn-primary btn-block" onclick="register()" value="Signup">
				</div>
			</form>
		</div>
	</div>

	<div class="col-sm-6 status">
		<div class="jumbotron">
			<h3 class="text-center">Players Online</h3>
<div class="horde"><?php if ( $players['horde'] > 0 || ($players['horde'] == 0 && $players['alliance'] == 0)) echo "Horde: ".$players['horde']; ?></div>
				<div class="alliance"><?php if ( $players['alliance'] > 0 || ($players['alliance'] == 0 && $players['horde'] == 0)) echo "alliance: ".$players['alliance']; ?></div>
	</div>
		<div class="jumbotron">
			<h3 class="text-center">News</h3>
			<div>
				<h4>Title</h4>
				<p>text</p>
				<h4>set realmlist putyourserverrealmlisthere </h4>
			</div>
		</div>
	
	</div>

</body>
</html>