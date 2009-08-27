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
      
      $canedit=$this->can($ID,'w');
			$canrecu=$this->can($ID,'recursive');
			
			$this->showTabs($ID, $withtemplate,$_SESSION['glpi_tab']);
      $this->showFormHeader($target,$ID, $withtemplate,2);
			
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
			echo "<select name=\"display_ports\" size=\"1\"> ";
			echo "<option ";
			if ($this->fields["display_ports"]=='0') echo "selected ";
			echo "value=\"0\">".$LANG['choice'][0]."</option>";
			echo "<option ";
			if ($this->fields["display_ports"]=='1') echo "selected ";
			echo "value=\"1\">".$LANG['plugin_archires'][29]."</option>";
			echo "<option ";
			if ($this->fields["display_ports"]=='2') echo "selected ";
			echo "value=\"2\">".$LANG['plugin_archires'][33]."</option>";
			echo "</select> ";
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
			if ($this->fields["format"]==PLUGIN_ARCHIRES_JPEG_FORMAT) echo "selected ";
			echo "value=\"0\">jpeg</option>";
			echo "<option ";
			if ($this->fields["format"]==PLUGIN_ARCHIRES_PNG_FORMAT) echo "selected ";
			echo "value=\"1\">png</option>";
			echo "<option ";
			if ($this->fields["format"]==PLUGIN_ARCHIRES_GIF_FORMAT) echo "selected ";
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

			$this->showFormButtons($ID,$withtemplate,2);
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