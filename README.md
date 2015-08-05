# Network Miner
Network Miner digs your network by sending SNMP requests (EDP/CDP) and generates a network map.

## Table of contents:
- [Dependencies](#dependencies)
- [Files](#files)
- [How to use](#how-to-use)
- [Test files](#test-files)
- [Screenshots](#screenshots)
- [Technical details](#technical-details)
- [Roadmap](#roadmap)

# Dependencies
The following libs are needed to run Network Miner.

* [jQuery](https://jquery.com/)
* [jQuery-ui](https://jqueryui.com/)
* [D3.js](https://github.com/mbostock/d3)
* [Bootstrap3](http://getbootstrap.com/)
* [typeahead.js](https://github.com/twitter/typeahead.js/)

At this moment all libs are included into the repository. ([js](js/) and [bootstrap](bootstrap/) dirs)

# Files
Four files are important.

* [index.php](index.php): base page that will be filled by [functions.js](js/functions.js) with Ajax
* [data_mining.php](data_mining.php): functions to get data from SNMP devices with CDP/EDP enabled
* [functions.js](js/functions.js): get events and display the network map
* [custom.css](bootstrap/css/custom.css): custom css for bootstrap, typeahead and D3.js

# How to use
* EDP/CDP need to be enabled on your SNMP devices.
* Edit [data_mining.php](data_mining.php) and set an hostname (or ip address) and the dig level at this line:  `recursive_search('eswctb08ma', 9);`
* Run index.php on your web browser.

# Test files
You can test Network Miner without the appropriate environment.

To test Network Miner, you need first to copy json data file [test/data/snmp_data.json](test/data/snmp_data.json) to your *data* dir.

Once the file is copied you need to comment this line to avoid overwrite on *snmp_data.json*: 
```
file_put_contents('./data/snmp_data.json', json_encode(array('nodes' => $nodes, 
												        	 'links' => $links)),
												        	 LOCK_EX);
```
# Screenshots
![D3.js display](http://i.imgur.com/eMewdyu.png)

# Technical details
[Cisco Discovery Protocol (CDP)](https://en.wikipedia.org/wiki/Cisco_Discovery_Protocol):
> The Cisco Discovery Protocol (CDP) is a proprietary Data Link Layer protocol developed by Cisco Systems. It is used to share information about other directly connected Cisco equipment, such as the operating system version and IP address.

[Extreme Discovery Protocol (EDP)](https://wiki.wireshark.org/EDP):
>EDP is a vendor proprietary protocol from Extreme Networks. It is used to send information like system MAC, device name or VLAN information to neighboring Extreme devices.

# Roadmap
* CDP integration
* Autorefresh map
* Save maps
