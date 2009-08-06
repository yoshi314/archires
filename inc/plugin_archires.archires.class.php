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
				
				echo "<td><a  class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/front/plugin_archires.config.index.php\"><b>".$LANG['plugin_archires']['title'][3]."</b></a></td>";
								
				if(plugin_archires_haveRight("archires","w") || haveRight("config","w"))
				echo "<td><a class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/front/plugin_archires.config.php\">".$LANG['plugin_archires']['profile'][2]."</a></td>";
				echo "</tr></table></div>";
		}
	
	function titleimg(){
			
				GLOBAL $CFG_GLPI, $LANG;
				
				echo "<div align='center'><table border='0'><tr><td>";
				echo "<img src=\"".$CFG_GLPI["root_doc"]."/plugins/archires/pics/archires.png\" alt='".$LANG['plugin_archires']['title'][0]."' title='".$LANG['plugin_archires']['title'][0]."'></td>";
				echo "<td><a  class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/index.php\"><b>".$LANG['plugin_archires']['title'][0]."</b></a></td>";
				echo "<td><a  class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/front/plugin_archires.config.index.php\"><b>".$LANG['plugin_archires']['title'][3]."</b></a></td>";
				if(plugin_archires_haveRight("archires","w"))
				echo "<td><a class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/front/plugin_archires.config.php\">".$LANG['plugin_archires']['profile'][2]."</a>";
				echo "</td></tr></table></div>";
		}
}
		
class PluginArchiresQueryLocation extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_query_location";
		$this->type=PLUGIN_ARCHIRES_LOCATION_TYPE;
	}
	
	function cleanDBonPurge($ID) {
		global $DB;

		$query = "DELETE FROM 
				`glpi_plugin_archires_query_type` 
				WHERE `FK_query` = '$ID'";
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

		$con_spotted=false;

		if (empty($ID)) {

			if($this->getEmpty()) $con_spotted = true;
			$use_cache=false;
		} else {
			if($this->getfromDB($ID)&&haveAccessToEntity($this->fields["FK_entities"])) $con_spotted = true;
		}

		if ($con_spotted){
		
			$this->showTabs($ID, $withtemplate,$_SESSION['glpi_tab']);
			
			echo "<form method='post' name=form action=\"$target\">";
			if (empty($ID)||$ID<0){
					echo "<input type='hidden' name='FK_entities' value='".$_SESSION["glpiactive_entity"]."'>";
				}
			echo "<div class='center' id='tabsbody'>";
			echo "<table class='tab_cadre_fixe'>";
			$this->showFormHeader($ID,'');
			echo "<tr><td class='tab_bg_1' valign='top'>";

			echo "<table cellpadding='2' cellspacing='2' border='0'>\n";

			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][1].":	</td>";
			echo "<td>";
			autocompletionTextField("name","glpi_plugin_archires_query_location","name",$this->fields["name"],50,$this->fields["FK_entities"]);		
			echo "</td></tr>";

			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][2].":	</td><td>";
			
			plugin_archires_dropdownLocation($this,$ID);

			echo "</td></tr>";
			
			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][3].":	</td>";
			echo "<td>";
			dropdownyesno("child",$this->fields["child"]);
			echo "</td></tr>";
			
			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][4].":	</td><td>";
			dropdownValue("glpi_dropdown_network", "network", $this->fields["network"]);
			echo "</td></tr>";

			echo "</table>";
			echo "</td>";	
			echo "<td class='tab_bg_1' valign='top'>";
			echo "<table cellpadding='2' cellspacing='2' border='0'>";
			
			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][5].":	</td><td>";
			dropdownValue("glpi_dropdown_state", "state", $this->fields["state"]);
			echo "</td></tr>";
			
			
			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['common'][35].": </td><td>";
			dropdownValue("glpi_groups", "FK_group", $this->fields["FK_group"]);
			echo "</td></tr>";
			
			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['networking'][56].": </td><td>";
			dropdownValue("glpi_dropdown_vlan", "FK_vlan", $this->fields["FK_vlan"]);
			echo "</td></tr>";
			
			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['setup'][20].": </td><td>";
			//config
			plugin_archires_dropdownConfig($this,$ID);
			echo "</td></tr>";
			
			echo "</table>";
			echo "</td>";
			echo "</tr>";
			
			if (plugin_archires_haveRight("archires","w")){
				if ($ID=="") {
						echo "<tr>";
						echo "<td class='tab_bg_2' valign='top' colspan='2'>";
						echo "<div align='center'><input type='submit' name='add' value=\"".$LANG['buttons'][8]."\" class='submit'></div>";
						echo "</td>";
						echo "</tr>";
	
				} else {
	
					echo "<tr>";
					echo "<td class='tab_bg_2'  colspan='2' valign='top'><div align='center'>";
	
						echo "<input type='hidden' name='ID' value=\"$ID\">\n";
						echo "<input type='submit' name='update' value=\"".$LANG['buttons'][7]."\" class='submit' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						echo "<input type='submit' name='duplicate' value=\"".$LANG['plugin_archires'][28]."\" class='submit' >";
						
						if ($this->fields["deleted"]=='0')
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='delete' value=\"".$LANG['buttons'][6]."\" class='submit'></div>";
						else {
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='restore' value=\"".$LANG['buttons'][21]."\" class='submit'>";
	
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='purge' value=\"".$LANG['buttons'][22]."\" class='submit'></div>";
						}
					
					echo "</td>";
					echo "</tr>";
				}
			}
			echo "</table></div></form>";
			echo "<div id='tabcontent'></div>";
			echo "<script type='text/javascript'>loadDefaultTab();</script>";

		} else {
			echo "<div align='center'><b>".$LANG['plugin_archires']['search'][7]."</b></div>";
			return false;

		}
		return true;
	}	
}

class PluginArchiresQuerySwitch extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_query_switch";
		$this->type=PLUGIN_ARCHIRES_SWITCH_TYPE;
		$object=$this;
	}
	
	function cleanDBonPurge($ID) {
		global $DB;

		$query = "DELETE 
					FROM `glpi_plugin_archires_query_type` 
					WHERE `FK_query` = '$ID'";
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

		$con_spotted=false;

		if (empty($ID)) {

			if($this->getEmpty()) $con_spotted = true;
			$use_cache=false;
		} else {
			if($this->getfromDB($ID)&&haveAccessToEntity($this->fields["FK_entities"])) $con_spotted = true;
		}

		if ($con_spotted){
		
			$this->showTabs($ID, $withtemplate,$_SESSION['glpi_tab']);
			
			echo "<form method='post' name=form action=\"$target\">";
			if (empty($ID)||$ID<0){
					echo "<input type='hidden' name='FK_entities' value='".$_SESSION["glpiactive_entity"]."'>";
				}
			echo "<div class='center' id='tabsbody'>";
			echo "<table class='tab_cadre_fixe'>";
			$this->showFormHeader($ID,'');
			echo "<tr><td class='tab_bg_1' valign='top'>";

			echo "<table cellpadding='2' cellspacing='2' border='0'>\n";

			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][1].":	</td>";
			echo "<td>";
			autocompletionTextField("name","glpi_plugin_archires_query_switch","name",$this->fields["name"],50,$this->fields["FK_entities"]);		
			echo "</td></tr>";
			
			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['help'][26].":	</td><td>";
			
			dropdownValue("glpi_networking", "switch", $this->fields["switch"],1,$this->fields["FK_entities"]);
			echo "</td></tr>";	
				
			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][4].":	</td><td>";
			dropdownValue("glpi_dropdown_network", "network", $this->fields["network"]);
			echo "</td></tr>";


			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][5].":	</td><td>";
			dropdownValue("glpi_dropdown_state", "state", $this->fields["state"]);
			echo "</td></tr>";
			
			echo "</table>";
			echo "</td>";	
			echo "<td class='tab_bg_1' valign='top'>";
			echo "<table cellpadding='2' cellspacing='2' border='0'>";
			
			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['common'][35].": </td><td>";
			dropdownValue("glpi_groups", "FK_group", $this->fields["FK_group"]);
			echo "</td></tr>";
			
			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['networking'][56].": </td><td>";
			dropdownValue("glpi_dropdown_vlan", "FK_vlan", $this->fields["FK_vlan"]);
			echo "</td></tr>";
			
			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['setup'][20].": </td><td>";
			//config
			plugin_archires_dropdownConfig($this,$ID);
			echo "</td></tr>";
			
			echo "</table>";
			echo "</td>";
			echo "</tr>";
			
			if (plugin_archires_haveRight("archires","w")){
				if ($ID=="") {
				
						echo "<tr>";
						echo "<td class='tab_bg_2' valign='top' colspan='2'>";
						echo "<div align='center'><input type='submit' name='add' value=\"".$LANG['buttons'][8]."\" class='submit'></div>";
						echo "</td>";
						echo "</tr>";
	
				} else {
	
					echo "<tr>";
					echo "<td class='tab_bg_2'  colspan='2' valign='top'><div align='center'>";
					echo "<input type='hidden' name='ID' value=\"$ID\">\n";
					echo "<input type='submit' name='update' value=\"".$LANG['buttons'][7]."\" class='submit' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					echo "<input type='submit' name='duplicate' value=\"".$LANG['plugin_archires'][28]."\" class='submit' >";
	
					if ($this->fields["deleted"]=='0')
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='delete' value=\"".$LANG['buttons'][6]."\" class='submit'></div>";
					else {
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='restore' value=\"".$LANG['buttons'][21]."\" class='submit'>";
	
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='purge' value=\"".$LANG['buttons'][22]."\" class='submit'></div>";
					}
					
					echo "</td>";
					echo "</tr>";
				}
			}
			echo "</table></div></form>";
			echo "<div id='tabcontent'></div>";
			echo "<script type='text/javascript'>loadDefaultTab();</script>";

		} else {
			echo "<div align='center'><b>".$LANG['plugin_archires']['search'][7]."</b></div>";
			return false;

		}
		return true;
	}
}

class PluginArchiresQueryApplicatifs extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_query_applicatifs";
		$this->type=PLUGIN_ARCHIRES_APPLICATIFS_TYPE;
	}
	
	function cleanDBonPurge($ID) {
		global $DB;

		$query = "DELETE 
					FROM `glpi_plugin_applicatifs_query_location` 
					WHERE `FK_query` = '$ID'";
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

		$con_spotted=false;

		if (empty($ID)) {

			if($this->getEmpty()) $con_spotted = true;
			$use_cache=false;
		} else {
			if($this->getfromDB($ID)&&haveAccessToEntity($this->fields["FK_entities"])) $con_spotted = true;
		}

		if ($con_spotted){
		
			$this->showTabs($ID, $withtemplate,$_SESSION['glpi_tab']);
			
			echo "<form method='post' name=form action=\"$target\">";
			if (empty($ID)||$ID<0){
					echo "<input type='hidden' name='FK_entities' value='".$_SESSION["glpiactive_entity"]."'>";
				}
			echo "<div class='center' id='tabsbody'>";
			echo "<table class='tab_cadre_fixe'>";
			$this->showFormHeader($ID,'');
			echo "<tr><td class='tab_bg_1' valign='top'>";

			echo "<table cellpadding='2' cellspacing='2' border='0'>\n";

			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][1].":	</td>";
			echo "<td>";
			autocompletionTextField("name","glpi_plugin_archires_query_applicatifs","name",$this->fields["name"],50,$this->fields["FK_entities"]);		
			echo "</td></tr>";

			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][8].":	</td><td>";
 	      dropdownValue("glpi_plugin_applicatifs", "applicatifs", $this->fields["applicatifs"],1,$this->fields["FK_entities"]);
			echo "</td></tr>";
			
	
			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][4].":	</td><td>";
			dropdownValue("glpi_dropdown_network", "network", $this->fields["network"],1,$this->fields["FK_entities"]);
			echo "</td></tr>";


			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['search'][5].":	</td><td>";
			dropdownValue("glpi_dropdown_state", "state", $this->fields["state"],1,$this->fields["FK_entities"]);
			echo "</td></tr>";
			
			echo "</table>";
			echo "</td>";	
			echo "<td class='tab_bg_1' valign='top'>";
			echo "<table cellpadding='2' cellspacing='2' border='0'>";
			
			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['common'][35].": </td><td>";
			dropdownValue("glpi_groups", "FK_group", $this->fields["FK_group"],1,$this->fields["FK_entities"]);
			echo "</td></tr>";
			
			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['networking'][56].": </td><td>";
			dropdownValue("glpi_dropdown_vlan", "FK_vlan", $this->fields["FK_vlan"],1,$this->fields["FK_entities"]);
			echo "</td></tr>";
			
			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['setup'][20].": </td><td>";
			//config
			plugin_archires_dropdownConfig($this,$ID);
			echo "</td></tr>";
			
			echo "</table>";
			echo "</td>";
			echo "</tr>";
			
			if (plugin_archires_haveRight("archires","w")){
				if ($ID=="") {
						echo "<tr>";
						echo "<td class='tab_bg_2' valign='top' colspan='2'>";
						echo "<div align='center'><input type='submit' name='add' value=\"".$LANG['buttons'][8]."\" class='submit'></div>";
						echo "</td>";
						echo "</tr>";

				} else {
	
					echo "<tr>";
					echo "<td class='tab_bg_2'  colspan='2' valign='top'><div align='center'>";	
					echo "<input type='hidden' name='ID' value=\"$ID\">\n";
					echo "<input type='submit' name='update' value=\"".$LANG['buttons'][7]."\" class='submit' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					echo "<input type='submit' name='duplicate' value=\"".$LANG['plugin_archires'][28]."\" class='submit' >";
					if ($this->fields["deleted"]=='0')
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='delete' value=\"".$LANG['buttons'][6]."\" class='submit'></div>";
					else {
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='restore' value=\"".$LANG['buttons'][21]."\" class='submit'>";
	
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='purge' value=\"".$LANG['buttons'][22]."\" class='submit'></div>";
					}
					
					echo "</td>";
					echo "</tr>";
				}
			}
			echo "</table></div></form>";
			echo "<div id='tabcontent'></div>";
			echo "<script type='text/javascript'>loadDefaultTab();</script>";

		} else {
			echo "<div align='center'><b>".$LANG['plugin_archires']['search'][7]."</b></div>";
			return false;

		}
		return true;
	}
}

?>