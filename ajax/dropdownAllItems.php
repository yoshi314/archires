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

$AJAX_INCLUDE=1;
define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
header_nocache();

// Make a select box

if (isset($_POST["typetable"])) {

	$test= explode(";", $_POST['typetable']);
	$table= $test[1];
	$itemtype= $test[0];
	// Link to user for search only > normal users
	$link="dropdownValue.php";

	$rand=mt_rand();

	$use_ajax=false;
	if ($CFG_GLPI["use_ajax"]&&countElementsInTable($table)>$CFG_GLPI["ajax_limit_count"]) {
		$use_ajax=true;
	}

     $params=array('searchText'=>'__VALUE__',
                     'itemtype'=>$itemtype,
                     'table'=>$table,
                     'rand'=>$rand,
                     'myname'=>$_POST["myname"],
                     );

	if (isset($_POST['value'])) {
		$params['value']=$_POST['value'];
	}
	if (isset($_POST['entity_restrict'])) {
		$params['entity_restrict']=$_POST['entity_restrict'];
	}
	
	$default="<select name='".$_POST["myname"]."'><option value='0'>------</option></select>";
	ajaxDropdown($use_ajax,"/plugins/archires/ajax/$link",$params,$default,$rand);

	if (isset($_POST['value'])&&$_POST['value']>0) {
		$params['searchText']=$CFG_GLPI["ajax_wildcard"];
		echo "<script type='text/javascript' >\n";
		echo "document.getElementById('search_$rand').value='".$CFG_GLPI["ajax_wildcard"]."';";
		echo "</script>\n";
		ajaxUpdateItem("results_$rand",$CFG_GLPI["root_doc"]."/plugins/archires/ajax/$link",$params);
	}
}
	
?>