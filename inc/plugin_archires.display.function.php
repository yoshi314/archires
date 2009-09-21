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

function plugin_archires_Select($target,$ID,$querytype,$views_id){
	GLOBAL  $CFG_GLPI,$LANG,$DB;

	if ($querytype==PLUGIN_ARCHIRES_LOCATIONS_QUERY)
		$table="locations";
	elseif ($querytype==PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY)
		$table="networkequipments";
	elseif ($querytype==PLUGIN_ARCHIRES_APPLIANCES_QUERY)
		$table="appliances";

	$table_query="glpi_plugin_archires_".$table."queries";

	$query = "SELECT `id`, `name`,`views_id`
			FROM `".$table_query."`
			WHERE `is_deleted` = '0' ";
	// Add Restrict to current entities
	if (in_array($table_query,$CFG_GLPI["specif_entities_tables"])){
		$LINK= " AND " ;
		$query.=getEntitiesRestrictRequest($LINK,$table_query);
	}
	$query.=" ORDER BY `name` ASC";

	if($result = $DB->query($query)){

		if($DB->numrows($result) >0){
			echo "<form method='get' name='selecting' action=\"$target\">";
			echo "<table class='tab_cadre' cellpadding='5'>";
			echo "<tr class='tab_bg_1'><td align='center'>";

			//location
			echo $LANG['plugin_archires'][0]." : ";
			echo "<select name=\"selectquery\" size=\"1\"> ";

				while($ligne= mysql_fetch_array($result)){
					$location=$ligne["name"];
					$location_id=$ligne["id"];
					if ($location_id==$ID)
					echo "<option value=\"$location_id\" selected>$location</option>";
					else
					echo "<option value=\"$location_id\">$location</option>";
				}

			echo "</select></td>";

			//vue
			$query1 = "SELECT `id`, `name`
						FROM `glpi_plugin_archires_views`
						WHERE `is_deleted` = '0'";
			// Add Restrict to current entities
			if (in_array("glpi_plugin_archires_views",$CFG_GLPI["specif_entities_tables"])){
				$LINK= " AND " ;
				$query1.=getEntitiesRestrictRequest($LINK,"glpi_plugin_archires_views");
			}
			$query1.=" ORDER BY `name` ASC";

			if($result1 = $DB->query($query1)){
			echo "<td align='center'>";
			echo $LANG['plugin_archires']['title'][3]." : ";
			echo "<select name=\"views_id\" size=\"1\"> ";

				while($ligne1= mysql_fetch_array($result1)){
					$vue=$ligne1["name"];
					$vue_id=$ligne1["id"];
					if ($vue_id==$views_id)
            echo "<option value=\"$vue_id\" selected>$vue</option>";
					else
            echo "<option value=\"$vue_id\">$vue</option>";
				}

			echo "</select></td>";
			}
			echo "<td>";
			echo "<input type='hidden' name='querytype' value=\"".$querytype."\"> ";
			echo "<input type='submit' class='submit'  name='affiche' value=\"".$LANG['buttons'][2]."\"> ";
			echo "</td>";
			echo "<td>";
			if ($views_id)
				echo "<a href=\"./image.php?format=".PLUGIN_ARCHIRES_SVG_FORMAT."&amp;id=".$ID."&amp;querytype=".$querytype."&amp;views_id=".$views_id."\">".$LANG['plugin_archires']['setup'][16]."</a>";
			else
				echo "<a href=\"./image.php?format=".PLUGIN_ARCHIRES_SVG_FORMAT."&amp;id=".$ID."&amp;querytype=".$querytype."&amp;views_id=".$vue_id."\">".$LANG['plugin_archires']['setup'][16]."</a>";
			echo "</td>";
			echo "</tr>";
			echo "</table>";

			echo "</form> ";

		}
	}
}


function  plugin_archires_query_showTypes ($type,$ID) {

	global $CFG_GLPI,$DB,$LANG;

	if ($type==PLUGIN_ARCHIRES_LOCATIONS_QUERY)
		$table="location";
	elseif ($type==PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY)
		$table="networkequipment";
	elseif ($type==PLUGIN_ARCHIRES_APPLIANCES_QUERY)
		$table="appliance";

	$table_query="glpi_plugin_archires_queriestypes";

	echo "<div align='center'>";

	if(plugin_archires_haveRight("archires","w")){

		echo "<form method='post'  action=\"./plugin_archires.".$table.".form.php\">";
		echo "<table class='tab_cadre' cellpadding='5' width='34%'><tr><th colspan='2'>";
		echo $LANG['plugin_archires'][2]." : </th></tr>";
		echo "<tr class='tab_bg_1'><td>";
		$types=$CFG_GLPI["state_types"];
		plugin_archires_dropdownAllItems("type",0,0,$_SESSION["glpiactive_entity"],$types);

		echo "</td>";
		echo "<td>";

		echo "<div align='center'><input type='hidden' name='query' value='$ID'><input type='submit' name='addtype' value=\"".$LANG['buttons'][2]."\" class='submit' ></div></td></tr>";
		echo "</table>";
		echo "</form>";
	}

	$query = "SELECT *
			FROM `".$table_query."`
			WHERE `queries_id` = '".$ID."'
			AND `querytype` = '".$type."'  ";
		$query.=" ORDER BY `itemtype`, `type` ASC;";

	$i=0;
	$rand=mt_rand();
	if($result = $DB->query($query)){
		$number = $DB->numrows($result);
		if($number != 0){

			echo "<form method='post' name='massiveaction_form$rand' id='massiveaction_form$rand' action=\"./plugin_archires.".$table.".form.php\">";
			echo "<div id='liste'>";
			echo "<table class='tab_cadre' cellpadding='5'>";
			echo "<tr>";
			if ($number > 1){
				echo "<th><div align='left'>".$LANG['plugin_archires'][12]."</div></th><th><div align='left'>".$LANG['plugin_archires'][13]."</div></th><th></th>";
				echo "<th><div align='left'>".$LANG['plugin_archires'][12]."</div></th><th><div align='left'>".$LANG['plugin_archires'][13]."</div></th><th></th>";
			}else{
				echo "<th><div align='left'>".$LANG['plugin_archires'][12]."</div></th><th><div align='left'>".$LANG['plugin_archires'][13]."</div></th><th></th>";
			}
			echo "</tr>";

			while($ligne= mysql_fetch_array($result)){

				$ID=$ligne["id"];

				if($i  % 2==0 && $number>1)
					echo "<tr class='tab_bg_1'>";

				if($number==1)
					echo "<tr class='tab_bg_1'>";
				echo "<td>".plugin_archires_getDeviceType($ligne["itemtype"])."</td><td>".plugin_archires_getType($ligne["itemtype"],$ligne["type"])."</td>";
				echo "<td>";
				echo "<input type='hidden' name='id' value='$ID'>";
				echo "<input type='checkbox' name='item[$ID]' value='1'>";
				echo "</td>";

				$i++;
				if(($i  == $number) && ($number  % 2 !=0) && $number>1)
					echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
			}

			if(plugin_archires_haveRight("archires","w")){
				echo "<tr class='tab_bg_1'>";
				if ($number > 1)
					echo "<td colspan='6'>";
				else
					echo "<td colspan='3'>";

				echo "<div align='center'><a onclick= \"if ( markCheckboxes('massiveaction_form$rand') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=all'>".$LANG['buttons'][18]."</a>";
				echo " - <a onclick= \"if ( unMarkCheckboxes('massiveaction_form$rand') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=none'>".$LANG['buttons'][19]."</a> ";
				echo "<input type='submit' name='deletetype' value=\"".$LANG['buttons'][6]."\" class='submit' ></div></td></tr>";

			}
			echo "</table>";
			echo "</div>";
			echo "</form>";
		}
	}
	echo "</div>";
}

function plugin_archires_view_Associated($type,$ID) {

	global $CFG_GLPI,$DB,$LANG;

	$object= plugin_archires_getClassType($type);
	
  $obj=new $object();
	$obj->getFromDB($ID);
	$views_id=$obj->fields["views_id"];

	$PluginArchiresView=new PluginArchiresView;
	$PluginArchiresView->getFromDB($views_id);

	$name_config=$PluginArchiresView->fields["name"];

    echo "<div align='center'>";
	echo "<table class='tab_cadrehov' cellpadding='2'width='75%'>";
	echo "<tr>";
	echo "<th colspan='3'>";
	echo $LANG['plugin_archires']['setup'][20]." : ".$name_config;
	echo "</th></tr>";

	echo "<tr class='tab_bg_2' valign='top'><th>".$LANG['plugin_archires'][3]."</th><th>".$LANG['plugin_archires'][24]."</th><th>".$LANG['plugin_archires']['search'][6]."</th></tr>";

	echo "<tr class='tab_bg_1' valign='top'><td align='center'>";
	if ($PluginArchiresView->fields["computer"]!=0) echo $LANG['plugin_archires'][6]." : ".$LANG['choice'][1];
	else
		echo $LANG['plugin_archires'][6]." : ".$LANG['choice'][0];
	echo "<br>";

	if ($PluginArchiresView->fields["networking"]!=0) echo $LANG['plugin_archires'][7]." : ".$LANG['choice'][1];
	else
		echo $LANG['plugin_archires'][7]." : ".$LANG['choice'][0];
	echo "<br>";

	if ($PluginArchiresView->fields["printer"]!=0) echo $LANG['plugin_archires'][8]." : ".$LANG['choice'][1];
	else
		echo $LANG['plugin_archires'][8]." : ".$LANG['choice'][0];
	echo "<br>";

	if ($PluginArchiresView->fields["peripheral"]!=0) echo $LANG['plugin_archires'][9]." : ".$LANG['choice'][1];
	else
		echo $LANG['plugin_archires'][9]." : ".$LANG['choice'][0];
	echo "<br>";

	if ($PluginArchiresView->fields["phone"]!=0) echo $LANG['plugin_archires'][10]." : ".$LANG['choice'][1];
	else
		echo $LANG['plugin_archires'][10]." : ".$LANG['choice'][0];
	echo "</td>";

	//

	echo "<td align='center'>";
	if ($PluginArchiresView->fields["display_ports"]!=0) echo $LANG['plugin_archires'][16]." : ".$LANG['choice'][1];
	else
		echo $LANG['plugin_archires'][16]." : ".$LANG['choice'][0];
	echo "<br>";

	if ($PluginArchiresView->fields["display_ip"]!=0) echo $LANG['plugin_archires'][23]." : ".$LANG['choice'][1];
	else
		echo $LANG['plugin_archires'][23]." : ".$LANG['choice'][0];
	echo "<br>";

	if ($PluginArchiresView->fields["display_type"]!=0) echo $LANG['plugin_archires'][25]." : ".$LANG['choice'][1];
	else
		echo $LANG['plugin_archires'][25]." : ".$LANG['choice'][0];
	echo "<br>";

	if ($PluginArchiresView->fields["display_state"]!=0) echo $LANG['plugin_archires'][26]." : ".$LANG['choice'][1];
	else
		echo $LANG['plugin_archires'][26]." : ".$LANG['choice'][0];
	echo "<br>";

	if ($PluginArchiresView->fields["display_location"]!=0) echo $LANG['plugin_archires'][31]." : ".$LANG['choice'][1];
	else
		echo $LANG['plugin_archires'][31]." : ".$LANG['choice'][0];
	echo "<br>";

	if ($PluginArchiresView->fields["display_entity"]!=0) echo $LANG['plugin_archires'][32]." : ".$LANG['choice'][1];
	else
		echo $LANG['plugin_archires'][32]." : ".$LANG['choice'][0];
	echo "</td>";

	//
	echo "<td align='center'>".$LANG['plugin_archires']['setup'][11]." : ";
	echo $LANG['plugin_archires']['setup'][13]." : ";
	if ($PluginArchiresView->fields["engine"]!=0) echo "Neato";
	else
		echo "Dot";
	echo "<br>";
	echo $LANG['plugin_archires']['setup'][15]." : ";
	if ($PluginArchiresView->fields["format"]==PLUGIN_ARCHIRES_JPEG_FORMAT) $format_graph="jpeg";
  elseif ($PluginArchiresView->fields["format"]==PLUGIN_ARCHIRES_PNG_FORMAT) $format_graph="png";
  elseif ($PluginArchiresView->fields["format"]==PLUGIN_ARCHIRES_GIF_FORMAT) $format_graph="gif";
  echo $format_graph;

	echo "</td></tr>";

	echo "</table></div>";

}

function plugin_archires_brut($string) {

	$string = str_replace(">", " - ", $string);
	$string = str_replace("&", " - ", $string);
	return $string;
}

function plugin_archires_findChilds($DB, $parent){

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
				$queryBranch .= plugin_archires_findChilds($DB, $child);
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
				$queryBranch .= plugin_archires_findChilds($DB, $child);
			}
		}
	}
    return $queryBranch;
}

function plugin_archires_findLevels($DB,$parent){

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

function plugin_archires_test_Graphviz() {

$graph ="graph G {
	a;
	b;
	c -- d;
	a -- c;}";

$format="png";
$engine="dot";

	//print $graph;
	$Path = GLPI_PLUGIN_DOC_DIR."/archires";
    $graph_name = tempnam($Path, "");
    $output_name = tempnam($Path, "");

    if ($graph_file = fopen($graph_name, "w")) {
        fputs($graph_file, $graph);
        fclose($graph_file);
		//$command = $engine." -T" . $format . " -o " . $output_name . " " . $graph_name;
    $command = $engine." -T" .$format." -o \"".$output_name ."\" \"".$graph_name."\"";
		`$command`;

        unlink($graph_name);

        if ($output_file = fopen($output_name, "rb")) {
            $output_data = fread($output_file, filesize($output_name));
            fclose($output_file);
            unlink($output_name);

            return $output_data;
        }
    }
}

function plugin_archires_display_Image_Device($type,$itemtype,$test) {

	global $CFG_GLPI,$DB,$LANG;

	$path="";
	if ($test)
		$path="../";

	$image_name = $path."pics/nothing.png";
	$query = "SELECT *
			FROM `glpi_plugin_archires_imageitems`
			WHERE `itemtype` = '".$itemtype."';";
			if($result = $DB->query($query)){
				while($ligne= mysql_fetch_array($result)){
					$config_img=$ligne["img"];

					if ($type == $ligne["type"])
					$image_name = $path."pics/$config_img";
				}
			}

	return $image_name;

}

function plugin_archires_display_Type_And_IP($PluginArchiresView,$itemtype,$device,$generation) {

		$graph ="";
		if ($PluginArchiresView->fields["display_ip"]!=0 && isset($device["ip"])){
			if ($PluginArchiresView->fields["display_type"]!=0 && !empty($device["type"])){
				if (!$generation)
					$graph = plugin_archires_getType($itemtype,$device["type"]) . " " . $device["ip"];
				else
					$graph =" - ".plugin_archires_getType($itemtype,$device["type"])."</td></tr><tr><td>".$device["ip"]."</td></tr>";

			}else{
				if (!$generation)
					$graph = $device["ip"];
				else
					$graph ="</td></tr><tr><td>".$device["ip"]."</td></tr>";
			}
		}else{
			if ($PluginArchiresView->fields["display_type"]!=0 && !empty($device["type"])){
				if (!$generation)
					$graph =plugin_archires_getType($itemtype,$device["type"]);
				else
					$graph ="</td></tr><tr><td>".plugin_archires_getType($itemtype,$device["type"])."</td></tr>";
			}else{
				if (!$generation)
					echo "";
				else
					$graph ="</td></tr>";
			}
		}
	return $graph;

}

function plugin_archires_display_Users($url,$device,$generation) {

		$graph ="";
		if ($device["users_id"]){
			if ($generation)
				$graph = "URL=\"".$url."\" tooltip=\"".getusername($device["users_id"])."\"";
			else
				$graph = "<a href='".$url."'>".getusername($device["users_id"])."</a>";
		}elseif (!$device["users_id"] && $device["groups_id"]){
			if ($generation)
				$graph = "URL=\"".$url."\" tooltip=\"".getdropdownname("glpi_groups",$device["groups_id"])."\"";
			else
				$graph = "<a href='".$url."'>".getdropdownname("glpi_groups",$device["groups_id"])."</a>";
		}elseif (!$device["users_id"] && !$device["groups_id"] && $device["contact"]){
			if ($generation)
				$graph = "URL=\"".$url."\" tooltip=\"".$device["contact"]."\"";
			else
				$graph = "<a href='".$url."'>".$device["contact"]."</a>";
		}else{
			if ($generation)
				$graph = "URL=\"".$url."\" tooltip=\"".$device["name"]."\"";
			else
				$graph = "<a href='".$url."'>".$device["name"]."</a>";
		}
	return $graph;

}


function plugin_archires_display_Color_State($device) {

	global $CFG_GLPI,$DB,$LANG;

		$graph ="";
		$query_state = "SELECT *
		FROM `glpi_plugin_archires_statescolors`
		WHERE `states_id`= '".$device["states_id"]."';";
		$result_state = $DB->query($query_state);
		$number_state = $DB->numrows($result_state);

		if($number_state != 0 && $device["states_id"] > 0){
			$color_state=$DB->result($result_state,0,"color");
			$graph ="<font color=\"$color_state\">".getDropdownName("glpi_states",$device["states_id"])."</font>";
		}elseif($number_state == 0 && $device["states_id"] > 0){
			$graph =getDropdownName("glpi_states",$device["states_id"]);
		}

	return $graph;

}

?>