CREATE TABLE `glpi_plugin_archires_color` (
	`ID` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`iface` INT( 11 ) NOT NULL ,
	`color` VARCHAR( 50 ) collate utf8_unicode_ci NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `glpi_plugin_archires_profiles` (
	`ID` int(11) NOT NULL auto_increment,
	`name` varchar(255) collate utf8_unicode_ci default NULL,
	`interface` varchar(50) collate utf8_unicode_ci NOT NULL default 'archires',
	`is_default` enum('0','1') NOT NULL default '0',
	`archires` char(1) default NULL,
	PRIMARY KEY  (`ID`),
	KEY `interface` (`interface`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_archires_profiles` ( `ID`, `name` , `interface`, `is_default`, `archires`) VALUES ('1', 'post-only','archires','1',NULL);
INSERT INTO `glpi_plugin_archires_profiles` ( `ID`, `name` , `interface`, `is_default`, `archires`) VALUES ('2', 'normal','archires','0',NULL);
INSERT INTO `glpi_plugin_archires_profiles` ( `ID`, `name` , `interface`, `is_default`, `archires`) VALUES ('3', 'admin','archires','0','r');
INSERT INTO `glpi_plugin_archires_profiles` ( `ID`, `name` , `interface`, `is_default`, `archires`) VALUES ('4', 'super-admin','archires','0','r');
ALTER TABLE `glpi_display` ADD `display_ip` ENUM( '1', '0' ) NOT NULL DEFAULT '0';
ALTER TABLE `glpi_display` ADD `system` ENUM( '1', '0' ) NOT NULL DEFAULT '0';
