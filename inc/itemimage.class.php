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

class PluginArchiresItemImage extends CommonDBTM {

	public $table = 'glpi_plugin_archires_imageitems';

	function getFromDBbyType($itemtype, $type) {
		global $DB;
		
		$query = "SELECT * FROM `".$this->table."` " .
			"WHERE (`itemtype` = '" . $itemtype . "') " .
				"AND (`type` = '" . $type . "')";
		if ($result = $DB->query($query)) {
			if ($DB->numrows($result) != 1) {
				return false;
			}
			$this->fields = $DB->fetch_assoc($result);
			if (is_array($this->fields) && count($this->fields)) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}
	
	function addItemImage($type,$itemtype,$img) {
      global $PLUGIN_ARCHIRES_TYPE_TABLES,$DB;
    
      if ($type!='-1') {
         if ($this->GetfromDBbyType($itemtype,$type)) {

           $this->update(array(
             'id'=>$this->fields['id'],
             'img'=>$img));
             
         } else {

           $this->add(array(
             'itemtype'=>$itemtype,
             'type'=>$type,
             'img'=>$img));
         }
      } else {
       
         $query="SELECT * 
             FROM `".$PLUGIN_ARCHIRES_TYPE_TABLES[$itemtype]."` ";	    
         $result = $DB->query($query);
         $number = $DB->numrows($result);
         $i = 0;
         while($i < $number) {
            $type_table=$DB->result($result, $i, "id");
            if ($this->GetfromDBbyType($itemtype,$type_table)) {

            $this->update(array(
               'id'=>$this->fields['id'],
               'img'=>$img));
               
           } else {

             $this->add(array(
               'itemtype'=>$itemtype,
               'type'=>$type_table,
               'img'=>$img));
            }
            $i++;
         }			
      }
   }

   function deleteItemImage($ID) {
    
    $this->delete(array('id'=>$ID));
      
   }
  
   function showForm() {
      global $DB,$LANG,$CFG_GLPI,$PLUGIN_ARCHIRES_TYPE_NAME;

      echo "<form method='post' action=\"./config.form.php\">";
      echo "<table class='tab_cadre' cellpadding='5'><tr><th colspan='4'>";
      echo $LANG['plugin_archires']['setup'][2]." : </th></tr>";
      echo "<tr class='tab_bg_1'><td>";
      $PluginArchiresArchires=new PluginArchiresArchires();
      $PluginArchiresArchires->dropdownAllItems("type",0,0,$_SESSION["glpiactive_entity"]);
      echo "</td><td>";
      //file
      $rep = "../pics/";
      $dir = opendir($rep); 
      echo "<select name=\"img\">";
      while ($f = readdir($dir)) {
         if (is_file($rep.$f)) {
            echo "<option value='".$f."'>".$f."</option>";
         }   
      }
      echo "</select>";
      closedir($dir);
      echo " <img alt='' src='".$CFG_GLPI["root_doc"]."/pics/aide.png' onmouseout=\"cleanhide('commentsdropdown')\" onmouseover=\"cleandisplay('commentsdropdown')\">";
      echo "<span class='over_link' id='commentsdropdown'>".nl2br($LANG['plugin_archires']['setup'][21])."</span>";
      echo "<td>";
      echo "<div align='center'><input type='submit' name='add' value=\"".$LANG['buttons'][2]."\" class='submit' ></div></td></tr>";			
      echo "</table>";
      echo "</form>";	
    
      $query = "SELECT * 
        FROM `".$this->table."` 
        ORDER BY `itemtype`,`type` ASC;";
      $i=0;
      if ($result = $DB->query($query)) {
         $number = $DB->numrows($result);
         if ($number != 0) {
      
            echo "<form method='post' name='massiveaction_form' id='massiveaction_form' action=\"./config.form.php\">";
            echo "<div id='liste'>";
            echo "<table class='tab_cadre' cellpadding='5'>";
            echo "<tr>";
            if ($number > 1) {
               echo "<th><div align='left'>".$LANG['plugin_archires'][12]."</div></th><th><div align='left'>".$LANG['plugin_archires'][13]."</div></th><th><div align='left'>".$LANG['plugin_archires'][14]."</div></th><th></th>";
               echo "<th><div align='left'>".$LANG['plugin_archires'][12]."</div></th><th><div align='left'>".$LANG['plugin_archires'][13]."</div></th><th><div align='left'>".$LANG['plugin_archires'][14]."</div></th><th></th>";
            } else {
               echo "<th><div align='left'>".$LANG['plugin_archires'][12]."</div></th><th><div align='left'>".$LANG['plugin_archires'][13]."</div></th><th><div align='left'>".$LANG['plugin_archires'][14]."</div></th><th></th>";						
            }
            echo "</tr>";
      
            while($ligne= mysql_fetch_array($result)) {
        
               $ID=$ligne["id"];
        
               if ($i  % 2==0 && $number>1)
                  echo "<tr class='tab_bg_1'>";
        
               if ($number==1)
                  echo "<tr class='tab_bg_1'>";
                  
               $PluginArchiresArchires=new PluginArchiresArchires();
               echo "<td>".$PLUGIN_ARCHIRES_TYPE_NAME[$ligne["itemtype"]]."</td><td>".$PluginArchiresArchires->getType($ligne["itemtype"],$ligne["type"])."</td><td><img src=\"".$CFG_GLPI["root_doc"]."/plugins/archires/pics/".$ligne["img"]."\" alt=\"".$ligne["img"]."\" title=\"".$ligne["img"]."\"></td>";					
               echo "<td>";
               echo "<input type='hidden' name='id' value='$ID'>";
               echo "<input type='checkbox' name='item[$ID]' value='1'>";
               echo "</td>";
        
               $i++;
               if (($i  == $number) && ($number  % 2 !=0) && $number>1)
               echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
        
            }
        
            echo "<tr class='tab_bg_1'>";

            if ($number > 1)
               echo "<td colspan='8'>";
            else
               echo "<td colspan='4'>";	
            echo "<div align='center'><a onclick= \"if ( markCheckboxes ('massiveaction_form') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=all'>".$LANG['buttons'][18]."</a>";
            echo " - <a onclick= \"if ( unMarkCheckboxes ('massiveaction_form') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=none'>".$LANG['buttons'][19]."</a> ";
            echo "<input type='submit' name='delete' value=\"".$LANG['buttons'][6]."\" class='submit' ></div></td></tr>";
            echo "</table>";
            echo "</div>";
            echo "</form>";

         }
      }
   }
  
   function displayItemImage($type,$itemtype,$test) {
      global $DB;

      $path="";
      if ($test)
         $path="../";

      $image_name = $path."pics/nothing.png";
      $query = "SELECT *
        FROM `glpi_plugin_archires_imageitems`
        WHERE `itemtype` = '".$itemtype."';";
      if ($result = $DB->query($query)) {
         while($ligne= mysql_fetch_array($result)) {
            $config_img=$ligne["img"];

            if ($type == $ligne["type"])
            $image_name = $path."pics/$config_img";
         }
      }

      return $image_name;
   }
}

?>