<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>phpdhcpd</title>
	<script language="Javascript">
	function xmlhttpPost(str) {
	    var xmlHttpReq = false;
	    var self = this;
	    
	    if (window.XMLHttpRequest) // Firefox/Safari
	        self.xmlHttpReq = new XMLHttpRequest();
	    else if (window.ActiveXObject) // IE
	        self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
	    self.xmlHttpReq.open('POST', str, true);
	    self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	    self.xmlHttpReq.onreadystatechange = function() {
	        if (self.xmlHttpReq.readyState == 4)
	            updatepage(self.xmlHttpReq.responseText);
	    }
	    self.xmlHttpReq.send(getquerystring());
	}

	function getquerystring() {
	    var form = document.forms['login'];
	    var word = form.password.value;
	    qstr = 'password=' + escape(word);
	    return qstr;
	}

	function updatepage(str)
	{
	    document.getElementById("result").innerHTML = str;
	}
	</script>

</head>
<body>
<center>
<h2>Current DHCP IP Addresses (Leases)</h2>
</center>

<?php
require_once("config.php");


//check for password authentication
if ($password != "")
{
	//check password
	?>
	<center>
	<div id="result">
	<form name="login">
	<p>Password: <input name="password" type="password">  
	<input value="Submit" type="button" onclick='JavaScript:xmlhttpPost("login.php")'></p>
	</form>
	</div>
	</center>
	<?php
}
else
{
	require("login.php");
}

?>

<br><hr>
<center><a href="http://www.rivetcode.com">phpdhcpd</a>
<br>Version: 0.1</center>
</body>
</html>
