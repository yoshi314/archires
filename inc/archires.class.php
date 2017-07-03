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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginArchiresArchires extends CommonDBTM {

   static $rightname       = "plugin_archires";

   protected $usenotepad   = true;


   static function getTypeName($nb=0) {
      return _n('Network Architecture', 'Network Architectures', $nb, 'archires');
   }


   static function showSummary() {

      echo "<div class='center'><table class='tab_cadre' cellpadding='5' width='50%'>";
      echo "<tr><th>".__('Summary')."</th></tr>";


      if (countElementsInTable('glpi_plugin_archires_views',
                               "`entities_id`='".$_SESSION["glpiactive_entity"]."'") > 0) {

         echo "<tr class='tab_bg_1'><td>";
         echo "<a href='view.php'>".PluginArchiresView::getTypeName(2)."</a>";
         echo "</td></tr>";

         echo "<tr class='tab_bg_1'><td>";
         echo "<a href='locationquery.php'>".
                sprintf(__('%1$s - %2$s'), self::getTypeName(1),
                        PluginArchiresLocationQuery::getTypeName(1))."</a>";
         echo "</td></tr>";

         echo "<tr class='tab_bg_1'><td>";
         echo "<a href='networkequipmentquery.php'>".
                sprintf(__('%1$s - %2$s'), self::getTypeName(1),
                        PluginArchiresNetworkEquipmentQuery::getTypeName(1))."</a>";
         echo "</td></tr>";

         $plugin = new Plugin();
         if ($plugin->isActivated("appliances")) {
            echo "<tr class='tab_bg_1'><td>";
            echo "<a href='appliancequery.php'>".
                   sprintf(__('%1$s - %2$s'), self::getTypeName(1),
                           PluginAppliancesAppliance::getTypeName(1))."</a>";
            echo "</td></tr>";
         }
      } else {
         echo "<tr class='tab_bg_1'><td>";
         echo "<a href='view.form.php?new=1'>".__('Add view', 'archires')."</a>";
         echo "</td></tr>";
      }
      echo "</table></div>";
   }


   function showAllItems($myname, $value_type=0, $value=0, $entity_restrict=-1) {
      global $DB,$CFG_GLPI;

      $types = array('Computer','NetworkEquipment','Peripheral','Phone','Printer');
      $rand  = mt_rand();

      $params = array(0 => Dropdown::EMPTY_VALUE);
      foreach ($types as $label) {
         $item = new $label();
         $params[$label] = $item->getTypeName();
      }

      Dropdown::showFromArray('_itemtype', $params, array('width'   => '80%',
                                                          'rand'    => $rand));

      $field_id = Html::cleanId("dropdown__itemtype$rand");

      $params = array('itemtype'       => '__VALUE__',
                      'value'           => $value,
                      'myname'          => $myname,
                      'entity' => $entity_restrict);

      echo "<span id='show_$myname$rand'>&nbsp;</span>\n";
      Ajax::updateItemOnSelectEvent($field_id, "show_$myname$rand",
                                    $CFG_GLPI["root_doc"]."/plugins/archires/ajax/dropdownAllItems.php",
                                    $params);
      return $rand;
   }
}
