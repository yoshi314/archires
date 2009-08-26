<?php
/*
 * @version $Id: dropdownMassiveAction.php 3192 2006-04-17 15:53:11Z moyo $
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
// Original Author of file: Julien Dombre
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");
$AJAX_INCLUDE=1;

header("Content-Type: text/html; charset=UTF-8");
header_nocache();


if (isset($_POST["action"])||isset($_POST["id"])){
	echo "<input type='hidden' name='action' value='".$_POST["action"]."'>";
	echo "<input type='hidden' name='id' value='".$_POST["id"]."'>";
	switch($_POST["action"]){

		case "delete":
			case "purge":
			case "restore":
			echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
		break;
		case "duplicate":
			dropdownValue("glpi_entities", "entities_id", '');
			echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
		break;
		case "transfert":
			dropdownValue("glpi_entities", "entities_id", '');
			echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
		break;

	}
}

?>