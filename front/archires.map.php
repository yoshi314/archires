<?php
/*
 * @version $Id: HEADER 2010-10-31 21:36:26 tsmr $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

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
// Original Author of file: CAILLAUD Xavier & COLLET Remi & LASSON Nelly & PRUDHOMME Sebastien
// Purpose of file: plugin archires v1.8.1 - GLPI 0.78
// ----------------------------------------------------------------------
*/

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");

$PluginArchiresView      = new PluginArchiresView();
$PluginArchiresPrototype = new PluginArchiresPrototype();

$PluginArchiresView->getFromDB($_GET["plugin_archires_views_id"]);
if (isset($_GET["format"])) {
   $format = $_GET["format"];
} else {
   $format = $PluginArchiresView->fields["format"];
}
if ($format == PLUGIN_ARCHIRES_JPEG_FORMAT) {
   $format_graph = "jpeg";
} else if ($format == PLUGIN_ARCHIRES_PNG_FORMAT) {
   $format_graph = "png";
} else if ($format == PLUGIN_ARCHIRES_GIF_FORMAT) {
   $format_graph = "gif";
} else if ($format == PLUGIN_ARCHIRES_SVG_FORMAT) {
   $format_graph = "svg";
}
$object = $_GET["querytype"];
$obj = new $object();
$obj->getFromDB($_GET["id"]);
$object_view = $obj->fields["plugin_archires_views_id"];

if (!isset($_GET["plugin_archires_views_id"])) {
   $plugin_archires_views_id = $object_view;
} else {
   $plugin_archires_views_id = $_GET["plugin_archires_views_id"];
}
$output_data = $PluginArchiresPrototype->createGraph($format_graph,$obj,$plugin_archires_views_id);

if ($format==PLUGIN_ARCHIRES_SVG_FORMAT) {
   header("Content-type: image/svg+xml");
   header('Content-Disposition: attachment; filename="image.svg"');
} else {
   header("Content-Type: image/".$format_graph."");
}
header("Content-Length: ".strlen($output_data));

echo $output_data;

?>