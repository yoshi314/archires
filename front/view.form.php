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
$PluginArchiresView = new PluginArchiresView();

if (isset($_POST["add"])) {
   $PluginArchiresView->check(-1, CREATE,$_POST);
   $PluginArchiresView->add($_POST);
   Html::back();

} else if (isset($_POST["delete"])) {
   $PluginArchiresView->check($_POST['id'],DELETE);
   $PluginArchiresView->delete($_POST);
   Html::redirect(Toolbox::getItemTypeSearchURL('PluginArchiresView'));

} else if (isset($_POST["restore"])) {
   $PluginArchiresView->check($_POST['id'],PURGE);
   $PluginArchiresView->restore($_POST);
   Html::redirect(Toolbox::getItemTypeSearchURL('PluginArchiresView'));

} else if (isset($_POST["purge"])) {
   $PluginArchiresView->check($_POST['id'],PURGE);
   $PluginArchiresView->delete($_POST,1);
   Html::redirect(Toolbox::getItemTypeSearchURL('PluginArchiresView'));

} else if (isset($_POST["update"])) {
   $PluginArchiresView->check($_POST['id'],UPDATE);
   $PluginArchiresView->update($_POST);
   Html::back();

} else if (isset($_POST["duplicate"])) {
   $PluginArchiresView->check($_POST['id'],CREATE);
   unset($_POST['id']);
   $PluginArchiresView->add($_POST);
   Html::back();

} else {
   $PluginArchiresView->checkGlobal(READ);

   Html::header(PluginArchiresView::getTypeName(),'',"tools","pluginarchiresmenu","view");

   $PluginArchiresView->display($_GET);

   Html::footer();
}
