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

if (!defined('GLPI_ROOT')) {
   include ("../../../inc/includes.php");
}

$plugin = new Plugin();
if ($plugin->isActivated("archires")) {
   Session::checkRight("config", UPDATE);
   $PluginArchiresImageItem             = new PluginArchiresImageItem();
   $PluginArchiresNetworkInterfaceColor = new PluginArchiresNetworkInterfaceColor();
   $PluginArchiresVlanColor             = new PluginArchiresVlanColor();
   $PluginArchiresStateColor            = new PluginArchiresStateColor();

   if (isset($_POST["add"]) && isset($_POST['_itemtype'])) {
      if ($PluginArchiresImageItem->canCreate()) {
         $PluginArchiresImageItem->addItemImage($_POST['type'], $_POST['_itemtype'],
                                                $_POST['img']);
      }
      Html::back();

   } else if (isset($_POST["delete"])) {
      Session::checkRight("config", UPDATE);
      $PluginArchiresImageItem->getFromDB($_POST["id"],-1);

      foreach ($_POST["item"] as $key => $val) {
         if ($val == 1) {
            $PluginArchiresImageItem->delete(['id' => $key]);
         }
      }
      Html::back();

   } else if (isset($_POST["add_color_networkinterface"])
              && isset($_POST['networkinterfaces_id'])) {

      if ($PluginArchiresNetworkInterfaceColor->canCreate()) {
         $PluginArchiresNetworkInterfaceColor->addNetworkInterfaceColor($_POST['networkinterfaces_id'],
                                                                        $_POST['color']);
      }
      Html::back();

   } else if (isset($_POST["delete_color_networkinterface"])) {
      Session::checkRight("config", UPDATE);
      $PluginArchiresNetworkInterfaceColor->getFromDB($_POST["id"],-1);

      foreach ($_POST["item_color"] as $key => $val) {
         if ($val == 1) {
            $PluginArchiresNetworkInterfaceColor->delete(['id' => $key]);
         }
      }
      Html::back();

   } else if (isset($_POST["add_color_state"]) && isset($_POST['states_id'])) {
      if ($PluginArchiresStateColor->canCreate()) {
         $PluginArchiresStateColor->addStateColor($_POST['states_id'],$_POST['color']);
      }
      Html::back();

   } else if (isset($_POST["delete_color_state"])) {
      Session::checkRight("config", UPDATE);
      $PluginArchiresStateColor->getFromDB($_POST["id"],-1);

      foreach ($_POST["item_color"] as $key => $val) {
         if ($val == 1) {
            $PluginArchiresStateColor->delete(['id' => $key]);
         }
      }
      Html::back();

   } else if (isset($_POST["add_color_vlan"]) && isset($_POST['vlans_id'])) {
      if ($PluginArchiresVlanColor->canCreate()) {
         $PluginArchiresVlanColor->addVlanColor($_POST['vlans_id'],$_POST['color']);
      }
      Html::back();

   } else if (isset($_POST["delete_color_vlan"])) {
      Session::checkRight("config", UPDATE);
      $PluginArchiresVlanColor->getFromDB($_POST["id"],-1);

      foreach ($_POST["item_color"] as $key => $val) {
         if ($val == 1) {
            $PluginArchiresVlanColor->delete(['id' => $key]);
         }
      }
      Html::back();

   } else {
      Html::header(PluginArchiresArchires::getTypeName(), '', "tools", "pluginarchiresmenu");

      $PluginArchiresImageItem->showConfigForm();

      $PluginArchiresNetworkInterfaceColor->showConfigForm(true);

      $PluginArchiresVlanColor->showConfigForm(true);

      $PluginArchiresStateColor->showConfigForm(true);

      Html::footer();
   }

} else {
   Html::header(__('Setup'), '', "config", "plugins");
   echo "<div class='center'><br><br>".
         "<img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt='warning'><br><br>";
   echo "<b>".__('Please activate the plugin','addressing')."</b></div>";
   Html::footer();
}
