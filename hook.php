<?php
/*
 * @version $Id: hook.php 7355 2008-10-03 15:31:00Z moyo $
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

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
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DOMBRE Julien
// Purpose of file:
// ----------------------------------------------------------------------

foreach (glob(GLPI_ROOT . '/plugins/archires/inc/*.php') as $file)
	include_once ($file);

function plugin_archires_install(){
	global $DB, $LANG, $CFG_GLPI;

		include_once (GLPI_ROOT."/inc/profile.class.php");

		if(!TableExists("glpi_plugin_archires_config") && !TableExists("glpi_plugin_archires_views")) {

			plugin_archires_installing("1.8.0");

		}elseif(TableExists("glpi_plugin_archires_display") &&  !FieldExists("glpi_plugin_archires_display","display_ports")){

      plugin_archires_updatev13();
      plugin_archires_update("1.4");
      plugin_archires_update("1.5");
      plugin_archires_update("1.7.0");
      plugin_archires_update("1.7.2");
      plugin_archires_update("1.8.0");

    }elseif(TableExists("glpi_plugin_archires_display") && !TableExists("glpi_plugin_archires_profiles")){

      plugin_archires_update("1.4");
      plugin_archires_update("1.5");
      plugin_archires_update("1.7.0");
      plugin_archires_update("1.7.2");
      plugin_archires_update("1.8.0");

    }elseif(TableExists("glpi_plugin_archires_display") && !TableExists("glpi_plugin_archires_image_device")){

      plugin_archires_update("1.5");
      plugin_archires_update("1.7.0");
      plugin_archires_update("1.7.2");
      plugin_archires_update("1.8.0");

    }elseif(TableExists("glpi_plugin_archires_profiles") && FieldExists("glpi_plugin_archires_profiles","interface")) {

      plugin_archires_update("1.7.0");
      plugin_archires_update("1.7.2");
      plugin_archires_update("1.8.0");

    }elseif(TableExists("glpi_plugin_archires_config") && FieldExists("glpi_plugin_archires_config","system")) {

      plugin_archires_update("1.7.2");
      plugin_archires_update("1.8.0");

		}elseif(!TableExists("glpi_plugin_archires_views")) {

			plugin_archires_update("1.8.0");

		}

		plugin_archires_createFirstAccess($_SESSION['glpiactiveprofile']['id']);
		return true;
}

function plugin_archires_uninstall(){
	global $DB;

	$tables = array("glpi_plugin_archires_imageitems",
					"glpi_plugin_archires_views",
					"glpi_plugin_archires_networkinterfacescolors",
					"glpi_plugin_archires_vlanscolors",
					"glpi_plugin_archires_statescolors",
					"glpi_plugin_archires_profiles",
					"glpi_plugin_archires_locations_queries",
					"glpi_plugin_archires_networkequipments_queries",
					"glpi_plugin_archires_appliances_queries",
					"glpi_plugin_archires_query_types");

	foreach($tables as $table)
		$DB->query("DROP TABLE `$table`;");

	$rep_files_archires = GLPI_PLUGIN_DOC_DIR."/archires";

	deleteDir($rep_files_archires);
  
  $tables_glpi = array("glpi_displayprefs",
					"glpi_documents_items",
					"glpi_bookmarks",
					"glpi_logs");

	foreach($tables_glpi as $table_glpi)
		$DB->query("DELETE FROM `$table_glpi` WHERE `itemtype` = '".PLUGIN_ARCHIRES_LOCATIONS_QUERY."' OR `itemtype` = '".PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY."' OR `itemtype` = '".PLUGIN_ARCHIRES_APPLIANCES_QUERY."';");

	plugin_init_archires();

	return true;
}

// Define dropdown relations
function plugin_archires_getDatabaseRelations(){
	$plugin = new Plugin();
	if ($plugin->isActivated("archires"))

		return array(
		"glpi_entities"=>array("glpi_plugin_archires_locations_queries"=>"entities_id",
								"glpi_plugin_archires_networkequipments_queries"=>"entities_id",
								"glpi_plugin_archires_appliances_queries"=>"entities_id",
								"glpi_plugin_archires_views"=>"entities_id"));
	else
		return array();

}

////// SEARCH FUNCTIONS ///////(){

// Define search option for types of the plugins
function plugin_archires_getSearchOption(){
	global $LANG;
	$sopt=array();

	// Part header
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY]['common']=$LANG['plugin_archires']['title'][4];

	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][1]['table']='glpi_plugin_archires_locations_queries';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][1]['field']='name';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][1]['linkfield']='name';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][1]['name']=$LANG['plugin_archires']['search'][1];
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][1]['datatype']='itemlink';

	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][2]['table']='glpi_plugin_archires_locations_queries';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][2]['field']='child';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][2]['linkfield']='child';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][2]['name']=$LANG['plugin_archires']['search'][3];
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][2]['datatype']='bool';

	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][3]['table']='glpi_locations';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][3]['field']='completename';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][3]['linkfield']='locations_id';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][3]['name']=$LANG['plugin_archires']['search'][2];

	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][4]['table']='glpi_networks';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][4]['field']='name';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][4]['linkfield']='networks_id';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][4]['name']=$LANG['plugin_archires']['search'][4];

	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][5]['table']='glpi_states';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][5]['field']='name';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][5]['linkfield']='states_id';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][5]['name']=$LANG['plugin_archires']['search'][5];

	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][6]['table']='glpi_groups';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][6]['field']='name';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][6]['linkfield']='groups_id';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][6]['name']=$LANG['common'][35];

	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][7]['table']='glpi_vlans';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][7]['field']='name';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][7]['linkfield']='vlans_id';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][7]['name']=$LANG['networking'][56];

	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][8]['table']='glpi_plugin_archires_views';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][8]['field']='name';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][8]['linkfield']='views_id';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][8]['name']=$LANG['plugin_archires']['setup'][20];

	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][9]['table']='glpi_plugin_archires_locations_queries';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][9]['field']='link';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][9]['linkfield']='';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][9]['name']=$LANG['plugin_archires'][0];

	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][30]['table']='glpi_plugin_archires_locations_queries';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][30]['field']='id';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][30]['linkfield']='';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][30]['name']=$LANG['common'][2];

	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][80]['table']='glpi_entities';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][80]['field']='completename';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][80]['linkfield']='entities_id';
	$sopt[PLUGIN_ARCHIRES_LOCATIONS_QUERY][80]['name']=$LANG['entity'][0];

	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY]['common']=$LANG['plugin_archires']['title'][5];

	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][1]['table']='glpi_plugin_archires_networkequipments_queries';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][1]['field']='name';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][1]['linkfield']='name';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][1]['name']=$LANG['plugin_archires']['search'][1];
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][1]['datatype']='itemlink';

	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][2]['table']='glpi_networkequipments';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][2]['field']='name';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][2]['linkfield']='networkequipments_id';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][2]['name']=$LANG['help'][26];

	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][3]['table']='glpi_networks';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][3]['field']='name';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][3]['linkfield']='networks_id';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][3]['name']=$LANG['plugin_archires']['search'][4];

	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][4]['table']='glpi_states';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][4]['field']='name';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][4]['linkfield']='states_id';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][4]['name']=$LANG['plugin_archires']['search'][5];

	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][5]['table']='glpi_groups';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][5]['field']='name';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][5]['linkfield']='groups_id';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][5]['name']=$LANG['common'][35];

	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][6]['table']='glpi_vlans';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][6]['field']='name';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][6]['linkfield']='vlans_id';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][6]['name']=$LANG['networking'][56];

	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][7]['table']='glpi_plugin_archires_views';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][7]['field']='name';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][7]['linkfield']='views_id';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][7]['name']=$LANG['plugin_archires']['setup'][20];

	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][8]['table']='glpi_plugin_archires_networkequipments_queries';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][8]['field']='link';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][8]['linkfield']='';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][8]['name']=$LANG['plugin_archires'][0];

	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][30]['table']='glpi_plugin_archires_networkequipments_queries';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][30]['field']='id';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][30]['linkfield']='';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][30]['name']=$LANG['common'][2];

	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][80]['table']='glpi_entities';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][80]['field']='completename';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][80]['linkfield']='entities_id';
	$sopt[PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY][80]['name']=$LANG['entity'][0];

	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY]['common']=$LANG['plugin_archires']['title'][8];

	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][1]['table']='glpi_plugin_archires_appliances_queries';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][1]['field']='name';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][1]['linkfield']='name';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][1]['name']=$LANG['plugin_archires']['search'][1];
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][1]['datatype']='itemlink';

	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][2]['table']='glpi_plugin_appliances';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][2]['field']='name';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][2]['linkfield']='appliances_id';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][2]['name']=$LANG['plugin_archires']['search'][8];

	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][3]['table']='glpi_networks';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][3]['field']='name';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][3]['linkfield']='networks_id';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][3]['name']=$LANG['plugin_archires']['search'][4];

	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][4]['table']='glpi_states';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][4]['field']='name';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][4]['linkfield']='states_id';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][4]['name']=$LANG['plugin_archires']['search'][5];

	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][5]['table']='glpi_groups';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][5]['field']='name';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][5]['linkfield']='groups_id';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][5]['name']=$LANG['common'][35];

	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][6]['table']='glpi_vlans';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][6]['field']='name';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][6]['linkfield']='vlans_id';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][6]['name']=$LANG['networking'][56];

	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][7]['table']='glpi_plugin_archires_views';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][7]['field']='name';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][7]['linkfield']='views_id';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][7]['name']=$LANG['plugin_archires']['setup'][20];

	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][8]['table']='glpi_plugin_archires_appliances_queries';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][8]['field']='link';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][8]['linkfield']='';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][8]['name']=$LANG['plugin_archires'][0];

	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][30]['table']='glpi_plugin_archires_appliances_queries';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][30]['field']='id';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][30]['linkfield']='';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][30]['name']=$LANG['common'][2];

	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][80]['table']='glpi_entities';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][80]['field']='completename';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][80]['linkfield']='entities_id';
	$sopt[PLUGIN_ARCHIRES_APPLIANCES_QUERY][80]['name']=$LANG['entity'][0];

	return $sopt;
}

function plugin_archires_giveItem($type,$ID,$data,$num){
	global $CFG_GLPI, $INFOFORM_PAGES, $LANG,$SEARCH_OPTION,$DB;

	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];

	switch ($type){
		case PLUGIN_ARCHIRES_LOCATIONS_QUERY :
			switch ($table.'.'.$field){
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
				case "glpi_plugin_archires_locations_queries.link" :
					$out= "<a href=\"../graph.php?id=".$data["id"]."&querytype=".PLUGIN_ARCHIRES_LOCATIONS_QUERY."\">".$LANG['plugin_archires']['search'][6]."</a>";
				return $out;
				break;
			}
			return "";
		break;

		case PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY :
			switch ($table.'.'.$field){
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
				case "glpi_plugin_archires_networkequipments_queries.link" :
					$out= "<a href=\"../graph.php?id=".$data["id"]."&querytype=".PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY."\">".$LANG['plugin_archires']['search'][6]."</a>";
				return $out;
				break;
				break;
			}
		case PLUGIN_ARCHIRES_APPLIANCES_QUERY :
			switch ($table.'.'.$field){
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
				case "glpi_plugin_archires_appliances_queries.link" :
					$out= "<a href=\"../graph.php?id=".$data["id"]."&querytype=".PLUGIN_ARCHIRES_APPLIANCES_QUERY."\">".$LANG['plugin_archires']['search'][6]."</a>";
				return $out;
				break;
			}
			return "";
		break;
	}
	return "";
}

// Hook done on delete item case

function plugin_pre_item_delete_archires($input){
	if (isset($input["_item_type_"]))
		switch ($input["_item_type_"]){
			case PROFILE_TYPE :
				// Manipulate data if needed
				$PluginArchiresProfile=new PluginArchiresProfile;
				$PluginArchiresProfile->cleanProfiles($input["id"]);
				break;
		}
	return $input;
}

////// SPECIFIC MODIF MASSIVE FUNCTIONS ///////

function plugin_archires_MassiveActions($type){
	global $LANG;
	switch ($type){
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
function plugin_archires_MassiveActionsDisplay($type,$action){
	global $LANG;
	switch ($type){
		case PLUGIN_ARCHIRES_LOCATIONS_QUERY:
			switch ($action){
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
			switch ($action){
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
			switch ($action){
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
function plugin_archires_MassiveActionsProcess($data){
	global $DB,$LANG;

	switch ($data['action']){
		case 'plugin_archires_duplicate':
			if ($data['itemtype']==PLUGIN_ARCHIRES_LOCATIONS_QUERY){

				$PluginArchiresQueryLocation=new PluginArchiresQueryLocation();
				foreach ($data['item'] as $key => $val){
					if ($val==1) {
						if ($PluginArchiresQueryLocation->getFromDB($key)){
							unset($PluginArchiresQueryLocation->fields["id"]);
							$PluginArchiresQueryLocation->fields["entities_id"]=$data["entities_id"];
							$newID=$PluginArchiresQueryLocation->add($PluginArchiresQueryLocation->fields);
						}
					}
				}
			}elseif ($data['itemtype']==PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY){
				$PluginArchiresQueryNetworkEquipment=new PluginArchiresQueryNetworkEquipment();
				foreach ($data['item'] as $key => $val){
					if ($val==1) {
						if ($PluginArchiresQueryNetworkEquipment->getFromDB($key)){
							unset($PluginArchiresQueryNetworkEquipment->fields["id"]);
							$PluginArchiresQueryNetworkEquipment->fields["entities_id"]=$data["entities_id"];
							$newID=$PluginArchiresQueryNetworkEquipment->add($PluginArchiresQueryNetworkEquipment->fields);
						}
					}
				}
			}elseif ($data['itemtype']==PLUGIN_ARCHIRES_APPLIANCES_QUERY){
				$PluginArchiresQueryAppliance=new PluginArchiresQueryAppliance();
				foreach ($data['item'] as $key => $val){
					if ($val==1) {
						if ($PluginArchiresQueryAppliance->getFromDB($key)){
							unset($PluginArchiresQueryAppliance->fields["id"]);
							$PluginArchiresQueryAppliance->fields["entities_id"]=$data["entities_id"];
							$newID=$PluginArchiresQueryAppliance->add($PluginArchiresQueryAppliance->fields);
						}
					}
				}
			}

		break;
		case "plugin_archires_transfert":
		if ($data['itemtype']==PLUGIN_ARCHIRES_LOCATIONS_QUERY){
			foreach ($data["item"] as $key => $val){
				if ($val==1){

					$query="UPDATE `glpi_plugin_archires_locations_queries`
							SET `entities_id` = '".$data['entities_id']."'
							WHERE `id` = '$key'";
					$DB->query($query);
				}
			}
		}elseif ($data['itemtype']==PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY){
			foreach ($data["item"] as $key => $val){
				if ($val==1){

					$query="UPDATE `glpi_plugin_archires_networkequipments_queries`
							SET `entities_id` = '".$data['entities_id']."'
							WHERE `id` = '$key'";
					$DB->query($query);
				}
			}
		}elseif ($data['itemtype']==PLUGIN_ARCHIRES_APPLIANCES_QUERY){
			foreach ($data["item"] as $key => $val){
				if ($val==1){

					$query="UPDATE `glpi_plugin_archires_appliances_queries`
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
function plugin_get_headings_archires($type,$ID,$withtemplate){

	global $LANG;

	if ($type==PROFILE_TYPE){
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
function plugin_headings_actions_archires($type){

	if (in_array($type,array(PROFILE_TYPE))){
		return array(
					1 => "plugin_headings_archires",
					);
	}
	return false;
}

// action heading
function plugin_headings_archires($type,$ID,$withtemplate=0){
	global $CFG_GLPI,$LANG;

	switch ($type){
		case PROFILE_TYPE :
				$prof=new PluginArchiresProfile();
				if (!$prof->GetfromDB($ID))
					plugin_archires_createAccess($ID);
				$prof->showForm($CFG_GLPI["root_doc"]."/plugins/archires/front/plugin_archires.profile.php",$ID);
		break;
	}
}

?>