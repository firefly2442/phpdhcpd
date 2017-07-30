<?php
require_once("config.php");
require_once("parser.class.php");

//Check login session
session_start();

if (!$_SESSION['logged_in'])
{
	//check fails
	header("Location: login.php?status=session");
	exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<title>phpdhcpd</title>
	<script type="text/javascript">
		function sortby (to, p) {
			var myForm = document.createElement("form");
			myForm.method = "post";
			myForm.action = to;
			for (var k in p) {
				var myInput = document.createElement("input");
				myInput.setAttribute("name", k);
				myInput.setAttribute("value", p[k]);
				myForm.appendChild(myInput);
			}
			document.body.appendChild(myForm);
			myForm.submit() ;
			document.body.removeChild(myForm);
		}
	</script>
</head>
<body>
<div class="center">
<h2>Current DHCP IP Addresses (Leases)</h2>
</div>

<?php
//read leases file
if (isset($_POST['searchfilter'])) {
	$searchfilter = $_POST['searchfilter'];
} else {
	$searchfilter = "";
}
if (isset($_POST['sort_column'])) {
	$sort_column = $_POST['sort_column'];
} else {
	$sort_column = 0;
}
if (isset($_POST['onlyactiveleases'])) {
	$onlyactiveleases = true;
} else {
	$onlyactiveleases = false;
}

if (file_exists($dhcpd_leases_file) && is_readable($dhcpd_leases_file))
{
	$open_file = fopen($dhcpd_leases_file, "r") or die("Unable to open DHCP leases file.");
	if ($open_file)
	{
		if ($searchfilter != "") {
			$searchfiledmsg = $searchfilter;
		} else {
			$searchfiledmsg = "Type to search";
		}

		//Call the dhcplease file parser
		$parser = new ParseClass();
		$parser->parser($open_file);

		?>

		<form action = "index.php"  accept-charset="UTF-8" method="post" id="search-form">
		<input type="checkbox" name="onlyactiveleases" value="true" 
		<?php
		if ($onlyactiveleases)
			echo "checked='true'";
		?>
		/> Only display active leases<br>
		Search Filter:
		<input type="text" maxlength="255" name="searchfilter" id="searchfilter" size="20" placeholder="Enter Search" 
		<?php
			echo " value = '" . $searchfilter . "'>";
		?>
		<input type="submit" name="Search" value="Search">

		<?php
		if ($searchfilter != "")
			echo "<a href='index.php'>Clear Search</a>\n";
		?>
		
		</form>
		<br><br>

		<table>
		<tr class="table_title">
		<td style="width:14%;"><b>
			<a href="javascript:sortby('index.php',{sort_column:'-1',searchfilter:'<?php echo $searchfilter;?>'})">
				<img src="images/arrow_down.png" alt="Sort Descending" title="Sort Descending">
			</a>
			IP Address
			<a href="javascript:sortby('index.php',{sort_column:'1',searchfilter:'<?php echo $searchfilter;?>'})">
				<img src="images/arrow_up.png" alt="Sort Ascending" title="Sort Ascending">
			</a>
		</b></td>

		<td style="width:14%;"><b>
			<a href="javascript:sortby('index.php',{sort_column:'-2',searchfilter:'<?php echo $searchfilter;?>'})">
				<img src="images/arrow_down.png" alt="Sort Descending" title="Sort Descending">
			</a>
			Start Time
			<a href="javascript:sortby('index.php',{sort_column:'2',searchfilter:'<?php echo $searchfilter;?>'})">
				<img src="images/arrow_up.png" alt="Sort Ascending" title="Sort Ascending">
			</a>
		</b></td>

		<td style="width:14%;"><b>
			<a href="javascript:sortby('index.php',{sort_column:'-3',searchfilter:'<?php echo $searchfilter;?>'})">
				<img src="images/arrow_down.png" alt="Sort Descending" title="Sort Descending">
			</a>
			End Time
			<a href="javascript:sortby('index.php',{sort_column:'3',searchfilter:'<?php echo $searchfilter;?>'})">
				<img src="images/arrow_up.png" alt="Sort Ascending" title="Sort Ascending">
			</a>
		</b></td>

		<td style="width:14%;"><b>
			<a href="javascript:sortby('index.php',{sort_column:'-4',searchfilter:'<?php echo $searchfilter;?>'})">
				<img src="images/arrow_down.png" alt="Sort Descending" title="Sort Descending">
			</a>
			Lease Expires
			<a href="javascript:sortby('index.php',{sort_column:'4',searchfilter:'<?php echo $searchfilter;?>'})">
				<img src="images/arrow_up.png" alt="Sort Ascending" title="Sort Ascending">
			</a>
		</b></td>

		<td style="width:14%;"><b>
			<a href="javascript:sortby('index.php',{sort_column:'-5',searchfilter:'<?php echo $searchfilter;?>'})">
				<img src="images/arrow_down.png" alt="Sort Descending" title="Sort Descending">
			</a>
			MAC Address
			<a href="javascript:sortby('index.php',{sort_column:'5',searchfilter:'<?php echo $searchfilter;?>'})">
				<img src="images/arrow_up.png" alt="Sort Ascending" title="Sort Ascending">
			</a>
		</b></td>
		<td style="width:14%;"><b>
			<a href="javascript:sortby('index.php',{sort_column:'-6',searchfilter:'<?php echo $searchfilter;?>'})">
				<img src="images/arrow_down.png" alt="Sort Descending" title="Sort Descending">
			</a>
			Client Identifier
			<a href="javascript:sortby('index.php',{sort_column:'6',searchfilter:'<?php echo $searchfilter;?>'})">
				<img src="images/arrow_up.png" alt="Sort Ascending" title="Sort Ascending">
			</a>
		</b></td>
		<td style="width:14%;"><b>
			<a href="javascript:sortby('index.php',{sort_column:'-7',searchfilter:'<?php echo $searchfilter;?>'})">
				<img src="images/arrow_down.png" alt="Sort Descending" title="Sort Descending">
			</a>
			Hostname
			<a href="javascript:sortby('index.php',{sort_column:'7',searchfilter:'<?php echo $searchfilter;?>'})">
				<img src="images/arrow_up.png" alt="Sort Ascending" title="Sort Ascending">
			</a>
		</b></td>

		</tr>
		<?php
		//Display the dhcp lease table using the filter and ordered
		$parser->print_table($searchfilter, $sort_column, $onlyactiveleases);
		fclose($open_file);
		?>
		</table>
		<?php
		echo "<p>Total number of entries in DHCP lease table: " . count($parser->dhcptable) . "</p>\n";
		echo "<p>Number of entries displayed on this page: " . $parser->filtered_number_display . "</p>\n";
	}
}
else
{
	echo "<p class='error'>The DHCP leases file does not exist or does not have sufficient read privileges.</p>";
}

//display message if the cache file isn't writeable
if ($cache_vendor_results && !is_writeable("./nmap-mac-prefixes_cache")) {
	echo "<p class='error'>The nmap-mac-prefixes_cache file doesn't have sufficient write privileges.</p>";
}
?>

<br><hr>
<div class="center">
<a href="https://github.com/firefly2442/phpdhcpd/">phpdhcpd</a>
<br>Version: <?php echo $version;?>
</div>
</body>
</html>

