-- MySQL dump 10.13  Distrib 5.1.35, for Win64 (unknown)
--
-- Host: localhost    Database: westchasepub
-- ------------------------------------------------------
-- Server version	5.1.35-community

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
-- Table structure for table `cmu_apartment`
--

DROP TABLE IF EXISTS `cmu_apartment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmu_apartment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quarter` int(10) unsigned NOT NULL DEFAULT '0',
  `property` int(10) NOT NULL DEFAULT '0',
  `completed_by` varchar(255) DEFAULT NULL,
  `occupancy_rate` double DEFAULT NULL,
  `community_mgr` varchar(255) DEFAULT NULL,
  `community_mgr_email` varchar(255) DEFAULT NULL,
  `community_mgr_phone` varchar(20) DEFAULT NULL,
  `community_mgr_fax` varchar(20) DEFAULT NULL,
  `mgmt_company` varchar(255) DEFAULT NULL,
  `mgmt_company_addr` varchar(255) DEFAULT NULL,
  `supervisor` varchar(255) DEFAULT NULL,
  `supervisor_email` varchar(255) DEFAULT NULL,
  `supervisor_phone` varchar(20) DEFAULT NULL,
  `supervisor_fax` varchar(20) DEFAULT NULL,
  `owner` varchar(255) DEFAULT NULL,
  `owner_address` varchar(255) DEFAULT NULL,
  `owner_phone` varchar(20) DEFAULT NULL,
  `owner_fax` varchar(20) DEFAULT NULL,
  `comments` longtext,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `transferred` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_cmu_apartment_quarter` (`quarter`),
  CONSTRAINT `FK_cmu_apartment_quarter` FOREIGN KEY (`quarter`) REFERENCES `cmu_quarter` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=259 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmu_devsite`
--

DROP TABLE IF EXISTS `cmu_devsite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmu_devsite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quarter` int(10) unsigned NOT NULL,
  `property` int(10) NOT NULL,
  `completed_by` varchar(255) DEFAULT NULL,
  `site_size` double DEFAULT NULL,
  `frontage` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `divide` tinyint(1) NOT NULL DEFAULT '0',
  `price_sq_ft` varchar(20) DEFAULT NULL,
  `restrictions` longtext,
  `comments` longtext,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `transferred` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_cmu_devsite_quarter` (`quarter`),
  CONSTRAINT `FK_cmu_devsite_quarter` FOREIGN KEY (`quarter`) REFERENCES `cmu_quarter` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmu_hotel`
--

DROP TABLE IF EXISTS `cmu_hotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmu_hotel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quarter` int(10) unsigned NOT NULL,
  `property` int(10) NOT NULL,
  `completed_by` varchar(255) DEFAULT NULL,
  `general_mgr` varchar(255) DEFAULT NULL,
  `general_mgr_email` varchar(255) DEFAULT NULL,
  `occupancy` double DEFAULT NULL,
  `comments` longtext,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `transferred` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_cmu_hotel_quarter` (`quarter`),
  CONSTRAINT `FK_cmu_hotel_quarter` FOREIGN KEY (`quarter`) REFERENCES `cmu_quarter` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmu_lease`
--

DROP TABLE IF EXISTS `cmu_lease`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmu_lease` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quarter` int(10) unsigned NOT NULL,
  `property` int(10) NOT NULL,
  `tenant_name` varchar(255) DEFAULT NULL,
  `sq_ft` double DEFAULT NULL,
  `lease_trans_type` tinyint(1) unsigned NOT NULL,
  `owners_rep` varchar(255) NOT NULL,
  `tenants_rep` varchar(255) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `transferred` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_cmu_lease_transtype` (`lease_trans_type`),
  KEY `FK_cmu_lease_quarter` (`quarter`),
  KEY `FK_cmu_lease_property` (`property`),
  CONSTRAINT `FK_cmu_lease_quarter` FOREIGN KEY (`quarter`) REFERENCES `cmu_quarter` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_cmu_lease_transtype` FOREIGN KEY (`lease_trans_type`) REFERENCES `cmu_transaction_type` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmu_office_retail_svc`
--

DROP TABLE IF EXISTS `cmu_office_retail_svc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmu_office_retail_svc` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quarter` int(10) unsigned NOT NULL,
  `property` int(10) NOT NULL,
  `completed_by` varchar(255) DEFAULT NULL,
  `for_sale` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `for_sale_contact` varchar(255) DEFAULT NULL,
  `for_sale_phone` varchar(20) DEFAULT NULL,
  `sq_ft_for_lease` double DEFAULT NULL,
  `occupancy` double DEFAULT NULL,
  `largest_space` double DEFAULT NULL,
  `largest_space_6mths` double DEFAULT NULL,
  `largest_space_12mths` double DEFAULT NULL,
  `property_mgr` varchar(255) DEFAULT NULL,
  `property_mgr_phone` varchar(20) DEFAULT NULL,
  `property_mgr_fax` varchar(20) DEFAULT NULL,
  `property_mgr_email` varchar(255) DEFAULT NULL,
  `mgmt_company` varchar(255) DEFAULT NULL,
  `mgmt_company_addr` varchar(255) DEFAULT NULL,
  `leasing_company` varchar(255) DEFAULT NULL,
  `leasing_company_addr` varchar(255) DEFAULT NULL,
  `leasing_agent` varchar(255) DEFAULT NULL,
  `leasing_agent_phone` varchar(20) DEFAULT NULL,
  `leasing_agent_fax` varchar(20) DEFAULT NULL,
  `leasing_agent_email` varchar(255) DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `transferred` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_cmu_office_quarter` (`quarter`),
  CONSTRAINT `FK_cmu_office_quarter` FOREIGN KEY (`quarter`) REFERENCES `cmu_quarter` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmu_quarter`
--

DROP TABLE IF EXISTS `cmu_quarter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmu_quarter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(45) DEFAULT NULL,
  `year` int(4) unsigned NOT NULL,
  `quarterNum` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmu_transaction_type`
--

DROP TABLE IF EXISTS `cmu_transaction_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmu_transaction_type` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `Description` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-03-18 14:36:59
