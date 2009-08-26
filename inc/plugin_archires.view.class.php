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

class PluginArchiresView extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_views";
		$this->type=PLUGIN_ARCHIRES_VIEWS_TYPE;
	}

	function title(){

		GLOBAL $CFG_GLPI, $LANG;

		echo "<div align='center'><table border='0'><tr><td>";
		echo "<img src=\"".$CFG_GLPI["root_doc"]."/plugins/archires/pics/archires.png\" alt='".$LANG['plugin_archires']['title'][0]."' title='".$LANG['plugin_archires']['title'][0]."'></td>";
		if(plugin_archires_haveRight("archires","w") || haveRight("config","w")){
			echo "<td><a  class='icon_consol' href=\"./plugin_archires.view.form.php?new=1\"><b>".$LANG['plugin_archires']['title'][1]."</b></a></td>";
		}else{
			echo "<td><a  class='icon_consol' href=\"index.php\"><b>".$LANG['plugin_archires']['title'][3]."</b></a></td>";
		}
		echo "<td><a  class='icon_consol' href=\"../index.php\"><b>".$LANG['plugin_archires']['title'][0]."</b></a></td>";

		if(plugin_archires_haveRight("archires","w") || haveRight("config","w")){
			echo "<td><a class='icon_consol' href=\"plugin_archires.config.php\">".$LANG['plugin_archires']['profile'][2]."</a></td>";
		}
		echo "</tr></table></div>";
	}

	function defineTabs($ID,$withtemplate){
		global $LANG;
		$ong[1]=$LANG['title'][26];

		return $ong;
	}

	function showForm ($target,$ID, $withtemplate='') {

		GLOBAL $CFG_GLPI,$DB, $LANG;

		if (!plugin_archires_haveRight("archires","r")) return false;

		$con_spotted=false;

		if (empty($ID) ||$ID==-1) {

			if($this->getEmpty()) $con_spotted = true;
			$use_cache=false;
		} else {
			if($this->getfromDB($ID)&&haveAccessToEntity($this->fields["entities_id"])) $con_spotted = true;
		}

		if ($con_spotted){

			$this->showTabs($ID, $withtemplate,$_SESSION['glpi_tab']);

			echo "<form method='post' name=form action=\"$target\">";
			if (empty($ID)||$ID<0){
					echo "<input type='hidden' name='entities_id' value='".$_SESSION["glpiactive_entity"]."'>";
				}
			echo "<div class='center' id='tabsbody'>";
			echo "<table class='tab_cadre_fixe'>";
			echo "<tr><th><div align='left'>";
			echo "<a href=\"./plugin_archires.view.index.php\">".$LANG['buttons'][13]."</a>";
			echo "</div></th>";
			echo "<th colspan='3'><div align='left'>";
			if (empty($ID)) {
				echo $LANG['plugin_archires']['title'][1].":";

			} else {
				echo $LANG['plugin_archires']['title'][3]." ID $ID:";
			}
			echo "</div></th></tr>";


			echo "<tr class='tab_bg_1' valign='top'><td colspan='1'>".$LANG['plugin_archires']['search'][1].":	</td>";
			echo "<td colspan='3'>";
			autocompletionTextField("name","glpi_plugin_archires_views","name",$this->fields["name"],20,$this->fields["entities_id"]);

			echo "</td></tr>";

			echo "<tr class='tab_bg_1' valign='top'><th colspan='4'>".$LANG['plugin_archires'][3]."</th></tr>";

			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires'][6].": </td>";
			echo "<td>";
			dropdownyesno("computer",$this->fields["computer"]);
			echo "</td>";

			echo "<td>".$LANG['plugin_archires'][7].": </td>";
			echo "<td>";
			dropdownyesno("networking",$this->fields["networking"]);
			echo "</td></tr>";

			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires'][8].": </td>";
			echo "<td>";
			dropdownyesno("printer",$this->fields["printer"]);
			echo "</td>";

			echo "<td>".$LANG['plugin_archires'][9].": </td>";
			echo "<td>";
			dropdownyesno("peripheral",$this->fields["peripheral"]);
			echo "</td></tr>";

			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires'][10].": </td>";
			echo "<td>";
			dropdownyesno("phone",$this->fields["phone"]);
			echo "</td><td></td><td></td></tr>";


			echo "<tr class='tab_bg_1' valign='top'><th colspan='4'>".$LANG['plugin_archires'][24]."</th></tr>";


			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires'][16].": </td>";
			echo "<td>";
			dropdownyesno("display_ports",$this->fields["display_ports"]);
			echo "</td>";

			echo "<td>".$LANG['plugin_archires'][23].": </td>";
			echo "<td>";
			dropdownyesno("display_ip",$this->fields["display_ip"]);
			echo "</td></tr>";

			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires'][25].": </td>";
			echo "<td>";
			dropdownyesno("display_type",$this->fields["display_type"]);
			echo "</td>";


			echo "<td>".$LANG['plugin_archires'][26].": </td>";
			echo "<td>";
			dropdownyesno("display_state",$this->fields["display_state"]);
			echo "</td></tr>";

			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires'][31].": </td>";
			echo "<td>";
			dropdownyesno("display_location",$this->fields["display_location"]);
			echo "</td>";

			echo "<td>".$LANG['plugin_archires'][32].": </td>";
			echo "<td>";
			dropdownyesno("display_entity",$this->fields["display_entity"]);
			echo "</td></tr>";

			echo "<tr class='tab_bg_1' valign='top'><th colspan='4'>".$LANG['plugin_archires']['search'][6]."</th></tr>";

			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['setup'][13].": </td>";
			echo "<td>";
			echo "<select name=\"engine\" size=\"1\"> ";
			echo "<option ";
			if ($this->fields["engine"]=='0') echo "selected ";
			echo "value=\"0\">Dot</option>";
			echo "<option ";
			if ($this->fields["engine"]=='1') echo "selected ";
			echo "value=\"1\">Neato</option>";
			echo "</select> ";
			echo " <img alt='' src='".$CFG_GLPI["root_doc"]."/pics/aide.png' onmouseout=\"cleanhide('comments')\" onmouseover=\"cleandisplay('comments')\">";
			echo "<span class='over_link' id='comments'>".nl2br($LANG['plugin_archires']['setup'][14])."</span>";
			echo "</td>";

			echo "<td>".$LANG['plugin_archires']['setup'][15].": </td>";
			echo "<td>";
			echo "<select name=\"format\" size=\"1\"> ";
			echo "<option ";
			if ($this->fields["format"]=='0') echo "selected ";
			echo "value=\"0\">jpeg</option>";
			echo "<option ";
			if ($this->fields["format"]=='1') echo "selected ";
			echo "value=\"1\">png</option>";
			echo "<option ";
			if ($this->fields["format"]=='2') echo "selected ";
			echo "value=\"2\">gif</option>";
			echo "</select>";
			echo "</td>";

			echo "<tr class='tab_bg_1' valign='top'><td>".$LANG['plugin_archires']['setup'][25].": </td>";
			echo "<td>";
			echo "<select name=\"color\" size=\"1\"> ";
			echo "<option ";
			if ($this->fields["color"]=='0') echo "selected ";
			echo "value=\"0\">".$LANG['plugin_archires'][19]."</option>";
			echo "<option ";
			if ($this->fields["color"]=='1') echo "selected ";
			echo "value=\"1\">".$LANG['plugin_archires'][35]."</option>";
			echo "</select>";
			echo "</td><td colspan='2'></td></tr>";

			if ($ID=="") {
				if (plugin_archires_haveRight("archires","w")){
					echo "<tr>";
					echo "<td class='tab_bg_2' valign='top' colspan='4'>";
					echo "<div align='center'><input type='submit' name='add' value=\"".$LANG['buttons'][8]."\" class='submit'></div>";
					echo "</td>";
					echo "</tr>";
				}

			} else {

				echo "<tr>";
				echo "<td class='tab_bg_2'  colspan='4' valign='top'><div align='center'>";
				if (plugin_archires_haveRight("archires","w")){

					echo "<input type='hidden' name='id' value=\"$ID\">\n";
					echo "<input type='submit' name='update' value=\"".$LANG['buttons'][7]."\" class='submit' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					echo "<input type='submit' name='duplicate' value=\"".$LANG['plugin_archires'][28]."\" class='submit' >";
				}

				if (plugin_archires_haveRight("archires","w")){

					if ($this->fields["is_deleted"]=='0')
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='delete' value=\"".$LANG['buttons'][6]."\" class='submit'></div>";
					else {
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='restore' value=\"".$LANG['buttons'][21]."\" class='submit'>";

						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='purge' value=\"".$LANG['buttons'][22]."\" class='submit'></div>";
					}
				}
				echo "</td>";
				echo "</tr>";
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