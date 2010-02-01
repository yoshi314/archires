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

class PluginArchiresProfile extends CommonDBTM {

   //if profile deleted
   function cleanProfiles($ID) {
      $this->delete(array('id'=>$ID));
   }


   static function createFirstAccess($ID) {

      $myProf = new self();
      if (!$myProf->GetfromDB($ID)) {
         $Profile = new Profile();
         $Profile->GetfromDB($ID);
         $name = $Profile->fields["name"];

         $myProf->add(array('id'       => $ID,
                            'name'     => $name,
                            'archires' => 'w'));
      } 
   }


   function createAccess($Profile) {

      return $this->add(array('id'   => $Profile->getField('id'),
                              'name' => $Profile->getField('name')));
   }


   static function changeProfile() {

      $prof = new self();
      if ($prof->getFromDB($_SESSION['glpiactiveprofile']['id'])) {
         $_SESSION["glpi_plugin_archires_profile"]=$prof->fields;
      } else {
         unset($_SESSION["glpi_plugin_archires_profile"]);
      }
   }


   static function checkRight($module, $right) {
      global $CFG_GLPI;

      if (!plugin_archires_haveRight($module, $right)) {
         // Gestion timeout session
         if (!isset ($_SESSION["glpiID"])) {
            glpi_header($CFG_GLPI["root_doc"] . "/index.php");
            exit ();
         }
         displayRightError();
      }
   }


   //profiles modification
   function showForm($ID, $options=array()) {
      global $LANG;

      $target = $this->getFormURL();
      if (isset($options['target'])) {
        $target = $options['target'];
      }

      if (!haveRight("profile","r")) {
         return false;
      }
      $canedit = haveRight("profile","w");
      $profile = new Profile();
      if ($ID) {
         $this->getFromDB($ID);
         $profile->getFromDB($ID);
      }

      echo "<form action='".$target."' method='post'>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr><th colspan='2' class='center b'>".$LANG['plugin_archires']['profile'][0]." ".
            $this->fields["name"]."</th></tr>";

      echo "<tr class='tab_bg_2'>";
      echo "<td>".$LANG['plugin_archires']['profile'][3]." : </td><td>";
      if ($profile->fields['interface'] != 'helpdesk') {
         Profile::dropdownNoneReadWrite("archires",$this->fields["archires"],1,1,1);
      } else {
         echo $LANG['profiles'][12]; // No access;
      }
      echo "</td></tr>";

      if ($canedit) {
         echo "<tr class='tab_bg_1'>";
         echo "<td class='center' colspan='2'>";
         echo "<input type='hidden' name='id' value=$ID>";
         echo "<input type='submit' name='update_user_profile' value=\"".$LANG['buttons'][7].
               "\" class='submit'>";
         echo "</td></tr>";
      }
      echo "</table></form>";
   }
}

?>