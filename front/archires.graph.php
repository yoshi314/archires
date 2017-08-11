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
 @copyright Copyright (c) 2016-2017 Archires plugin team
 @license   AGPL License 3.0 or (at your option) any later version
            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 @link      https://forge.glpi-project.org/projects/archires
 @since     version 2.2
 --------------------------------------------------------------------------
*/

include ("../../../inc/includes.php");

if (!isset($_GET["id"])) {
   $_GET["id"] = "";
}

$PluginArchiresView      = new PluginArchiresView();
$PluginArchiresPrototype = new PluginArchiresPrototype();

$object = $_GET["querytype"];
$obj    = new $object();

if (isset($_GET["displayview"])) {
   $obj->getFromDB($_GET["plugin_archires_queries_id"]);
   Html::redirect($CFG_GLPI["root_doc"]."/plugins/archires/front/archires.graph.php?id=".
                  $obj->fields["id"]."&querytype=".$_GET["querytype"]."&plugin_archires_views_id=".
                  $_GET["plugin_archires_views_id"]);

} else {
   Html::header(PluginArchiresArchires::getTypeName(),'',"tools","pluginarchires");

   $obj->getFromDB($_GET["id"]);
   $object_view = $obj->fields["plugin_archires_views_id"];
   $entities_id = $obj->fields["entities_id"];

   if ($PluginArchiresView->getFromDB($object_view)
       && Session::haveAccessToEntity($entities_id)) {

      if (!isset($_GET["plugin_archires_views_id"])) {
        $plugin_archires_views_id = $object_view;
      } else {
        $plugin_archires_views_id = $_GET["plugin_archires_views_id"];
      }
      $PluginArchiresPrototype->displayGraph($obj,$plugin_archires_views_id,1);

   } else {
      Html::redirect($CFG_GLPI["root_doc"]."/plugins/archires/front/archires.php");
   }

   Html::footer();
}
