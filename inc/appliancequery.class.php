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

class PluginArchiresApplianceQuery extends CommonDBTM {

   static $rightname             = "plugin_archires";
   protected $usenotepad         = true;


   static function getTypeName($nb=0) {
      return PluginAppliancesAppliance::getTypeName($nb);
   }


   function cleanDBonPurge() {

      $querytype = new PluginArchiresQueryType();
      $querytype->deleteByCriteria(['plugin_archires_queries_id' => $this->fields['id']]);
   }


   function getSearchOptionsNew() {

      $tab = [];

      $tab[] = ['id'             => 'common',
                'name'           => self::getTypeName(2)];

      $tab[] = ['id'             => '1',
                'table'          => $this->getTable(),
                'field'          =>'name',
                'name'           => __('Name'),
                'datatype'       => 'itemlink',
                'itemlink_type'  => $this->getType()];

      $tab[] = ['id'             => '2',
                'table'          => 'glpi_plugin_appliances_appliances',
                'field'          => 'name',
                'name'           => PluginAppliancesAppliance::getTypeName(1),
                'datatype'       => 'dropdown'];

      $tab[] = ['id'             => '3',
                'table'          => 'glpi_networks',
                'field'          => 'name',
                'name'           => _n('Network', 'Networks', 2),
                'datatype'       => 'dropdown'];

      $tab[] = ['id'             => '4',
                'table'          => 'glpi_states',
                'field'          => 'name',
                'name'           => __('State'),
                'datatype'       => 'dropdown'];

      $tab[] = ['id'             => '5',
                'table'          => 'glpi_groups',
                'field'          => 'completename',
                'name'           => __('Group'),
                'datatype'       => 'dropdown'];

      $tab[] = ['id'             => '6',
                'table'          => 'glpi_vlans',
                'field'          => 'name',
                'name'           => __('VLAN'),
                'datatype'       => 'dropdown'];

      $tab[] = ['id'             => '7',
               'table'           => 'glpi_plugin_archires_views',
               'field'           => 'name',
               'name'            => PluginArchiresView::getTypeName(1),
               'datatype'        => 'dropdown'];

      $tab[] = ['id'             => '30',
               'table'           => $this->getTable(),
               'field'           => 'id',
               'name'            => __('ID'),
               'datatype'        => 'number'];

      $tab[] = ['id'             => '80',
               'table'           => 'glpi_entities',
               'field'           => 'completename',
               'name'            => __('Entity'),
               'datatype'        => 'dropdown'];

   return $tab;
   }


   function prepareInputForAdd($input) {

      if (!isset ($input["plugin_archires_views_id"])
          || $input["plugin_archires_views_id"] == 0) {
         Session::addMessageAfterRedirect(__('Thanks to specify a default used view', 'archires'),
                                          false, ERROR);
         return [];
      }
      return $input;
   }


   function defineTabs($options=[]) {

      $ong = [];
      $this->addDefaultFormTab($ong)
         ->addStandardTab('PluginArchiresQueryType', $ong, $options)
         ->addStandardTab('PluginArchiresView', $ong, $options)
         ->addStandardTab('PluginArchiresPrototype', $ong, $options)
         ->addStandardTab('Notepad',$ong, $options);

      return $ong;
   }


   function showForm ($ID, $options=[]) {

      $this->initForm($ID, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Name')."</td>";
      echo "<td>";
      Html::autocompletionTextField($this,"name");
      echo "</td>";
      echo "<td>".__('Group')."</td><td>";
      Group::dropdown(['name'   => "groups_id",
                       'value'  => $this->fields["groups_id"],
                       'entity' => $this->fields["entities_id"]]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".PluginAppliancesAppliance::getTypeName(1)."</td><td>";
      Dropdown::show('PluginAppliancesAppliance',
                     ['name'   => "appliances_id",
                      'value'  => $this->fields["plugin_appliances_appliances_id"],
                      'entity' => $this->fields["entities_id"]]);
      echo "</td>";
      echo "<td>".__('VLAN')."</td><td>";
      Vlan::dropdown(['name'  => "vlans_id",
                      'value' => $this->fields["vlans_id"]]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'><td>".__('Network')."</td><td>";
      Network::dropdown(['name'  => "networks_id",
                         'value' => $this->fields["networks_id"]]);
      echo "</td>";
      echo "<td>".PluginArchiresView::getTypeName(1)."</td><td>";
      //View
      Dropdown::show('PluginArchiresView',
                     ['name'  => "plugin_archires_views_id",
                      'value' => $this->fields["plugin_archires_views_id"]]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'><td>".__('State')."</td><td colspan='3'>";
      State::dropdown(['name' => "states_id"]);
      echo "</td></tr>";

      $this->showFormButtons($options);

      return true;
   }


   function Query ($ID,$PluginArchiresView,$for) {
      global $DB;

      $this->getFromDB($ID);

      $types   = [];
      $devices = [];
      $ports   = [];

      if ($PluginArchiresView->fields["computer"] != 0) {
         $types[] = 'Computer';
      }
      if ($PluginArchiresView->fields["printer"] != 0) {
         $types[] = 'Printer';
      }
      if ($PluginArchiresView->fields["peripheral"] != 0) {
         $types[] = 'Peripheral';
      }
      if ($PluginArchiresView->fields["phone"] != 0) {
         $types[] = 'Phone';
      }
      if ($PluginArchiresView->fields["networking"] != 0) {
         $types[] = 'NetworkEquipment';
      }
      foreach ($types as $key => $val) {
         $fieldsnp = "`np`.`id`, `np`.`items_id`, `np`.`logical_number`, `np`.`instantiation_type`,
                      `glpi_ipaddresses`.`name` AS ip, `ipn`.`netmask`, `np`.`name` AS namep";

         $itemtable = getTableForItemType($val);
         $query = "SELECT `$itemtable`.`id` AS idc, $fieldsnp , `$itemtable`.`name`,
                          `$itemtable`.`".getForeignKeyFieldForTable(getTableForItemType($val."Type"))."`
                              AS `type`,
                          `$itemtable`.`users_id`, `$itemtable`.`groups_id`, `$itemtable`.`contact`,
                          `$itemtable`.`states_id`, `$itemtable`.`entities_id`,
                          `$itemtable`.`locations_id`
                   FROM `glpi_networkports` np,
                        `$itemtable`,
                        `glpi_ipnetworks` AS ipn,
                        `glpi_plugin_appliances_appliances_items` app";

         if ($this->fields["vlans_id"] > "0") {
            $query .= ", `glpi_networkports_vlans` nv";
         }

         $query .= " LEFT JOIN `glpi_networknames`
                        ON (`glpi_networknames`.`itemtype` = 'NetworkPort')
                     LEFT JOIN `glpi_ipaddresses`
                        ON (`glpi_ipaddresses`.`itemtype` = 'NetworkName'
                            AND `glpi_networknames`.`id` = `glpi_ipaddresses`.`items_id`)
                     WHERE `np`.`instantiation_type` = 'NetworkPortEthernet'
                           AND `np`.`itemtype` = '$val'
                           AND `np`.`items_id` = `$itemtable`.`id`
                           AND `app`.`items_id` = `$itemtable`.`id`
                           AND `$itemtable`.`is_deleted` = '0'
                           AND `$itemtable`.`is_template` = '0'
                           AND `np`.`id` = `glpi_networknames`.`items_id`".
                           getEntitiesRestrictRequest(" AND",$itemtable);

         if ($this->fields["vlans_id"] > "0") {
            $query .= " AND `nv`.`networkports_id` = `np`.`id`
                        AND `vlans_id` = '".$this->fields["vlans_id"]."'";
         }
         if (($this->fields["networks_id"] > "0")
             && ($val != 'Phone')
             && ($val != 'Peripheral')) {
            $query .= " AND `$itemtable`.`networks_id` = '".$this->fields["networks_id"]."'";
         }
         if ($this->fields["states_id"] > "0") {
            $query .= " AND `$itemtable`.`states_id` = '".$this->fields["states_id"]."'";
         }
         if ($this->fields["groups_id"] > "0") {
            $query .= " AND `$itemtable`.`groups_id` = '".$this->fields["groups_id"]."'";
         }

         $query .= " AND `app`.`plugin_appliances_appliances_id` = '".$this->fields["plugin_appliances_appliances_id"]."'
                     AND `app`.`itemtype` = '$val' ";

         $PluginArchiresQueryType = new PluginArchiresQueryType();
         $query .= $PluginArchiresQueryType->queryTypeCheck($this->getType(), $ID, $val);

         $query .= "ORDER BY `glpi_ipaddresses`.`name` ASC ";

         if ($result = $DB->request($query)) {
            while ($data = $result->next()) {

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

               if ($data["ip"]) {
                  if (!empty($devices[$val][$data["items_id"]]["ip"])) {
                     $devices[$val][$data["items_id"]]["ip"]  .= " - ";
                     $devices[$val][$data["items_id"]]["ip"]  .= $data["ip"];
                  } else {
                     $devices[$val][$data["items_id"]]["ip"]  = $data["ip"];
                  }
               }

               $ports[$data["id"]]["items_id"]             = $data["items_id"];
               $ports[$data["id"]]["logical_number"]       = $data["logical_number"];
               $ports[$data["id"]]["ip"]                   = $data["ip"];
               $ports[$data["id"]]["netmask"]              = $data["netmask"];
               $ports[$data["id"]]["namep"]                = $data["namep"];
               $ports[$data["id"]]["idp"]                  = $data["id"];
               $ports[$data["id"]]["itemtype"]             = $val;
            }
         }
      }
      if ($for) {
         return $devices;
      }
      return $ports;
   }

}
