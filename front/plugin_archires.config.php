<?php
/*
   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

   http://indepnet.net/   http://glpi-archires.org/
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

// Original Author of file: GRISARD Jean Marc & CAILLAUD Xavier
// Purpose of file:
// ----------------------------------------------------------------------

if(!defined('GLPI_ROOT')){
	define('GLPI_ROOT', '../../..');
	$NEEDED_ITEMS=array("setup");
	include (GLPI_ROOT . "/inc/includes.php");
}

useplugin('archires',true);

checkRight("config","w");

$plugin = new Plugin();

if ($plugin->isActivated("network"))
	commonHeader($LANG['plugin_archires']['title'][0],$_SERVER['PHP_SELF'],"plugins","network");
elseif ($plugin->isActivated("archires"))
	commonHeader($LANG['plugin_archires']['title'][0],$_SERVER['PHP_SELF'],"plugins","archires","summary");
else
	commonHeader($LANG['common'][12],$_SERVER['PHP_SELF'],"config","plugins");

if ($plugin->isActivated("network")){
	echo "<div align='center'><table border='0'><tr><td>";
	echo "<a class='icon_consol' href=\"../index.php\">".$LANG['plugin_archires']['title'][0]."</a></td>";
	echo "<td><a  class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/network/front/plugin_network.config.php\"><b>".$LANG['plugin_network']['title'][0]."</b></a></td>";
	echo "</tr></table></div><br>";
}


$PluginArchiresItemImage=new PluginArchiresItemImage();
$PluginArchiresNetworkInterfaceColor=new PluginArchiresNetworkInterfaceColor();
$PluginArchiresVlanColor=new PluginArchiresVlanColor();
$PluginArchiresStateColor=new PluginArchiresStateColor();
	
if (isset($_POST["add"]) && isset($_POST['type'])) {

	$test= explode(";", $_POST['type']);
	
	if (isset($test[0]) && $test[0]> 0){
		$_POST['type']= $test[1];
		$_POST['itemtype']= $test[0];
	
		if(plugin_archires_haveRight("archires","w")){
				$PluginArchiresItemImage->addItemImage($_POST['type'],$_POST['itemtype'],$_POST['img']);
		}
	}
	glpi_header($_SERVER['HTTP_REFERER']);

}elseif (isset($_POST["delete"])) {
	checkRight("config","w");
	
	$PluginArchiresItemImage->getFromDB($_POST["id"],-1);
	
	foreach ($_POST["item"] as $key => $val){
		if ($val==1) {
			$PluginArchiresItemImage->deleteItemImage($key);
		}
	}
	glpi_header($_SERVER['HTTP_REFERER']);

}elseif (isset($_POST["add_color_networkinterface"]) && isset($_POST['networkinterfaces_id'])){
	
	if(plugin_archires_haveRight("archires","w")){
		$PluginArchiresNetworkInterfaceColor->addNetworkInterfaceColor($_POST['networkinterfaces_id'],$_POST['color']);
	}
	
	glpi_header($_SERVER['HTTP_REFERER']);

}elseif (isset($_POST["delete_color_networkinterface"])) {
	checkRight("config","w");
	
	$PluginArchiresNetworkInterfaceColor->getFromDB($_POST["id"],-1);
	
	foreach ($_POST["item_color"] as $key => $val){
		if ($val==1) {
			$PluginArchiresNetworkInterfaceColor->deleteNetworkInterfaceColor($key);
		}
	}
	glpi_header($_SERVER['HTTP_REFERER']);

}elseif (isset($_POST["add_color_state"]) && isset($_POST['states_id'])){
	
	if(plugin_archires_haveRight("archires","w")){
		$PluginArchiresStateColor->addStateColor($_POST['states_id'],$_POST['color']);
	}
	
	glpi_header($_SERVER['HTTP_REFERER']);

}elseif (isset($_POST["delete_color_state"])) {
	checkRight("config","w");

	$PluginArchiresStateColor->getFromDB($_POST["id"],-1);
	
	foreach ($_POST["item_color"] as $key => $val){
		if ($val==1) {
			$PluginArchiresStateColor->deleteStateColor($key);
		}
	}
	glpi_header($_SERVER['HTTP_REFERER']);

}elseif (isset($_POST["add_color_vlan"]) && isset($_POST['vlans_id'])){
	
	if(plugin_archires_haveRight("archires","w")){
		$PluginArchiresVlanColor->addVlanColor($_POST['vlans_id'],$_POST['color']);
	}
	glpi_header($_SERVER['HTTP_REFERER']);

}elseif (isset($_POST["delete_color_vlan"])) {
	checkRight("config","w");
	
	$PluginArchiresVlanColor->getFromDB($_POST["id"],-1);
	
	foreach ($_POST["item_color"] as $key => $val){
		if ($val==1) {
			$PluginArchiresVlanColor->deleteVlanColor($key);
		}
	}
	glpi_header($_SERVER['HTTP_REFERER']);

} else {

	checkRight("config","w");
					
	plugin_archires_config_Display();
	
	plugin_archires_config_NetworkInterface();
		
	plugin_archires_config_Vlan();
		
	plugin_archires_config_State();
}

	
commonFooter();

?>