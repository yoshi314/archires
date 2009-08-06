<?php
/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.
 
 http://indepnet.net/   http://glpi.indepnet.org
 ----------------------------------------------------------------------

 LICENSE

    This file is part of GLPI.

    GLPI is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    GLPI is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GLPI; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
*/

function plugin_archires_display_Query_Switch ($ID,$PluginArchiresQuerySwitch,$PluginArchiresConfig,$for){
	
	global $DB,$CFG_GLPI,$LANG,$LINK_ID_TABLE,$INFOFORM_PAGES;
	
	$types = array();
	$devices = array();
	$ports = array();
		
	if ($PluginArchiresConfig->fields["computer"]!=0)
		$types[]=COMPUTER_TYPE;
	if ($PluginArchiresConfig->fields["printer"]!=0)
		$types[]=PRINTER_TYPE;
	if ($PluginArchiresConfig->fields["peripheral"]!=0)
		$types[]=PERIPHERAL_TYPE;
	if ($PluginArchiresConfig->fields["phone"]!=0)
		$types[]=PHONE_TYPE;
	if ($PluginArchiresConfig->fields["networking"]!=0)
		$types[]=NETWORKING_TYPE;
	
	$query_switch = "SELECT c.name as port,c.ID AS IDport ";
	$query_switch .= " FROM glpi_networking";
	$query_switch .= " LEFT JOIN glpi_networking_ports c ON (c.device_type=" . NETWORKING_TYPE . " AND c.on_device=glpi_networking.ID)";
	$query_switch .= " WHERE glpi_networking.id='".$PluginArchiresQuerySwitch->fields["switch"]."' 
						AND glpi_networking.deleted = '0' 
						AND glpi_networking.is_template = '0'";
	$LINK= " AND " ;
	$query_switch.=getEntitiesRestrictRequest($LINK,"glpi_networking");
	
	if ($result_switch = $DB->query($query_switch)) {
	
		while ($ligne = $DB->fetch_array($result_switch)) {

			$port = $ligne['port'];
			$nw=new NetWire();
			$end=$nw->getOppositeContact($ligne['IDport']);

			if ($end){
			
				foreach ($types as $key => $val){
						
					$fieldsnp = "`np`.`ID`, `np`.`on_device`, `np`.`logical_number`, `np`.`iface`,`np`.`ifaddr`,`np`.`netmask`, `np`.`name` AS namep";
					
					$query = "SELECT `$LINK_ID_TABLE[$val]`.`ID` AS IDc, $fieldsnp , `$LINK_ID_TABLE[$val]`.`name`, `$LINK_ID_TABLE[$val]`.`type`, `$LINK_ID_TABLE[$val]`.`FK_users`, `$LINK_ID_TABLE[$val]`.`FK_groups`, `$LINK_ID_TABLE[$val]`.`contact`, `$LINK_ID_TABLE[$val]`.`state`, `$LINK_ID_TABLE[$val]`.`type`";
					
					$query .= ", `$LINK_ID_TABLE[$val]`.`FK_entities`,`$LINK_ID_TABLE[$val]`.`location` ";
					$query .= " FROM `glpi_networking_ports` np, `$LINK_ID_TABLE[$val]`";
					if ($PluginArchiresQuerySwitch->fields["FK_vlan"] > "0")
						$query .= ", `glpi_networking_vlan` nv";

					$query .= " WHERE `np`.`device_type` = " . $val . " 
											AND `np`.`on_device` = `$LINK_ID_TABLE[$val]`.`ID`  
											AND `np`.`ID` ='".$end."'";
					$query .= " AND `$LINK_ID_TABLE[$val]`.`deleted` = '0' 
										AND `$LINK_ID_TABLE[$val]`.`is_template` = '0'";
					$LINK= " AND " ;
					$query.=getEntitiesRestrictRequest($LINK,$LINK_ID_TABLE[$val]);
					if ($PluginArchiresQuerySwitch->fields["FK_vlan"] > "0")
						$query .= " AND `nv`.`FK_port` = `np`.`ID` 
										AND FK_vlan= '".$PluginArchiresQuerySwitch->fields["FK_vlan"]."'";
					if ($PluginArchiresQuerySwitch->fields["network"] > "0")
						$query .= " AND `$LINK_ID_TABLE[$val]`.`network` = '".$PluginArchiresQuerySwitch->fields["network"]."'";
					if ($PluginArchiresQuerySwitch->fields["state"] > "0")
						$query .= " AND `$LINK_ID_TABLE[$val]`.`state` = '".$PluginArchiresQuerySwitch->fields["state"]."'";
					if ($PluginArchiresQuerySwitch->fields["FK_group"] > "0")
						$query .= " AND `$LINK_ID_TABLE[$val]`.`FK_groups` = '".$PluginArchiresQuerySwitch->fields["FK_group"]."'";

					//types
					$query .= plugin_archires_Query_Type_check(PLUGIN_ARCHIRES_SWITCH_QUERY,$ID,$val);
					
					$query .= "ORDER BY `np`.`ifaddr` ASC ";
					 
					if ($result = $DB->query($query)) {
						while ($data = $DB->fetch_array($result)) {
								
							if ($PluginArchiresConfig->fields["display_state"]!=0)
								$devices[$val][$data["on_device"]]["state"] = $data["state"];
								
							$devices[$val][$data["on_device"]]["type"] = $data["type"];
							$devices[$val][$data["on_device"]]["name"] = $data["name"];
							$devices[$val][$data["on_device"]]["FK_users"] = $data["FK_users"];
							$devices[$val][$data["on_device"]]["FK_groups"] = $data["FK_groups"];
							$devices[$val][$data["on_device"]]["contact"] = $data["contact"];
							$devices[$val][$data["on_device"]]["entity"] = $data["FK_entities"];
							$devices[$val][$data["on_device"]]["location"] = $data["location"];
							
							$ports[$data["ID"]]["on_device"] = $data["on_device"];
							$ports[$data["ID"]]["logical_number"] = $data["logical_number"];
							$ports[$data["ID"]]["iface"] = $data["iface"];
							$ports[$data["ID"]]["ifaddr"] = $data["ifaddr"];
							$ports[$data["ID"]]["netmask"] = $data["netmask"];
							$ports[$data["ID"]]["namep"] = $data["namep"];
							$ports[$data["ID"]]["IDp"] = $data["ID"];
							$ports[$data["ID"]]["device_type"] = $val;

							//ip
							if ($data["ifaddr"]){
									
								if (!empty($devices[$val][$data["on_device"]]["ifaddr"])){
									$devices[$val][$data["on_device"]]["ifaddr"]  .= " - ";
									$devices[$val][$data["on_device"]]["ifaddr"]  .= $data["ifaddr"];
								}else{
									$devices[$val][$data["on_device"]]["ifaddr"]  = $data["ifaddr"];
								}
								
							}
							//fin ip
						}
					}
				}
			}
		}
	}
	//The networking
	$query = "SELECT n.ID AS IDn, np.ID, np.on_device, np.logical_number, np.iface ,np.ifaddr, np.name as namep, n.ifaddr as nifaddr,np.netmask, n.name, n.type, n.FK_users, n.FK_groups, n.contact, n.state, n.FK_entities,n.location";
	$query .= " FROM glpi_networking_ports np, glpi_networking n";
	if ($PluginArchiresQuerySwitch->fields["FK_vlan"] > "0")
		$query .= ", glpi_networking_vlan nv";
	$query .= " WHERE np.device_type = " . NETWORKING_TYPE . " 
	AND np.on_device = n.ID 
	AND n.ID='".$PluginArchiresQuerySwitch->fields["switch"]."'";
	$query .= " AND n.deleted = '0' 
	AND n.is_template = '0'";

	$query .= "ORDER BY np.ifaddr ASC ";

	if ($result = $DB->query($query)) {
		while ($data = $DB->fetch_array($result)) {

			if ($PluginArchiresConfig->fields["display_state"]!=0)
				$devices[NETWORKING_TYPE][$data["on_device"]]["state"] = $data["state"];

			$devices[NETWORKING_TYPE][$data["on_device"]]["name"] = $data["name"];  
			$devices[NETWORKING_TYPE][$data["on_device"]]["type"] = $data["type"];
			$devices[NETWORKING_TYPE][$data["on_device"]]["FK_users"] = $data["FK_users"];
			$devices[NETWORKING_TYPE][$data["on_device"]]["FK_groups"] = $data["FK_groups"];
			$devices[NETWORKING_TYPE][$data["on_device"]]["contact"] = $data["contact"];
			$devices[NETWORKING_TYPE][$data["on_device"]]["ifaddr"]  = $data["nifaddr"];
			$devices[NETWORKING_TYPE][$data["on_device"]]["entity"] = $data["FK_entities"];
			$devices[NETWORKING_TYPE][$data["on_device"]]["location"] = $data["location"];
			$ports[$data["ID"]]["on_device"] = $data["on_device"];
			$ports[$data["ID"]]["logical_number"] = $data["logical_number"];
			$ports[$data["ID"]]["iface"] = $data["iface"];
			$ports[$data["ID"]]["ifaddr"] = $data["ifaddr"];
			$ports[$data["ID"]]["netmask"] = $data["netmask"];
			$ports[$data["ID"]]["namep"] = $data["namep"];
			$ports[$data["ID"]]["IDp"] = $data["ID"];
			$ports[$data["ID"]]["device_type"] = NETWORKING_TYPE;
		}
	}
	
	if ($for)
		return $devices;
	else
		return $ports;
}

?>