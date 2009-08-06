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

function plugin_archires_Query_Type_check($type,$ID,$val) {
	
	global $DB,$LINK_ID_TABLE;
	
	$query0="SELECT * 
				FROM `glpi_plugin_archires_query_type` 
				WHERE `type_query` = '".$type."' 
				AND `FK_query` = '".$ID."' 
				AND `device_type` = '" . $val . "';";
	$result0=$DB->query($query0);
	
	$query="";
	
	if ($DB->numrows($result0)>0){
			
			$query = "AND `$LINK_ID_TABLE[$val]`.`type` IN (0 ";	
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
   
   if ($type==PLUGIN_ARCHIRES_LOCATION_QUERY){
		$object= "PluginArchiresQueryLocation";
   }elseif ($type==PLUGIN_ARCHIRES_SWITCH_QUERY){
		$object= "PluginArchiresQuerySwitch";
   }elseif ($type==PLUGIN_ARCHIRES_APPLICATIFS_QUERY){
		$object= "PluginArchiresQueryApplicatifs";
	}
	
   $obj=new $object();
	$obj->getFromDB($ID);
	$FK_config=$obj->fields["FK_config"];
	
	$PluginArchiresConfig=new PluginArchiresConfig;
	$PluginArchiresConfig->getFromDB($FK_config);

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

	 if ($type==PLUGIN_ARCHIRES_LOCATION_QUERY){
		$devices=plugin_archires_display_Query_Location($ID,$obj,$PluginArchiresConfig,true);
		$ports=plugin_archires_display_Query_Location($ID,$obj,$PluginArchiresConfig,false);
   }elseif ($type==PLUGIN_ARCHIRES_SWITCH_QUERY){
		$devices=plugin_archires_display_Query_Switch($ID,$obj,$PluginArchiresConfig,true);
		$ports=plugin_archires_display_Query_Switch($ID,$obj,$PluginArchiresConfig,false);
   }elseif ($type==PLUGIN_ARCHIRES_APPLICATIFS_QUERY){
		$devices=plugin_archires_display_Query_Applicatifs($ID,$obj,$PluginArchiresConfig,true);
		$ports=plugin_archires_display_Query_Applicatifs($ID,$obj,$PluginArchiresConfig,false);
	}
	

	foreach ($devices as $device_type => $typed_devices){

		foreach ($typed_devices as $device_id => $device){

			$device_unique_name = $device_type . "_" . $device_id . "_";
			$device_unique_name .= $device["name"];

			$image_name = plugin_archires_display_Image_Device($device["type"],$device_type,true);

			$url = $CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[$device_type]."?ID=".$device_id;

			echo "<tr class='tab_bg_1'>";
			echo "<td>$device_unique_name</td>";
			echo "<td><div align='center'><img src='$image_name' alt='$image_name'></div></td>";
			echo "<td>" . $device["name"];
			echo "</td>";
			
			echo "<td>";
			echo plugin_archires_display_Type_And_IP($PluginArchiresConfig,$device_type,$device,false);
			echo  "</td>";
			
			echo  "<td>";
			if ($PluginArchiresConfig->fields["display_state"]!=0 && isset($device["state"]) ){
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

	$query = "SELECT `ID`, `end1`, `end2`
	FROM `glpi_networking_wire`";

	if ($result = $DB->query($query)) {
		while ($data = $DB->fetch_array($result)) {
			$wires[$data["ID"]]["end1"] = $data["end1"];
			$wires[$data["ID"]]["end2"] = $data["end2"];
		}
	}

	foreach ($wires as $wire) {
		if (!empty($ports[$wire["end1"]]) && !empty($ports[$wire["end2"]]) && isset($ports[$wire["end1"]]) && isset($ports[$wire["end2"]]) ) {
			$on_device1 = $ports[$wire["end1"]]["on_device"];
			$device_type1 = $ports[$wire["end1"]]["device_type"];
			$logical_number1 = $ports[$wire["end1"]]["logical_number"];
			$name1 = $ports[$wire["end1"]]["namep"];
			$ID1 = $ports[$wire["end1"]]["IDp"];
			$iface1 = $ports[$wire["end1"]]["iface"];
			$ifaddr1 = $ports[$wire["end1"]]["ifaddr"];
			$device_unique_name1 = $device_type1 . "_" . $on_device1 . "_";
			$device_unique_name1 .= $devices[$device_type1][$on_device1]["name"];

			$on_device2 = $ports[$wire["end2"]]["on_device"];
			$device_type2 = $ports[$wire["end2"]]["device_type"];
			$logical_number2 = $ports[$wire["end2"]]["logical_number"];
			$name2 = $ports[$wire["end2"]]["namep"];
			$ID2 = $ports[$wire["end2"]]["IDp"];
			$iface2 = $ports[$wire["end2"]]["iface"];
			$ifaddr2 = $ports[$wire["end2"]]["ifaddr"];
			$device_unique_name2 = $device_type2 . "_" . $on_device2 . "_";
			$device_unique_name2 .= $devices[$device_type2][$on_device2]["name"];


			echo "<tr class='tab_bg_1'>";

			if ($PluginArchiresConfig->fields["display_ports"]!=0 && $PluginArchiresConfig->fields["engine"]!=1){
				$url_ports = $CFG_GLPI["root_doc"] . "/front/networking.port.php?ID=";
				echo  "<td>".$device_unique_name1;		
				echo  " -- " . $device_unique_name2 ."</td>";

				if ($PluginArchiresConfig->fields["display_ip"]!=0)
					echo  "<td>".$ifaddr1."</td>";
				echo  "<td><a href='".$url_ports.$ID1."'>".$name1."</a> - ".$LANG['plugin_archires'][17]." ".$logical_number1."</td>";   
				echo  "<td><div align='center'><img src= \"../pics/socket.png\" alt='../pics/socket.png' /></div></td>";
				echo  "<td><a href='".$url_ports.$ID2."'>".$name2."</a> - ".$LANG['plugin_archires'][17]." ".$logical_number2."</td>";
				if ($PluginArchiresConfig->fields["display_ip"]!=0)
					echo  "<td>".$ifaddr2."</td>";

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

function plugin_archires_generate_Graph_Devices($device,$device_id,$device_type,$format,$image_name,$url,$PluginArchiresConfig) {
	
	global $DB;
	
	$device_unique_name = $device_type . "_" . $device_id . "_";
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
	$graph .= plugin_archires_display_Type_And_IP($PluginArchiresConfig,$device_type,$device,true);
	//entity
	if ($PluginArchiresConfig->fields["display_entity"]!=0 && isset($device["entity"])){
		$graph .="<tr><td>".plugin_archires_Brut(getDropdownName("glpi_entities",$device["entity"]))."</td></tr>";
	}
	//location
	if ($PluginArchiresConfig->fields["display_location"]!=0 && isset($device["location"])){
		$graph .="<tr><td>".plugin_archires_Brut(getDropdownName("glpi_dropdown_locations",$device["location"]))."</td></tr>";
	}

	//state
	if ($PluginArchiresConfig->fields["display_state"]!=0 && isset($device["state"]) ){

		$graph .="<tr><td>".plugin_archires_display_Color_State($device)."</td></tr>";
	}
	
	$graph .= "</table>>";
	//end label
	
	//link - users
	$graph .=plugin_archires_display_Users($url,$device,true);

	$graph .="];\n";
	
	return $graph;
}

function plugin_archires_generate_Graph_Ports($devices,$ports,$wire,$format,$PluginArchiresConfig) {
	
	global $DB,$CFG_GLPI,$LANG;
	
	$PluginArchiresColorIface=new PluginArchiresColorIface();
	$PluginArchiresColorVlan=new PluginArchiresColorVlan();
	
	$on_device1 = $ports[$wire["end1"]]["on_device"];
	$device_type1 = $ports[$wire["end1"]]["device_type"];
	$logical_number1 = $ports[$wire["end1"]]["logical_number"];
	$name1 = $ports[$wire["end1"]]["namep"];
	$ID1 = $ports[$wire["end1"]]["IDp"];
	$iface1 = $ports[$wire["end1"]]["iface"];
	$ifaddr1 = $ports[$wire["end1"]]["ifaddr"];
	$netmask1 = $ports[$wire["end2"]]["netmask"];
	$device_unique_name1 = $device_type1 . "_" . $on_device1 . "_";
	$device_unique_name1 .= $devices[$device_type1][$on_device1]["name"];

	$on_device2 = $ports[$wire["end2"]]["on_device"];
	$device_type2 = $ports[$wire["end2"]]["device_type"];
	$logical_number2 = $ports[$wire["end2"]]["logical_number"];
	$name2 = $ports[$wire["end2"]]["namep"];
	$ID2 = $ports[$wire["end2"]]["IDp"];
	$iface2 = $ports[$wire["end2"]]["iface"];
	$ifaddr2 = $ports[$wire["end2"]]["ifaddr"];
	$netmask2 = $ports[$wire["end2"]]["netmask"];
	$device_unique_name2 = $device_type2 . "_" . $on_device2 . "_";
	$device_unique_name2 .= $devices[$device_type2][$on_device2]["name"];
	
	$graph="";

	if($PluginArchiresConfig->fields["color"] == PLUGIN_ARCHIRES_NETWORK_COLOR ) {
		if (empty($iface1) && empty($iface2)) {
			$graph .= "edge [color=black,arrowsize=1, fontname=\"Verdana\", fontsize=\"5\"];\n";
		}elseif (!empty($iface1)){
			
			if ($PluginArchiresColorIface->GetfromDBbyIface($iface1)){
				$graph .= "edge [color=".$PluginArchiresColorIface->fields["color"].", fontname=\"Verdana\", fontsize=\"5\"];\n";
			}else{
				$graph .= "edge [color=black,arrowsize=1, fontname=\"Verdana\", fontsize=\"5\"];\n";			
			}
		}else{
			if ($PluginArchiresColorIface->GetfromDBbyIface($iface2)){
				$graph .= "edge [color=".$PluginArchiresColorIface->fields["color"].", fontname=\"Verdana\", fontsize=\"5\"];\n";
			}else{
				$graph .= "edge [color=black,arrowsize=1, fontname=\"Verdana\", fontsize=\"5\"];\n";			
			}
		}
	}elseif($PluginArchiresConfig->fields["color"] == PLUGIN_ARCHIRES_VLAN_COLOR ) {
		
		$q = "SELECT `glpi_dropdown_vlan`.`ID` 
		FROM `glpi_dropdown_vlan`, `glpi_networking_vlan` 
		WHERE `glpi_networking_vlan`.`FK_vlan` = `glpi_dropdown_vlan`.`ID` 
		AND `glpi_networking_vlan`.`FK_port` = '$ID1' " ;
		$r=$DB->query($q);
		$nb = $DB->numrows($r);
		if( $r = $DB->query($q)){
			$data_vlan = $DB->fetch_array($r) ;
			$vlan1= $data_vlan["ID"] ;
		}

		$q = "SELECT `glpi_dropdown_vlan`.`ID` 
		FROM `glpi_dropdown_vlan`, `glpi_networking_vlan` 
		WHERE `glpi_networking_vlan`.`FK_vlan` = `glpi_dropdown_vlan`.`ID` 
		AND `glpi_networking_vlan`.`FK_port` = '$ID2' " ;
		$r=$DB->query($q);
		$nb = $DB->numrows($r);
		if( $r = $DB->query($q)){
			$data_vlan = $DB->fetch_array($r) ;
			$vlan2= $data_vlan["ID"] ;
		}

		if (empty($vlan1) && empty($vlan2)) {
			$graph .= "edge [color=black,arrowsize=1, fontname=\"Verdana\", fontsize=\"5\"];\n";
		}elseif (!empty($vlan1)){
			
			if ($PluginArchiresColorVlan->getFromDBbyVlan($vlan1)){
				$graph .= "edge [color=".$PluginArchiresColorVlan->fields["color"].", fontname=\"Verdana\", fontsize=\"5\"];\n";
			}else{
				$graph .= "edge [color=black,arrowsize=1, fontname=\"Verdana\", fontsize=\"5\"];\n";			
			}
		}else{
			if ($PluginArchiresColorVlan->getFromDBbyVlan($vlan2)){
				$graph .= "edge [color=".$PluginArchiresColorVlan->fields["color"].", fontname=\"Verdana\", fontsize=\"5\"];\n";
			}else{
				$graph .= "edge [color=black,arrowsize=1, fontname=\"Verdana\", fontsize=\"5\"];\n";			
			}
		}	 
	}
	//Display Ports
	if ($PluginArchiresConfig->fields["display_ports"]!=0 && $PluginArchiresConfig->fields["engine"]!=1){
		$url_ports = $CFG_GLPI["root_doc"] . "/front/networking.port.php?ID=";
		$graph .= "\"".$device_unique_name1."\"";		
		$graph .= " -- \"".$device_unique_name2."\"[label=";
		$graph .= "<<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\">";

		if ($PluginArchiresConfig->fields["display_ip"]!=0){

			if (!empty($ifaddr1))
				$graph .= "<tr><td>".$ifaddr1;

			if (!empty($ifaddr1)&&!empty($netmask1))
				$graph .= "/".$netmask1."</td></tr>";
			elseif (!empty($ifaddr1) && empty($netmask1))
				$graph .= "</td></tr>";
		}
		$graph .= "<tr><td HREF=\"".$url_ports.$ID1."\" tooltip=\"".$name1."\">".$LANG['plugin_archires'][17]." ".$logical_number1."</td></tr>";

		if ($format!='svg')
			$graph .= "<tr><td><img src= \"pics/socket.png\" /></td></tr>";
		else
			$graph .= "<tr><td><img src=\"".realpath(GLPI_ROOT)."/plugins/archires/pics/socket.png\" /></td></tr>";

		$graph .= "<tr><td HREF=\"".$url_ports.$ID2."\" tooltip=\"".$name2."\">".$LANG['plugin_archires'][17]." ".$logical_number2."</td></tr>";
		
		if ($PluginArchiresConfig->fields["display_ip"]!=0){

			if (!empty($ifaddr2))
				$graph .= "<tr><td>".$ifaddr2;

			if (!empty($ifaddr2)&&!empty($netmask2))
				$graph .= "/".$netmask2."</td></tr>";
			elseif (!empty($ifaddr2) && empty($netmask2))
				$graph .= "</td></tr>";
		}

		$graph .= "</table>>];\n";

	}else{
		$graph .= "\"".$device_unique_name1."\"";
		$graph .= " -- \"".$device_unique_name2."\";\n";
	}
	
	return $graph;
}

function plugin_archires_Create_Graph($format,$type,$ID,$config=0) {
   global $DB,$CFG_GLPI,$LANG,$LINK_ID_TABLE,$INFOFORM_PAGES;
	
   $obj=new $type();
	$obj->getFromDB($ID);
	$FK_config=$obj->fields["FK_config"];

	$PluginArchiresConfig=new PluginArchiresConfig;
	if ($config!=0){
		$PluginArchiresConfig->getFromDB($config);
	}else{
		$PluginArchiresConfig->getFromDB($FK_config);
	}
	$devices = array();
	$ports = array();

	if (isset($obj->fields["location"])){
		$devices=plugin_archires_display_Query_Location($ID,$obj,$PluginArchiresConfig,true);
		$ports=plugin_archires_display_Query_Location($ID,$obj,$PluginArchiresConfig,false);
   }elseif (isset($obj->fields["switch"])){
		$devices=plugin_archires_display_Query_Switch($ID,$obj,$PluginArchiresConfig,true);
		$ports=plugin_archires_display_Query_Switch($ID,$obj,$PluginArchiresConfig,false);
   }elseif (isset($obj->fields["applicatifs"])){
		$devices=plugin_archires_display_Query_Applicatifs($ID,$obj,$PluginArchiresConfig,true);
		$ports=plugin_archires_display_Query_Applicatifs($ID,$obj,$PluginArchiresConfig,false);
	}
	
	$wires = array();

	$query = "SELECT `ID`, `end1`, `end2`
	FROM `glpi_networking_wire`";

	if ($result = $DB->query($query)) {
		while ($data = $DB->fetch_array($result)) {
			$wires[$data["ID"]]["end1"] = $data["end1"];
			$wires[$data["ID"]]["end2"] = $data["end2"];
		}
	}

	$graph = "graph G {\n";
	$graph .= "overlap=false;\n";

	$graph .= "bgcolor=white;\n";

	//items
	$graph .= "node [shape=polygon, sides=6, fontname=\"Verdana\", fontsize=\"5\"];\n";

	foreach ($devices as $device_type => $typed_devices){

		foreach ($typed_devices as $device_id => $device){

			$image_name = plugin_archires_display_Image_Device($device["type"],$device_type,false);

			$url = $CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[$device_type]."?ID=".$device_id;
			
			$graph.=plugin_archires_generate_Graph_Devices($device,$device_id,$device_type,$format,$image_name,$url,$PluginArchiresConfig);
			
		}
	}
    
	foreach ($wires as $wire) {
		if (!empty($ports[$wire["end1"]]) && !empty($ports[$wire["end2"]]) && isset($ports[$wire["end1"]]) && isset($ports[$wire["end2"]]) ) {
			 $graph.=plugin_archires_generate_Graph_Ports($devices,$ports,$wire,$format,$PluginArchiresConfig);
		}
	}

	$graph .= "}\n";

	return plugin_archires_generate_Graphviz($graph,$format,$PluginArchiresConfig);
}


function plugin_archires_generate_Graphviz($graph,$format,$PluginArchiresConfig) {
	
	$Path = GLPI_PLUGIN_DOC_DIR."/archires";
	$graph_name = tempnam($Path, "");
	$output_name = tempnam($Path, "");

	if ($graph_file = fopen($graph_name, "w")) {
		fputs($graph_file, $graph);
		fclose($graph_file);

		if ($PluginArchiresConfig->fields["engine"]!=0) $engine_archires="neato";
		else $engine_archires="dot";

		$command = $engine_archires." -T".$format." -o ".$output_name." ".$graph_name;
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