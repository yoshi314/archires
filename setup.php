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

define ("PLUGIN_ARCHIRES_NETWORK_COLOR",0);
define ("PLUGIN_ARCHIRES_VLAN_COLOR",1);

define ("PLUGIN_ARCHIRES_JPEG_FORMAT",0);
define ("PLUGIN_ARCHIRES_PNG_FORMAT",1);
define ("PLUGIN_ARCHIRES_GIF_FORMAT",2);
define ("PLUGIN_ARCHIRES_SVG_FORMAT",3);

include_once ("inc/plugin_archires.profile.class.php");

// Init the hooks of the plugins -Needed
function plugin_init_archires() {

	global $PLUGIN_HOOKS,$CFG_GLPI,$LANG,$PLUGIN_ARCHIRES_TYPE_TABLES;

	$PLUGIN_HOOKS['change_profile']['archires'] = 'plugin_archires_changeProfile';

	$PLUGIN_ARCHIRES_TYPE_TABLES = array (
		COMPUTER_TYPE => "glpi_computertypes",
		PRINTER_TYPE => "glpi_printertypes",
		NETWORKING_TYPE => "glpi_networkequipmenttypes",
		PERIPHERAL_TYPE => "glpi_peripheraltypes",
		PHONE_TYPE => "glpi_phonetypes"
	);

	// Params : plugin name - string type - number - tabke - form page
	registerPluginType('archires', 'PLUGIN_ARCHIRES_LOCATIONS_QUERY', 3000, array(
		'classname'  => 'PluginArchiresQueryLocation',
		'tablename'  => 'glpi_plugin_archires_locationsqueries',
		'formpage'   => 'front/plugin_archires.location.form.php',
		'searchpage' => 'front/plugin_archires.location.index.php',
		'deleted_tables' => true,
		'specif_entities_tables' => true,
		'typename'   => $LANG['plugin_archires']['title'][4]
		));

	registerPluginType('archires', 'PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY', 3001, array(
		'classname'  => 'PluginArchiresQueryNetworkEquipment',
		'tablename'  => 'glpi_plugin_archires_networkequipmentsqueries',
		'formpage'   => 'front/plugin_archires.networkequipment.form.php',
		'searchpage' => 'front/plugin_archires.networkequipment.index.php',
		'deleted_tables' => true,
		'specif_entities_tables' => true,
		'typename'   => $LANG['plugin_archires']['title'][5]
		));

	registerPluginType('archires', 'PLUGIN_ARCHIRES_APPLIANCES_QUERY', 3002, array(
		'classname'  => 'PluginArchiresQueryAppliance',
		'tablename'  => 'glpi_plugin_archires_appliancesqueries',
		'formpage'   => 'front/plugin_archires.appliance.form.php',
		'searchpage' => 'front/plugin_archires.appliance.index.php',
		'deleted_tables' => true,
		'specif_entities_tables' => true,
		'typename'   => $LANG['plugin_archires']['title'][8]
		));

	registerPluginType('archires', 'PLUGIN_ARCHIRES_VIEWS_TYPE', 3003, array(
		'classname'  => 'PluginArchiresView',
		'tablename'  => 'glpi_plugin_archires_views',
		'formpage'   => 'front/plugin_archires.view.form.php',
		'deleted_tables' => true,
		'specif_entities_tables' => true,
		'typename'   => $LANG['plugin_archires']['title'][3]
		));

	if (isset($_SESSION["glpiID"])) {

		if ((isset($_SESSION["glpi_plugin_network_installed"]) && $_SESSION["glpi_plugin_network_installed"]==1)) {

			$_SESSION["glpi_plugin_network_archires"]=1;

			if (plugin_archires_haveRight("archires","r")) {
				$PLUGIN_HOOKS['menu_entry']['archires'] = false;
				$PLUGIN_HOOKS['use_massive_action']['archires']=1;
			}
		} else {

			if (plugin_archires_haveRight("archires","r")) {
				$PLUGIN_HOOKS['menu_entry']['archires'] = true;
				
				//summary
				$PLUGIN_HOOKS['submenu_entry']['archires']['search']['summary'] = 'index.php';
				$PLUGIN_HOOKS['submenu_entry']['archires']["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_showall.png' title='".$LANG['plugin_archires']["title"][3]."' alt='".$LANG['plugin_archires']["title"][3]."'>"]['summary'] = 'front/plugin_archires.view.index.php';
				
				//locations
				$PLUGIN_HOOKS['submenu_entry']['archires']['search']['locations'] = 'front/plugin_archires.location.index.php';
				$PLUGIN_HOOKS['submenu_entry']['archires']["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_showall.png' title='".$LANG['plugin_archires']["title"][3]."' alt='".$LANG['plugin_archires']["title"][3]."'>"]['locations'] = 'front/plugin_archires.view.index.php';
				
				//networkequipments
				$PLUGIN_HOOKS['submenu_entry']['archires']['search']['networkequipments'] = 'front/plugin_archires.networkequipment.index.php';
				$PLUGIN_HOOKS['submenu_entry']['archires']["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_showall.png' title='".$LANG['plugin_archires']["title"][3]."' alt='".$LANG['plugin_archires']["title"][3]."'>"]['networkequipments'] = 'front/plugin_archires.view.index.php';
				
				//appliances
				$PLUGIN_HOOKS['submenu_entry']['archires']['search']['appliances'] = 'front/plugin_archires.appliance.index.php';
				$PLUGIN_HOOKS['submenu_entry']['archires']["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_showall.png' title='".$LANG['plugin_archires']["title"][3]."' alt='".$LANG['plugin_archires']["title"][3]."'>"]['appliances'] = 'front/plugin_archires.view.index.php';

			}

			if (plugin_archires_haveRight("archires","w")) {
				
				//summary
				$PLUGIN_HOOKS['submenu_entry']['archires']["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_addtemplate.png' title='".$LANG['plugin_archires']["title"][1]."' alt='".$LANG['plugin_archires']["title"][1]."'>"]['summary'] = 'front/plugin_archires.view.form.php?new=1';
				
				//locations
				$PLUGIN_HOOKS['submenu_entry']['archires']['add']['locations'] = 'front/plugin_archires.location.form.php?new=1';
				$PLUGIN_HOOKS['submenu_entry']['archires']["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_addtemplate.png' title='".$LANG['plugin_archires']["title"][1]."' alt='".$LANG['plugin_archires']["title"][1]."'>"]['locations'] = 'front/plugin_archires.view.form.php?new=1';
				
				//networkequipments
				$PLUGIN_HOOKS['submenu_entry']['archires']['add']['networkequipments'] = 'front/plugin_archires.networkequipment.form.php?new=1';
				$PLUGIN_HOOKS['submenu_entry']['archires']["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_addtemplate.png' title='".$LANG['plugin_archires']["title"][1]."' alt='".$LANG['plugin_archires']["title"][1]."'>"]['networkequipments'] = 'front/plugin_archire.view.form.php?new=1';
				
				//appliances
				$PLUGIN_HOOKS['submenu_entry']['archires']['add']['appliances'] = 'front/plugin_archires.appliance.form.php?new=1';
				$PLUGIN_HOOKS['submenu_entry']['archires']["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_addtemplate.png' title='".$LANG['plugin_archires']["title"][1]."' alt='".$LANG['plugin_archires']["title"][1]."'>"]['appliances'] = 'front/plugin_archires.view.form.php?new=1';

				if (haveRight("config","w"))
					$PLUGIN_HOOKS['submenu_entry']['archires']['config'] = 'front/plugin_archires.config.php';
					
				$PLUGIN_HOOKS['use_massive_action']['archires']=1;
			}
		}
		// Headings
		if (plugin_archires_haveRight("archires","r")) {
			$PLUGIN_HOOKS['headings']['archires'] = 'plugin_get_headings_archires';
			$PLUGIN_HOOKS['headings_action']['archires'] = 'plugin_headings_actions_archires';
		}
		// Config page
			if (plugin_archires_haveRight("archires","w") || haveRight("config","w"))
            $PLUGIN_HOOKS['config_page']['archires'] = 'front/plugin_archires.config.php';

		$PLUGIN_HOOKS['pre_item_delete']['archires'] = 'plugin_pre_item_delete_archires';

	}
}

// Get the name and the version of the plugin - Needed
function plugin_version_archires() {
global $LANG;

	return array (
		'name' => $LANG['plugin_archires']['title'][0],
		'version' => '1.8.0',
		'author'=>'Xavier Caillaud',
		'homepage'=>'https://forge.indepnet.net/projects/show/archires',
		'minGlpiVersion' => '0.80',// For compatibility / no install in version < 0.80
	);
}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_archires_check_prerequisites() {
	if (GLPI_VERSION>=0.80) {
		return true;
	} else {
		echo "GLPI version not compatible need 0.80";
	}
}

// Uninstall process for plugin : need to return true if succeeded : may display messages or add to message after redirect
function plugin_archires_check_config() {
	return true;
}

//////////////////////////////// Define rights for the plugin types

function plugin_archires_haveTypeRight($type,$right) {
	switch ($type) {
		case PLUGIN_ARCHIRES_LOCATIONS_QUERY :
			return plugin_archires_haveRight("archires",$right);
			break;
		case PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY :
			return plugin_archires_haveRight("archires",$right);
			break;
		case PLUGIN_ARCHIRES_APPLIANCES_QUERY :
			return plugin_archires_haveRight("archires",$right);
			break;
		case PLUGIN_ARCHIRES_VIEWS_TYPE :
			return plugin_archires_haveRight("archires",$right);
			break;
	}
}

function plugin_archires_changeProfile() {
	$PluginArchiresProfile=new PluginArchiresProfile();
	$PluginArchiresProfile->changeProfile();
}

function plugin_archires_haveRight($module,$right) {
	$matches=array(
			""  => array("","r","w"), // ne doit pas arriver normalement
			"r" => array("r","w"),
			"w" => array("w"),
			"1" => array("1"),
			"0" => array("0","1"), // ne doit pas arriver non plus
		      );
	if (isset($_SESSION["glpi_plugin_archires_profile"][$module])&&in_array($_SESSION["glpi_plugin_archires_profile"][$module],$matches[$right]))
		return true;
	else return false;
}

?>