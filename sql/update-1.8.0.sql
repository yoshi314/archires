ALTER TABLE `glpi_plugin_archires_query_location` RENAME `glpi_plugin_archires_locationsqueries`;
ALTER TABLE `glpi_plugin_archires_query_switch` RENAME `glpi_plugin_archires_networkequipmentsqueries`;
ALTER TABLE `glpi_plugin_archires_query_applicatifs` RENAME `glpi_plugin_archires_appliancesqueries`;
ALTER TABLE `glpi_plugin_archires_image_device` RENAME `glpi_plugin_archires_imageitems`;
ALTER TABLE `glpi_plugin_archires_query_type` RENAME `glpi_plugin_archires_queriestypes`;
ALTER TABLE `glpi_plugin_archires_color_iface` RENAME `glpi_plugin_archires_networkinterfacescolors`;
ALTER TABLE `glpi_plugin_archires_color_state` RENAME `glpi_plugin_archires_statescolors`;
ALTER TABLE `glpi_plugin_archires_color_vlan` RENAME `glpi_plugin_archires_vlanscolors`;
ALTER TABLE `glpi_plugin_archires_config` RENAME `glpi_plugin_archires_views`;

ALTER TABLE `glpi_plugin_archires_locationsqueries` DROP `link`;
ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` DROP `link`;
ALTER TABLE `glpi_plugin_archires_appliancesqueries` DROP `link`;

ALTER TABLE `glpi_plugin_archires_locationsqueries` DROP INDEX `deleted`;

ALTER TABLE `glpi_plugin_archires_locationsqueries` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_archires_locationsqueries` CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_archires_locationsqueries` CHANGE `FK_entities` `entities_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_plugin_archires_locationsqueries` CHANGE `location` `locations_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_locations (id)';
ALTER TABLE `glpi_plugin_archires_locationsqueries` CHANGE `network` `networks_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networks (id)';
ALTER TABLE `glpi_plugin_archires_locationsqueries` CHANGE `state` `states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)';
ALTER TABLE `glpi_plugin_archires_locationsqueries` CHANGE `FK_group` `groups_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_groups (id)';
ALTER TABLE `glpi_plugin_archires_locationsqueries` CHANGE `FK_config` `views_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_archires_views (id)';
ALTER TABLE `glpi_plugin_archires_locationsqueries` CHANGE `FK_vlan` `vlans_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_vlans (id)';
ALTER TABLE `glpi_plugin_archires_locationsqueries` CHANGE `deleted` `is_deleted` tinyint(1) NOT NULL default '0';
ALTER TABLE `glpi_plugin_archires_locationsqueries` CHANGE `notes` `notepad` longtext collate utf8_unicode_ci;

ALTER TABLE `glpi_plugin_archires_locationsqueries` ADD INDEX (`name`);
ALTER TABLE `glpi_plugin_archires_locationsqueries` ADD INDEX (`entities_id`);
ALTER TABLE `glpi_plugin_archires_locationsqueries` ADD INDEX (`locations_id`);
ALTER TABLE `glpi_plugin_archires_locationsqueries` ADD INDEX (`networks_id`);
ALTER TABLE `glpi_plugin_archires_locationsqueries` ADD INDEX (`groups_id`);
ALTER TABLE `glpi_plugin_archires_locationsqueries` ADD INDEX (`views_id`);
ALTER TABLE `glpi_plugin_archires_locationsqueries` ADD INDEX (`states_id`);
ALTER TABLE `glpi_plugin_archires_locationsqueries` ADD INDEX (`vlans_id`);
ALTER TABLE `glpi_plugin_archires_locationsqueries` ADD INDEX (`is_deleted`);

ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` DROP INDEX `deleted`;

ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` CHANGE `FK_entities` `entities_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` CHANGE `switch` `networkequipments_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networkequipments (id)';
ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` CHANGE `network` `networks_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networks (id)';
ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` CHANGE `state` `states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)';
ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` CHANGE `FK_group` `groups_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_groups (id)';
ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` CHANGE `FK_config` `views_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_archires_views (id)';
ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` CHANGE `FK_vlan` `vlans_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_vlans (id)';
ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` CHANGE `deleted` `is_deleted` tinyint(1) NOT NULL default '0';
ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` CHANGE `notes` `notepad` longtext collate utf8_unicode_ci;

ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` ADD INDEX (`name`);
ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` ADD INDEX (`entities_id`);
ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` ADD INDEX (`networkequipments_id`);
ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` ADD INDEX (`networks_id`);
ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` ADD INDEX (`groups_id`);
ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` ADD INDEX (`views_id`);
ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` ADD INDEX (`states_id`);
ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` ADD INDEX (`vlans_id`);
ALTER TABLE `glpi_plugin_archires_networkequipmentsqueries` ADD INDEX (`is_deleted`);

ALTER TABLE `glpi_plugin_archires_appliancesqueries` DROP INDEX `deleted`;

ALTER TABLE `glpi_plugin_archires_appliancesqueries` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_archires_appliancesqueries` CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_archires_appliancesqueries` CHANGE `FK_entities` `entities_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_plugin_archires_appliancesqueries` CHANGE `applicatifs` `appliances_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_appliances (id)';
ALTER TABLE `glpi_plugin_archires_appliancesqueries` CHANGE `network` `networks_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networks (id)';
ALTER TABLE `glpi_plugin_archires_appliancesqueries` CHANGE `state` `states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)';
ALTER TABLE `glpi_plugin_archires_appliancesqueries` CHANGE `FK_group` `groups_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_groups (id)';
ALTER TABLE `glpi_plugin_archires_appliancesqueries` CHANGE `FK_config` `views_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_archires_views (id)';
ALTER TABLE `glpi_plugin_archires_appliancesqueries` CHANGE `FK_vlan` `vlans_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_vlans (id)';
ALTER TABLE `glpi_plugin_archires_appliancesqueries` CHANGE `deleted` `is_deleted` tinyint(1) NOT NULL default '0';
ALTER TABLE `glpi_plugin_archires_appliancesqueries` CHANGE `notes` `notepad` longtext collate utf8_unicode_ci;

ALTER TABLE `glpi_plugin_archires_appliancesqueries` ADD INDEX (`name`);
ALTER TABLE `glpi_plugin_archires_appliancesqueries` ADD INDEX (`entities_id`);
ALTER TABLE `glpi_plugin_archires_appliancesqueries` ADD INDEX (`appliances_id`);
ALTER TABLE `glpi_plugin_archires_appliancesqueries` ADD INDEX (`networks_id`);
ALTER TABLE `glpi_plugin_archires_appliancesqueries` ADD INDEX (`groups_id`);
ALTER TABLE `glpi_plugin_archires_appliancesqueries` ADD INDEX (`views_id`);
ALTER TABLE `glpi_plugin_archires_appliancesqueries` ADD INDEX (`states_id`);
ALTER TABLE `glpi_plugin_archires_appliancesqueries` ADD INDEX (`vlans_id`);
ALTER TABLE `glpi_plugin_archires_appliancesqueries` ADD INDEX (`is_deleted`);

ALTER TABLE `glpi_plugin_archires_imageitems` DROP INDEX `device_type`;
ALTER TABLE `glpi_plugin_archires_imageitems` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_archires_imageitems` CHANGE `type` `type` int(11) NOT NULL default '0';
ALTER TABLE `glpi_plugin_archires_imageitems` CHANGE `device_type` `itemtype` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'see .class.php file';

ALTER TABLE `glpi_plugin_archires_queriestypes` DROP INDEX `FK_query`;
ALTER TABLE `glpi_plugin_archires_queriestypes` DROP INDEX `type`;
ALTER TABLE `glpi_plugin_archires_queriestypes` DROP INDEX `type_query`;
ALTER TABLE `glpi_plugin_archires_queriestypes` DROP INDEX `device_type`;

ALTER TABLE `glpi_plugin_archires_queriestypes` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_archires_queriestypes` CHANGE `type_query` `querytype` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'RELATION to the 3 type of archires (type)';
ALTER TABLE `glpi_plugin_archires_queriestypes` CHANGE `type` `type` int(11) NOT NULL default '0';
ALTER TABLE `glpi_plugin_archires_queriestypes` CHANGE `device_type` `itemtype` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'see .class.php file';
ALTER TABLE `glpi_plugin_archires_queriestypes` CHANGE `FK_query` `queries_id` int(11) NOT NULL default '0' COMMENT 'RELATION to the 3 queries tables (id)';

ALTER TABLE `glpi_plugin_archires_queriestypes` ADD INDEX (`querytype`);
ALTER TABLE `glpi_plugin_archires_queriestypes` ADD INDEX (`type`);
ALTER TABLE `glpi_plugin_archires_queriestypes` ADD INDEX (`itemtype`);
ALTER TABLE `glpi_plugin_archires_queriestypes` ADD INDEX (`queries_id`);

UPDATE `glpi_plugin_archires_queriestypes` SET `querytype` = 'PluginArchiresLocationQuery' WHERE `querytype` = 0;
UPDATE `glpi_plugin_archires_queriestypes` SET `querytype` = 'PluginArchiresNetworkEquipmentQuery' WHERE `querytype` = 1;
UPDATE `glpi_plugin_archires_queriestypes` SET `querytype` = 'PluginArchiresApplianceQuery' WHERE `querytype` = 2;

ALTER TABLE `glpi_plugin_archires_networkinterfacescolors` DROP INDEX `iface`;
ALTER TABLE `glpi_plugin_archires_networkinterfacescolors` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_archires_networkinterfacescolors` CHANGE `iface` `networkinterfaces_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networkinterfaces (id)';
ALTER TABLE `glpi_plugin_archires_networkinterfacescolors` ADD INDEX (`networkinterfaces_id`);

ALTER TABLE `glpi_plugin_archires_statescolors` DROP INDEX `state`;
ALTER TABLE `glpi_plugin_archires_statescolors` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_archires_statescolors` CHANGE `state` `states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)';
ALTER TABLE `glpi_plugin_archires_statescolors` ADD INDEX (`states_id`);

ALTER TABLE `glpi_plugin_archires_vlanscolors` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_archires_vlanscolors` CHANGE `vlan` `vlans_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_vlans (id)';
ALTER TABLE `glpi_plugin_archires_vlanscolors` ADD INDEX (`vlans_id`);

ALTER TABLE `glpi_plugin_archires_views` DROP INDEX `deleted`;
ALTER TABLE `glpi_plugin_archires_views` DROP INDEX `FK_entities`;
ALTER TABLE `glpi_plugin_archires_views` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_archires_views` CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_archires_views` CHANGE `FK_entities` `entities_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_plugin_archires_views` CHANGE `deleted` `is_deleted` tinyint(1) NOT NULL default '0';
ALTER TABLE `glpi_plugin_archires_views` ADD INDEX (`entities_id`);
ALTER TABLE `glpi_plugin_archires_views` ADD INDEX (`is_deleted`);

ALTER TABLE `glpi_plugin_archires_profiles` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_archires_profiles` CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_archires_profiles` CHANGE `archires` `archires` char(1) collate utf8_unicode_ci default NULL;

DELETE FROM `glpi_displaypreferences` WHERE `itemtype` = 3000 AND `num` = 9;
DELETE FROM `glpi_displaypreferences` WHERE `itemtype` = 3001 AND `num` = 8;
DELETE FROM `glpi_displaypreferences` WHERE `itemtype` = 3002 AND `num` = 8;