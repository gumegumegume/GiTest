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
<div id="register-block">
<img src = "css/images/icon.png" class ="icon">
<h2>Register</h2>
	<form method="POST" action="processes/process.user.php?action=new">

	<input type="text" id="fname" class="input" name="firstname" placeholder="Enter First Name" required>

	<input type="text" id="lname" class="input" name="lastname" placeholder="Enter Last Name" required>

    <select id="access" name="access">
              <option value="Applicant">Applicant</option>
              <option value="Employer">Employer</option>
            </select>

    <input type="email" id="email" class="input" name="email" placeholder="Enter Email" required>
	
	<input type="password" id="password" class="input" name="password" placeholder="Enter Password" minlength="8" required>

	<input type="password" id="confirmpassword" class="input" name="confirmpassword" placeholder="Confirm password" minlength="8" required>
	


		<input type="submit" value="Register"/>

	<p class = "small">Already have an accout yet? <a class ="anchor-button" href="login.php">Login now</a></p>
	</form>
</div>
<div id="brand-block">
</div>
</body>
</html>