<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 Archires plugin for GLPI
 Copyright (C) 2003-2011 by the archires Development Team.

 https://forge.indepnet.net/projects/archires
 -------------------------------------------------------------------------

 LICENSE

 This file is part of archires.

 Archires is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Archires is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Archires. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginArchiresQueryType extends CommonDBTM {


   function canCreate() {
      return plugin_archires_haveRight('archires', 'w');
   }


   function canView() {
      return plugin_archires_haveRight('archires', 'r');
   }


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

      if ($type!='-1') {
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


   function deleteType($ID) {
      $this->delete(array('id' => $ID));
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
      if ($DB->numrows($result0)>0) {
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
      global $CFG_GLPI, $DB, $LANG;

      $type     = $item->getType();
      $showtype = new self();
      $ID       = $item->getID();

      if ($type == 'PluginArchiresLocationQuery') {
         $page = "locationquery";
      } else if ($type == 'PluginArchiresNetworkEquipmentQuery') {
         $page = "networkequipmentquery";
      } else if ($type == 'PluginArchiresApplianceQuery') {
         $page = "appliancequery";
      }

      $PluginArchiresArchires = new PluginArchiresArchires();

      if ($showtype->canCreate()) {
         echo "<form method='post'  action=\"./".$page.".form.php\">";
         echo "<table class='tab_cadre' cellpadding='5' width='34%'><tr><th colspan='2'>";
         echo $LANG['plugin_archires'][2]." : </th></tr>";
         echo "<tr class='tab_bg_1'><td>";
         $PluginArchiresArchires->showAllItems("type", 0, 0, $_SESSION["glpiactive_entity"]);
         echo "</td>";
         echo "<td>";
         echo "<input type='hidden' name='query' value='$ID'>";
         echo "<input type='submit' name='addtype' value=\"".$LANG['buttons'][2]."\" class='submit'>";
         echo "</td></tr>";
         echo "</table>";
         echo "</form>";
      }

      $query = "SELECT *
                FROM `".$showtype->getTable()."`
                WHERE `plugin_archires_queries_id` = '$ID'
                      AND `querytype` = '$type'
                ORDER BY `itemtype`, `type` ASC";

      $i    = 0;
      $rand = mt_rand();
      if ($result = $DB->query($query)) {
         $number = $DB->numrows($result);
         if ($number != 0) {
            echo "<form method='post' name='massiveaction_form$rand' id='massiveaction_form$rand' ".
                  "action=\"./".$page.".form.php\">";
            echo "<div id='liste'>";
            echo "<table class='tab_cadre' cellpadding='5'>";
            echo "<tr>";
            echo "<th class='left'>".$LANG['plugin_archires'][12]."</th>";
            echo "<th class='left'>".$LANG['plugin_archires'][13]."</th><th></th>";
            if ($number > 1) {
               echo "<th class='left'>".$LANG['plugin_archires'][12]."</th>";
               echo "<th class='left'>".$LANG['plugin_archires'][13]."</th><th></th>";
            }
            echo "</tr>";

            while ($ligne= mysql_fetch_array($result)) {
               $ID = $ligne["id"];

               if ($i % 2==0 && $number>1) {
                  echo "<tr class='tab_bg_1'>";
               }
               if ($number==1) {
                  echo "<tr class='tab_bg_1'>";
               }
               $item      = new $ligne["itemtype"]();
               echo "<td>".$item->getTypeName()."</td>";
               $class     = $ligne["itemtype"]."Type";
               $typeclass = new $class();
               $typeclass->getFromDB($ligne["type"]);
               echo "<td>".$typeclass->fields["name"]."</td>";
               echo "<td>";
               echo "<input type='hidden' name='id' value='$ID'>";
               echo "<input type='checkbox' name='item[$ID]' value='1'>";
               echo "</td>";

               $i++;
               if (($i  == $number) && ($number  % 2 !=0) && $number>1)
                  echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
            }

            if ($showtype->canCreate()) {
               echo "<tr class='tab_bg_1'>";
               if ($number > 1) {
                  echo "<td colspan='6' class='center'>";
               } else {
                  echo "<td colspan='3' class='center'>";
               }

               echo "<a onclick= \"if (markCheckboxes('massiveaction_form$rand')) return false;\"
                     href='#'>".$LANG['buttons'][18]."</a>";
               echo " - <a onclick= \"if (unMarkCheckboxes('massiveaction_form$rand')) return false;\"
                     href='#'>".$LANG['buttons'][19]."</a> ";
               Html::closeArrowMassives(array('deletetype' => $LANG['buttons'][6]));
            } else {
               echo "</table>";
            }
            echo "</div>";
            echo "</form>";
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
      global $LANG;

      if (!$withtemplate && plugin_archires_haveRight('archires', 'r')) {
         switch ($item->getType()) {
            case 'PluginArchiresApplianceQuery' :
            case 'PluginArchiresLocationQuery' :
            case 'PluginArchiresNetworkEquipmentQuery' :
               return $LANG['plugin_archires'][13];
         }
      }
      return '';
   }

}

?>