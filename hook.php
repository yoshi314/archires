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

function plugin_archires_install() {
   global $DB;

   include_once (GLPI_ROOT."/plugins/archires/inc/profile.class.php");
   $update = false;

   if (!TableExists("glpi_plugin_archires_config") && !TableExists("glpi_plugin_archires_views")) {
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/empty-1.8.0.sql");

   } else if (TableExists("glpi_plugin_archires_display")
              && !FieldExists("glpi_plugin_archires_display","display_ports")) {

      $update = true;
      plugin_archires_updatev13();
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.4.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.5.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.0.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.2.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.8.0.sql");

   } else if (TableExists("glpi_plugin_archires_display")
              && !TableExists("glpi_plugin_archires_profiles")) {

      $update = true;
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.4.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.5.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.0.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.2.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.8.0.sql");

   } else if (TableExists("glpi_plugin_archires_display")
              && !TableExists("glpi_plugin_archires_image_device")) {

      $update = true;
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.5.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.0.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.2.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.8.0.sql");

   } else if (TableExists("glpi_plugin_archires_profiles")
              && FieldExists("glpi_plugin_archires_profiles","interface")) {

      $update = true;
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.0.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.2.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.8.0.sql");

   } else if (TableExists("glpi_plugin_archires_config")
              && FieldExists("glpi_plugin_archires_config","system")) {

      $update = true;
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.2.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.8.0.sql");

   } else if (!TableExists("glpi_plugin_archires_views")) {
      $update = true;
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.8.0.sql");
   }
   
   if ($update) {
      $query_="SELECT *
            FROM `glpi_plugin_archires_profiles` ";
      $result_=$DB->query($query_);
      if ($DB->numrows($result_)>0) {

         while ($data=$DB->fetch_array($result_)) {
            $query="UPDATE `glpi_plugin_archires_profiles`
                  SET `profiles_id` = '".$data["id"]."'
                  WHERE `id` = '".$data["id"]."';";
            $result=$DB->query($query);

         }
      }
      
      $query="ALTER TABLE `glpi_plugin_archires_profiles`
               DROP `name` ;";
      $result=$DB->query($query);
  
      Plugin::migrateItemType(array(3000 => 'PluginArchiresLocationQuery',
                                    3001 => 'PluginArchiresNetworkEquipmentQuery',
                                    3002 => 'PluginArchiresApplianceQuery',
                                    3003 => 'PluginArchiresView'),
                              array("glpi_bookmarks", "glpi_bookmarks_users",
                                    "glpi_displaypreferences", "glpi_documents_items",
                                    "glpi_infocoms", "glpi_logs", "glpi_tickets"),
                              array("glpi_plugin_archires_querytypes",
                                    "glpi_plugin_archires_imageitems"));
   }
   PluginArchiresProfile::createFirstAccess($_SESSION['glpiactiveprofile']['id']);
   return true;
}


function plugin_archires_uninstall() {
   global $DB;

   $tables = array("glpi_plugin_archires_imageitems",
                   "glpi_plugin_archires_views",
                   "glpi_plugin_archires_networkinterfacecolors",
                   "glpi_plugin_archires_vlancolors",
                   "glpi_plugin_archires_statecolors",
                   "glpi_plugin_archires_profiles",
                   "glpi_plugin_archires_locationqueries",
                   "glpi_plugin_archires_networkequipmentqueries",
                   "glpi_plugin_archires_appliancequeries",
                   "glpi_plugin_archires_querytypes");

   foreach($tables as $table) {
      $DB->query("DROP TABLE IF EXISTS `$table`;");
   }
   //old versions
   $tables = array("glpi_plugin_archires_query_location",
                   "glpi_plugin_archires_query_switch",
                   "glpi_plugin_archires_query_applicatifs",
                   "glpi_plugin_archires_image_device",
                   "glpi_plugin_archires_query_type",
                   "glpi_plugin_archires_color_iface",
                   "glpi_plugin_archires_color_state",
                   "glpi_plugin_archires_config",
                   "glpi_plugin_archires_color_vlan");

   foreach($tables as $table) {
      $DB->query("DROP TABLE IF EXISTS `$table`;");
   }

   $rep_files_archires = GLPI_PLUGIN_DOC_DIR."/archires";

   deleteDir($rep_files_archires);

   $tables_glpi = array("glpi_displaypreferences",
                        "glpi_documents_items",
                        "glpi_bookmarks",
                        "glpi_logs");

   foreach($tables_glpi as $table_glpi) {
      $DB->query("DELETE
                  FROM `$table_glpi`
                  WHERE `itemtype` IN ('PluginArchiresLocationQuery',
                                       'PluginArchiresNetworkEquipmentQuery',
                                       'PluginArchiresApplianceQuery','PluginArchiresView'");
   }
//                  WHERE `itemtype` = 'PluginArchiresLocationQuery' OR `itemtype` = 'PluginArchiresNetworkEquipmentQuery' OR `itemtype` = 'PluginArchiresApplianceQuery' OR `itemtype` = 'PluginArchiresView';");
   return true;
}


// Define dropdown relations
function plugin_archires_getDatabaseRelations() {

   $plugin = new Plugin();
   if ($plugin->isActivated("archires")) {
      return array("glpi_locations" => array("glpi_plugin_archires_locationqueries"  => "locations_id"),
                     "glpi_networks" => array("glpi_plugin_archires_locationqueries"  => "networks_id",
                                             "glpi_plugin_archires_appliancequeries"  => "networks_id",
                                             "glpi_plugin_archires_networkequipmentqueries"  => "networks_id"),
                     "glpi_states" => array("glpi_plugin_archires_locationqueries"  => "states_id",
                                             "glpi_plugin_archires_appliancequeries"  => "states_id",
                                             "glpi_plugin_archires_networkequipmentqueries"  => "states_id",
                                             "glpi_plugin_archires_statecolors"  => "states_id"),
                     "glpi_groups" => array("glpi_plugin_archires_locationqueries"  => "groups_id",
                                             "glpi_plugin_archires_appliancequeries"  => "groups_id",
                                             "glpi_plugin_archires_networkequipmentqueries"  => "groups_id"),
                     "glpi_vlans" => array("glpi_plugin_archires_locationqueries"  => "vlans_id",
                                             "glpi_plugin_archires_appliancequeries"  => "vlans_id",
                                             "glpi_plugin_archires_networkequipmentqueries"  => "vlans_id",
                                             "glpi_plugin_archires_vlancolors"  => "vlans_id"),
                     "glpi_entities" => array("glpi_plugin_archires_locationqueries"  => "entities_id",
                                                "glpi_plugin_archires_networkequipmentqueries"=> "entities_id",
                                                "glpi_plugin_archires_appliancequeries" => "entities_id",
                                                "glpi_plugin_archires_views" => "entities_id"),
                     "glpi_plugin_archires_views" => array("glpi_plugin_archires_locationqueries"  => "plugin_archires_views_id",
                                                            "glpi_plugin_archires_networkequipmentqueries"=> "plugin_archires_views_id",
                                                            "glpi_plugin_archires_appliancequeries" => "plugin_archires_views_id"),
                     "glpi_plugin_appliances_appliances" => array("glpi_plugin_archires_appliancequeries" => "appliances_id"),
                     "glpi_profiles" => array("glpi_plugin_addressing_profiles" => "profiles_id"),
                     "glpi_networkinterfaces" => array("glpi_plugin_archires_networkinterfacecolors" => "networkinterfaces_id"));
   } else {
      return array();
   }
}


////// SEARCH FUNCTIONS ///////() {

function plugin_archires_giveItem($type,$ID,$data,$num) {
   global $LANG;

   $searchopt = &Search::getOptions($type);

   $table = $searchopt[$ID]["table"];
   $field = $searchopt[$ID]["field"];

   switch ($table.'.'.$field) {
      case "glpi_locations.completename" :
         if (empty($data["ITEM_$num"])) {
            $out = $LANG['plugin_archires'][30];
         } else {
            $out = $data["ITEM_$num"];
         }
         return $out;

      case "glpi_networks.name" :
      case "glpi_states.name" :
      case "glpi_vlans.name" :
         if (empty($data["ITEM_$num"])) {
            $out = $LANG['plugin_archires'][11];
         } else {
            $out = $data["ITEM_$num"];
         }
         return $out;

      case "glpi_networkequipments.name" :
      case "glpi_plugin_appliances_appliances.name" :
         if (empty($data["ITEM_$num"])) {
            $out = $LANG['plugin_archires'][34];
         } else {
            $out = $data["ITEM_$num"];
         }
         return $out;

      case "glpi_plugin_archires_views.display_ports" :
         if (empty($data["ITEM_$num"])) {
            $out = $LANG['choice'][0];
         } else if ($data["ITEM_$num"]=='1') {
            $out = $LANG['plugin_archires'][29];
         } else if ($data["ITEM_$num"]=='2') {
            $out = $LANG['plugin_archires'][33];
         }
         return $out;

      case "glpi_plugin_archires_views.engine" :
         if (empty($data["ITEM_$num"])) {
            $out = "Dot";
         } else if ($data["ITEM_$num"]=='1') {
            $out = "Neato";
         }
         return $out;

      case "glpi_plugin_archires_views.format" :
         if ($data["ITEM_$num"]==PLUGIN_ARCHIRES_JPEG_FORMAT) {
            $out = "jpeg";
         } else if ($data["ITEM_$num"]==PLUGIN_ARCHIRES_PNG_FORMAT) {
            $out = "png";
         } else if ($data["ITEM_$num"]==PLUGIN_ARCHIRES_GIF_FORMAT) {
            $out = "gif";
         }
         return $out;

      case "glpi_plugin_archires_views.color" :
         if (empty($data["ITEM_$num"])) {
            $out = $LANG['plugin_archires'][19];
         } else if ($data["ITEM_$num"]=='1') {
            $out = $LANG['plugin_archires'][35];
         }
         return $out;
   }
   return "";
}


// Hook done on delete item case

function plugin_pre_item_purge_archires($item) {

   switch (get_class($item)) {
      case 'Profile' :
         // Manipulate data if needed
         $PluginArchiresProfile=new PluginArchiresProfile;
         $PluginArchiresProfile->cleanProfiles($item->getField('id'));
         break;
   }
}


////// SPECIFIC MODIF MASSIVE FUNCTIONS ///////

function plugin_archires_MassiveActions($type) {
   global $LANG;

   // Specific one
   switch ($type) {
      case 'PluginArchiresLocationQuery' :
      case 'PluginArchiresNetworkEquipmentQuery' :
      case 'PluginArchiresApplianceQuery' :
      case 'PluginArchiresView' :
         return array("plugin_archires_duplicate" => $LANG['plugin_archires'][28],
                      "plugin_archires_transfert" => $LANG['buttons'][48]);
   }
   return array();
}


// How to display specific actions ?
function plugin_archires_MassiveActionsDisplay($options=array()) {
   global $LANG;

   switch ($options['itemtype']) {
      case 'PluginArchiresLocationQuery':
      case 'PluginArchiresNetworkEquipmentQuery' :
      case 'PluginArchiresApplianceQuery' :
      case 'PluginArchiresView':
         switch ($options['action']) {
            // No case for add_document : use GLPI core one
            case "plugin_archires_duplicate" :
            case "plugin_archires_transfert" :
               Dropdown::show('Entity');
               echo "&nbsp;<input type='submit' name='massiveaction' class='submit' value='".
                     $LANG['buttons'][2]."'>";
               break;
         }
         break;
   }
   return "";
}


// How to process specific actions ?
function plugin_archires_MassiveActionsProcess($data) {
   global $DB;

   switch ($data['action']) {
      case 'plugin_archires_duplicate' :
         if ($data['itemtype'] == 'PluginArchiresLocationQuery'
             || $data['itemtype']=='PluginArchiresNetworkEquipmentQuery'
             || $data['itemtype']=='PluginArchiresApplianceQuery'
             || $data['itemtype']=='PluginArchiresView') {

            $item = new $data['itemtype']();
            foreach ($data['item'] as $key => $val) {
               if ($val == 1 && $item->getFromDB($key)) {
                  unset($item->fields["id"]);
                  $item->fields["entities_id"]=$data["entities_id"];
                  if ($item->can(-1,'w',$item->fields)) {
                     $item->add($item->fields);
                  }
               }
            }
         }
         break;

      case 'plugin_archires_transfert' :
         if ($data['itemtype']=='PluginArchiresLocationQuery'
             || $data['itemtype']=='PluginArchiresNetworkEquipmentQuery'
             || $data['itemtype']=='PluginArchiresApplianceQuery'
             || $data['itemtype']=='PluginArchiresView') {

            $item = new $data['itemtype']();
            foreach ($data["item"] as $key => $val) {
               if ($val==1) {

                  $query="UPDATE `".$item->getTable()."`
                        SET `entities_id` = '".$data['entities_id']."'
                        WHERE `id` = '$key'";
                  $DB->query($query);
               }
            }
         }
         break;
   }
}


// Define headings added by the plugin
function plugin_get_headings_archires($item,$withtemplate) {
   global $LANG;

   if (get_class($item) == 'Profile') {
      if ($item->getField('id') && $item->getField('interface')!='helpdesk') {
         return array(1 => $LANG['plugin_archires']['title'][0]);
      }

   } else if (get_class($item) == 'Config') {
      return array(1 => $LANG['plugin_archires']['title'][0]);
   }
   return false;
}


// Define headings actions added by the plugin
function plugin_headings_actions_archires($item) {

   if (in_array(get_class($item),array('Profile','Config')) && $item->getField('interface')!='helpdesk') {
      return array(1 => "plugin_headings_archires");
   }
   return false;
}


// action heading
function plugin_headings_archires($item,$withtemplate=0) {
   global $CFG_GLPI;

   $type = get_Class($item);
   $ID = $item->getField('id');
   switch ($type) {
      case 'Profile' :
         $ArchiresProfile = new PluginArchiresProfile();
         if ($ArchiresProfile->getFromDBByProfile($ID) || $ArchiresProfile->createAccess($item)) {
            $ArchiresProfile->showForm($item->getField('id'),
                                       array('target' => $CFG_GLPI["root_doc"]."/plugins/archires/front/profile.form.php"));
         }
         break;
   }

}

?>