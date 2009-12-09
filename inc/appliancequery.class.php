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

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginArchiresApplianceQuery extends CommonDBTM {

	public $table = 'glpi_plugin_archires_appliancesqueries';
   public $type = "PluginArchiresApplianceQuery";
	
	function cleanDBonPurge($ID) {
		global $DB;

		$query = "DELETE 
					FROM `glpi_plugin_archires_queriestypes` 
					WHERE `queries_id` = '$ID'";
		$DB->query($query);
	}
  
  function getSearchOptions() {
      global $LANG;

      $tab = array();

      $tab['common'] = $LANG['plugin_archires']['title'][8];

      $tab[1]['table']=$this->table;
      $tab[1]['field']='name';
      $tab[1]['linkfield']='name';
      $tab[1]['name']=$LANG['plugin_archires']['search'][1];
      $tab[1]['datatype']='itemlink';

      $tab[2]['table']='glpi_plugin_appliances_appliances';
      $tab[2]['field']='name';
      $tab[2]['linkfield']='appliances_id';
      $tab[2]['name']=$LANG['plugin_archires']['search'][8];

      $tab[3]['table']='glpi_networks';
      $tab[3]['field']='name';
      $tab[3]['linkfield']='networks_id';
      $tab[3]['name']=$LANG['plugin_archires']['search'][4];

      $tab[4]['table']='glpi_states';
      $tab[4]['field']='name';
      $tab[4]['linkfield']='states_id';
      $tab[4]['name']=$LANG['plugin_archires']['search'][5];

      $tab[5]['table']='glpi_groups';
      $tab[5]['field']='name';
      $tab[5]['linkfield']='groups_id';
      $tab[5]['name']=$LANG['common'][35];

      $tab[6]['table']='glpi_vlans';
      $tab[6]['field']='name';
      $tab[6]['linkfield']='vlans_id';
      $tab[6]['name']=$LANG['networking'][56];

      $tab[7]['table']='glpi_plugin_archires_views';
      $tab[7]['field']='name';
      $tab[7]['linkfield']='views_id';
      $tab[7]['name']=$LANG['plugin_archires']['setup'][20];

      $tab[30]['table']=$this->table;
      $tab[30]['field']='id';
      $tab[30]['linkfield']='';
      $tab[30]['name']=$LANG['common'][2];

      $tab[80]['table']='glpi_entities';
      $tab[80]['field']='completename';
      $tab[80]['linkfield']='entities_id';
      $tab[80]['name']=$LANG['entity'][0];
		
		return $tab;
   }
   
	function defineTabs($ID,$withtemplate) {
		global $LANG;
		
		$ong[1]=$LANG['title'][26];
		if ($ID > 0) {
			$ong[2]=$LANG['plugin_archires']['test'][0];
			$ong[3]=$LANG['plugin_archires']['search'][6];
			if (haveRight("notes","r"))	
				$ong[10]=$LANG['title'][37];
		}

		return $ong;
	}
	
	function showForm ($target,$ID,$withtemplate='') {
		global $CFG_GLPI,$DB,$LANG;

		if (!plugin_archires_haveRight("archires","r")) return false;

		if ($ID > 0) {
         $this->check($ID,'r');
      } else {
         // Create item
         $this->check(-1,'w');
         $this->getEmpty();
      }
		
      $this->showTabs($ID, $withtemplate,$_SESSION['glpi_tab']);
      $this->showFormHeader($target,$ID,$withtemplate);
      echo "<tr><td class='tab_bg_1 top'>";

      echo "<table cellpadding='2' cellspacing='2' border='0'>\n";

      echo "<tr class='tab_bg_1 top'><td>".$LANG['plugin_archires']['search'][1].":	</td>";
      echo "<td>";
      autocompletionTextField("name",$this->table,"name",$this->fields["name"],50,$this->fields["entities_id"]);		
      echo "</td></tr>";

      echo "<tr class='tab_bg_1 top'><td>".$LANG['plugin_archires']['search'][8].":	</td><td>";
      CommonDropdown::dropdownValue("glpi_plugin_appliances_appliances", "appliances_id", $this->fields["appliances_id"],1,$this->fields["entities_id"]);
      echo "</td></tr>";


      echo "<tr class='tab_bg_1 top'><td>".$LANG['plugin_archires']['search'][4].":	</td><td>";
      CommonDropdown::dropdownValue("glpi_networks", "networks_id", $this->fields["networks_id"],1,$this->fields["entities_id"]);
      echo "</td></tr>";


      echo "<tr class='tab_bg_1 top'><td>".$LANG['plugin_archires']['search'][5].":	</td><td>";
      CommonDropdown::dropdownValue("glpi_states", "states_id", $this->fields["states_id"],1,$this->fields["entities_id"]);
      echo "</td></tr>";

      echo "</table>";
      echo "</td>";	
      echo "<td class='tab_bg_1 top'>";
      echo "<table cellpadding='2' cellspacing='2' border='0'>";

      echo "<tr class='tab_bg_1 top'><td>".$LANG['common'][35].": </td><td>";
      CommonDropdown::dropdownValue("glpi_groups", "groups_id", $this->fields["groups_id"],1,$this->fields["entities_id"]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1 top'><td>".$LANG['networking'][56].": </td><td>";
      CommonDropdown::dropdownValue("glpi_vlans", "vlans_id", $this->fields["vlans_id"],1,$this->fields["entities_id"]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1 top'><td>".$LANG['plugin_archires']['setup'][20].": </td><td>";
      //View
      $PluginArchiresView=new PluginArchiresView();
      $PluginArchiresView->dropdownView($this,-1);
      echo "</td></tr>";

      echo "</table>";
      echo "</td>";
      echo "</tr>";
      
      $this->showFormButtons($ID,$withtemplate);
      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";

		return true;
	}
	
	function Query ($ID,$PluginArchiresView,$for) {
      global $DB,$CFG_GLPI,$LANG,$LINK_ID_TABLE,$INFOFORM_PAGES,$PLUGIN_ARCHIRES_TYPE_FIELD_TABLES;
    
      $this->getFromDB($ID);
    
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
    
      foreach ($types as $key => $val) {
      
         $fieldsnp = "`np`.`id`, `np`.`items_id`, `np`.`logical_number`, `np`.`networkinterfaces_id`,`np`.`ip`,`np`.`netmask`, `np`.`name` AS namep";
      
         $query = "SELECT `$LINK_ID_TABLE[$val]`.`id` AS idc, $fieldsnp , `$LINK_ID_TABLE[$val]`.`name`, `$LINK_ID_TABLE[$val]`.`$PLUGIN_ARCHIRES_TYPE_FIELD_TABLES[$val]` AS `type`, `$LINK_ID_TABLE[$val]`.`users_id`, `$LINK_ID_TABLE[$val]`.`groups_id`, `$LINK_ID_TABLE[$val]`.`contact`, `$LINK_ID_TABLE[$val]`.`states_id`";
         //en +
         $query .= ", `$LINK_ID_TABLE[$val]`.`entities_id`,`$LINK_ID_TABLE[$val]`.`locations_id` ";
         $query .= " FROM `glpi_networkports` np, `$LINK_ID_TABLE[$val]` ";
         if ($this->fields["vlans_id"] > "0")
            $query .= ", `glpi_networkports_vlans` nv";

         $query .= ", `glpi_plugin_appliances_appliances_items` app";
         $query .= " WHERE `np`.`itemtype` = '" . $val . "' 
            AND `np`.`items_id` = `$LINK_ID_TABLE[$val]`.`id` 
            AND `app`.`items_id` = `$LINK_ID_TABLE[$val]`.`id` ";
         $query .= " AND `$LINK_ID_TABLE[$val]`.`is_deleted` = '0' 
            AND `$LINK_ID_TABLE[$val]`.`is_template` = '0'";
         $LINK= " AND " ;
         $query.=getEntitiesRestrictRequest($LINK,$LINK_ID_TABLE[$val]);
         if ($this->fields["vlans_id"] > "0")
            $query .= " AND `nv`.`networkports_id` = `np`.`id` 
              AND `vlans_id` = '".$this->fields["vlans_id"]."'";
         if ($this->fields["networks_id"] > "0" && $val != 'Phone' && $val != 'Peripheral')
            $query .= " AND `$LINK_ID_TABLE[$val]`.`networks_id` = '".$this->fields["networks_id"]."'";
         if ($this->fields["states_id"] > "0")
            $query .= " AND `$LINK_ID_TABLE[$val]`.`states_id` = '".$this->fields["states_id"]."'";
         if ($this->fields["groups_id"] > "0")
            $query .= " AND `$LINK_ID_TABLE[$val]`.`groups_id` = '".$this->fields["groups_id"]."'";
      
         $query .= " AND `app`.`plugin_appliances_appliances_id` = '" . $this->fields["appliances_id"] . "' 
            AND `app`.`itemtype` = '" . $val . "' ";
      
         $PluginArchiresQueryType=new PluginArchiresQueryType();
         $query .= $PluginArchiresQueryType->queryTypeCheck($this->type,$ID,$val);
        
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
             
               if ($data["ip"]) {
                  if (!empty($devices[$val][$data["items_id"]]["ip"])) {
                     $devices[$val][$data["items_id"]]["ip"]  .= " - ";
                     $devices[$val][$data["items_id"]]["ip"]  .= $data["ip"];
                  } else {
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
}

?>