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

class PluginArchiresView extends CommonDBTM {

	public $table = 'glpi_plugin_archires_views';
   public $type = "PluginArchiresView";
   
   function dropdownObject($obj) {
      global $LANG,$DB,$CFG_GLPI;
      
      $ID=$obj->fields["id"];
      
      $query = "SELECT `id`, `name`
               FROM `".$obj->table."`
               WHERE `is_deleted` = '0' ";
      // Add Restrict to current entities
      if (in_array($obj->table,$CFG_GLPI["specif_entities_tables"])) {
         $LINK= " AND " ;
         $query.=getEntitiesRestrictRequest($LINK,$obj->table);
      }
      $query.=" ORDER BY `name` ASC";

      if ($result = $DB->query($query)) {

         if ($DB->numrows($result) >0) {

            echo "<select name=\"queries_id\" size=\"1\"> ";
            while($ligne= mysql_fetch_array($result)) {
               echo "<option value='".$ligne["id"]."' ".($ligne["id"]=="".$ID.""?" selected ":"").">".$ligne["name"]."</option>";
            }
            echo "</select>";
         }
      }
	}
	
	function dropdownView($obj,$default) {
      global $DB,$CFG_GLPI;
    
      if (isset($obj->fields["id"])) {
         $default=$obj->fields["views_id"];
      }
      $query = "SELECT `id`, `name` 
        FROM `".$this->table."` 
        WHERE `is_deleted` = '0' 
        AND `entities_id` = '" . $_SESSION["glpiactive_entity"] . "' 
        ORDER BY `name` ASC";
      echo "<select name='views_id' size=\"1\"> ";
      echo "<option value='0'>-----</option>\n";
      if ($result = $DB->query($query)) {
         while($ligne= mysql_fetch_array($result)) {
            $view_name=$ligne["name"];
            $view_id=$ligne["id"];
            echo "<option value='".$view_id."' ".($view_id=="".$default.""?" selected ":"").">".$view_name."</option>";
         } 
      } 
      echo "</select>";
   }
	
	function linkToAllViews($type,$ID) {
      global $LANG;
      echo "<div align='center'>";
      echo "<a href=\"./archires.graph.php?id=".$ID."&querytype=".$type."\">".$LANG['plugin_archires'][1]."</a>";
      echo "</div>";
   }
   
   function viewSelect($obj,$views_id,$select=0) {
      global $CFG_GLPI,$LANG,$DB;
      
      $querytype=$obj->type;
      $ID=$obj->fields["id"];
      $object_view=$obj->fields["views_id"];
      if (!isset($views_id)) $views_id = $object_view;
      
      if ($select) {
         echo "<form method='get' name='selecting' action='".$CFG_GLPI["root_doc"]."/plugins/archires/front/archires.graph.php'>";
         echo "<table class='tab_cadre' cellpadding='5'>";
         echo "<tr class='tab_bg_1'>";
         
         echo "<td class='center'>";
         echo $LANG['plugin_archires'][0]." : ";    
         $this->dropdownObject($obj);
         echo "</td>";
         
         echo "<td class='center'>";
         echo $LANG['plugin_archires']['title'][3]." : ";
         $this->dropdownView(-1,$views_id);      
         echo "</td>";
         
         echo "<td>";
         echo "<input type='hidden' name='querytype' value=\"".$querytype."\"> ";
         echo "<input type='submit' class='submit'  name='displayview' value=\"".$LANG['buttons'][2]."\"> ";
         echo "</td>";
         echo "</tr>";
         echo "</table>";
         echo "</form>";
      }
      
      if ($views_id)
         echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/archires/front/archires.map.php?format=".PLUGIN_ARCHIRES_SVG_FORMAT."&amp;id=".$ID."&amp;querytype=".$querytype."&amp;views_id=".$views_id."'>".$LANG['plugin_archires']['setup'][16]."</a>";
      else
         echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/archires/front/archires.map.php?format=".PLUGIN_ARCHIRES_SVG_FORMAT."&amp;id=".$ID."&amp;querytype=".$querytype."&amp;views_id=".$vue_id."'>".$LANG['plugin_archires']['setup'][16]."</a>";

   }
  
	function defineTabs($ID,$withtemplate) {
		global $LANG;
		
		$ong[1]=$LANG['title'][26];

		return $ong;
	}

	function showForm ($target,$ID, $withtemplate='') {
    global $CFG_GLPI,$DB,$LANG;

		if (!plugin_archires_haveRight("archires","r")) return false;

		$con_spotted=false;

		if (empty($ID) ||$ID==-1) {

			if ($this->getEmpty()) $con_spotted = true;
			$use_cache=false;
		} else {
			if ($this->getfromDB($ID)&&haveAccessToEntity($this->fields["entities_id"])) $con_spotted = true;
		}

		if ($con_spotted) {
      
         $canedit=$this->can($ID,'w');
         $canrecu=$this->can($ID,'recursive');
            
         $this->showTabs($ID, $withtemplate,$_SESSION['glpi_tab']);
         $this->showFormHeader($target,$ID, $withtemplate,2);
            
         echo "<tr class='tab_bg_1 top'><td colspan='1'>".$LANG['plugin_archires']['search'][1].":	</td>";
         echo "<td colspan='3'>";
         autocompletionTextField("name","glpi_plugin_archires_views","name",$this->fields["name"],20,$this->fields["entities_id"]);

         echo "</td></tr>";

         echo "<tr class='tab_bg_1 top'><th colspan='4'>".$LANG['plugin_archires'][3]."</th></tr>";

         echo "<tr class='tab_bg_1 top'><td>".$LANG['plugin_archires'][6].": </td>";
         echo "<td>";
         dropdownyesno("computer",$this->fields["computer"]);
         echo "</td>";

         echo "<td>".$LANG['plugin_archires'][7].": </td>";
         echo "<td>";
         dropdownyesno("networking",$this->fields["networking"]);
         echo "</td></tr>";

         echo "<tr class='tab_bg_1 top'><td>".$LANG['plugin_archires'][8].": </td>";
         echo "<td>";
         dropdownyesno("printer",$this->fields["printer"]);
         echo "</td>";

         echo "<td>".$LANG['plugin_archires'][9].": </td>";
         echo "<td>";
         dropdownyesno("peripheral",$this->fields["peripheral"]);
         echo "</td></tr>";

         echo "<tr class='tab_bg_1 top'><td>".$LANG['plugin_archires'][10].": </td>";
         echo "<td>";
         dropdownyesno("phone",$this->fields["phone"]);
         echo "</td><td></td><td></td></tr>";

         echo "<tr class='tab_bg_1 top'><th colspan='4'>".$LANG['plugin_archires'][24]."</th></tr>";

         echo "<tr class='tab_bg_1 top'><td>".$LANG['plugin_archires'][16].": </td>";
         echo "<td>";
         echo "<select name=\"display_ports\" size=\"1\"> ";
         echo "<option ";
         if ($this->fields["display_ports"]=='0') echo "selected ";
         echo "value=\"0\">".$LANG['choice'][0]."</option>";
         echo "<option ";
         if ($this->fields["display_ports"]=='1') echo "selected ";
         echo "value=\"1\">".$LANG['plugin_archires'][29]."</option>";
         echo "<option ";
         if ($this->fields["display_ports"]=='2') echo "selected ";
         echo "value=\"2\">".$LANG['plugin_archires'][33]."</option>";
         echo "</select> ";
         echo "</td>";

         echo "<td>".$LANG['plugin_archires'][23].": </td>";
         echo "<td>";
         dropdownyesno("display_ip",$this->fields["display_ip"]);
         echo "</td></tr>";

         echo "<tr class='tab_bg_1 top'><td>".$LANG['plugin_archires'][25].": </td>";
         echo "<td>";
         dropdownyesno("display_type",$this->fields["display_type"]);
         echo "</td>";

         echo "<td>".$LANG['plugin_archires'][26].": </td>";
         echo "<td>";
         dropdownyesno("display_state",$this->fields["display_state"]);
         echo "</td></tr>";

         echo "<tr class='tab_bg_1 top'><td>".$LANG['plugin_archires'][31].": </td>";
         echo "<td>";
         dropdownyesno("display_location",$this->fields["display_location"]);
         echo "</td>";

         echo "<td>".$LANG['plugin_archires'][32].": </td>";
         echo "<td>";
         dropdownyesno("display_entity",$this->fields["display_entity"]);
         echo "</td></tr>";

         echo "<tr class='tab_bg_1 top'><th colspan='4'>".$LANG['plugin_archires']['search'][6]."</th></tr>";

         echo "<tr class='tab_bg_1 top'><td>".$LANG['plugin_archires']['setup'][13].": </td>";
         echo "<td>";
         echo "<select name=\"engine\" size=\"1\"> ";
         echo "<option ";
         if ($this->fields["engine"]=='0') echo "selected ";
         echo "value=\"0\">Dot</option>";
         echo "<option ";
         if ($this->fields["engine"]=='1') echo "selected ";
         echo "value=\"1\">Neato</option>";
         echo "</select> ";
         echo " <img alt='' src='".$CFG_GLPI["root_doc"]."/pics/aide.png' onmouseout=\"cleanhide('comments')\" onmouseover=\"cleandisplay('comments')\">";
         echo "<span class='over_link' id='comments'>".nl2br($LANG['plugin_archires']['setup'][14])."</span>";
         echo "</td>";

         echo "<td>".$LANG['plugin_archires']['setup'][15].": </td>";
         echo "<td>";
         echo "<select name=\"format\" size=\"1\"> ";
         echo "<option ";
         if ($this->fields["format"]==PLUGIN_ARCHIRES_JPEG_FORMAT) echo "selected ";
         echo "value=\"0\">jpeg</option>";
         echo "<option ";
         if ($this->fields["format"]==PLUGIN_ARCHIRES_PNG_FORMAT) echo "selected ";
         echo "value=\"1\">png</option>";
         echo "<option ";
         if ($this->fields["format"]==PLUGIN_ARCHIRES_GIF_FORMAT) echo "selected ";
         echo "value=\"2\">gif</option>";
         echo "</select>";
         echo "</td>";

         echo "<tr class='tab_bg_1 top'><td>".$LANG['plugin_archires']['setup'][25].": </td>";
         echo "<td>";
         echo "<select name=\"color\" size=\"1\"> ";
         echo "<option ";
         if ($this->fields["color"]=='0') echo "selected ";
         echo "value=\"0\">".$LANG['plugin_archires'][19]."</option>";
         echo "<option ";
         if ($this->fields["color"]=='1') echo "selected ";
         echo "value=\"1\">".$LANG['plugin_archires'][35]."</option>";
         echo "</select>";
         echo "</td><td colspan='2'></td></tr>";

         $this->showFormButtons($ID,$withtemplate,2);
         echo "<div id='tabcontent'></div>";
         echo "<script type='text/javascript'>loadDefaultTab();</script>";

      } else {
         echo "<div align='center'><b>".$LANG['plugin_archires']['search'][7]."</b></div>";
         return false;
      }
      return true;
	}
	
	// Print Search Form
	function searchForm($params) {
      global $CFG_GLPI,$LANG;
      
      // Default values of parameters
      $default_values["link"]=array();
      $default_values["field"]=array();
      $default_values["contains"]=array();
      $default_values["link1"]=array();
      $default_values["field1"]=array();
      $default_values["contains1"]=array();
      $default_values["sort"]="";
      $default_values["is_deleted"]=0;
      $default_values["link2"]="";
      $default_values["contains2"]="";
      $default_values["field2"]="";
      $default_values["itemtype2"]="";
      $default_values["target"] = $_SERVER['PHP_SELF'];
      
      foreach ($default_values as $key => $val) {
         if (isset($params[$key])) {
            $$key=$params[$key];
         } else {
            $$key=$default_values[$key];
         }
      }
      
      $option[$this->table.".id"]	= $LANG['plugin_archires']['search'][0];
      $option[$this->table.".name"]	= $LANG['plugin_archires']['search'][1];

      echo "<form method='get' action=\"./view.php\">";
      echo "<div align='center'><table  width='750' class='tab_cadre'>";
      echo "<tr><th colspan='4'>".$LANG['search'][0].":</th></tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td class='center'>";
      echo "<input type='text' size='15' name=\"contains\" value=\"". $contains ."\" >";
      echo "&nbsp;";
      echo $LANG['search'][10]."&nbsp;<select name=\"field\" size='1'>";
      echo "<option value='all' ";
      if ($field == "all") echo "selected";
      echo ">".$LANG['common'][66]."</option>";
      reset($option);
      foreach ($option as $key => $val) {
         echo "<option value=\"".$key."\"";
         if ($key == $field) echo "selected";
         echo ">". substr($val, 0, 18) ."</option>\n";
      }
      echo "</select>&nbsp;";

      echo $LANG['search'][4];
      echo "&nbsp;<select name='sort' size='1'>";
      reset($option);
      foreach ($option as $key => $val) {
         echo "<option value=\"".$key."\"";
         if ($key == $sort) echo "selected";
         echo ">".$val."</option>\n";
      }
      echo "</select> ";

      echo "<td>";
      dropdownyesno("is_deleted",$is_deleted);

      echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/showdeleted.png\" alt='".$LANG['common'][3]."' title='".$LANG['common'][3]."'>";

      echo "</td>";
      // Display Reset search
      echo "<td class='center'>";
      echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/archires/front/view.php?reset_search=reset_search&amp;type=".$this->type."'><img title=\"".$LANG['buttons'][16]."\" alt=\"".$LANG['buttons'][16]."\" src='".$CFG_GLPI["root_doc"]."/pics/reset.png' class='calendrier'></a>";
      Bookmark::showSaveButton(BOOKMARK_SEARCH,$this->type);

      echo "</td>";

      echo "<td width='80' class='center' class='tab_bg_2'>";
      echo "<input type='submit' value=\"".$LANG['buttons'][0]."\" class='submit'>";
      echo "</td></tr></table></div></form>";
   }


   /**
   * Search and list sup
   *
   *
   * Build the query, make the search and list contacts after a search.
   *
   *@param $target filename where to go when done.
   *@param $username not used to be deleted.
   *@param $field the field in witch the search would be done
   *@param $contains the search string
   *@param $sort the "sort by" field value
   *@param $order ASC or DSC (for mysql query)
   *@param $start row number from witch we start the query (limit $start,xxx)
   *@param $is_deleted Query on deleted items or not.
   *@param $phrasetype='' not used (to be deleted)
   *
   *@return Nothing (display)
   *
   **/
  function showList($params) {
      global $DB,$CFG_GLPI,$LANG;
      
      // Default values of parameters
      $default_values["link"]=array();
      $default_values["field"]=array();
      $default_values["contains"]=array();
      $default_values["link1"]=array();
      $default_values["field1"]=array();
      $default_values["contains1"]=array();
      $default_values["phrasetype"]="contains";
      $default_values["sort"]="1";
      $default_values["order"]="ASC";
      $default_values["start"]=0;
      $default_values["is_deleted"]=0;
      $default_values["export_all"]=0;
      $default_values["link2"]="";
      $default_values["contains2"]="";
      $default_values["field2"]="";
      $default_values["itemtype2"]="";
      $default_values["target"] = $_SERVER['PHP_SELF'];
      
      foreach ($default_values as $key => $val) {
         if (isset($params[$key])) {
            $$key=$params[$key];
         } else {
            $$key=$default_values[$key];
         }
      }
      
      $first=true;
      // Build query
      if ($field=="all") {
         $where = " (";
         $fields = $DB->list_fields($this->table);
         $columns = count($fields);
         $i=0;
         foreach ($fields as $key => $val) {
            if ($i != 0) {
               $where .= " OR ";
            }

            $where .= $this->table.".".$key . " LIKE '%".$contains."%'";

            $i++;
         }
         $where .= ")";
      } else {
         if ($phrasetype == "contains") {
            $where = "($field LIKE '%".$contains."%')";
         } else {
            $where = "($field LIKE '".$contains."')";
         }
      }

      if (!$start) {
         $start = 0;
      }
      if (!$order) {
         $order = "ASC";
      }
      $query = "SELECT *
        FROM `".$this->table."` ";
      $query .= "WHERE ";
      if (!empty($where)) $query .= " $where AND";

      $itemtable=$this->table;

      // Add deleted if item have it
      if (in_array($itemtable,$CFG_GLPI["deleted_tables"])) {
         $LINK= " AND " ;
         if ($first) {$LINK=" ";$first=false;}
         $query.= $LINK.$itemtable.".`is_deleted` = '$is_deleted' ";
      }
      // Remove template items
      if (in_array($itemtable,$CFG_GLPI["template_tables"])) {
         $LINK= " AND " ;
         if ($first) {$LINK=" ";$first=false;}
         $query.= $LINK.$itemtable.".`is_template` = '0' ";
      }

      // Add Restrict to current entities
      if (in_array($itemtable,$CFG_GLPI["specif_entities_tables"])) {
         $LINK= " AND " ;
         if ($first) {$LINK=" ";$first=false;}

         $query.=getEntitiesRestrictRequest($LINK,$itemtable);
      }

      $query .= " ORDER BY $sort $order";
      // Get it from database
      if ($result = $DB->query($query)) {
         $numrows =  $DB->numrows($result);

         // Limit the result, if no limit applies, use prior result
         if ($numrows > $_SESSION["glpilist_limit"]) {
            $query_limit = $query ." LIMIT $start,".$_SESSION["glpilist_limit"]." ";
            $result_limit = $DB->query($query_limit);
            $numrows_limit = $DB->numrows($result_limit);
         } else {
            $numrows_limit = $numrows;
            $result_limit = $result;
         }

         if ($numrows_limit>0) {
            // Pager
            $parameters="field=$field&amp;phrasetype=$phrasetype&amp;contains=$contains&amp;sort=$sort&amp;order=$order";
            printPager($start,$numrows,$target,$parameters);

            echo "<form method='post' name='massiveaction_form' id='massiveaction_form' action=\"../ajax/massiveactionViews.php\">";
            // Produce headline
            echo "<div align='center'><table  class='tab_cadrehov'><tr>";
            // Name
            if (plugin_archires_haveRight("archires","w"))
               echo "<th></th>";

            echo "<th>";
            if ($sort==$this->table.".name") {
               if ($order=="DESC") echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/puce-down.png\" alt='' title=''>";
               else echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/puce-up.png\" alt='' title=''>";
            }
            echo "<a href=\"$target?field=$field&amp;phrasetype=$phrasetype&amp;contains=$contains&amp;sort=".$this->table.".name&amp;order=".($order=="ASC"?"DESC":"ASC")."&amp;start=$start\">";
            echo $LANG['plugin_archires']['search'][1]."</a></th>";

            //items
            echo "<th>";
            echo $LANG['plugin_archires'][3]."</th>";

            //display_ports
            echo "<th>";
            echo $LANG['plugin_archires'][24]."</th>";

            //engine
            echo "<th>";
            if ($sort==$this->table.".engine") {
               if ($order=="DESC") echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/puce-down.png\" alt='' title=''>";
               else echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/puce-up.png\" alt='' title=''>";
            }
            echo "<a href=\"$target?field=$field&amp;phrasetype=$phrasetype&amp;contains=$contains&amp;sort=".$this->table.".engine&amp;order=".($order=="ASC"?"DESC":"ASC")."&amp;start=$start\">";
            echo $LANG['plugin_archires']['setup'][13]."</a></th>";

            //format
            echo "<th>";
            if ($sort==$this->table.".format") {
               if ($order=="DESC") echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/puce-down.png\" alt='' title=''>";
               else echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/puce-up.png\" alt='' title=''>";
            }
            echo "<a href=\"$target?field=$field&amp;phrasetype=$phrasetype&amp;contains=$contains&amp;sort=".$this->table.".format&amp;order=".($order=="ASC"?"DESC":"ASC")."&amp;start=$start\">";
            echo $LANG['plugin_archires']['setup'][15]."</a></th>";

            echo "</tr>";

            for ($i=0; $i < $numrows_limit; $i++) {

               $ID = $DB->result($result_limit, $i, "id");

               $this->getfromDB($ID);

               $sel="";
               if (isset($_GET["select"])&&$_GET["select"]=="all") $sel="checked";

               echo displaySearchNewLine(HTML_OUTPUT,$i%2);
               //	echo "<td>";

               if (plugin_archires_haveRight("archires","w"))
                  echo "<td width='5'><input type='checkbox' name='item[$ID]' value='1' $sel></td>";
               echo "<td>";
               echo "<a href=\"./view.form.php?id=$ID\">";
               echo $this->fields["name"]."";
               if ($_SESSION["glpiis_ids_visible"] == 1 ||empty($this->fields["name"])) {
                  echo " (";
                  echo $this->fields["id"].")";
               }
               echo "</a></td>";

               echo "<td class='center'>";
               if ($this->fields["computer"]!=0) echo $LANG['plugin_archires'][6]." : ".$LANG['choice'][1];
               else
                  echo $LANG['plugin_archires'][6]." : ".$LANG['choice'][0];
               echo "<br>";

               if ($this->fields["networking"]!=0) echo $LANG['plugin_archires'][7]." : ".$LANG['choice'][1];
               else
                  echo $LANG['plugin_archires'][7]." : ".$LANG['choice'][0];
               echo "<br>";

               if ($this->fields["printer"]!=0) echo $LANG['plugin_archires'][8]." : ".$LANG['choice'][1];
               else
                  echo $LANG['plugin_archires'][8]." : ".$LANG['choice'][0];
               echo "<br>";

               if ($this->fields["peripheral"]!=0) echo $LANG['plugin_archires'][9]." : ".$LANG['choice'][1];
               else
                  echo $LANG['plugin_archires'][9]." : ".$LANG['choice'][0];
               echo "<br>";

               if ($this->fields["phone"]!=0) echo $LANG['plugin_archires'][10]." : ".$LANG['choice'][1];
               else
                  echo $LANG['plugin_archires'][10]." : ".$LANG['choice'][0];
               echo "</td>";

               echo "<td class='center'>";
               if ($this->fields["display_ports"]!=0) echo $LANG['plugin_archires'][16]." : ".$LANG['choice'][1];
               else
                  echo $LANG['plugin_archires'][16]." : ".$LANG['choice'][0];
               echo "<br>";

               if ($this->fields["display_ip"]!=0) echo $LANG['plugin_archires'][23]." : ".$LANG['choice'][1];
               else
                  echo $LANG['plugin_archires'][23]." : ".$LANG['choice'][0];
               echo "<br>";

               if ($this->fields["display_type"]!=0) echo $LANG['plugin_archires'][25]." : ".$LANG['choice'][1];
               else
                  echo $LANG['plugin_archires'][25]." : ".$LANG['choice'][0];
               echo "<br>";

               if ($this->fields["display_state"]!=0) echo $LANG['plugin_archires'][26]." : ".$LANG['choice'][1];
               else
                  echo $LANG['plugin_archires'][26]." : ".$LANG['choice'][0];
               echo "<br>";

               if ($this->fields["display_location"]!=0) echo $LANG['plugin_archires'][31]." : ".$LANG['choice'][1];
               else
                  echo $LANG['plugin_archires'][31]." : ".$LANG['choice'][0];
               echo "<br>";

               if ($this->fields["display_entity"]!=0) echo $LANG['plugin_archires'][32]." : ".$LANG['choice'][1];
               else
                  echo $LANG['plugin_archires'][32]." : ".$LANG['choice'][0];
               echo "</td>";

               echo "<td class='center'>";
               if ($this->fields["engine"]!=0) echo "Neato";
               else
                  echo "Dot";
               echo "</td>";

               echo "<td class='center'>";
               if ($this->fields["format"]==PLUGIN_ARCHIRES_JPEG_FORMAT) $format_graph="jpeg";
               else if ($this->fields["format"]==PLUGIN_ARCHIRES_PNG_FORMAT) $format_graph="png";
               else if ($this->fields["format"]==PLUGIN_ARCHIRES_GIF_FORMAT) $format_graph="gif";
               echo $format_graph;
               echo "</td>";

               echo "</tr>";
            }

            // Close Table
            echo "</table></div>";
            //massive action
            if (plugin_archires_haveRight("archires","w")) {
               openArrowMassive("massiveaction_form");
               $this->dropdownMassiveAction($ID,$is_deleted);
               closeArrowMassive();
            }
            echo "</div></form>";

            // Pager
            echo "<br>";
            printPager($start,$numrows,$target,$parameters);

         } else {
            echo "<div align='center'><b>".$LANG['plugin_archires']['search'][7]."</b></div>";
         }
      }
   }
  
   function dropdownMassiveAction($ID,$is_deleted) {
      global $LANG,$CFG_GLPI;

      echo "<select name=\"massiveaction\" id='massiveaction'>";
      echo "<option value=\"-1\" selected>-----</option>";
      if (plugin_archires_haveRight("archires","w")) {
         if ($is_deleted=="1") {
            echo "<option value=\"purge\">".$LANG['buttons'][22]."</option>";
            echo "<option value=\"restore\">".$LANG['buttons'][21]."</option>";
        
         } else {
            echo "<option value=\"duplicate\">".$LANG['plugin_archires'][28]."</option>";
            echo "<option value=\"delete\">".$LANG['buttons'][6]."</option>";
            echo "<option value=\"transfert\">".$LANG['buttons'][48]."</option>";
         }
      }
      echo "</select>";
    
      $params=array('action'=>'__VALUE__',
        'is_deleted'=>$is_deleted,
        'id'=>$ID,
        );
    
      ajaxUpdateItemOnSelectEvent("massiveaction","show_massiveaction",$CFG_GLPI["root_doc"]."/plugins/archires/ajax/dropdownMassiveActionViews.php",$params);
    
      echo "<span id='show_massiveaction'>&nbsp;</span>\n";
   }
  
   function showView($type,$ID) {
      global $CFG_GLPI,$DB,$LANG;
    
      $obj=new $type();
      $obj->getFromDB($ID);
      $views_id=$obj->fields["views_id"];
      
      if (!$views_id) return false;
      $this->getFromDB($views_id);

      $name_config=$this->fields["name"];

      echo "<div align='center'>";
      echo "<table class='tab_cadrehov' cellpadding='2'width='75%'>";
      echo "<tr>";
      echo "<th colspan='3'>";
      echo $LANG['plugin_archires']['setup'][20]." : ".$name_config;
      echo "</th></tr>";

      echo "<tr class='tab_bg_2 top'><th>".$LANG['plugin_archires'][3]."</th><th>".$LANG['plugin_archires'][24]."</th><th>".$LANG['plugin_archires']['search'][6]."</th></tr>";

      echo "<tr class='tab_bg_1 top'><td class='center'>";
      if ($this->fields["computer"]!=0) echo $LANG['plugin_archires'][6]." : ".$LANG['choice'][1];
      else
         echo $LANG['plugin_archires'][6]." : ".$LANG['choice'][0];
      echo "<br>";

      if ($this->fields["networking"]!=0) echo $LANG['plugin_archires'][7]." : ".$LANG['choice'][1];
      else
         echo $LANG['plugin_archires'][7]." : ".$LANG['choice'][0];
      echo "<br>";

      if ($this->fields["printer"]!=0) echo $LANG['plugin_archires'][8]." : ".$LANG['choice'][1];
      else
         echo $LANG['plugin_archires'][8]." : ".$LANG['choice'][0];
      echo "<br>";

      if ($this->fields["peripheral"]!=0) echo $LANG['plugin_archires'][9]." : ".$LANG['choice'][1];
      else
         echo $LANG['plugin_archires'][9]." : ".$LANG['choice'][0];
      echo "<br>";

      if ($this->fields["phone"]!=0) echo $LANG['plugin_archires'][10]." : ".$LANG['choice'][1];
      else
         echo $LANG['plugin_archires'][10]." : ".$LANG['choice'][0];
      echo "</td>";

      echo "<td class='center'>";
      if ($this->fields["display_ports"]!=0) echo $LANG['plugin_archires'][16]." : ".$LANG['choice'][1];
      else
         echo $LANG['plugin_archires'][16]." : ".$LANG['choice'][0];
      echo "<br>";

      if ($this->fields["display_ip"]!=0) echo $LANG['plugin_archires'][23]." : ".$LANG['choice'][1];
      else
         echo $LANG['plugin_archires'][23]." : ".$LANG['choice'][0];
      echo "<br>";

      if ($this->fields["display_type"]!=0) echo $LANG['plugin_archires'][25]." : ".$LANG['choice'][1];
      else
         echo $LANG['plugin_archires'][25]." : ".$LANG['choice'][0];
      echo "<br>";

      if ($this->fields["display_state"]!=0) echo $LANG['plugin_archires'][26]." : ".$LANG['choice'][1];
      else
         echo $LANG['plugin_archires'][26]." : ".$LANG['choice'][0];
      echo "<br>";

      if ($this->fields["display_location"]!=0) echo $LANG['plugin_archires'][31]." : ".$LANG['choice'][1];
      else
         echo $LANG['plugin_archires'][31]." : ".$LANG['choice'][0];
      echo "<br>";

      if ($this->fields["display_entity"]!=0) echo $LANG['plugin_archires'][32]." : ".$LANG['choice'][1];
      else
         echo $LANG['plugin_archires'][32]." : ".$LANG['choice'][0];
      echo "</td>";

      //
      echo "<td class='center'>".$LANG['plugin_archires']['setup'][11]." : ";
      echo $LANG['plugin_archires']['setup'][13]." : ";
      if ($this->fields["engine"]!=0) echo "Neato";
      else
         echo "Dot";
      echo "<br>";
      echo $LANG['plugin_archires']['setup'][15]." : ";
      if ($this->fields["format"]==PLUGIN_ARCHIRES_JPEG_FORMAT) $format_graph="jpeg";
      else if ($this->fields["format"]==PLUGIN_ARCHIRES_PNG_FORMAT) $format_graph="png";
      else if ($this->fields["format"]==PLUGIN_ARCHIRES_GIF_FORMAT) $format_graph="gif";
      echo $format_graph;

      echo "</td></tr>";

      echo "</table></div>";
   }
}

?>