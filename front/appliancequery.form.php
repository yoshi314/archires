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
include (GLPI_ROOT."/inc/includes.php");

if (!isset($_GET["id"])) {
   $_GET["id"] = "";
}

if (isset($_GET["start"])) {
   $start = $_GET["start"];
} else {
   $start = 0;
}

$PluginArchiresApplianceQuery = new PluginArchiresApplianceQuery();
$PluginArchiresQueryType      = new PluginArchiresQueryType();

if (isset($_POST["add"])) {
   $PluginArchiresApplianceQuery->check(-1,'w',$_POST);
   $PluginArchiresApplianceQuery->add($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["delete"])) {
   $PluginArchiresApplianceQuery->check($_POST['id'],'w');
   $PluginArchiresApplianceQuery->delete($_POST);
   glpi_header(getItemTypeSearchURL('PluginArchiresApplianceQuery'));

} else if (isset($_POST["restore"])) {
   $PluginArchiresApplianceQuery->check($_POST['id'],'w');
   $PluginArchiresApplianceQuery->restore($_POST);
   glpi_header(getItemTypeSearchURL('PluginArchiresApplianceQuery'));

} else if (isset($_POST["purge"])) {
   $PluginArchiresApplianceQuery->check($_POST['id'],'w');
   $PluginArchiresApplianceQuery->delete($_POST,1);
   glpi_header(getItemTypeSearchURL('PluginArchiresApplianceQuery'));

} else if (isset($_POST["update"])) {
   $PluginArchiresApplianceQuery->check($_POST['id'],'w');
   $PluginArchiresApplianceQuery->update($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["duplicate"])) {
   $PluginArchiresApplianceQuery->check($_POST['id'],'w');
   unset($_POST['id']);
   $PluginArchiresApplianceQuery->add($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["addtype"])) {
   $test= explode(";", $_POST['type']);
   if (isset($test[0]) && isset($test[1])) {
      $_POST['type']= $test[1];
      $_POST['itemtype']= $test[0];
      if ($PluginArchiresQueryType->canCreate()) {
         $PluginArchiresQueryType->addType('PluginArchiresApplianceQuery', $_POST['type'],
                                           $_POST['itemtype'], $_POST['query']);
      }
   }
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["deletetype"])) {
   if ($PluginArchiresQueryType->canCreate()) {
      $PluginArchiresQueryType->getFromDB($_POST["id"],-1);
      foreach ($_POST["item"] as $key => $val) {
         if ($val == 1) {
            $PluginArchiresQueryType->deleteType($key);
         }
      }
   }
   glpi_header($_SERVER['HTTP_REFERER']);

} else {
   
   $PluginArchiresApplianceQuery->checkGlobal("r");

   commonHeader($LANG['plugin_archires']['menu'][2]." ".$LANG['plugin_archires']['title'][8],
                '',"plugins","archires","appliance");

   $PluginArchiresApplianceQuery->showForm($_GET["id"]);

   commonFooter();
}

?>