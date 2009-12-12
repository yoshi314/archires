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

if (!defined('GLPI_ROOT')) {
	define('GLPI_ROOT', '../../..');
	include (GLPI_ROOT . "/inc/includes.php");
}

$plugin = new Plugin();
if ($plugin->isActivated("archires")) {
   
   checkRight("config","w");

   $PluginArchiresItemImage=new PluginArchiresItemImage();
   $PluginArchiresNetworkInterfaceColor=new PluginArchiresNetworkInterfaceColor();
   $PluginArchiresVlanColor=new PluginArchiresVlanColor();
   $PluginArchiresStateColor=new PluginArchiresStateColor();
      
   if (isset($_POST["add"]) && isset($_POST['type'])) {

      $test= explode(";", $_POST['type']);
      
      if (isset($test[0])) {
         $_POST['type']= $test[1];
         $_POST['itemtype']= $test[0];
      
         if (plugin_archires_haveRight("archires","w")) {
            $PluginArchiresItemImage->addItemImage($_POST['type'],$_POST['itemtype'],$_POST['img']);
         }
      }
      glpi_header($_SERVER['HTTP_REFERER']);

   } else if (isset($_POST["delete"])) {
      checkRight("config","w");
      
      $PluginArchiresItemImage->getFromDB($_POST["id"],-1);
      
      foreach ($_POST["item"] as $key => $val) {
         if ($val==1) {
            $PluginArchiresItemImage->deleteItemImage($key);
         }
      }
      glpi_header($_SERVER['HTTP_REFERER']);

   } else if (isset($_POST["add_color_networkinterface"]) && isset($_POST['networkinterfaces_id'])) {
      
      if (plugin_archires_haveRight("archires","w")) {
         $PluginArchiresNetworkInterfaceColor->addNetworkInterfaceColor($_POST['networkinterfaces_id'],$_POST['color']);
      }
      
      glpi_header($_SERVER['HTTP_REFERER']);

   } else if (isset($_POST["delete_color_networkinterface"])) {

      checkRight("config","w");
      $PluginArchiresNetworkInterfaceColor->getFromDB($_POST["id"],-1);
      
      foreach ($_POST["item_color"] as $key => $val) {
         if ($val==1) {
            $PluginArchiresNetworkInterfaceColor->deleteNetworkInterfaceColor($key);
         }
      }
      glpi_header($_SERVER['HTTP_REFERER']);

   } else if (isset($_POST["add_color_state"]) && isset($_POST['states_id'])) {
      
      if (plugin_archires_haveRight("archires","w")) {
         $PluginArchiresStateColor->addStateColor($_POST['states_id'],$_POST['color']);
      }
      
      glpi_header($_SERVER['HTTP_REFERER']);

   } else if (isset($_POST["delete_color_state"])) {

      checkRight("config","w");
      $PluginArchiresStateColor->getFromDB($_POST["id"],-1);
      
      foreach ($_POST["item_color"] as $key => $val) {
         if ($val==1) {
            $PluginArchiresStateColor->deleteStateColor($key);
         }
      }
      glpi_header($_SERVER['HTTP_REFERER']);

   } else if (isset($_POST["add_color_vlan"]) && isset($_POST['vlans_id'])) {
      
      if (plugin_archires_haveRight("archires","w")) {
         $PluginArchiresVlanColor->addVlanColor($_POST['vlans_id'],$_POST['color']);
      }
      glpi_header($_SERVER['HTTP_REFERER']);

   } else if (isset($_POST["delete_color_vlan"])) {

      checkRight("config","w");
      $PluginArchiresVlanColor->getFromDB($_POST["id"],-1);
      
      foreach ($_POST["item_color"] as $key => $val) {
         if ($val==1) {
            $PluginArchiresVlanColor->deleteVlanColor($key);
         }
      }
      glpi_header($_SERVER['HTTP_REFERER']);

   } else {
      
      commonHeader($LANG['plugin_archires']['title'][0],$_SERVER['PHP_SELF'],"plugins","archires","summary");

      $PluginArchiresItemImage->showForm();
      
      $PluginArchiresNetworkInterfaceColor->showForm(true);

      $PluginArchiresVlanColor->showForm(true);

      $PluginArchiresStateColor->showForm(true);
      
      commonFooter();
   }

} else {
   commonHeader($LANG["common"][12],$_SERVER['PHP_SELF'],"config","plugins");
   echo "<div align='center'><br><br><img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt=\"warning\"><br><br>";
   echo "<b>Please activate the plugin</b></div>";
   commonFooter();
}

?>