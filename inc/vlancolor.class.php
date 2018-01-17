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
   die("Sorry. You can't access directly to this file");
}

class PluginArchiresVlanColor extends CommonDBTM {

   static $rightname = "plugin_archires";


   function getFromDBbyVlan($vlan) {
      global $DB;

      $query = ['FROM'  => $this->getTable(),
                'WHERE' => ['vlans_id' => $vlan]];

      if ($result = $DB->request($query)) {
         if (count($result) != 1) {
            return false;
         }
         $this->fields = $result->next();
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
            $this->update(['id'    => $this->fields['id'],
                           'color' => $color]);
         } else {
            $this->add(['vlans_id' => $vlan,
                        'color'    => $color]);
         }
      } else {
         $query = ['FROM' => 'glpi_vlans'];
         $result = $DB->request($query);

         $i = 0;
         while ($i < count($result)) {
            $vlan_table=$DB->result($result, $i, "id");
            if ($this->GetfromDBbyVlan($vlan_table)) {
               $this->update(['id'    => $this->fields['id'],
                              'color' => $color]);
            } else {
               $this->add(['vlans_id' => $vlan_table,
                           'color'    => $color]);
           }
           $i++;
         }
      }
   }


   function showConfigForm($canupdate=false) {
      global $DB;

      if ($canupdate) {
         echo "<div class='firstbloc'>";
         echo "<form method='post' name='vlan_color' action='./config.form.php'>";
         echo "<table class='tab_cadre' cellpadding='5' width='50%'><tr ><th colspan='3'>";
         echo __('Associate colors to VLANs', 'archires')."</th></tr>";
         echo "<tr class='tab_bg_1'><td width='60%'>";
         $this->dropdownVlan();
         echo "</td>";
         echo "<td><input type='text' name='color'>";
         echo "&nbsp;";
         Html::showToolTip(nl2br(__('Please use this color format', 'archires')),
                           ['link'       => 'http://www.graphviz.org/doc/info/colors.html',
                            'linktarget' => '_blank']);
         echo "<td class='center'><input type='submit' name='add_color_vlan' value=\"".
                _sx('button', 'Add')."\" class='submit'></td></tr>";
         echo "</table>";
         Html::closeForm();
      }

      $query = ['FROM'  => $this->getTable(),
                'ORDER' => 'vlans_id ASC'];

      if ($result = $DB->request($query)) {
         $number = count($result);

         if ($number != 0) {
            echo "<div id='liste_vlan'>";
            if ($canupdate) {
               $rand = mt_rand();
               Html::openMassiveActionsForm('mass'.__CLASS__.$rand);
               $massiveactionparams = ['num_displayed'    => $number,
                                       'container'        => 'mass'.__CLASS__.$rand];
               Html::showMassiveActions($massiveactionparams);
            }
            echo "<table class='tab_cadre' cellpadding='5' width='50%'>";
            echo "<tr>";
            if ($canupdate) {
               echo "<th width='10'>";
               Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand);
               echo "</th>";
            }
            echo "<th class='left'>".__('VLAN')."</th>";
            echo "<th class='left'>".__('Color', 'archires')."</th><th></th>";
            echo "</tr>";

            while($ligne = $result->next()) {
               $ID = $ligne["id"];
               echo "<tr class='tab_bg_1'>";
               if ($canupdate) {
                  echo "<td width='10'>";
                  Html::showMassiveActionCheckBox(__CLASS__, $ID);
                  echo "</td>";
               }
               echo "<td>".Dropdown::getDropdownName("glpi_vlans", $ligne["vlans_id"])."</td>";
               echo "<td bgcolor='".$ligne["color"]."'>".$ligne["color"]."</td>";
               echo "<td><input type='hidden' name='id' value='$ID'></td>";
            }

            echo "</table>";
            if ($canupdate) {
               $massiveactionparams['ontop'] = false;
               Html::showMassiveActions($massiveactionparams);
            }
            echo "</div>";
            Html::closeForm();
         }
      }
   }


   function dropdownVlan() {
      global $DB;

      $colors = [];
      foreach($DB->request("glpi_plugin_archires_vlancolors") as $color) {
         $colors[] = $color['vlans_id'];
      }

      $query = ['FROM'  => 'glpi_vlans',
                'WHERE' =>  ['NOT' => ['id' => [implode("','",$colors)]]],
                'ORDER' => 'name'];
      $result = $DB->request($query);

      if (count($result)) {
         $values = [1 => __('All VLANs', 'archires')];
         while ($data = $result->next()) {
            $values[$data['id']] = $data["name"];
         }
         Dropdown::showFromArray('vlans_id', $values, ['width'                => '80%',
                                                       'display_emptychoice'  => true]);
      }
   }


   function getVlanbyNetworkPort ($ID) {
    global $DB;

      $query = ['SELECT'   => 'glpi_vlans.id',
                'FROM'     => ['glpi_vlans', 'glpi_networkports_vlans'],
                'WHERE'    => ['vlans_id'          => 'glpi_vlans.id',
                               'networkports_id'   => $ID]];

      if ($result = $DB->request($query)) {
        $data_vlan = $result->next();
        $vlan      = $data_vlan["id"] ;
      }
      return $vlan;
   }


   function getForbiddenStandardMassiveAction() {

      $forbidden   = parent::getForbiddenStandardMassiveAction();
      $forbidden[] = 'update';
      return $forbidden;
   }
}
