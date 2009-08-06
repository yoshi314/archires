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
// Fichier modifiéar Pierre érd pour la socié GRUAU
// Le 5 mai 2006
// ----------------------------------------------------------------------
// modifier par Adrien RAVISE  aravise@citali.com
// le 12 septembre 2006
// Pour la societe CITALI
// ----------------------------------------------------------------------

$NEEDED_ITEMS=array("user","tracking","reservation","document","computer","device","printer","networking","peripheral","monitor","software","infocom","phone","link","ocsng","consumable","cartridge","contract","enterprise","contact","group","profile","search","mailgate","typedoc","setup","admininfo","registry","setup");
define('GLPI_ROOT', '../..');
include (GLPI_ROOT."/inc/includes.php");

useplugin('archires',true);

if ($_GET["type"]==PLUGIN_ARCHIRES_LOCATION_QUERY){
	$object= "PluginArchiresQueryLocation";
}elseif ($_GET["type"]==PLUGIN_ARCHIRES_SWITCH_QUERY){
	$object= "PluginArchiresQuerySwitch";
}elseif ($_GET["type"]==PLUGIN_ARCHIRES_APPLICATIFS_QUERY){
	$object= "PluginArchiresQueryApplicatifs";
}

$obj=new $object();
$output_data = plugin_archires_Create_Graph('svg',$obj,$_GET["ID"],$_GET["config"]);

header("Content-type: image/svg+xml");
header("Content-Length: " . strlen($output_data));
header('Content-Disposition: attachment; filename="image.svg"');
echo $output_data;

?>