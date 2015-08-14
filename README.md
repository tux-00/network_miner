# Network Miner
Network Miner generates a network map by sending SNMP requests (LLDP/CDP/EDP).

![D3.js display](http://i.imgur.com/kFvCZty.png)

## Table of contents:
- [Installation](#installation)
- [Dependencies](#dependencies)
- [Files](#files)
- [How to use](#how-to-use)
- [Test files](#test-files)
- [Technical details](#technical-details)
- [Roadmap](#roadmap)

# Installation
Clone this repository :

`git clone https://github.com/tux-00/network_miner.git`

Download dependencies :

`cd network_miner`

`bower install`

Check [bower.io](http://bower.io/) website for more informations about Bower.

# Dependencies
The following libs are needed to run Network Miner.

* [jQuery](https://jquery.com/)
* [jQuery-ui](https://jqueryui.com/)
* [D3.js](https://github.com/mbostock/d3)
* [Bootstrap3](http://getbootstrap.com/)
* [typeahead.js](https://github.com/twitter/typeahead.js/)

Use `bower install` to install dependencies.

# Files
Four files are important.

* [index.php](index.php): base page that will be filled by [functions.js](functions.js) with Ajax
* [data_mining.php](data_mining.php): functions to get data from SNMP devices with CDP/EDP enabled
* [functions.js](functions.js): get events and display the network map
* [custom.css](custom.css): custom css for bootstrap, typeahead and D3.js

# How to use
* EDP/CDP need to be enabled on your SNMP devices.
* Edit [data_mining.php](data_mining.php) and set an hostname (or ip address) and the dig level at this line:  `recursive_search('eswctb08ma', 9);`
* Run index.php on your web browser.

# Test files
You can test Network Miner without the appropriate environment.

To test Network Miner, you need first to copy the content of a json example data file (located in [test/data/](test/data/)) to your *data* directory.

Once the file is copied you need to comment this line to avoid overwrite on *snmp_data.json*: 
```
file_put_contents('./data/snmp_data.json', json_encode(array('nodes' => $nodes, 
 'links' => $links)),
 LOCK_EX);
```

**JSON data faker**

If you want to submit data for testing purpose you can use the [json_data_faker.py](test/data/json_data_faker.py) Python 2.7 script to fake every device names in your JSON file.

To use this script you need to install *faker* module with `pip install fake-factory`.

# Technical details
[Cisco Discovery Protocol (CDP)](https://en.wikipedia.org/wiki/Cisco_Discovery_Protocol):
> The Cisco Discovery Protocol (CDP) is a proprietary Data Link Layer protocol developed by Cisco Systems. It is used to share information about other directly connected Cisco equipment, such as the operating system version and IP address.

[Extreme Discovery Protocol (EDP)](https://wiki.wireshark.org/EDP):
>EDP is a vendor proprietary protocol from Extreme Networks. It is used to send information like system MAC, device name or VLAN information to neighboring Extreme devices.

JSON data structure:
```
{
    "nodes":[
        {
            "name":"eswexample",
            "group":0
        },
        {
            "name":"eswtest",
            "group":0
        }
    ],
    "links":[
        {
            "source":1,
            "target":0
        },
        {
            "source":2,
            "target":0
        }
    ]
}
```

# Roadmap
* Autorefresh map data
* Save maps as PDF, PNG ...
* Icinga plugin
