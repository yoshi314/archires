<?php
/*
 * @version $Id: HEADER 2011-03-12 18:01:26 tsmr $
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
// Original Author of file: CAILLAUD Xavier & COLLET Remi & LASSON Nelly & PRUDHOMME Sebastien
// Purpose of file: plugin archires v1.9.0 - GLPI 0.80
// ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginArchiresView extends CommonDBTM {

   const PLUGIN_ARCHIRES_NETWORK_COLOR = 0;
   const PLUGIN_ARCHIRES_VLAN_COLOR    = 1;

   const PLUGIN_ARCHIRES_JPEG_FORMAT = 0;
   const PLUGIN_ARCHIRES_PNG_FORMAT  = 1;
   const PLUGIN_ARCHIRES_GIF_FORMAT  = 2;
   const PLUGIN_ARCHIRES_SVG_FORMAT  = 3;


   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_archires']['title'][3];
   }


   function canCreate() {
      return plugin_archires_haveRight('archires', 'w');
   }


   function canView() {
      return plugin_archires_haveRight('archires', 'r');
   }


   function getSearchOptions() {
      global $LANG;

      $tab = array();
      $tab['common'] = $LANG['plugin_archires']['title'][3];

      $tab[1]['table']         = $this->getTable();
      $tab[1]['field']         = 'name';
      $tab[1]['name']          = $LANG['plugin_archires']['search'][1];
      $tab[1]['datatype']      = 'itemlink';
      $tab[1]['itemlink_type'] = $this->getType();

      $tab[2]['table']     = $this->getTable();
      $tab[2]['field']     = 'computer';
      $tab[2]['name']      = $LANG['plugin_archires'][6];
      $tab[2]['datatype']  = 'bool';

      $tab[3]['table']     = $this->getTable();
      $tab[3]['field']     = 'networking';
      $tab[3]['name']      = $LANG['plugin_archires'][7];
      $tab[3]['datatype']  = 'bool';

      $tab[4]['table']     = $this->getTable();
      $tab[4]['field']     = 'printer';
      $tab[4]['name']      = $LANG['plugin_archires'][8];
      $tab[4]['datatype']  = 'bool';

      $tab[5]['table']     = $this->getTable();
      $tab[5]['field']     = 'peripheral';
      $tab[5]['name']      = $LANG['plugin_archires'][9];
      $tab[5]['datatype']  = 'bool';

      $tab[6]['table']     = $this->getTable();
      $tab[6]['field']     = 'phone';
      $tab[6]['name']      = $LANG['plugin_archires'][10];
      $tab[6]['datatype']  = 'bool';

      $tab[7]['table']     = $this->getTable();
      $tab[7]['field']     = 'display_ports';
      $tab[7]['name']      = $LANG['plugin_archires'][16];
      $tab[7]['datatype']  = 'text';

      $tab[8]['table']     = $this->getTable();
      $tab[8]['field']     = 'display_ip';
      $tab[8]['name']      = $LANG['plugin_archires'][23];
      $tab[8]['datatype']  = 'bool';

      $tab[9]['table']     = $this->getTable();
      $tab[9]['field']     = 'display_type';
      $tab[9]['name']      = $LANG['plugin_archires'][25];
      $tab[9]['datatype']  = 'bool';

      $tab[10]['table']     = $this->getTable();
      $tab[10]['field']     = 'display_state';
      $tab[10]['name']      = $LANG['plugin_archires'][26];
      $tab[10]['datatype']  = 'bool';

      $tab[11]['table']     = $this->getTable();
      $tab[11]['field']     = 'display_location';
      $tab[11]['name']      = $LANG['plugin_archires'][31];
      $tab[11]['datatype']  = 'bool';

      $tab[12]['table']     = $this->getTable();
      $tab[12]['field']     = 'display_entity';
      $tab[12]['name']      = $LANG['plugin_archires'][32];
      $tab[12]['datatype']  = 'bool';

      $tab[13]['table']     = $this->getTable();
      $tab[13]['field']     = 'engine';
      $tab[13]['name']      = $LANG['plugin_archires']['setup'][13];
      $tab[13]['datatype']  = 'text';

      $tab[14]['table']     = $this->getTable();
      $tab[14]['field']     = 'format';
      $tab[14]['name']      = $LANG['plugin_archires']['setup'][15];
      $tab[14]['datatype']  = 'text';

      $tab[15]['table']     = $this->getTable();
      $tab[15]['field']     = 'color';
      $tab[15]['name']      = $LANG['plugin_archires']['setup'][25];
      $tab[15]['datatype']  = 'text';

      return $tab;
   }


   function dropdownObject($obj) {
      global $LANG,$DB,$CFG_GLPI;

      $ID = $obj->fields["id"];

      $query = "SELECT `id`, `name`
                FROM `".$obj->getTable()."`
                WHERE `is_deleted` = '0' ";
      // Add Restrict to current entities
      if ($obj->isEntityAssign()) {
         $query .= getEntitiesRestrictRequest(" AND",$obj->getTable());
      }
      $query.=" ORDER BY `name` ASC";

      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) >0) {
            echo "<select name='plugin_archires_queries_id' size='1'> ";
            while ($ligne = mysql_fetch_array($result)) {
               echo "<option value='".$ligne["id"]."' ".($ligne["id"]=="".$ID.""?" selected ":"").">".
                     $ligne["name"]."</option>";
            }
            echo "</select>";
         }
      }
   }


	function dropdownView($obj,$default) {
      global $DB,$CFG_GLPI;

      if (isset($obj->fields["id"])) {
         $default = $obj->fields["plugin_archires_views_id"];
      }
      $query = "SELECT `id`, `name`
                FROM `".$this->getTable()."`
                WHERE `is_deleted` = '0'
                      AND `entities_id` = '" . $_SESSION["glpiactive_entity"] . "'
                ORDER BY `name` ASC";

      echo "<select name='plugin_archires_views_id' size='1'> ";
      echo "<option value='0'>".Dropdown::EMPTY_VALUE."</option>\n";
      if ($result = $DB->query($query)) {
         while ($ligne= mysql_fetch_array($result)) {
            $view_name = $ligne["name"];
            $view_id   = $ligne["id"];
            echo "<option value='".$view_id."' ".($view_id=="".$default.""?" selected ":"").">".
                  $view_name."</option>";
         }
      }
      echo "</select>";
   }


   static function linkToAllViews($item) {
      global $LANG;

      echo "<div class='center'>";
      echo "<a href=\"./archires.graph.php?id=".$item->getID()."&querytype=".$item->getType()."\">".
             $LANG['plugin_archires'][1];
      echo "</a></div>";
   }


   function viewSelect($obj,$plugin_archires_views_id,$select=0) {
      global $CFG_GLPI,$LANG,$DB;

      $querytype   = get_class($obj);
      $ID          = $obj->fields["id"];
      $object_view = $obj->fields["plugin_archires_views_id"];
      if (!isset($plugin_archires_views_id)) {
         $plugin_archires_views_id = $object_view;
      }
      if ($select) {
         echo "<form method='get' name='selecting' action='".$CFG_GLPI["root_doc"].
               "/plugins/archires/front/archires.graph.php'>";
         echo "<table class='tab_cadre' cellpadding='5'>";
         echo "<tr class='tab_bg_1'>";

         echo "<td class='center'>". $LANG['plugin_archires'][0]." : ";
         $this->dropdownObject($obj);
         echo "</td>";

         echo "<td class='center'>".$LANG['plugin_archires']['title'][3]." : ";
         $this->dropdownView(-1,$plugin_archires_views_id);
         echo "</td>";

         echo "<td>";
         echo "<input type='hidden' name='querytype' value=\"".$querytype."\"> ";
         echo "<input type='submit' class='submit'  name='displayview' value=\"".
                $LANG['buttons'][2]."\"> ";
         echo "</td>";
         echo "</tr>";
         echo "</table>";
         echo "</form>";
      }
      echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/archires/front/archires.map.php?format=".
            self::PLUGIN_ARCHIRES_SVG_FORMAT."&amp;id=".$ID."&amp;querytype=".$querytype.
            "&amp;plugin_archires_views_id=".
            $plugin_archires_views_id."'>".$LANG['plugin_archires']['setup'][16]."</a>";
   }


   function defineTabs($options=array()) {
      global $LANG;

      $ong[1] = $LANG['title'][26];
      return $ong;
   }


   function showForm ($ID, $options=array()) {
      global $CFG_GLPI, $LANG;

      if ($ID>0) {
         $this->check($ID,'r');
      } else {
         $this->check(-1,'w');
         $this->getEmpty();
      }

      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='1'>".$LANG['plugin_archires']['search'][1]." : </td>";
      echo "<td colspan='3'>";
      Html::autocompletionTextField($this, "name", array('size' => 20));
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'><th colspan='4'>".$LANG['plugin_archires'][3]."</th></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_archires'][6]." : </td>";
      echo "<td>";
      Dropdown::showYesNo("computer",$this->fields["computer"]);
      echo "</td>";
      echo "<td>".$LANG['plugin_archires'][7]." : </td>";
      echo "<td>";
      Dropdown::showYesNo("networking",$this->fields["networking"]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_archires'][8]." : </td>";
      echo "<td>";
      Dropdown::showYesNo("printer",$this->fields["printer"]);
      echo "</td>";
      echo "<td>".$LANG['plugin_archires'][9].": </td>";
      echo "<td>";
      Dropdown::showYesNo("peripheral",$this->fields["peripheral"]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_archires'][10]." : </td>";
      echo "<td colspan='3'>";
      Dropdown::showYesNo("phone",$this->fields["phone"]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'><th colspan='4'>".$LANG['plugin_archires'][24]."</th></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_archires'][16]." : </td>";
      echo "<td><select name='display_ports'> ";
      echo "<option ";
      if ($this->fields["display_ports"]=='0') {
         echo "selected ";
      }
      echo "value='0'>".$LANG['choice'][0]."</option>";
      echo "<option ";
      if ($this->fields["display_ports"]=='1') {
         echo "selected ";
      }
      echo "value='1'>".$LANG['plugin_archires'][29]."</option>";
      echo "<option ";
      if ($this->fields["display_ports"]=='2') {
         echo "selected ";
      }
      echo "value='2'>".$LANG['plugin_archires'][33]."</option>";
      echo "</select></td>";
      echo "<td>".$LANG['plugin_archires'][23]." : </td>";
      echo "<td>";
      Dropdown::showYesNo("display_ip",$this->fields["display_ip"]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_archires'][25]." : </td>";
      echo "<td>";
      Dropdown::showYesNo("display_type",$this->fields["display_type"]);
      echo "</td>";
      echo "<td>".$LANG['plugin_archires'][26]." : </td>";
      echo "<td>";
      Dropdown::showYesNo("display_state",$this->fields["display_state"]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_archires'][31]." : </td>";
      echo "<td>";
      Dropdown::showYesNo("display_location",$this->fields["display_location"]);
      echo "</td>";
      echo "<td>".$LANG['plugin_archires'][32]." : </td>";
      echo "<td>";
      Dropdown::showYesNo("display_entity",$this->fields["display_entity"]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'><th colspan='4'>".$LANG['plugin_archires']['search'][6]."</th></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_archires']['setup'][13]." : </td>";
      echo "<td><select name='engine'> ";
      echo "<option ";
      if ($this->fields["engine"]=='0') {
         echo "selected ";
      }
      echo "value='0'>Dot</option>";
      echo "<option ";
      if ($this->fields["engine"]=='1') {
         echo "selected ";
      }
      echo "value='1'>Neato</option>";
      echo "</select>&nbsp;";
      Html::showToolTip(nl2br($LANG['plugin_archires']['setup'][14]));
      echo "</td>";
      echo "<td>".$LANG['plugin_archires']['setup'][15]." : </td>";
      echo "<td><select name='format'> ";
      echo "<option ";
      if ($this->fields["format"]==self::PLUGIN_ARCHIRES_JPEG_FORMAT) {
         echo "selected ";
      }
      echo "value='0'>jpeg</option>";
      echo "<option ";
      if ($this->fields["format"] == self::PLUGIN_ARCHIRES_PNG_FORMAT) {
         echo "selected ";
      }
      echo "value='1'>png</option>";
      echo "<option ";
      if ($this->fields["format"] == self::PLUGIN_ARCHIRES_GIF_FORMAT) {
         echo "selected ";
      }
      echo "value='2'>gif</option>";
      echo "</select></td>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_archires']['setup'][25]." : </td>";
      echo "<td colspan='3'><select name='color'> ";
      echo "<option ";
      if ($this->fields["color"]=='0') {
         echo "selected ";
      }
      echo "value='0'>".$LANG['plugin_archires'][19]."</option>";
      echo "<option ";
      if ($this->fields["color"]=='1') {
         echo "selected ";
      }
      echo "value='1'>".$LANG['plugin_archires'][35]."</option>";
      echo "</select></td></tr>";

      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }


   static function showView($item) {
      global $CFG_GLPI,$DB,$LANG;

      $plugin_archires_views_id = $item->fields["plugin_archires_views_id"];

      if (!$plugin_archires_views_id) {
         return false;
      }
      $view = new self();
      $view->getFromDB($plugin_archires_views_id);

      $name_config = $view->fields["name"];

      echo "<table class='tab_cadre_fixe' cellpadding='2'width='75%'>";
      echo "<tr>";
      echo "<th colspan='3'>".$LANG['plugin_archires']['setup'][20]." : ".$name_config;
      echo "</th></tr>";

      echo "<tr class='tab_bg_2 top'><th>".$LANG['plugin_archires'][3]."</th>";
      echo "<th>".$LANG['plugin_archires'][24]."</th><th>".$LANG['plugin_archires']['search'][6].
           "</th></tr>";

      echo "<tr class='tab_bg_1 top'><td class='center'>";
      if ($view->fields["computer"] != 0) {
         echo $LANG['plugin_archires'][6]." : ".$LANG['choice'][1];
      } else {
         echo $LANG['plugin_archires'][6]." : ".$LANG['choice'][0];
      }
      echo "<br>";

      if ($view->fields["networking"]!=0) {
         echo $LANG['plugin_archires'][7]." : ".$LANG['choice'][1];
      } else {
         echo $LANG['plugin_archires'][7]." : ".$LANG['choice'][0];
      }
      echo "<br>";

      if ($view->fields["printer"]!=0) {
         echo $LANG['plugin_archires'][8]." : ".$LANG['choice'][1];
      } else {
         echo $LANG['plugin_archires'][8]." : ".$LANG['choice'][0];
      }
      echo "<br>";

      if ($view->fields["peripheral"]!=0) {
         echo $LANG['plugin_archires'][9]." : ".$LANG['choice'][1];
      } else {
         echo $LANG['plugin_archires'][9]." : ".$LANG['choice'][0];
      }
      echo "<br>";

      if ($view->fields["phone"]!=0) {
         echo $LANG['plugin_archires'][10]." : ".$LANG['choice'][1];
      } else {
         echo $LANG['plugin_archires'][10]." : ".$LANG['choice'][0];
      }
      echo "</td>";

      echo "<td class='center'>";
      if ($view->fields["display_ports"]!=0) {
         echo $LANG['plugin_archires'][16]." : ".$LANG['choice'][1];
      } else {
         echo $LANG['plugin_archires'][16]." : ".$LANG['choice'][0];
      }
      echo "<br>";

      if ($view->fields["display_ip"]!=0) {
         echo $LANG['plugin_archires'][23]." : ".$LANG['choice'][1];
      } else {
         echo $LANG['plugin_archires'][23]." : ".$LANG['choice'][0];
      }
      echo "<br>";

      if ($view->fields["display_type"]!=0) {
         echo $LANG['plugin_archires'][25]." : ".$LANG['choice'][1];
      } else {
         echo $LANG['plugin_archires'][25]." : ".$LANG['choice'][0];
      }
      echo "<br>";

      if ($view->fields["display_state"]!=0) {
         echo $LANG['plugin_archires'][26]." : ".$LANG['choice'][1];
      } else {
         echo $LANG['plugin_archires'][26]." : ".$LANG['choice'][0];
      }
      echo "<br>";

      if ($view->fields["display_location"]!=0) {
         echo $LANG['plugin_archires'][31]." : ".$LANG['choice'][1];
      } else {
         echo $LANG['plugin_archires'][31]." : ".$LANG['choice'][0];
      }
      echo "<br>";

      if ($view->fields["display_entity"]!=0) {
         echo $LANG['plugin_archires'][32]." : ".$LANG['choice'][1];
      } else {
         echo $LANG['plugin_archires'][32]." : ".$LANG['choice'][0];
      }
      echo "</td>";

      //
      echo "<td class='center'>".$LANG['plugin_archires']['setup'][11]." : ";
      echo $LANG['plugin_archires']['setup'][13]." : ";
      if ($view->fields["engine"]!=0) {
         echo "Neato";
      } else {
         echo "Dot";
      }
      echo "<br>";
      echo $LANG['plugin_archires']['setup'][15]." : ";
      if ($view->fields["format"] == self::PLUGIN_ARCHIRES_JPEG_FORMAT) {
         $format_graph = "jpeg";
      } else if ($view->fields["format"] == self::PLUGIN_ARCHIRES_PNG_FORMAT) {
         $format_graph = "png";
      } else if ($view->fields["format"] == self::PLUGIN_ARCHIRES_GIF_FORMAT) {
         $format_graph = "gif";
      }
      echo $format_graph;

      echo "</td></tr>";
      echo "</table>";
   }


   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      switch ($item->getType()) {
         case 'PluginArchiresApplianceQuery' :
            switch ($tabnum) {
               case '1' :
                  self::showView($item);
                  break;

               case '2' :
                  self::linkToAllViews($item);
                  break;
            }
            break;
      }
      return true;
   }


   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $LANG;

      if (!$withtemplate && plugin_archires_haveRight('archires', 'r')) {
         switch ($item->getType()) {
            case 'PluginArchiresApplianceQuery' :
               return array('1' => $LANG['plugin_archires']['setup'][20],
                            '2' => $LANG['plugin_archires']['title'][3]);
         }
      }
      return '';
   }
}
?>