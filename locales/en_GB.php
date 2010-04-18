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

$title = "Network Architecture";

$LANG['plugin_archires']['title'][0] = "".$title."";
$LANG['plugin_archires']['title'][1] = "Add view";
$LANG['plugin_archires']['title'][2] = "Add Network architecture by location";
$LANG['plugin_archires']['title'][3] = "Viewes";
$LANG['plugin_archires']['title'][4] = "location";
$LANG['plugin_archires']['title'][5] = "networking device";
$LANG['plugin_archires']['title'][6] = "Add a network architecture by networking device";
$LANG['plugin_archires']['title'][7] = "Add Network architecture";
$LANG['plugin_archires']['title'][8] = "appliance";
$LANG['plugin_archires']['title'][9] = "Add Network architecture by appliance";

$LANG['plugin_archires']['menu'][0] = "Summary";
$LANG['plugin_archires']['menu'][1] = "Adding Network architecture";
$LANG['plugin_archires']['menu'][2] = "Network architecture by";

$LANG['plugin_archires'][0] = "Display";
$LANG['plugin_archires'][1] = "See all views";
$LANG['plugin_archires'][2] = "Display types of items";
$LANG['plugin_archires'][3] = "Display of items";
$LANG['plugin_archires'][4] = "Thanks to specify a default used view";
$LANG['plugin_archires'][6] = "Computers";
$LANG['plugin_archires'][7] = "Networking devices";
$LANG['plugin_archires'][8] = "Printers";
$LANG['plugin_archires'][9] = "Peripherals";
$LANG['plugin_archires'][10] = "Phones";
$LANG['plugin_archires'][11] = "All";
$LANG['plugin_archires'][12] = "Item";
$LANG['plugin_archires'][13] = "Item type";
$LANG['plugin_archires'][14] = "Image";
$LANG['plugin_archires'][15] = "All states";
$LANG['plugin_archires'][16] = "Display sockets";
$LANG['plugin_archires'][17] = "Socket";
$LANG['plugin_archires'][18] = "All types";
$LANG['plugin_archires'][19] = "Type of network";
$LANG['plugin_archires'][20] = "Color";
$LANG['plugin_archires'][21] = "All types of network";
$LANG['plugin_archires'][22] = "Legend";
$LANG['plugin_archires'][23] = "Display IP/Mask";
$LANG['plugin_archires'][24] = "Display description";
$LANG['plugin_archires'][25] = "Display items type";
$LANG['plugin_archires'][26] = "Display items state";
$LANG['plugin_archires'][27] = "State";
$LANG['plugin_archires'][28] = "Duplicate";
$LANG['plugin_archires'][29] = "See numbers";
$LANG['plugin_archires'][30] = "All root locations";
$LANG['plugin_archires'][31] = "Display items location";
$LANG['plugin_archires'][32] = "Display items entity";
$LANG['plugin_archires'][33] = "See names";
$LANG['plugin_archires'][34] = "None";
$LANG['plugin_archires'][35] = "VLAN";
$LANG['plugin_archires'][36] = "Tous les VLANs";

$LANG['plugin_archires']['search'][0] = "ID";
$LANG['plugin_archires']['search'][1] = "Name";
$LANG['plugin_archires']['search'][2] = "Location";
$LANG['plugin_archires']['search'][3] = "Childs";
$LANG['plugin_archires']['search'][4] = "Network";
$LANG['plugin_archires']['search'][5] = "State";
$LANG['plugin_archires']['search'][6] = "Generation";
$LANG['plugin_archires']['search'][7] = "No recording found";
$LANG['plugin_archires']['search'][8] = "Appliances";

$LANG['plugin_archires']['profile'][0] = "Rights management";
$LANG['plugin_archires']['profile'][2] = "Setup";
$LANG['plugin_archires']['profile'][3] = "Generate a graph";

$LANG['plugin_archires']['setup'][2] = "Associate images with types of material";
$LANG['plugin_archires']['setup'][8] = "Associate colors with network types";
$LANG['plugin_archires']['setup'][9] = "Windows";
$LANG['plugin_archires']['setup'][10] = "Linux";
$LANG['plugin_archires']['setup'][11] = "Type of server";
$LANG['plugin_archires']['setup'][12] = "Please use this color format :<br> http://www.graphviz.org/doc/info/colors.html";
$LANG['plugin_archires']['setup'][13] = "Rendering engine";
$LANG['plugin_archires']['setup'][14] = "With neato, the sockets will not be displayed";
$LANG['plugin_archires']['setup'][15] = "Image format";
$LANG['plugin_archires']['setup'][16] = "[SVG]";
$LANG['plugin_archires']['setup'][19] = "Associate colors with items status";
$LANG['plugin_archires']['setup'][20] = "Associated view";
$LANG['plugin_archires']['setup'][21] = "Some types of items must be created so that the association can exist";
$LANG['plugin_archires']['setup'][23] = "Associer des couleurs aux VLAN";
$LANG['plugin_archires']['setup'][25] = "Appliquer la couleur sur" ;

$LANG['plugin_archires']['test'][0] = "Test";
$LANG['plugin_archires']['test'][1] = "Test Graphviz";
$LANG['plugin_archires']['test'][2] = "Material";
$LANG['plugin_archires']['test'][3] = "Links";
$LANG['plugin_archires']['test'][4] = "Graphviz name";
$LANG['plugin_archires']['test'][5] = "Associated image";
$LANG['plugin_archires']['test'][6] = "Name of material";
$LANG['plugin_archires']['test'][7] = "Type / IP";
$LANG['plugin_archires']['test'][8] = "State";
$LANG['plugin_archires']['test'][9] = "User / Group / Contact";
$LANG['plugin_archires']['test'][10] = "Graphviz links";
$LANG['plugin_archires']['test'][11] = "IP material 1";
$LANG['plugin_archires']['test'][12] = "Socket material 1";
$LANG['plugin_archires']['test'][13] = "Socket material 2";
$LANG['plugin_archires']['test'][14] = "IP material 2";

?>