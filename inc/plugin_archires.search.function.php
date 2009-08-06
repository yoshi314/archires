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

if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
	}

function plugin_archires_config_SearchForm($field="",$phrasetype= "",$contains="",$sort= "",$deleted= "") {
	// Print Search Form
	GLOBAL $CFG_GLPI,$LANG;

	$option["glpi_plugin_archires_config.ID"]		= $LANG['plugin_archires']['search'][0];
	$option["glpi_plugin_archires_config.name"]	= $LANG['plugin_archires']['search'][1];

	echo "<form method='get' action=\"./plugin_archires.search.config.php\">";
	echo "<div align='center'><table  width='750' class='tab_cadre'>";
	echo "<tr><th colspan='4'>".$LANG['search'][0].":</th></tr>";
	echo "<tr class='tab_bg_1'>";
	echo "<td align='center'>";
	echo "<input type='text' size='15' name=\"contains\" value=\"". $contains ."\" >";
	echo "&nbsp;";
	echo $LANG['search'][10]."&nbsp;<select name=\"field\" size='1'>";
	echo "<option value='all' ";
	if($field == "all") echo "selected";
	echo ">".$LANG['common'][66]."</option>";
	reset($option);
	foreach ($option as $key => $val) {
		echo "<option value=\"".$key."\"";
		if($key == $field) echo "selected";
		echo ">". substr($val, 0, 18) ."</option>\n";
	}
	echo "</select>&nbsp;";

	echo $LANG['search'][4];
	echo "&nbsp;<select name='sort' size='1'>";
	reset($option);
	foreach ($option as $key => $val) {
		echo "<option value=\"".$key."\"";
		if($key == $sort) echo "selected";
		echo ">".$val."</option>\n";
	}
	echo "</select> ";

	echo "<td>";
	dropdownyesno("deleted",$deleted);

	echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/showdeleted.png\" alt='".$LANG['common'][3]."' title='".$LANG['common'][3]."'>";

	echo "</td>";
	// Display Reset search
	echo "<td align='center'>";
	echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/archires/front/plugin_archires.config.index.php?reset_search=reset_search&amp;type='".PLUGIN_ARCHIRES_VIEW_TYPE."' ><img title=\"".$LANG['buttons'][16]."\" alt=\"".$LANG['buttons'][16]."\" src='".$CFG_GLPI["root_doc"]."/pics/reset.png' class='calendrier'></a>";
	showSaveBookmarkButton(BOOKMARK_SEARCH,PLUGIN_ARCHIRES_VIEW_TYPE);

	echo "</td>";

	echo "<td width='80' align='center' class='tab_bg_2'>";
	echo "<input type='submit' value=\"".$LANG['buttons'][0]."\" class='submit'>";
	echo "</td></tr></table></div></form>";
}


/**
 * Search and list sup
 *
 *
 * Build the query, make the search and list contacts after a search.
 *
 *@param $target filename where to go when done.
 *@param $username not used to be deleted.
 *@param $field the field in witch the search would be done
 *@param $contains the search string
 *@param $sort the "sort by" field value
 *@param $order ASC or DSC (for mysql query)
 *@param $start row number from witch we start the query (limit $start,xxx)
 *@param $deleted Query on deleted items or not.
 *@param $phrasetype='' not used (to be deleted)
 *
 *@return Nothing (display)
 *
 **/
function plugin_archires_config_ShowList($target,$username,$field,$phrasetype,$contains,$sort,$order,$start,$deleted) {

	GLOBAL $DB,$CFG_GLPI,$LANG;

	$first=true;
	// Build query
	if($field=="all") {
		$where = " (";
		$fields = $DB->list_fields("glpi_plugin_archires_config");
		$columns = count($fields);
		$i=0;
		foreach ($fields as $key => $val) {
			if($i != 0) {
				$where .= " OR ";
			}

			$where .= "glpi_plugin_archires_config.".$key . " LIKE '%".$contains."%'";

			$i++;
		}
		$where .= ")";

	}

	else {
		if ($phrasetype == "contains") {
			$where = "($field LIKE '%".$contains."%')";
		}
		else {
			$where = "($field LIKE '".$contains."')";
		}
	}

	if (!$start) {
		$start = 0;
	}
	if (!$order) {
		$order = "ASC";
	}
	$query = "SELECT *
			FROM glpi_plugin_archires_config ";
	$query .= "WHERE ";
	if (!empty($where)) $query .= " $where AND";

	$itemtable="glpi_plugin_archires_config";

	// Add deleted if item have it
	if (in_array($itemtable,$CFG_GLPI["deleted_tables"])){
		$LINK= " AND " ;
		if ($first) {$LINK=" ";$first=false;}
		$query.= $LINK.$itemtable.".deleted='$deleted' ";
	}
	// Remove template items
	if (in_array($itemtable,$CFG_GLPI["template_tables"])){
		$LINK= " AND " ;
		if ($first) {$LINK=" ";$first=false;}
		$query.= $LINK.$itemtable.".is_template='0' ";
	}

	// Add Restrict to current entities
	if (in_array($itemtable,$CFG_GLPI["specif_entities_tables"])){
		$LINK= " AND " ;
		if ($first) {$LINK=" ";$first=false;}

		$query.=getEntitiesRestrictRequest($LINK,$itemtable);
	}

	$query .= " ORDER BY $sort $order";
	// Get it from database
	if ($result = $DB->query($query)) {
		$numrows =  $DB->numrows($result);

		// Limit the result, if no limit applies, use prior result
		if ($numrows > $_SESSION["glpilist_limit"]) {
			$query_limit = $query ." LIMIT $start,".$_SESSION["glpilist_limit"]." ";
			$result_limit = $DB->query($query_limit);
			$numrows_limit = $DB->numrows($result_limit);
		} else {
			$numrows_limit = $numrows;
			$result_limit = $result;
		}


		if ($numrows_limit>0) {
			// Pager
			$parameters="field=$field&amp;phrasetype=$phrasetype&amp;contains=$contains&amp;sort=$sort&amp;order=$order";
			printPager($start,$numrows,$target,$parameters);


			echo "<form method='post' name='massiveaction_form' id='massiveaction_form' action=\"../ajax/massiveactionarchires_config.php\">";
			// Produce headline
			echo "<div align='center'><table  class='tab_cadrehov'><tr>";
			// Name
			if(plugin_archires_haveRight("archires","w"))
				echo "<th></th>";

			echo "<th>";
			if ($sort=="glpi_plugin_archires_config.name") {
				if ($order=="DESC") echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/puce-down.png\" alt='' title=''>";
				else echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/puce-up.png\" alt='' title=''>";
			}
			echo "<a href=\"$target?field=$field&amp;phrasetype=$phrasetype&amp;contains=$contains&amp;sort=glpi_plugin_archires_config.name&amp;order=".($order=="ASC"?"DESC":"ASC")."&amp;start=$start\">";
			echo $LANG['plugin_archires']['search'][1]."</a></th>";

			//items
			echo "<th>";
			echo $LANG['plugin_archires'][3]."</th>";

			//display_ports
			echo "<th>";
			echo $LANG['plugin_archires'][24]."</th>";

			//engine
			echo "<th>";
			if ($sort=="glpi_plugin_archires_config.engine") {
				if ($order=="DESC") echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/puce-down.png\" alt='' title=''>";
				else echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/puce-up.png\" alt='' title=''>";
			}
			echo "<a href=\"$target?field=$field&amp;phrasetype=$phrasetype&amp;contains=$contains&amp;sort=glpi_plugin_archires_config.engine&amp;order=".($order=="ASC"?"DESC":"ASC")."&amp;start=$start\">";
			echo $LANG['plugin_archires']['setup'][13]."</a></th>";

			//format
			echo "<th>";
			if ($sort=="glpi_plugin_archires_config.format") {
				if ($order=="DESC") echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/puce-down.png\" alt='' title=''>";
				else echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/puce-up.png\" alt='' title=''>";
			}
			echo "<a href=\"$target?field=$field&amp;phrasetype=$phrasetype&amp;contains=$contains&amp;sort=glpi_plugin_archires_config.format&amp;order=".($order=="ASC"?"DESC":"ASC")."&amp;start=$start\">";
			echo $LANG['plugin_archires']['setup'][15]."</a></th>";

			echo "</tr>";

			for ($i=0; $i < $numrows_limit; $i++) {

				$ID = $DB->result($result_limit, $i, "ID");
				$PluginArchiresConfig = new PluginArchiresConfig;
				$PluginArchiresConfig->getfromDB($ID);

				$sel="";
				if (isset($_GET["select"])&&$_GET["select"]=="all") $sel="checked";

				echo displaySearchNewLine(HTML_OUTPUT,$i%2);
				//	echo "<td>";

				if(plugin_archires_haveRight("archires","w"))
					echo "<td width='5'><input type='checkbox' name='item[$ID]' value='1' $sel></td>";
				echo "<td>";
				echo "<a href=\"./plugin_archires.config.form.php?ID=$ID\">";
				echo $PluginArchiresConfig->fields["name"]."";
				if ($_SESSION["glpiview_ID"] == 1 ||empty($PluginArchiresConfig->fields["name"])){
					echo " (";
					echo $PluginArchiresConfig->fields["ID"].")";
				}
				echo "</a></td>";

				echo "<td align='center'>";
				if ($PluginArchiresConfig->fields["computer"]!=0) echo $LANG['plugin_archires'][6]." : ".$LANG['choice'][1];
				else
				echo $LANG['plugin_archires'][6]." : ".$LANG['choice'][0];
				echo "<br>";

				if ($PluginArchiresConfig->fields["networking"]!=0) echo $LANG['plugin_archires'][7]." : ".$LANG['choice'][1];
				else
				echo $LANG['plugin_archires'][7]." : ".$LANG['choice'][0];
				echo "<br>";

				if ($PluginArchiresConfig->fields["printer"]!=0) echo $LANG['plugin_archires'][8]." : ".$LANG['choice'][1];
				else
				echo $LANG['plugin_archires'][8]." : ".$LANG['choice'][0];
				echo "<br>";

				if ($PluginArchiresConfig->fields["peripheral"]!=0) echo $LANG['plugin_archires'][9]." : ".$LANG['choice'][1];
				else
				echo $LANG['plugin_archires'][9]." : ".$LANG['choice'][0];
				echo "<br>";

				if ($PluginArchiresConfig->fields["phone"]!=0) echo $LANG['plugin_archires'][10]." : ".$LANG['choice'][1];
				else
				echo $LANG['plugin_archires'][10]." : ".$LANG['choice'][0];
				echo "</td>";

				echo "<td align='center'>";
				if ($PluginArchiresConfig->fields["display_ports"]!=0) echo $LANG['plugin_archires'][16]." : ".$LANG['choice'][1];
				else
				echo $LANG['plugin_archires'][16]." : ".$LANG['choice'][0];
				echo "<br>";

				if ($PluginArchiresConfig->fields["display_ip"]!=0) echo $LANG['plugin_archires'][23]." : ".$LANG['choice'][1];
				else
				echo $LANG['plugin_archires'][23]." : ".$LANG['choice'][0];
				echo "<br>";

				if ($PluginArchiresConfig->fields["display_type"]!=0) echo $LANG['plugin_archires'][25]." : ".$LANG['choice'][1];
				else
				echo $LANG['plugin_archires'][25]." : ".$LANG['choice'][0];
				echo "<br>";

				if ($PluginArchiresConfig->fields["display_state"]!=0) echo $LANG['plugin_archires'][26]." : ".$LANG['choice'][1];
				else
				echo $LANG['plugin_archires'][26]." : ".$LANG['choice'][0];
				echo "<br>";

				if ($PluginArchiresConfig->fields["display_location"]!=0) echo $LANG['plugin_archires'][31]." : ".$LANG['choice'][1];
				else
				echo $LANG['plugin_archires'][31]." : ".$LANG['choice'][0];
				echo "<br>";

				if ($PluginArchiresConfig->fields["display_entity"]!=0) echo $LANG['plugin_archires'][32]." : ".$LANG['choice'][1];
				else
				echo $LANG['plugin_archires'][32]." : ".$LANG['choice'][0];
				echo "</td>";

				echo "<td align='center'>";
				if ($PluginArchiresConfig->fields["engine"]!=0) echo "Neato";
				else
				echo "Dot";
				echo "</td>";

				echo "<td align='center'>";
				if ($PluginArchiresConfig->fields["format"]=='0') echo "jpeg ";
				elseif ($PluginArchiresConfig->fields["format"]=='1') echo "png ";
				elseif ($PluginArchiresConfig->fields["format"]=='2') echo "gif ";

				echo "</td>";

				echo "</tr>";
			}

			// Close Table
			echo "</table></div>";
			//massive action
			if(plugin_archires_haveRight("archires","w")){
				echo "<div align='center'>";
				echo "<table width='80%'>";
				 echo "<tr><td><img src=\"".$CFG_GLPI["root_doc"]."/pics/arrow-left.png\" alt=''></td><td align='center'><a onclick= \"if ( markCheckboxes('massiveaction_form') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=all' >".$LANG['buttons'][18]."</a></td>";
				echo "<td>/</td><td align='center'><a onclick= \"if ( unMarkCheckboxes('massiveaction_form') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=none'>".$LANG['buttons'][19]."</a>";
				echo "</td><td align='left' width='80%'>";
				plugin_archires_dropdownMassiveAction_Config($ID,$deleted);
				echo "</td>";
				echo "</table>";
			}
			echo "</div></form>";

			// Pager
			echo "<br>";
			printPager($start,$numrows,$target,$parameters);

		} else {
			echo "<div align='center'><b>".$LANG['plugin_archires']['search'][7]."</b></div>";
		}
	}
}

?>