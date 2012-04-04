<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 Archires plugin for GLPI
 Copyright (C) 2003-2011 by the archires Development Team.

 https://forge.indepnet.net/projects/archires
 -------------------------------------------------------------------------

 LICENSE

 This file is part of archires.

 Archires is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Archires is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Archires. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

// Init the hooks of the plugins -Needed
function plugin_init_archires() {
   global $PLUGIN_HOOKS,$CFG_GLPI,$LANG;

   Plugin::registerClass('PluginArchiresProfile', array('addtabon' => array('Profile')));

   $PLUGIN_HOOKS['change_profile']['archires'] = array('PluginArchiresProfile','changeProfile');
   $PLUGIN_HOOKS['pre_item_purge']['archires'] = array('Profile' => array('PluginArchiresProfile',
                                                       'purgeProfiles'));

   if (Session::getLoginUserID()) {
      if (plugin_archires_haveRight("archires","r")) {
         $PLUGIN_HOOKS['menu_entry']['archires'] = 'front/archires.php';
         //summary
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['summary']['title']
                  = $LANG['plugin_archires']['menu'][0];
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['summary']['page']
                  = '/plugins/archires/front/archires.php';
         //views
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['view']['title']
                  = $LANG['plugin_archires']['title'][3];
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['view']['page']
                  = '/plugins/archires/front/view.php';
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['view']['links']['search']
                  = '/plugins/archires/front/view.php';
         //locations
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['location']['title']
                  = $LANG['plugin_archires']['title'][4];
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['location']['page']
                  = '/plugins/archires/front/locationquery.php';
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['location']['links']['search']
                  = '/plugins/archires/front/locationquery.php';
         //networkequipments
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['networkequipment']['title']
                  = $LANG['plugin_archires']['title'][5];
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['networkequipment']['page']
                  = '/plugins/archires/front/networkequipmentquery.php';
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['networkequipment']['links']['search']
                  = '/plugins/archires/front/networkequipmentquery.php';
         //appliances
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['appliance']['title']
                  = $LANG['plugin_archires']['title'][8];
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['appliance']['page']
                  = '/plugins/archires/front/appliancequery.php';
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['appliance']['links']['search']
                  = '/plugins/archires/front/appliancequery.php';
      }

      if (plugin_archires_haveRight("archires","w")) {
         //summary
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['view']['links']['add']
                  = '/plugins/archires/front/view.form.php?new=1';
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['view']['links']['config']
                  = '/plugins/archires/front/config.form.php';
         //locations
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['location']['links']['add']
                  = '/plugins/archires/front/locationquery.form.php?new=1';
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['location']['links']['config']
                  = '/plugins/archires/front/config.form.php';
         //networkequipments
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['networkequipment']['links']['add']
                  = '/plugins/archires/front/networkequipmentquery.form.php?new=1';
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['networkequipment']['links']['config']
                  = '/plugins/archires/front/config.form.php';
         //appliances
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['appliance']['links']['add']
                  = '/plugins/archires/front/appliancequery.form.php?new=1';
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['appliance']['links']['config']
                  = '/plugins/archires/front/config.form.php';

         if (Session::haveRight("config","w")) {
            $PLUGIN_HOOKS['submenu_entry']['archires']['config'] = 'front/config.form.php';
         }
         $PLUGIN_HOOKS['use_massive_action']['archires'] = 1;
      }
      // Config page
      if (plugin_archires_haveRight("archires","w") || Session::haveRight("config","w")) {
         $PLUGIN_HOOKS['config_page']['archires'] = 'front/config.form.php';
      }
   }
}


// Get the name and the version of the plugin - Needed
function plugin_version_archires() {
global $LANG;

   return array('name'           => $LANG['plugin_archires']['title'][0],
                'version'        => '2.0.0',
                'author'         => 'Xavier Caillaud, Remi Collet, Nelly Mahu-Masson, Sebastien Prudhomme',
                'license'        => 'GPLv2+',
                'homepage'       => 'https://forge.indepnet.net/projects/archires',
                'minGlpiVersion' => '0.83');
}


// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_archires_check_prerequisites() {

   if (version_compare(GLPI_VERSION,'0.83','lt') || version_compare(GLPI_VERSION,'0.84','ge')) {
      echo "This plugin requires GLPI >= 0.83";
      return false;
   }
   return true;
}


// Uninstall process for plugin : need to return true if succeeded : may display messages or add to message after redirect
function plugin_archires_check_config() {
   return true;
}


function plugin_archires_haveRight($module,$right) {

   $matches=array(""  => array("","r","w"), // ne doit pas arriver normalement
                  "r" => array("r","w"),
                  "w" => array("w"),
                  "1" => array("1"),
                  "0" => array("0","1")); // ne doit pas arriver non plus

   if (isset($_SESSION["glpi_plugin_archires_profile"][$module])
       && in_array($_SESSION["glpi_plugin_archires_profile"][$module],$matches[$right])) {
      return true;
   }
   return false;
}
?>