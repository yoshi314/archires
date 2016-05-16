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

// Init the hooks of the plugins -Needed
function plugin_init_archires() {
   global $PLUGIN_HOOKS,$CFG_GLPI;

   $PLUGIN_HOOKS['csrf_compliant']['archires'] = true;

   Plugin::registerClass('PluginArchiresProfile', array('addtabon' => array('Profile')));

   $PLUGIN_HOOKS['pre_item_purge']['archires'] = array('Profile' => array('PluginArchiresProfile',
                                                       'purgeProfiles'));

   if (Session::getLoginUserID()) {
      if (Session::haveRight("plugin_archires", READ)) {
         $PLUGIN_HOOKS["menu_toadd"]['archires'] = array('tools' => 'PluginArchiresMenu');
         //summary

         //appliances
         if (class_exists('PluginAppliancesAppliance')) {
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['appliance']['title']
                  = PluginAppliancesAppliance::getTypeName(1);

            $PLUGIN_HOOKS['submenu_entry']['archires']['options']['appliance']['page']
                  = '/plugins/archires/front/appliancequery.php';

            $PLUGIN_HOOKS['submenu_entry']['archires']['options']['appliance']['links']['search']
                  = '/plugins/archires/front/appliancequery.php';
         }
      }

      if (Session::haveRight("plugin_archires", CREATE)) {
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

         if (Session::haveRight("config", UPDATE)) {
            $PLUGIN_HOOKS['submenu_entry']['archires']['config'] = 'front/config.form.php';
         }
         $PLUGIN_HOOKS['use_massive_action']['archires'] = 1;
      }
      // Config page
      if (Session::haveRight("plugin_archires", UPDATE)
          || Session::haveRight("config", UPDATE)) {
         $PLUGIN_HOOKS['config_page']['archires'] = 'front/config.form.php';
      }
   }
}


// Get the name and the version of the plugin - Needed
function plugin_version_archires() {

   return array('name'           => _n('Network Architecture', 'Network Architectures', 2, 'archires'),
                'version'        => '2.2',
                'author'         => 'Xavier Caillaud, Remi Collet, Nelly Mahu-Lasson, Sebastien Prudhomme',
                'license'        => 'AGPLv3+',
                'homepage'       => ' https://forge.glpi-project.org/projects/archires',
                'minGlpiVersion' => '0.85');
}


// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_archires_check_prerequisites() {

   if (version_compare(GLPI_VERSION,'0.85','lt') || version_compare(GLPI_VERSION,'9.2','ge')) {
      echo "This plugin requires GLPI >= 0.85";
      return false;
   }
   return true;
}


// Uninstall process for plugin : need to return true if succeeded : may display messages or add to message after redirect
function plugin_archires_check_config() {
   return true;
}
