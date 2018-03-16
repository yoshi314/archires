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

class PluginArchiresNetworkInterfaceColor extends CommonDBTM {

   static $rightname             = "plugin_archires";


   function getFromDBbyNetworkInterface($networkinterfaces_id) {
      global $DB;

      $query = ['FROM'  => $this->getTable(),
                'WHERE' => ['networkinterfaces_id' => $networkinterfaces_id]];

      if ($result = $DB->request($query)) {
         if (count($result) != 1) {
            return false;
         }
         $this->fields = $result->next();
         if (is_array($this->fields) && count($this->fields)) {
            return true;
         }
      }
      return false;
   }


   function addNetworkInterfaceColor($networkinterfaces_id,$color) {
      global $DB;

      if ($networkinterfaces_id != '-1') {
         if ($this->getFromDBbyNetworkInterface($networkinterfaces_id)) {
            $this->update(['id'    => $this->fields['id'],
                           'color' => $color]);
         } else {
            $this->add(['networkinterfaces_id' => $networkinterfaces_id,
                        'color'                => $color]);
         }
      } else {
         $query  = ['FROM' => 'glpi_networkinterfaces'];
         $result = $DB->request($query);
         $i      = 0;
         while ($i < count($result)) {
            $row = $result->next();
           $networkinterface_table = $rox['id'];
           if ($this->getFromDBbyNetworkInterface($networkinterface_table)) {
               $this->update(['id'    => $this->fields['id'],
                              'color' => $color]);
           } else {
               $this->add(['networkinterfaces_id' => $networkinterface_table,
                           'color'                => $color]);
           }
           $i++;
         }
      }
   }


   function showConfigForm($canupdate=false) {
      global $DB;

      if ($canupdate) {
         echo "<div class='firstbloc'>";
         echo "<form method='post' name='networkinterface_color' action='./config.form.php'>";
         echo "<table class='tab_cadre' cellpadding='5' width='50%'><tr ><th colspan='3'>";
         echo __('Associate colors with network types', 'archires')."</th></tr>";

         echo "<tr class='tab_bg_1'><td width='70%'>";
         $this->dropdownNetworkInterface();
         echo "</td><td>";
         echo "<input type='text' name=\"color\">";
         echo "&nbsp;";
         Html::showToolTip(nl2br(__('Please use this color format', 'archires')),
                           ['link'       => 'http://www.graphviz.org/doc/info/colors.html',
                            'linktarget' => '_blank']);
         echo "</td><td></div>";
         echo "<div class='center'>";
         echo "<input type='submit' name='add_color_networkinterface' value=\"".
            _sx('button', 'Add')."\" class='submit' ></div></td></tr>";
         echo "</table>";
         Html::closeForm();
         echo "</div>";
      }

      $query = ['FROM'  => $this->getTable(),
                'ORDER' => 'networkinterfaces_id ASC'];

      if ($result = $DB->request($query)) {
         $number = count($result);

         if ($number) {
            echo "<div id='liste_color' class='spaced center'>";
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
            echo "<th class='left'>".__('Type of network', 'archires')."</th>";
            echo "<th class='left'>".__('Color', 'archires')."</th><th></th>";
            echo "</tr>";

            while ($ligne = $result->next()) {
               $ID = $ligne["id"];
               echo "<tr class='tab_bg_1'>";
               if ($canupdate) {
                  echo "<td width='10'>";
                  Html::showMassiveActionCheckBox(__CLASS__, $ID);
                  echo "</td>";
               }
               echo "</td><td>".Dropdown::getDropdownName("glpi_networkinterfaces",
                                                     $ligne["networkinterfaces_id"])."</td><";
               echo "td bgcolor='".$ligne["color"]."'>".$ligne["color"]."</td>";
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


   function dropdownNetworkInterface() {
      global $DB;

      $colors = [];
      foreach($DB->request("glpi_plugin_archires_networkinterfacecolors") as $color) {
         $colors[] = $color['networkinterfaces_id'];
      }

      $query = ['FROM'  => 'glpi_networkinterfaces',
                'WHERE' =>  ['NOT' => ['id' => [implode("','",$colors)]]],
                'ORDER' => 'name'];

      $result = $DB->request($query);

      if (count($result)) {
         while ($data = $result->next()) {
            $values[$data['id']] = $data["name"];
         }
         Dropdown::showFromArray('networkinterfaces_id', $values, ['width'               => '80%',
                                                                   'display_emptychoice' => true]);
      }
   }


   function getForbiddenStandardMassiveAction() {

      $forbidden   = parent::getForbiddenStandardMassiveAction();
      $forbidden[] = 'update';
      return $forbidden;
   }
}
