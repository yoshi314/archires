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

$NEEDED_ITEMS=array("computer","printer","networking","monitor","software","peripheral","phone","tracking","document","user","enterprise","contract","infocom","group");

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
header_nocache();

useplugin('archires',true);

if(!isset($_POST["id"])) {
	exit();
}

	plugin_archires_checkRight("archires","r");

	if (empty($_POST["id"])){
		switch($_POST['glpi_tab']){
			default :
				break;
		}
	}else{
		switch($_POST['glpi_tab']){
			case -1 :
				plugin_archires_query_ShowTypes(PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY,$_POST["id"]);
				break;
			case 2 :
				plugin_archires_view_Associated(PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY,$_POST["id"]);
				plugin_archires_query_Test(PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY,$_POST["id"]);
				break;
			case 10 :
				showNotesForm($_POST['target'],PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY,$_POST["id"]);
				break;
			default :
				plugin_archires_query_ShowTypes(PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY,$_POST["id"]);
				break;
		}
		ajaxFooter();
	}

?>