<?php
/*
 * @version $Id: HEADER 2010-10-31 21:36:26 tsmr $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

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
// Original Author of file: CAILLAUD Xavier & COLLET Remi & LASSON Nelly & PRUDHOMME Sebastien
// Purpose of file: plugin archires v1.8.1 - GLPI 0.78
// ----------------------------------------------------------------------
 */

define ("PLUGIN_ARCHIRES_NETWORK_COLOR",0);
define ("PLUGIN_ARCHIRES_VLAN_COLOR",1);

define ("PLUGIN_ARCHIRES_JPEG_FORMAT",0);
define ("PLUGIN_ARCHIRES_PNG_FORMAT",1);
define ("PLUGIN_ARCHIRES_GIF_FORMAT",2);
define ("PLUGIN_ARCHIRES_SVG_FORMAT",3);

// Init the hooks of the plugins -Needed
function plugin_init_archires() {
   global $PLUGIN_HOOKS,$CFG_GLPI,$LANG;

   $PLUGIN_HOOKS['change_profile']['archires'] = array('PluginArchiresProfile','changeProfile');
   $PLUGIN_HOOKS['pre_item_purge']['archires'] = array('Profile'=>array('PluginArchiresProfile', 'purgeProfiles'));

   if (getLoginUserID()) {
      if (plugin_archires_haveRight("archires","r")) {
         $PLUGIN_HOOKS['menu_entry']['archires'] = 'front/archires.php';
         //summary
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['summary']['title'] = $LANG['plugin_archires']['menu'][0];
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['summary']['page']  = '/plugins/archires/front/archires.php';
         //views
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['view']['title'] = $LANG['plugin_archires']['title'][3];
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['view']['page']  = '/plugins/archires/front/view.php';
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['view']['links']['search'] = '/plugins/archires/front/view.php';
         //locations
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['location']['title'] = $LANG['plugin_archires']['title'][4];
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['location']['page']  = '/plugins/archires/front/locationquery.php';
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['location']['links']['search'] = '/plugins/archires/front/locationquery.php';
         //networkequipments
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['networkequipment']['title'] = $LANG['plugin_archires']['title'][5];
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['networkequipment']['page']  = '/plugins/archires/front/networkequipmentquery.php';
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['networkequipment']['links']['search'] = '/plugins/archires/front/networkequipmentquery.php';
         //appliances
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['appliance']['title'] = $LANG['plugin_archires']['title'][8];
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['appliance']['page']  = '/plugins/archires/front/appliancequery.php';
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['appliance']['links']['search'] = '/plugins/archires/front/appliancequery.php';
      }

      if (plugin_archires_haveRight("archires","w")) {
         //summary
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['view']['links']['add']    = '/plugins/archires/front/view.form.php?new=1';
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['view']['links']['config'] = '/plugins/archires/front/config.form.php';
         //locations
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['location']['links']['add']    = '/plugins/archires/front/locationquery.form.php?new=1';
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['location']['links']['config'] = '/plugins/archires/front/config.form.php';
         //networkequipments
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['networkequipment']['links']['add']    = '/plugins/archires/front/networkequipmentquery.form.php?new=1';
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['networkequipment']['links']['config'] = '/plugins/archires/front/config.form.php';
         //appliances
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['appliance']['links']['add']    = '/plugins/archires/front/appliancequery.form.php?new=1';
         $PLUGIN_HOOKS['submenu_entry']['archires']['options']['appliance']['links']['config'] = '/plugins/archires/front/config.form.php';

         if (haveRight("config","w")) {
            $PLUGIN_HOOKS['submenu_entry']['archires']['config'] = 'front/config.form.php';
         }
         $PLUGIN_HOOKS['use_massive_action']['archires']=1;
      }

      // Headings
      if (plugin_archires_haveRight("archires","r") || haveRight("config","w")) {
         $PLUGIN_HOOKS['headings']['archires']        = 'plugin_get_headings_archires';
         $PLUGIN_HOOKS['headings_action']['archires'] = 'plugin_headings_actions_archires';
      }
      // Config page
      if (plugin_archires_haveRight("archires","w") || haveRight("config","w")) {
         $PLUGIN_HOOKS['config_page']['archires'] = 'front/config.form.php';
      }
   }
}


// Get the name and the version of the plugin - Needed
function plugin_version_archires() {
global $LANG;

   return array('name'           => $LANG['plugin_archires']['title'][0],
                'version'        => '1.8.1',
                'author'         => 'Xavier Caillaud',
                'homepage'       => 'https://forge.indepnet.net/projects/show/archires',
                'minGlpiVersion' => '0.78');
}


// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_archires_check_prerequisites() {

   if (GLPI_VERSION >= 0.78) {
      return true;
   } else {
      echo "GLPI version not compatible need 0.78";
   }
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