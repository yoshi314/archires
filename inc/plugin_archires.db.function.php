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


function plugin_archires_type_Add($type_query,$type,$device_type,$query_ID){
	GLOBAL  $PLUGIN_ARCHIRES_TYPE_TABLES,$DB;
	
	$obj = new PluginArchiresQueryType();
	
	if ($type!='-1'){
		if (!$obj->GetfromDBbyType($device_type,$type,$type_query,$query_ID)){

			$obj->add(array(
				'device_type'=>$device_type,
				'type'=>$type,
				'type_query'=>$type_query,
				'FK_query'=>$query_ID));
		}
	}else{
			
		$query="SELECT * 
				FROM `".$PLUGIN_ARCHIRES_TYPE_TABLES[$device_type]."` ";	    
		$result = $DB->query($query);
		$number = $DB->numrows($result);
		$i = 0;
		while($i < $number){
			$type_table=$DB->result($result, $i, "ID");
			if (!$obj->GetfromDBbyType($device_type,$type_table,$type_query,$query_ID)){
				$obj->add(array(
				'device_type'=>$device_type,
				'type'=>$type_table,
				'type_query'=>$type_query,
				'FK_query'=>$query_ID));
			}
			$i++;
		}			
	}
}

function plugin_archires_type_Delete($ID){
	
	$obj = new PluginArchiresQueryType();
	$obj->delete(array('ID'=>$ID));
			
}

function plugin_archires_image_Device_Add($type,$device_type,$img){
	GLOBAL  $PLUGIN_ARCHIRES_TYPE_TABLES,$DB;
	
	$obj = new PluginArchiresImageDevice();
	
	if ($type!='-1'){
		if ($obj->GetfromDBbyType($device_type,$type)){

			$obj->update(array(
				'ID'=>$obj->fields['ID'],
				'img'=>$img));
		} else {

			$obj->add(array(
				'device_type'=>$device_type,
				'type'=>$type,
				'img'=>$img));
		}
	}else{
		$query="SELECT * 
				FROM `".$PLUGIN_ARCHIRES_TYPE_TABLES[$device_type]."` ";	    
		$result = $DB->query($query);
		$number = $DB->numrows($result);
		$i = 0;
		while($i < $number){
			$type_table=$DB->result($result, $i, "ID");
			if ($obj->GetfromDBbyType($device_type,$type_table)){

				$obj->update(array(
					'ID'=>$obj->fields['ID'],
					'img'=>$img));
			} else {

				$obj->add(array(
					'device_type'=>$device_type,
					'type'=>$type_table,
					'img'=>$img));
			}
			$i++;
		}			
	}
}

function plugin_archires_image_Device_Delete($ID){
	
	$obj = new PluginArchiresImageDevice();
	$obj->delete(array('ID'=>$ID));
		
}

function plugin_archires_color_Iface_Add($iface,$color){
	
	GLOBAL $DB;
	
	$obj=new PluginArchiresColorIface();
	
	if ($iface!='-1'){
		if ($obj->GetfromDBbyIface($iface)){

			$obj->update(array(
			'ID'=>$obj->fields['ID'],
			'color'=>$color));
		} else {

			$obj->add(array(
			'iface'=>$iface,
			'color'=>$color));
		}
	}else{
		$query="SELECT * 
				FROM `glpi_dropdown_iface` ";	    
		$result = $DB->query($query);
		$number = $DB->numrows($result);
		$i = 0;
		while($i < $number){
			$iface_table=$DB->result($result, $i, "ID");
			if ($obj->GetfromDBbyIface($iface_table)){

				$obj->update(array(
				'ID'=>$obj->fields['ID'],
				'color'=>$color));
			} else {

				$obj->add(array(
				'iface'=>$iface_table,
				'color'=>$color));
			}
			$i++;
		}			
	}
}

function plugin_archires_color_Iface_Delete($ID){
	
	$obj = new PluginArchiresColorIface();
	$obj->delete(array('ID'=>$ID));
		
}

function plugin_archires_color_State_Add($state,$color){
	
	GLOBAL $DB;
	
	$obj=new PluginArchiresColorState();
	
	if ($state!='-1'){
		if ($obj->GetfromDBbyState($state)){

			$obj->update(array(
			'ID'=>$obj->fields['ID'],
			'color'=>$color));
		} else {

			$obj->add(array(
			'state'=>$state,
			'color'=>$color));
		}
	}else{
		$query="SELECT * 
				FROM `glpi_dropdown_state` ";	    
		$result = $DB->query($query);
		$number = $DB->numrows($result);
		$i = 0;
		while($i < $number){
			$state_table=$DB->result($result, $i, "ID");
			if ($obj->GetfromDBbyState($state_table)){

				$obj->update(array(
				'ID'=>$obj->fields['ID'],
				'color'=>$color));
			} else {

				$obj->add(array(
			'state'=>$state_table,
			'color'=>$color));
			}
			$i++;
		}			
	}
}

function plugin_archires_color_State_Delete($ID){
	
	$obj = new PluginArchiresColorState();
	$obj->delete(array('ID'=>$ID));
		
}

function plugin_archires_color_Vlan_Add($vlan,$color){
	
	GLOBAL $DB;
	
	$obj = new PluginArchiresColorVlan();
	
	if ($vlan!='-1'){
		if ($obj->GetfromDBbyVlan($vlan)){

			$obj->update(array(
			'ID'=>$obj->fields['ID'],
			'color'=>$color));
		} else {

			$obj->add(array(
			'vlan'=>$vlan,
			'color'=>$color));
		}
	}else{
		$query="SELECT * 
				FROM `glpi_dropdown_vlan` ";	    
		$result = $DB->query($query);
		$number = $DB->numrows($result);
		$i = 0;
		while($i < $number){
			$vlan_table=$DB->result($result, $i, "ID");
			if ($obj->GetfromDBbyVlan($vlan_table)){

				$obj->update(array(
				'ID'=>$obj->fields['ID'],
				'color'=>$color));
			} else {

				$obj->add(array(
			'vlan'=>$vlan_table,
			'color'=>$color));
			}
			$i++;
		}			
	}
}

function plugin_archires_color_Vlan_Delete($ID){
	
	$obj = new PluginArchiresColorVlan();
	$obj->delete(array('ID'=>$ID));
		
}

?>