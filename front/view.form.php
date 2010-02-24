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
$PluginArchiresView = new PluginArchiresView();

if (isset($_POST["add"])) {
   $PluginArchiresView->check(-1,'w',$_POST);
   $PluginArchiresView->add($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["delete"])) {
   $PluginArchiresView->check($_POST['id'],'w');
   $PluginArchiresView->delete($_POST);
   glpi_header($CFG_GLPI["root_doc"]."/plugins/archires/front/archires.php");

} else if (isset($_POST["restore"])) {
   $PluginArchiresView->check($_POST['id'],'w');
   $PluginArchiresView->restore($_POST);
   glpi_header($CFG_GLPI["root_doc"]."/plugins/archires/front/archires.php");

} else if (isset($_POST["purge"])) {
   $PluginArchiresView->check($_POST['id'],'w');
   $PluginArchiresView->delete($_POST,1);
   glpi_header($CFG_GLPI["root_doc"]."/plugins/archires/front/archires.php");

} else if (isset($_POST["update"])) {
   $PluginArchiresView->check($_POST['id'],'w');
   $PluginArchiresView->update($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["duplicate"])) {
   $PluginArchiresView->check($_POST['id'],'w');
   unset($_POST['id']);
   $PluginArchiresView->add($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);

} else {
   PluginArchiresProfile::checkRight("archires","r");

   if (!isset($_SESSION['glpi_tab'])) {
      $_SESSION['glpi_tab'] = 1;
   }
   if (isset($_GET['onglet'])) {
      $_SESSION['glpi_tab'] = $_GET['onglet'];
   }

   commonHeader($LANG['plugin_archires']['title'][3],'',"plugins","archires","view");

   $PluginArchiresView->showForm($_GET["id"]);

   commonFooter();
}

?>