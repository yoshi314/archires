<?php
/*
  ----------------------------------------------------------------------
  GLPI - Gestionnaire Libre de Parc Informatique
  Copyright (C) 2003-2008 by the INDEPNET Development Team.
  
  http://indepnet.net/   http://glpi-project.org/
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

// ----------------------------------------------------------------------
// Original Author of file: GRISARD Jean Marc & CAILLAUD Xavier
// Purpose of file:
// ----------------------------------------------------------------------


function plugin_archires_dropdownLocation($object,$ID) {
	global $DB,$CFG_GLPI,$LANG;
	
	$obj=new $object();
	$locations_id=-1;
	if($obj->getFromDB($ID)){
		$locations_id=$obj->fields["locations_id"];
	}
	$query0 = "SELECT `entities_id` 
				FROM `glpi_locations` ";
	$LINK= " WHERE " ;
	$query0.=getEntitiesRestrictRequest($LINK,"glpi_locations");
	$query0.=" GROUP BY `entities_id`
				ORDER BY `entities_id`";

	echo "<select name=\"locations_id\" size=\"1\"> ";
	echo "<option value='0'>-----</option>\n";
	echo "<option value=\"-1\">".$LANG['plugin_archires'][30]."</option>";

	if($result0 = $DB->query($query0)){

		while($ligne0= mysql_fetch_array($result0)){
			
			echo "<optgroup label=\"".getdropdownname("glpi_entities",$ligne0["entities_id"])."\">";

			$query = "SELECT `id`, `completename` 
			FROM `glpi_locations` ";
			$query.=" WHERE `entities_id` = '".$ligne0["entities_id"]."' ";
			$query.=" ORDER BY `completename` ASC";

			if($result = $DB->query($query)){

				while($ligne= mysql_fetch_array($result)){

					$location=$ligne["completename"];
					$location_id=$ligne["id"];
					echo "<option value='".$location_id."' ".($location_id=="".$locations_id.""?" selected ":"").">".$location."</option>";
				}
			}
			echo "</optgroup>";
		}
	} 
	echo "</select>";
}
 	        
function plugin_archires_dropdownView($object,$ID) {
	global $DB,$CFG_GLPI;
	
	$views_id=-1;
	$obj=new $object();
	if($obj->getFromDB($ID)){
		$views_id=$obj->fields["views_id"];
	}
	$query = "SELECT `id`, `name` 
			FROM `glpi_plugin_archires_views` 
			WHERE `is_deleted` = '0' 
			AND `entities_id` = '" . $_SESSION["glpiactive_entity"] . "' 
			ORDER BY `name` ASC";
	echo "<select name='views_id' size=\"1\"> ";
	echo "<option value='0'>-----</option>\n";
	if($result = $DB->query($query)){
		while($ligne= mysql_fetch_array($result)){
			$view_name=$ligne["name"];
			$view_id=$ligne["id"];
			echo "<option value='".$view_id."' ".($view_id=="".$views_id.""?" selected ":"").">".$view_name."</option>";
		} 
	} 
	echo "</select>"; 

}

function plugin_archires_dropdownAllItems($myname,$value_type=0,$value=0,$entity_restrict=-1,$types='') {
	global $DB,$LANG,$CFG_GLPI,$PLUGIN_ARCHIRES_TYPE_TABLES;
		    
	$rand=mt_rand();
	$ci=new CommonItem();

	echo "<table border='0'><tr><td>\n";

	echo "<select name='type' id='item_type$rand'>\n";
	echo "<option value='0;0'>-----</option>\n";
	
	echo "<option value='".COMPUTER_TYPE.";".$PLUGIN_ARCHIRES_TYPE_TABLES[COMPUTER_TYPE]."'>".$LANG['Menu'][0]."</option>\n";
	echo "<option value='".NETWORKING_TYPE.";".$PLUGIN_ARCHIRES_TYPE_TABLES[NETWORKING_TYPE]."'>".$LANG['Menu'][1]."</option>\n";
	echo "<option value='".PRINTER_TYPE.";".$PLUGIN_ARCHIRES_TYPE_TABLES[PRINTER_TYPE]."'>".$LANG['Menu'][2]."</option>\n";
	echo "<option value='".PERIPHERAL_TYPE.";".$PLUGIN_ARCHIRES_TYPE_TABLES[PERIPHERAL_TYPE]."'>".$LANG['Menu'][16]."</option>\n";
	echo "<option value='".PHONE_TYPE.";".$PLUGIN_ARCHIRES_TYPE_TABLES[PHONE_TYPE]."'>".$LANG['Menu'][34]."</option>\n";
	echo "</select>";

	$params=array('idtable'=>'__VALUE__',
		'value'=>$value,
		'myname'=>$myname,
		'entity_restrict'=>$entity_restrict,
		);
	ajaxUpdateItemOnSelectEvent("item_type$rand","show_$myname$rand",$CFG_GLPI["root_doc"]."/plugins/archires/ajax/dropdownAllItems.php",$params);

	echo "</td><td>\n"	;
	echo "<span id='show_$myname$rand'>&nbsp;</span>\n";
	echo "</td></tr></table>\n";

	if ($value>0){
		echo "<script type='text/javascript' >\n";
		echo "document.getElementById('item_type$rand').value='".$value_type."';";
		echo "</script>\n";

		$params["idtable"]=$value_type;
		ajaxUpdateItem("show_$myname$rand",$CFG_GLPI["root_doc"]."/plugins/archires/ajax/dropdownAllItems.php",$params);
		
	}
	return $rand;
}

function plugin_archires_getClassType ($type){
	
		if ($type==PLUGIN_ARCHIRES_LOCATIONS_QUERY){
      $object= "PluginArchiresQueryLocation";
     }elseif ($type==PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY){
      $object= "PluginArchiresQueryNetworkEquipment";
     }elseif ($type==PLUGIN_ARCHIRES_APPLIANCES_QUERY){
      $object= "PluginArchiresQueryAppliance";
    }
    
  return $object;

}

function plugin_archires_getDeviceType ($devicetype){
		global $LANG;
	
		switch ($devicetype){
			
			case COMPUTER_TYPE :	
				return $LANG['computers'][44];
				break;
			case NETWORKING_TYPE :
				return $LANG['help'][26];
				break;
			case PRINTER_TYPE :
				return $LANG['help'][27];
				break;
			case PERIPHERAL_TYPE : 
				return $LANG['help'][29];
				break;				
			case PHONE_TYPE : 
				return $LANG['help'][35];
				break;				
			
		}

}

function plugin_archires_getType($device_type,$type)
{
	global $DB,$PLUGIN_ARCHIRES_TYPE_TABLES;
	
	$name="";
	if(isset($PLUGIN_ARCHIRES_TYPE_TABLES[$device_type])){
	
		$query="SELECT `name` 
					FROM `".$PLUGIN_ARCHIRES_TYPE_TABLES[$device_type]."` 
					WHERE `id` = '$type' ";
		$result = $DB->query($query);
		$number = $DB->numrows($result);
		if($number !="0")
			$name=$DB->result($result, 0, "name");
	}
	return $name;
}

function plugin_archires_dropdownColors_NetworkInterface($used=array()) {
	global $DB,$LANG,$CFG_GLPI;

	$limit=$_SESSION["glpidropdown_chars_limit"];
  
  $where="";
  
  if (count($used)) {
		$where .= "WHERE id NOT IN (0";
		foreach ($used as $ID)
			$where .= ",$ID";
		$where .= ")";
	}
	
	$query="SELECT * 
			FROM `glpi_networkinterfaces`
			$where
			ORDER BY `name`";
	$result=$DB->query($query);
	$number = $DB->numrows($result);

	if($number>0){
		echo "<select name='networkinterfaces_id'>\n";
		echo "<option value='0'>-----</option>\n";
		echo "<option value='-1'>".$LANG['plugin_archires'][21]."</option>\n";
		while($data= mysql_fetch_array($result)){
			$output=$data["name"];
			if (utf8_strlen($output)>$limit) {
				$output=utf8_substr($output,0,$limit)."&hellip;";
			}
			echo "<option value='".$data["id"]."'>".$output."</option>";
		}
		echo "</select>";
	}	
}

function plugin_archires_dropdowncolors_Vlan($used=array()) {
	global $DB,$LANG,$CFG_GLPI;
  
  $limit=$_SESSION["glpidropdown_chars_limit"];
  
  $where="";
  
  if (count($used)) {
		$where .= "WHERE id NOT IN (0";
		foreach ($used as $ID)
			$where .= ",$ID";
		$where .= ")";
	}
	
	$query="SELECT * 
			FROM `glpi_vlans` 
			$where
			ORDER BY `name`";
	$result=$DB->query($query);
	$number = $DB->numrows($result);

	if($number !="0"){
	echo "<select name='vlans_id'>\n";
	echo "<option value='0'>-----</option>\n";
	echo "<option value='-1'>".$LANG['plugin_archires'][36]."</option>\n";
	while($data= mysql_fetch_array($result)){
		$output=$data["name"];
    if (utf8_strlen($output)>$limit) {
      $output=utf8_substr($output,0,$limit)."&hellip;";
    }
    echo "<option value='".$data["id"]."'>".$output."</option>";
	}
	echo "</select>";

	}	
}

function plugin_archires_dropdownColors_State($used=array()) {
	global $DB,$LANG,$CFG_GLPI;
  
  $limit=$_SESSION["glpidropdown_chars_limit"];
  
  $where="";
  
  if (count($used)) {
		$where .= "WHERE id NOT IN (0";
		foreach ($used as $ID)
			$where .= ",$ID";
		$where .= ")";
	}
	
	$query="SELECT * 
			FROM `glpi_states` 
			$where
			ORDER BY `name`";
	$result=$DB->query($query);
	$number = $DB->numrows($result);

	if($number !="0"){
	echo "<select name='states_id'>\n";
	echo "<option value='0'>-----</option>\n";
	echo "<option value='-1'>".$LANG['plugin_archires'][15]."</option>\n";
	while($data= mysql_fetch_array($result)){
		$output=$data["name"];
    if (utf8_strlen($output)>$limit) {
      $output=utf8_substr($output,0,$limit)."&hellip;";
    }
    echo "<option value='".$data["id"]."'>".$output."</option>";

	}
	echo "</select>";

	}	
}

function plugin_archires_getVlanbyNetworkPort ($ID){
	global $DB;
	
		$q = "SELECT `glpi_vlans`.`id` 
		FROM `glpi_vlans`, `glpi_networkports_vlans` 
		WHERE `glpi_networkports_vlans`.`vlans_id` = `glpi_vlans`.`id` 
		AND `glpi_networkports_vlans`.`networkports_id` = '$ID' " ;
		$r=$DB->query($q);
		$nb = $DB->numrows($r);
		if( $r = $DB->query($q)){
			$data_vlan = $DB->fetch_array($r) ;
			$vlan= $data_vlan["id"] ;
		}
    
  return $vlan;

}

?>