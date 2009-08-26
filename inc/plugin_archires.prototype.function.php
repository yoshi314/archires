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

function plugin_archires_Query_Type_check($querytype,$views_id,$val) {
	
	global $DB,$LINK_ID_TABLE;
	
	$query0="SELECT * 
				FROM `glpi_plugin_archires_query_types` 
				WHERE `querytype` = '".$querytype."' 
				AND `queries_id` = '".$views_id."' 
				AND `itemtype` = '" . $val . "';";
	$result0=$DB->query($query0);
	
	$query="";
	
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
    
	if ($DB->numrows($result0)>0){
			
			$query = "AND `$LINK_ID_TABLE[$val]`.`$typefield` IN (0 ";	
			while ($data0=$DB->fetch_array($result0)){
				$type_where=",'".$data0["type"]."' ";
				$query .= " $type_where ";
			}
		$query .= ") ";
	}
	
	return $query;
}

function plugin_archires_query_Test($type,$ID) {
	
	global $DB,$CFG_GLPI,$LANG,$LINK_ID_TABLE,$INFOFORM_PAGES;
   
   if ($type==PLUGIN_ARCHIRES_LOCATIONS_QUERY){
		$object= "PluginArchiresQueryLocation";
   }elseif ($type==PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY){
		$object= "PluginArchiresQueryNetworkEquipment";
   }elseif ($type==PLUGIN_ARCHIRES_APPLIANCES_QUERY){
		$object= "PluginArchiresQueryAppliance";
	}
	
   $obj=new $object();
	$obj->getFromDB($ID);
	$views_id=$obj->fields["views_id"];
	
	$PluginArchiresView=new PluginArchiresView;
	$PluginArchiresView->getFromDB($views_id);

   $devices = array();
   $ports = array();

	echo "<br><div align='center'>";
	echo "<table class='tab_cadrehov' border='0' cellpadding='2' width='75%'>";
	echo "<tr><th colspan='6'>".$LANG['plugin_archires']['test'][2]."</th></tr>";
	echo "<tr><th>".$LANG['plugin_archires']['test'][4]."</th>";
	echo "<th>".$LANG['plugin_archires']['test'][5]."</th>";
	echo "<th>".$LANG['plugin_archires']['test'][6]."</th>";
	echo "<th>".$LANG['plugin_archires']['test'][7]."</th>";
	echo "<th>".$LANG['plugin_archires']['test'][8]."</th>";
	echo "<th>".$LANG['plugin_archires']['test'][9]."</th></tr>";

	 if ($type==PLUGIN_ARCHIRES_LOCATIONS_QUERY){
		$devices=plugin_archires_display_Query_Location($ID,$obj,$PluginArchiresView,true);
		$ports=plugin_archires_display_Query_Location($ID,$obj,$PluginArchiresView,false);
   }elseif ($type==PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY){
		$devices=plugin_archires_display_Query_NetworkEquipment($ID,$obj,$PluginArchiresView,true);
		$ports=plugin_archires_display_Query_NetworkEquipment($ID,$obj,$PluginArchiresView,false);
   }elseif ($type==PLUGIN_ARCHIRES_APPLIANCES_QUERY){
		$devices=plugin_archires_display_Query_Appliance($ID,$obj,$PluginArchiresView,true);
		$ports=plugin_archires_display_Query_Appliance($ID,$obj,$PluginArchiresView,false);
	}
	

	foreach ($devices as $itemtype => $typed_devices){

		foreach ($typed_devices as $device_id => $device){

			$device_unique_name = $itemtype . "_" . $device_id . "_";
			$device_unique_name .= $device["name"];

			$image_name = plugin_archires_display_Image_Device($device["type"],$itemtype,true);

			$url = $CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[$itemtype]."?id=".$device_id;

			echo "<tr class='tab_bg_1'>";
			echo "<td>$device_unique_name</td>";
			echo "<td><div align='center'><img src='$image_name' alt='$image_name'></div></td>";
			echo "<td>" . $device["name"];
			echo "</td>";
			
			echo "<td>";
			echo plugin_archires_display_Type_And_IP($PluginArchiresView,$itemtype,$device,false);
			echo  "</td>";
			
			echo  "<td>";
			if ($PluginArchiresView->fields["display_state"]!=0 && isset($device["states_id"]) ){
				echo plugin_archires_display_Color_State($device);
			}
			echo  "</td>";
			
			echo  "<td>";
			echo plugin_archires_display_Users($url,$device,false);
			echo  "</td>";
			
			echo  "</tr>";
		}
	}

	echo "</table>";

	echo "<br><table class='tab_cadrehov' border='0' cellpadding='2' width='75%'>";
	echo "<tr><th colspan='6'>".$LANG['plugin_archires']['test'][3]."</th></tr>";
	echo "<tr><th>".$LANG['plugin_archires']['test'][10]."</th>";
	echo "<th>".$LANG['plugin_archires']['test'][11]."</th>";
	echo "<th>".$LANG['plugin_archires']['test'][12]."</th>";
	echo "<th>".$LANG['plugin_archires']['test'][5]."</th>";
	echo "<th>".$LANG['plugin_archires']['test'][13]."</th>";
	echo "<th>".$LANG['plugin_archires']['test'][14]."</th></tr>";

	$wires = array();

	$query = "SELECT `id`, `networkports_id_1`, `networkports_id_2`
	FROM `glpi_networkports_networkports`";

	if ($result = $DB->query($query)) {
		while ($data = $DB->fetch_array($result)) {
			$wires[$data["id"]]["networkports_id_1"] = $data["networkports_id_1"];
			$wires[$data["id"]]["networkports_id_2"] = $data["networkports_id_2"];
		}
	}

	foreach ($wires as $wire) {
		if (isset($ports[$wire["networkports_id_1"]]) && !empty($ports[$wire["networkports_id_1"]]) && isset($ports[$wire["networkports_id_2"]]) && !empty($ports[$wire["networkports_id_2"]])) {
			$items_id1 = $ports[$wire["networkports_id_1"]]["items_id"];
			$itemtype1 = $ports[$wire["networkports_id_1"]]["itemtype"];
			$logical_number1 = $ports[$wire["networkports_id_1"]]["logical_number"];
			$name1 = $ports[$wire["networkports_id_1"]]["namep"];
			$ID1 = $ports[$wire["networkports_id_1"]]["idp"];
			$networkinterfaces_id1 = $ports[$wire["networkports_id_1"]]["networkinterfaces_id"];
			$ip1 = $ports[$wire["networkports_id_1"]]["ip"];
			$device_unique_name1 = $itemtype1 . "_" . $items_id1 . "_";
			$device_unique_name1 .= $devices[$itemtype1][$items_id1]["name"];

			$items_id2 = $ports[$wire["networkports_id_2"]]["items_id"];
			$itemtype2 = $ports[$wire["networkports_id_2"]]["itemtype"];
			$logical_number2 = $ports[$wire["networkports_id_2"]]["logical_number"];
			$name2 = $ports[$wire["networkports_id_2"]]["namep"];
			$ID2 = $ports[$wire["networkports_id_2"]]["idp"];
			$networkinterfaces_id2 = $ports[$wire["networkports_id_2"]]["networkinterfaces_id"];
			$ip2 = $ports[$wire["networkports_id_2"]]["ip"];
			$device_unique_name2 = $itemtype2 . "_" . $items_id2 . "_";
			$device_unique_name2 .= $devices[$itemtype2][$items_id2]["name"];


			echo "<tr class='tab_bg_1'>";

			if ($PluginArchiresView->fields["display_ports"]!=0 && $PluginArchiresView->fields["engine"]!=1){
				$url_ports = $CFG_GLPI["root_doc"] . "/front/networking.port.php?id=";
				echo  "<td>".$device_unique_name1;		
				echo  " -- " . $device_unique_name2 ."</td>";

				if ($PluginArchiresView->fields["display_ip"]!=0)
					echo  "<td>".$ip1."</td>";
				echo  "<td><a href='".$url_ports.$ID1."'>".$name1."</a> - ".$LANG['plugin_archires'][17]." ".$logical_number1."</td>";   
				echo  "<td><div align='center'><img src= \"../pics/socket.png\" alt='../pics/socket.png' /></div></td>";
				echo  "<td><a href='".$url_ports.$ID2."'>".$name2."</a> - ".$LANG['plugin_archires'][17]." ".$logical_number2."</td>";
				if ($PluginArchiresView->fields["display_ip"]!=0)
					echo  "<td>".$ip2."</td>";

			}else{

				echo  "<td>".$device_unique_name1;
				echo  " -- ".$device_unique_name2 ."</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
			}
		}
	}		
	echo "</tr></table>";

	echo "<br><table class='tab_cadre' border='0' cellpadding='2'>";
	echo "<tr><th>".$LANG['plugin_archires']['test'][1]."</th></tr>";
	echo "<tr class='tab_bg_1'><td>";
	echo "<img src=\"../test.php\" alt=\"\">";
	echo "</td></tr>";
	echo "</table>";

	echo "</div>";
}

function plugin_archires_generate_Graph_Devices($device,$device_id,$itemtype,$format,$image_name,$url,$PluginArchiresView) {
	
	global $DB;
	
	$device_unique_name = $itemtype . "_" . $device_id . "_";
	$device_unique_name .= $device["name"];
			
	$graph = "\"".$device_unique_name."\"[shape=plaintext, label=";
	//label
	$graph .= "<<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
	
	//img
	if ($format!='svg')
		$graph .= "<tr><td><img src=\"".$image_name."\"/></td></tr>";
	else
		$graph .= "<tr><td><img src=\"".realpath(GLPI_ROOT)."/plugins/archires/".$image_name. "\"/></td></tr>";

	$graph .= "<tr><td> </td></tr><tr><td>".$device["name"];
	//ip / type
	$graph .= plugin_archires_display_Type_And_IP($PluginArchiresView,$itemtype,$device,true);
	//entity
	if ($PluginArchiresView->fields["display_entity"]!=0 && isset($device["entity"])){
		$graph .="<tr><td>".plugin_archires_Brut(getDropdownName("glpi_entities",$device["entity"]))."</td></tr>";
	}
	//location
	if ($PluginArchiresView->fields["display_location"]!=0 && isset($device["locations_id"])){
		$graph .="<tr><td>".plugin_archires_Brut(getDropdownName("glpi_locations",$device["locations_id"]))."</td></tr>";
	}

	//state
	if ($PluginArchiresView->fields["display_state"]!=0 && isset($device["states_id"]) ){

		$graph .="<tr><td>".plugin_archires_display_Color_State($device)."</td></tr>";
	}
	
	$graph .= "</table>>";
	//end label
	
	//link - users
	$graph .=plugin_archires_display_Users($url,$device,true);

	$graph .="];\n";
	
	return $graph;
}

function plugin_archires_generate_Graph_Ports($devices,$ports,$wire,$format,$PluginArchiresView) {
	
	global $DB,$CFG_GLPI,$LANG;
	
	$PluginArchiresNetworkInterfaceColor=new PluginArchiresNetworkInterfaceColor();
	$PluginArchiresVlanColor=new PluginArchiresVlanColor();
	
	$items_id1 = $ports[$wire["networkports_id_1"]]["items_id"];
	$itemtype1 = $ports[$wire["networkports_id_1"]]["itemtype"];
	$logical_number1 = $ports[$wire["networkports_id_1"]]["logical_number"];
	$name1 = $ports[$wire["networkports_id_1"]]["namep"];
	$ID1 = $ports[$wire["networkports_id_1"]]["idp"];
	$networkinterfaces_id1 = $ports[$wire["networkports_id_1"]]["networkinterfaces_id"];
	$ip1 = $ports[$wire["networkports_id_1"]]["ip"];
	$netmask1 = $ports[$wire["networkports_id_2"]]["netmask"];
	$device_unique_name1 = $itemtype1 . "_" . $items_id1 . "_";
	$device_unique_name1 .= $devices[$itemtype1][$items_id1]["name"];

	$items_id2 = $ports[$wire["networkports_id_2"]]["items_id"];
	$itemtype2 = $ports[$wire["networkports_id_2"]]["itemtype"];
	$logical_number2 = $ports[$wire["networkports_id_2"]]["logical_number"];
	$name2 = $ports[$wire["networkports_id_2"]]["namep"];
	$ID2 = $ports[$wire["networkports_id_2"]]["idp"];
	$networkinterfaces_id2 = $ports[$wire["networkports_id_2"]]["networkinterfaces_id"];
	$ip2 = $ports[$wire["networkports_id_2"]]["ip"];
	$netmask2 = $ports[$wire["networkports_id_2"]]["netmask"];
	$device_unique_name2 = $itemtype2 . "_" . $items_id2 . "_";
	$device_unique_name2 .= $devices[$itemtype2][$items_id2]["name"];
	
	$graph="";

	if($PluginArchiresView->fields["color"] == PLUGIN_ARCHIRES_NETWORK_COLOR ) {
		if (empty($networkinterfaces_id1) && empty($networkinterfaces_id2)) {
			$graph .= "edge [color=black,arrowsize=1, fontname=\"Verdana\", fontsize=\"5\"];\n";
		}elseif (!empty($networkinterfaces_id1)){
			
			if ($PluginArchiresNetworkInterfaceColor->getFromDBbyIface($networkinterfaces_id1)){
				$graph .= "edge [color=".$PluginArchiresNetworkInterfaceColor->fields["color"].", fontname=\"Verdana\", fontsize=\"5\"];\n";
			}else{
				$graph .= "edge [color=black,arrowsize=1, fontname=\"Verdana\", fontsize=\"5\"];\n";			
			}
		}else{
			if ($PluginArchiresNetworkInterfaceColor->getFromDBbyIface($networkinterfaces_id2)){
				$graph .= "edge [color=".$PluginArchiresNetworkInterfaceColor->fields["color"].", fontname=\"Verdana\", fontsize=\"5\"];\n";
			}else{
				$graph .= "edge [color=black,arrowsize=1, fontname=\"Verdana\", fontsize=\"5\"];\n";			
			}
		}
	}elseif($PluginArchiresView->fields["color"] == PLUGIN_ARCHIRES_VLAN_COLOR ) {
		
		$q = "SELECT `glpi_vlans`.`id` 
		FROM `glpi_vlans`, `glpi_networkports_vlans` 
		WHERE `glpi_networkports_vlans`.`vlans_id` = `glpi_vlans`.`id` 
		AND `glpi_networkports_vlans`.`networkports_id` = '$ID1' " ;
		$r=$DB->query($q);
		$nb = $DB->numrows($r);
		if( $r = $DB->query($q)){
			$data_vlan = $DB->fetch_array($r) ;
			$vlan1= $data_vlan["id"] ;
		}

		$q = "SELECT `glpi_vlans`.`id` 
		FROM `glpi_vlans`, `glpi_networkports_vlans` 
		WHERE `glpi_networkports_vlans`.`vlans_id` = `glpi_vlans`.`id` 
		AND `glpi_networkports_vlans`.`networkports_id` = '$ID2' " ;
		$r=$DB->query($q);
		$nb = $DB->numrows($r);
		if( $r = $DB->query($q)){
			$data_vlan = $DB->fetch_array($r) ;
			$vlan2= $data_vlan["id"] ;
		}

		if (empty($vlan1) && empty($vlan2)) {
			$graph .= "edge [color=black,arrowsize=1, fontname=\"Verdana\", fontsize=\"5\"];\n";
		}elseif (!empty($vlan1)){
			
			if ($PluginArchiresVlanColor->getFromDBbyVlan($vlan1)){
				$graph .= "edge [color=".$PluginArchiresVlanColor->fields["color"].", fontname=\"Verdana\", fontsize=\"5\"];\n";
			}else{
				$graph .= "edge [color=black,arrowsize=1, fontname=\"Verdana\", fontsize=\"5\"];\n";			
			}
		}else{
			if ($PluginArchiresVlanColor->getFromDBbyVlan($vlan2)){
				$graph .= "edge [color=".$PluginArchiresVlanColor->fields["color"].", fontname=\"Verdana\", fontsize=\"5\"];\n";
			}else{
				$graph .= "edge [color=black,arrowsize=1, fontname=\"Verdana\", fontsize=\"5\"];\n";			
			}
		}	 
	}
	//Display Ports
	if ($PluginArchiresView->fields["display_ports"]!=0 && $PluginArchiresView->fields["engine"]!=1){
		$url_ports = $CFG_GLPI["root_doc"] . "/front/networking.port.php?id=";
		$graph .= "\"".$device_unique_name1."\"";		
		$graph .= " -- \"".$device_unique_name2."\"[label=";
		$graph .= "<<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\">";

		if ($PluginArchiresView->fields["display_ip"]!=0){

			if (!empty($ip1))
				$graph .= "<tr><td>".$ip1;

			if (!empty($ip1)&&!empty($netmask1))
				$graph .= "/".$netmask1."</td></tr>";
			elseif (!empty($ip1) && empty($netmask1))
				$graph .= "</td></tr>";
		}
		$graph .= "<tr><td HREF=\"".$url_ports.$ID1."\" tooltip=\"".$name1."\">".$LANG['plugin_archires'][17]." ".$logical_number1."</td></tr>";

		if ($format!='svg')
			$graph .= "<tr><td><img src= \"pics/socket.png\" /></td></tr>";
		else
			$graph .= "<tr><td><img src=\"".realpath(GLPI_ROOT)."/plugins/archires/pics/socket.png\" /></td></tr>";

		$graph .= "<tr><td HREF=\"".$url_ports.$ID2."\" tooltip=\"".$name2."\">".$LANG['plugin_archires'][17]." ".$logical_number2."</td></tr>";
		
		if ($PluginArchiresView->fields["display_ip"]!=0){

			if (!empty($ip2))
				$graph .= "<tr><td>".$ip2;

			if (!empty($ip2)&&!empty($netmask2))
				$graph .= "/".$netmask2."</td></tr>";
			elseif (!empty($ip2) && empty($netmask2))
				$graph .= "</td></tr>";
		}

		$graph .= "</table>>];\n";

	}else{
		$graph .= "\"".$device_unique_name1."\"";
		$graph .= " -- \"".$device_unique_name2."\";\n";
	}
	
	return $graph;
}

function plugin_archires_Create_Graph($format,$type,$ID,$view=0) {
   global $DB,$CFG_GLPI,$LANG,$LINK_ID_TABLE,$INFOFORM_PAGES;
	
  $obj=new $type();
	$obj->getFromDB($ID);
	$views_id=$obj->fields["views_id"];

	$PluginArchiresView=new PluginArchiresView;
	if ($view!=0){
		$PluginArchiresView->getFromDB($view);
	}else{
		$PluginArchiresView->getFromDB($views_id);
	}
	$devices = array();
	$ports = array();

	if (isset($obj->fields["locations_id"])){
		$devices=plugin_archires_display_Query_Location($ID,$obj,$PluginArchiresView,true);
		$ports=plugin_archires_display_Query_Location($ID,$obj,$PluginArchiresView,false);
   }elseif (isset($obj->fields["networkequipments_id"])){
		$devices=plugin_archires_display_Query_NetworkEquipment($ID,$obj,$PluginArchiresView,true);
		$ports=plugin_archires_display_Query_NetworkEquipment($ID,$obj,$PluginArchiresView,false);
   }elseif (isset($obj->fields["appliances_id"])){
		$devices=plugin_archires_display_Query_Appliance($ID,$obj,$PluginArchiresView,true);
		$ports=plugin_archires_display_Query_Appliance($ID,$obj,$PluginArchiresView,false);
	}
	
	$wires = array();

	$query = "SELECT `id`, `networkports_id_1`, `networkports_id_2`
	FROM `glpi_networkports_networkports`";

	if ($result = $DB->query($query)) {
		while ($data = $DB->fetch_array($result)) {
			$wires[$data["id"]]["networkports_id_1"] = $data["networkports_id_1"];
			$wires[$data["id"]]["networkports_id_2"] = $data["networkports_id_2"];
		}
	}

	$graph = "graph G {\n";
	$graph .= "overlap=false;\n";

	$graph .= "bgcolor=white;\n";

	//items
	$graph .= "node [shape=polygon, sides=6, fontname=\"Verdana\", fontsize=\"5\"];\n";

	foreach ($devices as $itemtype => $typed_devices){

		foreach ($typed_devices as $device_id => $device){

			$image_name = plugin_archires_display_Image_Device($device["type"],$itemtype,false);

			$url = $CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[$itemtype]."?id=".$device_id;
			
			$graph.=plugin_archires_generate_Graph_Devices($device,$device_id,$itemtype,$format,$image_name,$url,$PluginArchiresView);
			
		}
	}
    
	foreach ($wires as $wire) {
		if (!empty($ports[$wire["networkports_id_1"]]) && !empty($ports[$wire["networkports_id_2"]]) && isset($ports[$wire["networkports_id_1"]]) && isset($ports[$wire["networkports_id_2"]]) ) {
			 $graph.=plugin_archires_generate_Graph_Ports($devices,$ports,$wire,$format,$PluginArchiresView);
		}
	}

	$graph .= "}\n";

	return plugin_archires_generate_Graphviz($graph,$format,$PluginArchiresView);
}


function plugin_archires_generate_Graphviz($graph,$format,$PluginArchiresView) {
	
	$Path = GLPI_PLUGIN_DOC_DIR."/archires";
	$graph_name = tempnam($Path, "");
	$output_name = tempnam($Path, "");
  
	if ($graph_file = fopen($graph_name, "w")) {
		fputs($graph_file, $graph);
		fclose($graph_file);

		if ($PluginArchiresView->fields["engine"]!=0) $engine_archires="neato";
		else $engine_archires="dot";

		//$command = $engine_archires." -T".$format." -o ".$output_name." ".$graph_name;
    $command = $engine_archires." -T" .$format." -o \"".$output_name ."\" \"".$graph_name."\"";
		`$command`;

		unlink($graph_name);

		if ($output_file = fopen($output_name, "rb")) {
			$output_data = fread($output_file, filesize($output_name));
			fclose($output_file);
			unlink($output_name);

			return $output_data;
		}
	}
}

?>