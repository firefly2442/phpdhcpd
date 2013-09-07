<?php
require_once("config.php");

//check password
if ((isset($_POST['password']) && $_POST['password'] == $password) || $password == "")
{
	//successful login
	session_start();
	$_SESSION['logged_in'] = true;
	header("Location: index.php");
	exit();
}
else if (isset($_POST['password']))
{
	//password was incorrect at this point!
	$error = true;
}

//Destroy any previous session data
//This way it requires a login
session_start();
session_destroy();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<title>phpdhcpd</title>
</head>
<body>
<center>
<h2>Login</h2>
</center>

	<?php
	if (isset($error) && $error == true)
		echo "<p class='error'>Error, password is incorrect.<br>Entries are cAsESEnsITiVE, do you have your capslock key on?...</p>";
	if (isset($_GET['status']) && $_GET['status'] == "session")
		echo "<p class='error'>Your session has timed out, please re-login.</p>";
	?>
	<center>
	<form action="login.php" method="POST" name="login">
	<p>Password: <input name="password" type="password">  
	<input type="submit" name="Log In" value="Log In">
	</form>
	</center>

<br><hr>
<center><a href="https://github.com/firefly2442/phpdhcpd/">phpdhcpd</a>
<br>Version: <?php echo $version;?></center>
</body>
</html>
