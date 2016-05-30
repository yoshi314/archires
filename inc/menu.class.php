<?php
/*
 * @version $Id: setup.php 164 2013-09-03 12:39:17Z tsmr $
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

class PluginArchiresMenu extends CommonGLPI {

   static $rightname = 'plugin_archires';


   static function getMenuName() {
      return _n('Network Architecture', 'Network Architectures', 2, 'archires');
   }


   static function getMenuContent() {
      global $CFG_GLPI;

      $menu                    = array();
      $menu['title']           = self::getMenuName();
      $menu['page']            = '/plugins/archires/front/archires.php';
      $menu['links']['search'] = '/plugins/archires/front/archires.php';


      $menu['options']['view']['title']           = _n('View', 'Views', 2);
      $menu['options']['view']['page']            = '/plugins/archires/front/view.php';
      $menu['options']['view']['links']['add']    = '/plugins/archires/front/view.form.php';
      $menu['options']['view']['links']['search'] = '/plugins/archires/front/view.php';

      $menu['options']['location']['title']           = __('Location');
      $menu['options']['location']['page']            = '/plugins/archires/front/locationquery.php';
      $menu['options']['location']['links']['add']    = '/plugins/archires/front/locationquery.form.php';
      $menu['options']['location']['links']['search'] = '/plugins/archires/front/locationquery.php';

      $menu['options']['networkequipment']['title']           = _n('Network equipment', 'Network equipments', 1, 'archires');
      $menu['options']['networkequipment']['page']            = '/plugins/archires/front/networkequipmentquery.php';
      $menu['options']['networkequipment']['links']['add']    = '/plugins/archires/front/networkequipmentquery.form.php';
      $menu['options']['networkequipment']['links']['search'] = '/plugins/archires/front/networkequipmentquery.php';

      if (class_exists('PluginAppliancesAppliance')) {
         $menu['options']['appliance']['title']           = __('appliances');
         $menu['options']['appliance']['page']            = '/plugins/archires/front/appliancequery.php';
         $menu['options']['appliance']['links']['add']    = '/plugins/archires/front/appliancequery.form.php?new=1';
         $menu['options']['appliance']['links']['search'] = '/plugins/archires/front/appliancequery.php';
      }
      return $menu;
   }


   static function removeRightsFromSession() {

      if (isset($_SESSION['glpimenu']['tools']['types']['PluginArchiresMenu'])) {
         unset($_SESSION['glpimenu']['tools']['types']['PluginArchiresMenu']);
      }
      if (isset($_SESSION['glpimenu']['tools']['content']['PluginArchiresMenu'])) {
         unset($_SESSION['glpimenu']['tools']['content']['PluginArchiresMenu']);
      }
   }
}