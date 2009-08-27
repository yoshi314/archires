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


function plugin_archires_type_Add($querytype,$type,$itemtype,$queries_id){
	GLOBAL  $PLUGIN_ARCHIRES_TYPE_TABLES,$DB;
	
	$obj = new PluginArchiresQueryType();
	
	if ($type!='-1'){
		if (!$obj->GetfromDBbyType($itemtype,$type,$querytype,$queries_id)){

			$obj->add(array(
				'itemtype'=>$itemtype,
				'type'=>$type,
				'querytype'=>$querytype,
				'queries_id'=>$queries_id));
		}
	}else{
			
		$query="SELECT * 
				FROM `".$PLUGIN_ARCHIRES_TYPE_TABLES[$itemtype]."` ";	    
		$result = $DB->query($query);
		$number = $DB->numrows($result);
		$i = 0;
		while($i < $number){
			$type_table=$DB->result($result, $i, "id");
			if (!$obj->GetfromDBbyType($itemtype,$type_table,$querytype,$queries_id)){
				$obj->add(array(
				'itemtype'=>$itemtype,
				'type'=>$type_table,
				'querytype'=>$querytype,
				'queries_id'=>$queries_id));
			}
			$i++;
		}			
	}
}

function plugin_archires_type_Delete($ID){
	
	$obj = new PluginArchiresQueryType();
	$obj->delete(array('id'=>$ID));
			
}

function plugin_archires_image_Device_Add($type,$itemtype,$img){
	GLOBAL  $PLUGIN_ARCHIRES_TYPE_TABLES,$DB;
	
	$obj = new PluginArchiresItemImage();
	
	if ($type!='-1'){
		if ($obj->GetfromDBbyType($itemtype,$type)){

			$obj->update(array(
				'id'=>$obj->fields['id'],
				'img'=>$img));
		} else {

			$obj->add(array(
				'itemtype'=>$itemtype,
				'type'=>$type,
				'img'=>$img));
		}
	}else{
		$query="SELECT * 
				FROM `".$PLUGIN_ARCHIRES_TYPE_TABLES[$itemtype]."` ";	    
		$result = $DB->query($query);
		$number = $DB->numrows($result);
		$i = 0;
		while($i < $number){
			$type_table=$DB->result($result, $i, "id");
			if ($obj->GetfromDBbyType($itemtype,$type_table)){

				$obj->update(array(
					'id'=>$obj->fields['id'],
					'img'=>$img));
			} else {

				$obj->add(array(
					'itemtype'=>$itemtype,
					'type'=>$type_table,
					'img'=>$img));
			}
			$i++;
		}			
	}
}

function plugin_archires_image_Device_Delete($ID){
	
	$obj = new PluginArchiresItemImage();
	$obj->delete(array('id'=>$ID));
		
}

function plugin_archires_color_NetworkInterface_Add($networkinterfaces_id,$color){
	
	GLOBAL $DB;
	
	$obj=new PluginArchiresNetworkInterfaceColor();
	
	if ($networkinterfaces_id!='-1'){
		if ($obj->getFromDBbyNetworkInterface($networkinterfaces_id)){

			$obj->update(array(
			'id'=>$obj->fields['id'],
			'color'=>$color));
		} else {

			$obj->add(array(
			'networkinterfaces_id'=>$networkinterfaces_id,
			'color'=>$color));
		}
	}else{
		$query="SELECT * 
				FROM `glpi_networkinterfaces` ";	    
		$result = $DB->query($query);
		$number = $DB->numrows($result);
		$i = 0;
		while($i < $number){
			$networkinterface_table=$DB->result($result, $i, "id");
			if ($obj->getFromDBbyNetworkInterface($networkinterface_table)){

				$obj->update(array(
				'id'=>$obj->fields['id'],
				'color'=>$color));
			} else {

				$obj->add(array(
				'networkinterfaces_id'=>$networkinterface_table,
				'color'=>$color));
			}
			$i++;
		}			
	}
}

function plugin_archires_color_NetworkInterface_Delete($ID){
	
	$obj = new PluginArchiresNetworkInterfaceColor();
	$obj->delete(array('id'=>$ID));
		
}

function plugin_archires_color_State_Add($state,$color){
	
	GLOBAL $DB;
	
	$obj=new PluginArchiresStateColor();
	
	if ($state!='-1'){
		if ($obj->GetfromDBbyState($state)){

			$obj->update(array(
			'id'=>$obj->fields['id'],
			'color'=>$color));
		} else {

			$obj->add(array(
			'states_id'=>$state,
			'color'=>$color));
		}
	}else{
		$query="SELECT * 
				FROM `glpi_states` ";	    
		$result = $DB->query($query);
		$number = $DB->numrows($result);
		$i = 0;
		while($i < $number){
			$state_table=$DB->result($result, $i, "id");
			if ($obj->GetfromDBbyState($state_table)){

				$obj->update(array(
				'id'=>$obj->fields['id'],
				'color'=>$color));
			} else {

				$obj->add(array(
			'states_id'=>$state_table,
			'color'=>$color));
			}
			$i++;
		}			
	}
}

function plugin_archires_color_State_Delete($ID){
	
	$obj = new PluginArchiresStateColor();
	$obj->delete(array('id'=>$ID));
		
}

function plugin_archires_color_Vlan_Add($vlan,$color){
	
	GLOBAL $DB;
	
	$obj = new PluginArchiresVlanColor();
	
	if ($vlan!='-1'){
		if ($obj->GetfromDBbyVlan($vlan)){

			$obj->update(array(
			'id'=>$obj->fields['id'],
			'color'=>$color));
		} else {

			$obj->add(array(
			'vlans_id'=>$vlan,
			'color'=>$color));
		}
	}else{
		$query="SELECT * 
				FROM `glpi_vlans` ";	    
		$result = $DB->query($query);
		$number = $DB->numrows($result);
		$i = 0;
		while($i < $number){
			$vlan_table=$DB->result($result, $i, "id");
			if ($obj->GetfromDBbyVlan($vlan_table)){

				$obj->update(array(
				'id'=>$obj->fields['id'],
				'color'=>$color));
			} else {

				$obj->add(array(
			'vlans_id'=>$vlan_table,
			'color'=>$color));
			}
			$i++;
		}			
	}
}

function plugin_archires_color_Vlan_Delete($ID){
	
	$obj = new PluginArchiresVlanColor();
	$obj->delete(array('id'=>$ID));
		
}

?>