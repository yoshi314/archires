<?php
/*
 * @version $Id: setup.php,v 1.3 2006/04/02 16:12:23 moyo Exp $
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
// Original Author of file: GRISARD Jean Marc
// Purpose of file:
// ----------------------------------------------------------------------

define ("PLUGIN_ARCHIRES_LOCATION_QUERY",0);
define ("PLUGIN_ARCHIRES_SWITCH_QUERY",1);
define ("PLUGIN_ARCHIRES_APPLICATIFS_QUERY",2);
define ("PLUGIN_ARCHIRES_NETWORK_COLOR",0);
define ("PLUGIN_ARCHIRES_VLAN_COLOR",1);
include_once ("inc/plugin_archires.auth.function.php");
include_once ("inc/plugin_archires.profile.class.php");

// Init the hooks of the plugins -Needed
function plugin_init_archires() {

	global $PLUGIN_HOOKS,$CFG_GLPI,$LANG,$PLUGIN_ARCHIRES_TYPE_TABLES;

	$PLUGIN_HOOKS['change_profile']['archires'] = 'plugin_archires_changeProfile';

	$PLUGIN_ARCHIRES_TYPE_TABLES = array (
		COMPUTER_TYPE => "glpi_type_computers",
		PRINTER_TYPE => "glpi_type_printers",
		NETWORKING_TYPE => "glpi_type_networking",
		PERIPHERAL_TYPE => "glpi_type_peripherals",
		PHONE_TYPE => "glpi_type_phones"
	);

	// Params : plugin name - string type - number - tabke - form page
	registerPluginType('archires', 'PLUGIN_ARCHIRES_LOCATION_TYPE', 3000, array(
		'classname'  => 'PluginArchiresQueryLocation',
		'tablename'  => 'glpi_plugin_archires_query_location',
		'formpage'   => 'front/plugin_archires.location.form.php',
		'searchpage' => 'front/plugin_archires.location.index.php',
		'deleted_tables' => true,
		'specif_entities_tables' => true
		));

	registerPluginType('archires', 'PLUGIN_ARCHIRES_SWITCH_TYPE', 3001, array(
		'classname'  => 'PluginArchiresQuerySwitch',
		'tablename'  => 'glpi_plugin_archires_query_switch',
		'formpage'   => 'front/plugin_archires.switch.form.php',
		'searchpage' => 'front/plugin_archires.switch.index.php',
		'deleted_tables' => true,
		'specif_entities_tables' => true
		));

	registerPluginType('archires', 'PLUGIN_ARCHIRES_APPLICATIFS_TYPE', 3002, array(
		'classname'  => 'PluginArchiresQueryApplicatifs',
		'tablename'  => 'glpi_plugin_archires_query_applicatifs',
		'formpage'   => 'front/plugin_archires.applicatif.form.php',
		'searchpage' => 'front/plugin_archires.applicatif.index.php',
		'deleted_tables' => true,
		'specif_entities_tables' => true
		));

	registerPluginType('archires', 'PLUGIN_ARCHIRES_VIEW_TYPE', 3003, array(
		'classname'  => 'PluginArchiresQueryConfig',
		'tablename'  => 'glpi_plugin_archires_config',
		'formpage'   => 'front/plugin_archires.config.form.php',
		'deleted_tables' => true,
		'specif_entities_tables' => true
		));

	if (isset($_SESSION["glpiID"])){

		//archires
		if ((isset($_SESSION["glpi_plugin_network_installed"]) && $_SESSION["glpi_plugin_network_installed"]==1)){

			$_SESSION["glpi_plugin_network_archires"]=1;

			if (plugin_archires_haveRight("archires","r")){
				$PLUGIN_HOOKS['menu_entry']['archires'] = false;
				$PLUGIN_HOOKS['use_massive_action']['archires']=1;
			}
		}else{

			if (plugin_archires_haveRight("archires","r")){
				$PLUGIN_HOOKS['menu_entry']['archires'] = true;

				$PLUGIN_HOOKS['submenu_entry']['archires']['search']['summary'] = 'index.php';

				$PLUGIN_HOOKS['submenu_entry']['archires']['search']['location'] = 'front/plugin_archires.location.index.php';
				$PLUGIN_HOOKS['submenu_entry']['archires']["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_showall.png' title='".$LANG['plugin_archires']["title"][3]."' alt='".$LANG['plugin_archires']["title"][3]."'>"]['location'] = 'front/plugin_archires.config.index.php';

				$PLUGIN_HOOKS['submenu_entry']['archires']['search']['switch'] = 'front/plugin_archires.switch.index.php';
				$PLUGIN_HOOKS['submenu_entry']['archires']["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_showall.png' title='".$LANG['plugin_archires']["title"][3]."' alt='".$LANG['plugin_archires']["title"][3]."'>"]['switch'] = 'front/plugin_archires.config.index.php';

				$PLUGIN_HOOKS['submenu_entry']['archires']['search']['applicatifs'] = 'front/plugin_archires.applicatif.index.php';
				$PLUGIN_HOOKS['submenu_entry']['archires']["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_showall.png' title='".$LANG['plugin_archires']["title"][3]."' alt='".$LANG['plugin_archires']["title"][3]."'>"]['applicatifs'] = 'front/plugin_archires.config.index.php';

				$PLUGIN_HOOKS['submenu_entry']['archires']["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_showall.png' title='".$LANG['plugin_archires']["title"][3]."' alt='".$LANG['plugin_archires']["title"][3]."'>"]['summary'] = 'front/plugin_archires.config.index.php';

			}

			if (plugin_archires_haveRight("archires","w")){
				$PLUGIN_HOOKS['submenu_entry']['archires']['add']['location'] = 'front/plugin_archires.location.form.php?new=1';
				$PLUGIN_HOOKS['submenu_entry']['archires']["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_addtemplate.png' title='".$LANG['plugin_archires']["title"][1]."' alt='".$LANG['plugin_archires']["title"][1]."'>"]['location'] = 'front/plugin_archires.config.form.php?new=1';

				$PLUGIN_HOOKS['submenu_entry']['archires']['add']['switch'] = 'front/plugin_archires.switch.form.php?new=1';
				$PLUGIN_HOOKS['submenu_entry']['archires']["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_addtemplate.png' title='".$LANG['plugin_archires']["title"][1]."' alt='".$LANG['plugin_archires']["title"][1]."'>"]['switch'] = 'front/plugin_archire.configs.form.php?new=1';

				$PLUGIN_HOOKS['submenu_entry']['archires']['add']['applicatifs'] = 'front/plugin_archires.applicatif.form.php?new=1';
				$PLUGIN_HOOKS['submenu_entry']['archires']["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_addtemplate.png' title='".$LANG['plugin_archires']["title"][1]."' alt='".$LANG['plugin_archires']["title"][1]."'>"]['applicatifs'] = 'front/plugin_archires.config.form.php?new=1';

				$PLUGIN_HOOKS['submenu_entry']['archires']["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_addtemplate.png' title='".$LANG['plugin_archires']["title"][1]."' alt='".$LANG['plugin_archires']["title"][1]."'>"]['summary'] = 'front/plugin_archires.config.form.php?new=1';

				if (haveRight("config","w"))
				$PLUGIN_HOOKS['submenu_entry']['archires']['config'] = 'front/plugin_archires.config.php';
				$PLUGIN_HOOKS['use_massive_action']['archires']=1;
			}
		}
		// Headings
		if (plugin_archires_haveRight("archires","r")){
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
function plugin_version_archires(){
global $LANG;

	return array (
		'name' => $LANG['plugin_archires']['title'][0],
		'version' => '1.7.2',
		'author'=>'Pierre érd, Adrien Ravise, Sébastien Prud homme, Xavier Caillaud',
		'homepage'=>'http://glpi-project.org/wiki/doku.php?id='.substr($_SESSION["glpilanguage"],0,2).':plugins:pluginslist',
		'minGlpiVersion' => '0.72',// For compatibility / no install in version < 0.72
	);
}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_archires_check_prerequisites(){
	if (GLPI_VERSION>=0.72){
		return true;
	} else {
		echo "GLPI version not compatible need 0.72";
	}
}

// Uninstall process for plugin : need to return true if succeeded : may display messages or add to message after redirect
function plugin_archires_check_config(){
	return true;
}

//////////////////////////////// Define rights for the plugin types

function plugin_archires_haveTypeRight($type,$right){
	switch ($type){
		case PLUGIN_ARCHIRES_LOCATION_TYPE :
			return plugin_archires_haveRight("archires",$right);
			break;
		case PLUGIN_ARCHIRES_SWITCH_TYPE :
			return plugin_archires_haveRight("archires",$right);
			break;
		case PLUGIN_ARCHIRES_APPLICATIFS_TYPE :
			return plugin_archires_haveRight("archires",$right);
			break;
	}
}

?>