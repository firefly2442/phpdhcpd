<?php
//phpdhcpd config file

//Location of dhcpd.leases file
$dhcpd_leases_file = "/var/lib/dhcp3/dhcpd.leases";

//If password is left blank, NO authentication will
//be required.  However, if it is NOT blank, the only
//way to view the page is by entering the correct password.
//This password is in cleartext, if other people can access
//this file, this is NOT secure.  For true security, use
//Apache digest authentication or other methods.
$password = "";


?>