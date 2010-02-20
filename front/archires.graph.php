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

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");

if (!isset($_GET["id"])) $_GET["id"] = "";

$PluginArchiresView      = new PluginArchiresView();
$PluginArchiresPrototype = new PluginArchiresPrototype();

$object = $_GET["querytype"];
$obj = new $object();

if (isset($_GET["displayview"])) {
   $obj->getFromDB($_GET["plugin_archires_queries_id"]);
   glpi_header($CFG_GLPI["root_doc"]."/plugins/archires/front/archires.graph.php?id=".
               $obj->fields["id"]."&querytype=".$_GET["querytype"]."&plugin_archires_views_id=".$_GET["plugin_archires_views_id"]);

} else {
   commonHeader($LANG['plugin_archires']['title'][0],'',"plugins","archires");

   $obj->getFromDB($_GET["id"]);
   $object_view = $obj->fields["plugin_archires_views_id"];
   $entities_id = $obj->fields["entities_id"];

   if ($PluginArchiresView->getFromDB($object_view) && haveAccessToEntity($entities_id)) {
      if (!isset($_GET["plugin_archires_views_id"])) {
        $plugin_archires_views_id = $object_view;
      } else {
        $plugin_archires_views_id = $_GET["plugin_archires_views_id"];
      }
      $PluginArchiresPrototype->displayGraph($obj,$plugin_archires_views_id,1);

   } else {
      glpi_header($CFG_GLPI["root_doc"]."/plugins/archires/front/archires.php");
   }

   commonFooter();
}

?>