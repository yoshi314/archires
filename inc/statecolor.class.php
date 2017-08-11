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

class PluginArchiresStateColor extends CommonDBTM {

   static $rightname = "plugin_archires";


   function getFromDBbyState($state) {
      global $DB;

      $query = "SELECT *
                FROM `".$this->getTable()."`
                WHERE `states_id` = '$state'";

      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 1) {
            return false;
         }
         $this->fields = $DB->fetch_assoc($result);
         if (is_array($this->fields) && count($this->fields)) {
            return true;
         }
      }
      return false;
   }


   function addStateColor($state,$color) {
      global $DB;

      if ($state != '-1') {
         if ($this->GetfromDBbyState($state)) {
            $this->update(array('id'    => $this->fields['id'],
                                'color' => $color));
         } else {
            $this->add(array('states_id' => $state,
                             'color'     => $color));
         }
      } else {
         $query  = "SELECT *
                    FROM `glpi_states`";
         $result = $DB->query($query);
         $number = $DB->numrows($result);
         $i      = 0;

         while ($i < $number) {
            $state_table = $DB->result($result, $i, "id");
            if ($this->GetfromDBbyState($state_table)) {
               $this->update(array('id'    => $this->fields['id'],
                                   'color' => $color));
            } else {
               $this->add(array('states_id' => $state_table,
                                'color'     => $color));
            }
            $i++;
         }
      }
   }


   function showConfigForm($canupdate=false) {
      global $DB;

      if ($canupdate) {
         echo "<div class='firstbloc'>";
         echo "<form method='post' name='state_color' action='./config.form.php'>";
         echo "<table class='tab_cadre' cellpadding='5' width='50%'>";
         echo "<tr><th colspan='3'>".__('Associate colors with items statuses', 'archires').
              "</th></tr>";

         echo "<tr class='tab_bg_1'><td width='70%'>";
         $this->dropdownState();
         echo "</td>";
         echo "<td><input type='text' name='color'>";
         echo "&nbsp;";
         Html::showToolTip(nl2br(__('Please use this color format', 'archires')),
                           array('link'       => 'http://www.graphviz.org/doc/info/colors.html',
                                 'linktarget' => '_blank'));
         echo "</td><td></div>";
         echo "<div class='center'><input type='submit' name='add_color_state' value=\"".
                                    _sx('button', 'Add')."\" class='submit' ></div></td></tr>";
         echo "</table></div>";
         Html::closeForm();
      }

      $query = "SELECT *
                FROM `".$this->getTable()."`
                ORDER BY `states_id` ASC";

      if ($result = $DB->query($query)) {
         $number = $DB->numrows($result);

         if ($number != 0) {
            echo "<div id='liste_color'>";
            if ($canupdate) {
               $rand = mt_rand();
               Html::openMassiveActionsForm('mass'.__CLASS__.$rand);
               $massiveactionparams = array('num_displayed'    => $number,
                                            'container'        => 'mass'.__CLASS__.$rand);
               Html::showMassiveActions($massiveactionparams);
            }
            echo "<table class='tab_cadre' cellpadding='5' width='50%'>";
            echo "<tr>";
            if ($canupdate) {
               echo "<th width='10'>";
               Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand);
               echo "</th>";
            }
            echo "<th class='left' widht='50%'>".__('Status')."</th>";
            echo "<th class='left'>".__('Color', 'archires')."</th><th></th>";
            echo "</tr>";

            while ($ligne= $DB->fetch_assoc($result)) {
               $ID        = $ligne["id"];
               echo "<tr class='tab_bg_1'>";
                if ($canupdate) {
                  echo "<td width='10'>";
                  Html::showMassiveActionCheckBox(__CLASS__, $ID);
                  echo "</td>";
               }
               echo "</td><td>".Dropdown::getDropdownName("glpi_states",$ligne["states_id"])."</td>";
               echo "<td bgcolor='".$ligne["color"]."'>".$ligne["color"]."</td>";
               echo "<td><input type='hidden' name='id' value='$ID'>";
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


   function dropdownState() {
      global $DB;

      $colors = array();
      foreach($DB->request("glpi_plugin_archires_statecolors") as $color) {
         $colors[] = $color['states_id'];
      }

      $query = "SELECT *
                FROM `glpi_states`
                WHERE `id` NOT IN ('".implode("','",$colors)."')
                ORDER BY `name`";

      $result = $DB->query($query);
      $number = $DB->numrows($result);

      if ($number !="0") {
         $values = array(0 => Dropdown::EMPTY_VALUE,
                         1 => __('All statuses', 'archires'));
         while ($data = $DB->fetch_array($result)) {
            $values[$data['id']] = $data["name"];
         }
         Dropdown::showFromArray('states_id', $values, array('width' => '80%'));
      }
   }


   function displayColorState($device) {
      global $DB;

      $graph       = "";
      $query_state = "SELECT *
                      FROM `".$this->getTable()."`
                      WHERE `states_id`= '".$device["states_id"]."'";
      $result_state = $DB->query($query_state);
      $number_state = $DB->numrows($result_state);

      if (($number_state != 0)
          && ($device["states_id"] > 0)) {
         $color_state = $DB->result($result_state,0,"color");
         $graph ="<font color=\"$color_state\">".Dropdown::getDropdownName("glpi_states",
                                                                           $device["states_id"])
                ."</font>";
      } else if (($number_state == 0)
                 && ($device["states_id"] > 0)) {
         $graph = Dropdown::getDropdownName("glpi_states",$device["states_id"]);
      }
      return $graph;
   }



   function getForbiddenStandardMassiveAction() {

      $forbidden   = parent::getForbiddenStandardMassiveAction();
      $forbidden[] = 'update';
      return $forbidden;
   }

}
