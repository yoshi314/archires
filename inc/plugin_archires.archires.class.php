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


class PluginArchires extends CommonDBTM {

	function title(){
			
				GLOBAL $CFG_GLPI, $LANG;
				
				echo "<div align='center'><table border='0'><tr><td>";
				echo "<img src=\"".$CFG_GLPI["root_doc"]."/plugins/archires/pics/archires.png\" alt='".$LANG['plugin_archires']['title'][0]."' title='".$LANG['plugin_archires']['title'][0]."'></td>";
				if(plugin_archires_haveRight("archires","w") || haveRight("config","w"))
          echo "<td><a  class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/index.php?new=1\"><b>".$LANG['plugin_archires']['title'][7]."</b></a></td>";
				else
          echo "<td><a  class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/index.php\"><b>".$LANG['plugin_archires']['title'][0]."</b></a></td>";
				
				echo "<td><a  class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/front/plugin_archires.view.index.php\"><b>".$LANG['plugin_archires']['title'][3]."</b></a></td>";
								
				if(plugin_archires_haveRight("archires","w") || haveRight("config","w"))
          echo "<td><a class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/front/plugin_archires.config.php\">".$LANG['plugin_archires']['profile'][2]."</a></td>";
				echo "</tr></table></div>";
		}
	
	function titleimg(){
			
				GLOBAL $CFG_GLPI, $LANG;
				
				echo "<div align='center'><table border='0'><tr><td>";
				echo "<img src=\"".$CFG_GLPI["root_doc"]."/plugins/archires/pics/archires.png\" alt='".$LANG['plugin_archires']['title'][0]."' title='".$LANG['plugin_archires']['title'][0]."'></td>";
				echo "<td><a  class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/index.php\"><b>".$LANG['plugin_archires']['title'][0]."</b></a></td>";
				echo "<td><a  class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/front/plugin_archires.view.index.php\"><b>".$LANG['plugin_archires']['title'][3]."</b></a></td>";
				if(plugin_archires_haveRight("archires","w"))
          echo "<td><a class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/front/plugin_archires.config.php\">".$LANG['plugin_archires']['profile'][2]."</a>";
				echo "</td></tr></table></div>";
		}
}
		
class PluginArchiresQueryLocation extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_locations_queries";
		$this->type=PLUGIN_ARCHIRES_LOCATIONS_QUERY;
	}
	
	function cleanDBonPurge($ID) {
		global $DB;

		$query = "DELETE FROM 
				`glpi_plugin_archires_query_types` 
				WHERE `queries_id` = '$ID'";
		$DB->query($query);
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

    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][2].":	</td><td>";
    
    plugin_archires_dropdownLocation($this,$ID);

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

class PluginArchiresQueryNetworkEquipment extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_networkequipments_queries";
		$this->type=PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY;
		$object=$this;
	}
	
	function cleanDBonPurge($ID) {
		global $DB;

		$query = "DELETE 
					FROM `glpi_plugin_archires_query_types` 
					WHERE `queries_id` = '$ID'";
		$DB->query($query);
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
    
    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['help'][26].":	</td><td>";
    
    dropdownValue("glpi_networkequipments", "networkequipments_id", $this->fields["networkequipments_id"],1,$this->fields["entities_id"]);
    echo "</td></tr>";	
      
    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][4].":	</td><td>";
    dropdownValue("glpi_networks", "networks_id", $this->fields["networks_id"]);
    echo "</td></tr>";


    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][5].":	</td><td>";
    dropdownValue("glpi_states", "states_id", $this->fields["states_id"]);
    echo "</td></tr>";
    
    echo "</table>";
    echo "</td>";	
    echo "<td class='tab_bg_1' valign='top'>";
    echo "<table cellpadding='2' cellspacing='2' border='0'>";
    
    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['common'][35].": </td><td>";
    dropdownValue("glpi_groups", "groups_id", $this->fields["groups_id"]);
    echo "</td></tr>";
    
    echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['networking'][56].": </td><td>";
    dropdownValue("glpi_vlans", "vlans_id", $this->fields["vlans_id"]);
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

class PluginArchiresQueryAppliance extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_appliances_queries";
		$this->type=PLUGIN_ARCHIRES_APPLIANCES_QUERY;
	}
	
	function cleanDBonPurge($ID) {
		global $DB;

		$query = "DELETE 
					FROM `glpi_plugin_archires_query_types` 
					WHERE `queries_id` = '$ID'";
		$DB->query($query);
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