<?php
//phpdhcpd config file

//Location of dhcpd.leases file
$dhcpd_leases_file = "/var/lib/dhcp3/dhcpd.leases";

//If password is left blank, NO authentication will
//be required and the page will be visible to anyone.
//However, if it is NOT blank, the only
//way to view the page is by entering the correct password.
//This password is in cleartext, if other people can access
//this file, this is NOT secure.  For true security, use
//Apache digest authentication or other methods such as
//an htaccess file.
$password = "";

//Run MAC address vendor check
//Note: this can be taxing on systems with a large leases file
$mac_vendor = true;


//phpdhcpd version
//You don't have to change this
$version = "0.5";
?>
