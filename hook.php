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

foreach (glob(GLPI_ROOT . '/plugins/archires/inc/*.php') as $file)
	include_once ($file);

function plugin_archires_install() {
   global $DB;
   
   include_once (GLPI_ROOT."/inc/profile.class.php");

   if (!TableExists("glpi_plugin_archires_config") && !TableExists("glpi_plugin_archires_views")) {

      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/empty-1.8.0.sql");

   } else if (TableExists("glpi_plugin_archires_display") &&  !FieldExists("glpi_plugin_archires_display","display_ports")) {

      plugin_archires_updatev13();
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.4.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.5.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.0.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.2.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.8.0.sql");

   } else if (TableExists("glpi_plugin_archires_display") && !TableExists("glpi_plugin_archires_profiles")) {

      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.4.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.5.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.0.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.2.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.8.0.sql");

   } else if (TableExists("glpi_plugin_archires_display") && !TableExists("glpi_plugin_archires_image_device")) {

      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.5.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.0.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.2.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.8.0.sql");

   } else if (TableExists("glpi_plugin_archires_profiles") && FieldExists("glpi_plugin_archires_profiles","interface")) {

      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.0.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.2.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.8.0.sql");

   } else if (TableExists("glpi_plugin_archires_config") && FieldExists("glpi_plugin_archires_config","system")) {

      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.7.2.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.8.0.sql");

   } else if (!TableExists("glpi_plugin_archires_views")) {

      $DB->runFile(GLPI_ROOT ."/plugins/archires/sql/update-1.8.0.sql");

   }
 
   $PluginArchiresProfile=new PluginArchiresProfile();
   $PluginArchiresProfile->createFirstAccess($_SESSION['glpiactiveprofile']['id']);
   return true;
}

function plugin_archires_uninstall() {
	global $DB;

	$tables = array("glpi_plugin_archires_imageitems",
					"glpi_plugin_archires_views",
					"glpi_plugin_archires_networkinterfacescolors",
					"glpi_plugin_archires_vlanscolors",
					"glpi_plugin_archires_statescolors",
					"glpi_plugin_archires_profiles",
					"glpi_plugin_archires_locationsqueries",
					"glpi_plugin_archires_networkequipmentsqueries",
					"glpi_plugin_archires_appliancesqueries",
					"glpi_plugin_archires_queriestypes");

	foreach($tables as $table)
		$DB->query("DROP TABLE `$table`;");

	$rep_files_archires = GLPI_PLUGIN_DOC_DIR."/archires";

	deleteDir($rep_files_archires);
  
   $tables_glpi = array("glpi_displaypreferences",
					"glpi_documents_items",
					"glpi_bookmarks",
					"glpi_logs");

	foreach($tables_glpi as $table_glpi)
		$DB->query("DELETE FROM `$table_glpi` WHERE `itemtype` = '".PLUGIN_ARCHIRES_LOCATIONS_QUERY."' OR `itemtype` = '".PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY."' OR `itemtype` = '".PLUGIN_ARCHIRES_APPLIANCES_QUERY."';");

	plugin_init_archires();

	return true;
}

// Define dropdown relations
function plugin_archires_getDatabaseRelations() {

	$plugin = new Plugin();
	if ($plugin->isActivated("archires"))

		return array(
		"glpi_entities"=>array("glpi_plugin_archires_locationsqueries"=>"entities_id",
								"glpi_plugin_archires_networkequipmentsqueries"=>"entities_id",
								"glpi_plugin_archires_appliancesqueries"=>"entities_id",
								"glpi_plugin_archires_views"=>"entities_id"));
	else
		return array();

}

////// SEARCH FUNCTIONS ///////() {

function plugin_archires_giveItem($type,$ID,$data,$num) {
	global $LANG;
  
   $searchopt=&getSearchOptions($type);
  
	$table=$searchopt[$ID]["table"];
	$field=$searchopt[$ID]["field"];

	switch ($type) {
		case PLUGIN_ARCHIRES_LOCATIONS_QUERY :
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
			}
			return "";
		break;

		case PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY :
			switch ($table.'.'.$field) {
				case "glpi_networkequipments.name" :
					if (empty($data["ITEM_$num"]))
						$out=$LANG['plugin_archires'][32];
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
			}
		case PLUGIN_ARCHIRES_APPLIANCES_QUERY :
			switch ($table.'.'.$field) {
				case "glpi_plugin_appliances.name" :
					if (empty($data["ITEM_$num"]))
						$out=$LANG['plugin_archires'][32];
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
			}
			return "";
		break;
	}
	return "";
}

// Hook done on delete item case

function plugin_pre_item_delete_archires($input) {

	if (isset($input["_item_type_"]))
		switch ($input["_item_type_"]) {
			case PROFILE_TYPE :
				// Manipulate data if needed
				$PluginArchiresProfile=new PluginArchiresProfile;
				$PluginArchiresProfile->cleanProfiles($input["id"]);
				break;
		}
	return $input;
}

////// SPECIFIC MODIF MASSIVE FUNCTIONS ///////

function plugin_archires_MassiveActions($type) {
	global $LANG;
	
	switch ($type) {
		case PLUGIN_ARCHIRES_LOCATIONS_QUERY:
			return array(
				// Specific one
				"plugin_archires_duplicate"=>$LANG['plugin_archires'][28],
				"plugin_archires_transfert"=>$LANG['buttons'][48],
				);
         break;

		case PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY:
			return array(
				// Specific one
				"plugin_archires_duplicate"=>$LANG['plugin_archires'][28],
				"plugin_archires_transfert"=>$LANG['buttons'][48],
				);
         break;

		case PLUGIN_ARCHIRES_APPLIANCES_QUERY:
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
		case PLUGIN_ARCHIRES_LOCATIONS_QUERY:
			switch ($action) {
				// No case for add_document : use GLPI core one
				case "plugin_archires_duplicate":
					dropdownValue("glpi_entities", "entities_id", '');
					echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
				case "plugin_archires_transfert":
					dropdownValue("glpi_entities", "entities_id", '');
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
			}
         break;
		case PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY:
			switch ($action) {
				// No case for add_document : use GLPI core one
				case "plugin_archires_duplicate":
					dropdownValue("glpi_entities", "entities_id", '');
					echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
				case "plugin_archires_transfert":
					dropdownValue("glpi_entities", "entities_id", '');
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
			}
         break;
		case PLUGIN_ARCHIRES_APPLIANCES_QUERY:
			switch ($action) {
				// No case for add_document : use GLPI core one
				case "plugin_archires_duplicate":
					dropdownValue("glpi_entities", "entities_id", '');
					echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
				case "plugin_archires_transfert":
					dropdownValue("glpi_entities", "entities_id", '');
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
			if ($data['itemtype']==PLUGIN_ARCHIRES_LOCATIONS_QUERY) {

				$PluginArchiresQueryLocation=new PluginArchiresQueryLocation();
				foreach ($data['item'] as $key => $val) {
					if ($val==1) {
						if ($PluginArchiresQueryLocation->getFromDB($key)) {
							unset($PluginArchiresQueryLocation->fields["id"]);
							$PluginArchiresQueryLocation->fields["entities_id"]=$data["entities_id"];
							$newID=$PluginArchiresQueryLocation->add($PluginArchiresQueryLocation->fields);
						}
					}
				}
			} else if ($data['itemtype']==PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY) {
				$PluginArchiresQueryNetworkEquipment=new PluginArchiresQueryNetworkEquipment();
				foreach ($data['item'] as $key => $val) {
					if ($val==1) {
						if ($PluginArchiresQueryNetworkEquipment->getFromDB($key)) {
							unset($PluginArchiresQueryNetworkEquipment->fields["id"]);
							$PluginArchiresQueryNetworkEquipment->fields["entities_id"]=$data["entities_id"];
							$newID=$PluginArchiresQueryNetworkEquipment->add($PluginArchiresQueryNetworkEquipment->fields);
						}
					}
				}
			} else if ($data['itemtype']==PLUGIN_ARCHIRES_APPLIANCES_QUERY) {
				$PluginArchiresQueryAppliance=new PluginArchiresQueryAppliance();
				foreach ($data['item'] as $key => $val) {
					if ($val==1) {
						if ($PluginArchiresQueryAppliance->getFromDB($key)) {
							unset($PluginArchiresQueryAppliance->fields["id"]);
							$PluginArchiresQueryAppliance->fields["entities_id"]=$data["entities_id"];
							$newID=$PluginArchiresQueryAppliance->add($PluginArchiresQueryAppliance->fields);
						}
					}
				}
			}

         break;
		case "plugin_archires_transfert":
		if ($data['itemtype']==PLUGIN_ARCHIRES_LOCATIONS_QUERY) {
			foreach ($data["item"] as $key => $val) {
				if ($val==1) {

					$query="UPDATE `glpi_plugin_archires_locationsqueries`
							SET `entities_id` = '".$data['entities_id']."'
							WHERE `id` = '$key'";
					$DB->query($query);
				}
			}
		} else if ($data['itemtype']==PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY) {
			foreach ($data["item"] as $key => $val) {
				if ($val==1) {

					$query="UPDATE `glpi_plugin_archires_networkequipmentsqueries`
							SET `entities_id` = '".$data['entities_id']."'
							WHERE `id` = '$key'";
					$DB->query($query);
				}
			}
		} else if ($data['itemtype']==PLUGIN_ARCHIRES_APPLIANCES_QUERY) {
			foreach ($data["item"] as $key => $val) {
				if ($val==1) {

					$query="UPDATE `glpi_plugin_archires_appliancesqueries`
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
function plugin_get_headings_archires($type,$ID,$withtemplate) {
	global $LANG;

	if ($type==PROFILE_TYPE) {
		$prof = new Profile();
		if ($ID>0 && $prof->getFromDB($ID) && $prof->fields['interface']!='helpdesk') {
			return array(
				1 => $LANG['plugin_archires']['title'][0],
				);
		}
	}
	return false;
}

// Define headings actions added by the plugin
function plugin_headings_actions_archires($type) {

	if (in_array($type,array(PROFILE_TYPE))) {
		return array(
			1 => "plugin_headings_archires",
		);
	}
	return false;
}

// action heading
function plugin_headings_archires($type,$ID,$withtemplate=0) {
	global $CFG_GLPI;
  
   $PluginArchiresProfile=new PluginArchiresProfile();
  
	switch ($type) {
		case PROFILE_TYPE :
			if (!$PluginArchiresProfile->GetfromDB($ID))
            $PluginArchiresProfile->createAccess($ID);
			$PluginArchiresProfile->showForm($CFG_GLPI["root_doc"]."/plugins/archires/front/profile.fom.php",$ID);
         break;
	}
}

?>