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

define('GLPI_ROOT', '../../..'); 
include (GLPI_ROOT."/inc/includes.php");

if (!isset($_GET["id"])) $_GET["id"] = "";
if (isset($_GET["start"])) $start=$_GET["start"];
else $start=0;

$PluginArchiresLocationQuery=new PluginArchiresLocationQuery();
$PluginArchiresQueryType=new PluginArchiresQueryType();

if (isset($_POST["add"])) {

	$PluginArchiresLocationQuery->check(-1,'w',$_POST);
   $newID=$PluginArchiresLocationQuery->add($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
	
} else if (isset($_POST["delete"])) {

	$PluginArchiresLocationQuery->check($_POST['id'],'w');
   $PluginArchiresLocationQuery->delete($_POST);
	glpi_header($CFG_GLPI["root_doc"]."/plugins/archires/front/archires.php");
	
} else if (isset($_POST["restore"])) {

	$PluginArchiresLocationQuery->check($_POST['id'],'w');
   $PluginArchiresLocationQuery->restore($_POST);
	glpi_header($CFG_GLPI["root_doc"]."/plugins/archires/front/archires.php");
	
} else if (isset($_POST["purge"])) {

	$PluginArchiresLocationQuery->check($_POST['id'],'w');
   $PluginArchiresLocationQuery->delete($_POST,1);
	glpi_header($CFG_GLPI["root_doc"]."/plugins/archires/front/archires.php");
	
} else if (isset($_POST["update"])) {

	$PluginArchiresLocationQuery->check($_POST['id'],'w');
   $PluginArchiresLocationQuery->update($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
	
} else if (isset($_POST["duplicate"])) {

	$PluginArchiresLocationQuery->check($_POST['id'],'w');
   unset($_POST['id']);
   $newID=$PluginArchiresLocationQuery->add($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
	
} else if (isset($_POST["addtype"])) {
	
	$test= explode(";", $_POST['type']);
	
	if (isset($test[0]) && isset($test[1])) {
		$_POST['type']= $test[1];
		$_POST['itemtype']= $test[0];
	
		if (plugin_archires_haveRight("archires","w")) {
			$PluginArchiresQueryType->addType('PluginArchiresLocationQuery',$_POST['type'],$_POST['itemtype'],$_POST['query']);
		}
	}
	glpi_header($_SERVER['HTTP_REFERER']);
	
} else if (isset($_POST["deletetype"])) {

	if (plugin_archires_haveRight("archires","w")) {
      $PluginArchiresQueryType->getFromDB($_POST["id"],-1);
	
      foreach ($_POST["item"] as $key => $val) {
         if ($val==1) {
            $PluginArchiresQueryType->deleteType($key);
         }
      }
   }
	glpi_header($_SERVER['HTTP_REFERER']);
	
} else {

	PluginArchiresProfile::checkRight("archires","r");
	
	if (!isset($_SESSION['glpi_tab'])) $_SESSION['glpi_tab']=1;
	if (isset($_GET['onglet'])) {
		$_SESSION['glpi_tab']=$_GET['onglet'];;
	}

	commonHeader($LANG['plugin_archires']['menu'][2]." ".$LANG['plugin_archires']['title'][4],$_SERVER['PHP_SELF'],"plugins","archires","location");

	$PluginArchiresLocationQuery->showForm($_SERVER["PHP_SELF"],$_GET["id"]);

	commonFooter();
}

?>