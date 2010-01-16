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

class PluginArchiresArchires extends CommonDBTM {

   function showAllItems($myname,$value_type=0,$value=0,$entity_restrict=-1) {
      global $DB,$LANG,$CFG_GLPI;

      $types = array('Computer','NetworkEquipment','Peripheral','Phone','Printer');

      $rand=mt_rand();

      echo "<table border='0'><tr><td>\n";

      echo "<select name='type' id='item_type$rand'>\n";
      echo "<option value='0;0'>-----</option>\n";
      
      foreach ($types as $type => $label) {
         $item = new $label();
         echo "<option value='".$label.";".getTableForItemType($label."Type")."'>".
               $item->getTypeName()."</option>\n";
      }

      echo "</select>";

      $params=array('typetable'       => '__VALUE__',
                    'value'           => $value,
                    'myname'          => $myname,
                    'entity_restrict' => $entity_restrict);

      ajaxUpdateItemOnSelectEvent("item_type$rand", "show_$myname$rand",
                                  $CFG_GLPI["root_doc"]."/plugins/archires/ajax/dropdownAllItems.php",
                                  $params);

      echo "</td><td>\n"	;
      echo "<span id='show_$myname$rand'>&nbsp;</span>\n";
      echo "</td></tr></table>\n";

      if ($value>0) {
         echo "<script type='text/javascript' >\n";
         echo "document.getElementById('item_type$rand').value='".$value_type."';";
         echo "</script>\n";

         $params["typetable"]=$value_type;
         ajaxUpdateItem("show_$myname$rand", 
                        $CFG_GLPI["root_doc"]."/plugins/archires/ajax/dropdownAllItems.php",$params);
      }
      return $rand;
   }


   function getItemType($device_type,$type) {
      global $DB;

      $name = "";
      if (file_exists(GLPI_ROOT."/inc/".strtolower($device_type)."type.class.php")) {

         $query = "SELECT `name` 
                   FROM `".getTableForItemType($device_type."Type")."` 
                   WHERE `id` = '$type' ";
         $result = $DB->query($query);
         $number = $DB->numrows($result);
         if ($number !="0") {
            $name = $DB->result($result, 0, "name");
         }
      }
      return $name;
   }

}

?>