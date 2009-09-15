ALTER TABLE `glpi_plugin_archires_query_location` RENAME `glpi_plugin_archires_locations_queries`;
ALTER TABLE `glpi_plugin_archires_query_switch` RENAME `glpi_plugin_archires_networkequipments_queries`;
ALTER TABLE `glpi_plugin_archires_query_applicatifs` RENAME `glpi_plugin_archires_appliances_queries`;
ALTER TABLE `glpi_plugin_archires_image_device` RENAME `glpi_plugin_archires_imageitems`;
ALTER TABLE `glpi_plugin_archires_query_type` RENAME `glpi_plugin_archires_query_types`;
ALTER TABLE `glpi_plugin_archires_color_iface` RENAME `glpi_plugin_archires_networkinterfacescolors`;
ALTER TABLE `glpi_plugin_archires_color_state` RENAME `glpi_plugin_archires_statescolors`;
ALTER TABLE `glpi_plugin_archires_color_vlan` RENAME `glpi_plugin_archires_vlanscolors`;
ALTER TABLE `glpi_plugin_archires_config` RENAME `glpi_plugin_archires_views`;

ALTER TABLE `glpi_plugin_archires_locations_queries` DROP INDEX `deleted`;

ALTER TABLE `glpi_plugin_archires_locations_queries` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_archires_locations_queries` CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_archires_locations_queries` CHANGE `FK_entities` `entities_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_plugin_archires_locations_queries` CHANGE `location` `locations_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_locations (id)';
ALTER TABLE `glpi_plugin_archires_locations_queries` CHANGE `network` `networks_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networks (id)';
ALTER TABLE `glpi_plugin_archires_locations_queries` CHANGE `state` `states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)';
ALTER TABLE `glpi_plugin_archires_locations_queries` CHANGE `FK_group` `groups_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_groups (id)';
ALTER TABLE `glpi_plugin_archires_locations_queries` CHANGE `FK_config` `views_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_archires_views (id)';
ALTER TABLE `glpi_plugin_archires_locations_queries` CHANGE `FK_vlan` `vlans_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_vlans (id)';
ALTER TABLE `glpi_plugin_archires_locations_queries` CHANGE `deleted` `is_deleted` tinyint(1) NOT NULL default '0';
ALTER TABLE `glpi_plugin_archires_locations_queries` CHANGE `notes` `notepad` longtext collate utf8_unicode_ci;

ALTER TABLE `glpi_plugin_archires_locations_queries` ADD INDEX (`name`);
ALTER TABLE `glpi_plugin_archires_locations_queries` ADD INDEX (`entities_id`);
ALTER TABLE `glpi_plugin_archires_locations_queries` ADD INDEX (`locations_id`);
ALTER TABLE `glpi_plugin_archires_locations_queries` ADD INDEX (`networks_id`);
ALTER TABLE `glpi_plugin_archires_locations_queries` ADD INDEX (`groups_id`);
ALTER TABLE `glpi_plugin_archires_locations_queries` ADD INDEX (`views_id`);
ALTER TABLE `glpi_plugin_archires_locations_queries` ADD INDEX (`states_id`);
ALTER TABLE `glpi_plugin_archires_locations_queries` ADD INDEX (`vlans_id`);
ALTER TABLE `glpi_plugin_archires_locations_queries` ADD INDEX (`is_deleted`);

ALTER TABLE `glpi_plugin_archires_networkequipments_queries` DROP INDEX `deleted`;

ALTER TABLE `glpi_plugin_archires_networkequipments_queries` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_archires_networkequipments_queries` CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_archires_networkequipments_queries` CHANGE `FK_entities` `entities_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_plugin_archires_networkequipments_queries` CHANGE `switch` `networkequipments_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networkequipments (id)';
ALTER TABLE `glpi_plugin_archires_networkequipments_queries` CHANGE `network` `networks_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networks (id)';
ALTER TABLE `glpi_plugin_archires_networkequipments_queries` CHANGE `state` `states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)';
ALTER TABLE `glpi_plugin_archires_networkequipments_queries` CHANGE `FK_group` `groups_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_groups (id)';
ALTER TABLE `glpi_plugin_archires_networkequipments_queries` CHANGE `FK_config` `views_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_archires_views (id)';
ALTER TABLE `glpi_plugin_archires_networkequipments_queries` CHANGE `FK_vlan` `vlans_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_vlans (id)';
ALTER TABLE `glpi_plugin_archires_networkequipments_queries` CHANGE `deleted` `is_deleted` tinyint(1) NOT NULL default '0';
ALTER TABLE `glpi_plugin_archires_networkequipments_queries` CHANGE `notes` `notepad` longtext collate utf8_unicode_ci;

ALTER TABLE `glpi_plugin_archires_networkequipments_queries` ADD INDEX (`name`);
ALTER TABLE `glpi_plugin_archires_networkequipments_queries` ADD INDEX (`entities_id`);
ALTER TABLE `glpi_plugin_archires_networkequipments_queries` ADD INDEX (`networkequipments_id`);
ALTER TABLE `glpi_plugin_archires_networkequipments_queries` ADD INDEX (`networks_id`);
ALTER TABLE `glpi_plugin_archires_networkequipments_queries` ADD INDEX (`groups_id`);
ALTER TABLE `glpi_plugin_archires_networkequipments_queries` ADD INDEX (`views_id`);
ALTER TABLE `glpi_plugin_archires_networkequipments_queries` ADD INDEX (`states_id`);
ALTER TABLE `glpi_plugin_archires_networkequipments_queries` ADD INDEX (`vlans_id`);
ALTER TABLE `glpi_plugin_archires_networkequipments_queries` ADD INDEX (`is_deleted`);

ALTER TABLE `glpi_plugin_archires_appliances_queries` DROP INDEX `deleted`;

ALTER TABLE `glpi_plugin_archires_appliances_queries` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_archires_appliances_queries` CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_archires_appliances_queries` CHANGE `FK_entities` `entities_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_plugin_archires_appliances_queries` CHANGE `applicatifs` `appliances_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_appliances (id)';
ALTER TABLE `glpi_plugin_archires_appliances_queries` CHANGE `network` `networks_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networks (id)';
ALTER TABLE `glpi_plugin_archires_appliances_queries` CHANGE `state` `states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)';
ALTER TABLE `glpi_plugin_archires_appliances_queries` CHANGE `FK_group` `groups_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_groups (id)';
ALTER TABLE `glpi_plugin_archires_appliances_queries` CHANGE `FK_config` `views_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_archires_views (id)';
ALTER TABLE `glpi_plugin_archires_appliances_queries` CHANGE `FK_vlan` `vlans_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_vlans (id)';
ALTER TABLE `glpi_plugin_archires_appliances_queries` CHANGE `deleted` `is_deleted` tinyint(1) NOT NULL default '0';
ALTER TABLE `glpi_plugin_archires_appliances_queries` CHANGE `notes` `notepad` longtext collate utf8_unicode_ci;

ALTER TABLE `glpi_plugin_archires_appliances_queries` ADD INDEX (`name`);
ALTER TABLE `glpi_plugin_archires_appliances_queries` ADD INDEX (`entities_id`);
ALTER TABLE `glpi_plugin_archires_appliances_queries` ADD INDEX (`appliances_id`);
ALTER TABLE `glpi_plugin_archires_appliances_queries` ADD INDEX (`networks_id`);
ALTER TABLE `glpi_plugin_archires_appliances_queries` ADD INDEX (`groups_id`);
ALTER TABLE `glpi_plugin_archires_appliances_queries` ADD INDEX (`views_id`);
ALTER TABLE `glpi_plugin_archires_appliances_queries` ADD INDEX (`states_id`);
ALTER TABLE `glpi_plugin_archires_appliances_queries` ADD INDEX (`vlans_id`);
ALTER TABLE `glpi_plugin_archires_appliances_queries` ADD INDEX (`is_deleted`);

ALTER TABLE `glpi_plugin_archires_imageitems` DROP INDEX `device_type`;
ALTER TABLE `glpi_plugin_archires_imageitems` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_archires_imageitems` CHANGE `type` `type` int(11) NOT NULL default '0';
ALTER TABLE `glpi_plugin_archires_imageitems` CHANGE `device_type` `itemtype` int(11) NOT NULL default '0'  COMMENT 'see define.php *_TYPE constant';

ALTER TABLE `glpi_plugin_archires_query_types` DROP INDEX `FK_query`;
ALTER TABLE `glpi_plugin_archires_query_types` DROP INDEX `type`;
ALTER TABLE `glpi_plugin_archires_query_types` DROP INDEX `type_query`;
ALTER TABLE `glpi_plugin_archires_query_types` DROP INDEX `device_type`;

ALTER TABLE `glpi_plugin_archires_query_types` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_archires_query_types` CHANGE `type_query` `querytype` int(11) NOT NULL default '0' COMMENT 'RELATION to the 3 type of archires (type)';
ALTER TABLE `glpi_plugin_archires_query_types` CHANGE `type` `type` int(11) NOT NULL default '0';
ALTER TABLE `glpi_plugin_archires_query_types` CHANGE `device_type` `itemtype` int(11) NOT NULL default '0'  COMMENT 'see define.php *_TYPE constant';
ALTER TABLE `glpi_plugin_archires_query_types` CHANGE `FK_query` `queries_id` int(11) NOT NULL default '0' COMMENT 'RELATION to the 3 queries tables (id)';

ALTER TABLE `glpi_plugin_archires_query_types` ADD INDEX (`querytype`);
ALTER TABLE `glpi_plugin_archires_query_types` ADD INDEX (`type`);
ALTER TABLE `glpi_plugin_archires_query_types` ADD INDEX (`itemtype`);
ALTER TABLE `glpi_plugin_archires_query_types` ADD INDEX (`queries_id`);

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