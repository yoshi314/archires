<?php
/*
 * @version $Id: auth.function.php 3576 2006-06-12 08:40:44Z moyo $
 ---------------------------------------------------------------------- 
 GLPI - Gestionnaire Libre de Parc Informatique 
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
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
// Original Author of file: GRISARD Jean Marc
// Purpose of file:
// ----------------------------------------------------------------------

function plugin_archires_config_Display() {
	global $DB,$LANG,$CFG_GLPI;

	echo "<form method='post' action=\"./plugin_archires.config.php\">";
	echo "<table class='tab_cadre' cellpadding='5'><tr><th colspan='4'>";
	echo $LANG['plugin_archires']['setup'][2]." : </th></tr>";
	echo "<tr class='tab_bg_1'><td>";
	$types=$CFG_GLPI["state_types"];
	plugin_archires_dropdownAllItems("type",0,0,$_SESSION["glpiactive_entity"],$types);
	echo "</td><td>";
	//file
	$rep = "../pics/";
	$dir = opendir($rep); 
	echo "<select name=\"img\">";
	while ($f = readdir($dir)) {
		if(is_file($rep.$f)) {
			echo "<option value='".$f."'>".$f."</option>";
		}   
	}
	echo "</select>";
	closedir($dir);
	echo " <img alt='' src='".$CFG_GLPI["root_doc"]."/pics/aide.png' onmouseout=\"cleanhide('commentsdropdown')\" onmouseover=\"cleandisplay('commentsdropdown')\">";
	echo "<span class='over_link' id='commentsdropdown'>".nl2br($LANG['plugin_archires']['setup'][21])."</span>";
	echo "<td>";
	echo "<div align='center'><input type='submit' name='add' value=\"".$LANG['buttons'][2]."\" class='submit' ></div></td></tr>";			
	echo "</table>";
	echo "</form>";	
	
	$query = "SELECT * 
			FROM `glpi_plugin_archires_imageitems` 
			ORDER BY `itemtype`,`type` ASC;";
	$i=0;
	if($result = $DB->query($query)){
		$number = $DB->numrows($result);
		if($number != 0){
		
			echo "<form method='post' name='massiveaction_form' id='massiveaction_form' action=\"./plugin_archires.config.php\">";
			echo "<div id='liste'>";
			echo "<table class='tab_cadre' cellpadding='5'>";
			echo "<tr>";
			if ($number > 1)
			{
				echo "<th><div align='left'>".$LANG['plugin_archires'][12]."</div></th><th><div align='left'>".$LANG['plugin_archires'][13]."</div></th><th><div align='left'>".$LANG['plugin_archires'][14]."</div></th><th></th>";
				echo "<th><div align='left'>".$LANG['plugin_archires'][12]."</div></th><th><div align='left'>".$LANG['plugin_archires'][13]."</div></th><th><div align='left'>".$LANG['plugin_archires'][14]."</div></th><th></th>";
			}
			else
			{
				echo "<th><div align='left'>".$LANG['plugin_archires'][12]."</div></th><th><div align='left'>".$LANG['plugin_archires'][13]."</div></th><th><div align='left'>".$LANG['plugin_archires'][14]."</div></th><th></th>";						
			}
			echo "</tr>";
		
			while($ligne= mysql_fetch_array($result)){
			
			$ID=$ligne["id"];
			
			if($i  % 2==0 && $number>1)
				echo "<tr class='tab_bg_1'>";
			
			if($number==1)
				echo "<tr class='tab_bg_1'>";						
			echo "<td>".plugin_archires_getDeviceType($ligne["itemtype"])."</td><td>".plugin_archires_getType($ligne["itemtype"],$ligne["type"])."</td><td><img src=\"".$CFG_GLPI["root_doc"]."/plugins/archires/pics/".$ligne["img"]."\" alt=\"".$ligne["img"]."\" title=\"".$ligne["img"]."\"></td>";					
			echo "<td>";
			echo "<input type='hidden' name='id' value='$ID'>";
			echo "<input type='checkbox' name='item[$ID]' value='1'>";
			echo "</td>";
			
			$i++;
			if(($i  == $number) && ($number  % 2 !=0) && $number>1)
				echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
			
			}
			
			echo "<tr class='tab_bg_1'>";

			if ($number > 1)
				echo "<td colspan='8'>";
			else
				echo "<td colspan='4'>";	
				echo "<div align='center'><a onclick= \"if ( markCheckboxes ('massiveaction_form') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=all'>".$LANG['buttons'][18]."</a>";
				echo " - <a onclick= \"if ( unMarkCheckboxes ('massiveaction_form') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=none'>".$LANG['buttons'][19]."</a> ";
				echo "<input type='submit' name='delete' value=\"".$LANG['buttons'][6]."\" class='submit' ></div></td></tr>";
				echo "</table>";
				echo "</div>";
				echo "</form>";

		}
	}
}

function plugin_archires_config_NetworkInterface() {
	global $DB,$LANG,$CFG_GLPI;

	$query = "SELECT * 
			FROM `glpi_plugin_archires_networkinterfacescolors` 
			ORDER BY `networkinterfaces_id` ASC;";
	$i=0;
	if($result = $DB->query($query)){
		$number = $DB->numrows($result);
		
		echo "<form method='post' name='massiveaction_form_iface_color' id='massiveaction_form_iface_color' action=\"./plugin_archires.config.php\">";
		$used=array();
		if($number != 0){
			
			echo "<div id='liste_color'>";
			echo "<table class='tab_cadre' cellpadding='5'>";
			echo "<tr>";
			if ($number > 1)
			{
				echo "<th><div align='left'>".$LANG['plugin_archires'][19]."</div></th><th><div align='left'>".$LANG['plugin_archires'][20]."</div></th><th></th>";
				echo "<th><div align='left'>".$LANG['plugin_archires'][19]."</div></th><th><div align='left'>".$LANG['plugin_archires'][20]."</div></th><th></th>";
			}
			else
			{
				echo "<th><div align='left'>".$LANG['plugin_archires'][19]."</div></th><th><div align='left'>".$LANG['plugin_archires'][20]."</div></th><th></th>";					
			}
			echo "</tr>";
      
			while($ligne= mysql_fetch_array($result)){
			
				$ID=$ligne["id"];
				$networkinterfaces_id=$ligne["networkinterfaces_id"];
        $used[]=$networkinterfaces_id;
				if($i  % 2==0 && $number>1)
					echo "<tr class='tab_bg_1'>";
				
				if($number==1)
					echo "<tr class='tab_bg_1'>";						
				echo "<td>".getDropdownName("glpi_networkinterfaces",$ligne["networkinterfaces_id"])."</td><td bgcolor='".$ligne["color"]."'>".$ligne["color"]."</td>";					
				echo "<td>";
				echo "<input type='hidden' name='id' value='$ID'>";
				echo "<input type='checkbox' name='item_color[$ID]' value='1'>";
				echo "</td>";
				
				$i++;
				if(($i  == $number) && ($number  % 2 !=0) && $number>1)
					echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
			
			}
			
			echo "<tr class='tab_bg_1'>";
			if ($number > 1)
				echo "<td colspan='8'>";
			else
				echo "<td colspan='4'>";
				
			echo "<div align='center'><a onclick= \"if ( markCheckboxes ('massiveaction_form_iface_color') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=all'>".$LANG['buttons'][18]."</a>";
			echo " - <a onclick= \"if ( unMarkCheckboxes ('massiveaction_form_iface_color') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=none'>".$LANG['buttons'][19]."</a> ";
			echo "<input type='submit' name='delete_color_iface' value=\"".$LANG['buttons'][6]."\" class='submit' ></div></td></tr>";
			echo "</table>";
			echo "</div>";
				
		}
		
		echo "<table class='tab_cadre' cellpadding='5'><tr ><th colspan='3'>";
    echo $LANG['plugin_archires']['setup'][8]." : </th></tr>";
    echo "<tr class='tab_bg_1'><td>";
    plugin_archires_dropdownColors_NetworkInterface($used);
    echo "</td><td>";
    echo "<input type='text' name=\"color\">";
    echo " <a href=\"http://www.graphviz.org/doc/info/colors.html\" target='_blank'>";
    echo " <img alt='' src='".$CFG_GLPI["root_doc"]."/pics/aide.png' onmouseout=\"cleanhide('comments_iface')\" onmouseover=\"cleandisplay('comments_iface')\">";
    echo "</a><span class='over_link' id='comments_iface'>".nl2br($LANG['plugin_archires']['setup'][12])."</span>";
    
    echo "<td>";
    echo "<div align='center'><input type='submit' name='add_color_iface' value=\"".$LANG['buttons'][2]."\" class='submit' ></div></td></tr>";
    echo "</table>";
    echo "</form>";
	}
}

function plugin_archires_Config_Vlan() {
	global $DB,$LANG,$CFG_GLPI;

	$query = "SELECT * 
			FROM `glpi_plugin_archires_vlanscolors` 
			ORDER BY `vlans_id` ASC;";
	$i=0;
	$used=array();
	
	if($result = $DB->query($query)){
		$number = $DB->numrows($result);
		
		echo "<form method='post' name='massiveaction_form_vlan_color' id='massiveaction_form_vlan_color' action=\"./plugin_archires.config.php\">";
		
		if($number != 0){
			
			echo "<div id='liste_vlan'>";
			echo "<table class='tab_cadre' cellpadding='5'>";
			echo "<tr>";
			if ($number > 1)
			{
				echo "<th><div align='left'>".$LANG['plugin_archires'][35]."</div></th><th><div align='left'>".$LANG['plugin_archires'][20]."</div></th><th></th>";
				echo "<th><div align='left'>".$LANG['plugin_archires'][35]."</div></th><th><div align='left'>".$LANG['plugin_archires'][20]."</div></th><th></th>";
			}
			else
			{
				echo "<th><div align='left'>".$LANG['plugin_archires'][35]."</div></th><th><div align='left'>".$LANG['plugin_archires'][20]."</div></th><th></th>";					
			}
			echo "</tr>";
		
			while($ligne= mysql_fetch_array($result)){
			
				$ID=$ligne["id"];
        $vlans_id=$ligne["vlans_id"];
        $used[]=$vlans_id;
				if($i  % 2==0 && $number>1)
					echo "<tr class='tab_bg_1'>";
				
				if($number==1)
					echo "<tr class='tab_bg_1'>";						
				echo "<td>".getDropdownName("glpi_vlans", $ligne["vlans_id"])."</td><td bgcolor='".$ligne["color"]."'>".$ligne["color"]."</td>";					
				echo "<td>";
				echo "<input type='hidden' name='id' value='$ID'>";
				echo "<input type='checkbox' name='item_color[$ID]' value='1'>";
				echo "</td>";
				
				$i++;
				if(($i  == $number) && ($number  % 2 !=0) && $number>1)
					echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
			
			}
			
			echo "<tr class='tab_bg_1'>";
			if ($number > 1)
				echo "<td colspan='8'>";
			else
				echo "<td colspan='4'>";
				
			echo "<div align='center'><a onclick= \"if ( markCheckboxes ('massiveaction_form_vlan_color') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=all'>".$LANG['buttons'][18]."</a>";
			echo " - <a onclick= \"if ( unMarkCheckboxes ('massiveaction_form_vlan_color') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=none'>".$LANG['buttons'][19]."</a> ";
			echo "<input type='submit' name='delete_color_vlan' value=\"".$LANG['buttons'][6]."\" class='submit' ></div></td></tr>";
			echo "</table>";
			echo "</div>";
			
		}
		
		echo "<table class='tab_cadre' cellpadding='5'><tr ><th colspan='3'>";
    echo $LANG['plugin_archires']['setup'][23]." : </th></tr>";
    echo "<tr class='tab_bg_1'><td>";
    plugin_archires_dropdownColors_Vlan($used);
    echo "</td><td>";
    echo "<input type='text' name=\"color\">";
    echo " <a href=\"http://www.graphviz.org/doc/info/colors.html\" target='_blank'>";	
    echo " <img alt='' src='".$CFG_GLPI["root_doc"]."/pics/aide.png' onmouseout=\"cleanhide('comments_vlan')\" onmouseover=\"cleandisplay('comments_vlan')\">";
    echo "</a><span class='over_link' id='comments_vlan'>".nl2br($LANG['plugin_archires']['setup'][23])."</span>";

    echo "<td>";
    echo "<div align='center'><input type='submit' name='add_color_vlan' value=\"".$LANG['buttons'][2]."\" class='submit' ></div></td></tr>";
    echo "</table>";
    echo "</form>";	
	}
}

function plugin_archires_config_State() {
	global $DB,$LANG,$CFG_GLPI;

	$query = "SELECT * 
			FROM `glpi_plugin_archires_statescolors` 
			ORDER BY `states_id` ASC;";
	$i=0;
	$used=array();
	
	if($result = $DB->query($query)){
		$number = $DB->numrows($result);
		
		echo "<form method='post' name='massiveaction_form_state_color' id='massiveaction_form_state_color' action=\"./plugin_archires.config.php\">";
		
		if($number != 0){
			
			echo "<div id='liste_color'>";
			echo "<table class='tab_cadre' cellpadding='5'>";
			echo "<tr>";
			if ($number > 1)
			{
				echo "<th><div align='left'>".$LANG['plugin_archires'][27]."</div></th><th><div align='left'>".$LANG['plugin_archires'][20]."</div></th><th></th>";
				echo "<th><div align='left'>".$LANG['plugin_archires'][27]."</div></th><th><div align='left'>".$LANG['plugin_archires'][20]."</div></th><th></th>";
			}
			else
			{
				echo "<th><div align='left'>".$LANG['plugin_archires'][27]."</div></th><th><div align='left'>".$LANG['plugin_archires'][20]."</div></th><th></th>";					
			}
			echo "</tr>";
		
			while($ligne= mysql_fetch_array($result)){
			
				$ID=$ligne["id"];
        $states_id=$ligne["states_id"];
        $used[]=$states_id;
				if($i  % 2==0 && $number>1)
					echo "<tr class='tab_bg_1'>";
				
				if($number==1)
					echo "<tr class='tab_bg_1'>";						
				echo "<td>".getDropdownName("glpi_states",$ligne["states_id"])."</td><td bgcolor='".$ligne["color"]."'>".$ligne["color"]."</td>";					
				echo "<td>";
				echo "<input type='hidden' name='id' value='$ID'>";
				echo "<input type='checkbox' name='item_color[$ID]' value='1'>";
				echo "</td>";
				
				$i++;
				if(($i  == $number) && ($number  % 2 !=0) && $number>1)
					echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
			
			}
			
			echo "<tr class='tab_bg_1'>";
			if ($number > 1)
				echo "<td colspan='8'>";
			else
				echo "<td colspan='4'>";
				
			echo "<div align='center'><a onclick= \"if ( markCheckboxes ('massiveaction_form_state_color') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=all'>".$LANG['buttons'][18]."</a>";
			echo " - <a onclick= \"if ( unMarkCheckboxes ('massiveaction_form_state_color') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=none'>".$LANG['buttons'][19]."</a> ";
			echo "<input type='submit' name='delete_color_state' value=\"".$LANG['buttons'][6]."\" class='submit' ></div></td></tr>";
			echo "</table>";
			echo "</div>";
				
		}
		
		echo "<table class='tab_cadre' cellpadding='5'><tr ><th colspan='3'>";
    echo $LANG['plugin_archires']['setup'][19]." : </th></tr>";
    echo "<tr class='tab_bg_1'><td>";
    plugin_archires_dropdownColors_State($used);
    echo "</td><td>";
    echo "<input type='text' name=\"color\">";
    echo " <a href=\"http://www.graphviz.org/doc/info/colors.html\" target='_blank'>";	
    echo " <img alt='' src='".$CFG_GLPI["root_doc"]."/pics/aide.png' onmouseout=\"cleanhide('comments_state')\" onmouseover=\"cleandisplay('comments_state')\">";
    echo "</a><span class='over_link' id='comments_state'>".nl2br($LANG['plugin_archires']['setup'][12])."</span>";

    echo "<td>";
    echo "<div align='center'><input type='submit' name='add_color_state' value=\"".$LANG['buttons'][2]."\" class='submit' ></div></td></tr>";
    echo "</table>";
    echo "</form>";
	}
}

?>