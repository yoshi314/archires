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

$NEEDED_ITEMS=array("search");
define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");

plugin_archires_checkRight("archires","r");

$plugin = new Plugin();
if ($plugin->isActivated("network"))
	commonHeader($LANG['plugin_archires']['title'][0],$_SERVER['PHP_SELF'],"plugins","network");
else
	commonHeader($LANG['plugin_archires']['title'][0],$_SERVER["PHP_SELF"],"plugins","archires","summary");

if(empty($_GET["start"])) $_GET["start"] = 0;
if(empty($_GET["order"])) $_GET["order"] = "ASC";
if(empty($_GET["phrasetype"])) $_GET["phrasetype"] = "contains";
if (!isset($_GET["deleted"])) $_GET["deleted"] = "0";

if ($plugin->isActivated("network")){
	$PluginArchiresConfig=new PluginArchiresConfig();
	$PluginArchiresConfig->title();
}	
plugin_archires_config_searchForm($_GET["field"],$_GET["phrasetype"],$_GET["contains"],$_GET["sort"],$_GET["deleted"]);

plugin_archires_config_showList($_SERVER["PHP_SELF"],$_SESSION["glpiname"],$_GET["field"],$_GET["phrasetype"],$_GET["contains"],$_GET["sort"],$_GET["order"],$_GET["start"],$_GET["deleted"]);

commonFooter();

?>