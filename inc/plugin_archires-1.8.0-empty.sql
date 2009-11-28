DROP TABLE IF EXISTS `glpi_plugin_archires_locationsqueries`;
CREATE TABLE `glpi_plugin_archires_locationsqueries` (
	`id` int(11) NOT NULL auto_increment,
	`entities_id` int(11) NOT NULL default '0',
	`name` varchar(255) collate utf8_unicode_ci default NULL,
	`locations_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_locations (id)',
	`child` smallint(6) NOT NULL default '0',
	`networks_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networks (id)',
	`states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)',
	`groups_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_groups (id)',
	`views_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_archires_views (id)',
	`vlans_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_vlans (id)',
	`notepad` longtext collate utf8_unicode_ci,
	`is_deleted` tinyint(1) NOT NULL default '0',
	PRIMARY KEY  (`id`),
	KEY `name` (`name`),
	KEY `entities_id` (`entities_id`),
	KEY `locations_id` (`locations_id`),
	KEY `networks_id` (`networks_id`),
	KEY `groups_id` (`groups_id`),
	KEY `views_id` (`views_id`),
	KEY `states_id` (`states_id`),
	KEY `vlans_id` (`vlans_id`),
	KEY `is_deleted` (`is_deleted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_networkequipmentsqueries`;
CREATE TABLE `glpi_plugin_archires_networkequipmentsqueries` (
	`id` int(11) NOT NULL auto_increment,
	`entities_id` int(11) NOT NULL default '0',
	`name` varchar(255) collate utf8_unicode_ci default NULL,
	`networkequipments_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networkequipments (id)',
	`networks_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networks (id)',
	`states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)',
	`groups_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_groups (id)',
	`views_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_archires_views (id)',
	`vlans_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_vlans (id)',
	`notepad` longtext collate utf8_unicode_ci,
	`is_deleted` tinyint(1) NOT NULL default '0',
	PRIMARY KEY  (`id`),
	KEY `name` (`name`),
	KEY `entities_id` (`entities_id`),
	KEY `networkequipments_id` (`networkequipments_id`),
	KEY `networks_id` (`networks_id`),
	KEY `groups_id` (`groups_id`),
	KEY `views_id` (`views_id`),
	KEY `states_id` (`states_id`),
	KEY `vlans_id` (`vlans_id`),
	KEY `is_deleted` (`is_deleted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_appliancesqueries`;
CREATE TABLE `glpi_plugin_archires_appliancesqueries` (
	`id` int(11) NOT NULL auto_increment,
	`entities_id` int(11) NOT NULL default '0',
	`name` varchar(255) collate utf8_unicode_ci default NULL,
	`appliances_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_appliances (id)',
	`networks_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networks (id)',
	`states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)',
	`groups_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_groups (id)',
	`views_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_archires_views (id)',
	`vlans_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_vlans (id)',
	`notepad` longtext collate utf8_unicode_ci,
	`is_deleted` tinyint(1) NOT NULL default '0',
	PRIMARY KEY  (`id`),
	KEY `name` (`name`),
	KEY `entities_id` (`entities_id`),
	KEY `appliances_id` (`appliances_id`),
	KEY `networks_id` (`networks_id`),
	KEY `groups_id` (`groups_id`),
	KEY `views_id` (`views_id`),
	KEY `states_id` (`states_id`),
	KEY `vlans_id` (`vlans_id`),
	KEY `is_deleted` (`is_deleted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_imageitems`;
CREATE TABLE `glpi_plugin_archires_imageitems` (
	`id` int(11) NOT NULL auto_increment,
	`type` int(11) NOT NULL default '0',
	`itemtype` int(11) NOT NULL default '0'  COMMENT 'see define.php *_TYPE constant',
	`img` VARCHAR( 50 ) collate utf8_unicode_ci NOT NULL,
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_queriestypes`;
CREATE TABLE `glpi_plugin_archires_queriestypes` (
	`id` int(11) NOT NULL auto_increment,
	`querytype` int(11) NOT NULL default '0' COMMENT 'RELATION to the 3 type of archires (type)',
	`type` int(11) NOT NULL default '0',
	`itemtype` int(11) NOT NULL default '0'  COMMENT 'see define.php *_TYPE constant',
	`queries_id` int(11) NOT NULL default '0' COMMENT 'RELATION to the 3 queries tables (id)',
	PRIMARY KEY  (`id`),
	KEY `queries_id` (`queries_id`),
	KEY `type` (`type`),
	KEY `querytype` (`querytype`),
	KEY `itemtype` (`itemtype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_networkinterfacescolors`;
CREATE TABLE `glpi_plugin_archires_networkinterfacescolors` (
	`id` int(11) NOT NULL auto_increment,
	`networkinterfaces_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_networkinterfaces (id)',
	`color` VARCHAR( 50 ) collate utf8_unicode_ci NOT NULL,
	PRIMARY KEY  (`id`),
	KEY `networkinterfaces_id` (`networkinterfaces_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_statescolors`;
CREATE TABLE `glpi_plugin_archires_statescolors` (
	`id` int(11) NOT NULL auto_increment,
	`states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)',
	`color` VARCHAR( 50 ) collate utf8_unicode_ci NOT NULL,
	PRIMARY KEY  (`id`),
	KEY `states_id` (`states_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_vlanscolors`;
CREATE TABLE `glpi_plugin_archires_vlanscolors` (
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
	`name` varchar(255) collate utf8_unicode_ci default NULL,
	`archires` char(1) collate utf8_unicode_ci default NULL,
	PRIMARY KEY  (`id`),
	KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_displayprefs` VALUES (NULL,'3000','2','1','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'3000','3','2','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'3000','4','3','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'3000','5','4','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'3000','6','5','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'3000','7','6','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'3000','8','7','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'3001','2','1','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'3001','3','2','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'3001','4','3','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'3001','5','4','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'3001','6','5','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'3001','7','6','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'3002','2','1','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'3002','3','2','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'3002','4','3','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'3002','5','4','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'3002','6','5','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'3002','7','6','0');