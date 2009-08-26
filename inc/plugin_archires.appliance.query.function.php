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

function plugin_archires_display_Query_Appliance ($ID,$PluginArchiresQueryAppliance,$PluginArchiresView,$for){
	
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
		
		$query = "SELECT `$LINK_ID_TABLE[$val]`.`id` AS idc, $fieldsnp , `$LINK_ID_TABLE[$val]`.`name`, `$LINK_ID_TABLE[$val]`.`$typefield` AS `type`, `$LINK_ID_TABLE[$val]`.`users_id`, `$LINK_ID_TABLE[$val]`.`groups_id`, `$LINK_ID_TABLE[$val]`.`contact`, `$LINK_ID_TABLE[$val]`.`states_id`";
		//en +
		$query .= ", `$LINK_ID_TABLE[$val]`.`entities_id`,`$LINK_ID_TABLE[$val]`.`locations_id` ";
		$query .= " FROM `glpi_networkports` np, `$LINK_ID_TABLE[$val]` ";
		if ($PluginArchiresQueryAppliance->fields["vlans_id"] > "0")
			$query .= ", `glpi_networkports_vlans` nv";

		$query .= ", `glpi_plugin_appliances_items` app";
		$query .= " WHERE `np`.`itemtype` = " . $val . " 
					AND `np`.`items_id` = `$LINK_ID_TABLE[$val]`.`id` 
					AND `app`.`items_id` = `$LINK_ID_TABLE[$val]`.`id` ";
		$query .= " AND `$LINK_ID_TABLE[$val]`.`is_deleted` = '0' 
					AND `$LINK_ID_TABLE[$val]`.`is_template` = '0'";
		$LINK= " AND " ;
		$query.=getEntitiesRestrictRequest($LINK,$LINK_ID_TABLE[$val]);
		if ($PluginArchiresQueryAppliance->fields["vlans_id"] > "0")
			$query .= " AND `nv`.`networkports_id` = `np`.`id` 
						AND `vlans_id` = '".$PluginArchiresQueryAppliance->fields["vlans_id"]."'";
		if ($PluginArchiresQueryAppliance->fields["networks_id"] > "0" && $val != PHONE_TYPE && $val != PERIPHERAL_TYPE)
			$query .= " AND `$LINK_ID_TABLE[$val]`.`networks_id` = '".$PluginArchiresQueryAppliance->fields["networks_id"]."'";
		if ($PluginArchiresQueryAppliance->fields["states_id"] > "0")
			$query .= " AND `$LINK_ID_TABLE[$val]`.`states_id` = '".$PluginArchiresQueryAppliance->fields["states_id"]."'";
		if ($PluginArchiresQueryAppliance->fields["groups_id"] > "0")
			$query .= " AND `$LINK_ID_TABLE[$val]`.`groups_id` = '".$PluginArchiresQueryAppliance->fields["groups_id"]."'";
		
		$query .= " AND `app`.`appliances_id` = '" . $PluginArchiresQueryAppliance->fields["appliances"] . "' 
					AND `app`.`itemtype` = " . $val . " ";
		
		$query .= plugin_archires_Query_Type_check(PLUGIN_ARCHIRES_APPLIANCES_QUERY,$ID,$val);
		  
		$query .= "ORDER BY `np`.`ip` ASC ";
	
		if ($result = $DB->query($query)) {
			while ($data = $DB->fetch_array($result)) {

				if ($PluginArchiresView->fields["display_state"]!=0)
					$devices[$val][$data["items_id"]]["state"] = $data["state"];

				$devices[$val][$data["items_id"]]["type"] = $data["type"];
				$devices[$val][$data["items_id"]]["name"] = $data["name"];
				$devices[$val][$data["items_id"]]["users_id"] = $data["users_id"];
				$devices[$val][$data["items_id"]]["groups_id"] = $data["groups_id"];
				$devices[$val][$data["items_id"]]["contact"] = $data["contact"];
				$devices[$val][$data["items_id"]]["entity"] = $data["entities_id"];
				$devices[$val][$data["items_id"]]["locations_id"] = $data["locations_id"];
				
				if ($data["ip"]){
					if (!empty($devices[$val][$data["items_id"]]["ip"])){
						$devices[$val][$data["items_id"]]["ip"]  .= " - ";
						$devices[$val][$data["items_id"]]["ip"]  .= $data["ip"];
					}else{
						$devices[$val][$data["items_id"]]["ip"]  = $data["ip"];
					}
				}
				
				$ports[$data["id"]]["items_id"] = $data["items_id"];
				$ports[$data["id"]]["logical_number"] = $data["logical_number"];
				$ports[$data["id"]]["networkinterfaces_id"] = $data["networkinterfaces_id"];
				$ports[$data["id"]]["ip"] = $data["ip"];
				$ports[$data["id"]]["netmask"] = $data["netmask"];
				$ports[$data["id"]]["namep"] = $data["namep"];
				$ports[$data["id"]]["idp"] = $data["id"];
				$ports[$data["id"]]["itemtype"] = $val;
				
			}
		} 
	}
	if ($for)
		return $devices;
	else
		return $ports;
}

?>