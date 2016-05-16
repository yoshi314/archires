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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginArchiresVlanColor extends CommonDBTM {

   static $rightname = "plugin_archires";


   function getFromDBbyVlan($vlan) {
      global $DB;

      $query = "SELECT *
                FROM `".$this->getTable()."`
                WHERE `vlans_id` = '$vlan'";

      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 1) {
            return false;
         }
         $this->fields = $DB->fetch_assoc($result);
         if (is_array($this->fields) && count($this->fields)) {
            return true;
         }
         return false;
      }
      return false;
   }


   function addVlanColor($vlan,$color) {
      global $DB;

      if ($vlan != '-1') {
         if ($this->GetfromDBbyVlan($vlan)) {
            $this->update(array('id'    => $this->fields['id'],
                                'color' => $color));
         } else {
            $this->add(array('vlans_id' => $vlan,
                             'color'    => $color));
         }
      } else {
         $query = "SELECT *
                   FROM `glpi_vlans` ";
         $result = $DB->query($query);
         $number = $DB->numrows($result);

         $i = 0;
         while ($i < $number) {
            $vlan_table=$DB->result($result, $i, "id");
            if ($this->GetfromDBbyVlan($vlan_table)) {
               $this->update(array('id'    => $this->fields['id'],
                                   'color' => $color));
            } else {
               $this->add(array('vlans_id' => $vlan_table,
                                'color'    => $color));
           }
           $i++;
         }
      }
   }


   function showConfigForm($canupdate=false) {
      global $DB;

      $query = "SELECT *
                FROM `".$this->getTable()."`
                ORDER BY `vlans_id` ASC";
      $i    = 0;
      $used = array();

      if ($result = $DB->query($query)) {
         $number = $DB->numrows($result);

         if ($canupdate) {
            echo "<form method='post' name='massiveaction_form_vlan_color' id='".
                  "massiveaction_form_vlan_color' action='./config.form.php'>";
         }
         if ($number != 0) {
            echo "<div id='liste_vlan'>";
            echo "<table class='tab_cadre' cellpadding='5' width='50%'>";
            echo "<tr>";
            echo "<th class='left'>".__('VLAN')."</th>";
            echo "<th class='left'>".__('Color', 'archires')."</th><th></th>";
            if ($number > 1) {
               echo "<th class='left'>".__('VLAN')."</th>";
               echo "<th class='left'>".__('Color', 'archires')."</th><th></th>";
            }
            echo "</tr>";

            while($ligne= $DB->fetch_assoc($result)) {
               $ID       = $ligne["id"];
               $vlans_id = $ligne["vlans_id"];
               $used[]   = $vlans_id;
               if ((($i % 2) == 0)
                   && ($number > 1)) {
                  echo "<tr class='tab_bg_1'>";
               }
               if ($number == 1) {
                  echo "<tr class='tab_bg_1'>";
               }
               echo "<td>".Dropdown::getDropdownName("glpi_vlans", $ligne["vlans_id"])."</td>";
               echo "<td bgcolor='".$ligne["color"]."'>".$ligne["color"]."</td>";
               echo "<td><input type='hidden' name='id' value='$ID'>";
               if ($canupdate) {
                  echo "<input type='checkbox' name='item_color[$ID]' value='1'>";
               }
               echo "</td>";

               $i++;
               if (($i == $number) && (($number % 2) != 0) && ($number > 1)) {
                  echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
               }
            }

            if ($canupdate) {
               echo "<tr class='tab_bg_1'>";
               if ($number > 1)
                  echo "<td colspan='8' class='center'>";
               else
                  echo "<td colspan='4' class='center'>";

               echo "<a onclick= \"if (markCheckboxes ('massiveaction_form_vlan_color')) ".
                     "return false;\" href='#'>".__('Select all')."</a>";
               echo " - <a onclick= \"if (unMarkCheckboxes ('massiveaction_form_vlan_color')) ".
                     "return false;\" href='#'>".__('Deselect all')."</a> ";
               Html::closeArrowMassives(array('delete_color_vlan' => _sx('button',
                                                                         'Delete permanently')));
            } else {
               echo "</table>";
            }
            echo "</div>";
         }

         if ($canupdate) {
            echo "<table class='tab_cadre' cellpadding='5' width='50%'><tr ><th colspan='3'>";
            echo __('Associate colors to VLANs', 'archires')."</th></tr>";
            echo "<tr class='tab_bg_1'><td width='60%'>";
            $this->dropdownVlan($used);
            echo "</td>";
            echo "<td><input type='text' name='color'>";
            echo "&nbsp;";
            Html::showToolTip(nl2br(__('Please use this color format', 'archires')),
                              array('link'       => 'http://www.graphviz.org/doc/info/colors.html',
                                    'linktarget' => '_blank'));
            echo "<td class='center'><input type='submit' name='add_color_vlan' value=\"".
                  _sx('button', 'Add')."\" class='submit'></td></tr>";
            echo "</table>";
            Html::closeForm();
         }
      }
   }


   function dropdownVlan($used=array()) {
      global $DB;

      $where = "";

      if (count($used)) {
         $where = "WHERE `id` NOT IN (0";
         foreach ($used as $ID) {
            $where .= ",$ID";
         }
         $where .= ")";
      }

      $query = "SELECT *
                FROM `glpi_vlans`
                $where
                ORDER BY `name`";
      $result = $DB->query($query);
      $number = $DB->numrows($result);

      if ($number != "0") {
         $values = array(0 => Dropdown::EMPTY_VALUE,
                         1 => __('All VLANs', 'archires'));
         while ($data= $DB->fetch_array($result)) {
            $values[$data['id']] = $data["name"];
         }
         Dropdown::showFromArray('vlans_id', $values, array('width' => '80%'));
      }
   }


  function getVlanbyNetworkPort ($ID) {
    global $DB;

      $query = "SELECT `glpi_vlans`.`id`
                FROM `glpi_vlans`, `glpi_networkports_vlans`
                WHERE `glpi_networkports_vlans`.`vlans_id` = `glpi_vlans`.`id`
                      AND `glpi_networkports_vlans`.`networkports_id` = '$ID' " ;

      $result = $DB->query($query);
      $nb     = $DB->numrows($result);
      if ($result = $DB->query($query)) {
        $data_vlan = $DB->fetch_array($result) ;
        $vlan      = $data_vlan["id"] ;
      }
      return $vlan;
   }

}
