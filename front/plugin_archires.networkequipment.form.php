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
define('GLPI_ROOT', '../../..'); 
include (GLPI_ROOT."/inc/includes.php");

useplugin('archires',true);

if(isset($_GET)) $tab = $_GET;
if(empty($tab) && isset($_POST)) $tab = $_POST;
if(!isset($tab["id"])) $tab["id"] = "";

if (isset($_GET["start"])) $start=$_GET["start"];
else $start=0;

$PluginArchiresQueryNetworkEquipment=new PluginArchiresQueryNetworkEquipment();
$PluginArchiresQueryType=new PluginArchiresQueryType();

if (isset($_POST["add"]))
{
	if(plugin_archires_haveRight("archires","w"))
		$newID=$PluginArchiresQueryNetworkEquipment->add($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
} 
else if (isset($_POST["delete"]))
{
	if(plugin_archires_haveRight("archires","w"))
		$PluginArchiresQueryNetworkEquipment->delete($_POST);
	glpi_header($CFG_GLPI["root_doc"]."/plugins/archires/index.php");
}
else if (isset($_POST["restore"]))
{
	if(plugin_archires_haveRight("archires","w"))
		$PluginArchiresQueryNetworkEquipment->restore($_POST);
	glpi_header($CFG_GLPI["root_doc"]."/plugins/archires/index.php");
}
else if (isset($_POST["purge"]))
{
	if(plugin_archires_haveRight("archires","w"))
		$PluginArchiresQueryNetworkEquipment->delete($_POST,1);
	glpi_header($CFG_GLPI["root_doc"]."/plugins/archires/index.php");
}
else if (isset($_POST["update"]))
{
	if(plugin_archires_haveRight("archires","w"))
		$PluginArchiresQueryNetworkEquipment->update($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
}
else if (isset($_POST["duplicate"])){

	if(plugin_archires_haveRight("archires","w"))
		unset($_POST['id']);
		$newID=$PluginArchiresQueryNetworkEquipment->add($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
}
//type
else if (isset($_POST["addtype"])){
		
	$test= explode(";", $_POST['type']);
	
	if (isset($test[0]) && isset($test[1])){
		$_POST['type']= $test[1];
		$_POST['itemtype']= $test[0];
	
		if(plugin_archires_haveRight("archires","w")){
				$PluginArchiresQueryType->addType(PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY,$_POST['type'],$_POST['itemtype'],$_POST['query']);
		}
	}
	glpi_header($_SERVER['HTTP_REFERER']);
}
else if (isset($_POST["deletetype"])){

	if(plugin_archires_haveRight("archires","w"))
	$PluginArchiresQueryType->getFromDB($_POST["id"],-1);
	
	foreach ($_POST["item"] as $key => $val){
		if ($val==1) {
			$PluginArchiresQueryType->deleteType($key);
		}
	}
	glpi_header($_SERVER['HTTP_REFERER']);
	
}
else
{
	$PluginArchiresProfile=new PluginArchiresProfile();
	$PluginArchiresProfile->checkRight("archires","r");
	
	if (!isset($_SESSION['glpi_tab'])) $_SESSION['glpi_tab']=1;
	if (isset($_GET['onglet'])) {
		$_SESSION['glpi_tab']=$_GET['onglet'];
		//		glpi_header($_SERVER['HTTP_REFERER']);
	}

	$plugin = new Plugin();
	if ($plugin->isActivated("network"))
		commonHeader($LANG['plugin_archires']['title'][0],$_SERVER['PHP_SELF'],"plugins","network");
	else
		commonHeader($LANG['plugin_archires']['title'][0],$_SERVER['PHP_SELF'],"plugins","archires","networkequipments");

	$PluginArchiresQueryNetworkEquipment->showForm($_SERVER["PHP_SELF"],$tab["id"]);

	commonFooter();
}

?>