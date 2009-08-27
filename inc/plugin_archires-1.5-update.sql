ALTER TABLE `glpi_plugin_archires_profiles` CHANGE `is_default` `is_default` smallint(6) NOT NULL default '0';
UPDATE `glpi_plugin_archires_profiles` SET `is_default` = '0' WHERE `is_default` = '1';
UPDATE `glpi_plugin_archires_profiles` SET `is_default` = '1' WHERE `is_default` = '2';

ALTER TABLE `glpi_plugin_archires_color` RENAME `glpi_plugin_archires_color_iface`;

ALTER TABLE `glpi_plugin_archires_config` RENAME `glpi_plugin_archires_image_device` ;

DROP TABLE `glpi_plugin_archires_display` ;

DROP TABLE IF EXISTS `glpi_plugin_archires_color_state`;
CREATE TABLE `glpi_plugin_archires_color_state` (
	`ID` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`state` INT( 11 ) NOT NULL ,
	`color` VARCHAR( 50 ) collate utf8_unicode_ci NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_query_location`;
CREATE TABLE `glpi_plugin_archires_query_location` (
	`ID` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`FK_entities` int(11) NOT NULL default '0',
	`name` VARCHAR( 50 ) collate utf8_unicode_ci NOT NULL,
	`location` VARCHAR( 50 ) collate utf8_unicode_ci NOT NULL DEFAULT '0',
	`child` smallint(6) NOT NULL default '0',
	`network` INT( 11 ) NOT NULL DEFAULT '0',
	`status` INT( 11 ) NOT NULL DEFAULT '0',
	`FK_group` INT( 11 ) NOT NULL DEFAULT '0',
	`FK_config` INT( 11 ) NOT NULL DEFAULT '0',
	`FK_vlan` INT( 11 ) NOT NULL DEFAULT '0',
	`link` smallint(6) NOT NULL default '1',
	`notes` LONGTEXT,
	`deleted` smallint(6) NOT NULL default '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_query_switch`;
CREATE TABLE `glpi_plugin_archires_query_switch` (
	`ID` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`FK_entities` int(11) NOT NULL default '0',
	`name` VARCHAR( 50 ) collate utf8_unicode_ci NOT NULL,
	`switch` INT( 11 ) NOT NULL DEFAULT '0',
	`network` INT( 11 ) NOT NULL DEFAULT '0',
	`status` INT( 11 ) NOT NULL DEFAULT '0',
	`FK_group` INT( 11 ) NOT NULL DEFAULT '0',
	`FK_config` INT( 11 ) NOT NULL DEFAULT '0',
	`FK_vlan` INT( 11 ) NOT NULL DEFAULT '0',
	`link` smallint(6) NOT NULL default '1',
	`notes` LONGTEXT,
	`deleted` smallint(6) NOT NULL default '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_query_applicatifs`;
CREATE TABLE `glpi_plugin_archires_query_applicatifs` (
	`ID` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`FK_entities` int(11) NOT NULL default '0',
	`name` VARCHAR( 50 ) collate utf8_unicode_ci NOT NULL,
	`applicatifs` INT( 11 ) NOT NULL DEFAULT '0',
	`network` INT( 11 ) NOT NULL DEFAULT '0',
	`status` INT( 11 ) NOT NULL DEFAULT '0',
	`FK_group` INT( 11 ) NOT NULL DEFAULT '0',
	`FK_config` INT( 11 ) NOT NULL DEFAULT '0',
	`FK_vlan` INT( 11 ) NOT NULL DEFAULT '0',
	`link` smallint(6) NOT NULL default '1',
	`notes` LONGTEXT,
	`deleted` smallint(6) NOT NULL default '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_query_type`;
CREATE TABLE `glpi_plugin_archires_query_type` (
	`ID` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`type_query` INT( 11 ) NOT NULL ,
	`type` INT( 11 ) NOT NULL ,
	`device_type` INT( 11 ) NOT NULL,
	`FK_query` INT( 11 ) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_archires_config`;
CREATE TABLE `glpi_plugin_archires_config` (
	`ID` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`FK_entities` int(11) NOT NULL default '0',
	`name` VARCHAR( 250 ) collate utf8_unicode_ci NOT NULL,
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
	`system` smallint(6) NOT NULL default '0',
	`engine` smallint(6) NOT NULL default '0',
	`format` smallint(6) NOT NULL default '0',
	`deleted` smallint(6) NOT NULL default '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_archires_config` ( `ID`,`FK_entities`,`name`, `computer` , `networking`, `printer`, `peripheral`, `phone`,`display_ports`,`display_ip`,`display_type`,`display_state`,`display_location`,`display_entity`,`system`,`engine`,`format`) VALUES ('1','0','default','1', '1','1','1','1','0','0','0','0','0','0','0','0','1');

INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3000','2','1','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3000','3','2','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3000','4','3','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3000','5','4','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3000','6','5','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3000','7','6','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3000','8','7','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3000','9','8','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3001','2','1','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3001','3','2','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3001','4','3','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3001','5','4','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3001','6','5','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3001','7','6','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3001','8','7','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3002','2','1','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3002','3','2','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3002','4','3','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3002','5','4','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3002','6','5','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3002','7','6','0');
INSERT INTO `glpi_displayprefs` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'3002','8','7','0');