<?php
include_once 'config/config.php';
include_once 'classes/class.user.php';
$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : '';
$user = new User();
if($user->get_session()){
	header("location: index.php");
}
if(isset($_REQUEST['submit'])){
	extract($_REQUEST);
	$login = $user->check_login($useremail,$password);
	if($login){
		header("location: index.php");
	}else{
		?>
        <div id='error_notif'>Wrong email or password.</div>
        <?php
	}
	
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Application Name</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Assistant&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/custom.css?<?php echo time();?>">
</head>
<body>
<div id="brand-block">
</div>
<div id="login-block">
<img src = "css/images/icon.png" class = "icon">
<h2>Login</h2>
	<form method="POST" action="" name="login">
	<div>
		<input type="email" class="input" required name="useremail" placeholder="Enter Your Email here"/>
	</div>
	<div>
		<input type="password" class="input" required name="password" placeholder="Enter your Password here"/>
	</div>
	<div>
		<input type="submit" name="submit" value="Login"/>
	</div>
	<p class = "small">Don't have an accout yet? <a class ="anchor-button" href="register.php">Register now</a></p>
	</form>
</div>
<div id="brand-block">
</div>
</body>
</html>