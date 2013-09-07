---phpdhcpd---
Version: 0.7
License: GPLv2 (see license.txt)

phpdhcpd provides a nice online interface for viewing
the file /var/lib/dhcp3/dhcpd.leases.  This is created
by a DHCP server.  This file
contains all the information regarding a DHCP server
and the IP addresses that it has allocated to users.
Compatibility with other DHCP lease servers is unknown
at this time.

Installation is pretty simple.  Just copy the folder
to where you want it in your "www" directory in apache.

Edit the "config.php" file and follow the directions.

If set it to do MAC address lookups, I would recommend
also enabling the cache file.  Make sure your webserver
has write access for the nmap-mac-prefixes_cache file.

Now just navigate to your website and that directory.
It should come up and display the information.

Images are from the Tango Desktop Project (version 0.8.90):
Released under public domain

http://tango.freedesktop.org
