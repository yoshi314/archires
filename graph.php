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

$NEEDED_ITEMS=array("user","tracking","reservation","document","computer","device","printer","networking","peripheral","monitor","software","infocom","phone","link","ocsng","consumable","cartridge","contract","enterprise","contact","group","profile","search","mailgate","typedoc","setup","admininfo","registry","setup");
define('GLPI_ROOT', '../..'); 
include (GLPI_ROOT."/inc/includes.php");

useplugin('archires',true);

if(isset($_GET)) $tab = $_GET;
if(empty($tab) && isset($_POST)) $tab = $_POST;
if(!isset($tab["ID"])) $tab["ID"] = "";

if ($_GET["type"]==PLUGIN_ARCHIRES_LOCATION_QUERY){
	$object= "PluginArchiresQueryLocation";
}elseif ($_GET["type"]==PLUGIN_ARCHIRES_SWITCH_QUERY){
	$object= "PluginArchiresQuerySwitch";
}elseif ($_GET["type"]==PLUGIN_ARCHIRES_APPLICATIFS_QUERY){
	$object= "PluginArchiresQueryApplicatifs";
}

$obj=new $object();
$PluginArchiresConfig=new PluginArchiresConfig();
$PluginArchires=new PluginArchires();

if (isset($_GET["affiche"]))
{	
	
	$obj->getFromDB($_GET["selectloc"]);
	glpi_header($CFG_GLPI["root_doc"]."/plugins/archires/graph.php?ID=".$obj->fields["ID"]."&type=".$_GET["type"]."&config=".$_GET["selectvue"]);
} 
else{

	$plugin = new Plugin();
	if ($plugin->isActivated("network"))
		commonHeader($LANG['plugin_archires']['title'][0],$_SERVER['PHP_SELF'],"plugins","network");
	else
		commonHeader($LANG['plugin_archires']['title'][0],$_SERVER["PHP_SELF"],"plugins","archires");
	
	$obj->getFromDB($_GET["ID"]);
	$FK_config=$obj->fields["FK_config"];
	$FK_entities=$obj->fields["FK_entities"];
	
	
	if($PluginArchiresConfig->getFromDB($obj->fields["FK_config"])&&haveAccessToEntity($FK_entities)){
		
		if(!isset($_GET["config"])) $_GET["config"] = $FK_config;
		
		$PluginArchiresConfig->getFromDB($_GET["config"]);
		$format=$PluginArchiresConfig->fields["format"];

		if ($plugin->isActivated("network")){
			
			$PluginArchires->titleimg();
		}
		echo "<div align=\"center\">";
		plugin_archires_Select($_SERVER['PHP_SELF'],$_GET["ID"],$_GET["type"],$_GET["config"]);
		
		echo "<br>";
		
		if(isset($_GET["ID"]) && !empty($_GET["ID"])){
		
			echo "<img src=\"image.php?ID=".$_GET["ID"]."&amp;type=".$_GET["type"]."&amp;config=".$_GET["config"]."\" alt=\"\" usemap=\"#G\">";
			echo plugin_archires_Create_Graph("cmapx",$obj,$_GET["ID"],$_GET["config"]);
			
		}
		//legend
		if(isset($_GET["ID"]) && !empty($_GET["ID"])){
			echo "<table  cellpadding='5' border='0'>";
			echo "<tr><td valign='top'>";
			if( $PluginArchiresConfig->fields["color"] == 0 ){
    			//legende color iface		
    			$query = "SELECT * 
						FROM `glpi_plugin_archires_color_iface` 
						ORDER BY `iface` ASC ";
    			if($result = $DB->query($query)){
    				$number = $DB->numrows($result);
    				if($number != 0){			
    					$i=0;
    					echo "<div align='center'>";
    					echo "<table class='tab_cadre' cellpadding='5'>";
    					echo "<tr>";
    					echo "<th colspan='4'>".$LANG['plugin_archires'][22]." ".$LANG['plugin_archires'][19]."</th>";
    					echo "</tr>";
    						while($ligne= mysql_fetch_array($result)){
    			
    							$ID=$ligne["ID"];
    							if($i  % 2==0 && $number>1)
    								echo "<tr class='tab_bg_1'>";
    							if($number==1)
    								echo "<tr class='tab_bg_1'>";						
    							echo "<td>".getDropdownName("glpi_dropdown_iface",$ligne["iface"])."</td><td bgcolor='".$ligne["color"]."'>&nbsp;</td>";					
    							$i++;
    							if(($i  == $number) && ($number  % 2 !=0) && $number>1)
    								echo "<td>&nbsp;</td><td>&nbsp;</td></tr>";
    						}
    			
    					echo "</table>";
    					echo "</div>";
    				}
    			}
    			echo "</td><td valign='top'>";
    		}elseif($PluginArchiresConfig->fields["color"] == 1 ){
    			//legende color vlan		
    			$query = "SELECT * 
						FROM `glpi_plugin_archires_color_vlan` 
						ORDER BY `vlan` ASC ";
    			if($result = $DB->query($query)){
    				$number = $DB->numrows($result);
    				if($number != 0){			
    					$i=0;
    					echo "<div align='center'>";
    					echo "<table class='tab_cadre' cellpadding='5'>";
    					echo "<tr>";
    					echo "<th colspan='4'>".$LANG['plugin_archires'][22]." ".$LANG['plugin_archires'][35]."</th>";
    					echo "</tr>";
    						while($ligne= mysql_fetch_array($result)){
    			
    							$ID=$ligne["ID"];
    							if($i  % 2==0 && $number>1)
    								echo "<tr class='tab_bg_1'>";
    							if($number==1)
    								echo "<tr class='tab_bg_1'>";						
    							echo "<td>".getDropdownName("glpi_dropdown_vlan",$ligne["vlan"])."</td><td bgcolor='".$ligne["color"]."'>&nbsp;</td>";					
    							$i++;
    							if(($i  == $number) && ($number  % 2 !=0) && $number>1)
    								echo "<td>&nbsp;</td><td>&nbsp;</td></tr>";
    						}
    			
    					echo "</table>";
    					echo "</div>";
    				}
    			}
    			echo "</td><td valign='top'>";										 
    		}
			//legende color state		
			$query = "SELECT * 
					FROM `glpi_plugin_archires_color_state` 
					ORDER BY `state` ASC ";
			if($result = $DB->query($query)){
				$number = $DB->numrows($result);
				if($number != 0){			
					$i=0;
					echo "<div align='center'>";
					echo "<table class='tab_cadre' cellpadding='5'>";
					echo "<tr>";
					echo "<th colspan='4'>".$LANG['plugin_archires'][22]." ".$LANG['plugin_archires'][27]."</th>";
					echo "</tr>";
						while($ligne= mysql_fetch_array($result)){
			
							$ID=$ligne["ID"];
							if($i  % 2==0 && $number>1)
								echo "<tr class='tab_bg_1'>";
							if($number==1)
								echo "<tr class='tab_bg_1'>";						
							echo "<td>".getDropdownName("glpi_dropdown_state",$ligne["state"])."</td><td bgcolor='".$ligne["color"]."'>&nbsp;</td>";					
							$i++;
							if(($i  == $number) && ($number  % 2 !=0) && $number>1)
								echo "<td>&nbsp;</td><td>&nbsp;</td></tr>";
						}
			
					echo "</table>";
					echo "</div>";
				}
			}
			echo "</td></tr>";
			echo "</table>";
			
		}else{
		
			echo "<div align='center'><br><br><img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt=\"warning\"><br><br>";
			echo "<b>".$LANG['plugin_archires'][1]."</b></div>";
		}
	}else{
		
			glpi_header($CFG_GLPI["root_doc"]."/plugins/archires/index.php");
		}
	echo "</div>";
	commonFooter();
}

?>