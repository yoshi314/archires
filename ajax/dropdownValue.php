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

// Direct access to file
if (strpos($_SERVER['PHP_SELF'],"dropdownValue.php")) {
   define('GLPI_ROOT', '../../..');
   include (GLPI_ROOT."/inc/includes.php");
   header("Content-Type: text/html; charset=UTF-8");
   header_nocache();
}

if (!defined('GLPI_ROOT')) {
   die("Can not acces directly to this file");
}

include_once (GLPI_ROOT."/plugins/archires/locales/".$_SESSION["glpilanguage"].".php");

checkLoginUser();

// Security
if (!TableExists($_POST['table'])) {
   exit();
}

$item = new $_POST['itemtype']();

// Make a select box with preselected values
if (!isset($_POST["limit"])) {
   $_POST["limit"] = $CFG_GLPI["dropdown_chars_limit"];
}

$NBMAX = $CFG_GLPI["dropdown_max"];
$LIMIT = "LIMIT 0,$NBMAX";
if ($_POST['searchText']==$CFG_GLPI["ajax_wildcard"]) {
   $LIMIT = "";
}

$where = "WHERE id <> '".$_POST['value']."' ";
$field = "name";

if ($_POST['searchText']!=$CFG_GLPI["ajax_wildcard"]) {
   $where .= " AND $field ".makeTextSearch($_POST['searchText']);
}

$query = "SELECT *
          FROM `".$_POST['table']."`
          $where
          ORDER BY $field $LIMIT";

$result = $DB->query($query);

echo "<select id='dropdown_".$_POST["myname"].$_POST["rand"]."' name=\"".$_POST['myname']."\">";

if ($_POST['searchText'] != $CFG_GLPI["ajax_wildcard"] && $DB->numrows($result)==$NBMAX) {
   echo "<option value='0\'>--".$LANG['common'][11]."--</option>";
} else {
   echo "<option value='0'>".DROPDOWN_EMPTY_VALUE."</option>";
}
$number = $DB->numrows($result);
if ($number != 0) {
   echo "<option value=\"".$_POST['itemtype'].";-1\">".$LANG['plugin_archires'][18]."</option>";
}
$output = Dropdown::getDropdownName($_POST['table'],$_POST['value']);
if (!empty($output)&&$output!="&nbsp;") {
   echo "<option selected value='".$_POST['value']."'>".$output."</option>";
}

if ($DB->numrows($result)) {
   while ($data =$DB->fetch_array($result)) {
      $output = $data[$field];
      $ID = $data['id'];
      $addcomment = "";
      if (isset($data["comment"])) {
         $addcomment = " - ".$data["comment"];
      }
      if (empty($output)) {
         $output = "($ID)";
      }

      echo "<option value=\"".$_POST['itemtype'].";$ID\" title=\"$output$addcomment\">".
            substr($output,0,$_POST["limit"])."</option>";
   }
}
echo "</select>";

?>