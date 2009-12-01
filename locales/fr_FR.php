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

$title = "Architecture Réseau";

$LANG['plugin_archires']['title'][0] = "".$title."";
$LANG['plugin_archires']['title'][1] = "Ajouter une vue";
$LANG['plugin_archires']['title'][2] = "Ajouter une architecture par lieu";
$LANG['plugin_archires']['title'][3] = "Vues";
$LANG['plugin_archires']['title'][4] = "lieu";
$LANG['plugin_archires']['title'][5] = "matériel réseau";
$LANG['plugin_archires']['title'][6] = "Ajouter une architecture par matériel réseau";
$LANG['plugin_archires']['title'][7] = "Ajouter une architecture";
$LANG['plugin_archires']['title'][8] = "applicatif";
$LANG['plugin_archires']['title'][9] = "Ajouter une architecture par applicatif";

$LANG['plugin_archires']['menu'][0] = "Menu général";
$LANG['plugin_archires']['menu'][1] = "Ajout d'architecture";
$LANG['plugin_archires']['menu'][2] = "Architecture par";

$LANG['plugin_archires'][0] = "Afficher";
$LANG['plugin_archires'][1] = "Voir toutes les vues";
$LANG['plugin_archires'][2] = "Afficher des types de matériel particulier";
$LANG['plugin_archires'][3] = "Affichage des matériels";
$LANG['plugin_archires'][6] = "Ordinateurs";
$LANG['plugin_archires'][7] = "Matériel réseau";
$LANG['plugin_archires'][8] = "Imprimantes";
$LANG['plugin_archires'][9] = "Périphériques";
$LANG['plugin_archires'][10] = "Téléphones";
$LANG['plugin_archires'][11] = "Tous";
$LANG['plugin_archires'][12] = "matériel";
$LANG['plugin_archires'][13] = "type de matériel";
$LANG['plugin_archires'][14] = "Image";
$LANG['plugin_archires'][15] = "Tous les statuts";
$LANG['plugin_archires'][16] = "Voir les ports";
$LANG['plugin_archires'][17] = "Port";
$LANG['plugin_archires'][18] = "Tous les types";
$LANG['plugin_archires'][19] = "Type de réseau";
$LANG['plugin_archires'][20] = "Couleur";
$LANG['plugin_archires'][21] = "Tous les types de réseau";
$LANG['plugin_archires'][22] = "Légende";
$LANG['plugin_archires'][23] = "Voir les IP/Masque";
$LANG['plugin_archires'][24] = "Affichage de la description";
$LANG['plugin_archires'][25] = "Voir le type de matériel";
$LANG['plugin_archires'][26] = "Voir l'état du matériel";
$LANG['plugin_archires'][27] = "Statut";
$LANG['plugin_archires'][28] = "Dupliquer";
$LANG['plugin_archires'][29] = "Voir les numéros";
$LANG['plugin_archires'][30] = "Tous les lieux racines";
$LANG['plugin_archires'][31] = "Voir le lieu du matériel";
$LANG['plugin_archires'][32] = "Voir l'entité du matériel";
$LANG['plugin_archires'][33] = "Voir les noms";
$LANG['plugin_archires'][34] = "Tous les applicatifs";
$LANG['plugin_archires'][35] = "Vlan";
$LANG['plugin_archires'][36] = "Tous les VLANs";

$LANG['plugin_archires']['search'][0] = "ID";
$LANG['plugin_archires']['search'][1] = "Nom";
$LANG['plugin_archires']['search'][2] = "Lieu";
$LANG['plugin_archires']['search'][3] = "Enfants";
$LANG['plugin_archires']['search'][4] = "Réseau";
$LANG['plugin_archires']['search'][5] = "Statut";
$LANG['plugin_archires']['search'][6] = "Génération";
$LANG['plugin_archires']['search'][7] = "Pas d'enregistrement trouvé";
$LANG['plugin_archires']['search'][8] = "Applicatifs";

$LANG['plugin_archires']['profile'][0] = "Gestion des droits";
$LANG['plugin_archires']['profile'][2] = "Configuration";
$LANG['plugin_archires']['profile'][3] = "Génération de graphique";

$LANG['plugin_archires']['setup'][2] = "Associer les images à des types de matériel";
$LANG['plugin_archires']['setup'][8] = "Associer des couleurs aux types de réseau";
$LANG['plugin_archires']['setup'][9] = "Windows";
$LANG['plugin_archires']['setup'][10] = "Linux";
$LANG['plugin_archires']['setup'][11] = "Type de serveur";
$LANG['plugin_archires']['setup'][12] = "Veuillez utiliser ce formatage de couleur :<br> http://www.graphviz.org/doc/info/colors.html";
$LANG['plugin_archires']['setup'][13] = "Moteur de rendu";
$LANG['plugin_archires']['setup'][14] = "Avec neato, les ports ne seront pas visibles";
$LANG['plugin_archires']['setup'][15] = "Format de l'image";
$LANG['plugin_archires']['setup'][16] = "[SVG]";
$LANG['plugin_archires']['setup'][19] = "Associer des couleurs aux statuts des matériels";
$LANG['plugin_archires']['setup'][20] = "Vue associée";
$LANG['plugin_archires']['setup'][21] = "Des types de matériels doivent être créés pour que l'association puisse exister";
$LANG['plugin_archires']['setup'][23] = "Associer des couleurs aux VLAN";
$LANG['plugin_archires']['setup'][25] = "Appliquer la couleur sur" ;

$LANG['plugin_archires']['test'][0] = "Tester";
$LANG['plugin_archires']['test'][1] = "Test Graphviz";
$LANG['plugin_archires']['test'][2] = "Matériel";
$LANG['plugin_archires']['test'][3] = "Liaisons";
$LANG['plugin_archires']['test'][4] = "Nom Graphviz";
$LANG['plugin_archires']['test'][5] = "Image associé";
$LANG['plugin_archires']['test'][6] = "Nom du matériel";
$LANG['plugin_archires']['test'][7] = "Type / IP";
$LANG['plugin_archires']['test'][8] = "Statut";
$LANG['plugin_archires']['test'][9] = "Utilisateur / Groupe / Contact";
$LANG['plugin_archires']['test'][10] = "Liaisons Graphviz";
$LANG['plugin_archires']['test'][11] = "IP matériel 1";
$LANG['plugin_archires']['test'][12] = "Port matériel 1";
$LANG['plugin_archires']['test'][13] = "Port matériel 2";
$LANG['plugin_archires']['test'][14] = "IP matériel 2";

?>