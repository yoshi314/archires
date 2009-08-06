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
// Original Author of file: Sébastien Prud'homme
// Purpose of file:
// ----------------------------------------------------------------------
// ----------------------------------------------------------------------
// Fichier modifiéar Pierre Bérd pour la socié GRUAU
// Le 5 mai 2006
// ----------------------------------------------------------------------
// modifier par Adrien RAVISE  aravise@citali.com
// le 12 septembre 2006
// Pour la societe CITALI
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..'); 
include (GLPI_ROOT."/inc/includes.php");

useplugin('archires',true);

$plugin = new Plugin();
if ($plugin->isActivated("network"))
	commonHeader($LANG['plugin_archires']['title'][0],$_SERVER['PHP_SELF'],"plugins","network");
else
	commonHeader($LANG['plugin_archires']['title'][0],$_SERVER["PHP_SELF"],"plugins","archires","summary");

if(plugin_archires_haveRight("archires","r") || haveRight("config","w")){
	
	if(!isset($_GET["start"])) $_GET["start"] = 0;
	if (!isset($_GET["order"])) $_GET["order"] = "ASC";
	if (!isset($_GET["field"])) $_GET["field"] = "glpi_plugin_archires_config.name";
	if (!isset($_GET["phrasetype"])) $_GET["phrasetype"] = "contains";
	if (!isset($_GET["contains"])) $_GET["contains"] = "";
	if (!isset($_GET["sort"])) $_GET["sort"] = "glpi_plugin_archires_config.name";
	if (!isset($_GET["deleted"])) $_GET["deleted"] = "0";
	
	if ($plugin->isActivated("network")){
		$PluginArchiresConfig=new PluginArchiresConfig();
		$PluginArchiresConfig->title();
	}
	plugin_archires_config_searchForm();

	plugin_archires_config_showList($_SERVER["PHP_SELF"],$_SESSION["glpiname"],$_GET["field"],$_GET["phrasetype"],$_GET["contains"],$_GET["sort"],$_GET["order"],$_GET["start"],$_GET["deleted"]);

	
}else{
	echo "<div align='center'><br><br><img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt=\"warning\"><br><br>";
	echo "<b>".$LANG['login'][5]."</b></div>";
}

commonFooter();

?>