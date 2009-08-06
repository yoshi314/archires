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

		if(!TableExists("glpi_plugin_archires_config")) {

			plugin_archires_installing("1.7.2");

		}elseif(!TableExists("glpi_plugin_archires_color_vlan")){

			if(TableExists("glpi_plugin_archires_display") && !TableExists("glpi_plugin_archires_config")){

				plugin_archires_updatev13();
				plugin_archires_update("1.4");
				plugin_archires_update("1.5");
				plugin_archires_update("1.7.0");
				plugin_archires_update("1.7.2");

			}elseif(!TableExists("glpi_plugin_archires_color") && !TableExists("glpi_plugin_archires_profiles")){

				plugin_archires_update("1.4");
				plugin_archires_update("1.5");
				plugin_archires_update("1.7.0");
				plugin_archires_update("1.7.2");

			}elseif(!TableExists("glpi_plugin_archires_image_device")){

				plugin_archires_update("1.5");
				plugin_archires_update("1.7.0");
				plugin_archires_update("1.7.2");

			}elseif(TableExists("glpi_plugin_archires_profiles") && FieldExists("glpi_plugin_archires_profiles","interface")) {

				plugin_archires_update("1.7.0");
				plugin_archires_update("1.7.2");

			}elseif(TableExists("glpi_plugin_archires_config") && FieldExists("glpi_plugin_archires_config","system")) {

				plugin_archires_update("1.7.2");

			}
		}

		plugin_archires_createFirstAccess($_SESSION['glpiactiveprofile']['ID']);
		return true;
}

function plugin_archires_uninstall(){
	global $DB;

	$tables = array("glpi_plugin_archires_image_device",
					"glpi_plugin_archires_config",
					"glpi_plugin_archires_color_iface",
					"glpi_plugin_archires_color_vlan",
					"glpi_plugin_archires_color_state",
					"glpi_plugin_archires_profiles",
					"glpi_plugin_archires_query_location",
					"glpi_plugin_archires_query_switch",
					"glpi_plugin_archires_query_applicatifs",
					"glpi_plugin_archires_query_type");

	foreach($tables as $table)
		$DB->query("DROP TABLE `$table`;");

	$rep_files_archires = GLPI_PLUGIN_DOC_DIR."/archires";

	deleteDir($rep_files_archires);

	$query="DELETE FROM `glpi_display`
			WHERE `type`= '".PLUGIN_ARCHIRES_LOCATION_TYPE."'
			OR `type` = '".PLUGIN_ARCHIRES_SWITCH_TYPE."'
			OR `type`= '".PLUGIN_ARCHIRES_APPLICATIFS_TYPE."';";
	$DB->query($query);

	$query="DELETE FROM glpi_bookmark
			WHERE `device_type` = '".PLUGIN_ARCHIRES_LOCATION_TYPE."'
			OR `device_type` = '".PLUGIN_ARCHIRES_SWITCH_TYPE."'
			OR `device_type` = '".PLUGIN_ARCHIRES_APPLICATIFS_TYPE."';";
	$DB->query($query);

	plugin_init_archires();
	cleanCache("GLPI_HEADER_".$_SESSION["glpiID"]);

	return true;
}

// Define dropdown relations
function plugin_archires_getDatabaseRelations(){
	$plugin = new Plugin();
	if ($plugin->isActivated("archires"))

		return array(
		"glpi_entities"=>array("glpi_plugin_archires_query_location"=>"FK_entities",
								"glpi_plugin_archires_query_switch"=>"FK_entities",
								"glpi_plugin_archires_query_applicatifs"=>"FK_entities",
								"glpi_plugin_archires_config"=>"FK_entities"));
	else
		return array();

}

////// SEARCH FUNCTIONS ///////(){

// Define search option for types of the plugins
function plugin_archires_getSearchOption(){
	global $LANG;
	$sopt=array();

	// Part header
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE]['common']=$LANG['plugin_archires']['title'][4];

	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][1]['table']='glpi_plugin_archires_query_location';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][1]['field']='name';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][1]['linkfield']='name';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][1]['name']=$LANG['plugin_archires']['search'][1];
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][1]['datatype']='itemlink';

	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][2]['table']='glpi_plugin_archires_query_location';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][2]['field']='child';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][2]['linkfield']='child';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][2]['name']=$LANG['plugin_archires']['search'][3];
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][2]['datatype']='bool';

	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][3]['table']='glpi_dropdown_locations';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][3]['field']='completename';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][3]['linkfield']='location';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][3]['name']=$LANG['plugin_archires']['search'][2];

	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][4]['table']='glpi_dropdown_network';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][4]['field']='name';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][4]['linkfield']='network';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][4]['name']=$LANG['plugin_archires']['search'][4];

	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][5]['table']='glpi_dropdown_state';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][5]['field']='name';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][5]['linkfield']='state';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][5]['name']=$LANG['plugin_archires']['search'][5];

	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][6]['table']='glpi_groups';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][6]['field']='name';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][6]['linkfield']='FK_group';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][6]['name']=$LANG['common'][35];

	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][7]['table']='glpi_dropdown_vlan';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][7]['field']='name';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][7]['linkfield']='FK_vlan';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][7]['name']=$LANG['networking'][56];

	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][8]['table']='glpi_plugin_archires_config';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][8]['field']='name';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][8]['linkfield']='FK_config';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][8]['name']=$LANG['plugin_archires']['setup'][20];

	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][9]['table']='glpi_plugin_archires_query_location';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][9]['field']='link';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][9]['linkfield']='';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][9]['name']=$LANG['plugin_archires'][0];

	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][30]['table']='glpi_plugin_archires_query_location';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][30]['field']='ID';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][30]['linkfield']='';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][30]['name']=$LANG['common'][2];

	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][80]['table']='glpi_entities';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][80]['field']='completename';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][80]['linkfield']='FK_entities';
	$sopt[PLUGIN_ARCHIRES_LOCATION_TYPE][80]['name']=$LANG['entity'][0];

	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE]['common']=$LANG['plugin_archires']['title'][5];

	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][1]['table']='glpi_plugin_archires_query_switch';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][1]['field']='name';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][1]['linkfield']='name';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][1]['name']=$LANG['plugin_archires']['search'][1];
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][1]['datatype']='itemlink';

	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][2]['table']='glpi_networking';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][2]['field']='name';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][2]['linkfield']='switch';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][2]['name']=$LANG['help'][26];

	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][3]['table']='glpi_dropdown_network';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][3]['field']='name';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][3]['linkfield']='network';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][3]['name']=$LANG['plugin_archires']['search'][4];

	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][4]['table']='glpi_dropdown_state';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][4]['field']='name';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][4]['linkfield']='state';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][4]['name']=$LANG['plugin_archires']['search'][5];

	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][5]['table']='glpi_groups';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][5]['field']='name';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][5]['linkfield']='FK_group';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][5]['name']=$LANG['common'][35];

	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][6]['table']='glpi_dropdown_vlan';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][6]['field']='name';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][6]['linkfield']='FK_vlan';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][6]['name']=$LANG['networking'][56];

	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][7]['table']='glpi_plugin_archires_config';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][7]['field']='name';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][7]['linkfield']='FK_config';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][7]['name']=$LANG['plugin_archires']['setup'][20];

	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][8]['table']='glpi_plugin_archires_query_switch';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][8]['field']='link';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][8]['linkfield']='';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][8]['name']=$LANG['plugin_archires'][0];

	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][30]['table']='glpi_plugin_archires_query_switch';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][30]['field']='ID';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][30]['linkfield']='';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][30]['name']=$LANG['common'][2];

	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][80]['table']='glpi_entities';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][80]['field']='completename';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][80]['linkfield']='FK_entities';
	$sopt[PLUGIN_ARCHIRES_SWITCH_TYPE][80]['name']=$LANG['entity'][0];

	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE]['common']=$LANG['plugin_archires']['title'][8];

	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][1]['table']='glpi_plugin_archires_query_applicatifs';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][1]['field']='name';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][1]['linkfield']='name';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][1]['name']=$LANG['plugin_archires']['search'][1];
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][1]['datatype']='itemlink';

	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][2]['table']='glpi_plugin_applicatifs';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][2]['field']='name';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][2]['linkfield']='applicatifs';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][2]['name']=$LANG['plugin_archires']['search'][8];

	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][3]['table']='glpi_dropdown_network';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][3]['field']='name';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][3]['linkfield']='network';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][3]['name']=$LANG['plugin_archires']['search'][4];

	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][4]['table']='glpi_dropdown_state';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][4]['field']='name';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][4]['linkfield']='state';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][4]['name']=$LANG['plugin_archires']['search'][5];

	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][5]['table']='glpi_groups';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][5]['field']='name';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][5]['linkfield']='FK_group';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][5]['name']=$LANG['common'][35];

	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][6]['table']='glpi_dropdown_vlan';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][6]['field']='name';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][6]['linkfield']='FK_vlan';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][6]['name']=$LANG['networking'][56];

	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][7]['table']='glpi_plugin_archires_config';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][7]['field']='name';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][7]['linkfield']='FK_config';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][7]['name']=$LANG['plugin_archires']['setup'][20];

	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][8]['table']='glpi_plugin_archires_query_applicatifs';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][8]['field']='link';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][8]['linkfield']='';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][8]['name']=$LANG['plugin_archires'][0];

	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][30]['table']='glpi_plugin_archires_query_applicatifs';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][30]['field']='ID';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][30]['linkfield']='';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][30]['name']=$LANG['common'][2];

	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][80]['table']='glpi_entities';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][80]['field']='completename';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][80]['linkfield']='FK_entities';
	$sopt[PLUGIN_ARCHIRES_APPLICATIFS_TYPE][80]['name']=$LANG['entity'][0];

	return $sopt;
}

function plugin_archires_giveItem($type,$ID,$data,$num){
	global $CFG_GLPI, $INFOFORM_PAGES, $LANG,$SEARCH_OPTION,$DB;

	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];

	switch ($type){
		case PLUGIN_ARCHIRES_LOCATION_TYPE :
			switch ($table.'.'.$field){
				case "glpi_dropdown_locations.completename" :
					if (empty($data["ITEM_$num"]))
						$out=$LANG['plugin_archires'][30];
					else
						$out= $data["ITEM_$num"];
				return $out;
				break;
				case "glpi_dropdown_network.name" :
					if (empty($data["ITEM_$num"]))
						$out=$LANG['plugin_archires'][11];
					else
						$out= $data["ITEM_$num"];
				return $out;
				break;
				case "glpi_dropdown_state.name" :
					if (empty($data["ITEM_$num"]))
						$out=$LANG['plugin_archires'][11];
					else
						$out= $data["ITEM_$num"];
				return $out;
				break;
				case "glpi_dropdown_vlan.name" :
					if (empty($data["ITEM_$num"]))
						$out=$LANG['plugin_archires'][11];
					else
						$out= $data["ITEM_$num"];
				return $out;
				break;
				case "glpi_plugin_archires_query_location.link" :
					$out= "<a href=\"../graph.php?ID=".$data["ID"]."&type=".PLUGIN_ARCHIRES_LOCATION_QUERY."\">".$LANG['plugin_archires']['search'][6]."</a>";
				return $out;
				break;
			}
			return "";
		break;

		case PLUGIN_ARCHIRES_SWITCH_TYPE :
			switch ($table.'.'.$field){
				case "glpi_networking.name" :
					if (empty($data["ITEM_$num"]))
						$out=$LANG['plugin_archires'][32];
					else
						$out= $data["ITEM_$num"];
				return $out;
				break;
				case "glpi_dropdown_network.name" :
					if (empty($data["ITEM_$num"]))
						$out=$LANG['plugin_archires'][11];
					else
						$out= $data["ITEM_$num"];
				return $out;
				break;
				case "glpi_dropdown_state.name" :
					if (empty($data["ITEM_$num"]))
						$out=$LANG['plugin_archires'][11];
					else
						$out= $data["ITEM_$num"];
				return $out;
				break;
				case "glpi_dropdown_vlan.name" :
					if (empty($data["ITEM_$num"]))
						$out=$LANG['plugin_archires'][11];
					else
						$out= $data["ITEM_$num"];
				return $out;
				break;
				case "glpi_plugin_archires_query_switch.link" :
					$out= "<a href=\"../graph.php?ID=".$data["ID"]."&type=".PLUGIN_ARCHIRES_SWITCH_QUERY."\">".$LANG['plugin_archires']['search'][6]."</a>";
				return $out;
				break;
				break;
			}
		case PLUGIN_ARCHIRES_APPLICATIFS_TYPE :
			switch ($table.'.'.$field){
				case "glpi_plugin_applicatifs.name" :
					if (empty($data["ITEM_$num"]))
						$out=$LANG['plugin_archires'][32];
					else
						$out= $data["ITEM_$num"];
				return $out;
				break;
				case "glpi_dropdown_network.name" :
					if (empty($data["ITEM_$num"]))
						$out=$LANG['plugin_archires'][11];
					else
						$out= $data["ITEM_$num"];
				return $out;
				break;
				case "glpi_dropdown_state.name" :
					if (empty($data["ITEM_$num"]))
						$out=$LANG['plugin_archires'][11];
					else
						$out= $data["ITEM_$num"];
				return $out;
				break;
				case "glpi_dropdown_vlan.name" :
					if (empty($data["ITEM_$num"]))
						$out=$LANG['plugin_archires'][11];
					else
						$out= $data["ITEM_$num"];
				return $out;
				break;
				case "glpi_plugin_archires_query_applicatifs.link" :
					$out= "<a href=\"../graph.php?ID=".$data["ID"]."&type=".PLUGIN_ARCHIRES_APPLICATIFS_QUERY."\">".$LANG['plugin_archires']['search'][6]."</a>";
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
				$PluginArchiresProfile->cleanProfiles($input["ID"]);
				break;
		}
	return $input;
}

////// SPECIFIC MODIF MASSIVE FUNCTIONS ///////

function plugin_archires_MassiveActions($type){
	global $LANG;
	switch ($type){
		case PLUGIN_ARCHIRES_LOCATION_TYPE:
			return array(
				// GLPI core one
				//"add_document"=>$LANG['document'][16],
				// Specific one
				"plugin_archires_duplicate"=>$LANG['plugin_archires'][28],
				"plugin_archires_transfert"=>$LANG['buttons'][48],
				);
		break;

		case PLUGIN_ARCHIRES_SWITCH_TYPE:
			return array(
				// GLPI core one
				//"add_document"=>$LANG['document'][16],
				// Specific one
				"plugin_archires_duplicate"=>$LANG['plugin_archires'][28],
				"plugin_archires_transfert"=>$LANG['buttons'][48],
				);
		break;

		case PLUGIN_ARCHIRES_APPLICATIFS_TYPE:
			return array(
				// GLPI core one
				//"add_document"=>$LANG['document'][16],
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
		case PLUGIN_ARCHIRES_LOCATION_TYPE:
			switch ($action){
				// No case for add_document : use GLPI core one
				case "plugin_archires_duplicate":
					dropdownValue("glpi_entities", "FK_entities", '');
					echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
				break;
				case "plugin_archires_transfert":
					dropdownValue("glpi_entities", "FK_entities", '');
				echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
				break;
			}
		break;
		case PLUGIN_ARCHIRES_SWITCH_TYPE:
			switch ($action){
				// No case for add_document : use GLPI core one
				case "plugin_archires_duplicate":
					dropdownValue("glpi_entities", "FK_entities", '');
					echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
				break;
				case "plugin_archires_transfert":
					dropdownValue("glpi_entities", "FK_entities", '');
				echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
				break;
			}
		break;
		case PLUGIN_ARCHIRES_APPLICATIFS_TYPE:
			switch ($action){
				// No case for add_document : use GLPI core one
				case "plugin_archires_duplicate":
					dropdownValue("glpi_entities", "FK_entities", '');
					echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
				break;
				case "plugin_archires_transfert":
					dropdownValue("glpi_entities", "FK_entities", '');
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
			if ($data['device_type']==PLUGIN_ARCHIRES_LOCATION_TYPE){

				$PluginArchiresQueryLocation=new PluginArchiresQueryLocation();
				foreach ($data['item'] as $key => $val){
					if ($val==1) {
						if ($PluginArchiresQueryLocation->getFromDB($key)){
							unset($PluginArchiresQueryLocation->fields["ID"]);
							$PluginArchiresQueryLocation->fields["FK_entities"]=$data["FK_entities"];
							$newID=$PluginArchiresQueryLocation->add($PluginArchiresQueryLocation->fields);
						}
					}
				}
			}elseif ($data['device_type']==PLUGIN_ARCHIRES_SWITCH_TYPE){
				$PluginArchiresQuerySwitch=new PluginArchiresQuerySwitch();
				foreach ($data['item'] as $key => $val){
					if ($val==1) {
						if ($PluginArchiresQuerySwitch->getFromDB($key)){
							unset($PluginArchiresQuerySwitch->fields["ID"]);
							$PluginArchiresQuerySwitch->fields["FK_entities"]=$data["FK_entities"];
							$newID=$PluginArchiresQuerySwitch->add($PluginArchiresQuerySwitch->fields);
						}
					}
				}
			}elseif ($data['device_type']==PLUGIN_ARCHIRES_APPLICATIFS_TYPE){
				$PluginArchiresQueryApplicatifs=new PluginArchiresQueryApplicatifs();
				foreach ($data['item'] as $key => $val){
					if ($val==1) {
						if ($PluginArchiresQueryApplicatifs->getFromDB($key)){
							unset($PluginArchiresQueryApplicatifs->fields["ID"]);
							$PluginArchiresQueryApplicatifs->fields["FK_entities"]=$data["FK_entities"];
							$newID=$PluginArchiresQueryApplicatifs->add($PluginArchiresQueryApplicatifs->fields);
						}
					}
				}
			}

		break;
		case "plugin_archires_transfert":
		if ($data['device_type']==PLUGIN_ARCHIRES_LOCATION_TYPE){
			foreach ($data["item"] as $key => $val){
				if ($val==1){

					$query="UPDATE `glpi_plugin_archires_query_location`
							SET `FK_entities` = '".$data['FK_entities']."'
							WHERE `ID` = '$key'";
					$DB->query($query);
				}
			}
		}elseif ($data['device_type']==PLUGIN_ARCHIRES_SWITCH_TYPE){
			foreach ($data["item"] as $key => $val){
				if ($val==1){

					$query="UPDATE `glpi_plugin_archires_query_switch`
							SET `FK_entities` = '".$data['FK_entities']."'
							WHERE `ID` = '$key'";
					$DB->query($query);
				}
			}
		}elseif ($data['device_type']==PLUGIN_ARCHIRES_APPLICATIFS_TYPE){
			foreach ($data["item"] as $key => $val){
				if ($val==1){

					$query="UPDATE `glpi_plugin_archires_query_applicatifs`
							SET `FK_entities` = '".$data['FK_entities']."'
							WHERE `ID` = '$key'";
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