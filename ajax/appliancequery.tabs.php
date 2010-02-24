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
// Purpose of file: plugin archires v1.8.0 - GLPI 0.78
// ----------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
header_nocache();

if (!isset($_POST["id"])) {
   exit();
}

PluginArchiresProfile::checkRight("archires","r");

$ApplianceQuery = new PluginArchiresApplianceQuery();
$PluginArchiresQueryType = new PluginArchiresQueryType();
$PluginArchiresView      = new PluginArchiresView();
$PluginArchiresPrototype = new PluginArchiresPrototype();

if ($_POST["id"] >0 && $ApplianceQuery->can($_POST["id"],'r')) {
   switch($_REQUEST['glpi_tab']) {
      case -1 :
         $PluginArchiresQueryType->showTypes('PluginArchiresApplianceQuery',$_POST["id"]);
         Plugin::displayAction($ApplianceQuery,$_REQUEST['glpi_tab']);
         break;

      case 2 :
         $PluginArchiresView->showView('PluginArchiresApplianceQuery',$_POST["id"]);
         $PluginArchiresPrototype->test('PluginArchiresApplianceQuery',$_POST["id"]);
         break;

      case 3 :
         $PluginArchiresView->linkToAllViews('PluginArchiresApplianceQuery',$_POST["id"]);
         $obj = new PluginArchiresApplianceQuery();
         $obj->getFromDB($_POST["id"]);
         $plugin_archires_views_id=$obj->fields["plugin_archires_views_id"];
         $PluginArchiresPrototype->displayGraph($obj,$plugin_archires_views_id);
         break;

      case 10 :
         showNotesForm($_POST['target'],'PluginArchiresApplianceQuery',$_POST["id"]);
         break;

      default :

         if (!Plugin::displayAction($ApplianceQuery,$_REQUEST['glpi_tab'])) {
            $PluginArchiresQueryType->showTypes('PluginArchiresApplianceQuery',$_POST["id"]);
         }
   }
}

ajaxFooter();

?>