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

$title = "Architettura di Rete";

$LANG['plugin_archires']['title'][0] = "".$title."";
$LANG['plugin_archires']['title'][1] = "Aggiungi visualizzazione";
$LANG['plugin_archires']['title'][2] = "Aggiungi architettura di rete per posizione";
$LANG['plugin_archires']['title'][3] = "Visualizzazioni";
$LANG['plugin_archires']['title'][4] = "Architettura di rete per posizione";
$LANG['plugin_archires']['title'][5] = "Architettura di rete per dispositivo di rete";
$LANG['plugin_archires']['title'][6] = "Aggiungi architettura di rete per dispositivo di rete";
$LANG['plugin_archires']['title'][7] = "Aggiungi architettura di rete";
$LANG['plugin_archires']['title'][8] = "Architettura di rete per appliance";
$LANG['plugin_archires']['title'][9] = "Aggiungi architettura di rete per appliance";

$LANG['plugin_archires']['menu'][0] = "Sommario";
$LANG['plugin_archires']['menu'][1] = "Aggiunta architettura di rete";

$LANG['plugin_archires'][0] = "Mostra";
$LANG['plugin_archires'][1] = "Nessuna posizione definita";
$LANG['plugin_archires'][2] = "Mostra il tipo degli elementi";
$LANG['plugin_archires'][3] = "Visualizzazione degli elementi";
$LANG['plugin_archires'][6] = "Computers";
$LANG['plugin_archires'][7] = "Dispositivi di rete";
$LANG['plugin_archires'][8] = "Stampanti";
$LANG['plugin_archires'][9] = "Periferiche";
$LANG['plugin_archires'][10] = "Telefoni";
$LANG['plugin_archires'][11] = "Tutti";
$LANG['plugin_archires'][12] = "Elemento";
$LANG['plugin_archires'][13] = "Tipo elemento";
$LANG['plugin_archires'][14] = "Immagini";
$LANG['plugin_archires'][15] = "Tutti gli stati";
$LANG['plugin_archires'][16] = "Visualizza i numeri di socket";
$LANG['plugin_archires'][17] = "Socket";
$LANG['plugin_archires'][18] = "Tutti i tipo";
$LANG['plugin_archires'][19] = "Tipo di rete";
$LANG['plugin_archires'][20] = "Colore";
$LANG['plugin_archires'][21] = "Tutti i tipi di rete";
$LANG['plugin_archires'][22] = "Legenda";
$LANG['plugin_archires'][23] = "Visulizza IP/Mask";
$LANG['plugin_archires'][24] = "Visualizza descrizione";
$LANG['plugin_archires'][25] = "Visualizza tipo elementi";
$LANG['plugin_archires'][26] = "Visualizza stato elementi";
$LANG['plugin_archires'][27] = "Stato";
$LANG['plugin_archires'][28] = "Duplicato";
$LANG['plugin_archires'][29] = "See numbers";
$LANG['plugin_archires'][30] = "Tutte le posizioni root";
$LANG['plugin_archires'][31] = "Visualizza posizione elementi";
$LANG['plugin_archires'][32] = "Visulizza entit&agrave; degli elementi";
$LANG['plugin_archires'][33] = "See names";
$LANG['plugin_archires'][34] = "Tutte le appliances";
$LANG['plugin_archires'][35] = "Vlan";
$LANG['plugin_archires'][36] = "Tous les VLANs";

$LANG['plugin_archires']['search'][0] = "ID";
$LANG['plugin_archires']['search'][1] = "Nome";
$LANG['plugin_archires']['search'][2] = "Posizione";
$LANG['plugin_archires']['search'][3] = "Figli";
$LANG['plugin_archires']['search'][4] = "Rete";
$LANG['plugin_archires']['search'][5] = "Stato";
$LANG['plugin_archires']['search'][6] = "Generazione";
$LANG['plugin_archires']['search'][7] = "Nessuna registrazione trovata";
$LANG['plugin_archires']['search'][8] = "Appliances";

$LANG['plugin_archires']['profile'][0] = "Gestione permessi";
$LANG['plugin_archires']['profile'][2] = "Setup";
$LANG['plugin_archires']['profile'][3] = "Genera un grafico";

$LANG['plugin_archires']['setup'][2] = "Associa immagini con i tipi di materiale";
$LANG['plugin_archires']['setup'][8] = "Associa colori con i tipi di rete";
$LANG['plugin_archires']['setup'][9] = "Windows";
$LANG['plugin_archires']['setup'][10] = "Linux";
$LANG['plugin_archires']['setup'][11] = "Tipo del server";
$LANG['plugin_archires']['setup'][12] = "Usa il seguente formato per il colore:<br> http://www.graphviz.org/doc/info/colors.html";
$LANG['plugin_archires']['setup'][13] = "Motore grafico";
$LANG['plugin_archires']['setup'][14] = "Con neato, i sockets non saranno visualizzati";
$LANG['plugin_archires']['setup'][15] = "Formato immagine";
$LANG['plugin_archires']['setup'][16] = "[SVG]";
$LANG['plugin_archires']['setup'][19] = "Associa colori con lo stato degli elementi";
$LANG['plugin_archires']['setup'][20] = "Visualizzazione associata";
$LANG['plugin_archires']['setup'][21] = "Some types of items must be created so that the association can exist";
$LANG['plugin_archires']['setup'][23] = "Associer des couleurs aux VLAN";
$LANG['plugin_archires']['setup'][25] = "Appliquer la couleur sur" ;

$LANG['plugin_archires']['test'][0] = "Test";
$LANG['plugin_archires']['test'][1] = "Test Graphviz";
$LANG['plugin_archires']['test'][2] = "Materiale";
$LANG['plugin_archires']['test'][3] = "Collegamenti";
$LANG['plugin_archires']['test'][4] = "Nome Graphviz";
$LANG['plugin_archires']['test'][5] = "Immagine associata";
$LANG['plugin_archires']['test'][6] = "Nome del materiale";
$LANG['plugin_archires']['test'][7] = "Tipo / IP";
$LANG['plugin_archires']['test'][8] = "Stato";
$LANG['plugin_archires']['test'][9] = "Utente / Gruppo / Contatto";
$LANG['plugin_archires']['test'][10] = "Collegamenti Graphviz";
$LANG['plugin_archires']['test'][11] = "IP materiale 1";
$LANG['plugin_archires']['test'][12] = "Socket materiale 1";
$LANG['plugin_archires']['test'][13] = "Socket materiale 2";
$LANG['plugin_archires']['test'][14] = "IP materiale 2";

?>