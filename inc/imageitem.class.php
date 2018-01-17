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
 @link      https://forge.glpi-project.org/projects/aarchires
 @since     version 2.2
 --------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginArchiresImageItem extends CommonDBTM {

   static $rightname  = "plugin_archires";


   function getFromDBbyType($itemtype, $type) {
      global $DB;

      $query = ['FROM'  => $this->getTable(),
                'WHERE' =>  ['itemtype' => $itemtype,
                             'type'     => $type]];

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


   function addItemImage($type,$itemtype,$img) {
      global $DB;

      if ($type != '-1') {
         if ($this->GetfromDBbyType($itemtype,$type)) {
            $this->update(['id'  => $this->fields['id'],
                           'img' => $img]);
         } else {
            $this->add(['itemtype' => $itemtype,
                        'type'     => $type,
                        'img'      => $img]);
         }
      } else {
         $query  = ['FROM' => getTableForItemType($itemtype."Type")];

         $result = $DB->request($query);
         $i      = 0;
         while ($i < count($result)) {
            $row        = $result->next();
            $type_table = $row['id'];
            if ($this->GetfromDBbyType($itemtype,$type_table)) {
            $this->update(['id'  => $this->fields['id'],
                           'img' => $img]);
           } else {
             $this->add(['itemtype' => $itemtype,
                         'type'     => $type_table,
                         'img'      => $img]);
            }
            $i++;
         }
      }
   }


   function showConfigForm() {
      global $DB, $CFG_GLPI;

      echo "<form method='post' action='./config.form.php'>";
      echo "<table class='tab_cadre' cellpadding='5' width='50%'>";
      echo "<tr><th colspan='4'>".__('Associate pictures with item types', 'archires')."</th></tr>";

      echo "<tr class='tab_bg_1'><td>";
      $PluginArchiresArchires = new PluginArchiresArchires();
      $PluginArchiresArchires->showAllItems("type",0,0,$_SESSION["glpiactive_entity"]);
      echo "</td><td>";
      //file
      $rep = "../pics/";
      $dir = opendir($rep);

      while ($f = readdir($dir)) {
         if (is_file($rep.$f)) {
            $values[$f] = $f;
         }
      }
      Dropdown::showFromArray('img', $values);
      closedir($dir);
      Html::showToolTip(nl2br(__('Some types of items must be created so that the association can exist',
                                 'archires')));
      echo "</td><td>";

      echo "<div class='center'><input type='submit' name='add' value=\""._sx('button', 'Add').
             "\" class='submit'></div>";
      echo "</td></tr>";
      echo "</table>";
      Html::closeForm();

      $query = ['FROM'  => $this->getTable(),
                'ORDER' => ['itemtype ASC', 'type ASC']];

      if ($result = $DB->request($query)) {
         $number = count($result);
         if ($number) {
            $rand = mt_rand();
            echo "<div id='liste'>";
            Html::openMassiveActionsForm('mass'.__CLASS__.$rand);
            $massiveactionparams = ['num_displayed'    => $number,
                                    'container'        => 'mass'.__CLASS__.$rand];
            Html::showMassiveActions($massiveactionparams);

            echo "<table class='tab_cadre' cellpadding='5' width='50%'>";
            echo "<tr>";
            echo "<th width='10'>";
            Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand);
            echo "</th>";
            echo "<th class='left'>".__('Item')."</th>";
            echo "<th class='left'>".__('Item type')."</th>";
            echo "<th class='left'>".__('Picture', 'archires')."</th><th></th>";
            echo "</tr>";

            while ($ligne = $result->next()) {
               $ID = $ligne["id"];
               echo "<tr class='tab_bg_1'>";
               echo "<td width='10'>";
               $PluginArchiresArchires = new PluginArchiresArchires();
               $item                   = new $ligne["itemtype"]();
               Html::showMassiveActionCheckBox(__CLASS__, $ID);
               echo "</td><td>".$item->getTypeName()."</td>";
               $class     = $ligne["itemtype"]."Type";
               $typeclass = new $class();
               $typeclass->getFromDB($ligne["type"]);
               echo "<td>".$typeclass->fields["name"]."</td>";
               echo "<td><img src=\"".$CFG_GLPI["root_doc"]."/plugins/archires/pics/".$ligne["img"].
                           "\" alt=\"".$ligne["img"]."\" title=\"".$ligne["img"]."\"></td>";
               echo "<td width='10'>";
               echo "</td>";
            }

            echo "</table>";
            $massiveactionparams['ontop'] = false;
            Html::showMassiveActions($massiveactionparams);
            echo "</div>";
            Html::closeForm();
         }
      }
   }


   function displayItemImage($type,$itemtype,$test) {
      global $DB;

      $path = "";
      if ($test)
         $path="../";

      $image_name = $path."pics/nothing.png";

      $query = ['FROM'  => 'glpi_plugin_archires_imageitems',
                'WHERE' => ['itemtype' => $itemtype]];

      if ($result = $DB->request($query)) {
         while ($ligne= $result->next()) {
            $config_img = $ligne["img"];
            if ($type == $ligne["type"]) {
               $image_name = $path."pics/$config_img";
            }
         }
      }
      return $image_name;
   }


   function getForbiddenStandardMassiveAction() {

      $forbidden   = parent::getForbiddenStandardMassiveAction();
      $forbidden[] = 'update';
      return $forbidden;
   }
}
