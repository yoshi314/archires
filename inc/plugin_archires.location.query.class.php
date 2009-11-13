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
		
class PluginArchiresQueryLocation extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_locationsqueries";
		$this->type=PLUGIN_ARCHIRES_LOCATIONS_QUERY;
	}
	
	function cleanDBonPurge($ID) {
		global $DB;

		$query = "DELETE FROM 
				`glpi_plugin_archires_queriestypes` 
				WHERE `queries_id` = '$ID'";
		$DB->query($query);
	}
  
  function getSearchOptions() {
    global $LANG;

      $tab = array();

      $tab['common'] = $LANG['plugin_archires']['title'][4];

      $tab[1]['table']=$this->table;
      $tab[1]['field']='name';
      $tab[1]['linkfield']='name';
      $tab[1]['name']=$LANG['plugin_archires']['search'][1];
      $tab[1]['datatype']='itemlink';

      $tab[2]['table']=$this->table;
      $tab[2]['field']='child';
      $tab[2]['linkfield']='child';
      $tab[2]['name']=$LANG['plugin_archires']['search'][3];
      $tab[2]['datatype']='bool';

      $tab[3]['table']='glpi_locations';
      $tab[3]['field']='completename';
      $tab[3]['linkfield']='locations_id';
      $tab[3]['name']=$LANG['plugin_archires']['search'][2];

      $tab[4]['table']='glpi_networks';
      $tab[4]['field']='name';
      $tab[4]['linkfield']='networks_id';
      $tab[4]['name']=$LANG['plugin_archires']['search'][4];

      $tab[5]['table']='glpi_states';
      $tab[5]['field']='name';
      $tab[5]['linkfield']='states_id';
      $tab[5]['name']=$LANG['plugin_archires']['search'][5];

      $tab[6]['table']='glpi_groups';
      $tab[6]['field']='name';
      $tab[6]['linkfield']='groups_id';
      $tab[6]['name']=$LANG['common'][35];

      $tab[7]['table']='glpi_vlans';
      $tab[7]['field']='name';
      $tab[7]['linkfield']='vlans_id';
      $tab[7]['name']=$LANG['networking'][56];

      $tab[8]['table']='glpi_plugin_archires_views';
      $tab[8]['field']='name';
      $tab[8]['linkfield']='views_id';
      $tab[8]['name']=$LANG['plugin_archires']['setup'][20];

      $tab[9]['table']=$this->table;
      $tab[9]['field']='link';
      $tab[9]['linkfield']='';
      $tab[9]['name']=$LANG['plugin_archires'][0];

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
   
	function defineTabs($ID,$withtemplate){
		global $LANG;
		$ong[1]=$LANG['title'][26];
		if ($ID > 0){
			$ong[2]=$LANG['plugin_archires']['test'][0];
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
    
    echo "<tr><td class='tab_bg_1' valign='top'>";

    echo "<table cellpadding='2' cellspacing='2' border='0'>\n";

    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][1].":	</td>";
    echo "<td>";
    autocompletionTextField("name",$this->table,"name",$this->fields["name"],50,$this->fields["entities_id"]);		
    echo "</td></tr>";

    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][2].":	</td><td>";
    
    $this->dropdownLocation($this,$ID);

    echo "</td></tr>";
    
    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][3].":	</td>";
    echo "<td>";
    dropdownyesno("child",$this->fields["child"]);
    echo "</td></tr>";
    
    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][4].":	</td><td>";
    dropdownValue("glpi_networks", "networks_id", $this->fields["networks_id"]);
    echo "</td></tr>";

    echo "</table>";
    echo "</td>";	
    echo "<td class='tab_bg_1' valign='top'>";
    echo "<table cellpadding='2' cellspacing='2' border='0'>";
    
    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][5].":	</td><td>";
    dropdownValue("glpi_states", "states_id", $this->fields["states_id"]);
    echo "</td></tr>";
    
    
    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['common'][35].": </td><td>";
    dropdownValue("glpi_groups", "groups_id", $this->fields["groups_id"]);
    echo "</td></tr>";
    
    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['networking'][56].": </td><td>";
    dropdownValue("glpi_vlans", "vlans_id", $this->fields["vlans_id"]);
    echo "</td></tr>";
    
    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['setup'][20].": </td><td>";
    //view
    $PluginArchiresView=new PluginArchiresView();
    $PluginArchiresView->dropdownView($this,$ID);
    echo "</td></tr>";
    
    echo "</table>";
    echo "</td>";
    echo "</tr>";
    
    $this->showFormButtons($ID,$withtemplate);
    echo "<div id='tabcontent'></div>";
    echo "<script type='text/javascript'>loadDefaultTab();</script>";

		return true;
	}
	
	function dropdownLocation($object,$ID) {
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
  
  function findChilds($DB, $parent){

    $queryBranch='';
    // Recherche les enfants
    if ($parent!="-1"){
      $queryChilds= "SELECT `id`
            FROM `glpi_locations`
            WHERE `locations_id` = '$parent' ";
      if ($resultChilds = $DB->query($queryChilds)){
        while ($dataChilds = $DB->fetch_array($resultChilds)){
          $child=$dataChilds["id"];
          $queryBranch .= ",$child";
          // Recherche les petits enfants récursivement
          $queryBranch .= $this->findChilds($DB, $child);
        }
      }
    }else{
      $queryChilds= "SELECT `id`
            FROM `glpi_locations`
            WHERE `level`= 1";
      if ($resultChilds = $DB->query($queryChilds)){
        while ($dataChilds = $DB->fetch_array($resultChilds)){
          $child=$dataChilds["id"];
          $queryBranch .= ",$child";
          // Recherche les petits enfants récursivement
          $queryBranch .= $this->findChilds($DB, $child);
        }
      }
    }
      return $queryBranch;
  }
  
  function findLevels($DB,$parent){

    $queryBranch='';
    // Recherche les enfants
    $queryLevels= "SELECT `id`
          FROM `glpi_locations`
          WHERE `level`= 1";
    if ($resultLevels = $DB->query($queryLevels)){
      while ($dataLevels = $DB->fetch_array($resultLevels)){
        $Levels=$dataLevels["id"];
        $queryBranch .= ",$Levels";
      }
    }

      return $queryBranch;
  }

	function Query ($ID,$PluginArchiresView,$for){
    global $DB,$CFG_GLPI,$LANG,$LINK_ID_TABLE,$INFOFORM_PAGES;
    
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
      if ($this->fields["vlans_id"] > "0")
        $query .= ", `glpi_networkports_vlans` nv";

      $query .= ", `glpi_locations` lc";
      $query .= " WHERE `np`.`itemtype` = " . $val . " 
            AND `np`.`items_id` = `$LINK_ID_TABLE[$val]`.`id` ";
      $query .= " AND `$LINK_ID_TABLE[$val]`.`is_deleted` = '0' 
            AND `$LINK_ID_TABLE[$val]`.`is_template` = '0'";
      $LINK= " AND " ;
      $query.=getEntitiesRestrictRequest($LINK,$LINK_ID_TABLE[$val]);
      
      if ($this->fields["vlans_id"] > "0")
        $query .= " AND `nv`.`networkports_id` = `np`.`id` 
            AND `vlans_id` = '".$this->fields["vlans_id"]."'";
            
      if ($this->fields["networks_id"] > "0" && $val != PHONE_TYPE && $val != PERIPHERAL_TYPE)
        $query .= " AND `$LINK_ID_TABLE[$val]`.`networks_id` = '".$this->fields["networks_id"]."'";
      if ($this->fields["states_id"] > "0")
        $query .= " AND `$LINK_ID_TABLE[$val]`.`states_id` = '".$this->fields["states_id"]."'";
      if ($this->fields["groups_id"] > "0")
        $query .= " AND `$LINK_ID_TABLE[$val]`.`groups_id` = '".$this->fields["groups_id"]."'";
      if ($this->fields["locations_id"]!="-1"){
        $query .= " AND `lc`.`id` = `$LINK_ID_TABLE[$val]`.`locations_id` 
              AND `lc`.`id` IN ('".$this->fields["locations_id"]."'";
        if ($this->fields["child"]!='0')
          $query .= $this->findChilds($DB, $this->fields["locations_id"]);
        $query .= ") ";
       }else{
        $query .= " AND `lc`.`id` = `$LINK_ID_TABLE[$val]`.`locations_id` 
            AND `lc`.`id` IN (0";
        $query .= $this->findLevels($DB, $this->fields["locations_id"]);
        if ($this->fields["child"]!='0')
          $query .= $this->findChilds($DB, $this->fields["locations_id"]);
        $query .= ") ";
      }
      //types
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

}

?>