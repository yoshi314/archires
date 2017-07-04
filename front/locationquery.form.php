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
if (isset($_GET["start"])) {
   $start = $_GET["start"];
} else {
   $start = 0;
}

$PluginArchiresLocationQuery = new PluginArchiresLocationQuery();
$PluginArchiresQueryType     = new PluginArchiresQueryType();

if (isset($_POST["add"])) {
   $PluginArchiresLocationQuery->check(-1, CREATE,$_POST);
   $PluginArchiresLocationQuery->add($_POST);
   Html::back();

} else if (isset($_POST["delete"])) {
   $PluginArchiresLocationQuery->check($_POST['id'],DELETE);
   $PluginArchiresLocationQuery->delete($_POST);
   Html::redirect(Toolbox::getItemTypeSearchURL('PluginArchiresLocationQuery'));

} else if (isset($_POST["restore"])) {
   $PluginArchiresLocationQuery->check($_POST['id'],PURGE);
   $PluginArchiresLocationQuery->restore($_POST);
   Html::redirect(Toolbox::getItemTypeSearchURL('PluginArchiresLocationQuery'));

} else if (isset($_POST["purge"])) {
   $PluginArchiresLocationQuery->check($_POST['id'],PURGE);
   $PluginArchiresLocationQuery->delete($_POST,1);
   Html::redirect(Toolbox::getItemTypeSearchURL('PluginArchiresLocationQuery'));

} else if (isset($_POST["update"])) {
   $PluginArchiresLocationQuery->check($_POST['id'],UPDATE);
   $PluginArchiresLocationQuery->update($_POST);
   Html::back();

} else if (isset($_POST["duplicate"])) {
   $PluginArchiresLocationQuery->check($_POST['id'],CREATE);
   unset($_POST['id']);
   $PluginArchiresLocationQuery->add($_POST);
   Html::back();

} else if (isset($_POST["addtype"]) && isset($_POST['_itemtype'])) {
   if ($PluginArchiresQueryType->canCreate()) {
      $PluginArchiresQueryType->addType('PluginArchiresLocationQuery', $_POST['type'],
                                        $_POST['_itemtype'], $_POST['query']);
   }
   Html::back();

} else if (isset($_POST["deletetype"])) {
   if ($PluginArchiresQueryType->canCreate()) {
      $PluginArchiresQueryType->getFromDB($_POST["id"],-1);

      foreach ($_POST["item"] as $key => $val) {
         if ($val == 1) {
            $PluginArchiresQueryType->delete(array('id' => $key));
         }
      }
   }
   Html::back();

} else {
   $PluginArchiresLocationQuery->checkGlobal(READ);

   Html::header(PluginArchiresArchires::getTypeName()." ".PluginArchiresLocationQuery::getTypeName(),
                '',"tools","pluginarchiresmenu","location");

   $PluginArchiresLocationQuery->display($_GET);

   Html::footer();
}
