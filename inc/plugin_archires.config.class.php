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

class PluginArchiresItemImage extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_imageitems";
	}

	function getFromDBbyType($itemtype, $type) {
		global $DB;
		$query = "SELECT * FROM `".$this->table."` " .
			"WHERE (`itemtype` = '" . $itemtype . "') " .
				"AND (`type` = '" . $type . "')";
		if ($result = $DB->query($query)) {
			if ($DB->numrows($result) != 1) {
				return false;
			}
			$this->fields = $DB->fetch_assoc($result);
			if (is_array($this->fields) && count($this->fields)) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}
	
	function addItemImage($type,$itemtype,$img){
    GLOBAL  $PLUGIN_ARCHIRES_TYPE_TABLES,$DB;
    
    if ($type!='-1'){
      if ($this->GetfromDBbyType($itemtype,$type)){

        $this->update(array(
          'id'=>$this->fields['id'],
          'img'=>$img));
      } else {

        $this->add(array(
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
        if ($this->GetfromDBbyType($itemtype,$type_table)){

          $this->update(array(
            'id'=>$this->fields['id'],
            'img'=>$img));
        } else {

          $this->add(array(
            'itemtype'=>$itemtype,
            'type'=>$type_table,
            'img'=>$img));
        }
        $i++;
      }			
    }
  }

  function deleteItemImage($ID){
    
    $this->delete(array('id'=>$ID));
      
  }

}

class PluginArchiresNetworkInterfaceColor extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_networkinterfacescolors";
	}

	function getFromDBbyNetworkInterface($networkinterfaces_id) {
		global $DB;
		$query = "SELECT * FROM `".$this->table."`
					WHERE `networkinterfaces_id` = '" . $networkinterfaces_id . "' ";
		if ($result = $DB->query($query)) {
			if ($DB->numrows($result) != 1) {
				return false;
			}
			$this->fields = $DB->fetch_assoc($result);
			if (is_array($this->fields) && count($this->fields)) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}
	
	function addNetworkInterfaceColor($networkinterfaces_id,$color){
	
	GLOBAL $DB;
    
    if ($networkinterfaces_id!='-1'){
      if ($this->getFromDBbyNetworkInterface($networkinterfaces_id)){

        $this->update(array(
        'id'=>$this->fields['id'],
        'color'=>$color));
      } else {

        $this->add(array(
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
        if ($this->getFromDBbyNetworkInterface($networkinterface_table)){

          $this->update(array(
          'id'=>$this->fields['id'],
          'color'=>$color));
        } else {

          $this->add(array(
          'networkinterfaces_id'=>$networkinterface_table,
          'color'=>$color));
        }
        $i++;
      }			
    }
  }

  function deleteNetworkInterfaceColor($ID){
    
    $this->delete(array('id'=>$ID));
      
  }
}

class PluginArchiresVlanColor extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_vlanscolors";
	}

	function getFromDBbyVlan($vlan) {
		global $DB;
		$query = "SELECT * FROM `".$this->table."`
					WHERE `vlans_id` = '" . $vlan . "' ";
		if ($result = $DB->query($query)) {
			if ($DB->numrows($result) != 1) {
				return false;
			}
			$this->fields = $DB->fetch_assoc($result);
			if (is_array($this->fields) && count($this->fields)) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}
	
	function addVlanColor($vlan,$color){
	
    GLOBAL $DB;
    
    if ($vlan!='-1'){
      if ($this->GetfromDBbyVlan($vlan)){

        $this->update(array(
        'id'=>$this->fields['id'],
        'color'=>$color));
      } else {

        $this->add(array(
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
        if ($this->GetfromDBbyVlan($vlan_table)){

          $this->update(array(
          'id'=>$this->fields['id'],
          'color'=>$color));
        } else {

          $this->add(array(
        'vlans_id'=>$vlan_table,
        'color'=>$color));
        }
        $i++;
      }			
    }
  }

  function deleteVlanColor($ID){
    
    $this->delete(array('id'=>$ID));
      
  }
}

class PluginArchiresStateColor extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_statescolors";
	}

	function getFromDBbyState($state) {
		global $DB;
		$query = "SELECT * FROM `".$this->table."`
				WHERE `states_id` = '" . $state . "' ";
		if ($result = $DB->query($query)) {
			if ($DB->numrows($result) != 1) {
				return false;
			}
			$this->fields = $DB->fetch_assoc($result);
			if (is_array($this->fields) && count($this->fields)) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}
	
	function addStateColor($state,$color){
	
    GLOBAL $DB;
    
    if ($state!='-1'){
      if ($this->GetfromDBbyState($state)){

        $this->update(array(
        'id'=>$this->fields['id'],
        'color'=>$color));
      } else {

        $this->add(array(
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
        if ($this->GetfromDBbyState($state_table)){

          $this->update(array(
          'id'=>$this->fields['id'],
          'color'=>$color));
        } else {

          $this->add(array(
        'states_id'=>$state_table,
        'color'=>$color));
        }
        $i++;
      }			
    }
  }

  function deleteStateColor($ID){
    
    $this->delete(array('id'=>$ID));
      
  }
}


class PluginArchiresQueryType extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_queriestypes";
	}

	function getFromDBbyType($itemtype, $type,$type_query,$query_ID) {
		global $DB;
		$query = "SELECT * FROM `".$this->table."` " .
			"WHERE `itemtype` = '" . $itemtype . "' " .
				"AND `type` = '" . $type . "' " .
				"AND `querytype` = '" . $type_query . "' " .
				"AND `queries_id` = '" . $query_ID . "' ";
		if ($result = $DB->query($query)) {
			if ($DB->numrows($result) != 1) {
				return false;
			}
			$this->fields = $DB->fetch_assoc($result);
			if (is_array($this->fields) && count($this->fields)) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}
	
	function addType($querytype,$type,$itemtype,$queries_id){
    GLOBAL  $PLUGIN_ARCHIRES_TYPE_TABLES,$DB;
    
    if ($type!='-1'){
      if (!$this->GetfromDBbyType($itemtype,$type,$querytype,$queries_id)){

        $this->add(array(
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
        if (!$this->GetfromDBbyType($itemtype,$type_table,$querytype,$queries_id)){
          $this->add(array(
          'itemtype'=>$itemtype,
          'type'=>$type_table,
          'querytype'=>$querytype,
          'queries_id'=>$queries_id));
        }
        $i++;
      }			
    }
  }

  function deleteType($ID){
    
    $this->delete(array('id'=>$ID));
        
  }

}

?>