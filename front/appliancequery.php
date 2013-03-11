<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 Archires plugin for GLPI
 Copyright (C) 2003-2011 by the archires Development Team.

 https://forge.indepnet.net/projects/archires
 -------------------------------------------------------------------------

 LICENSE

 This file is part of archires.

 Archires is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Archires is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Archires. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
*/

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");

Html::header(PluginArchiresArchires::getTypeName()." ".PluginAppliancesAppliance::getTypeName(),
             '',"plugins","archires","appliance");

$PluginArchiresApplianceQuery = new PluginArchiresApplianceQuery();

if ($PluginArchiresApplianceQuery->canView() 
      || Session::haveRight("config","w")) {
   Search::show("PluginArchiresApplianceQuery");

} else {
   Html::displayRightError();
}

Html::footer();
?>