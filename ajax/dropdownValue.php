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

// Direct access to file
if (strpos($_SERVER['PHP_SELF'],"dropdownValue.php")) {
	define('GLPI_ROOT', '../../..');
	$AJAX_INCLUDE=1;
	include (GLPI_ROOT."/inc/includes.php");
  
	header("Content-Type: text/html; charset=UTF-8");
	header_nocache();
};

if (!defined('GLPI_ROOT')) {
	die("Can not acces directly to this file");
}

include_once (GLPI_ROOT."/plugins/archires/locales/".$_SESSION["glpilanguage"].".php");

checkLoginUser();

// Security
if (! TableExists($_POST['table'])) {
	exit();
}

// Make a select box with preselected values
if (!isset($_POST["limit"])) $_POST["limit"]=$CFG_GLPI["dropdown_chars_limit"];
$first=true;
$where="WHERE ";

if (in_array($_POST['table'],$CFG_GLPI["deleted_tables"])) {
	if (!$first) $where.=" AND ";
	else $first=false;
	$where.=" `is_deleted` = '0' ";
}
if (in_array($_POST['table'],$CFG_GLPI["template_tables"])) {
	if (!$first) $where.=" AND ";
	else $first=false;
	$where.=" `is_template` = '0' ";
}


$NBMAX=$CFG_GLPI["dropdown_max"];
$LIMIT="LIMIT 0,$NBMAX";
if ($_POST['searchText']==$CFG_GLPI["ajax_wildcard"]) $LIMIT="";


if (in_array($_POST['table'],$CFG_GLPI["dropdowntree_tables"])) {

	if ($_POST['searchText']!=$CFG_GLPI["ajax_wildcard"]) {
		if (!$first) $where.=" AND ";
		else $first=false;
		$where.=" completename ".makeTextSearch($_POST['searchText']);
	}


	// Manage multiple Entities dropdowns
	$add_order="";
	if (in_array($_POST['table'],$CFG_GLPI["specif_entities_tables"])) {
		$add_order=" entities_id, ";

		if (!$first) $where.=" AND ";
		else $first=false;

		if (isset($_POST["entity_restrict"])&&$_POST["entity_restrict"]>=0) {
			$where.= $_POST['table'].".entities_id='".$_POST["entity_restrict"]."'";
		} else {
			$where.=getEntitiesRestrictRequest("",$_POST['table']);
		}
	}


	if ($where=="WHERE ") $where="";


	$query = "SELECT *
			FROM `".$_POST['table']."`
			$where
			ORDER BY $add_order completename $LIMIT";
	$result = $DB->query($query);

	echo "<select id='dropdown_".$_POST["myname"].$_POST["rand"]."' name=\"".$_POST['myname']."\" size='1'>";

	if ($_POST['searchText']!=$CFG_GLPI["ajax_wildcard"]&&$DB->numrows($result)==$NBMAX)
		echo "<option class='tree' value=\"0\">--".$LANG['common'][11]."--</option>";

	switch ($_POST["table"]) {
		default :
			echo "<option class='tree' value=\"0\">-----</option>";
			break;
	}

	$outputval=getDropdownName($_POST['table'],$_POST['value']);
	if (!empty($outputval)&&$outputval!="&nbsp;")
		echo "<option class='tree' selected value='".$_POST['value']."'>".$outputval."</option>";

	if ($DB->numrows($result)) {
		while ($data =$DB->fetch_array($result)) {

			$ID = $data['id'];
			$level = $data['level'];

			if (empty($data['name'])) $output="($ID)";
			else $output=$data['name'];

			$class=" class='tree' ";
			$raquo="&raquo;";
			if ($level==1) {
				$class=" class='treeroot' ";
				$raquo="";
			}
			$style=$class;
			$addcomment="";
			if (isset($data["comments"])) $addcomment=" - ".$data["comments"];

			echo "<option value=\"$ID\" $style title=\"".$data['completename']."$addcomment\">".str_repeat("&nbsp;&nbsp;&nbsp;", $level).$raquo.substr($output,0,$_POST["limit"])."</option>";
		}

	}
	echo "</select>";

} else {
	if (!$first) $where.=" AND ";
	else $first=false;
	$where .=" id <> '".$_POST['value']."' ";

	if (in_array($_POST['table'],$CFG_GLPI["specif_entities_tables"])) {
		if (isset($_POST["entity_restrict"])&&$_POST["entity_restrict"]>=0) {
			$where.= " AND ".$_POST['table'].".entities_id='".$_POST["entity_restrict"]."'";
		} else {
			$where.=getEntitiesRestrictRequest("AND",$_POST['table']);
		}
	}

	$field="name";
	if (strstr("glpi_device",$_POST['table'])) $field="designation";

	if ($_POST['searchText']!=$CFG_GLPI["ajax_wildcard"])
		$where.=" AND $field ".makeTextSearch($_POST['searchText']);


	switch ($_POST['table']) {
		case "glpi_contacts":
			$query = "SELECT CONCAT(name,' ',firstname) AS $field, `".$_POST['table']."`.`comment`, `".$_POST['table']."`.`id`
					FROM `".$_POST['table']."`
					$where
					ORDER BY $field $LIMIT";
		break;
		default :
			$query = "SELECT *
					FROM `".$_POST['table']."`
					$where
					ORDER BY $field $LIMIT";
		break;
	}

	$result = $DB->query($query);

	echo "<select id='dropdown_".$_POST["myname"].$_POST["rand"]."' name=\"".$_POST['myname']."\" size='1'>";

	if ($_POST['searchText']!=$CFG_GLPI["ajax_wildcard"]&&$DB->numrows($result)==$NBMAX)
		echo "<option value=\"0\">--".$LANG['common'][11]."--</option>";


	echo "<option value=\"0\">-----</option>";
	$number = $DB->numrows($result);
	if ($number != 0)
	echo "<option value=\"".$_POST['itemtype'].";-1\">".$LANG['plugin_archires'][18]."</option>";
	$output=getDropdownName($_POST['table'],$_POST['value']);
	if (!empty($output)&&$output!="&nbsp;") {
		echo "<option selected value='".$_POST['value']."'>".$output."</option>";
	}

	if ($DB->numrows($result)) {
		while ($data =$DB->fetch_array($result)) {
			$output = $data[$field];
			$ID = $data['id'];
			$addcomment="";
			if (isset($data["comment"])) $addcomment=" - ".$data["comment"];

			if (empty($output)) $output="($ID)";

			echo "<option value=\"".$_POST['itemtype'].";$ID\" title=\"$output$addcomment\">".substr($output,0,$_POST["limit"])."</option>";
		}
	}
	echo "</select>";
}

if (isset($_POST["comments"])&&$_POST["comments"]) {
	$params=array('value'=>'__VALUE__','table'=>$_POST["table"]);
	ajaxUpdateItemOnSelectEvent("dropdown_".$_POST["myname"].$_POST["rand"],"comments_".$_POST["myname"].$_POST["rand"],$CFG_GLPI["root_doc"]."/ajax/comments.php",$params,false);
}

?>