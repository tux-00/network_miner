<?php

// Array of device names and groups (informations will be inserted into the JSON
// file to create the map)
$nodes = array();
// Links between devices (informations will be inserted into the JSON file to
// create the map)
$links = array();
// Will be inserted into the $nodes and $devices global arrays
$group = 0;
// Array contening device names with her group, parent and links
$devices = array();
// Correct OID to use
$OID_SELECTED = 0;

snmp_set_quick_print(TRUE);

$OIDS = array(
	"LLDP" => "iso.0.8802.1.1.2.1.4.1.1.9",
	"CDP" => "1.3.6.1.4.1.9.9.23.1.2.1.1.6",
	"EDP" => "1.3.6.1.4.1.1916.1.13.2.1.3"
);


/**
 * Select the correct OID from the select list
 *
 * @return 	string|bool 	Associated OID, FALSE if error
 */
function get_oid() {
	global $OIDS;

	if ($_POST['community_input'] == '') {
		$COMMUNITY = 'public';
	} else {
		$COMMUNITY = $_POST['community_input'];
	}

	if ($_POST['selected_proto'] != 'Auto') {
		foreach ($OIDS as $name => $oid) {
			if ($name == $_POST['selected_proto']) {
				if (snmp2_walk($_POST['ip_input'], $COMMUNITY, $oid) != FALSE) {
					return $oid;
				} else {
					display_alert('danger', error_get_last()['message'] . '.');
					return FALSE;
				}

			}
		}
	}
	if ($_POST['selected_proto'] == 'Auto') {
		foreach ($OIDS as $name => $oid) {
			$result = @snmp2_walk($_POST['ip_input'], 'public', $oid);
			if ($result != FALSE) {
				return $oid;
			}
		}
	}

	display_alert('danger', error_get_last()['message'] . '.');
	return FALSE;
}


/**
 * Return the CDP/EDP table of $device
 *
 * @param 	string	$device 	Device name to request
 * @return 	table|bool 	SNMP response, FALSE if error
 */
function get_snmp_table($device) {
	global $OID_SELECTED;

	$result = snmp2_walk($device, 'public', $OID_SELECTED);

	if ($result != FALSE) {
		$result = str_replace('"', '', $result);
		$result = array_unique($result, SORT_STRING);
		$result = array_values(array_filter($result));
		return $result;
	} else {
		return FALSE;
	}
}


/**
 * Return the node ID (json data) of $device
 *
 * @param 	string	$device 	Device name
 * @return 	int|bool 	Node ID of $device, FALSE if not found
 */
function get_device_id($device) {
	global $nodes;

	foreach($nodes as $key => $node) {
		if($node['name'] == $device) {
			return $key;
		}
	}
	return FALSE;
}


/**
 * Store informations from $base_device in global variables
 *
 * @param 	string	$base_device 	Device name for base search
 * @return 	bool 	Returns TRUE if informations was written, FALSE otherwise
 */
function get_device_links($base_device) {
	global $devices;
	global $nodes;
	global $group;
	global $links;

	$base_device_id = get_device_id($base_device);

	// Get devices connected to $base_device
	$snmp_response = get_snmp_table($base_device);

	if($snmp_response == FALSE) {
		return FALSE;
	}

	foreach($snmp_response as $key => $device) {
		// If $base_device not in $nodes (first run of get_device_links()), add it
		if(strstr(json_encode($nodes), $base_device) == FALSE) {
			array_push($nodes, array('name'  => $base_device,
						 'group' => $group));
		}

		// If $device is already present, delete it to avoid duplicate entry
		if(strstr(json_encode($nodes), $device) != FALSE) {
			unset($snmp_response[$key]);

			$device_id = get_device_id($device);

			if($device_id != FALSE && $base_device_id != FALSE) {
				array_push($links, array('source' => $device_id,
							 'target' => $base_device_id));
			}
		} else {
			// Add devices linked to $base_device in $nodes
			array_push($nodes, array('name'   => $device,
						 'group'  => $group));
			array_push($links, array('source' => get_device_id($device),
						 'target' => get_device_id($base_device)));
		}
	}

	// Add $base_device + links + group to $devices
	array_push($devices, array('group'  => $group,
				    'parent' => $base_device,
				    'links'  => $snmp_response));

	if(strstr(json_encode($nodes), $base_device) != FALSE) $group++;

	return TRUE;
}


/**
 * Request all links found in CDP/EDP response
 *
 * @param 	string	$base_device 	Device name for base search
 * @param 	int 	$level 	Dig level of CDP/EDP requests
 * @return 	bool 	Returns TRUE if links of $base_device has been returned, FALSE otherwise
 */
function recursive_search($base_device, $level = 2) {
	global $devices;

	// Fill the $devices global variable
	if(get_device_links($base_device) == FALSE) {
		return FALSE;
	}

	$i = 0;
	do {
		foreach($devices[$i]['links'] as $sw) {
			get_device_links($sw);
		}
		$i++;
	} while($i < $level);

	return TRUE;
}


/**
 * Request all links found in CDP/EDP response
 *
 * @param 	string	$type 	Type of alert (success, info, warning, danger)
 * @param 	string 	$content 	Content of the alert
 * @return 	bool 	Returns TRUE if alert is print, FALSE otherwise
 */
function display_alert($type, $content) {
	$types = array(
		'success'	=> 'glyphicon-ok-sign',
		'info'		=> 'glyphicon-info-sign',
		'warning'	=> 'glyphicon-warning-sign',
		'danger' 	=> 'glyphicon-exclamation-sign'
	);

	if(!array_key_exists($type, $types)) {
		return FALSE;
	}

	print '<div class="alert alert-' . $type . ' alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<span class="glyphicon ' . $types[$type] . '" aria-hidden="true"></span> '
		. $content . '</div>';

	return TRUE;
}


// Get the correct OID to send SNMP request
$OID_SELECTED = get_oid();
if ($OID_SELECTED == FALSE) {
	exit(-1);
}

recursive_search($_POST['ip_input'], 1);

file_put_contents('./data/snmp_data.json', json_encode(array('nodes' => $nodes,
							     'links' => $links)),
							     LOCK_EX);

if(count($nodes) > 0) {
	display_alert('info', 'Protocol : ' . array_search($OID_SELECTED, $OIDS, TRUE) . ' | ' . count($nodes) . ' devices | ' . count($links) . ' links');
} else {
	display_alert('info', 'No device found. Check if your devices are up, if SNMP is enabled, if LLDP, CDP or EDP are enabled and if the configuration is correct.');
}
