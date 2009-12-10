ALTER TABLE `glpi_plugin_archires_query_location` RENAME `glpi_plugin_archires_locationqueries`;
ALTER TABLE `glpi_plugin_archires_query_switch` RENAME `glpi_plugin_archires_networkequipmentqueries`;
ALTER TABLE `glpi_plugin_archires_query_applicatifs` RENAME `glpi_plugin_archires_appliancequeries`;
ALTER TABLE `glpi_plugin_archires_image_device` RENAME `glpi_plugin_archires_imageitems`;
ALTER TABLE `glpi_plugin_archires_query_type` RENAME `glpi_plugin_archires_queriestypes`;
ALTER TABLE `glpi_plugin_archires_color_iface` RENAME `glpi_plugin_archires_networkinterfacescolors`;
ALTER TABLE `glpi_plugin_archires_color_state` RENAME `glpi_plugin_archires_statescolors`;
ALTER TABLE `glpi_plugin_archires_color_vlan` RENAME `glpi_plugin_archires_vlanscolors`;
ALTER TABLE `glpi_plugin_archires_config` RENAME `glpi_plugin_archires_views`;

ALTER TABLE `glpi_plugin_archires_locationqueries`
   DROP INDEX `deleted`,
   CHANGE `ID` `id` int(11) NOT NULL auto_increment,
   CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL,
   CHANGE `FK_entities` `entities_id` int(11) NOT NULL default '0',
   CHANGE `location` `locations_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_locations (id)',
   CHANGE `network` `networks_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networks (id)',
   CHANGE `state` `states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)',
   CHANGE `FK_group` `groups_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_groups (id)',
   CHANGE `FK_config` `views_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_archires_views (id)',
   CHANGE `FK_vlan` `vlans_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_vlans (id)',
   CHANGE `deleted` `is_deleted` tinyint(1) NOT NULL default '0',
   CHANGE `notes` `notepad` longtext collate utf8_unicode_ci,
   DROP `link`,
   ADD INDEX (`name`),
   ADD INDEX (`entities_id`),
   ADD INDEX (`locations_id`),
   ADD INDEX (`networks_id`),
   ADD INDEX (`groups_id`),
   ADD INDEX (`views_id`),
   ADD INDEX (`states_id`),
   ADD INDEX (`vlans_id`),
   ADD INDEX (`is_deleted`);

ALTER TABLE `glpi_plugin_archires_networkequipmentqueries` 
   DROP INDEX `deleted`,
   CHANGE `ID` `id` int(11) NOT NULL auto_increment,
   CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL,
   CHANGE `FK_entities` `entities_id` int(11) NOT NULL default '0',
   CHANGE `switch` `networkequipments_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networkequipments (id)',
   CHANGE `network` `networks_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networks (id)',
   CHANGE `state` `states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)',
   CHANGE `FK_group` `groups_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_groups (id)',
   CHANGE `FK_config` `views_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_archires_views (id)',
   CHANGE `FK_vlan` `vlans_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_vlans (id)',
   CHANGE `deleted` `is_deleted` tinyint(1) NOT NULL default '0',
   CHANGE `notes` `notepad` longtext collate utf8_unicode_ci,
   DROP `link`,
   ADD INDEX (`name`),
   ADD INDEX (`entities_id`),
   ADD INDEX (`networkequipments_id`),
   ADD INDEX (`networks_id`),
   ADD INDEX (`groups_id`),
   ADD INDEX (`views_id`),
   ADD INDEX (`states_id`),
   ADD INDEX (`vlans_id`),
   ADD INDEX (`is_deleted`);

ALTER TABLE `glpi_plugin_archires_appliancequeries` 
   DROP INDEX `deleted`,
   CHANGE `ID` `id` int(11) NOT NULL auto_increment,
   CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL,
   CHANGE `FK_entities` `entities_id` int(11) NOT NULL default '0',
   CHANGE `applicatifs` `appliances_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_appliances (id)',
   CHANGE `network` `networks_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networks (id)',
   CHANGE `state` `states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)',
   CHANGE `FK_group` `groups_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_groups (id)',
   CHANGE `FK_config` `views_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_archires_views (id)',
   CHANGE `FK_vlan` `vlans_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_vlans (id)',
   CHANGE `deleted` `is_deleted` tinyint(1) NOT NULL default '0',
   CHANGE `notes` `notepad` longtext collate utf8_unicode_ci,
   DROP `link`,
   ADD INDEX (`name`),
   ADD INDEX (`entities_id`),
   ADD INDEX (`appliances_id`),
   ADD INDEX (`networks_id`),
   ADD INDEX (`groups_id`),
   ADD INDEX (`views_id`),
   ADD INDEX (`states_id`),
   ADD INDEX (`vlans_id`),
   ADD INDEX (`is_deleted`);

ALTER TABLE `glpi_plugin_archires_imageitems` 
   DROP INDEX `device_type`,
   CHANGE `ID` `id` int(11) NOT NULL auto_increment,
   CHANGE `type` `type` int(11) NOT NULL default '0',
   CHANGE `device_type` `itemtype` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'see .class.php file';

ALTER TABLE `glpi_plugin_archires_queriestypes` 
   DROP INDEX `FK_query`,
   DROP INDEX `type`,
   DROP INDEX `type_query`,
   DROP INDEX `device_type`,
   CHANGE `ID` `id` int(11) NOT NULL auto_increment,
   CHANGE `type_query` `querytype` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'RELATION to the 3 type of archires (type)',
   CHANGE `type` `type` int(11) NOT NULL default '0',
   CHANGE `device_type` `itemtype` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'see .class.php file',
   CHANGE `FK_query` `queries_id` int(11) NOT NULL default '0' COMMENT 'RELATION to the 3 queries tables (id)',
   ADD INDEX (`querytype`),
   ADD INDEX (`type`),
   ADD INDEX (`itemtype`),
   ADD INDEX (`queries_id`);

 
UPDATE `glpi_plugin_archires_queriestypes` SET `querytype` = 'PluginArchiresLocationQuery' WHERE `querytype` = 0;
UPDATE `glpi_plugin_archires_queriestypes` SET `querytype` = 'PluginArchiresNetworkEquipmentQuery' WHERE `querytype` = 1;
UPDATE `glpi_plugin_archires_queriestypes` SET `querytype` = 'PluginArchiresApplianceQuery' WHERE `querytype` = 2;

ALTER TABLE `glpi_plugin_archires_networkinterfacescolors` 
   DROP INDEX `iface`,
   CHANGE `ID` `id` int(11) NOT NULL auto_increment,
   CHANGE `iface` `networkinterfaces_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networkinterfaces (id)',
   ADD INDEX (`networkinterfaces_id`);

ALTER TABLE `glpi_plugin_archires_statescolors` 
   CHANGE `ID` `id` int(11) NOT NULL auto_increment,
   CHANGE `state` `states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)',
   ADD INDEX (`states_id`);

ALTER TABLE `glpi_plugin_archires_statescolors` DROP INDEX `state`;

ALTER TABLE `glpi_plugin_archires_vlanscolors` 
   CHANGE `ID` `id` int(11) NOT NULL auto_increment,
   CHANGE `vlan` `vlans_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_vlans (id)',
   ADD INDEX (`vlans_id`);

ALTER TABLE `glpi_plugin_archires_views` 
   DROP INDEX `deleted`,
   DROP INDEX `FK_entities`,
   CHANGE `ID` `id` int(11) NOT NULL auto_increment,
   CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL,
   CHANGE `FK_entities` `entities_id` int(11) NOT NULL default '0',
   CHANGE `deleted` `is_deleted` tinyint(1) NOT NULL default '0',
   ADD INDEX (`entities_id`),
   ADD INDEX (`is_deleted`);

ALTER TABLE `glpi_plugin_archires_profiles` 
   CHANGE `ID` `id` int(11) NOT NULL auto_increment,
   CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL,
   CHANGE `archires` `archires` char(1) collate utf8_unicode_ci default NULL;

DELETE FROM `glpi_displaypreferences` WHERE `itemtype` = 3000 AND `num` = 9;
DELETE FROM `glpi_displaypreferences` WHERE `itemtype` = 3001 AND `num` = 8;
DELETE FROM `glpi_displaypreferences` WHERE `itemtype` = 3002 AND `num` = 8;