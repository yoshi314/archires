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

class PluginArchires extends CommonDBTM {

	function title() {	
		global $CFG_GLPI,$LANG;
				
      echo "<div align='center'><table border='0'><tr><td>";
      echo "<img src=\"".$CFG_GLPI["root_doc"]."/plugins/archires/pics/archires.png\" alt='".$LANG['plugin_archires']['title'][0]."' title='".$LANG['plugin_archires']['title'][0]."'></td>";
      if (plugin_archires_haveRight("archires","w") || haveRight("config","w"))
         echo "<td><a  class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/index.php?new=1\"><b>".$LANG['plugin_archires']['title'][7]."</b></a></td>";
      else
         echo "<td><a  class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/index.php\"><b>".$LANG['plugin_archires']['title'][0]."</b></a></td>";

      echo "<td><a  class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/front/plugin_archires.view.index.php\"><b>".$LANG['plugin_archires']['title'][3]."</b></a></td>";

      if (plugin_archires_haveRight("archires","w") || haveRight("config","w"))
         echo "<td><a class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/front/plugin_archires.config.php\">".$LANG['plugin_archires']['profile'][2]."</a></td>";
      echo "</tr></table></div>";
   }
	
	function titleGraph() {
      global $CFG_GLPI,$LANG;
				
      echo "<div align='center'><table border='0'><tr><td>";
      echo "<img src=\"".$CFG_GLPI["root_doc"]."/plugins/archires/pics/archires.png\" alt='".$LANG['plugin_archires']['title'][0]."' title='".$LANG['plugin_archires']['title'][0]."'></td>";
      echo "<td><a  class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/index.php\"><b>".$LANG['plugin_archires']['title'][0]."</b></a></td>";
      echo "<td><a  class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/front/plugin_archires.view.index.php\"><b>".$LANG['plugin_archires']['title'][3]."</b></a></td>";
      if (plugin_archires_haveRight("archires","w"))
      echo "<td><a class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/front/plugin_archires.config.php\">".$LANG['plugin_archires']['profile'][2]."</a>";
      echo "</td></tr></table></div>";
	}
	
	function dropdownAllItems($myname,$value_type=0,$value=0,$entity_restrict=-1) {
      global $DB,$LANG,$CFG_GLPI,$PLUGIN_ARCHIRES_TYPE_TABLES,$PLUGIN_ARCHIRES_TYPE_NAME;
      
      $types = array(
         COMPUTER_TYPE,NETWORKING_TYPE,PERIPHERAL_TYPE,
         PHONE_TYPE,PRINTER_TYPE);
         
      $rand=mt_rand();
      $ci=new CommonItem();
      
      echo "<table border='0'><tr><td>\n";

      echo "<select name='type' id='item_type$rand'>\n";
      echo "<option value='0;0'>-----</option>\n";
      
      foreach ($types as $type => $label) {
         echo "<option value='".$label.";".$PLUGIN_ARCHIRES_TYPE_TABLES[$label]."'>".$PLUGIN_ARCHIRES_TYPE_NAME[$label]."</option>\n";
      }

      echo "</select>";

      $params=array('idtable'=>'__VALUE__',
      'value'=>$value,
      'myname'=>$myname,
      'entity_restrict'=>$entity_restrict,
      );
      ajaxUpdateItemOnSelectEvent("item_type$rand","show_$myname$rand",$CFG_GLPI["root_doc"]."/plugins/archires/ajax/dropdownAllItems.php",$params);

      echo "</td><td>\n"	;
      echo "<span id='show_$myname$rand'>&nbsp;</span>\n";
      echo "</td></tr></table>\n";

      if ($value>0) {
         echo "<script type='text/javascript' >\n";
         echo "document.getElementById('item_type$rand').value='".$value_type."';";
         echo "</script>\n";

         $params["idtable"]=$value_type;
         ajaxUpdateItem("show_$myname$rand",$CFG_GLPI["root_doc"]."/plugins/archires/ajax/dropdownAllItems.php",$params);
      }
      return $rand;
   }
  
   function getType($device_type,$type) {
      global $DB,$PLUGIN_ARCHIRES_TYPE_TABLES;
    
      $name="";
      if (isset($PLUGIN_ARCHIRES_TYPE_TABLES[$device_type])) {
    
         $query="SELECT `name` 
            FROM `".$PLUGIN_ARCHIRES_TYPE_TABLES[$device_type]."` 
            WHERE `id` = '$type' ";
         $result = $DB->query($query);
         $number = $DB->numrows($result);
         if ($number !="0")
         $name=$DB->result($result, 0, "name");
      }
      return $name;
   }
  
   function getClassType ($type) {
	
      if ($type==PLUGIN_ARCHIRES_LOCATIONS_QUERY) {
         $object= "PluginArchiresQueryLocation";
      } else if ($type==PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY) {
         $object= "PluginArchiresQueryNetworkEquipment";
      } else if ($type==PLUGIN_ARCHIRES_APPLIANCES_QUERY) {
         $object= "PluginArchiresQueryAppliance";
      }
      
      return $object;

   }
}

?>