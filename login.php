<?php
require_once("config.php");

if ((isset($_POST['password']) && $_POST['password'] == $password) || $password == "")
{
	//login successful or no login required
	//read leases file
	if (file_exists($dhcpd_leases_file) && is_readable($dhcpd_leases_file))
	{
		$open_file = fopen($dhcpd_leases_file, "r") or die("Unable to open DHCP leases file.");
		if ($open_file)
		{
			?>
			<table>
			<tr class="table_title">
			<td><b>IP Address</b></td>
			<td><b>Start Time</b></td>
			<td><b>End Time</b></td>
			<td><b>Lease Expires</b></td>
			<td><b>MAC Address</b></td>
			<td><b>Client Identifier</b></td>
			<td><b>Hostname</b></td>
			</tr>
			<?php
			$row_number = 1;
			$row_array = array();
			$row_array = initialize_array($row_array);
			while (!feof($open_file))
			{
				$read_line = fgets($open_file, 4096);
				if (substr($read_line, 0, 1) != "#") //check for comment (skip)
				{
					$tok = strtok($read_line, " ");
					if ($tok == "lease")
					{
						$css_num = $row_number % 2;
						$row_number++;
						$row_array[0] = "<tr class='row".$css_num."'><td>" . strtok(" ") . "</td>\n";
					}
					else if ($tok == "starts")
					{
						$row_array[1] = "<td>" . intToDay(strtok(" "));
						$row_array[1] = $row_array[1]." - " . strtok(" ") . " ";
						$time = strtok(" ");
						$time = str_replace(";", "", $time);
						$row_array[1] = $row_array[1].$time . "</td>\n";
					}
					else if ($tok == "ends")
					{
						$row_array[2] = "<td>" . intToDay(strtok(" "));
						$row_array[2] = $row_array[2]." - " . strtok(" ") . " ";
						$time = strtok(" ");
						$time = str_replace(";", "", $time);
						$row_array[2] = $row_array[2].$time . "</td>\n";
					}	
					else if ($tok == "tstp")
					{
						$row_array[3] = "<td>" . intToDay(strtok(" "));
						$row_array[3] = $row_array[3]." - " . strtok(" ") . " ";
						$time = strtok(" ");
						$time = str_replace(";", "", $time);
						$row_array[3] = $row_array[3].$time . "</td>\n";
					}
					else if ($tok == "hardware")
					{
						$row_array[4] = "<td>" . strtok(" ") . " - ";
						$MAC = strtok(" ");
						$MAC = strtoupper(str_replace(";", "", $MAC));
						$row_array[4] = $row_array[4].$MAC . "</td>\n";
					}
					else if ($tok == "uid")
					{
						$uid = strtok(" ");
						$replace = array(".", "\"", ";");
						$uid = str_replace($replace, "", $uid);
						$row_array[5] = "<td>" . $uid . "</td>\n";
					}
					else if ($tok == "client-hostname")
					{
						$hostname = strtok(" ");
						$replace = array("\"", ";");
						$hostname = str_replace($replace, "", $hostname);
						$row_array[6] = "<td>" . $hostname . "</td>\n";
					}
					else if ($tok == "}\n")
					{
						$row_array[6] = $row_array[6]."</tr>";
						print_array($row_array);
						$row_array = initialize_array($row_array);
					}
				}
			}
			fclose($open_file);
			?>
			</table>
			<?php
		}
	}
	else
	{
		echo "<p class='error'>The DHCP leases file does not exist or does not have sufficient read privileges.</p>";
	}
}
else
{
	//login failed
	//check password
	?>
	<center>
	<p class="error">Login failed, please try again.</p>
	<div id="result">
	<form name="login">
	<p>Password: <input name="password" type="password">  
	<input value="Submit" type="button" onclick='JavaScript:xmlhttpPost("login.php")'></p>
	</form>
	</div>
	</center>
	<?php
}

function intToDay($integer)
{
	if ($integer == 0)
		return "Sunday";
	else if ($integer == 1)
		return "Monday";
	else if ($integer == 2)
		return "Tuesday";
	else if ($integer == 3)
		return "Wednesday";
	else if ($integer == 4)
		return "Thursday";
	else if ($integer == 5)
		return "Friday";
	else
		return "Saturday";
}

function initialize_array($row_array)
{
	for ($i = 1; $i < 7; $i++)
		$row_array[$i] = "<td>-</td>";

	return $row_array;
}

function print_array($row_array)
{
	for ($i = 0; $i < 7; $i++)
		echo $row_array[$i];
}
?>
