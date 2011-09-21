<?php
/*
 * @version $Id: HEADER 2011-03-12 18:01:26 tsmr $
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
// Original Author of file: CAILLAUD Xavier & COLLET Remi & LASSON Nelly & PRUDHOMME Sebastien
// Purpose of file: plugin archires v1.9.0 - GLPI 0.80
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
   Html::back();

} else if (isset($_POST["delete"])) {
   $PluginArchiresView->check($_POST['id'],'w');
   $PluginArchiresView->delete($_POST);
   Html::redirect(getItemTypeSearchURL('PluginArchiresView'));

} else if (isset($_POST["restore"])) {
   $PluginArchiresView->check($_POST['id'],'w');
   $PluginArchiresView->restore($_POST);
   Html::redirect(getItemTypeSearchURL('PluginArchiresView'));

} else if (isset($_POST["purge"])) {
   $PluginArchiresView->check($_POST['id'],'w');
   $PluginArchiresView->delete($_POST,1);
   Html::redirect(getItemTypeSearchURL('PluginArchiresView'));

} else if (isset($_POST["update"])) {
   $PluginArchiresView->check($_POST['id'],'w');
   $PluginArchiresView->update($_POST);
   Html::back();

} else if (isset($_POST["duplicate"])) {
   $PluginArchiresView->check($_POST['id'],'w');
   unset($_POST['id']);
   $PluginArchiresView->add($_POST);
   Html::back();

} else {

   $PluginArchiresView->checkGlobal("r");

   Html::header($LANG['plugin_archires']['title'][3],'',"plugins","archires","view");

   $PluginArchiresView->showForm($_GET["id"]);

   Html::footer();
}
?>