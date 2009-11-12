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

class PluginArchiresItemImage extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_imageitems";
	}

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
	
	function addItemImage($type,$itemtype,$img){
    GLOBAL  $PLUGIN_ARCHIRES_TYPE_TABLES,$DB;
    
    if ($type!='-1'){
      if ($this->GetfromDBbyType($itemtype,$type)){

        $this->update(array(
          'id'=>$this->fields['id'],
          'img'=>$img));
      } else {

        $this->add(array(
          'itemtype'=>$itemtype,
          'type'=>$type,
          'img'=>$img));
      }
    }else{
      $query="SELECT * 
          FROM `".$PLUGIN_ARCHIRES_TYPE_TABLES[$itemtype]."` ";	    
      $result = $DB->query($query);
      $number = $DB->numrows($result);
      $i = 0;
      while($i < $number){
        $type_table=$DB->result($result, $i, "id");
        if ($this->GetfromDBbyType($itemtype,$type_table)){

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

  function deleteItemImage($ID){
    
    $this->delete(array('id'=>$ID));
      
  }
  
  function showForm() {
    global $DB,$LANG,$CFG_GLPI;

    echo "<form method='post' action=\"./plugin_archires.config.php\">";
    echo "<table class='tab_cadre' cellpadding='5'><tr><th colspan='4'>";
    echo $LANG['plugin_archires']['setup'][2]." : </th></tr>";
    echo "<tr class='tab_bg_1'><td>";
    $types=$CFG_GLPI["state_types"];
    $PluginArchires=new PluginArchires();
    $PluginArchires->dropdownAllItems("type",0,0,$_SESSION["glpiactive_entity"],$types);
    echo "</td><td>";
    //file
    $rep = "../pics/";
    $dir = opendir($rep); 
    echo "<select name=\"img\">";
    while ($f = readdir($dir)) {
      if(is_file($rep.$f)) {
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
    if($result = $DB->query($query)){
      $number = $DB->numrows($result);
      if($number != 0){
      
        echo "<form method='post' name='massiveaction_form' id='massiveaction_form' action=\"./plugin_archires.config.php\">";
        echo "<div id='liste'>";
        echo "<table class='tab_cadre' cellpadding='5'>";
        echo "<tr>";
        if ($number > 1)
        {
          echo "<th><div align='left'>".$LANG['plugin_archires'][12]."</div></th><th><div align='left'>".$LANG['plugin_archires'][13]."</div></th><th><div align='left'>".$LANG['plugin_archires'][14]."</div></th><th></th>";
          echo "<th><div align='left'>".$LANG['plugin_archires'][12]."</div></th><th><div align='left'>".$LANG['plugin_archires'][13]."</div></th><th><div align='left'>".$LANG['plugin_archires'][14]."</div></th><th></th>";
        }
        else
        {
          echo "<th><div align='left'>".$LANG['plugin_archires'][12]."</div></th><th><div align='left'>".$LANG['plugin_archires'][13]."</div></th><th><div align='left'>".$LANG['plugin_archires'][14]."</div></th><th></th>";						
        }
        echo "</tr>";
      
        while($ligne= mysql_fetch_array($result)){
        
        $ID=$ligne["id"];
        
        if($i  % 2==0 && $number>1)
          echo "<tr class='tab_bg_1'>";
        
        if($number==1)
          echo "<tr class='tab_bg_1'>";	
        $PluginArchires=new PluginArchires();
        echo "<td>".$PluginArchires->getItemType($ligne["itemtype"])."</td><td>".$PluginArchires->getType($ligne["itemtype"],$ligne["type"])."</td><td><img src=\"".$CFG_GLPI["root_doc"]."/plugins/archires/pics/".$ligne["img"]."\" alt=\"".$ligne["img"]."\" title=\"".$ligne["img"]."\"></td>";					
        echo "<td>";
        echo "<input type='hidden' name='id' value='$ID'>";
        echo "<input type='checkbox' name='item[$ID]' value='1'>";
        echo "</td>";
        
        $i++;
        if(($i  == $number) && ($number  % 2 !=0) && $number>1)
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
        if($result = $DB->query($query)){
          while($ligne= mysql_fetch_array($result)){
            $config_img=$ligne["img"];

            if ($type == $ligne["type"])
            $image_name = $path."pics/$config_img";
          }
        }

    return $image_name;

  }
}

class PluginArchiresNetworkInterfaceColor extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_networkinterfacescolors";
	}

	function getFromDBbyNetworkInterface($networkinterfaces_id) {
		global $DB;
		$query = "SELECT * FROM `".$this->table."`
					WHERE `networkinterfaces_id` = '" . $networkinterfaces_id . "' ";
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
	
	function addNetworkInterfaceColor($networkinterfaces_id,$color){
	
	GLOBAL $DB;
    
    if ($networkinterfaces_id!='-1'){
      if ($this->getFromDBbyNetworkInterface($networkinterfaces_id)){

        $this->update(array(
        'id'=>$this->fields['id'],
        'color'=>$color));
      } else {

        $this->add(array(
        'networkinterfaces_id'=>$networkinterfaces_id,
        'color'=>$color));
      }
    }else{
      $query="SELECT * 
          FROM `glpi_networkinterfaces` ";	    
      $result = $DB->query($query);
      $number = $DB->numrows($result);
      $i = 0;
      while($i < $number){
        $networkinterface_table=$DB->result($result, $i, "id");
        if ($this->getFromDBbyNetworkInterface($networkinterface_table)){

          $this->update(array(
          'id'=>$this->fields['id'],
          'color'=>$color));
        } else {

          $this->add(array(
          'networkinterfaces_id'=>$networkinterface_table,
          'color'=>$color));
        }
        $i++;
      }			
    }
  }

  function deleteNetworkInterfaceColor($ID){
    
    $this->delete(array('id'=>$ID));
      
  }
  
  function showForm() {
    global $DB,$LANG,$CFG_GLPI;

    $query = "SELECT * 
        FROM `".$this->table."` 
        ORDER BY `networkinterfaces_id` ASC;";
    $i=0;
    if($result = $DB->query($query)){
      $number = $DB->numrows($result);
      
      echo "<form method='post' name='massiveaction_form_networkinterface_color' id='massiveaction_form_networkinterface_color' action=\"./plugin_archires.config.php\">";
      $used=array();
      if($number != 0){
        
        echo "<div id='liste_color'>";
        echo "<table class='tab_cadre' cellpadding='5'>";
        echo "<tr>";
        if ($number > 1)
        {
          echo "<th><div align='left'>".$LANG['plugin_archires'][19]."</div></th><th><div align='left'>".$LANG['plugin_archires'][20]."</div></th><th></th>";
          echo "<th><div align='left'>".$LANG['plugin_archires'][19]."</div></th><th><div align='left'>".$LANG['plugin_archires'][20]."</div></th><th></th>";
        }
        else
        {
          echo "<th><div align='left'>".$LANG['plugin_archires'][19]."</div></th><th><div align='left'>".$LANG['plugin_archires'][20]."</div></th><th></th>";					
        }
        echo "</tr>";
        
        while($ligne= mysql_fetch_array($result)){
        
          $ID=$ligne["id"];
          $networkinterfaces_id=$ligne["networkinterfaces_id"];
          $used[]=$networkinterfaces_id;
          if($i  % 2==0 && $number>1)
            echo "<tr class='tab_bg_1'>";
          
          if($number==1)
            echo "<tr class='tab_bg_1'>";						
          echo "<td>".getDropdownName("glpi_networkinterfaces",$ligne["networkinterfaces_id"])."</td><td bgcolor='".$ligne["color"]."'>".$ligne["color"]."</td>";					
          echo "<td>";
          echo "<input type='hidden' name='id' value='$ID'>";
          echo "<input type='checkbox' name='item_color[$ID]' value='1'>";
          echo "</td>";
          
          $i++;
          if(($i  == $number) && ($number  % 2 !=0) && $number>1)
            echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
        
        }
        
        echo "<tr class='tab_bg_1'>";
        if ($number > 1)
          echo "<td colspan='8'>";
        else
          echo "<td colspan='4'>";
          
        echo "<div align='center'><a onclick= \"if ( markCheckboxes ('massiveaction_form_networkinterface_color') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=all'>".$LANG['buttons'][18]."</a>";
        echo " - <a onclick= \"if ( unMarkCheckboxes ('massiveaction_form_networkinterface_color') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=none'>".$LANG['buttons'][19]."</a> ";
        echo "<input type='submit' name='delete_color_networkinterface' value=\"".$LANG['buttons'][6]."\" class='submit' ></div></td></tr>";
        echo "</table>";
        echo "</div>";
          
      }
      
      echo "<table class='tab_cadre' cellpadding='5'><tr ><th colspan='3'>";
      echo $LANG['plugin_archires']['setup'][8]." : </th></tr>";
      echo "<tr class='tab_bg_1'><td>";
      $this->dropdownNetworkInterface($used);
      echo "</td><td>";
      echo "<input type='text' name=\"color\">";
      echo " <a href=\"http://www.graphviz.org/doc/info/colors.html\" target='_blank'>";
      echo " <img alt='' src='".$CFG_GLPI["root_doc"]."/pics/aide.png' onmouseout=\"cleanhide('comments_networkinterface')\" onmouseover=\"cleandisplay('comments_networkinterface')\">";
      echo "</a><span class='over_link' id='comments_networkinterface'>".nl2br($LANG['plugin_archires']['setup'][12])."</span>";
      
      echo "<td>";
      echo "<div align='center'><input type='submit' name='add_color_networkinterface' value=\"".$LANG['buttons'][2]."\" class='submit' ></div></td></tr>";
      echo "</table>";
      echo "</form>";
    }
  }
  
  function dropdownNetworkInterface($used=array()) {
    global $DB,$LANG,$CFG_GLPI;

    $limit=$_SESSION["glpidropdown_chars_limit"];
    
    $where="";
    
    if (count($used)) {
      $where .= "WHERE id NOT IN (0";
      foreach ($used as $ID)
        $where .= ",$ID";
      $where .= ")";
    }
    
    $query="SELECT * 
        FROM `glpi_networkinterfaces`
        $where
        ORDER BY `name`";
    $result=$DB->query($query);
    $number = $DB->numrows($result);

    if($number>0){
      echo "<select name='networkinterfaces_id'>\n";
      echo "<option value='0'>-----</option>\n";
      echo "<option value='-1'>".$LANG['plugin_archires'][21]."</option>\n";
      while($data= mysql_fetch_array($result)){
        $output=$data["name"];
        if (utf8_strlen($output)>$limit) {
          $output=utf8_substr($output,0,$limit)."&hellip;";
        }
        echo "<option value='".$data["id"]."'>".$output."</option>";
      }
      echo "</select>";
    }	
  }
}

class PluginArchiresVlanColor extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_vlanscolors";
	}

	function getFromDBbyVlan($vlan) {
		global $DB;
		$query = "SELECT * FROM `".$this->table."`
					WHERE `vlans_id` = '" . $vlan . "' ";
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
	
	function addVlanColor($vlan,$color){
	
    GLOBAL $DB;
    
    if ($vlan!='-1'){
      if ($this->GetfromDBbyVlan($vlan)){

        $this->update(array(
        'id'=>$this->fields['id'],
        'color'=>$color));
      } else {

        $this->add(array(
        'vlans_id'=>$vlan,
        'color'=>$color));
      }
    }else{
      $query="SELECT * 
          FROM `glpi_vlans` ";	    
      $result = $DB->query($query);
      $number = $DB->numrows($result);
      $i = 0;
      while($i < $number){
        $vlan_table=$DB->result($result, $i, "id");
        if ($this->GetfromDBbyVlan($vlan_table)){

          $this->update(array(
          'id'=>$this->fields['id'],
          'color'=>$color));
        } else {

          $this->add(array(
        'vlans_id'=>$vlan_table,
        'color'=>$color));
        }
        $i++;
      }			
    }
  }

  function deleteVlanColor($ID){
    
    $this->delete(array('id'=>$ID));
      
  }
  
  function showForm() {
    global $DB,$LANG,$CFG_GLPI;

    $query = "SELECT * 
        FROM `".$this->table."` 
        ORDER BY `vlans_id` ASC;";
    $i=0;
    $used=array();
    
    if($result = $DB->query($query)){
      $number = $DB->numrows($result);
      
      echo "<form method='post' name='massiveaction_form_vlan_color' id='massiveaction_form_vlan_color' action=\"./plugin_archires.config.php\">";
      
      if($number != 0){
        
        echo "<div id='liste_vlan'>";
        echo "<table class='tab_cadre' cellpadding='5'>";
        echo "<tr>";
        if ($number > 1)
        {
          echo "<th><div align='left'>".$LANG['plugin_archires'][35]."</div></th><th><div align='left'>".$LANG['plugin_archires'][20]."</div></th><th></th>";
          echo "<th><div align='left'>".$LANG['plugin_archires'][35]."</div></th><th><div align='left'>".$LANG['plugin_archires'][20]."</div></th><th></th>";
        }
        else
        {
          echo "<th><div align='left'>".$LANG['plugin_archires'][35]."</div></th><th><div align='left'>".$LANG['plugin_archires'][20]."</div></th><th></th>";					
        }
        echo "</tr>";
      
        while($ligne= mysql_fetch_array($result)){
        
          $ID=$ligne["id"];
          $vlans_id=$ligne["vlans_id"];
          $used[]=$vlans_id;
          if($i  % 2==0 && $number>1)
            echo "<tr class='tab_bg_1'>";
          
          if($number==1)
            echo "<tr class='tab_bg_1'>";						
          echo "<td>".getDropdownName("glpi_vlans", $ligne["vlans_id"])."</td><td bgcolor='".$ligne["color"]."'>".$ligne["color"]."</td>";					
          echo "<td>";
          echo "<input type='hidden' name='id' value='$ID'>";
          echo "<input type='checkbox' name='item_color[$ID]' value='1'>";
          echo "</td>";
          
          $i++;
          if(($i  == $number) && ($number  % 2 !=0) && $number>1)
            echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
        
        }
        
        echo "<tr class='tab_bg_1'>";
        if ($number > 1)
          echo "<td colspan='8'>";
        else
          echo "<td colspan='4'>";
          
        echo "<div align='center'><a onclick= \"if ( markCheckboxes ('massiveaction_form_vlan_color') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=all'>".$LANG['buttons'][18]."</a>";
        echo " - <a onclick= \"if ( unMarkCheckboxes ('massiveaction_form_vlan_color') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=none'>".$LANG['buttons'][19]."</a> ";
        echo "<input type='submit' name='delete_color_vlan' value=\"".$LANG['buttons'][6]."\" class='submit' ></div></td></tr>";
        echo "</table>";
        echo "</div>";
        
      }
      
      echo "<table class='tab_cadre' cellpadding='5'><tr ><th colspan='3'>";
      echo $LANG['plugin_archires']['setup'][23]." : </th></tr>";
      echo "<tr class='tab_bg_1'><td>";
      $this->dropdownVlan($used);
      echo "</td><td>";
      echo "<input type='text' name=\"color\">";
      echo " <a href=\"http://www.graphviz.org/doc/info/colors.html\" target='_blank'>";	
      echo " <img alt='' src='".$CFG_GLPI["root_doc"]."/pics/aide.png' onmouseout=\"cleanhide('comments_vlan')\" onmouseover=\"cleandisplay('comments_vlan')\">";
      echo "</a><span class='over_link' id='comments_vlan'>".nl2br($LANG['plugin_archires']['setup'][23])."</span>";

      echo "<td>";
      echo "<div align='center'><input type='submit' name='add_color_vlan' value=\"".$LANG['buttons'][2]."\" class='submit' ></div></td></tr>";
      echo "</table>";
      echo "</form>";	
    }
  }
  
  function dropdownVlan($used=array()) {
    global $DB,$LANG,$CFG_GLPI;
    
    $limit=$_SESSION["glpidropdown_chars_limit"];
    
    $where="";
    
    if (count($used)) {
      $where .= "WHERE id NOT IN (0";
      foreach ($used as $ID)
        $where .= ",$ID";
      $where .= ")";
    }
    
    $query="SELECT * 
        FROM `glpi_vlans` 
        $where
        ORDER BY `name`";
    $result=$DB->query($query);
    $number = $DB->numrows($result);

    if($number !="0"){
    echo "<select name='vlans_id'>\n";
    echo "<option value='0'>-----</option>\n";
    echo "<option value='-1'>".$LANG['plugin_archires'][36]."</option>\n";
    while($data= mysql_fetch_array($result)){
      $output=$data["name"];
      if (utf8_strlen($output)>$limit) {
        $output=utf8_substr($output,0,$limit)."&hellip;";
      }
      echo "<option value='".$data["id"]."'>".$output."</option>";
    }
    echo "</select>";

    }	
  }
  
  function getVlanbyNetworkPort ($ID){
    global $DB;
    
      $q = "SELECT `glpi_vlans`.`id` 
      FROM `glpi_vlans`, `glpi_networkports_vlans` 
      WHERE `glpi_networkports_vlans`.`vlans_id` = `glpi_vlans`.`id` 
      AND `glpi_networkports_vlans`.`networkports_id` = '$ID' " ;
      $r=$DB->query($q);
      $nb = $DB->numrows($r);
      if( $r = $DB->query($q)){
        $data_vlan = $DB->fetch_array($r) ;
        $vlan= $data_vlan["id"] ;
      }
      
    return $vlan;

  }
}

class PluginArchiresStateColor extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_statescolors";
	}

	function getFromDBbyState($state) {
		global $DB;
		$query = "SELECT * FROM `".$this->table."`
				WHERE `states_id` = '" . $state . "' ";
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
	
	function addStateColor($state,$color){
	
    GLOBAL $DB;
    
    if ($state!='-1'){
      if ($this->GetfromDBbyState($state)){

        $this->update(array(
        'id'=>$this->fields['id'],
        'color'=>$color));
      } else {

        $this->add(array(
        'states_id'=>$state,
        'color'=>$color));
      }
    }else{
      $query="SELECT * 
          FROM `glpi_states` ";	    
      $result = $DB->query($query);
      $number = $DB->numrows($result);
      $i = 0;
      while($i < $number){
        $state_table=$DB->result($result, $i, "id");
        if ($this->GetfromDBbyState($state_table)){

          $this->update(array(
          'id'=>$this->fields['id'],
          'color'=>$color));
        } else {

          $this->add(array(
        'states_id'=>$state_table,
        'color'=>$color));
        }
        $i++;
      }			
    }
  }

  function deleteStateColor($ID){
    
    $this->delete(array('id'=>$ID));
      
  }
  
  function showForm() {
    global $DB,$LANG,$CFG_GLPI;

    $query = "SELECT * 
        FROM `".$this->table."` 
        ORDER BY `states_id` ASC;";
    $i=0;
    $used=array();
    
    if($result = $DB->query($query)){
      $number = $DB->numrows($result);
      
      echo "<form method='post' name='massiveaction_form_state_color' id='massiveaction_form_state_color' action=\"./plugin_archires.config.php\">";
      
      if($number != 0){
        
        echo "<div id='liste_color'>";
        echo "<table class='tab_cadre' cellpadding='5'>";
        echo "<tr>";
        if ($number > 1)
        {
          echo "<th><div align='left'>".$LANG['plugin_archires'][27]."</div></th><th><div align='left'>".$LANG['plugin_archires'][20]."</div></th><th></th>";
          echo "<th><div align='left'>".$LANG['plugin_archires'][27]."</div></th><th><div align='left'>".$LANG['plugin_archires'][20]."</div></th><th></th>";
        }
        else
        {
          echo "<th><div align='left'>".$LANG['plugin_archires'][27]."</div></th><th><div align='left'>".$LANG['plugin_archires'][20]."</div></th><th></th>";					
        }
        echo "</tr>";
      
        while($ligne= mysql_fetch_array($result)){
        
          $ID=$ligne["id"];
          $states_id=$ligne["states_id"];
          $used[]=$states_id;
          if($i  % 2==0 && $number>1)
            echo "<tr class='tab_bg_1'>";
          
          if($number==1)
            echo "<tr class='tab_bg_1'>";						
          echo "<td>".getDropdownName("glpi_states",$ligne["states_id"])."</td><td bgcolor='".$ligne["color"]."'>".$ligne["color"]."</td>";					
          echo "<td>";
          echo "<input type='hidden' name='id' value='$ID'>";
          echo "<input type='checkbox' name='item_color[$ID]' value='1'>";
          echo "</td>";
          
          $i++;
          if(($i  == $number) && ($number  % 2 !=0) && $number>1)
            echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
        
        }
        
        echo "<tr class='tab_bg_1'>";
        if ($number > 1)
          echo "<td colspan='8'>";
        else
          echo "<td colspan='4'>";
          
        echo "<div align='center'><a onclick= \"if ( markCheckboxes ('massiveaction_form_state_color') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=all'>".$LANG['buttons'][18]."</a>";
        echo " - <a onclick= \"if ( unMarkCheckboxes ('massiveaction_form_state_color') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=none'>".$LANG['buttons'][19]."</a> ";
        echo "<input type='submit' name='delete_color_state' value=\"".$LANG['buttons'][6]."\" class='submit' ></div></td></tr>";
        echo "</table>";
        echo "</div>";
          
      }
      
      echo "<table class='tab_cadre' cellpadding='5'><tr ><th colspan='3'>";
      echo $LANG['plugin_archires']['setup'][19]." : </th></tr>";
      echo "<tr class='tab_bg_1'><td>";
      $this->dropdownState($used);
      echo "</td><td>";
      echo "<input type='text' name=\"color\">";
      echo " <a href=\"http://www.graphviz.org/doc/info/colors.html\" target='_blank'>";	
      echo " <img alt='' src='".$CFG_GLPI["root_doc"]."/pics/aide.png' onmouseout=\"cleanhide('comments_state')\" onmouseover=\"cleandisplay('comments_state')\">";
      echo "</a><span class='over_link' id='comments_state'>".nl2br($LANG['plugin_archires']['setup'][12])."</span>";

      echo "<td>";
      echo "<div align='center'><input type='submit' name='add_color_state' value=\"".$LANG['buttons'][2]."\" class='submit' ></div></td></tr>";
      echo "</table>";
      echo "</form>";
    }
  }
  
  function dropdownState($used=array()) {
    global $DB,$LANG,$CFG_GLPI;
    
    $limit=$_SESSION["glpidropdown_chars_limit"];
    
    $where="";
    
    if (count($used)) {
      $where .= "WHERE id NOT IN (0";
      foreach ($used as $ID)
        $where .= ",$ID";
      $where .= ")";
    }
    
    $query="SELECT * 
        FROM `glpi_states` 
        $where
        ORDER BY `name`";
    $result=$DB->query($query);
    $number = $DB->numrows($result);

    if($number !="0"){
    echo "<select name='states_id'>\n";
    echo "<option value='0'>-----</option>\n";
    echo "<option value='-1'>".$LANG['plugin_archires'][15]."</option>\n";
    while($data= mysql_fetch_array($result)){
      $output=$data["name"];
      if (utf8_strlen($output)>$limit) {
        $output=utf8_substr($output,0,$limit)."&hellip;";
      }
      echo "<option value='".$data["id"]."'>".$output."</option>";
    }
    echo "</select>";
    }	
  }
  
  function displayColorState($device) {
    global $CFG_GLPI,$DB,$LANG;

      $graph ="";
      $query_state = "SELECT *
      FROM `".$this->table."`
      WHERE `states_id`= '".$device["states_id"]."';";
      $result_state = $DB->query($query_state);
      $number_state = $DB->numrows($result_state);

      if($number_state != 0 && $device["states_id"] > 0){
        $color_state=$DB->result($result_state,0,"color");
        $graph ="<font color=\"$color_state\">".getDropdownName("glpi_states",$device["states_id"])."</font>";
      }elseif($number_state == 0 && $device["states_id"] > 0){
        $graph =getDropdownName("glpi_states",$device["states_id"]);
      }

    return $graph;

  }

}

class PluginArchiresQueryType extends CommonDBTM {

	function __construct () {
		$this->table="glpi_plugin_archires_queriestypes";
	}

	function getFromDBbyType($itemtype, $type,$type_query,$query_ID) {
		global $DB;
		$query = "SELECT * FROM `".$this->table."` " .
			"WHERE `itemtype` = '" . $itemtype . "' " .
				"AND `type` = '" . $type . "' " .
				"AND `querytype` = '" . $type_query . "' " .
				"AND `queries_id` = '" . $query_ID . "' ";
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
	
	function addType($querytype,$type,$itemtype,$queries_id){
    GLOBAL  $PLUGIN_ARCHIRES_TYPE_TABLES,$DB;
    
    if ($type!='-1'){
      if (!$this->GetfromDBbyType($itemtype,$type,$querytype,$queries_id)){

        $this->add(array(
          'itemtype'=>$itemtype,
          'type'=>$type,
          'querytype'=>$querytype,
          'queries_id'=>$queries_id));
      }
    }else{
        
      $query="SELECT * 
          FROM `".$PLUGIN_ARCHIRES_TYPE_TABLES[$itemtype]."` ";	    
      $result = $DB->query($query);
      $number = $DB->numrows($result);
      $i = 0;
      while($i < $number){
        $type_table=$DB->result($result, $i, "id");
        if (!$this->GetfromDBbyType($itemtype,$type_table,$querytype,$queries_id)){
          $this->add(array(
          'itemtype'=>$itemtype,
          'type'=>$type_table,
          'querytype'=>$querytype,
          'queries_id'=>$queries_id));
        }
        $i++;
      }			
    }
  }

  function deleteType($ID){
    
    $this->delete(array('id'=>$ID));
        
  }
  
  function queryTypeCheck($querytype,$views_id,$val) {
    global $DB,$LINK_ID_TABLE;
    
    $query0="SELECT * 
          FROM `".$this->table."` 
          WHERE `querytype` = '".$querytype."' 
          AND `queries_id` = '".$views_id."' 
          AND `itemtype` = '" . $val . "';";
    $result0=$DB->query($query0);
    
    $query="";
    
    if ($val == COMPUTER_TYPE) {
      $typefield = "computerstypes_id";
    }elseif ($val == NETWORKING_TYPE) {
      $typefield = "networkequipmentstypes_id";
    }elseif ($val == PERIPHERAL_TYPE) {
      $typefield = "peripheralstypes_id";
    }elseif ($val == PRINTER_TYPE) {
      $typefield = "printerstypes_id";
    }elseif ($val == PHONE_TYPE) {
      $typefield = "phonestypes_id";
    }
      
    if ($DB->numrows($result0)>0){
        
        $query = "AND `$LINK_ID_TABLE[$val]`.`$typefield` IN (0 ";	
        while ($data0=$DB->fetch_array($result0)){
          $type_where=",'".$data0["type"]."' ";
          $query .= " $type_where ";
        }
      $query .= ") ";
    }
    
    return $query;
  }
  
  function showTypes($type,$ID) {
    global $CFG_GLPI,$DB,$LANG;

    if ($type==PLUGIN_ARCHIRES_LOCATIONS_QUERY)
      $table="location";
    elseif ($type==PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY)
      $table="networkequipment";
    elseif ($type==PLUGIN_ARCHIRES_APPLIANCES_QUERY)
      $table="appliance";

    echo "<div align='center'>";

    if(plugin_archires_haveRight("archires","w")){

      echo "<form method='post'  action=\"./plugin_archires.".$table.".form.php\">";
      echo "<table class='tab_cadre' cellpadding='5' width='34%'><tr><th colspan='2'>";
      echo $LANG['plugin_archires'][2]." : </th></tr>";
      echo "<tr class='tab_bg_1'><td>";
      $types=$CFG_GLPI["state_types"];
      $PluginArchires=new PluginArchires();
      $PluginArchires->dropdownAllItems("type",0,0,$_SESSION["glpiactive_entity"],$types);

      echo "</td>";
      echo "<td>";

      echo "<div align='center'><input type='hidden' name='query' value='$ID'><input type='submit' name='addtype' value=\"".$LANG['buttons'][2]."\" class='submit' ></div></td></tr>";
      echo "</table>";
      echo "</form>";
    }

    $query = "SELECT *
        FROM `".$this->table."`
        WHERE `queries_id` = '".$ID."'
        AND `querytype` = '".$type."'  ";
      $query.=" ORDER BY `itemtype`, `type` ASC;";

    $i=0;
    $rand=mt_rand();
    if($result = $DB->query($query)){
      $number = $DB->numrows($result);
      if($number != 0){

        echo "<form method='post' name='massiveaction_form$rand' id='massiveaction_form$rand' action=\"./plugin_archires.".$table.".form.php\">";
        echo "<div id='liste'>";
        echo "<table class='tab_cadre' cellpadding='5'>";
        echo "<tr>";
        if ($number > 1){
          echo "<th><div align='left'>".$LANG['plugin_archires'][12]."</div></th><th><div align='left'>".$LANG['plugin_archires'][13]."</div></th><th></th>";
          echo "<th><div align='left'>".$LANG['plugin_archires'][12]."</div></th><th><div align='left'>".$LANG['plugin_archires'][13]."</div></th><th></th>";
        }else{
          echo "<th><div align='left'>".$LANG['plugin_archires'][12]."</div></th><th><div align='left'>".$LANG['plugin_archires'][13]."</div></th><th></th>";
        }
        echo "</tr>";

        while($ligne= mysql_fetch_array($result)){

          $ID=$ligne["id"];

          if($i  % 2==0 && $number>1)
            echo "<tr class='tab_bg_1'>";

          if($number==1)
            echo "<tr class='tab_bg_1'>";
          $PluginArchires=new PluginArchires();
          echo "<td>".$PluginArchires->getItemType($ligne["itemtype"])."</td><td>".$PluginArchires->getType($ligne["itemtype"],$ligne["type"])."</td>";
          echo "<td>";
          echo "<input type='hidden' name='id' value='$ID'>";
          echo "<input type='checkbox' name='item[$ID]' value='1'>";
          echo "</td>";

          $i++;
          if(($i  == $number) && ($number  % 2 !=0) && $number>1)
            echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
        }

        if(plugin_archires_haveRight("archires","w")){
          echo "<tr class='tab_bg_1'>";
          if ($number > 1)
            echo "<td colspan='6'>";
          else
            echo "<td colspan='3'>";

          echo "<div align='center'><a onclick= \"if ( markCheckboxes('massiveaction_form$rand') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=all'>".$LANG['buttons'][18]."</a>";
          echo " - <a onclick= \"if ( unMarkCheckboxes('massiveaction_form$rand') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=none'>".$LANG['buttons'][19]."</a> ";
          echo "<input type='submit' name='deletetype' value=\"".$LANG['buttons'][6]."\" class='submit' ></div></td></tr>";

        }
        echo "</table>";
        echo "</div>";
        echo "</form>";
      }
    }
    echo "</div>";
  }
}

?>