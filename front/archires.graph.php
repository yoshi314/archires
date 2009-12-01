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

if (isset($_GET)) $tab = $_GET;
if (empty($tab) && isset($_POST)) $tab = $_POST;
if (!isset($tab["id"])) $tab["id"] = "";

$PluginArchires=new PluginArchires();
$PluginArchiresView=new PluginArchiresView();
$PluginArchiresPrototype=new PluginArchiresPrototype();

$object=$PluginArchires->getClassType($_GET["querytype"]);

$obj=new $object();

if (isset($_GET["displayview"])) {

	$obj->getFromDB($_GET["queries_id"]);
	glpi_header($CFG_GLPI["root_doc"]."/plugins/archires/front/archires.graph.php?id=".$obj->fields["id"]."&querytype=".$_GET["querytype"]."&views_id=".$_GET["views_id"]);
	
} else {

	commonHeader($LANG['plugin_archires']['title'][0],$_SERVER["PHP_SELF"],"plugins","archires");
	
	$obj->getFromDB($_GET["id"]);
	$object_view=$obj->fields["views_id"];
	$entities_id=$obj->fields["entities_id"];
	
	if ($PluginArchiresView->getFromDB($object_view)&&haveAccessToEntity($entities_id)) {
		
		if (!isset($_GET["views_id"])) $views_id = $object_view;
		else $views_id = $_GET["views_id"];
         
      $PluginArchiresPrototype->displayGraph($obj,$views_id,1);
			
	} else {
		
			glpi_header($CFG_GLPI["root_doc"]."/plugins/archires/index.php");
		}

	commonFooter();
}

?>