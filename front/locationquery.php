<?php
/*
 * @version $Id: HEADER 1 2010-02-24 00:12 Tsmr $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

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
// Original Author of file: CAILLAUD Xavier & COLLET Remi & LASSON Nelly
// Purpose of file: plugin archires v1.8.0 - GLPI 0.80
// ----------------------------------------------------------------------
*/

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");

commonHeader($LANG['plugin_archires']['menu'][2]." ".$LANG['plugin_archires']['title'][4],
             '',"plugins","archires","location");

if (plugin_archires_haveRight("archires","r") || haveRight("config","w")) {
   Search::show("PluginArchiresLocationQuery");

} else {
   echo "<div align='center'><br><br>";
   echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt='warning'><br><br>";
   echo "<b>".$LANG['login'][5]."</b></div>";
}

commonFooter();

?>