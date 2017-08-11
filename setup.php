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
     }

      if (Session::haveRight("plugin_archires", CREATE)) {
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
                'version'        => '2.4',
                'author'         => 'Xavier Caillaud, Nelly Mahu-Lasson',
                'license'        => 'AGPLv3+',
                'homepage'       => ' https://forge.glpi-project.org/projects/archires',
                'minGlpiVersion' => '9.1');
}


// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_archires_check_prerequisites() {

   if (version_compare(GLPI_VERSION,'0.91','lt') || version_compare(GLPI_VERSION,'9.2','ge')) {
      echo "This plugin requires GLPI >= 0.91";
      return false;
   }
   return true;
}


// Uninstall process for plugin : need to return true if succeeded : may display messages or add to message after redirect
function plugin_archires_check_config() {
   return true;
}
