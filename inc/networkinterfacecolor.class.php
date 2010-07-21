<?php
/*
 * @version $Id: HEADER 1 2010-02-24 00:12 Tsmr $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

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
// Original Author of file: CAILLAUD Xavier & COLLET Remi & LASSON Nelly
// Purpose of file: plugin archires v1.8.0 - GLPI 0.78
// ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginArchiresNetworkInterfaceColor extends CommonDBTM {
   
   function canCreate() {
      return plugin_archires_haveRight('archires', 'w');
   }

   function canView() {
      return plugin_archires_haveRight('archires', 'r');
   }
   
   function getFromDBbyNetworkInterface($networkinterfaces_id) {
      global $DB;

      $query = "SELECT *
                FROM `".$this->getTable()."`
                WHERE `networkinterfaces_id` = '$networkinterfaces_id'";

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


   function addNetworkInterfaceColor($networkinterfaces_id,$color) {
      global $DB;

      if ($networkinterfaces_id!='-1') {
         if ($this->getFromDBbyNetworkInterface($networkinterfaces_id)) {
            $this->update(array('id'    => $this->fields['id'],
                                'color' => $color));
         } else {
            $this->add(array('networkinterfaces_id' => $networkinterfaces_id,
                             'color'                => $color));
         }
      } else {
         $query = "SELECT *
                   FROM `glpi_networkinterfaces` ";
         $result = $DB->query($query);
         $number = $DB->numrows($result);
         $i = 0;
         while ($i < $number) {
           $networkinterface_table = $DB->result($result, $i, "id");
           if ($this->getFromDBbyNetworkInterface($networkinterface_table)) {
               $this->update(array('id'    => $this->fields['id'],
                                  'color' => $color));
           } else {
               $this->add(array('networkinterfaces_id' => $networkinterface_table,
                                'color'                => $color));
           }
           $i++;
         }
      }
   }


   function deleteNetworkInterfaceColor($ID) {
      $this->delete(array('id' => $ID));
   }


   function showConfigForm($canupdate=false) {
      global $DB,$LANG,$CFG_GLPI;

      $query = "SELECT *
                FROM `".$this->getTable()."`
                ORDER BY `networkinterfaces_id` ASC;";
      $i = 0;
      if ($result = $DB->query($query)) {
         $number = $DB->numrows($result);

         if ($canupdate) {
            echo "<form method='post' name='massiveaction_form_networkinterface_color' id='".
                  "massiveaction_form_networkinterface_color' action='./config.form.php'>";
         }
         $used = array();
         if ($number != 0) {
            echo "<div id='liste_color'>";
            echo "<table class='tab_cadre' cellpadding='5'>";
            echo "<tr>";
            echo "<th class='left'>".$LANG['plugin_archires'][19]."</th>";
            echo "<th class='left'>".$LANG['plugin_archires'][20]."</th><th></th>";
            if ($number > 1) {
               echo "<th class='left'>".$LANG['plugin_archires'][19]."</th>";
               echo "<th class='left'>".$LANG['plugin_archires'][20]."</th><th></th>";
            }
            echo "</tr>";

            while ($ligne= mysql_fetch_array($result)) {
               $ID = $ligne["id"];
               $networkinterfaces_id = $ligne["networkinterfaces_id"];
               $used[] = $networkinterfaces_id;
               if ($i % 2 == 0 && $number > 1) {
                  echo "<tr class='tab_bg_1'>";
               }
               if ($number == 1) {
                  echo "<tr class='tab_bg_1'>";
               }
               echo "<td>".Dropdown::getDropdownName("glpi_networkinterfaces",
                                                     $ligne["networkinterfaces_id"])."</td><";
               echo "td bgcolor='".$ligne["color"]."'>".$ligne["color"]."</td>";
               echo "<td>";
               echo "<input type='hidden' name='id' value='$ID'>";
               if ($canupdate) {
                  echo "<input type='checkbox' name='item_color[$ID]' value='1'>";
               }
               echo "</td>";

               $i++;
               if (($i  == $number) && ($number  % 2 !=0) && $number>1) {
                  echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
               }
            }

            if ($canupdate) {
               echo "<tr class='tab_bg_1'>";
               if ($number > 1) {
                  echo "<td colspan='8' class='center'>";
               } else {
                  echo "<td colspan='4' class='center'>";
               }

               echo "<a onclick= \"if (markCheckboxes('massiveaction_form_networkinterface_color')) ".
                     "return false;\" href='#'>".
                     $LANG['buttons'][18]."</a>";
               echo " - <a onclick= \"if (unMarkCheckboxes('massiveaction_form_networkinterface_color')) ".
                     "return false;\" href='#'>".
                     $LANG['buttons'][19]."</a> ";
               echo "<input type='submit' name='delete_color_networkinterface' value=\"".
                     $LANG['buttons'][6]."\" class='submit'></td></tr>";
            }
            echo "</table>";
            echo "</div>";
         }

         if ($canupdate) {
            echo "<table class='tab_cadre' cellpadding='5'><tr ><th colspan='3'>";
            echo $LANG['plugin_archires']['setup'][8]." : </th></tr>";
            echo "<tr class='tab_bg_1'><td>";
            $this->dropdownNetworkInterface($used);
            echo "</td><td>";
            echo "<input type='text' name=\"color\">";
            echo "&nbsp;";
            showToolTip(nl2br($LANG['plugin_archires']['setup'][21]),
                        array('link'=>'http://www.graphviz.org/doc/info/colors.html',
                              'linktarget'=>'_blank'));
            echo "<td>";
            echo "<div align='center'><input type='submit' name='add_color_networkinterface' value=\"".
                  $LANG['buttons'][2]."\" class='submit' ></div></td></tr>";
            echo "</table>";
            echo "</form>";
         }
      }
   }


   function dropdownNetworkInterface($used=array()) {
      global $DB,$LANG,$CFG_GLPI;

      $limit = $_SESSION["glpidropdown_chars_limit"];

      $where = "";

      if (count($used)) {
         $where .= "WHERE `id` NOT IN (0";
         foreach ($used as $ID) {
            $where .= ",$ID";
         }
         $where .= ")";
      }

      $query = "SELECT *
                FROM `glpi_networkinterfaces`
                $where
                ORDER BY `name`";

      $result = $DB->query($query);
      $number = $DB->numrows($result);

      if ($number >0) {
         echo "<select name='networkinterfaces_id'>\n";
         echo "<option value='0'>".DROPDOWN_EMPTY_VALUE."</option>\n";
         echo "<option value='-1'>".$LANG['plugin_archires'][21]."</option>\n";
         while ($data= mysql_fetch_array($result)) {
            $output = $data["name"];
            if (utf8_strlen($output)>$limit) {
               $output = utf8_substr($output,0,$limit)."&hellip;";
            }
            echo "<option value='".$data["id"]."'>".$output."</option>";
         }
         echo "</select>";
      }
   }
}

?>