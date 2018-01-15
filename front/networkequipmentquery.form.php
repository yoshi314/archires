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
 @copyright Copyright (c) 2016-2018 Archires plugin team
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
if (isset($_GET["start"])) {
   $start = $_GET["start"];
} else {
   $start = 0;
}

$PluginArchiresNetworkEquipmentQuery = new PluginArchiresNetworkEquipmentQuery();
$PluginArchiresQueryType             = new PluginArchiresQueryType();

if (isset($_POST["add"])) {
   $PluginArchiresNetworkEquipmentQuery->check(-1,CREATE,$_POST);
   $PluginArchiresNetworkEquipmentQuery->add($_POST);
   Html::back();

} else if (isset($_POST["delete"])) {
   $PluginArchiresNetworkEquipmentQuery->check($_POST['id'],DELETE);
   $PluginArchiresNetworkEquipmentQuery->delete($_POST);
   Html::redirect(Toolbox::getItemTypeSearchURL('PluginArchiresNetworkEquipmentQuery'));

} else if (isset($_POST["restore"])) {
   $PluginArchiresNetworkEquipmentQuery->check($_POST['id'],PURGE);
   $PluginArchiresNetworkEquipmentQuery->restore($_POST);
   Html::redirect(Toolbox::getItemTypeSearchURL('PluginArchiresNetworkEquipmentQuery'));

} else if (isset($_POST["purge"])) {
   $PluginArchiresNetworkEquipmentQuery->check($_POST['id'],PURGE);
   $PluginArchiresNetworkEquipmentQuery->delete($_POST,1);
   Html::redirect(Toolbox::getItemTypeSearchURL('PluginArchiresNetworkEquipmentQuery'));

} else if (isset($_POST["update"])) {
   $PluginArchiresNetworkEquipmentQuery->check($_POST['id'],UPDATE);
   $PluginArchiresNetworkEquipmentQuery->update($_POST);
   Html::back();

} else if (isset($_POST["duplicate"])) {
   $PluginArchiresNetworkEquipmentQuery->check($_POST['id'],CREATE);
   unset($_POST['id']);
   $PluginArchiresNetworkEquipmentQuery->add($_POST);
   Html::back();

} else if (isset($_POST["addtype"])) {
   if ($PluginArchiresQueryType->canCreate()) {
      $PluginArchiresQueryType->addType('PluginArchiresNetworkEquipmentQuery', $_POST['type'],
                                        $_POST['_itemtype'], $_POST['query']);
   }
   Html::back();

} else if (isset($_POST["deletetype"])) {
   if ($PluginArchiresQueryType->canCreate()) {
      $PluginArchiresQueryType->getFromDB($_POST["id"],-1);

      foreach ($_POST["item"] as $key => $val) {
         if ($val == 1) {
            $PluginArchiresQueryType->delete(['id' => $key]);
         }
      }
   }
   Html::back();

} else {
   $PluginArchiresNetworkEquipmentQuery->checkGlobal(READ);

   Html::header(PluginArchiresArchires::getTypeName()." ".PluginArchiresNetworkEquipmentQuery::getTypeName(),
                '',"tools","pluginarchiresmenu","networkequipment");

   $PluginArchiresNetworkEquipmentQuery->display($_GET);

   Html::footer();
}
