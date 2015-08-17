# Network Miner
Network Miner generates a network map by sending SNMP requests (LLDP/CDP/EDP).

![D3.js display](http://i.imgur.com/RkQj2EF.png)

## Table of contents:
- [Installation](#installation)
- [Test files](#test-files)
- [Roadmap](#roadmap)
- [Wiki](#wiki)

# Installation
* LLDP, EDP or CDP need to be enabled on your SNMP devices.
* You need a web server with the php snmp plugin/package enabled.

* Clone this repository into your sites location (e.g. /var/www/) :

`git clone https://github.com/tux-00/network_miner.git`

* Download dependencies :

`cd network_miner`

`bower install`

Check [bower.io](http://bower.io/) website for more informations about Bower.

* Edit [data_mining.php](data_mining.php) and set the first hostname (or ip address) to scan at this line:

`$FIRST_DEVICE = 'test';`

and the dig level at this line (second parameter):

`recursive_search($FIRST_DEVICE, 1);`

* Run index.php in your web browser.

# Test files
You can test Network Miner without the appropriate environment.

To test Network Miner, you need first to copy the content of a json example data file (located in [test/data/](test/data/)) to your *data* directory.

Once the file is copied you need to comment these lines to avoid the scan and the overwrite of the data on *snmp_data.json*: 
```
recursive_search($FIRST_DEVICE, 1);

file_put_contents('./data/snmp_data.json', json_encode(array('nodes' => $nodes, 
 'links' => $links)),
 LOCK_EX);
```

**JSON data faker**

If you want to submit data for testing purpose you can use the [json_data_faker.py](test/data/json_data_faker.py) Python 2.7 script to fake every device names in your JSON file.

To use this script you need to install *faker* module with `pip install fake-factory`.

Just put the script at your json files location and execute it: `python json_data_faker.py`
The script will scan the current folder, find the json files and replace device names.

# Roadmap
* Get SNMP informations from a specific device
* Autorefresh map data
* Save maps as PDF, PNG ...
* Icinga plugin

# Wiki
For more information, see the [Wiki](https://github.com/tux-00/network_miner/wiki) section.
