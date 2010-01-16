<?php
/*
 * @version $Id: HEADER 1 2009-09-21 14:58 Tsmr $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2009 by the INDEPNET Development Team.

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
// Original Author of file: CAILLAUD Xavier
// Purpose of file: plugin archires v1.8.0 - GLPI 0.80
// ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginArchiresQueryType extends CommonDBTM {

   function getFromDBbyType($itemtype, $type,$type_query,$query_ID) {
      global $DB;

      $query = "SELECT *
                FROM `".$this->getTable()."`
                WHERE `itemtype` = '$itemtype'
                      AND `type` = '$type'
                      AND `querytype` = '$type_query'
                      AND `queries_id` = '$query_ID'";

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


   function addType($querytype,$type,$itemtype,$queries_id) {
      global $DB;

      if ($type!='-1') {
         if (!$this->GetfromDBbyType($itemtype,$type,$querytype,$queries_id)) {
            $this->add(array('itemtype'   => $itemtype,
                             'type'       => $type,
                             'querytype'  => $querytype,
                             'queries_id' => $queries_id));
         }
      } else {
         $query = "SELECT *
                   FROM `".getTableForItemType($itemtype."Type")."` ";
         $result = $DB->query($query);
         $number = $DB->numrows($result);
         $i = 0;
         while ($i < $number) {
            $type_table = $DB->result($result, $i, "id");
            if (!$this->GetfromDBbyType($itemtype,$type_table,$querytype,$queries_id)) {
               $this->add(array('itemtype'   => $itemtype,
                                'type'       => $type_table,
                                'querytype'  => $querytype,
                                'queries_id' => $queries_id));
            }
            $i++;
         }
      }
   }


   function deleteType($ID) {
      $this->delete(array('id'=>$ID));
   }


   function queryTypeCheck($querytype,$views_id,$val) {
      global $DB;

      $query0 = "SELECT *
                 FROM `".$this->getTable()."`
                 WHERE `querytype` = '$querytype'
                       AND `queries_id` = '$views_id'
                       AND `itemtype` = '$val'";
      $result0 = $DB->query($query0);

      $query = "";
      if ($DB->numrows($result0)>0) {
        $itemtable = getTableForItemType($val);
        $query = "AND `$itemtable`.`".getForeignKeyFieldForTable(getTableForItemType($val."Type"))."` IN (0 ";
         while ($data0=$DB->fetch_array($result0)) {
            $query .= ",'".$data0["type"]."' ";
         }
         $query .= ") ";
      }
      return $query;
   }


   function showTypes($type,$ID) {
      global $CFG_GLPI,$DB,$LANG;

      if ($type == 'PluginArchiresLocationQuery') {
         $page = "locationquery";
      } else if ($type == 'PluginArchiresNetworkEquipmentQuery') {
         $page = "networkequipmentquery";
      } else if ($type == 'PluginArchiresApplianceQuery') {
         $page = "appliancequery";
      }

      if (plugin_archires_haveRight("archires","w")) {
         echo "<form method='post'  action=\"./".$page.".form.php\">";
         echo "<table class='tab_cadre' cellpadding='5' width='34%'><tr><th colspan='2'>";
         echo $LANG['plugin_archires'][2]." : </th></tr>";
         echo "<tr class='tab_bg_1'><td>";

         $PluginArchiresArchires = new PluginArchiresArchires();
         $PluginArchiresArchires->showAllItems("type",0,0,$_SESSION["glpiactive_entity"]);

         echo "</td>";
         echo "<td>";
         echo "<input type='hidden' name='query' value='$ID'>";
         echo "<input type='submit' name='addtype' value=\"".$LANG['buttons'][2]."\" class='submit'>";
         echo "</td></tr>";
         echo "</table>";
         echo "</form>";
      }

      $query = "SELECT *
                FROM `".$this->getTable()."`
                WHERE `queries_id` = '$ID'
                      AND `querytype` = '$type'
                ORDER BY `itemtype`, `type` ASC";

      $i = 0;
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
               $PluginArchiresArchires = new PluginArchiresArchires();
               $item = new $ligne["itemtype"]();
               echo "<td>".$item->getTypeName()."</td>";
               $class = $ligne["itemtype"]."Type";
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

            if (plugin_archires_haveRight("archires","w")) {
               echo "<tr class='tab_bg_1'>";
               if ($number > 1) {
                  echo "<td colspan='6' class='center'>";
               } else {
                  echo "<td colspan='3' class='center'>";
               }

               echo "<a onclick= \"if (markCheckboxes('massiveaction_form$rand')) return false;\" href='".
                     $_SERVER['PHP_SELF']."?select=all'>".$LANG['buttons'][18]."</a>";
               echo " - <a onclick= \"if (unMarkCheckboxes('massiveaction_form$rand')) return false;\" href='".
                     $_SERVER['PHP_SELF']."?select=none'>".$LANG['buttons'][19]."</a> ";
               echo "<input type='submit' name='deletetype' value=\"".$LANG['buttons'][6]."\" class='submit'>";
               echo "</td></tr>";
            }
            echo "</table>";
            echo "</div>";
            echo "</form>";
         }
      }
   }


   function clean() {
      global $DB;

      $query = "DELETE
                FROM `".$this->getTable()."`
                WHERE `queries_id` = '".$this->fields['id']."'";
      $DB->query($query);
   }

}

?>