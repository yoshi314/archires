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
header("Content-Type: text/html; charset=UTF-8");
header_nocache();

useplugin('archires',true);

$PluginArchiresProfile=new PluginArchiresProfile();
$PluginArchiresProfile->checkRight("archires","w");

commonHeader($LANG['plugin_archires']['title'][0],$_SERVER["PHP_SELF"],"plugins","archires");

if (isset($_POST["action"])&&isset($_POST["id"])&&isset($_POST["item"])&&count($_POST["item"])){
	
	$PluginArchiresView=new PluginArchiresView();
	
	switch($_POST["action"]){
		case "delete":
			$PluginArchiresView->getFromDB($_POST["id"],-1);
			foreach ($_POST["item"] as $key => $val){
				if ($val==1) {
					$PluginArchiresView->delete(array("id"=>$key),$force=0);
				}
			}
		break;
		case "purge":
			$PluginArchiresView->getFromDB($_POST["id"],-1);
			foreach ($_POST["item"] as $key => $val){
				if ($val==1) {
					$PluginArchiresView->delete(array("id"=>$key),1);
				}
			}
		break;
		case "restore":
			$PluginArchiresView->getFromDB($_POST["id"],-1);
			foreach ($_POST["item"] as $key => $val){
				if ($val==1) {
					$PluginArchiresView->restore(array("id"=>$key));
				}
			}
		break;
		case "duplicate":
		foreach ($_POST["item"] as $key => $val){
				if ($val==1){
					if ($PluginArchiresView->getFromDB($key)){
						unset($PluginArchiresView->fields["id"]);
						$PluginArchiresView->fields["entities_id"]=$_POST["entities_id"];
						$newID=$PluginArchiresView->add($PluginArchiresView->fields);
					}
			}
		}
		break;
		case "transfert":
			foreach ($_POST["item"] as $key => $val){
				if ($val==1){				
					$query="UPDATE `glpi_plugin_archires_views` 
							SET `entities_id` = '".$_POST['entities_id']."' 
							WHERE `id` ='$key'";
					$DB->query($query);
				}
			}
		break;
	}

	addMessageAfterRedirect($LANG['common'][23]);
	glpi_header($_SERVER['HTTP_REFERER']);

} else {
	
	echo "<div align='center'><img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt=\"warning\"><br><br>";
	echo "<b>".$LANG['common'][24]."</b></div>";
	
}

commonFooter();

?>