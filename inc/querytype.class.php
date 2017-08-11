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

class PluginArchiresQueryType extends CommonDBTM {

   static $rightname = "plugin_archires";


   function getFromDBbyType($itemtype, $type,$type_query,$query_ID) {
      global $DB;

      $query = "SELECT *
                FROM `".$this->getTable()."`
                WHERE `itemtype` = '$itemtype'
                      AND `type` = '$type'
                      AND `querytype` = '$type_query'
                      AND `plugin_archires_queries_id` = '$query_ID'";

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


   function addType($querytype, $type, $itemtype, $plugin_archires_queries_id) {
      global $DB;

      if ($type != '-1') {
         if (!$this->getFromDBbyType($itemtype, $type, $querytype, $plugin_archires_queries_id)) {
            $this->add(array('itemtype'                   => $itemtype,
                             'type'                       => $type,
                             'querytype'                  => $querytype,
                             'plugin_archires_queries_id' => $plugin_archires_queries_id));
         }
      } else {
         $query = "SELECT *
                   FROM `".getTableForItemType($itemtype."Type")."` ";
         $result = $DB->query($query);
         $number = $DB->numrows($result);
         $i      = 0;

         while ($i < $number) {
            $type_table = $DB->result($result, $i, "id");
            if (!$this->getFromDBbyType($itemtype, $type_table, $querytype,
                                        $plugin_archires_queries_id)) {
               $this->add(array('itemtype'                   => $itemtype,
                                'type'                       => $type_table,
                                'querytype'                  => $querytype,
                                'plugin_archires_queries_id' => $plugin_archires_queries_id));
            }
            $i++;
         }
      }
   }


   function queryTypeCheck($querytype, $plugin_archires_views_id, $val) {
      global $DB;

      $query0 = "SELECT *
                 FROM `".$this->getTable()."`
                 WHERE `querytype` = '$querytype'
                       AND `plugin_archires_queries_id` = '$plugin_archires_views_id'
                       AND `itemtype` = '$val'";
      $result0 = $DB->query($query0);
      $query   = "";
      if ($DB->numrows($result0) > 0) {
        $itemtable = getTableForItemType($val);
        $query     = "AND `$itemtable`.`".getForeignKeyFieldForTable(getTableForItemType($val."Type"))."`
                           IN (0 ";
         while ($data0=$DB->fetch_array($result0)) {
            $query .= ",'".$data0["type"]."' ";
         }
         $query .= ") ";
      }
      return $query;
   }


   static function showTypes($item) {
      global $DB;

      $type     = $item->getType();
      $ID       = $item->getID();

      if ($type == 'PluginArchiresLocationQuery') {
         $page = "locationquery";
      } else if ($type == 'PluginArchiresNetworkEquipmentQuery') {
         $page = "networkequipmentquery";
      } else if ($type == 'PluginArchiresApplianceQuery') {
         $page = "appliancequery";
      }

      $PluginArchiresArchires = new PluginArchiresArchires();

      if (Session::haveRight("plugin_archires", UPDATE)) {
         echo "<form method='post' action=\"./".$page.".form.php\">";
         echo "<table class='tab_cadre' cellpadding='5' width='34%'><tr><th colspan='2'>";
         echo __('Display types of items', 'archires')."</th></tr>";
         echo "<tr class='tab_bg_1'><td>";
         $PluginArchiresArchires->showAllItems("type", 0, 0, $_SESSION["glpiactive_entity"]);
         echo "</td>";
         echo "<td>";
         echo "<input type='hidden' name='query' value='$ID'>";
         echo "<input type='submit' name='addtype' value=\""._sx('button', 'Add')."\" class='submit'>";
         echo "</td></tr>";
         echo "</table>";
         Html::closeForm();
      }

      $query = "SELECT *
                FROM `glpi_plugin_archires_querytypes`
                WHERE `plugin_archires_queries_id` = '$ID'
                      AND `querytype` = '$type'
                ORDER BY `itemtype`, `type` ASC";

      if ($result = $DB->query($query)) {
         $number = $DB->numrows($result);
         if ($number != 0) {
            echo "<div id='liste'>";
            if (Session::haveRight("plugin_archires", UPDATE)) {
               $rand = mt_rand();
               Html::openMassiveActionsForm('mass'.__CLASS__.$rand);
               $massiveactionparams = array('num_displayed'    => $number,
                                            'container'        => 'mass'.__CLASS__.$rand);
               Html::showMassiveActions($massiveactionparams);
            }
            echo "<table class='tab_cadre' cellpadding='5' width='63%'>";
            echo "<tr>";
            if (Session::haveRight("plugin_archires", UPDATE)) {
               echo "<th width='10'>";
               Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand);
               echo "</th>";
            }
            echo "<th class='left'>".__('Item')."</th>";
            echo "<th class='left'>".__('Item type')."</th><th></th>";
            echo "</tr>";

            while ($ligne = $DB->fetch_assoc($result)) {
               $ID = $ligne["id"];
               echo "<tr class='tab_bg_1'>";
               echo "<td width='10'>";
               if (Session::haveRight("plugin_archires", UPDATE)) {
                  Html::showMassiveActionCheckBox(__CLASS__, $ID);
               } else {
                  echo "&nbsp;";
               }
               $item = new $ligne["itemtype"]();
               echo "<td>".$item->getTypeName()."</td>";
               $class     = $ligne["itemtype"]."Type";
               $typeclass = new $class();
               $typeclass->getFromDB($ligne["type"]);
               echo "<td>".$typeclass->fields["name"]."</td>";
               echo "<td>";
               echo "<input type='hidden' name='id' value='$ID'>";
               echo "</td>";
            }

            echo "</table>";
            if (Session::haveRight("plugin_archires", UPDATE)) {
               $massiveactionparams['ontop'] = false;
               Html::showMassiveActions($massiveactionparams);
            }
            echo "</div>";
            Html::closeForm();
         }
      }
   }


   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      switch ($item->getType()) {
         case 'PluginArchiresApplianceQuery' :
         case 'PluginArchiresLocationQuery' :
         case 'PluginArchiresNetworkEquipmentQuery' :
            self::showTypes($item);
            break;
      }
      return true;
   }


   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if (!$withtemplate && Session::haveRight("plugin_archires", READ)) {
          switch ($item->getType()) {
            case 'PluginArchiresApplianceQuery' :
            case 'PluginArchiresLocationQuery' :
            case 'PluginArchiresNetworkEquipmentQuery' :
               return __('Item type');
         }
      }
      return '';
   }


   function getForbiddenStandardMassiveAction() {

      $forbidden   = parent::getForbiddenStandardMassiveAction();
      $forbidden[] = 'update';
      return $forbidden;
   }
}
