<?php


class ParseClass
{
	//Create a 2-dimensional table for the dhcplease file
	public $dhcptable = array(array());
	//Number of entries to display after filtering
	public $filtered_number_display = 0;

	public function parser($open_file)
	{
		$line_number = 0;
		$row_array = array(array());
		while (!feof($open_file))
		{	
			$read_line = fgets($open_file, 4096);
			if (substr($read_line, 0, 1) != "#") //check for comment (skip)
			{
				$tok = strtok($read_line, " ");
				if ($tok == "lease")
				{
					$row_array[$line_number] = $this->initialize_array();
					$row_array[$line_number][0] = strtok(" ")."\n";
				}
				else if ($tok == "starts")
				{
					$day = $this->intToDay(strtok(" "));
					$row_array[$line_number][1] = strtok(" ") . " ";
					$time = strtok(" ");
					$time = str_replace(";", "", $time);
					$row_array[$line_number][1] = $row_array[$line_number][1].$time;
					$row_array[$line_number][1] = $row_array[$line_number][1]."(".$day.")";
				}
				else if ($tok == "ends")
				{
					$day = $this->intToDay(strtok(" "));
					$row_array[$line_number][2] = strtok(" ") . " ";
					$time = strtok(" ");
					$time = str_replace(";", "", $time);
					$row_array[$line_number][2] = $row_array[$line_number][2].$time;
					$row_array[$line_number][2] = $row_array[$line_number][2]."(".$day.")";
				}	
				else if ($tok == "tstp")
				{
					$day = $this->intToDay(strtok(" "));
					$row_array[$line_number][3] = strtok(" ") . " ";
					$time = strtok(" ");
					$time = str_replace(";", "", $time);
					$row_array[$line_number][3] = $row_array[$line_number][3].$time;
					$row_array[$line_number][3] = $row_array[$line_number][3]."(".$day.")";
				}
				else if ($tok == "hardware")
				{
					$row_array[$line_number][4] = strtok(" ") . " - ";
					$MAC = strtok(" ");
					$MAC = strtoupper(str_replace(";", "", $MAC));
					$MAC = strtoupper(str_replace("ethernet - ", "", $MAC));
				
					$row_array[$line_number][4] = $MAC." (".$this->getmacvendor($MAC).")";
				}
				else if ($tok == "uid")
				{
					$uid = strtok(" ");
					$replace = array(".", "\"", ";");
					$uid = str_replace($replace, "", $uid);
					$row_array[$line_number][5] = $uid;
				}
				else if ($tok == "client-hostname")
				{
					$hostname = strtok(" ");
					$replace = array("\"", ";");
					$hostname = str_replace($replace, "", $hostname);
					$row_array[$line_number][6] = $hostname;
				}
				else if ($tok == "}\n")
				{
					$row_array[$line_number][6] = $row_array[$line_number][6];
					$line_number++;
				}
			}
		}
	
		$this->dhcptable = $row_array;
	}


	private function intToDay($integer)
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

	private function initialize_array()
	{
		$row_array = array();
		for ($i = 0; $i < 7; $i++) {
			$row_array[$i] = "-";
		}
		return $row_array;
	}

	private function print_line($row, $css_num)
	{
		for ($i = 0; $i < 7; $i++) {
			switch ($i) {
			case 0: 
				//IP Address
				echo "<tr class='row".$css_num."'><td><a href='http://".$row[0]."'>".$row[0]."</a></td>\n";
				break;
			case 1: 
				//Start Time
				echo "<td>".$row[1]. "</td>\n"; 
				break;
			case 2: 
				//End Time
				echo "<td>".$row[2]. "</td>\n"; 
				break;
			case 3: 
				//Lease Expires
				echo "<td>".$row[3]."</td>\n"; 
				break;
			case 4: 
				//MAC Address
				echo "<td>".$row[4]."</td>\n"; 
				break;
			case 5: 
				//Client Identifier
				echo "<td>".$row[5]. "</td>\n"; 
				break;
			case 6:
				//Hostname
				echo "<td>".$row[6]. "</td>\n</tr>";
				break;
			}
		}
	}

	private function compare_ip($a, $b) 
	{
		return strnatcmp($a[0], $b[0]);
	}

	private function compare_start_time($a, $b) 
	{
		return strnatcmp($a[1], $b[1]);
	}

	private function compare_end_time($a, $b) 
	{
		return strnatcmp($a[2], $b[2]);
	}

	private function compare_lease_expire($a, $b) 
	{
		return strnatcmp($a[3], $b[3]);
	}

	private function compare_mac($a, $b)
	{ 
		return strnatcmp($a[4], $b[4]);
	}

	private function compare_uid($a, $b)
	{
		return strnatcmp($a[5], $b[5]);
	}

	private function compare_hostname($a, $b)
	{
		return strnatcmp($a[6], $b[6]);
	}

	private function getmacvendor($mac_unformated)
	{
		require("config.php");
		if ($mac_vendor == true)
		{
			//Can be retrived on nmap http://nmap.org/book/nmap-mac-prefixes.html
			//or via http://standards.ieee.org/develop/regauth/oui/oui.txt
			//Location of the mac vendor list file
			$mac_vendor_file = "./nmap-mac-prefixes";
			$mac_vendor_file_cache = "./nmap-mac-prefixes_cache";

			$mac = substr(strtoupper(str_replace(array(":"," ","-"), "", $mac_unformated)),0,6);

			if ($cache_vendor_results) {
				// Open the MAC VENDOR CACHE file
				$open_file_cache = fopen($mac_vendor_file_cache, "r") or die("Unable to open MAC VENDOR CACHE file.");

				// First try to lookup the vendor in the cache file
				if ($open_file_cache) {
					while (!feof($open_file_cache)) {
						 $read_line = fgets($open_file_cache, 4096);
						 if (substr($read_line, 0, 6) == $mac) {
							return substr($read_line, 7, -1);
						 }
					}
					fclose($open_file_cache);
				}
			}

			// Second do regular lookup in the main file
			$open_file = fopen($mac_vendor_file, "r") or die("Unable to open MAC VENDOR file.");
			if ($open_file) {
				//open vendor cache file for writing (appending)
				if ($cache_vendor_results && is_writable($mac_vendor_file_cache)) {
					$open_file_cache_a = fopen($mac_vendor_file_cache, "a") or die("Unable to open MAC VENDOR CACHE file for writing.");
				}
				while (!feof($open_file)) {
					 $read_line = fgets($open_file, 4096);
					 if (substr($read_line, 0, 6) == $mac) {
						if ($cache_vendor_results && is_writable($mac_vendor_file_cache)) {
							//write the "hit" to the cache file
							fwrite($open_file_cache_a, $read_line);
						}
						return substr($read_line, 7, -1);
					 }
				}
				fclose($open_file);
				fclose($open_file_cache_a);
			}
			return "Unknown device";
		} else {
			return "Vendor Check Disabled";
		}
	}

	private function checkActiveLease($dhcp_line)
	{
		//Returns true or false depending on if the lease is currently active or not
		$leaseStart = strtotime(substr($dhcp_line[1], 0, strpos($dhcp_line[1], "(")));
		$leaseEnd = strtotime(substr($dhcp_line[2], 0, strpos($dhcp_line[2], "(")));

		if (time() >= $leaseStart && time() <= $leaseEnd) {
		  return true;
		} else {
			return false;
		}
	}


	public function print_table($searchfilter, $sort_column, $onlyactiveleases)
	{
		$order = 0;
		switch ($sort_column) {
			case 1: 
				usort($this->dhcptable, array($this, 'compare_ip'));
				break;
			case 2: 
				usort($this->dhcptable, array($this, 'compare_start_time'));
				break;
			case 3: 
				usort($this->dhcptable, array($this, 'compare_end_time')); 
				break;
			case 4: 
				usort($this->dhcptable, array($this, 'compare_lease_expire')); 
				break;
			case 5: 
				usort($this->dhcptable, array($this, 'compare_mac'));
				break;
			case 6: 
				usort($this->dhcptable, array($this, 'compare_uid'));
				break;
			case 7:
				usort($this->dhcptable, array($this, 'compare_hostname'));
				break;
			case -1: 
				usort($this->dhcptable, array($this, 'compare_ip'));
				$order=-1;
				break;
			case -2: 
				usort($this->dhcptable, array($this, 'compare_start_time'));
				$order=-1;
				break;
			case -3: 
				usort($this->dhcptable, array($this, 'compare_end_time')); 
				$order=-1;
				break;
			case -4: 
				usort($this->dhcptable, array($this, 'compare_lease_expire')); 
				$order=-1;
				break;
			case -5: 
				usort($this->dhcptable, array($this, 'compare_mac'));
				$order=-1;
				break;
			case -6: 
				usort($this->dhcptable, array($this, 'compare_uid'));
				$order=-1;
				break;
			case -7:
				usort($this->dhcptable, array($this, 'compare_hostname'));
				$order=-1;
				break;
		}
	
	
		$displayed_line_number = 0;
		if ($order >= 0) {
			$line = 0;
		} else {
			$line = count($this->dhcptable)-1;
		}

		//Read every line of the table to see if it should be printed
		while ($line < count($this->dhcptable) && $line >= 0) {

			//Check if the line contains the searched request
			if ($searchfilter != "") {
				$displayline = 0;
				for ($i = 0; $i < 7; $i++) {
					if (stristr (strtolower($this->dhcptable[$line][$i]), strtolower($searchfilter))== TRUE) {
						if (!$onlyactiveleases || ($onlyactiveleases && $this->checkActiveLease($this->dhcptable[$line]))) {
							$css_num = $displayed_line_number % 2;
							$this->print_line($this->dhcptable[$line], $css_num);
							$displayed_line_number++;
							break;
						}
					}
				}
			} else {
				if (!$onlyactiveleases || ($onlyactiveleases && $this->checkActiveLease($this->dhcptable[$line]))) {
					$css_num = $displayed_line_number % 2;
					$this->print_line($this->dhcptable[$line], $css_num);
					$displayed_line_number++;
				}
			}

			//increment or decrement line (based on if we are ascending or descending)
			if ($order >= 0) {
				$line++;
			} else {
				$line--;
			}
		}
		$this->filtered_number_display = $displayed_line_number;
	}
}
?>
