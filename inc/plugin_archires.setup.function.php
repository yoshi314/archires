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

function plugin_archires_installing($version) {
	
	global $DB;
		
	$DB_file = GLPI_ROOT ."/plugins/archires/inc/plugin_archires-$version-empty.sql";
	$DBf_handle = fopen($DB_file, "rt");
	$sql_query = fread($DBf_handle, filesize($DB_file));
	fclose($DBf_handle);
	foreach ( explode(";\n", "$sql_query") as $sql_line) {
		if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
		$DB->query($sql_line);
	}
	
	$rep_files_archires = GLPI_PLUGIN_DOC_DIR."/archires";
	if (!is_dir($rep_files_archires))
      mkdir($rep_files_archires);	
	
}

function plugin_archires_updatev13() {
	
	global $DB;

	$query = "ALTER TABLE `glpi_plugin_archires_display` ADD `display_ports` ENUM( '1', '0' ) NOT NULL DEFAULT '0';";		
	$DB->query($query) or die($DB->error());

}

function plugin_archires_update($version) {
	
	global $DB;

	$DB_file = GLPI_ROOT ."/plugins/archires/inc/plugin_archires-$version-update.sql";
	$DBf_handle = fopen($DB_file, "rt");
	$sql_query = fread($DBf_handle, filesize($DB_file));
	fclose($DBf_handle);
	foreach ( explode(";\n", "$sql_query") as $sql_line) {
		if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
		$DB->query($sql_line);
	}
	
	$rep_files_archires = GLPI_PLUGIN_DOC_DIR."/archires";
	if (!is_dir($rep_files_archires))
      mkdir($rep_files_archires);
}

?>