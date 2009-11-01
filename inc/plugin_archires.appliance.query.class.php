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

class PluginArchiresQueryAppliance extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_appliancesqueries";
		$this->type=PLUGIN_ARCHIRES_APPLIANCES_QUERY;
	}
	
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

      $tab[1]['table']='glpi_plugin_archires_appliancesqueries';
      $tab[1]['field']='name';
      $tab[1]['linkfield']='name';
      $tab[1]['name']=$LANG['plugin_archires']['search'][1];
      $tab[1]['datatype']='itemlink';

      $tab[2]['table']='glpi_plugin_appliances';
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

      $tab[8]['table']='glpi_plugin_archires_appliancesqueries';
      $tab[8]['field']='link';
      $tab[8]['linkfield']='';
      $tab[8]['name']=$LANG['plugin_archires'][0];

      $tab[30]['table']='glpi_plugin_archires_appliancesqueries';
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

		GLOBAL $CFG_GLPI,$DB, $LANG;

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

    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][8].":	</td><td>";
      dropdownValue("glpi_plugin_appliances", "appliances_id", $this->fields["appliances_id"],1,$this->fields["entities_id"]);
    echo "</td></tr>";
    

    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][4].":	</td><td>";
    dropdownValue("glpi_networks", "networks_id", $this->fields["networks_id"],1,$this->fields["entities_id"]);
    echo "</td></tr>";


    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][5].":	</td><td>";
    dropdownValue("glpi_states", "states_id", $this->fields["states_id"],1,$this->fields["entities_id"]);
    echo "</td></tr>";
    
    echo "</table>";
    echo "</td>";	
    echo "<td class='tab_bg_1' valign='top'>";
    echo "<table cellpadding='2' cellspacing='2' border='0'>";
    
    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['common'][35].": </td><td>";
    dropdownValue("glpi_groups", "groups_id", $this->fields["groups_id"],1,$this->fields["entities_id"]);
    echo "</td></tr>";
    
    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['networking'][56].": </td><td>";
    dropdownValue("glpi_vlans", "vlans_id", $this->fields["vlans_id"],1,$this->fields["entities_id"]);
    echo "</td></tr>";
    
    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['setup'][20].": </td><td>";
    //View
    plugin_archires_dropdownView($this,$ID);
    echo "</td></tr>";
    
    echo "</table>";
    echo "</td>";
    echo "</tr>";
    
    $this->showFormButtons($ID,$withtemplate);
    echo "<div id='tabcontent'></div>";
    echo "<script type='text/javascript'>loadDefaultTab();</script>";

		return true;
	}
}

?>