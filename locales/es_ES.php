<?php
/*

   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2005 by the INDEPNET Development Team.

   http://indepnet.net/   http://glpi-project.org/

   ----------------------------------------------------------------------
   LICENSE

   This program is free software; you can redistribute it and/or
   modify it under the terms of the GNU General Public License (GPL)
   as published by the Free Software Foundation; either version 2
   of the License, or (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   To read the license please visit http://www.gnu.org/copyleft/gpl.html
   ----------------------------------------------------------------------
// Original Author of file: GRISARD Jean Marc & CAILLAUD Xavier
Purpose of file:
----------------------------------------------------------------------
 */
/******************** plugin archires ***********/

$title = "Arquitectura de Red";

$LANG['plugin_archires']['title'][0] = "".$title."";
$LANG['plugin_archires']['title'][1] = "Añadir una vista";
$LANG['plugin_archires']['title'][2] = "Añadir una arquitectura por lugar";
$LANG['plugin_archires']['title'][3] = "Vistas";
$LANG['plugin_archires']['title'][4] = "Arquitectura por lugar";
$LANG['plugin_archires']['title'][5] = "Arquitectura por equipo de red";
$LANG['plugin_archires']['title'][6] = "Añadir arquitectura por equipo de red";
$LANG['plugin_archires']['title'][7] = "Añadir arquitectura";
$LANG['plugin_archires']['title'][8] = "Arquitectura por aplicativo";
$LANG['plugin_archires']['title'][9] = "Añadir una arquitectura por aplicativo";

$LANG['plugin_archires']['menu'][0] = "Menú generál";
$LANG['plugin_archires']['menu'][1] = "Adición de arquitectura";

$LANG['plugin_archires'][0] = "Mostrar";
$LANG['plugin_archires'][1] = "Lugar no definido";
$LANG['plugin_archires'][2] = "Mostrar los tipos de materiales";
$LANG['plugin_archires'][3] = "Mostrar los materiales";
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
$LANG['plugin_archires'][16] = "Ver los números de los puertos";
$LANG['plugin_archires'][17] = "Puerto";
$LANG['plugin_archires'][18] = "Todoss los tipos";
$LANG['plugin_archires'][19] = "Tipo de red";
$LANG['plugin_archires'][20] = "Color";
$LANG['plugin_archires'][21] = "Todos los tipos de red";
$LANG['plugin_archires'][22] = "Leyenda";
$LANG['plugin_archires'][23] = "Ver IP/Máscara";
$LANG['plugin_archires'][24] = "Ver Descripción";
$LANG['plugin_archires'][25] = "Ver el tipo de material";
$LANG['plugin_archires'][26] = "Ver el estado del material";
$LANG['plugin_archires'][27] = "Statut";
$LANG['plugin_archires'][28] = "Duplicar";
$LANG['plugin_archires'][30] = "Todos los lugares principales";
$LANG['plugin_archires'][31] = "Ver lugar del material";
$LANG['plugin_archires'][32] = "Ver la entidad del material";
$LANG['plugin_archires'][34] = "Todos los aplicativos";
$LANG['plugin_archires'][35] = "Vlan";
$LANG['plugin_archires'][36] = "Tous les VLANs";

$LANG['plugin_archires']['search'][0] = "ID";
$LANG['plugin_archires']['search'][1] = "Nombre";
$LANG['plugin_archires']['search'][2] = "Lugar";
$LANG['plugin_archires']['search'][3] = "Hijos";
$LANG['plugin_archires']['search'][4] = "Redes";
$LANG['plugin_archires']['search'][5] = "Estado";
$LANG['plugin_archires']['search'][6] = "Generación";
$LANG['plugin_archires']['search'][7] = "Registro no encontrado";
$LANG['plugin_archires']['search'][8] = "Aplicativos";

$LANG['plugin_archires']['profile'][0] = "Gestión de los derechos";
$LANG['plugin_archires']['profile'][2] = "Configuración";
$LANG['plugin_archires']['profile'][3] = "Generar un gráfico";

$LANG['plugin_archires']['setup'][2] = "Asociar las imágenes a los tipos de material";
$LANG['plugin_archires']['setup'][8] = "Asociar los colores al tipo de redes";
$LANG['plugin_archires']['setup'][9] = "Windows";
$LANG['plugin_archires']['setup'][10] = "Linux";
$LANG['plugin_archires']['setup'][11] = "Tipo de servidor";
$LANG['plugin_archires']['setup'][12] = "Utilizar este formato para introducir el color :<br> http://www.graphviz.org/doc/info/colors.html";
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