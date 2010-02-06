DROP TABLE IF EXISTS `glpi_plugin_archires_locationqueries`;
CREATE TABLE `glpi_plugin_archires_locationqueries` (
	`id` int(11) NOT NULL auto_increment,
	`entities_id` int(11) NOT NULL default '0',
	`name` varchar(255) collate utf8_unicode_ci default NULL,
	`locations_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_locations (id)',
	`child` smallint(6) NOT NULL default '0',
	`networks_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networks (id)',
	`states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)',
	`groups_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_groups (id)',
	`plugin_archires_views_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_archires_views (id)',
	`vlans_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_vlans (id)',
	`notepad` longtext collate utf8_unicode_ci,
	`is_deleted` tinyint(1) NOT NULL default '0',
	PRIMARY KEY  (`id`),
	KEY `name` (`name`),
	KEY `entities_id` (`entities_id`),
	KEY `locations_id` (`locations_id`),
	KEY `networks_id` (`networks_id`),
	KEY `groups_id` (`groups_id`),
	KEY `plugin_archires_views_id` (`plugin_archires_views_id`),
	KEY `states_id` (`states_id`),
	KEY `vlans_id` (`vlans_id`),
	KEY `is_deleted` (`is_deleted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_networkequipmentqueries`;
CREATE TABLE `glpi_plugin_archires_networkequipmentqueries` (
	`id` int(11) NOT NULL auto_increment,
	`entities_id` int(11) NOT NULL default '0',
	`name` varchar(255) collate utf8_unicode_ci default NULL,
	`networkequipments_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networkequipments (id)',
	`networks_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networks (id)',
	`states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)',
	`groups_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_groups (id)',
	`plugin_archires_views_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_archires_views (id)',
	`vlans_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_vlans (id)',
	`notepad` longtext collate utf8_unicode_ci,
	`is_deleted` tinyint(1) NOT NULL default '0',
	PRIMARY KEY  (`id`),
	KEY `name` (`name`),
	KEY `entities_id` (`entities_id`),
	KEY `networkequipments_id` (`networkequipments_id`),
	KEY `networks_id` (`networks_id`),
	KEY `groups_id` (`groups_id`),
	KEY `plugin_archires_views_id` (`plugin_archires_views_id`),
	KEY `states_id` (`states_id`),
	KEY `vlans_id` (`vlans_id`),
	KEY `is_deleted` (`is_deleted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_appliancequeries`;
CREATE TABLE `glpi_plugin_archires_appliancequeries` (
	`id` int(11) NOT NULL auto_increment,
	`entities_id` int(11) NOT NULL default '0',
	`name` varchar(255) collate utf8_unicode_ci default NULL,
	`appliances_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_appliances (id)',
	`networks_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networks (id)',
	`states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)',
	`groups_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_groups (id)',
	`plugin_archires_views_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_archires_views (id)',
	`vlans_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_vlans (id)',
	`notepad` longtext collate utf8_unicode_ci,
	`is_deleted` tinyint(1) NOT NULL default '0',
	PRIMARY KEY  (`id`),
	KEY `name` (`name`),
	KEY `entities_id` (`entities_id`),
	KEY `appliances_id` (`appliances_id`),
	KEY `networks_id` (`networks_id`),
	KEY `groups_id` (`groups_id`),
	KEY `plugin_archires_views_id` (`plugin_archires_views_id`),
	KEY `states_id` (`states_id`),
	KEY `vlans_id` (`vlans_id`),
	KEY `is_deleted` (`is_deleted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_imageitems`;
CREATE TABLE `glpi_plugin_archires_imageitems` (
	`id` int(11) NOT NULL auto_increment,
	`type` int(11) NOT NULL default '0',
	`itemtype` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'see .class.php file',
	`img` VARCHAR( 50 ) collate utf8_unicode_ci NOT NULL,
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_querytypes`;
CREATE TABLE `glpi_plugin_archires_querytypes` (
	`id` int(11) NOT NULL auto_increment,
	`querytype` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'RELATION to the 3 type of archires (type)',
	`type` int(11) NOT NULL default '0',
	`itemtype` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'see .class.php file',
	`plugin_archires_queries_id` int(11) NOT NULL default '0' COMMENT 'RELATION to the 3 queries tables (id)',
	PRIMARY KEY  (`id`),
	KEY `plugin_archires_queries_id` (`plugin_archires_queries_id`),
	KEY `type` (`type`),
	KEY `querytype` (`querytype`),
	KEY `itemtype` (`itemtype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_networkinterfacecolors`;
CREATE TABLE `glpi_plugin_archires_networkinterfacecolors` (
	`id` int(11) NOT NULL auto_increment,
	`networkinterfaces_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networkinterfaces (id)',
	`color` VARCHAR( 50 ) collate utf8_unicode_ci NOT NULL,
	PRIMARY KEY  (`id`),
	KEY `networkinterfaces_id` (`networkinterfaces_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_statecolors`;
CREATE TABLE `glpi_plugin_archires_statecolors` (
	`id` int(11) NOT NULL auto_increment,
	`states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)',
	`color` VARCHAR( 50 ) collate utf8_unicode_ci NOT NULL,
	PRIMARY KEY  (`id`),
	KEY `states_id` (`states_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_vlancolors`;
CREATE TABLE `glpi_plugin_archires_vlancolors` (
	`id` int(11) NOT NULL auto_increment,
	`vlans_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_vlans (id)',
	`color` VARCHAR( 50 ) collate utf8_unicode_ci NOT NULL,
	PRIMARY KEY  (`id`),
	KEY `vlans_id` (`vlans_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_views`;
CREATE TABLE `glpi_plugin_archires_views` (
	`id` int(11) NOT NULL auto_increment,
	`entities_id` int(11) NOT NULL default '0',
	`name` varchar(255) collate utf8_unicode_ci default NULL,
	`computer` smallint(6) NOT NULL default '0',
	`networking` smallint(6) NOT NULL default '0',
	`printer` smallint(6) NOT NULL default '0',
	`peripheral` smallint(6) NOT NULL default '0',
	`phone` smallint(6) NOT NULL default '0',
	`display_ports` smallint(6) NOT NULL default '0',
	`display_ip` smallint(6) NOT NULL default '0',
	`display_type` smallint(6) NOT NULL default '0',
	`display_state` smallint(6) NOT NULL default '0',
	`display_location` smallint(6) NOT NULL default '0',
	`display_entity` smallint(6) NOT NULL default '0',
	`engine` smallint(6) NOT NULL default '0',
	`format` smallint(6) NOT NULL default '0',
	`color` smallint(6) NOT NULL default '0',
	`is_deleted` tinyint(1) NOT NULL default '0',
	PRIMARY KEY  (`ID`),
	KEY `name` (`name`),
	KEY `entities_id` (`entities_id`),
	KEY `is_deleted` (`is_deleted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_archires_views` ( `id`,`entities_id`,`name`, `computer` , `networking`, `printer`, `peripheral`, `phone`,`display_ports`,`display_ip`,`display_type`,`display_state`,`display_location`,`display_entity`,`engine`,`format`) VALUES ('1','0','default','1', '1','1','1','1','0','0','0','0','0','0','0','1');

DROP TABLE IF EXISTS `glpi_plugin_archires_profiles`;
CREATE TABLE `glpi_plugin_archires_profiles` (
	`id` int(11) NOT NULL auto_increment,
	`profiles_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
	`archires` char(1) collate utf8_unicode_ci default NULL,
	PRIMARY KEY  (`id`),
	KEY `profiles_id` (`profiles_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiresLocationQuery','2','1','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiresLocationQuery','3','2','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiresLocationQuery','4','3','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiresLocationQuery','5','4','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiresLocationQuery','6','5','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiresLocationQuery','7','6','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiresLocationQuery','8','7','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiresNetworkEquipmentQuery','2','1','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiresNetworkEquipmentQuery','3','2','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiresNetworkEquipmentQuery','4','3','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiresNetworkEquipmentQuery','5','4','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiresNetworkEquipmentQuery','6','5','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiresNetworkEquipmentQuery','7','6','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiresApplianceQuery','2','1','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiresApplianceQuery','3','2','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiresApplianceQuery','4','3','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiresApplianceQuery','5','4','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiresApplianceQuery','6','5','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiresApplianceQuery','7','6','0');