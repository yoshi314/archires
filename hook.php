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
   $update=false;
   
   if (!TableExists("glpi_plugin_archires_config") && !TableExists("glpi_plugin_archires_views")) {

      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/empty-1.8.0.sql");

   } else if (TableExists("glpi_plugin_archires_display") &&  !FieldExists("glpi_plugin_archires_display","display_ports")) {
      
      $update=true;
      plugin_archires_updatev13();
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.4.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.5.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.0.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.2.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.8.0.sql");

   } else if (TableExists("glpi_plugin_archires_display") && !TableExists("glpi_plugin_archires_profiles")) {
      
      $update=true;
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.4.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.5.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.0.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.2.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.8.0.sql");

   } else if (TableExists("glpi_plugin_archires_display") && !TableExists("glpi_plugin_archires_image_device")) {
      
      $update=true;
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.5.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.0.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.2.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.8.0.sql");

   } else if (TableExists("glpi_plugin_archires_profiles") && FieldExists("glpi_plugin_archires_profiles","interface")) {
      
      $update=true;
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.0.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.2.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.8.0.sql");

   } else if (TableExists("glpi_plugin_archires_config") && FieldExists("glpi_plugin_archires_config","system")) {
      
      $update=true;
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.2.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.8.0.sql");

   } else if (!TableExists("glpi_plugin_archires_views")) {
      
      $update=true;
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.8.0.sql");

   }
   
   if ($update) {
      Plugin::migrateItemType(
         array(3000=>'PluginArchiresLocationQuery',3001=>'PluginArchiresNetworkEquipmentQuery',3002=>'PluginArchiresApplianceQuery',3003=>'PluginArchiresView'),
         array("glpi_bookmarks", "glpi_bookmarks_users", "glpi_displaypreferences",
               "glpi_documents_items", "glpi_infocoms", "glpi_logs", "glpi_tickets"),
         array("glpi_plugin_archires_querytypes","glpi_plugin_archires_imageitems"));
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

	foreach($tables as $table)
		$DB->query("DROP TABLE IF EXISTS `$table`;");
   
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

	foreach($tables as $table)
		$DB->query("DROP TABLE IF EXISTS `$table`;");
		
	$rep_files_archires = GLPI_PLUGIN_DOC_DIR."/archires";

	deleteDir($rep_files_archires);
  
   $tables_glpi = array("glpi_displaypreferences",
					"glpi_documents_items",
					"glpi_bookmarks",
					"glpi_logs");

	foreach($tables_glpi as $table_glpi)
		$DB->query("DELETE FROM `$table_glpi` WHERE `itemtype` = 'PluginArchiresLocationQuery' OR `itemtype` = 'PluginArchiresNetworkEquipmentQuery' OR `itemtype` = 'PluginArchiresApplianceQuery' OR `itemtype` = 'PluginArchiresView';");

	return true;
}

// Define dropdown relations
function plugin_archires_getDatabaseRelations() {

	$plugin = new Plugin();
	if ($plugin->isActivated("archires"))

		return array(
		"glpi_entities"=>array("glpi_plugin_archires_locationqueries"=>"entities_id",
								"glpi_plugin_archires_networkequipmentqueries"=>"entities_id",
								"glpi_plugin_archires_appliancequeries"=>"entities_id",
								"glpi_plugin_archires_views"=>"entities_id"));
	else
		return array();

}

////// SEARCH FUNCTIONS ///////() {

function plugin_archires_giveItem($type,$ID,$data,$num) {
	global $LANG;
  
   $searchopt=&Search::getOptions($type);
  
	$table=$searchopt[$ID]["table"];
	$field=$searchopt[$ID]["field"];

	switch ($table.'.'.$field) {
      case "glpi_locations.completename" :
         if (empty($data["ITEM_$num"]))
            $out=$LANG['plugin_archires'][30];
         else
            $out= $data["ITEM_$num"];
         return $out;
         break;
      case "glpi_networks.name" :
         if (empty($data["ITEM_$num"]))
            $out=$LANG['plugin_archires'][11];
         else
            $out= $data["ITEM_$num"];
         return $out;
         break;
      case "glpi_states.name" :
         if (empty($data["ITEM_$num"]))
            $out=$LANG['plugin_archires'][11];
         else
            $out= $data["ITEM_$num"];
         return $out;
         break;
      case "glpi_vlans.name" :
         if (empty($data["ITEM_$num"]))
            $out=$LANG['plugin_archires'][11];
         else
            $out= $data["ITEM_$num"];
         return $out;
         break;
      case "glpi_networkequipments.name" :
         if (empty($data["ITEM_$num"]))
            $out=$LANG['plugin_archires'][34];
         else
            $out= $data["ITEM_$num"];
         return $out;
         break;
      case "glpi_plugin_appliances_appliances.name" :
         if (empty($data["ITEM_$num"]))
            $out=$LANG['plugin_archires'][34];
         else
            $out= $data["ITEM_$num"];
         return $out;
         break;
      case "glpi_plugin_archires_views.display_ports" :
         if (empty($data["ITEM_$num"]))
            $out=$LANG['choice'][0];
         elseif ($data["ITEM_$num"]=='1')
            $out= $LANG['plugin_archires'][29];
         elseif ($data["ITEM_$num"]=='2')
            $out= $LANG['plugin_archires'][33];
         return $out;
         break;
      case "glpi_plugin_archires_views.engine" :
         if (empty($data["ITEM_$num"]))
            $out="Dot";
         elseif ($data["ITEM_$num"]=='1')
            $out= "Neato";
         return $out;
         break;
      case "glpi_plugin_archires_views.format" :
         if ($data["ITEM_$num"]==PLUGIN_ARCHIRES_JPEG_FORMAT)
            $out="jpeg";
         elseif ($data["ITEM_$num"]==PLUGIN_ARCHIRES_PNG_FORMAT)
            $out= "png";
         elseif ($data["ITEM_$num"]==PLUGIN_ARCHIRES_GIF_FORMAT)
            $out= "gif";
         return $out;
         break;
      case "glpi_plugin_archires_views.color" :
         if (empty($data["ITEM_$num"]))
            $out=$LANG['plugin_archires'][19];
         elseif ($data["ITEM_$num"]=='1')
            $out= $LANG['plugin_archires'][35];
         return $out;
         break;
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
	
	switch ($type) {
		case 'PluginArchiresLocationQuery':
			return array(
				// Specific one
				"plugin_archires_duplicate"=>$LANG['plugin_archires'][28],
				"plugin_archires_transfert"=>$LANG['buttons'][48],
				);
         break;

		case 'PluginArchiresNetworkEquipmentQuery':
			return array(
				// Specific one
				"plugin_archires_duplicate"=>$LANG['plugin_archires'][28],
				"plugin_archires_transfert"=>$LANG['buttons'][48],
				);
         break;

		case 'PluginArchiresApplianceQuery':
			return array(
				// Specific one
				"plugin_archires_duplicate"=>$LANG['plugin_archires'][28],
				"plugin_archires_transfert"=>$LANG['buttons'][48],
				);
         break;    
      case 'PluginArchiresView':
			return array(
				// Specific one
				"plugin_archires_duplicate"=>$LANG['plugin_archires'][28],
				"plugin_archires_transfert"=>$LANG['buttons'][48],
				);
         break;
	}
	return array();
}

// How to display specific actions ?
function plugin_archires_MassiveActionsDisplay($type,$action) {
	global $LANG;
	
	switch ($type) {
		case 'PluginArchiresLocationQuery':
			switch ($action) {
				// No case for add_document : use GLPI core one
				case "plugin_archires_duplicate":
					Dropdown::show('Entity');
					echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
				case "plugin_archires_transfert":
					Dropdown::show('Entity');
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
			}
         break;
		case 'PluginArchiresNetworkEquipmentQuery':
			switch ($action) {
				// No case for add_document : use GLPI core one
				case "plugin_archires_duplicate":
					Dropdown::show('Entity');
					echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
				case "plugin_archires_transfert":
					Dropdown::show('Entity');
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
			}
         break;
		case 'PluginArchiresApplianceQuery':
			switch ($action) {
				// No case for add_document : use GLPI core one
				case "plugin_archires_duplicate":
					Dropdown::show('Entity');
					echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
				case "plugin_archires_transfert":
					Dropdown::show('Entity');
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
			}
         break;
      case 'PluginArchiresView':
			switch ($action) {
				// No case for add_document : use GLPI core one
				case "plugin_archires_duplicate":
					Dropdown::show('Entity');
					echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
				case "plugin_archires_transfert":
					Dropdown::show('Entity');
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
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
		case 'plugin_archires_duplicate':
			if ($data['itemtype']=='PluginArchiresLocationQuery') {

				$PluginArchiresLocationQuery=new PluginArchiresLocationQuery();
				foreach ($data['item'] as $key => $val) {
					if ($val==1) {
						if ($PluginArchiresLocationQuery->getFromDB($key)) {
							unset($PluginArchiresLocationQuery->fields["id"]);
							$PluginArchiresLocationQuery->fields["entities_id"]=$data["entities_id"];
							$newID=$PluginArchiresLocationQuery->add($PluginArchiresLocationQuery->fields);
						}
					}
				}
			} else if ($data['itemtype']=='PluginArchiresNetworkEquipmentQuery') {
				$PluginArchiresNetworkEquipmentQuery=new PluginArchiresNetworkEquipmentQuery();
				foreach ($data['item'] as $key => $val) {
					if ($val==1) {
						if ($PluginArchiresNetworkEquipmentQuery->getFromDB($key)) {
							unset($PluginArchiresNetworkEquipmentQuery->fields["id"]);
							$PluginArchiresNetworkEquipmentQuery->fields["entities_id"]=$data["entities_id"];
							$newID=$PluginArchiresNetworkEquipmentQuery->add($PluginArchiresNetworkEquipmentQuery->fields);
						}
					}
				}
			} else if ($data['itemtype']=='PluginArchiresApplianceQuery') {
				$PluginArchiresApplianceQuery=new PluginArchiresApplianceQuery();
				foreach ($data['item'] as $key => $val) {
					if ($val==1) {
						if ($PluginArchiresApplianceQuery->getFromDB($key)) {
							unset($PluginArchiresApplianceQuery->fields["id"]);
							$PluginArchiresApplianceQuery->fields["entities_id"]=$data["entities_id"];
							$newID=$PluginArchiresApplianceQuery->add($PluginArchiresApplianceQuery->fields);
						}
					}
				}
			} else if ($data['itemtype']=='PluginArchiresView') {
				$PluginArchiresView=new PluginArchiresView();
				foreach ($data['item'] as $key => $val) {
					if ($val==1) {
						if ($PluginArchiresView->getFromDB($key)) {
							unset($PluginArchiresView->fields["id"]);
							$PluginArchiresView->fields["entities_id"]=$data["entities_id"];
							$newID=$PluginArchiresView->add($PluginArchiresView->fields);
						}
					}
				}
			}
         break;
		case "plugin_archires_transfert":
         if ($data['itemtype']=='PluginArchiresLocationQuery') {
            foreach ($data["item"] as $key => $val) {
               if ($val==1) {

                  $query="UPDATE `glpi_plugin_archires_locationqueries`
                        SET `entities_id` = '".$data['entities_id']."'
                        WHERE `id` = '$key'";
                  $DB->query($query);
               }
            }
         } else if ($data['itemtype']=='PluginArchiresNetworkEquipmentQuery') {
            foreach ($data["item"] as $key => $val) {
               if ($val==1) {

                  $query="UPDATE `glpi_plugin_archires_networkequipmentqueries`
                        SET `entities_id` = '".$data['entities_id']."'
                        WHERE `id` = '$key'";
                  $DB->query($query);
               }
            }
         } else if ($data['itemtype']=='PluginArchiresApplianceQuery') {
            foreach ($data["item"] as $key => $val) {
               if ($val==1) {

                  $query="UPDATE `glpi_plugin_archires_appliancequeries`
                        SET `entities_id` = '".$data['entities_id']."'
                        WHERE `id` = '$key'";
                  $DB->query($query);
               }
            }
         } else if ($data['itemtype']=='PluginArchiresView') {
            foreach ($data["item"] as $key => $val) {
               if ($val==1) {

                  $query="UPDATE `glpi_plugin_archires_views`
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

	if (get_class($item)=='Profile') {
		if ($item->getField('id') && $item->getField('interface')!='helpdesk') {
			return array(
				1 => $LANG['plugin_archires']['title'][0],
         );
		}
	} else if (get_class($item)=='Config') {
      return array(
         1 => $LANG['plugin_archires']['title'][0],
      );
	}
	return false;
}

// Define headings actions added by the plugin
function plugin_headings_actions_archires($item) {

	if (in_array(get_class($item),array('Profile','Config'))) {
		return array(
			1 => "plugin_headings_archires",
		);
	}
	return false;
}

// action heading
function plugin_headings_archires($item,$withtemplate=0) {
	global $CFG_GLPI;
  
   $PluginArchiresProfile=new PluginArchiresProfile();
  
	switch (get_class($item)) {
		case 'Profile' :
			if (!$PluginArchiresProfile->GetfromDB($item->getField('id')))
            $PluginArchiresProfile->createAccess($item->getField('id'));
			$PluginArchiresProfile->showForm($CFG_GLPI["root_doc"]."/plugins/archires/front/profile.fom.php",$item->getField('id'));
         break;
      case 'Config' :

         break;
	}
}

?>