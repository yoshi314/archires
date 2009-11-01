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

class PluginArchires extends CommonDBTM {

	function title(){
			
				GLOBAL $CFG_GLPI, $LANG;
				
				echo "<div align='center'><table border='0'><tr><td>";
				echo "<img src=\"".$CFG_GLPI["root_doc"]."/plugins/archires/pics/archires.png\" alt='".$LANG['plugin_archires']['title'][0]."' title='".$LANG['plugin_archires']['title'][0]."'></td>";
				if(plugin_archires_haveRight("archires","w") || haveRight("config","w"))
          echo "<td><a  class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/index.php?new=1\"><b>".$LANG['plugin_archires']['title'][7]."</b></a></td>";
				else
          echo "<td><a  class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/index.php\"><b>".$LANG['plugin_archires']['title'][0]."</b></a></td>";
				
				echo "<td><a  class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/front/plugin_archires.view.index.php\"><b>".$LANG['plugin_archires']['title'][3]."</b></a></td>";
								
				if(plugin_archires_haveRight("archires","w") || haveRight("config","w"))
          echo "<td><a class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/front/plugin_archires.config.php\">".$LANG['plugin_archires']['profile'][2]."</a></td>";
				echo "</tr></table></div>";
		}
	
	function titleimg(){
			
				GLOBAL $CFG_GLPI, $LANG;
				
				echo "<div align='center'><table border='0'><tr><td>";
				echo "<img src=\"".$CFG_GLPI["root_doc"]."/plugins/archires/pics/archires.png\" alt='".$LANG['plugin_archires']['title'][0]."' title='".$LANG['plugin_archires']['title'][0]."'></td>";
				echo "<td><a  class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/index.php\"><b>".$LANG['plugin_archires']['title'][0]."</b></a></td>";
				echo "<td><a  class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/front/plugin_archires.view.index.php\"><b>".$LANG['plugin_archires']['title'][3]."</b></a></td>";
				if(plugin_archires_haveRight("archires","w"))
          echo "<td><a class='icon_consol' href=\"".$CFG_GLPI["root_doc"]."/plugins/archires/front/plugin_archires.config.php\">".$LANG['plugin_archires']['profile'][2]."</a>";
				echo "</td></tr></table></div>";
		}
}