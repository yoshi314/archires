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

class PluginArchiresNetworkEquipmentQuery extends CommonDBTM {

   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_archires']['title'][5];
   }


   function canCreate() {
      return plugin_archires_haveRight('archires', 'w');
   }


   function canView() {
      return plugin_archires_haveRight('archires', 'r');
   }


   function cleanDBonPurge($ID) {

      $querytype = new PluginArchiresQueryType;
      $querytype->clean($ID);
   }


  function getSearchOptions() {
      global $LANG;

      $tab = array();

      $tab['common'] = $LANG['plugin_archires']['title'][5];

      $tab[1]['table']     = $this->getTable();
      $tab[1]['field']     = 'name';
      $tab[1]['linkfield'] = 'name';
      $tab[1]['name']      = $LANG['plugin_archires']['search'][1];
      $tab[1]['datatype']  = 'itemlink';

      $tab[2]['table']     = 'glpi_networkequipments';
      $tab[2]['field']     = 'name';
      $tab[2]['linkfield'] = 'networkequipments_id';
      $tab[2]['name']      = $LANG['help'][26];

      $tab[3]['table']     = 'glpi_networks';
      $tab[3]['field']     = 'name';
      $tab[3]['linkfield'] = 'networks_id';
      $tab[3]['name']      = $LANG['plugin_archires']['search'][4];

      $tab[4]['table']     = 'glpi_states';
      $tab[4]['field']     = 'name';
      $tab[4]['linkfield'] = 'states_id';
      $tab[4]['name']      = $LANG['plugin_archires']['search'][5];

      $tab[5]['table']     = 'glpi_groups';
      $tab[5]['field']     = 'name';
      $tab[5]['linkfield'] = 'groups_id';
      $tab[5]['name']      = $LANG['common'][35];

      $tab[6]['table']     = 'glpi_vlans';
      $tab[6]['field']     = 'name';
      $tab[6]['linkfield'] = 'vlans_id';
      $tab[6]['name']      = $LANG['networking'][56];

      $tab[7]['table']     = 'glpi_plugin_archires_views';
      $tab[7]['field']     = 'name';
      $tab[7]['linkfield'] = 'views_id';
      $tab[7]['name']      = $LANG['plugin_archires']['setup'][20];

      $tab[30]['table'] = $this->getTable();
      $tab[30]['field'] = 'id';
      $tab[30]['name']  = $LANG['common'][2];

      $tab[80]['table']     = 'glpi_entities';
      $tab[80]['field']     = 'completename';
      $tab[80]['linkfield'] = 'entities_id';
      $tab[80]['name']      = $LANG['entity'][0];

      return $tab;
   }


   function defineTabs($ID,$withtemplate) {
      global $LANG;

      $ong[1] = $LANG['title'][26];
      if ($ID > 0) {
         $ong[2] = $LANG['plugin_archires']['test'][0];
         $ong[3] = $LANG['plugin_archires']['search'][6];
         if (haveRight("notes","r")) {
            $ong[10] = $LANG['title'][37];
         }
      }
      return $ong;
   }


   function showForm ($target,$ID,$withtemplate='') {
    global $CFG_GLPI,$DB,$LANG;

      if ($ID > 0) {
       $this->check($ID,'r');
      } else {
       // Create item
       $this->check(-1,'w');
       $this->getEmpty();
      }

      $this->showTabs($ID, $withtemplate);
      $this->showFormHeader($target,$ID,$withtemplate,2);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_archires']['search'][1]." : </td>";
      echo "<td>";
      autocompletionTextField($this,"name");
      echo "</td>";
      echo "<td>".$LANG['common'][35]." : </td><td>";
      Dropdown::show('Group', array('name'   => "groups_id",
                                    'value'  => $this->fields["groups_id"],
                                    'entity' => $this->fields["entities_id"]));
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['help'][26]." : </td><td>";
      Dropdown::show('NetworkEquipment', array('name'   => "networkequipments_id",
                                               'value'  => $this->fields["networkequipments_id"],
                                               'entity' => $this->fields["entities_id"]));
      echo "</td>";
      echo "<td>".$LANG['networking'][56]." : </td><td>";
      Dropdown::show('Vlan', array('name' => "vlans_id",
                                   'value' => $this->fields["vlans_id"]));
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_archires']['search'][4]." : </td><td>";
      Dropdown::show('Network', array('name'  => "networks_id",
                                      'value' => $this->fields["networks_id"]));
      echo "</td>";
      echo "<td>".$LANG['plugin_archires']['setup'][20]." : </td><td>";
      //View
      $PluginArchiresView = new PluginArchiresView();
      $PluginArchiresView->dropdownView($this,-1);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_archires']['search'][5]." : </td><td colspan='3'>";
      Dropdown::show('State', array('name'  => "states_id",
                                    'value' => $this->fields["states_id"]));
      echo "</td></tr>";

      $this->showFormButtons($ID,$withtemplate,2);
      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";

      return true;
   }


   function Query ($ID,$PluginArchiresView,$for) {
      global $DB,$CFG_GLPI,$LANG,$PLUGIN_ARCHIRES_TYPE_FIELD_TABLES;

      $this->getFromDB($ID);

      $types = array();
      $devices = array();
      $ports = array();

      if ($PluginArchiresView->fields["computer"]!=0) {
         $types[]='Computer';
      }
      if ($PluginArchiresView->fields["printer"]!=0) {
         $types[]='Printer';
      }
      if ($PluginArchiresView->fields["peripheral"]!=0) {
         $types[]='Peripheral';
      }
      if ($PluginArchiresView->fields["phone"]!=0) {
         $types[]='Phone';
      }
      if ($PluginArchiresView->fields["networking"]!=0) {
         $types[]='NetworkEquipment';
      }

      $query_switch = "SELECT `glpi_networkports`.`name` AS port,
                              `glpi_networkports`.`id` AS idport
                       FROM `glpi_networkequipments`
                       LEFT JOIN `glpi_networkports`
                           ON (`glpi_networkports`.`itemtype` = 'NetworkEquipment'
                               AND `glpi_networkports`.`items_id` = `glpi_networkequipments`.`id`)
                       WHERE `glpi_networkequipments`.`id` = '".$this->fields["networkequipments_id"]."'
                             AND `glpi_networkequipments`.`is_deleted` = '0'
                             AND `glpi_networkequipments`.`is_template` = '0'".
                             getEntitiesRestrictRequest("AND","glpi_networkequipments");

      if ($result_switch = $DB->query($query_switch)) {
         while ($ligne = $DB->fetch_array($result_switch)) {
            $port = $ligne['port'];
            $nw = new NetworkPort_NetworkPort();
            $end = $nw->getOppositeContact($ligne['idport']);

            if ($end) {
               foreach ($types as $key => $val) {
                  $itemtable = getTableForItemType($val);
                  $fieldsnp = "`np`.`id`, `np`.`items_id`, `np`.`logical_number`,
                               `np`.`networkinterfaces_id`,`np`.`ip`,`np`.`netmask`,
                               `np`.`name` AS namep";

                  $query = "SELECT `$itemtable`.`id` AS idc, $fieldsnp , `$itemtable`.`name`,
                                   `$itemtable`.`$PLUGIN_ARCHIRES_TYPE_FIELD_TABLES[$val]` AS `type`,
                                   `$itemtable`.`users_id`, `$itemtable`.`groups_id`,
                                   `$itemtable`.`contact`, `$itemtable`.`states_id`,
                                   `$itemtable`.`entities_id`,`$itemtable`.`locations_id`
                            FROM `glpi_networkports` np, `$itemtable`";

                  if ($this->fields["vlans_id"] > "0") {
                     $query .= ", `glpi_networkports_vlans` nv";
                  }
                  $query .= " WHERE `np`.`itemtype` = '$val'
                                    AND `np`.`items_id` = `$itemtable`.`id`
                                    AND `np`.`id` ='$end'
                                    AND `$itemtable`.`is_deleted` = '0'
                                    AND `$itemtable`.`is_template` = '0'".
                                    getEntitiesRestrictRequest("AND",$itemtable);

                  if ($this->fields["vlans_id"] > "0") {
                     $query .= " AND `nv`.`networkports_id` = `np`.`id`
                                 AND vlans_id= '".$this->fields["vlans_id"]."'";
                  }
                  if ($this->fields["networks_id"] > "0"
                      && $val != 'Phone'
                      && $val != 'Peripheral') {
                     $query .= " AND `$itemtable`.`networks_id` = '".$this->fields["networks_id"]."'";
                  }
                  if ($this->fields["states_id"] > "0") {
                     $query .= " AND `$itemtable`.`states_id` = '".$this->fields["states_id"]."'";
                  }
                  if ($this->fields["groups_id"] > "0") {
                     $query .= " AND `$itemtable`.`groups_id` = '".$this->fields["groups_id"]."'";
                  }

                  //types
                  $PluginArchiresQueryType = new PluginArchiresQueryType();
                  $query .= $PluginArchiresQueryType->queryTypeCheck($this->getType(),$ID,$val);
                  $query .= "ORDER BY `np`.`ip` ASC ";

                  if ($result = $DB->query($query)) {
                     while ($data = $DB->fetch_array($result)) {
                        if ($PluginArchiresView->fields["display_state"] != 0) {
                           $devices[$val][$data["items_id"]]["states_id"] = $data["states_id"];
                        }
                        $devices[$val][$data["items_id"]]["type"]         = $data["type"];
                        $devices[$val][$data["items_id"]]["name"]         = $data["name"];
                        $devices[$val][$data["items_id"]]["users_id"]     = $data["users_id"];
                        $devices[$val][$data["items_id"]]["groups_id"]    = $data["groups_id"];
                        $devices[$val][$data["items_id"]]["contact"]      = $data["contact"];
                        $devices[$val][$data["items_id"]]["entity"]       = $data["entities_id"];
                        $devices[$val][$data["items_id"]]["locations_id"] = $data["locations_id"];

                        $ports[$data["id"]]["items_id"]             = $data["items_id"];
                        $ports[$data["id"]]["logical_number"]       = $data["logical_number"];
                        $ports[$data["id"]]["networkinterfaces_id"] = $data["networkinterfaces_id"];
                        $ports[$data["id"]]["ip"]                   = $data["ip"];
                        $ports[$data["id"]]["netmask"]              = $data["netmask"];
                        $ports[$data["id"]]["namep"]                = $data["namep"];
                        $ports[$data["id"]]["idp"]                  = $data["id"];
                        $ports[$data["id"]]["itemtype"]             = $val;

                        //ip
                        if ($data["ip"]) {
                           if (!empty($devices[$val][$data["items_id"]]["ip"])) {
                              $devices[$val][$data["items_id"]]["ip"] .= " - ";
                              $devices[$val][$data["items_id"]]["ip"] .= $data["ip"];
                           } else {
                              $devices[$val][$data["items_id"]]["ip"] = $data["ip"];
                           }
                        }
                        //fin ip
                     }
                  }
               }
            }
         }
      }
      //The networking
      $query = "SELECT `n`.`id` AS `idn`, `np`.`id`, `np`.`items_id`, `np`.`logical_number`,
                       `np`.`networkinterfaces_id` ,`np`.`ip`, `np`.`name` AS `namep`,
                       `n`.`ip` AS `nip`,`np`.`netmask`, `n`.`name`,
                       `n`.`networkequipmenttypes_id` AS `type`, `n`.`users_id`, `n`.`groups_id`,
                       `n`.`contact`, `n`.`states_id`, `n`.`entities_id`,`n`.`locations_id`
                FROM `glpi_networkports` `np`, `glpi_networkequipments` `n` ";

      if ($this->fields["vlans_id"] > "0") {
         $query .= ", `glpi_networkports_vlans` nv
                    WHERE `np`.`itemtype` = 'NetworkEquipment'
                          AND `np`.`items_id` = `n`.`id`
                          AND `n`.`id` = '".$this->fields["networkequipments_id"]."'
                          AND `n`.`is_deleted` = '0'
                          AND `n`.`is_template` = '0'";
      }
      $query .= "ORDER BY `np`.`ip` ASC ";

      if ($result = $DB->query($query)) {
         while ($data = $DB->fetch_array($result)) {
            if ($PluginArchiresView->fields["display_state"] != 0) {
               $devices['NetworkEquipment'][$data["items_id"]]["states_id"] = $data["states_id"];
            }
            $devices['NetworkEquipment'][$data["items_id"]]["name"]         = $data["name"];
            $devices['NetworkEquipment'][$data["items_id"]]["type"]         = $data["type"];
            $devices['NetworkEquipment'][$data["items_id"]]["users_id"]     = $data["users_id"];
            $devices['NetworkEquipment'][$data["items_id"]]["groups_id"]    = $data["groups_id"];
            $devices['NetworkEquipment'][$data["items_id"]]["contact"]      = $data["contact"];
            $devices['NetworkEquipment'][$data["items_id"]]["ip"]           = $data["nip"];
            $devices['NetworkEquipment'][$data["items_id"]]["entity"]       = $data["entities_id"];
            $devices['NetworkEquipment'][$data["items_id"]]["locations_id"] = $data["locations_id"];
            $ports[$data["id"]]["items_id"]             = $data["items_id"];
            $ports[$data["id"]]["logical_number"]       = $data["logical_number"];
            $ports[$data["id"]]["networkinterfaces_id"] = $data["networkinterfaces_id"];
            $ports[$data["id"]]["ip"]                   = $data["ip"];
            $ports[$data["id"]]["netmask"]              = $data["netmask"];
            $ports[$data["id"]]["namep"]                = $data["namep"];
            $ports[$data["id"]]["idp"]                  = $data["id"];
            $ports[$data["id"]]["itemtype"]             = 'NetworkEquipment';
         }
      }

      if ($for) {
         return $devices;
      } else {
         return $ports;
      }
   }
}

?>