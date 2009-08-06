ALTER TABLE `glpi_plugin_archires_query_location` ADD INDEX `deleted` (`deleted`);
ALTER TABLE `glpi_plugin_archires_query_switch` ADD INDEX `deleted` (`deleted`);
ALTER TABLE `glpi_plugin_archires_query_applicatifs` ADD INDEX `deleted` (`deleted`);

ALTER TABLE `glpi_plugin_archires_image_device` ADD INDEX `device_type` (`device_type`);

ALTER TABLE `glpi_plugin_archires_query_type` ADD INDEX `FK_query` (`FK_query`);
ALTER TABLE `glpi_plugin_archires_query_type` ADD INDEX `type_query` (`type_query`);
ALTER TABLE `glpi_plugin_archires_query_type` ADD INDEX `type` (`type`);
ALTER TABLE `glpi_plugin_archires_query_type` ADD INDEX `device_type` (`device_type`);

ALTER TABLE `glpi_plugin_archires_color_iface` ADD INDEX `iface` (`iface`);

ALTER TABLE `glpi_plugin_archires_config` ADD INDEX `deleted` (`deleted`);
ALTER TABLE `glpi_plugin_archires_config` ADD INDEX `FK_entities` (`FK_entities`);
ALTER TABLE `glpi_plugin_archires_config` ADD INDEX `name` (`name`);

ALTER TABLE `glpi_plugin_archires_config` ADD `color` smallint(6) NOT NULL default '0';

CREATE TABLE `glpi_plugin_archires_color_vlan` (
	`ID` INT( 11 ) NOT NULL auto_increment,
	`vlan` INT( 11 ) NOT NULL ,
	`color` VARCHAR( 50 ) collate utf8_unicode_ci NOT NULL,
	PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `glpi_plugin_archires_profiles` DROP COLUMN `interface` , DROP COLUMN `is_default`;
ALTER TABLE `glpi_plugin_archires_query_location` CHANGE `status` `state` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `glpi_plugin_archires_query_switch` CHANGE `status` `state` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `glpi_plugin_archires_query_applicatifs` CHANGE `status` `state` INT( 11 ) NOT NULL DEFAULT '0';