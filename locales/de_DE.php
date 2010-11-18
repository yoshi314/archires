<?php
/*
 * @version $Id: HEADER 2010-10-31 21:36:26 tsmr $
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
// Purpose of file: plugin archires v1.8.1 - GLPI 0.78
// ----------------------------------------------------------------------
 */

$title = "Netzwerk Architektur";

$LANG['plugin_archires']['title'][0] = "".$title."";
$LANG['plugin_archires']['title'][1] = "Ansicht hinzuf&uuml;gen";
$LANG['plugin_archires']['title'][3] = "Ansichten";
$LANG['plugin_archires']['title'][4] = "Netzwerkarchitektur nach Standort";
$LANG['plugin_archires']['title'][5] = "Netzwerkarchitektur nach Netzwerkger&auml;t";
$LANG['plugin_archires']['title'][8] = "Netzwerkarchitektur nach Anwendung";

$LANG['plugin_archires']['menu'][0] = "Zusammenfassung";
$LANG['plugin_archires']['menu'][2] = "Network architecture by";

$LANG['plugin_archires'][0] = "Display";
$LANG['plugin_archires'][1] = "Kein Standort definiert";
$LANG['plugin_archires'][2] = "Zeige Item Typen";
$LANG['plugin_archires'][3] = "Anzeige von Items";
$LANG['plugin_archires'][4] = "Thanks to specify a default used view";
$LANG['plugin_archires'][6] = "Computer";
$LANG['plugin_archires'][7] = "Netzwerk Ger&auml;te";
$LANG['plugin_archires'][8] = "Drucker";
$LANG['plugin_archires'][9] = "Ger&auml;te";
$LANG['plugin_archires'][10] = "Telefone";
$LANG['plugin_archires'][11] = "Alle";
$LANG['plugin_archires'][12] = "Item";
$LANG['plugin_archires'][13] = "Item type";
$LANG['plugin_archires'][14] = "Bild";
$LANG['plugin_archires'][15] = "Alle zust&auml;nde";
$LANG['plugin_archires'][16] = "Zeige Anzahl der Steckverbindungen";
$LANG['plugin_archires'][17] = "Steckverbindung";
$LANG['plugin_archires'][18] = "Alle Typen";
$LANG['plugin_archires'][19] = "Typen des Netzwerks";
$LANG['plugin_archires'][20] = "Farbe";
$LANG['plugin_archires'][21] = "Alle Typen des Netzwerks";
$LANG['plugin_archires'][23] = "IP/Mask anzeigen";
$LANG['plugin_archires'][24] = "Zeige Beschreibung";
$LANG['plugin_archires'][25] = "Zeige Item typ";
$LANG['plugin_archires'][26] = "Zeige Item Zustand";
$LANG['plugin_archires'][27] = "Zustand";
$LANG['plugin_archires'][28] = "Dupliziere";
$LANG['plugin_archires'][29] = "See numbers";
$LANG['plugin_archires'][30] = "All root locations";
$LANG['plugin_archires'][31] = "Zeige Item Standort";
$LANG['plugin_archires'][32] = "Zeige Item Einheit";
$LANG['plugin_archires'][33] = "See names";
$LANG['plugin_archires'][34] = "None";
$LANG['plugin_archires'][35] = "VLAN";
$LANG['plugin_archires'][36] = "Alle VLANs";

$LANG['plugin_archires']['search'][1] = "Name";
$LANG['plugin_archires']['search'][2] = "Standort";
$LANG['plugin_archires']['search'][3] = "Nachfolger";
$LANG['plugin_archires']['search'][4] = "Netzwerk";
$LANG['plugin_archires']['search'][5] = "Status";
$LANG['plugin_archires']['search'][6] = "Erzeuge";
$LANG['plugin_archires']['search'][7] = "Kein Eintrag gefunden";
$LANG['plugin_archires']['search'][8] = "Appliances";

$LANG['plugin_archires']['profile'][0] = "Rechte Management";
$LANG['plugin_archires']['profile'][3] = "Erzeuge einen Graphen";

$LANG['plugin_archires']['setup'][2] = "Bilder mit Material verkn&uuml;pfen";
$LANG['plugin_archires']['setup'][8] = "Netzwerktypen mit einer Farbe verkn&uuml;pfen";
$LANG['plugin_archires']['setup'][11] = "Art des Servers";
$LANG['plugin_archires']['setup'][12] = "Bitte benutzen Sie folgende Farben";
$LANG['plugin_archires']['setup'][13] = "Rendering engine";
$LANG['plugin_archires']['setup'][14] = "Mit neato werden Steckverbindungen nicht angezeigt";
$LANG['plugin_archires']['setup'][15] = "Bildformat";
$LANG['plugin_archires']['setup'][16] = "[SVG]";
$LANG['plugin_archires']['setup'][19] = "Verkn&uuml;pfe Farbe mit Item Zustand";
$LANG['plugin_archires']['setup'][20] = "Zugeh&ouml;rige Ansicht";
$LANG['plugin_archires']['setup'][21] = "Einige Arten von Items m&uuml;ssen erstellt werden, damit eine Zuordnug existieren kann";
$LANG['plugin_archires']['setup'][23] = "VLANs mit Farben verkn&uuml;pfen";
$LANG['plugin_archires']['setup'][25] = "Farbe w&auml;hlen" ;

$LANG['plugin_archires']['test'][0] = "Test";
$LANG['plugin_archires']['test'][1] = "Test Graphviz";
$LANG['plugin_archires']['test'][2] = "Material";
$LANG['plugin_archires']['test'][3] = "Links";
$LANG['plugin_archires']['test'][4] = "Graphviz name";
$LANG['plugin_archires']['test'][5] = "Zugeh&ouml;riges Bild";
$LANG['plugin_archires']['test'][6] = "Name des Materials";
$LANG['plugin_archires']['test'][7] = "Typ / IP";
$LANG['plugin_archires']['test'][8] = "Zustand";
$LANG['plugin_archires']['test'][9] = "Benutzer / Gruppe / Kontakt";
$LANG['plugin_archires']['test'][10] = "Graphviz links";
$LANG['plugin_archires']['test'][11] = "IP material 1";
$LANG['plugin_archires']['test'][12] = "Steckverbindungsmaterial 1";
$LANG['plugin_archires']['test'][13] = "Steckverbindungsmaterial 2";
$LANG['plugin_archires']['test'][14] = "IP material 2";

?>