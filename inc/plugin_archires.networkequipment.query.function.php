<?php
/*
 * @version $Id: HEADER 1 2009-09-21 14:58 Tsmr $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2009 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
 
// ----------------------------------------------------------------------
// Original Author of file: CAILLAUD Xavier
// Purpose of file: plugin archires v1.8.0 - GLPI 0.80
// ----------------------------------------------------------------------
*/

function plugin_archires_display_Query_NetworkEquipment ($ID,$PluginArchiresQueryNetworkEquipment,$PluginArchiresView,$for){
	
	global $DB,$CFG_GLPI,$LANG,$LINK_ID_TABLE,$INFOFORM_PAGES;
	
	$types = array();
	$devices = array();
	$ports = array();
		
	if ($PluginArchiresView->fields["computer"]!=0)
		$types[]=COMPUTER_TYPE;
	if ($PluginArchiresView->fields["printer"]!=0)
		$types[]=PRINTER_TYPE;
	if ($PluginArchiresView->fields["peripheral"]!=0)
		$types[]=PERIPHERAL_TYPE;
	if ($PluginArchiresView->fields["phone"]!=0)
		$types[]=PHONE_TYPE;
	if ($PluginArchiresView->fields["networking"]!=0)
		$types[]=NETWORKING_TYPE;
	
	$query_switch = "SELECT c.name as port,c.id AS idport ";
	$query_switch .= " FROM glpi_networkequipments";
	$query_switch .= " LEFT JOIN glpi_networkports c ON (c.itemtype=" . NETWORKING_TYPE . " AND c.items_id=glpi_networkequipments.id)";
	$query_switch .= " WHERE glpi_networkequipments.id='".$PluginArchiresQueryNetworkEquipment->fields["networkequipments_id"]."' 
						AND glpi_networkequipments.is_deleted = '0' 
						AND glpi_networkequipments.is_template = '0'";
	$LINK= " AND " ;
	$query_switch.=getEntitiesRestrictRequest($LINK,"glpi_networkequipments");
	
	if ($result_switch = $DB->query($query_switch)) {
	
		while ($ligne = $DB->fetch_array($result_switch)) {

			$port = $ligne['port'];
			$nw=new NetWire();
			$end=$nw->getOppositeContact($ligne['idport']);

			if ($end){
			
				foreach ($types as $key => $val){
					
					if ($val == COMPUTER_TYPE) {
            $typefield = "computerstypes_id";
          }elseif ($val == NETWORKING_TYPE) {
            $typefield = "networkequipmentstypes_id";
          }elseif ($val == PERIPHERAL_TYPE) {
            $typefield = "peripheralstypes_id";
          }elseif ($val == PRINTER_TYPE) {
            $typefield = "printerstypes_id";
          }elseif ($val == PHONE_TYPE) {
            $typefield = "phonestypes_id";
          }
    
					$fieldsnp = "`np`.`id`, `np`.`items_id`, `np`.`logical_number`, `np`.`networkinterfaces_id`,`np`.`ip`,`np`.`netmask`, `np`.`name` AS namep";
					
					$query = "SELECT `$LINK_ID_TABLE[$val]`.`id` AS idc, $fieldsnp , `$LINK_ID_TABLE[$val]`.`name`, `$LINK_ID_TABLE[$val]`.`$typefield` AS `type`, `$LINK_ID_TABLE[$val]`.`users_id`, `$LINK_ID_TABLE[$val]`.`groups_id`, `$LINK_ID_TABLE[$val]`.`contact`, `$LINK_ID_TABLE[$val]`.`states_id` ";
					
					$query .= ", `$LINK_ID_TABLE[$val]`.`entities_id`,`$LINK_ID_TABLE[$val]`.`locations_id` ";
					$query .= " FROM `glpi_networkports` np, `$LINK_ID_TABLE[$val]`";
					if ($PluginArchiresQueryNetworkEquipment->fields["vlans_id"] > "0")
						$query .= ", `glpi_networkports_vlans` nv";

					$query .= " WHERE `np`.`itemtype` = " . $val . " 
											AND `np`.`items_id` = `$LINK_ID_TABLE[$val]`.`id`  
											AND `np`.`id` ='".$end."'";
					$query .= " AND `$LINK_ID_TABLE[$val]`.`is_deleted` = '0' 
										AND `$LINK_ID_TABLE[$val]`.`is_template` = '0'";
					$LINK= " AND " ;
					$query.=getEntitiesRestrictRequest($LINK,$LINK_ID_TABLE[$val]);
					if ($PluginArchiresQueryNetworkEquipment->fields["vlans_id"] > "0")
						$query .= " AND `nv`.`networkports_id` = `np`.`id` 
										AND vlans_id= '".$PluginArchiresQueryNetworkEquipment->fields["vlans_id"]."'";
					if ($PluginArchiresQueryNetworkEquipment->fields["networks_id"] > "0" && $val != PHONE_TYPE && $val != PERIPHERAL_TYPE)
						$query .= " AND `$LINK_ID_TABLE[$val]`.`networks_id` = '".$PluginArchiresQueryNetworkEquipment->fields["networks_id"]."'";
					if ($PluginArchiresQueryNetworkEquipment->fields["states_id"] > "0")
						$query .= " AND `$LINK_ID_TABLE[$val]`.`states_id` = '".$PluginArchiresQueryNetworkEquipment->fields["states_id"]."'";
					if ($PluginArchiresQueryNetworkEquipment->fields["groups_id"] > "0")
						$query .= " AND `$LINK_ID_TABLE[$val]`.`groups_id` = '".$PluginArchiresQueryNetworkEquipment->fields["groups_id"]."'";

					//types
					$query .= plugin_archires_Query_Type_check(PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY,$ID,$val);
					
					$query .= "ORDER BY `np`.`ip` ASC ";
					 
					if ($result = $DB->query($query)) {
						while ($data = $DB->fetch_array($result)) {
								
							if ($PluginArchiresView->fields["display_state"]!=0)
								$devices[$val][$data["items_id"]]["states_id"] = $data["states_id"];
								
							$devices[$val][$data["items_id"]]["type"] = $data["type"];
							$devices[$val][$data["items_id"]]["name"] = $data["name"];
							$devices[$val][$data["items_id"]]["users_id"] = $data["users_id"];
							$devices[$val][$data["items_id"]]["groups_id"] = $data["groups_id"];
							$devices[$val][$data["items_id"]]["contact"] = $data["contact"];
							$devices[$val][$data["items_id"]]["entity"] = $data["entities_id"];
							$devices[$val][$data["items_id"]]["locations_id"] = $data["locations_id"];
							
							$ports[$data["id"]]["items_id"] = $data["items_id"];
							$ports[$data["id"]]["logical_number"] = $data["logical_number"];
							$ports[$data["id"]]["networkinterfaces_id"] = $data["networkinterfaces_id"];
							$ports[$data["id"]]["ip"] = $data["ip"];
							$ports[$data["id"]]["netmask"] = $data["netmask"];
							$ports[$data["id"]]["namep"] = $data["namep"];
							$ports[$data["id"]]["idp"] = $data["id"];
							$ports[$data["id"]]["itemtype"] = $val;

							//ip
							if ($data["ip"]){
									
								if (!empty($devices[$val][$data["items_id"]]["ip"])){
									$devices[$val][$data["items_id"]]["ip"]  .= " - ";
									$devices[$val][$data["items_id"]]["ip"]  .= $data["ip"];
								}else{
									$devices[$val][$data["items_id"]]["ip"]  = $data["ip"];
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
	$query = "SELECT `n`.`id` AS `idn`, `np`.`id`, `np`.`items_id`, `np`.`logical_number`, `np`.`networkinterfaces_id` ,`np`.`ip`, `np`.`name` AS `namep`, `n`.`ip` AS `nip`,`np`.`netmask`, `n`.`name`, `n`.`networkequipmentstypes_id` AS `type`, `n`.`users_id`, `n`.`groups_id`, `n`.`contact`, `n`.`states_id`, `n`.`entities_id`,`n`.`locations_id`";
	$query .= " FROM `glpi_networkports` `np`, `glpi_networkequipments` `n` ";
	if ($PluginArchiresQueryNetworkEquipment->fields["vlans_id"] > "0")
		$query .= ", `glpi_networkports_vlans` nv";
	$query .= " WHERE `np`.`itemtype` = " . NETWORKING_TYPE . " 
	AND `np`.`items_id` = `n`.`id` 
	AND `n`.`id` = '".$PluginArchiresQueryNetworkEquipment->fields["networkequipments_id"]."'";
	$query .= " AND `n`.`is_deleted` = '0' 
	AND `n`.`is_template` = '0'";

	$query .= "ORDER BY `np`.`ip` ASC ";

	if ($result = $DB->query($query)) {
		while ($data = $DB->fetch_array($result)) {

			if ($PluginArchiresView->fields["display_state"]!=0)
				$devices[NETWORKING_TYPE][$data["items_id"]]["states_id"] = $data["states_id"];

			$devices[NETWORKING_TYPE][$data["items_id"]]["name"] = $data["name"];  
			$devices[NETWORKING_TYPE][$data["items_id"]]["type"] = $data["type"];
			$devices[NETWORKING_TYPE][$data["items_id"]]["users_id"] = $data["users_id"];
			$devices[NETWORKING_TYPE][$data["items_id"]]["groups_id"] = $data["groups_id"];
			$devices[NETWORKING_TYPE][$data["items_id"]]["contact"] = $data["contact"];
			$devices[NETWORKING_TYPE][$data["items_id"]]["ip"]  = $data["nip"];
			$devices[NETWORKING_TYPE][$data["items_id"]]["entity"] = $data["entities_id"];
			$devices[NETWORKING_TYPE][$data["items_id"]]["locations_id"] = $data["locations_id"];
			$ports[$data["id"]]["items_id"] = $data["items_id"];
			$ports[$data["id"]]["logical_number"] = $data["logical_number"];
			$ports[$data["id"]]["networkinterfaces_id"] = $data["networkinterfaces_id"];
			$ports[$data["id"]]["ip"] = $data["ip"];
			$ports[$data["id"]]["netmask"] = $data["netmask"];
			$ports[$data["id"]]["namep"] = $data["namep"];
			$ports[$data["id"]]["idp"] = $data["id"];
			$ports[$data["id"]]["itemtype"] = NETWORKING_TYPE;
		}
	}
	
	if ($for)
		return $devices;
	else
		return $ports;
}

?>