<?php
/*
   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

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
// Original Author of file: GRISARD Jean Marc & CAILLAUD Xavier
// Purpose of file:
// ----------------------------------------------------------------------


class PluginArchiresItemImage extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_imageitems";
	}

	function getFromDBbyType($itemtype, $type) {
		global $DB;
		$query = "SELECT * FROM `".$this->table."` " .
			"WHERE (`itemtype` = '" . $itemtype . "') " .
				"AND (`type` = '" . $type . "')";
		if ($result = $DB->query($query)) {
			if ($DB->numrows($result) != 1) {
				return false;
			}
			$this->fields = $DB->fetch_assoc($result);
			if (is_array($this->fields) && count($this->fields)) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}
}

class PluginArchiresNetworkInterfaceColor extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_networkinterfacescolors";
	}

	function getFromDBbyNetworkInterface($networkinterfaces_id) {
		global $DB;
		$query = "SELECT * FROM `".$this->table."`
					WHERE `networkinterfaces_id` = '" . $networkinterfaces_id . "' ";
		if ($result = $DB->query($query)) {
			if ($DB->numrows($result) != 1) {
				return false;
			}
			$this->fields = $DB->fetch_assoc($result);
			if (is_array($this->fields) && count($this->fields)) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}
}

class PluginArchiresVlanColor extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_vlanscolors";
	}

	function getFromDBbyVlan($vlan) {
		global $DB;
		$query = "SELECT * FROM `".$this->table."`
					WHERE `vlans_id` = '" . $vlan . "' ";
		if ($result = $DB->query($query)) {
			if ($DB->numrows($result) != 1) {
				return false;
			}
			$this->fields = $DB->fetch_assoc($result);
			if (is_array($this->fields) && count($this->fields)) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}
}

class PluginArchiresStateColor extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_statescolors";
	}

	function getFromDBbyState($state) {
		global $DB;
		$query = "SELECT * FROM `".$this->table."`
				WHERE `states_id` = '" . $state . "' ";
		if ($result = $DB->query($query)) {
			if ($DB->numrows($result) != 1) {
				return false;
			}
			$this->fields = $DB->fetch_assoc($result);
			if (is_array($this->fields) && count($this->fields)) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}
}


class PluginArchiresQueryType extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_query_types";
	}

	function getFromDBbyType($itemtype, $type,$type_query,$query_ID) {
		global $DB;
		$query = "SELECT * FROM `".$this->table."` " .
			"WHERE `itemtype` = '" . $itemtype . "' " .
				"AND `type` = '" . $type . "' " .
				"AND `querytype` = '" . $type_query . "' " .
				"AND `queries_id` = '" . $query_ID . "' ";
		if ($result = $DB->query($query)) {
			if ($DB->numrows($result) != 1) {
				return false;
			}
			$this->fields = $DB->fetch_assoc($result);
			if (is_array($this->fields) && count($this->fields)) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}

}

?>