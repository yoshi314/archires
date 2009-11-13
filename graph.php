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

$NEEDED_ITEMS=array("user","tracking","reservation","document","computer","device","printer","networking","peripheral","monitor","software","infocom","phone","link","ocsng","consumable","cartridge","contract","enterprise","contact","group","profile","search","mailgate","typedoc","setup","admininfo","registry","setup");
define('GLPI_ROOT', '../..'); 
include (GLPI_ROOT."/inc/includes.php");

useplugin('archires',true);

if(isset($_GET)) $tab = $_GET;
if(empty($tab) && isset($_POST)) $tab = $_POST;
if(!isset($tab["id"])) $tab["id"] = "";

if ($_GET["querytype"]==PLUGIN_ARCHIRES_LOCATIONS_QUERY){
	$object= "PluginArchiresQueryLocation";
}elseif ($_GET["querytype"]==PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY){
	$object= "PluginArchiresQueryNetworkEquipment";
}elseif ($_GET["querytype"]==PLUGIN_ARCHIRES_APPLIANCES_QUERY){
	$object= "PluginArchiresQueryAppliance";
}

$obj=new $object();
$PluginArchiresView=new PluginArchiresView();
$PluginArchires=new PluginArchires();
$PluginArchiresPrototype=new PluginArchiresPrototype();

if (isset($_GET["affiche"])){
	
	$obj->getFromDB($_GET["selectquery"]);
	glpi_header($CFG_GLPI["root_doc"]."/plugins/archires/graph.php?id=".$obj->fields["id"]."&querytype=".$_GET["querytype"]."&views_id=".$_GET["views_id"]);
} 
else{

	$plugin = new Plugin();
	if ($plugin->isActivated("network"))
		commonHeader($LANG['plugin_archires']['title'][0],$_SERVER['PHP_SELF'],"plugins","network");
	else
		commonHeader($LANG['plugin_archires']['title'][0],$_SERVER["PHP_SELF"],"plugins","archires");
	
	$obj->getFromDB($_GET["id"]);
	$views_id=$obj->fields["views_id"];
	$entities_id=$obj->fields["entities_id"];
	
	
	if($PluginArchiresView->getFromDB($obj->fields["views_id"])&&haveAccessToEntity($entities_id)){
		
		if(!isset($_GET["views_id"])) $_GET["views_id"] = $views_id;
		
		$PluginArchiresView->getFromDB($_GET["views_id"]);
		$format=$PluginArchiresView->fields["format"];

		if ($plugin->isActivated("network")){
			
			$PluginArchires->titleimg();
		}
		echo "<div align=\"center\">";
		$PluginArchiresView->viewSelect($_SERVER['PHP_SELF'],$_GET["id"],$_GET["querytype"],$_GET["views_id"]);
		
		echo "<br>";
		
		if(isset($_GET["id"]) && !empty($_GET["id"])){
		
			echo "<img src=\"image.php?id=".$_GET["id"]."&amp;querytype=".$_GET["querytype"]."&amp;views_id=".$_GET["views_id"]."\" alt=\"\" usemap=\"#G\">";
			echo $PluginArchiresPrototype->createGraph("cmapx",$obj,$_GET["id"],$_GET["views_id"]);
			
		}
		//legend
		if(isset($_GET["id"]) && !empty($_GET["id"])){
			echo "<table  cellpadding='5' border='0'>";
			echo "<tr><td class='top'>";
			if( $PluginArchiresView->fields["color"] == 0 ){
    			//legende color networkinterface		
    			$query = "SELECT * 
						FROM `glpi_plugin_archires_networkinterfacescolors` 
						ORDER BY `networkinterfaces_id` ASC ";
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
    			
    							$ID=$ligne["id"];
    							if($i  % 2==0 && $number>1)
    								echo "<tr class='tab_bg_1'>";
    							if($number==1)
    								echo "<tr class='tab_bg_1'>";						
    							echo "<td>".getDropdownName("glpi_networkinterfaces",$ligne["networkinterfaces_id"])."</td><td bgcolor='".$ligne["color"]."'>&nbsp;</td>";					
    							$i++;
    							if(($i  == $number) && ($number  % 2 !=0) && $number>1)
    								echo "<td>&nbsp;</td><td>&nbsp;</td></tr>";
    						}
    			
    					echo "</table>";
    					echo "</div>";
    				}
    			}
    			echo "</td><td class='top'>";
    		}elseif($PluginArchiresView->fields["color"] == 1 ){
    			//legende color vlan		
    			$query = "SELECT * 
						FROM `glpi_plugin_archires_vlanscolors` 
						ORDER BY `vlans_id` ASC ";
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
    			
    							$ID=$ligne["id"];
    							if($i  % 2==0 && $number>1)
    								echo "<tr class='tab_bg_1'>";
    							if($number==1)
    								echo "<tr class='tab_bg_1'>";						
    							echo "<td>".getDropdownName("glpi_vlans",$ligne["vlans_id"])."</td><td bgcolor='".$ligne["color"]."'>&nbsp;</td>";					
    							$i++;
    							if(($i  == $number) && ($number  % 2 !=0) && $number>1)
    								echo "<td>&nbsp;</td><td>&nbsp;</td></tr>";
    						}
    			
    					echo "</table>";
    					echo "</div>";
    				}
    			}
    			echo "</td><td class='top'>";										 
    		}
			//legende color state		
			$query = "SELECT * 
					FROM `glpi_plugin_archires_statescolors` 
					ORDER BY `states_id` ASC ";
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
			
							$ID=$ligne["id"];
							if($i  % 2==0 && $number>1)
								echo "<tr class='tab_bg_1'>";
							if($number==1)
								echo "<tr class='tab_bg_1'>";						
							echo "<td>".getDropdownName("glpi_states",$ligne["states_id"])."</td><td bgcolor='".$ligne["color"]."'>&nbsp;</td>";					
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