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

class PluginArchiresPrototype extends CommonDBTM {

	function testGraphviz() {

    $graph ="graph G {
      a;
      b;
      c -- d;
      a -- c;}";

    $format="png";
    $engine="dot";

    //print $graph;
    $Path = GLPI_PLUGIN_DOC_DIR."/archires";
      $graph_name = tempnam($Path, "");
      $output_name = tempnam($Path, "");

      if ($graph_file = fopen($graph_name, "w")) {
          fputs($graph_file, $graph);
          fclose($graph_file);
      //$command = $engine." -T" . $format . " -o " . $output_name . " " . $graph_name;
      $command = $engine." -T" .$format." -o \"".$output_name ."\" \"".$graph_name."\"";
      `$command`;

          unlink($graph_name);

          if ($output_file = fopen($output_name, "rb")) {
              $output_data = fread($output_file, filesize($output_name));
              fclose($output_file);
              unlink($output_name);

              return $output_data;
          }
      }
  }
  
  function CleanField($string) {

    $string = str_replace(">", " - ", $string);
    $string = str_replace("&", " - ", $string);
    return $string;
  }
  
  function displayTypeAndIP($PluginArchiresView,$itemtype,$device,$generation) {

      $graph ="";
      
      $PluginArchires= new PluginArchires();
      if ($PluginArchiresView->fields["display_ip"]!=0 && isset($device["ip"])){
        if ($PluginArchiresView->fields["display_type"]!=0 && !empty($device["type"])){
          if (!$generation)
            $graph = $PluginArchires->getType($itemtype,$device["type"]) . " " . $device["ip"];
          else
            $graph =" - ".$PluginArchires->getType($itemtype,$device["type"])."</td></tr><tr><td>".$device["ip"]."</td></tr>";

        }else{
          if (!$generation)
            $graph = $device["ip"];
          else
            $graph ="</td></tr><tr><td>".$device["ip"]."</td></tr>";
        }
      }else{
        if ($PluginArchiresView->fields["display_type"]!=0 && !empty($device["type"])){
          if (!$generation)
            $graph =$PluginArchires->getType($itemtype,$device["type"]);
          else
            $graph ="</td></tr><tr><td>".$PluginArchires->getType($itemtype,$device["type"])."</td></tr>";
        }else{
          if (!$generation)
            echo "";
          else
            $graph ="</td></tr>";
        }
      }
    return $graph;

  }

  function displayUsers($url,$device,$generation) {

      $graph ="";
      if ($device["users_id"]){
        if ($generation)
          $graph = "URL=\"".$url."\" tooltip=\"".getusername($device["users_id"])."\"";
        else
          $graph = "<a href='".$url."'>".getusername($device["users_id"])."</a>";
      }elseif (!$device["users_id"] && $device["groups_id"]){
        if ($generation)
          $graph = "URL=\"".$url."\" tooltip=\"".getdropdownname("glpi_groups",$device["groups_id"])."\"";
        else
          $graph = "<a href='".$url."'>".getdropdownname("glpi_groups",$device["groups_id"])."</a>";
      }elseif (!$device["users_id"] && !$device["groups_id"] && $device["contact"]){
        if ($generation)
          $graph = "URL=\"".$url."\" tooltip=\"".$device["contact"]."\"";
        else
          $graph = "<a href='".$url."'>".$device["contact"]."</a>";
      }else{
        if ($generation)
          $graph = "URL=\"".$url."\" tooltip=\"".$device["name"]."\"";
        else
          $graph = "<a href='".$url."'>".$device["name"]."</a>";
      }
    return $graph;

  }

  function test($type,$ID) {
    
    global $DB,$CFG_GLPI,$LANG,$LINK_ID_TABLE,$INFOFORM_PAGES;
    
    $PluginArchires=new PluginArchires();
    $object=$PluginArchires->getClassType($type);
    
    $obj=new $object();
    $obj->getFromDB($ID);
    $views_id=$obj->fields["views_id"];
    
    $PluginArchiresView=new PluginArchiresView;
    $PluginArchiresQueryAppliance=new PluginArchiresQueryAppliance;
    $PluginArchiresQueryLocation=new PluginArchiresQueryLocation;
    $PluginArchiresQueryNetworkEquipment=new PluginArchiresQueryNetworkEquipment;
    $PluginArchiresStateColor=new PluginArchiresStateColor;
    $PluginArchiresItemImage=new PluginArchiresItemImage;
    
    $PluginArchiresView->getFromDB($views_id);

     $devices = array();
     $ports = array();

    echo "<br><div align='center'>";
    echo "<table class='tab_cadrehov' border='0' cellpadding='2' width='75%'>";
    echo "<tr><th colspan='6'>".$LANG['plugin_archires']['test'][2]."</th></tr>";
    echo "<tr><th>".$LANG['plugin_archires']['test'][4]."</th>";
    echo "<th>".$LANG['plugin_archires']['test'][5]."</th>";
    echo "<th>".$LANG['plugin_archires']['test'][6]."</th>";
    echo "<th>".$LANG['plugin_archires']['test'][7]."</th>";
    echo "<th>".$LANG['plugin_archires']['test'][8]."</th>";
    echo "<th>".$LANG['plugin_archires']['test'][9]."</th></tr>";

     if ($type==PLUGIN_ARCHIRES_LOCATIONS_QUERY){
      $devices=$PluginArchiresQueryLocation->Query($ID,$PluginArchiresView,true);
      $ports=$PluginArchiresQueryLocation->Query($ID,$PluginArchiresView,false);
     }elseif ($type==PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY){
      $devices=$PluginArchiresQueryNetworkEquipment->Query($ID,$PluginArchiresView,true);
      $ports=$PluginArchiresQueryNetworkEquipment->Query($ID,$PluginArchiresView,false);
     }elseif ($type==PLUGIN_ARCHIRES_APPLIANCES_QUERY){
      $devices=$PluginArchiresQueryAppliance->Query($ID,$PluginArchiresView,true);
      $ports=$PluginArchiresQueryAppliance->Query($ID,$PluginArchiresView,false);
    }
    

    foreach ($devices as $itemtype => $typed_devices){

      foreach ($typed_devices as $device_id => $device){

        $device_unique_name = $itemtype . "_" . $device_id . "_";
        $device_unique_name .= $device["name"];

        $image_name = $PluginArchiresItemImage->displayItemImage($device["type"],$itemtype,true);

        $url = $CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[$itemtype]."?id=".$device_id;

        echo "<tr class='tab_bg_1'>";
        echo "<td>$device_unique_name</td>";
        echo "<td><div align='center'><img src='$image_name' alt='$image_name'></div></td>";
        echo "<td>" . $device["name"];
        echo "</td>";
        
        echo "<td>";
        echo $this->displayTypeAndIP($PluginArchiresView,$itemtype,$device,false);
        echo  "</td>";
        
        echo  "<td>";
        if ($PluginArchiresView->fields["display_state"]!=0 && isset($device["states_id"]) ){
          echo $PluginArchiresStateColor->displayColorState($device);
        }
        echo  "</td>";
        
        echo  "<td>";
        echo $this->displayUsers($url,$device,false);
        echo  "</td>";
        
        echo  "</tr>";
      }
    }

    echo "</table>";

    echo "<br><table class='tab_cadrehov' border='0' cellpadding='2' width='75%'>";
    echo "<tr><th colspan='6'>".$LANG['plugin_archires']['test'][3]."</th></tr>";
    echo "<tr><th>".$LANG['plugin_archires']['test'][10]."</th>";
    echo "<th>".$LANG['plugin_archires']['test'][11]."</th>";
    echo "<th>".$LANG['plugin_archires']['test'][12]."</th>";
    echo "<th>".$LANG['plugin_archires']['test'][5]."</th>";
    echo "<th>".$LANG['plugin_archires']['test'][13]."</th>";
    echo "<th>".$LANG['plugin_archires']['test'][14]."</th></tr>";

    $wires = array();

    $query = "SELECT `id`, `networkports_id_1`, `networkports_id_2`
    FROM `glpi_networkports_networkports`";

    if ($result = $DB->query($query)) {
      while ($data = $DB->fetch_array($result)) {
        $wires[$data["id"]]["networkports_id_1"] = $data["networkports_id_1"];
        $wires[$data["id"]]["networkports_id_2"] = $data["networkports_id_2"];
      }
    }

    foreach ($wires as $wire) {
      if (isset($ports[$wire["networkports_id_1"]]) && !empty($ports[$wire["networkports_id_1"]]) && isset($ports[$wire["networkports_id_2"]]) && !empty($ports[$wire["networkports_id_2"]])) {
        $items_id1 = $ports[$wire["networkports_id_1"]]["items_id"];
        $itemtype1 = $ports[$wire["networkports_id_1"]]["itemtype"];
        $logical_number1 = $ports[$wire["networkports_id_1"]]["logical_number"];
        $name1 = $ports[$wire["networkports_id_1"]]["namep"];
        $ID1 = $ports[$wire["networkports_id_1"]]["idp"];
        $networkinterfaces_id1 = $ports[$wire["networkports_id_1"]]["networkinterfaces_id"];
        $ip1 = $ports[$wire["networkports_id_1"]]["ip"];
        $device_unique_name1 = $itemtype1 . "_" . $items_id1 . "_";
        $device_unique_name1 .= $devices[$itemtype1][$items_id1]["name"];

        $items_id2 = $ports[$wire["networkports_id_2"]]["items_id"];
        $itemtype2 = $ports[$wire["networkports_id_2"]]["itemtype"];
        $logical_number2 = $ports[$wire["networkports_id_2"]]["logical_number"];
        $name2 = $ports[$wire["networkports_id_2"]]["namep"];
        $ID2 = $ports[$wire["networkports_id_2"]]["idp"];
        $networkinterfaces_id2 = $ports[$wire["networkports_id_2"]]["networkinterfaces_id"];
        $ip2 = $ports[$wire["networkports_id_2"]]["ip"];
        $device_unique_name2 = $itemtype2 . "_" . $items_id2 . "_";
        $device_unique_name2 .= $devices[$itemtype2][$items_id2]["name"];


        echo "<tr class='tab_bg_1'>";

        if ($PluginArchiresView->fields["display_ports"]!=0 && $PluginArchiresView->fields["engine"]!=1){
          $url_ports = $CFG_GLPI["root_doc"] . "/front/networking.port.php?id=";
          echo  "<td>".$device_unique_name1;		
          echo  " -- " . $device_unique_name2 ."</td>";

          if ($PluginArchiresView->fields["display_ip"]!=0)
            echo  "<td>".$ip1."</td>";
          echo  "<td><a href='".$url_ports.$ID1."'>".$name1."</a> - ".$LANG['plugin_archires'][17]." ".$logical_number1."</td>";   
          echo  "<td><div align='center'><img src= \"../pics/socket.png\" alt='../pics/socket.png' /></div></td>";
          echo  "<td><a href='".$url_ports.$ID2."'>".$name2."</a> - ".$LANG['plugin_archires'][17]." ".$logical_number2."</td>";
          if ($PluginArchiresView->fields["display_ip"]!=0)
            echo  "<td>".$ip2."</td>";

        }else{

          echo  "<td>".$device_unique_name1;
          echo  " -- ".$device_unique_name2 ."</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
        }
      }
    }		
    echo "</tr></table>";

    echo "<br><table class='tab_cadre' border='0' cellpadding='2'>";
    echo "<tr><th>".$LANG['plugin_archires']['test'][1]."</th></tr>";
    echo "<tr class='tab_bg_1'><td>";
    echo "<img src=\"../test.php\" alt=\"\">";
    echo "</td></tr>";
    echo "</table>";

    echo "</div>";
  }

  function graphItems($device,$device_id,$itemtype,$format,$image_name,$url,$PluginArchiresView) {
    
    global $DB;
    
    $PluginArchiresStateColor=new PluginArchiresStateColor;
    
    $device_unique_name = $itemtype . "_" . $device_id . "_";
    $device_unique_name .= $device["name"];
        
    $graph = "\"".$device_unique_name."\"[shape=plaintext, label=";
    //label
    $graph .= "<<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
    
    //img
    if ($format!='svg'){
      $graph .= "<tr><td><img src=\"".$image_name."\"/></td></tr>";
    }else{
      $graph .= "<tr><td><img src=\"".realpath(GLPI_ROOT)."/plugins/archires/".$image_name. "\"/></td></tr>";
    }
    $graph .= "<tr><td> </td></tr><tr><td>".$device["name"];
    //ip / type
    $graph .= $this->displayTypeAndIP($PluginArchiresView,$itemtype,$device,true);
    //entity
    if ($PluginArchiresView->fields["display_entity"]!=0 && isset($device["entity"])){
      $graph .="<tr><td>".$this->CleanField(getDropdownName("glpi_entities",$device["entity"]))."</td></tr>";
    }
    //location
    if ($PluginArchiresView->fields["display_location"]!=0 && isset($device["locations_id"])){
      $graph .="<tr><td>".$this->CleanField(getDropdownName("glpi_locations",$device["locations_id"]))."</td></tr>";
    }

    //state
    if ($PluginArchiresView->fields["display_state"]!=0 && isset($device["states_id"]) ){

      $graph .="<tr><td>".$PluginArchiresStateColor->displayColorState($device)."</td></tr>";
    }
    
    $graph .= "</table>>";
    //end label
    
    //link - users
    $graph .=$this->displayUsers($url,$device,true);

    $graph .="];\n";
    
    return $graph;
  }

  function graphPorts($devices,$ports,$wire,$format,$PluginArchiresView) {
    
    global $DB,$CFG_GLPI,$LANG;
    
    $PluginArchiresNetworkInterfaceColor=new PluginArchiresNetworkInterfaceColor();
    $PluginArchiresVlanColor=new PluginArchiresVlanColor();
    
    $items_id1 = $ports[$wire["networkports_id_1"]]["items_id"];
    $itemtype1 = $ports[$wire["networkports_id_1"]]["itemtype"];
    $logical_number1 = $ports[$wire["networkports_id_1"]]["logical_number"];
    $name1 = $ports[$wire["networkports_id_1"]]["namep"];
    $ID1 = $ports[$wire["networkports_id_1"]]["idp"];
    $networkinterfaces_id1 = $ports[$wire["networkports_id_1"]]["networkinterfaces_id"];
    $ip1 = $ports[$wire["networkports_id_1"]]["ip"];
    $netmask1 = $ports[$wire["networkports_id_2"]]["netmask"];
    $device_unique_name1 = $itemtype1 . "_" . $items_id1 . "_";
    $device_unique_name1 .= $devices[$itemtype1][$items_id1]["name"];

    $items_id2 = $ports[$wire["networkports_id_2"]]["items_id"];
    $itemtype2 = $ports[$wire["networkports_id_2"]]["itemtype"];
    $logical_number2 = $ports[$wire["networkports_id_2"]]["logical_number"];
    $name2 = $ports[$wire["networkports_id_2"]]["namep"];
    $ID2 = $ports[$wire["networkports_id_2"]]["idp"];
    $networkinterfaces_id2 = $ports[$wire["networkports_id_2"]]["networkinterfaces_id"];
    $ip2 = $ports[$wire["networkports_id_2"]]["ip"];
    $netmask2 = $ports[$wire["networkports_id_2"]]["netmask"];
    $device_unique_name2 = $itemtype2 . "_" . $items_id2 . "_";
    $device_unique_name2 .= $devices[$itemtype2][$items_id2]["name"];
    
    $graph="";

    if($PluginArchiresView->fields["color"] == PLUGIN_ARCHIRES_NETWORK_COLOR ) {
      if (empty($networkinterfaces_id1) && empty($networkinterfaces_id2)) {
        $graph .= "edge [color=black,arrowsize=1, fontname=\"Verdana\", fontsize=\"5\"];\n";
      }elseif (!empty($networkinterfaces_id1)){
        
        if ($PluginArchiresNetworkInterfaceColor->getFromDBbyNetworkInterface($networkinterfaces_id1)){
          $graph .= "edge [color=".$PluginArchiresNetworkInterfaceColor->fields["color"].", fontname=\"Verdana\", fontsize=\"5\"];\n";
        }else{
          $graph .= "edge [color=black,arrowsize=1, fontname=\"Verdana\", fontsize=\"5\"];\n";			
        }
      }else{
        if ($PluginArchiresNetworkInterfaceColor->getFromDBbyNetworkInterface($networkinterfaces_id2)){
          $graph .= "edge [color=".$PluginArchiresNetworkInterfaceColor->fields["color"].", fontname=\"Verdana\", fontsize=\"5\"];\n";
        }else{
          $graph .= "edge [color=black,arrowsize=1, fontname=\"Verdana\", fontsize=\"5\"];\n";			
        }
      }
    }elseif($PluginArchiresView->fields["color"] == PLUGIN_ARCHIRES_VLAN_COLOR ) {
      
      $vlan1=$PluginArchiresVlanColor->getVlanbyNetworkPort($ID1);
      
      $vlan2=$PluginArchiresVlanColor->getVlanbyNetworkPort($ID2);
      
      if (empty($vlan1) && empty($vlan2)) {
        $graph .= "edge [color=black,arrowsize=1, fontname=\"Verdana\", fontsize=\"5\"];\n";
      }elseif (!empty($vlan1)){
        
        if ($PluginArchiresVlanColor->getFromDBbyVlan($vlan1)){
          $graph .= "edge [color=".$PluginArchiresVlanColor->fields["color"].", fontname=\"Verdana\", fontsize=\"5\"];\n";
        }else{
          $graph .= "edge [color=black,arrowsize=1, fontname=\"Verdana\", fontsize=\"5\"];\n";			
        }
      }else{
        if ($PluginArchiresVlanColor->getFromDBbyVlan($vlan2)){
          $graph .= "edge [color=".$PluginArchiresVlanColor->fields["color"].", fontname=\"Verdana\", fontsize=\"5\"];\n";
        }else{
          $graph .= "edge [color=black,arrowsize=1, fontname=\"Verdana\", fontsize=\"5\"];\n";			
        }
      }	 
    }
    //Display Ports
    if ($PluginArchiresView->fields["display_ports"]!=0 && $PluginArchiresView->fields["engine"]!=1){
      $url_ports = $CFG_GLPI["root_doc"] . "/front/networking.port.php?id=";
      $graph .= "\"".$device_unique_name1."\"";		
      $graph .= " -- \"".$device_unique_name2."\"[label=";
      $graph .= "<<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\">";
      //display ip ports
      if ($PluginArchiresView->fields["display_ip"]!=0){

        if (!empty($ip1))
          $graph .= "<tr><td>".$ip1;

        if (!empty($ip1)&&!empty($netmask1))
          $graph .= "/".$netmask1."</td></tr>";
        elseif (!empty($ip1) && empty($netmask1))
          $graph .= "</td></tr>";
      }
      $graph .= "<tr><td HREF=\"".$url_ports.$ID1."\" tooltip=\"".$name1;
      if($_SESSION["glpiis_ids_visible"]||empty($name1)) $graph.= "_".$ID1."_";
      $graph .= "\">";
      
      if ($PluginArchiresView->fields["display_ports"]==1){
        $graph .= $LANG['plugin_archires'][17]." ".$logical_number1;
      }elseif ($PluginArchiresView->fields["display_ports"]==2){
        $graph .= $name1;
        if($_SESSION["glpiis_ids_visible"]||empty($name1)) $graph.= " (".$ID1.")";
      }
      $graph .= "</td></tr>";
      
      if ($format!='svg'){
        $graph .= "<tr><td><img src= \"pics/socket.png\" /></td></tr>";
      }else{
        $graph .= "<tr><td><img src=\"".realpath(GLPI_ROOT)."/plugins/archires/pics/socket.png\" /></td></tr>";
      }
      $graph .= "<tr><td HREF=\"".$url_ports.$ID2."\" tooltip=\"".$name2;
      if($_SESSION["glpiis_ids_visible"]||empty($name2)) $graph.= "_".$ID2."_";
      $graph .= "\">";
      
      if ($PluginArchiresView->fields["display_ports"]==1){
        $graph .= $LANG['plugin_archires'][17]." ".$logical_number2;
      }elseif ($PluginArchiresView->fields["display_ports"]==2){
        $graph .= $name2;
        if($_SESSION["glpiis_ids_visible"]||empty($name2)) $graph.= " (".$ID2.")";
      }
      $graph .= "</td></tr>";
      
      //display ip ports
      if ($PluginArchiresView->fields["display_ip"]!=0){

        if (!empty($ip2))
          $graph .= "<tr><td>".$ip2;

        if (!empty($ip2)&&!empty($netmask2))
          $graph .= "/".$netmask2."</td></tr>";
        elseif (!empty($ip2) && empty($netmask2))
          $graph .= "</td></tr>";
      }

      $graph .= "</table>>];\n";

    }else{
      $graph .= "\"".$device_unique_name1."\"";
      $graph .= " -- \"".$device_unique_name2."\";\n";
    }
    
    return $graph;
  }

  function createGraph($format,$type,$ID,$view=0) {
     global $DB,$CFG_GLPI,$LANG,$LINK_ID_TABLE,$INFOFORM_PAGES;
    
    $obj=new $type();
    $obj->getFromDB($ID);
    $views_id=$obj->fields["views_id"];

    $PluginArchiresView=new PluginArchiresView;
    $PluginArchiresQueryAppliance=new PluginArchiresQueryAppliance;
    $PluginArchiresQueryLocation=new PluginArchiresQueryLocation;
    $PluginArchiresQueryNetworkEquipment=new PluginArchiresQueryNetworkEquipment;
    $PluginArchiresItemImage=new PluginArchiresItemImage;
    
    if ($view!=0){
      $PluginArchiresView->getFromDB($view);
    }else{
      $PluginArchiresView->getFromDB($views_id);
    }
    $devices = array();
    $ports = array();
    
    if ($type->type==PLUGIN_ARCHIRES_LOCATIONS_QUERY){
      $devices=$PluginArchiresQueryLocation->Query($ID,$PluginArchiresView,true);
      $ports=$PluginArchiresQueryLocation->Query($ID,$PluginArchiresView,false);
     }elseif ($type->type==PLUGIN_ARCHIRES_NETWORKEQUIPMENTS_QUERY){
      $devices=$PluginArchiresQueryNetworkEquipment->Query($ID,$PluginArchiresView,true);
      $ports=$PluginArchiresQueryNetworkEquipment->Query($ID,$PluginArchiresView,false);
     }elseif ($type->type==PLUGIN_ARCHIRES_APPLIANCES_QUERY){
      $devices=$PluginArchiresQueryAppliance->Query($ID,$PluginArchiresView,true);
      $ports=$PluginArchiresQueryAppliance->Query($ID,$PluginArchiresView,false);
    }
    $wires = array();

    $query = "SELECT `id`, `networkports_id_1`, `networkports_id_2`
    FROM `glpi_networkports_networkports`";

    if ($result = $DB->query($query)) {
      while ($data = $DB->fetch_array($result)) {
        $wires[$data["id"]]["networkports_id_1"] = $data["networkports_id_1"];
        $wires[$data["id"]]["networkports_id_2"] = $data["networkports_id_2"];
      }
    }

    $graph = "graph G {\n";
    $graph .= "overlap=false;\n";

    $graph .= "bgcolor=white;\n";

    //items
    $graph .= "node [shape=polygon, sides=6, fontname=\"Verdana\", fontsize=\"5\"];\n";

    foreach ($devices as $itemtype => $typed_devices){

      foreach ($typed_devices as $device_id => $device){

        $image_name = $PluginArchiresItemImage->displayItemImage($device["type"],$itemtype,false);

        $url = $CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[$itemtype]."?id=".$device_id;
        
        $graph.=$this->graphItems($device,$device_id,$itemtype,$format,$image_name,$url,$PluginArchiresView);
        
      }
    }
      
    foreach ($wires as $wire) {
      if (!empty($ports[$wire["networkports_id_1"]]) && !empty($ports[$wire["networkports_id_2"]]) && isset($ports[$wire["networkports_id_1"]]) && isset($ports[$wire["networkports_id_2"]]) ) {
         $graph.=$this->graphPorts($devices,$ports,$wire,$format,$PluginArchiresView);
      }
    }

    $graph .= "}\n";

    return $this->generateGraphviz($graph,$format,$PluginArchiresView);
  }


  function generateGraphviz($graph,$format,$PluginArchiresView) {
    
    $Path = GLPI_PLUGIN_DOC_DIR."/archires";
    $graph_name = tempnam($Path, "");
    $output_name = tempnam($Path, "");
    
    if ($graph_file = fopen($graph_name, "w")) {
      fputs($graph_file, $graph);
      fclose($graph_file);

      if ($PluginArchiresView->fields["engine"]!=0) $engine_archires="neato";
      else $engine_archires="dot";

      //$command = $engine_archires." -T".$format." -o ".$output_name." ".$graph_name;
      $command = $engine_archires." -T" .$format." -o \"".$output_name ."\" \"".$graph_name."\"";
      `$command`;

      unlink($graph_name);

      if ($output_file = fopen($output_name, "rb")) {
        $output_data = fread($output_file, filesize($output_name));
        fclose($output_file);
        unlink($output_name);

        return $output_data;
      }
    }
  }
}