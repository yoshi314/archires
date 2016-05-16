<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 LICENSE

 This file is part of Archires plugin for GLPI.

 Archires is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 Archires is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU Affero General Public License for more details.

 You should have received a copy of the GNU Affero General Public License
 along with Archires. If not, see <http://www.gnu.org/licenses/>.

 @package   archires
 @author    Nelly Mahu-Lasson, Xavier Caillaud
 @copyright Copyright (c) 2016 Archires plugin team
 @license   AGPL License 3.0 or (at your option) any later version
            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 @link      https://forge.glpi-project.org/projects/archires
 @since     version 2.2
 --------------------------------------------------------------------------
*/

include ("../../../inc/includes.php");

$PluginArchiresView      = new PluginArchiresView();
$PluginArchiresPrototype = new PluginArchiresPrototype();

$PluginArchiresView->getFromDB($_GET["plugin_archires_views_id"]);
if (isset($_GET["format"])) {
   $format = $_GET["format"];
} else {
   $format = $PluginArchiresView->fields["format"];
}

if ($format == PluginArchiresView::PLUGIN_ARCHIRES_JPEG_FORMAT) {
   $format_graph = "jpeg";
} else if ($format == PluginArchiresView::PLUGIN_ARCHIRES_PNG_FORMAT) {
   $format_graph = "png";
} else if ($format == PluginArchiresView::PLUGIN_ARCHIRES_GIF_FORMAT) {
   $format_graph = "gif";
} else if ($format == PluginArchiresView::PLUGIN_ARCHIRES_SVG_FORMAT) {
   $format_graph = "svg";
}

$object      = $_GET["querytype"];
$obj         = new $object();
$obj->getFromDB($_GET["id"]);
$object_view = $obj->fields["plugin_archires_views_id"];

if (!isset($_GET["plugin_archires_views_id"])) {
   $plugin_archires_views_id = $object_view;
} else {
   $plugin_archires_views_id = $_GET["plugin_archires_views_id"];
}
$output_data = $PluginArchiresPrototype->createGraph($format_graph,$obj,$plugin_archires_views_id);

if ($format==PluginArchiresView::PLUGIN_ARCHIRES_SVG_FORMAT) {
   header("Content-type: image/svg+xml");
   header('Content-Disposition: attachment; filename="image.svg"');
} else {
   header("Content-Type: image/".$format_graph."");
}
header("Content-Length: ".strlen($output_data));

echo $output_data;
