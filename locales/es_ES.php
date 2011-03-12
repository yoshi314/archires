<?php
/*
 * @version $Id: HEADER 2011-03-12 18:01:26 tsmr $
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
// Purpose of file: plugin archires v1.9.0 - GLPI 0.80
// ----------------------------------------------------------------------
 */

$title = "Arquitectura de Red";

$LANG['plugin_archires']['title'][0] = "".$title."";
$LANG['plugin_archires']['title'][1] = "Añadir una vista";
$LANG['plugin_archires']['title'][3] = "Vistas";
$LANG['plugin_archires']['title'][4] = "location";
$LANG['plugin_archires']['title'][5] = "networking device";
$LANG['plugin_archires']['title'][8] = "appliance";

$LANG['plugin_archires']['menu'][0] = "Menú generál";
$LANG['plugin_archires']['menu'][2] = "Network architecture by";

$LANG['plugin_archires'][0] = "Mostrar";
$LANG['plugin_archires'][1] = "See all views";
$LANG['plugin_archires'][2] = "Mostrar los tipos de materiales";
$LANG['plugin_archires'][3] = "Mostrar los materiales";
$LANG['plugin_archires'][4] = "Thanks to specify a default used view";
$LANG['plugin_archires'][6] = "Computadores";
$LANG['plugin_archires'][7] = "Equipos de red";
$LANG['plugin_archires'][8] = "Impresoras";
$LANG['plugin_archires'][9] = "Periféricos";
$LANG['plugin_archires'][10] = "Teléfonos";
$LANG['plugin_archires'][11] = "Todos";
$LANG['plugin_archires'][12] = "material";
$LANG['plugin_archires'][13] = "tipo de material";
$LANG['plugin_archires'][14] = "Imagen";
$LANG['plugin_archires'][15] = "Todos los estados";
$LANG['plugin_archires'][16] = "Ver los puertos";
$LANG['plugin_archires'][17] = "Puerto";
$LANG['plugin_archires'][18] = "Todoss los tipos";
$LANG['plugin_archires'][19] = "Tipo de red";
$LANG['plugin_archires'][20] = "Color";
$LANG['plugin_archires'][21] = "Todos los tipos de red";
$LANG['plugin_archires'][23] = "Ver IP/Máscara";
$LANG['plugin_archires'][24] = "Ver Descripción";
$LANG['plugin_archires'][25] = "Ver el tipo de material";
$LANG['plugin_archires'][26] = "Ver el estado del material";
$LANG['plugin_archires'][27] = "Statut";
$LANG['plugin_archires'][28] = "Duplicar";
$LANG['plugin_archires'][29] = "See numbers";
$LANG['plugin_archires'][30] = "Todos los lugares principales";
$LANG['plugin_archires'][31] = "Ver lugar del material";
$LANG['plugin_archires'][32] = "Ver la entidad del material";
$LANG['plugin_archires'][33] = "See names";
$LANG['plugin_archires'][34] = "None";
$LANG['plugin_archires'][35] = "Vlan";
$LANG['plugin_archires'][36] = "Tous les VLANs";

$LANG['plugin_archires']['search'][1] = "Nombre";
$LANG['plugin_archires']['search'][2] = "Lugar";
$LANG['plugin_archires']['search'][3] = "Hijos";
$LANG['plugin_archires']['search'][4] = "Redes";
$LANG['plugin_archires']['search'][5] = "Estado";
$LANG['plugin_archires']['search'][6] = "Generación";
$LANG['plugin_archires']['search'][7] = "Registro no encontrado";
$LANG['plugin_archires']['search'][8] = "Aplicativos";

$LANG['plugin_archires']['profile'][0] = "Gestión de los derechos";
$LANG['plugin_archires']['profile'][3] = "Generar un gráfico";

$LANG['plugin_archires']['setup'][2] = "Asociar las imágenes a los tipos de material";
$LANG['plugin_archires']['setup'][8] = "Asociar los colores a los tipos de redes";
$LANG['plugin_archires']['setup'][11] = "Tipo de servidor";
$LANG['plugin_archires']['setup'][12] = "Utilizar este formato para introducir el color";
$LANG['plugin_archires']['setup'][13] = "Motor de renderizado";
$LANG['plugin_archires']['setup'][14] = "Con neato, los puertos no serań visibles";
$LANG['plugin_archires']['setup'][15] = "Formato de la imagen";
$LANG['plugin_archires']['setup'][16] = "[SVG]";
$LANG['plugin_archires']['setup'][19] = "Asociar los colores a los estados de los materiales";
$LANG['plugin_archires']['setup'][20] = "Vista asociada";
$LANG['plugin_archires']['setup'][21] = "Des types de matériels doivent être créés pour que l'association puisse exister";
$LANG['plugin_archires']['setup'][23] = "Associer des couleurs aux VLAN";
$LANG['plugin_archires']['setup'][25] = "Appliquer la couleur sur" ;

$LANG['plugin_archires']['test'][0] = "Probador";
$LANG['plugin_archires']['test'][1] = "Probar Graphviz";
$LANG['plugin_archires']['test'][2] = "Material";
$LANG['plugin_archires']['test'][3] = "Enlaces";
$LANG['plugin_archires']['test'][4] = "Nombre Graphviz";
$LANG['plugin_archires']['test'][5] = "Imagen asociada";
$LANG['plugin_archires']['test'][6] = "Nombre del material";
$LANG['plugin_archires']['test'][7] = "Tipo / IP";
$LANG['plugin_archires']['test'][8] = "Estado";
$LANG['plugin_archires']['test'][9] = "Usuario / Grupo / Contacto";
$LANG['plugin_archires']['test'][10] = "Enlaces Graphviz";
$LANG['plugin_archires']['test'][11] = "IP material 1";
$LANG['plugin_archires']['test'][12] = "Puerto material 2";
$LANG['plugin_archires']['test'][13] = "Puerto material 2";
$LANG['plugin_archires']['test'][14] = "IP material 2";

?>