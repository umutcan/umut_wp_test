

DROP TABLE IF EXISTS `ZIGCONNECT_TABLENAME_ZC_CONN`;


CREATE TABLE `ZIGCONNECT_TABLENAME_ZC_CONN` (
	`zc_conn_id` 				BIGINT(20) 		UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`zc_conn_name`				VARCHAR(255)				NOT NULL DEFAULT '',
	`zc_conn_slug`				VARCHAR(255)				NOT NULL DEFAULT '',
	`zc_conn_from` 				VARCHAR(255) 				NOT NULL DEFAULT '',
	`zc_conn_to` 				VARCHAR(255) 				NOT NULL DEFAULT '',
	`zc_conn_reciprocal`		TINYINT(1) 					NOT NULL DEFAULT '0',
	PRIMARY KEY 				(`zc_conn_id`),
	KEY `zc_conn_from` 			(`zc_conn_from`),
	KEY `zc_conn_to` 			(`zc_conn_to`),
	KEY `zc_conn_reciprocal`	(`zc_conn_reciprocal`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ZIGCONNECT_TABLENAME_ZC_FIELD`;


CREATE TABLE `ZIGCONNECT_TABLENAME_ZC_FIELD` (
	`zc_field_id` 				BIGINT(20) 		UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`zc_conn_id` 				BIGINT(20) 		UNSIGNED	NOT NULL DEFAULT '0',
	`zc_field_type` 			VARCHAR(255) 				NOT NULL DEFAULT 'TEXT',
	`zc_field_name` 			VARCHAR(255) 				NOT NULL DEFAULT '',
	`zc_field_prompt` 			VARCHAR(255) 				NOT NULL DEFAULT '',
	`zc_field_size` 			BIGINT(20) 		UNSIGNED	NOT NULL DEFAULT '10',
	`zc_field_order` 			BIGINT(20) 		UNSIGNED	NOT NULL DEFAULT '0',
	PRIMARY KEY 				(`zc_field_id`),
	KEY `zc_conn_id` 			(`zc_conn_id`),
	KEY `zc_field_name` 		(`zc_field_name`),
	KEY `zc_field_order` 		(`zc_field_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ZIGCONNECT_TABLENAME_ZC_LINK`;


CREATE TABLE `ZIGCONNECT_TABLENAME_ZC_LINK` (
	`zc_link_id` 				BIGINT(20) 		UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`zc_conn_id` 				BIGINT(20) 		UNSIGNED	NOT NULL DEFAULT '0',
	`zc_link_from` 				BIGINT(20) 		UNSIGNED	NOT NULL DEFAULT '0',
	`zc_link_to` 				BIGINT(20) 		UNSIGNED	NOT NULL DEFAULT '0',
	PRIMARY KEY 				(`zc_link_id`),
	KEY `zc_conn_id` 			(`zc_conn_id`),
	KEY `zc_link_from` 			(`zc_link_from`),
	KEY `zc_link_to` 			(`zc_link_to`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ZIGCONNECT_TABLENAME_ZC_DATA`;


CREATE TABLE `ZIGCONNECT_TABLENAME_ZC_DATA` (
	`zc_data_id` 				BIGINT(20) 		UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`zc_link_id` 				BIGINT(20) 		UNSIGNED	NOT NULL DEFAULT '0',
	`zc_field_id` 				BIGINT(20) 		UNSIGNED	NOT NULL DEFAULT '0',
	`zc_conn_id` 				BIGINT(20) 		UNSIGNED	NOT NULL DEFAULT '0',
	`zc_data_value`				LONGTEXT,
	PRIMARY KEY 				(`zc_data_id`),
	KEY `zc_link_id` 			(`zc_link_id`),
	KEY `zc_field_id` 			(`zc_field_id`),
	KEY `zc_conn_id` 			(`zc_conn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


/* EOF */
