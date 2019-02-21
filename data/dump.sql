-- MySQL dump 10.13  Distrib 5.6.43, for FreeBSD12.0 (i386)
--
-- Host: localhost    Database: simba4
-- ------------------------------------------------------
-- Server version	5.6.43-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `catalog_category`
--

DROP TABLE IF EXISTS `catalog_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subid` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `xml_id` char(127) DEFAULT NULL,
  `name` char(255) DEFAULT NULL,
  `url` char(255) DEFAULT NULL,
  `poz` int(11) DEFAULT NULL,
  `public` int(11) DEFAULT NULL,
  `info` text,
  `title` char(255) DEFAULT NULL,
  `keywords` char(255) DEFAULT NULL,
  `description` varchar(5000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subid` (`subid`,`level`),
  KEY `url` (`url`),
  KEY `public` (`public`),
  KEY `xml_id` (`xml_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='дерево категорий';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `catalog_category2tovar`
--

DROP TABLE IF EXISTS `catalog_category2tovar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_category2tovar` (
  `catalog_category` int(11) NOT NULL,
  `catalog_tovar` int(11) NOT NULL,
  PRIMARY KEY (`catalog_category`,`catalog_tovar`),
  KEY `catalog_category2tovar_fk1` (`catalog_tovar`),
  CONSTRAINT `catalog_category2tovar_fk` FOREIGN KEY (`catalog_category`) REFERENCES `catalog_category` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catalog_category2tovar_fk1` FOREIGN KEY (`catalog_tovar`) REFERENCES `catalog_tovar` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='товар-категории';
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `catalog_currency`
--

DROP TABLE IF EXISTS `catalog_currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_currency` (
  `currency` char(3) NOT NULL COMMENT 'буквенный код',
  `amount_cnt` int(11) NOT NULL DEFAULT '1' COMMENT 'Номинал',
  `amount` decimal(18,4) DEFAULT NULL COMMENT 'курс по умолчанию',
  `poz` int(11) NOT NULL DEFAULT '100' COMMENT 'порядок в списках',
  `numcode` char(3) DEFAULT NULL COMMENT 'цифровой код',
  `base` int(1) NOT NULL DEFAULT '0' COMMENT 'флаг базовой валюты',
  `current_base_rate` decimal(26,12) DEFAULT NULL COMMENT 'текущий установленый курс обмена',
  PRIMARY KEY (`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_currency`
--

LOCK TABLES `catalog_currency` WRITE;
/*!40000 ALTER TABLE `catalog_currency` DISABLE KEYS */;
INSERT INTO `catalog_currency` VALUES ('EUR',1,71.7100,300,'978',0,71.710000000000),('RUB',1,1.0000,100,'643',1,1.000000000000),('USD',1,64.8100,200,'840',0,64.810000000000);
/*!40000 ALTER TABLE `catalog_currency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog_measure`
--

DROP TABLE IF EXISTS `catalog_measure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_measure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` int(11) NOT NULL,
  `measure_title` varchar(500) DEFAULT NULL,
  `symbol_rus` varchar(20) DEFAULT NULL,
  `symbol_intl` varchar(20) DEFAULT NULL,
  `symbol_letter_intl` varchar(20) DEFAULT NULL,
  `is_default` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='единицы измерения';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_measure`
--

LOCK TABLES `catalog_measure` WRITE;
/*!40000 ALTER TABLE `catalog_measure` DISABLE KEYS */;
INSERT INTO `catalog_measure` VALUES (1,6,'Метр','м','m','MTR',0),(2,112,'Литр','л.','l','LTR',0),(3,163,'Грамм','г','g','GRM',0),(4,166,'Килограмм','кг','kg','KGM',0),(5,796,'Штука','шт','pc. 1','PCE. NMB',1);
/*!40000 ALTER TABLE `catalog_measure` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog_store`
--

DROP TABLE IF EXISTS `catalog_store`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) DEFAULT NULL COMMENT 'имя склада',
  `public` int(1) NOT NULL DEFAULT '1' COMMENT 'активен',
  `address` varchar(245) NOT NULL COMMENT 'адрес',
  `description` text COMMENT 'свободное описание',
  `xml_id` varchar(255) DEFAULT NULL COMMENT 'согласование с 1С',
  `poz` int(11) NOT NULL DEFAULT '100' COMMENT 'порядок',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='склад магазина';
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `catalog_tovar`
--

DROP TABLE IF EXISTS `catalog_tovar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_tovar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `public` int(11) DEFAULT NULL,
  `xml_id` char(127) DEFAULT NULL,
  `name` char(255) DEFAULT NULL,
  `url` char(255) DEFAULT NULL,
  `poz` int(11) DEFAULT NULL,
  `anons` text,
  `info` text,
  `title` char(255) DEFAULT NULL,
  `keywords` char(255) DEFAULT NULL,
  `description` varchar(5000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `public` (`public`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='сам товар';
/*!40101 SET character_set_client = @saved_cs_client */;


delete from `design_tables` where interface_name like 'catalog%';

INSERT INTO `design_tables` (`interface_name`, `table_name`, `table_type`, `col_name`, `caption_style`, `row_type`, `col_por`, `pole_spisok_sql`, `pole_global_const`, `pole_prop`, `pole_type`, `pole_style`, `pole_name`, `default_sql`, `functions_befo`, `functions_after`, `functions_befo_out`, `functions_befo_del`, `properties`, `value`, `validator`, `sort_item_flag`, `col_function_array`) VALUES 
  ('catalog_category', 'catalog_category', 1, '1,1,1,1,1,0', 'a:3:{s:10:\"owner_user\";s:1:\"1\";s:11:\"owner_group\";s:1:\"1\";s:10:\"permission\";i:416;}', 0, 0, '', '', 'id,subid,level', '', '', '', '', '', '', '', '', '', '', 'catalog_category', 1, NULL),
  ('catalog_category', 'catalog_category', 1, 'poz', '', 2, 2, '', NULL, 'size=2', '2', NULL, 'poz', '', '', '', '', '', 'a:2:{i:0;s:4:\"Text\";i:1;s:1:\"0\";}', '', 'N;', 0, NULL),
  ('catalog_category', 'catalog_category', 1, 'name', '', 2, 3, '', NULL, '', '2', NULL, 'name', '', '', '', '', '', 'a:2:{i:0;s:4:\"Text\";i:1;s:1:\"0\";}', '', 'N;', 0, NULL),
  ('catalog_category', 'catalog_category', 1, 'id', '', 2, 100, '', NULL, '', '56', NULL, 'id', '', '', '', '', '', 'a:6:{i:0;s:3:\"0,0\";i:1;s:35:\"catalog_category,catalog_tovar_node\";i:2;s:4:\"menu\";i:3;s:0:\"\";i:4;s:0:\"\";i:5;s:1:\"0\";}', 0xD0A0D0B5D0B4D0B0D0BAD182D0B8D180D0BED0B2D0B0D182D18C20D183D0B7D0B5D0BB2CD0A0D0B5D0B4D0B0D0BAD182D0B8D180D0BED0B2D0B0D182D18C20D182D0BED0B2D0B0D180, 'N;', 0, NULL),
  ('catalog_category', 'catalog_category', 0, 'img', 'a:3:{s:10:\"owner_user\";s:1:\"1\";s:11:\"owner_group\";s:1:\"1\";s:10:\"permission\";i:416;}', 0, 1, 'select * from catalog_category where id=$get_interface_input', '', '0,0,0,0', 'img', '', 'id', 'delete from catalog_category where id=$id', '', '', '', '', '', 0x613A333A7B733A32343A22666F726D5F656C656D656E74735F6E65775F7265636F7264223B733A313A2230223B733A32343A22666F726D5F656C656D656E74735F6A6D705F7265636F7264223B733A313A2230223B733A31393A226372656174655F6E65775F7A61705F666C6167223B733A313A2230223B7D, 'catalog_category', 1, NULL),
  ('catalog_category', 'catalog_category', 0, 'name', '', 2, 3, '', NULL, 'size=120', '2', NULL, 'name', NULL, '', '', '', '', 'a:2:{i:0;s:4:\"Text\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, 'name', '', 3, NULL, '', NULL, 'size=120', '2', NULL, 'name', NULL, '', '', '', '', 'a:2:{i:0;s:4:\"Text\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, 'url', '', 2, 4, '', NULL, 'size=120', '2', NULL, 'url', NULL, '', '', '', '', 'a:2:{i:0;s:4:\"Text\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, 'url', '', 3, NULL, '', NULL, 'size=120', '2', NULL, 'url', NULL, 'Mf\\Catalog\\Lib\\Func\\CreateUrlCategory', '', '', '', 'a:2:{i:0;s:4:\"Text\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, 'public', '', 2, 5, '', NULL, '', '20', NULL, 'public', NULL, '', '', '', '', 'a:3:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, 'public', '', 3, NULL, '', NULL, '', '20', NULL, 'public', NULL, '', '', '', '', 'a:3:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, '1', '', 2, 100, '', NULL, '', '19', NULL, 'save', NULL, '', '', '', '', 'a:2:{i:0;s:1:\"1\";i:1;s:16:\"Добавить\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, '1', '', 3, NULL, '', NULL, '', '19', NULL, 'save', NULL, '', '', '', '', 'a:2:{i:0;s:1:\"1\";i:1;s:18:\"Сохранить\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, 'id', '', 2, 1, '', NULL, '', '1', NULL, '', NULL, '', '', '', '', 'a:2:{i:0;s:1:\"0\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, 'id', '', 3, NULL, '', NULL, '', '1', NULL, 'id', NULL, '', '', '', '', 'a:2:{i:0;s:1:\"0\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, 'xml_id', '', 2, 2, '', NULL, '', '2', NULL, 'xml_id', NULL, '', '', '', '', 'a:2:{i:0;s:4:\"Text\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, 'xml_id', '', 3, NULL, '', NULL, 'size=120', '2', NULL, 'xml_id', NULL, '', '', '', '', 'a:2:{i:0;s:4:\"Text\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, 'title', '', 2, 40, '', NULL, 'cols=120 rows=4', '3', NULL, 'title', NULL, '', '', '', '', 'a:1:{i:0;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, 'title', '', 3, NULL, '', NULL, 'cols=120 rows=4', '3', NULL, 'title', NULL, '', '', '', '', 'a:1:{i:0;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, 'keywords', '', 2, 41, '', NULL, 'cols=120 rows=4', '3', NULL, 'keywords', NULL, '', '', '', '', 'a:1:{i:0;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, 'keywords', '', 3, NULL, '', NULL, 'cols=120 rows=4', '3', NULL, 'keywords', NULL, '', '', '', '', 'a:1:{i:0;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, 'description', '', 2, 42, '', NULL, 'cols=120 rows=4', '3', NULL, 'description', NULL, '', '', '', '', 'a:1:{i:0;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, 'description', '', 3, NULL, '', NULL, 'cols=120 rows=4', '3', NULL, 'description', NULL, '', '', '', '', 'a:1:{i:0;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, 'info', '', 2, 10, '', '[''public_media_folder'']', ',', '39', NULL, '', NULL, '', '', '', '', 'a:4:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:7:\"default\";i:3;s:7:\"default\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, 'info', '', 3, NULL, '', '[''public_media_folder'']', ',', '39', NULL, 'info', NULL, '', '', '', '', 'a:4:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:7:\"default\";i:3;s:7:\"default\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, 'img', '', 2, 11, '', NULL, '', '1', NULL, '', NULL, '', '', '', '', 'a:2:{i:0;s:1:\"0\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 0, 'img', '', 3, NULL, '', NULL, '', '32', NULL, 'img', NULL, '', '', '', '', 'a:2:{i:0;s:16:\"catalog_category\";i:1;s:0:\"\";}', '', 'N;', NULL, 'N;'),
  ('catalog_category', 'catalog_category', 1, 'url', '', 2, 5, '', NULL, '', '2', NULL, 'url', '', 'Mf\\Catalog\\Lib\\Func\\CreateUrlCategory', '', '', '', 'a:2:{i:0;s:4:\"Text\";i:1;s:1:\"0\";}', '', 'N;', 0, NULL),
  ('tovar_catalog_list', 'catalog_tovar', 0, 'id1', 'a:3:{s:10:\"owner_user\";s:1:\"1\";s:11:\"owner_group\";s:1:\"1\";s:10:\"permission\";i:416;}', 0, 0, 'select catalog_tovar.*,\r\ncatalog_tovar.id as id1\r\n from catalog_tovar order by name', '50', '0,0,0,0', 'id1', '', 'id', '', '', '', '', '', '', 0x613A333A7B733A32343A22666F726D5F656C656D656E74735F6E65775F7265636F7264223B733A313A2230223B733A32343A22666F726D5F656C656D656E74735F6A6D705F7265636F7264223B733A313A2230223B733A31393A226372656174655F6E65775F7A61705F666C6167223B733A313A2230223B7D, 'catalog_tovar', 1, NULL),
  ('tovar_catalog_list', 'catalog_tovar', 0, 'id', '', 2, 1, '', NULL, '', '1', NULL, '', NULL, '', '', '', '', 'a:2:{i:0;s:1:\"0\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('tovar_catalog_list', 'catalog_tovar', 0, 'id', '', 3, NULL, '', NULL, '', '56', NULL, '', NULL, '', '', '', '', 'a:6:{i:0;s:1:\"3\";i:1;s:21:\"admin_tovar/edittovar\";i:2;s:6:\"button\";i:3;s:0:\"\";i:4;s:3:\"700\";i:5;s:1:\"1\";}', 0xD0A0D0B5D0B4D0B0D0BAD182D0B8D180D0BED0B2D0B0D182D18C20D182D0BED0B2D0B0D180, 'N;', NULL, 'N;'),
  ('tovar_catalog_list', 'catalog_tovar', 0, 'name', '', 2, 4, '', NULL, '', '1', NULL, 'name', NULL, '', '', '', '', 'a:2:{i:0;s:1:\"0\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('tovar_catalog_list', 'catalog_tovar', 0, 'name', '', 3, NULL, '', NULL, '', '1', NULL, '', NULL, '', '', '', '', 'a:2:{i:0;s:1:\"0\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('tovar_catalog_list', 'catalog_tovar', 0, '', '', 1, 0, '', '', '', '56', '', '', '', '', '', '', '', 'a:5:{i:0;s:3:\"3,3\";i:1;s:48:\"admin_tovar/createtovar,admin_tovar/createtovar1\";i:2;s:4:\"menu\";i:3;s:0:\"\";i:4;s:0:\"\";}', 0xD0A1D0BED0B7D0B4D0B0D182D18C20D182D0BED0B2D0B0D18020D0B1D0B5D0B720D182D0BED180D0B3D0BED0B2D18BD18520D0BFD180D0B5D0B4D0BBD0BED0B6D0B5D0BDD0B8D0B92CD0A1D0BED0B7D0B4D0B0D182D18C20D182D0BED0B2D0B0D18020D18120D182D0BED180D0B3D0BED0B2D18BD0BCD0B820D0BFD180D0B5D0B4D0BBD0BED0B6D0B5D0BDD0B8D18FD0BCD0B8, '', 0, NULL),
  ('create_catalog_tovar_base', 'catalog_tovar', 0, '', 'a:3:{s:10:\"owner_user\";s:1:\"1\";s:11:\"owner_group\";s:1:\"1\";s:10:\"permission\";i:416;}', 0, 1, 'select * from catalog_tovar where id=\"$get_interface_input\"', '', '0,0,0,0', '', '', 'id', '', '', '', '', '', '', 0x613A333A7B733A32343A22666F726D5F656C656D656E74735F6E65775F7265636F7264223B733A313A2230223B733A32343A22666F726D5F656C656D656E74735F6A6D705F7265636F7264223B733A313A2230223B733A31393A226372656174655F6E65775F7A61705F666C6167223B733A313A2230223B7D, 'catalog_tovar', 1, NULL),
  ('create_catalog_tovar_base', 'catalog_tovar', 0, 'name', '', 2, 2, '', NULL, 'size=60', '2', NULL, 'name', NULL, '', '', '', '', 'a:2:{i:0;s:4:\"Text\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_base', 'catalog_tovar', 0, 'name', '', 3, NULL, '', NULL, 'size=60', '2', NULL, 'name', NULL, '', '', '', '', 'a:2:{i:0;s:4:\"Text\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_base', 'catalog_tovar', 0, 'url', '', 2, 3, '', NULL, 'size=60', '2', NULL, 'url', NULL, '', '', '', '', 'a:2:{i:0;s:4:\"Text\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_base', 'catalog_tovar', 0, 'url', '', 3, NULL, '', NULL, 'size=60', '2', NULL, 'url', NULL, '', '', '', '', 'a:2:{i:0;s:4:\"Text\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_base', 'catalog_tovar', 0, 'xml_id', '', 2, 10, '', NULL, 'size=60', '2', NULL, 'xml_id', NULL, '', '', '', '', 'a:2:{i:0;s:4:\"Text\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_base', 'catalog_tovar', 0, 'xml_id', '', 3, NULL, '', NULL, 'size=60', '2', NULL, 'xml_id', NULL, '', '', '', '', 'a:2:{i:0;s:4:\"Text\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_base', 'catalog_tovar', 0, 'public', '', 2, 4, '', NULL, '', '20', NULL, 'public', NULL, '', '', '', '', 'a:3:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_base', 'catalog_tovar', 0, 'public', '', 3, NULL, '', NULL, '', '20', NULL, 'public', NULL, '', '', '', '', 'a:3:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_base', 'catalog_tovar', 0, 'poz', '', 2, 5, '', NULL, '', '2', NULL, 'poz', NULL, '', '', '', '', 'a:2:{i:0;s:4:\"Text\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_base', 'catalog_tovar', 0, 'poz', '', 3, NULL, '', NULL, '', '2', NULL, 'poz', NULL, '', '', '', '', 'a:2:{i:0;s:4:\"Text\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_base', 'catalog_tovar', 0, 'title', '', 2, 10, '', NULL, 'cols=60 rows=4', '3', NULL, 'title', NULL, '', '', '', '', 'a:1:{i:0;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_base', 'catalog_tovar', 0, 'title', '', 3, NULL, '', NULL, 'cols=60 rows=4', '3', NULL, 'title', NULL, '', '', '', '', 'a:1:{i:0;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_base', 'catalog_tovar', 0, 'keywords', '', 2, 11, '', NULL, 'cols=60 rows=4', '3', NULL, 'keywords', NULL, '', '', '', '', 'a:1:{i:0;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_base', 'catalog_tovar', 0, 'keywords', '', 3, NULL, '', NULL, 'cols=60 rows=4', '3', NULL, 'keywords', NULL, '', '', '', '', 'a:1:{i:0;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_base', 'catalog_tovar', 0, 'description', '', 2, 12, '', NULL, 'cols=60 rows=4', '3', NULL, 'description', NULL, '', '', '', '', 'a:1:{i:0;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_base', 'catalog_tovar', 0, 'description', '', 3, NULL, '', NULL, 'cols=60 rows=4', '3', NULL, 'description', NULL, '', '', '', '', 'a:1:{i:0;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_anons', 'catalog_tovar', 0, 'img_anons', 'a:3:{s:10:\"owner_user\";s:1:\"1\";s:11:\"owner_group\";s:1:\"1\";s:10:\"permission\";i:416;}', 0, 1, 'select * from catalog_tovar where id=\"$get_interface_input\"', '', '0,0,0,0', 'id', '', 'id', '', '', '', '', '', '', 0x613A333A7B733A32343A22666F726D5F656C656D656E74735F6E65775F7265636F7264223B733A313A2230223B733A32343A22666F726D5F656C656D656E74735F6A6D705F7265636F7264223B733A313A2230223B733A31393A226372656174655F6E65775F7A61705F666C6167223B733A313A2230223B7D, 'catalog_tovar', 1, NULL),
  ('create_catalog_tovar_anons', 'catalog_tovar', 0, 'anons', '', 2, 5, '', '[''public_media_folder'']', ',', '39', NULL, 'anons', NULL, '', '', '', '', 'a:4:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:7:\"default\";i:3;s:7:\"default\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_anons', 'catalog_tovar', 0, 'anons', '', 3, NULL, '', '[''public_media_folder'']', ',', '39', NULL, 'anons', NULL, '', '', '', '', 'a:4:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:7:\"default\";i:3;s:7:\"default\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_detal', 'catalog_tovar', 0, 'img', 'a:3:{s:10:\"owner_user\";s:1:\"1\";s:11:\"owner_group\";s:1:\"1\";s:10:\"permission\";i:416;}', 0, 1, 'select * from catalog_tovar where id=\"$get_interface_input\"', '', '0,0,0,0', '', '', 'id', '', '', '', '', '', '', 0x613A333A7B733A32343A22666F726D5F656C656D656E74735F6E65775F7265636F7264223B733A313A2230223B733A32343A22666F726D5F656C656D656E74735F6A6D705F7265636F7264223B733A313A2230223B733A31393A226372656174655F6E65775F7A61705F666C6167223B733A313A2230223B7D, 'catalog_tovar', 1, NULL),
  ('create_catalog_tovar_detal', 'catalog_tovar', 0, 'img', '', 2, 5, '', NULL, '', '32', NULL, 'img', NULL, '', '', '', '', 'a:2:{i:0;s:19:\"catalog_tovar_detal\";i:1;s:0:\"\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_detal', 'catalog_tovar', 0, 'img', '', 3, NULL, '', NULL, '', '32', NULL, 'img', NULL, '', '', '', '', 'a:2:{i:0;s:19:\"catalog_tovar_detal\";i:1;s:0:\"\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_detal', 'catalog_tovar', 0, 'info', '', 2, 7, '', '[''public_media_folder'']', ',', '39', NULL, 'info', NULL, '', '', '', '', 'a:4:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:7:\"default\";i:3;s:7:\"default\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_detal', 'catalog_tovar', 0, 'info', '', 3, NULL, '', '[''public_media_folder'']', ',', '39', NULL, 'info', NULL, '', '', '', '', 'a:4:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:7:\"default\";i:3;s:7:\"default\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_category', 'catalog_tovar', 0, 't2c', 'a:3:{s:10:\"owner_user\";s:1:\"1\";s:11:\"owner_group\";s:1:\"1\";s:10:\"permission\";i:416;}', 0, 1, 'select catalog_tovar.id, \r\n(select group_concat(catalog_category) from catalog_category2tovar where catalog_tovar=catalog_tovar.id  ) as t2c \r\nfrom catalog_tovar  where id=\"$get_interface_input\"', '', '0,0,0,0', 't2c', '', 'id', '', '', '', '', '', 'Mf\\Catalog\\Lib\\Func\\Savet2c', 0x613A333A7B733A32343A22666F726D5F656C656D656E74735F6E65775F7265636F7264223B733A313A2230223B733A32343A22666F726D5F656C656D656E74735F6A6D705F7265636F7264223B733A313A2230223B733A31393A226372656174655F6E65775F7A61705F666C6167223B733A313A2230223B7D, '', 0, NULL),
  ('create_catalog_tovar_category', 'catalog_tovar', 0, 't2c', '', 2, 2, '', NULL, 'size=30 style=\"min-width:300px\"', '22', NULL, 't2c', NULL, '', '', 'Mf\\Catalog\\Lib\\Func\\CategoryTreeTovar', '', 'N;', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_category', 'catalog_tovar', 0, 't2c', '', 3, NULL, '', NULL, 'size=30 style=\"min-width:300px\"', '22', NULL, 't2c', NULL, 'Mf\\Catalog\\Lib\\Func\\t2c', '', 'Mf\\Catalog\\Lib\\Func\\CategoryTreeTovar', '', 'N;', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_anons', 'catalog_tovar', 0, 'img_anons', '', 2, 2, '', NULL, '', '32', NULL, 'img_anons', NULL, '', '', '', '', 'a:2:{i:0;s:19:\"catalog_tovar_anons\";i:1;s:0:\"\";}', '', 'N;', NULL, 'N;'),
  ('create_catalog_tovar_anons', 'catalog_tovar', 0, 'img_anons', '', 3, NULL, '', NULL, '', '32', NULL, 'img_anons', NULL, '', '', '', '', 'a:2:{i:0;s:19:\"catalog_tovar_anons\";i:1;s:0:\"\";}', '', 'N;', NULL, 'N;'),
  ('tovar_catalog_list', 'catalog_tovar', 0, 'id1', '', 2, 2, '', NULL, '', '1', NULL, '', NULL, '', '', '', '', 'a:2:{i:0;s:1:\"0\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;'),
  ('tovar_catalog_list', 'catalog_tovar', 0, 'id1', '', 3, NULL, '', NULL, '', '1', NULL, '', NULL, '', '', '', '', 'a:2:{i:0;s:1:\"0\";i:1;s:1:\"0\";}', '', 'N;', NULL, 'N;');

delete from `design_tables_text_interfase` where interface_name like 'catalog%';

INSERT INTO `design_tables_text_interfase` (`language`, `table_type`, `interface_name`, `item_name`, `text`) VALUES 
  ('ru_RU', 1, 'catalog_category', 'coment0', ''),
  ('ru_RU', 1, 'catalog_category', 'caption0', '<h2>Разделы (категории) товара</h2>'),
  ('ru_RU', 1, 'catalog_category', 'caption_col_name', ' Имя'),
  ('ru_RU', 1, 'catalog_category', 'caption_col_url', ' URL'),
  ('ru_RU', 0, 'tovar_catalog_list', 'caption_col_1', 'Операция'),
  ('ru_RU', 0, 'tovar_catalog_list', 'caption_col_name', 'Наименование'),
  ('ru_RU', 0, 'tovar_catalog_list', 'caption_dop_0', 'Создать '),
  ('ru_RU', 0, 'create_catalog_tovar_base', 'caption_col_name', 'Наименование'),
  ('ru_RU', 0, 'create_catalog_tovar_base', 'caption_col_url', 'URL (автомат)'),
  ('ru_RU', 0, 'create_catalog_tovar_base', 'caption_col_xml_id', 'XML-ID'),
  ('ru_RU', 0, 'create_catalog_tovar_base', 'caption_col_public', 'Публиковать'),
  ('ru_RU', 0, 'create_catalog_tovar_base', 'caption_col_poz', 'сортировка'),
  ('ru_RU', 0, 'create_catalog_tovar_base', 'caption_col_title', 'TITLE'),
  ('ru_RU', 0, 'create_catalog_tovar_base', 'caption_col_keywords', 'KEYWORDS'),
  ('ru_RU', 0, 'create_catalog_tovar_base', 'caption_col_description', 'DESCRIPTION'),
  ('ru_RU', 0, 'create_catalog_tovar_anons', 'caption_col_img', 'Титульное фото'),
  ('ru_RU', 0, 'create_catalog_tovar_anons', 'caption_col_anons', 'анонс'),
  ('ru_RU', 0, 'create_catalog_tovar_detal', 'caption_col_img', 'Фото товара'),
  ('ru_RU', 0, 'create_catalog_tovar_detal', 'caption_col_info', 'Подробно'),
  ('ru_RU', 0, 'create_catalog_tovar_anons', 'caption_col_img_anons', 'Титульное фото'),
  ('ru_RU', 0, 'tovar_catalog_list', 'caption_col_id1', 'ID'),
  ('ru_RU', 0, 'tovar_catalog_list', 'caption0', 'Список товара'),
  ('ru_RU', 0, 'catalog_category', 'caption_col_public', 'Публиковать'),
  ('ru_RU', 0, 'catalog_category', 'caption_col_1', 'Операция'),
  ('ru_RU', 0, 'catalog_category', 'caption_col_id', 'Внутренний код'),
  ('ru_RU', 0, 'catalog_category', 'caption_col_name', 'Имя'),
  ('ru_RU', 0, 'catalog_category', 'caption_col_url', 'URL (автоматически)'),
  ('ru_RU', 0, 'catalog_category', 'caption_col_xml_id', 'код 1С'),
  ('ru_RU', 0, 'catalog_category', 'caption_col_title', 'TITLE'),
  ('ru_RU', 0, 'catalog_category', 'caption_col_keywords', 'KEYWORDS'),
  ('ru_RU', 0, 'catalog_category', 'caption_col_description', 'DESCRIPTION'),
  ('ru_RU', 0, 'catalog_category', 'caption_col_info', 'Контент раздела'),
  ('ru_RU', 0, 'catalog_category', 'caption_col_img', 'Титульная картинка'),
  ('ru_RU', 0, 'catalog_category', 'caption_col_subid', 'Родитель<br>обновите родительское окно'),
  ('ru_RU', 0, 'catalog_category', 'caption0', 'Редактирование элемента каталога');
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-02-19 15:42:34
