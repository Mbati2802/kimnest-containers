-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: kimnest_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (3,'admin','$2y$10$3IZhkj3lUbQEfHhbvI/szeV1Tfj8vKv.cC0SVsEMyJGcf/BS58v1W','Administrator','admin@kimnestcontainers.co.ke','2026-06-30 15:14:14');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_posts`
--

LOCK TABLES `blog_posts` WRITE;
/*!40000 ALTER TABLE `blog_posts` DISABLE KEYS */;
INSERT INTO `blog_posts` VALUES (1,'RRRFR','rrrfr','FRRRRFR','RRRRR',NULL,'development','published',2,'2026-06-30 15:24:54','2026-07-01 10:19:10');
/*!40000 ALTER TABLE `blog_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `status` enum('unread','read','replied') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) NOT NULL DEFAULT 'General',
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (1,'General','Do you sell both new and used shipping containers?','Yes. We supply both new and used shipping containers depending on your requirements, budget, and intended application.',0,'2026-07-01 09:59:02'),(2,'General','Can you customize containers?','Absolutely. We specialize in custom fabrication, including offices, homes, shops, restaurants, classrooms, clinics, and other modular structures.',1,'2026-07-01 09:59:02'),(3,'General','Do you deliver across Kenya?','Yes. We provide nationwide delivery and professional installation services to all parts of Kenya.',2,'2026-07-01 09:59:02'),(4,'General','Why choose Kimnest Containers?','Because we combine innovative design, quality craftsmanship, professional service, competitive pricing, and customized solutions to deliver projects that exceed expectations.',3,'2026-07-01 09:59:02'),(5,'General','How do I get a quotation?','Simply complete the online quotation form, contact us by phone, or send us a WhatsApp message with your project requirements.',4,'2026-07-01 09:59:02'),(6,'General','Can I visit your workshop?','Yes. Clients are welcome to schedule visits to view ongoing and completed fabrication projects.',5,'2026-07-01 09:59:02'),(7,'Fabrication','How long does fabrication take?','Project timelines depend on the size and complexity of the work. Once your requirements are confirmed, we will provide an estimated completion schedule.',6,'2026-07-01 09:59:02'),(8,'Fabrication','Can I provide my own design?','Yes. We can fabricate based on your architectural drawings or collaborate with you to develop a custom design.',7,'2026-07-01 09:59:02'),(9,'Fabrication','Can containers be insulated?','Yes. We offer insulation solutions that improve thermal comfort and energy efficiency.',8,'2026-07-01 09:59:02'),(10,'Fabrication','Do you install electrical and plumbing systems?','Yes. We provide complete electrical, plumbing, lighting, and interior finishing services.',9,'2026-07-01 09:59:02'),(11,'Fabrication','Can containers have multiple rooms?','Certainly. Through professional partitioning and design, containers can accommodate multiple functional spaces.',10,'2026-07-01 09:59:02'),(12,'Fabrication','Can containers be stacked?','Yes. Containers are structurally designed to support stacking, making them ideal for multi-storey developments.',11,'2026-07-01 09:59:02'),(13,'Fabrication','Do you provide after-sales support?','Yes. We remain available to assist clients with maintenance advice, modifications, and future expansion projects.',12,'2026-07-01 09:59:02'),(14,'Design & Planning','Are container homes durable?','Yes. Shipping containers are built from high-strength steel and are designed to withstand harsh environmental conditions, making them an excellent long-term building solution.',13,'2026-07-01 09:59:02'),(15,'Design & Planning','Do container buildings require planning approvals?','Depending on your location and intended use, approvals may be required. We recommend consulting the relevant local authorities before construction.',14,'2026-07-01 09:59:02');
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter`
--

DROP TABLE IF EXISTS `newsletter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `status` enum('active','unsubscribed') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter`
--

LOCK TABLES `newsletter` WRITE;
/*!40000 ALTER TABLE `newsletter` DISABLE KEYS */;
INSERT INTO `newsletter` VALUES (1,NULL,'willysonyango05@gmail.com','active','2026-07-01 09:41:49');
/*!40000 ALTER TABLE `newsletter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter_campaigns`
--

DROP TABLE IF EXISTS `newsletter_campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter_campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `sent_count` int(11) DEFAULT 0,
  `total_count` int(11) DEFAULT 0,
  `status` enum('draft','sent','failed') DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter_campaigns`
--

LOCK TABLES `newsletter_campaigns` WRITE;
/*!40000 ALTER TABLE `newsletter_campaigns` DISABLE KEYS */;
INSERT INTO `newsletter_campaigns` VALUES (1,'Group','<p>iuhihknknkmmm</p>',0,1,'failed','2026-07-01 10:36:05');
/*!40000 ALTER TABLE `newsletter_campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notification_templates`
--

DROP TABLE IF EXISTS `notification_templates`;
CREATE TABLE `notification_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_key` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` longtext NOT NULL,
  `placeholders` text DEFAULT NULL,
  `created_at` timestamp DEFAULT current_timestamp(),
  `updated_at` timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `template_key` (`template_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `notification_templates` WRITE;
INSERT INTO `notification_templates` (`template_key`, `name`, `subject`, `body`, `placeholders`) VALUES
('contact_notification', 'Contact Form Submission', 'New Contact: {subject}', '<h2>New Contact Message</h2><p><strong>From:</strong> {name}</p><p><strong>Email:</strong> {email}</p><p><strong>Phone:</strong> {phone}</p><p><strong>Subject:</strong> {subject}</p><p><strong>Message:</strong></p><p>{message}</p><hr><p><a href=\"{admin_url}\">View in Admin Panel</a></p>', 'name, email, phone, subject, message, admin_url'),
('quote_notification', 'Quote Request Submission', 'New Quote Request from {name}', '<h2>New Quote Request</h2><p><strong>From:</strong> {name}</p><p><strong>Company:</strong> {company}</p><p><strong>Email:</strong> {email}</p><p><strong>Phone:</strong> {phone}</p><p><strong>Preferred Contact:</strong> {contact_method}</p><p><strong>Project Type:</strong> {project_type}</p><p><strong>Container Size:</strong> {container_size}</p><p><strong>Quantity:</strong> {quantity}</p><p><strong>Location:</strong> {location}</p><p><strong>Intended Use:</strong> {intended_use}</p><p><strong>Budget:</strong> {budget}</p><p><strong>Completion Date:</strong> {completion_date}</p><p><strong>Description:</strong></p><p>{description}</p>{cart_section}<hr><p><a href=\"{admin_url}\">View in Admin Panel</a></p>', 'name, company, email, phone, contact_method, project_type, container_size, quantity, location, intended_use, budget, completion_date, description, cart_section, admin_url');
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (1,1,'uploads/product_1_6a44dc79bc87a.png','',0);
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `size` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `features` text DEFAULT NULL,
  `specs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specs`)),
  `image` varchar(255) DEFAULT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `price_label` varchar(100) DEFAULT NULL,
  `status` enum('available','limited','unavailable') DEFAULT 'available',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'10ft Standard Container','10ft-standard','Standard','10ft','Compact and versatile 10ft shipping container, perfect for limited spaces. Ideal for secure storage, small offices, and site sheds.','Durable corten steel construction\r\nWeather-resistant sealed doors\r\nStandard lock box\r\nVentilation system\r\nMarine-grade plywood flooring','{\"length\":\"10ft\",\"width\":\"8ft\",\"height\":\"8ft 6in\",\"weight\":\"1,050 kg\",\"capacity\":\"5.9 CBM\"}','uploads/6a44e46175220.jpg',NULL,'Request Pricing','available',1,'2026-07-01 08:59:15'),(2,'20ft Standard Container','20ft-standard','Standard','20ft','The most popular container size worldwide. Versatile, cost-effective, and ideal for a wide range of applications from storage to conversion.','Double swing doors\nLock box for security\nForklift pockets\nCSC plated\nVentilation system\nMarine-grade plywood flooring','{\"length\":\"20ft\",\"width\":\"8ft\",\"height\":\"8ft 6in\",\"weight\":\"2,230 kg\",\"capacity\":\"33.2 CBM\"}',NULL,NULL,'Request Pricing','available',2,'2026-07-01 08:59:15'),(3,'40ft Standard Container','40ft-standard','Standard','40ft','Maximum space for large-scale projects. Perfect for warehouses, multi-room structures, and commercial spaces requiring extensive floor area.','Double swing doors\nLock box for security\nForklift pockets\nCSC plated\nVentilation system\nMarine-grade plywood flooring','{\"length\":\"40ft\",\"width\":\"8ft\",\"height\":\"8ft 6in\",\"weight\":\"3,750 kg\",\"capacity\":\"67.7 CBM\"}',NULL,NULL,'Request Pricing','available',3,'2026-07-01 08:59:15'),(4,'20ft High Cube Container','20ft-highcube','High Cube','20ft','Extra height (9ft 6in) for projects requiring additional vertical space. Ideal for taller equipment, stacked storage, and conversions.','Extra foot of height\nDouble swing doors\nLock box for security\nForklift pockets\nCSC plated\nVentilation system','{\"length\":\"20ft\",\"width\":\"8ft\",\"height\":\"9ft 6in\",\"weight\":\"2,350 kg\",\"capacity\":\"37.4 CBM\"}',NULL,NULL,'Request Pricing','available',4,'2026-07-01 08:59:15'),(5,'40ft High Cube Container','40ft-highcube','High Cube','40ft','The ultimate in space and height. Ideal for large offices, restaurants, multi-level homes, and commercial structures.','Extra foot of height\nDouble swing doors\nLock box for security\nForklift pockets\nCSC plated\nVentilation system','{\"length\":\"40ft\",\"width\":\"8ft\",\"height\":\"9ft 6in\",\"weight\":\"3,900 kg\",\"capacity\":\"76.4 CBM\"}',NULL,NULL,'Request Pricing','available',5,'2026-07-01 08:59:15'),(6,'20ft Open Side Container','20ft-openside','Specialty','20ft','Full-length side doors allow easy loading of oversized cargo and materials. Perfect for retail displays and unique conversions.','Full side opening doors\nDouble swing end doors\nLock box\nReinforced structure\nCSC plated','{\"length\":\"20ft\",\"width\":\"8ft\",\"height\":\"8ft 6in\",\"weight\":\"2,450 kg\",\"capacity\":\"33.2 CBM\"}',NULL,NULL,'Request Pricing','limited',6,'2026-07-01 08:59:15'),(7,'20ft Refrigerated Container','20ft-reefer','Specialty','20ft','Temperature-controlled container for perishable goods, pharmaceuticals, and cold storage applications.','Temperature range: -25┬░C to +25┬░C\nDigital temperature display\nInsulated walls\nStainless steel interior\nCargo lashing rings','{\"length\":\"20ft\",\"width\":\"8ft\",\"height\":\"8ft 6in\",\"weight\":\"2,800 kg\",\"capacity\":\"28.4 CBM\"}',NULL,NULL,'Request Pricing','limited',7,'2026-07-01 08:59:15'),(8,'40ft Flat Rack Container','40ft-flatrack','Specialty','40ft','Open-top and collapsible sides for heavy machinery, vehicles, and oversized loads that cannot fit in standard containers.','Collapsible end walls\nForklift pockets\nLashing rings\nNon-slip flooring\nReinforced corners','{\"length\":\"40ft\",\"width\":\"8ft\",\"height\":\"8ft 6in\",\"weight\":\"2,600 kg\",\"capacity\":\"Open Top\"}',NULL,NULL,'Request Pricing','available',8,'2026-07-01 08:59:15');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_images`
--

DROP TABLE IF EXISTS `project_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `project_images_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_images`
--

LOCK TABLES `project_images` WRITE;
/*!40000 ALTER TABLE `project_images` DISABLE KEYS */;
INSERT INTO `project_images` VALUES (1,1,'uploads/6a44d4895663d.jpg',NULL,0),(2,1,'uploads/6a44de6153611.png',NULL,0),(3,1,'uploads/6a44de6156d38.jpg',NULL,0);
/*!40000 ALTER TABLE `project_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `full_description` longtext DEFAULT NULL,
  `client_testimonial` text DEFAULT NULL,
  `client_name` varchar(100) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `status` enum('completed','ongoing') DEFAULT 'completed',
  `featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (1,'Best Test','best-test','Residential','We did this Additional Services More Solutions We Offer. We did this Additional Services More Solutions We Offer. We did this Additional Services More Solutions We Offer','We did this Additional Services More Solutions We OfferWe did this Additional Services More Solutions We OfferWe did this Additional Services More Solutions We OfferWe did this Additional Services More Solutions We OfferWe did this Additional Services More Solutions We OfferWe did this Additional Services More Solutions We OfferWe did this Additional Services More Solutions We OfferWe did this Additional Services More Solutions We Offer','We did this Additional Services More Solutions We OfferWe did this Additional Services More Solutions We Offer','James Chege','Nairobi, Kenya','2026-04-30','completed',1,'2026-07-01 08:48:49');
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotes`
--

DROP TABLE IF EXISTS `quotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `project_type` varchar(100) DEFAULT NULL,
  `container_size` varchar(50) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `project_location` varchar(255) DEFAULT NULL,
  `intended_use` text DEFAULT NULL,
  `budget` varchar(50) DEFAULT NULL,
  `completion_date` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `contact_method` varchar(20) DEFAULT 'phone',
  `cart_data` text DEFAULT NULL,
  `status` enum('new','contacted','quoted','closed') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotes`
--

LOCK TABLES `quotes` WRITE;
/*!40000 ALTER TABLE `quotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `quotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_content`
--

DROP TABLE IF EXISTS `site_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_slug` varchar(100) NOT NULL,
  `section_key` varchar(100) NOT NULL,
  `content_key` varchar(100) NOT NULL,
  `content_value` longtext DEFAULT NULL,
  `content_type` enum('text','textarea','image','html') DEFAULT 'text',
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_key` (`page_slug`,`section_key`,`content_key`)
) ENGINE=InnoDB AUTO_INCREMENT=1026 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_content`
--

LOCK TABLES `site_content` WRITE;
/*!40000 ALTER TABLE `site_content` DISABLE KEYS */;
INSERT INTO `site_content` VALUES (1,'home','hero','overline','Kimnest Containers','text',1,'2026-07-01 06:42:00'),(2,'home','hero','title','Transforming Shipping Containers Into Exceptional Spaces','text',1,'2026-07-01 08:09:41'),(3,'home','hero','description','<p>We specialize in supplying, designing, fabricating, and modifying premium shipping containers into innovative, durable, and affordable spaces tailored to your needs.</p>','textarea',1,'2026-07-01 08:11:50'),(4,'home','hero','btn1_text','Request a Free Quote','text',1,'2026-06-30 15:08:34'),(5,'home','hero','btn1_link','/request-quote','text',1,'2026-06-30 15:08:34'),(6,'home','hero','btn2_text','View Our Projects','text',1,'2026-06-30 15:08:34'),(7,'home','hero','btn2_link','/projects','text',1,'2026-06-30 15:08:34'),(8,'home','stats','stat1_number','500+','text',1,'2026-07-01 08:09:41'),(9,'home','stats','stat1_label','Projects Completed','text',1,'2026-06-30 15:08:34'),(10,'home','stats','stat1_sub','Delivered across Kenya','text',1,'2026-06-30 15:08:34'),(11,'home','stats','stat2_number','10+','text',1,'2026-06-30 15:08:34'),(12,'home','stats','stat2_label','Years Experience','text',1,'2026-06-30 15:08:34'),(13,'home','stats','stat2_sub','Trusted by hundreds','text',1,'2026-06-30 15:08:34'),(14,'home','stats','stat3_number','100%','text',1,'2026-06-30 15:08:34'),(15,'home','stats','stat3_label','Client Satisfaction','text',1,'2026-06-30 15:08:34'),(16,'home','stats','stat3_sub','Quality guaranteed','text',1,'2026-06-30 15:08:34'),(17,'home','about','overline','About Us','text',1,'2026-07-01 08:09:41'),(18,'home','about','title','We Build Spaces That Inspire','text',1,'2026-07-01 08:09:41'),(19,'home','about','description','<p>Every great project begins with a vision. At Kimnest Containers, we believe that shipping containers offer limitless possibilities for modern construction, business innovation, and affordable living.</p>','textarea',1,'2026-07-01 08:11:50'),(20,'home','about','description2','<p>From compact site offices and stylish retail shops to luxurious container homes and fully customized commercial spaces, we create solutions that combine durability, functionality, and aesthetic appeal.</p>','textarea',1,'2026-06-30 16:19:19'),(21,'home','about','btn_text','Learn More About Us','text',1,'2026-06-30 15:08:34'),(22,'home','about','btn_link','/about','text',1,'2026-06-30 15:08:34'),(23,'home','about','floating_number','500+','text',0,'2026-07-01 08:13:27'),(24,'home','about','floating_label','Projects Delivered','text',0,'2026-07-01 08:13:27'),(25,'home','whyus','overline','Why Choose Us','text',1,'2026-06-30 15:08:34'),(26,'home','whyus','title','Built on Trust, Delivered with Excellence','text',1,'2026-06-30 15:08:34'),(27,'home','whyus','description','<p>We combine expertise, quality materials, and customer-first service to deliver container solutions that exceed expectations.</p>','textarea',1,'2026-06-30 16:11:46'),(28,'home','services','overline','Our Services','text',1,'2026-06-30 15:08:34'),(29,'home','services','title','Comprehensive Container Solutions','text',1,'2026-06-30 15:08:34'),(30,'home','services','description','<p>From initial consultation and custom design to professional fabrication and nationwide delivery, we handle every aspect of your container project with precision and care.</p>','textarea',1,'2026-07-01 08:11:50'),(31,'home','services','btn_text','Explore Our Services','text',1,'2026-06-30 15:08:34'),(32,'home','services','btn_link','/services','text',1,'2026-06-30 15:08:34'),(33,'home','cta','title','Ready to Bring Your Vision to Life?','text',1,'2026-06-30 15:08:34'),(34,'home','cta','description','<p>Whether you are planning a modern container home, portable office, commercial shop, or custom modular solution, Kimnest Containers is ready to make it happen.</p>','textarea',1,'2026-07-01 08:11:50'),(35,'home','cta','btn1_text','Get a Free Quotation','text',1,'2026-06-30 15:08:34'),(36,'home','cta','btn1_link','/request-quote','text',1,'2026-06-30 15:08:34'),(37,'about','hero','title','About Us','text',1,'2026-06-30 15:08:34'),(38,'about','story','overline','Our Story','text',1,'2026-06-30 15:08:34'),(39,'about','story','title','Building Dreams Through Innovative Container Solutions','text',1,'2026-06-30 15:08:34'),(40,'about','story','description','<p>At Kimnest Containers, we believe that every space has the potential to inspire, empower, and transform lives. What began as a vision to provide affordable and innovative container solutions has grown into a trusted company dedicated to delivering high-quality container sales, fabrication, and customized construction services across Kenya.</p>','textarea',1,'2026-07-01 06:25:01'),(41,'about','story','description2','<p>Our expertise lies in transforming ordinary shipping containers into extraordinary spaces that serve a wide range of purposes from modern homes and executive offices to retail shops, restaurants, classrooms, clinics, security cabins, and specialized commercial facilities.</p>','textarea',1,'2026-07-01 06:25:01'),(42,'about','mission','title','What Drives Us Forward','text',1,'2026-06-30 15:08:34'),(43,'about','mission','mission_title','Our Mission','text',1,'2026-06-30 15:08:34'),(44,'about','mission','mission_text','<p>To provide innovative, durable, and affordable container solutions that exceed customer expectations through exceptional craftsmanship, quality materials, and outstanding customer service.</p>','textarea',1,'2026-07-01 06:25:01'),(45,'about','mission','vision_title','Our Vision','text',1,'2026-06-30 15:08:34'),(46,'about','mission','vision_text','<p>To become East Africa most trusted and preferred provider of container sales, fabrication, and modular construction solutions by delivering excellence, innovation, and lasting value in every project.</p>','textarea',1,'2026-07-01 06:25:01'),(47,'about','cta','title','Lets Build Something Exceptional Together','text',1,'2026-06-30 15:08:34'),(48,'about','cta','description','<p>Whether you are looking for a simple storage container or a fully customized container home, Kimnest Containers has the expertise to bring your ideas to life.</p>','textarea',1,'2026-07-01 10:18:10'),(49,'services','hero','title','Our Services','text',1,'2026-06-30 15:08:34'),(50,'services','intro','overline','What We Do','text',1,'2026-06-30 15:08:34'),(51,'services','intro','title','Innovative Container Solutions Designed Around Your Needs','text',1,'2026-06-30 15:08:34'),(52,'services','intro','description','<p>We offer comprehensive container solutions that combine functionality, innovation, and exceptional craftsmanship.</p>','textarea',1,'2026-07-01 08:20:10'),(53,'services','sales','overline','Container Sales','text',1,'2026-06-30 15:08:34'),(54,'services','sales','title','Premium Quality Containers','text',1,'2026-06-30 15:08:34'),(55,'services','sales','description','<p>We supply premium-quality new and used shipping containers in a variety of sizes and specifications to suit different residential, commercial, and industrial applications.</p>','textarea',1,'2026-07-01 08:20:10'),(56,'services','fabrication','overline','Fabrication','text',1,'2026-06-30 15:08:34'),(57,'services','fabrication','title','Custom Container Fabrication','text',1,'2026-06-30 15:08:34'),(58,'services','fabrication','description','<p>Our fabrication service transforms standard shipping containers into fully functional spaces tailored to your unique requirements.</p>','textarea',1,'2026-07-01 08:20:10'),(59,'services','modifications','overline','Modifications','text',1,'2026-06-30 15:08:34'),(60,'services','modifications','title','Container Modifications','text',1,'2026-06-30 15:08:34'),(61,'services','modifications','description','<p>Need to customize an existing container? Our modification services allow clients to transform standard containers into practical, comfortable, and visually appealing spaces.</p>','textarea',1,'2026-07-01 08:20:10'),(62,'services','delivery','overline','Delivery','text',1,'2026-06-30 15:08:34'),(63,'services','delivery','title','Delivery &amp;amp;amp;amp;amp;amp;amp;amp;amp;amp; Installation','text',1,'2026-07-01 08:43:46'),(64,'services','delivery','description','<p>We understand that timely delivery is essential to the success of your project. Our logistics team coordinates safe transportation and professional installation of completed container structures to your desired location across Kenya.</p>','textarea',1,'2026-07-01 08:20:10'),(65,'services','cta','title','Have a Unique Project in Mind?','text',1,'2026-06-30 15:08:34'),(66,'services','cta','description','<p>Our design team works closely with clients to develop customized container projects that reflect specific operational, architectural, or lifestyle requirements.</p>','textarea',1,'2026-07-01 08:20:10'),(67,'home','hero','hero_image','uploads/img_6a43e277a8bba9.98046338.webp','image',1,'2026-06-30 16:14:26'),(68,'home','about','image','uploads/img_6a44cbc4d3b513.51736970.webp','image',1,'2026-07-01 08:11:50'),(69,'home','services','image','uploads/img_6a44cc467a17b5.61004742.png','image',1,'2026-07-01 08:13:59'),(70,'about','hero','image','uploads/img_6a44e9613cc4e3.56618837.jpeg','image',1,'2026-07-01 10:18:10'),(71,'services','sales','image','uploads/img_6a44d0529cb505.31203947.jpg','image',1,'2026-07-01 08:31:15'),(72,'services','fabrication','image','uploads/img_6a44cf46bdc347.38083470.png','image',1,'2026-07-01 08:26:47'),(73,'services','modifications','image','uploads/img_6a44ced70b2292.74408014.jpg','image',1,'2026-07-01 08:24:55'),(74,'services','delivery','image','uploads/img_6a44ce8b7da817.35271081.jpg','image',1,'2026-07-01 08:23:40'),(167,'home','hero','hero_image_2','uploads/img_6a44a28e5a2bd1.53158567.webp','image',1,'2026-07-01 05:16:00'),(168,'home','hero','hero_image_3','uploads/img_6a44a2d09fd576.68616224.webp','image',1,'2026-07-01 05:17:09'),(939,'services','hero','description','<p>From concept to completion, we create container solutions that combine quality craftsmanship, practical functionality, and modern innovation.</p>','textarea',1,'2026-07-01 08:43:46'),(940,'services','hero','bg_image','uploads/img_6a44d1a2125592.83713683.png','image',1,'2026-07-01 08:36:51'),(941,'services','hero','overlay_color','#1a2e4a','text',1,'2026-07-01 08:35:34'),(967,'about','hero','description','<p>Learn more about Kimnest Containers ΓÇö our story, mission, and the team behind Kenya\'s leading container solutions.</p>','textarea',1,'2026-07-01 10:18:10'),(968,'about','hero','bg_image','','image',1,'2026-07-01 08:43:07'),(969,'about','hero','overlay_color','#1a2e4a','text',1,'2026-07-01 08:43:07'),(970,'products','hero','description','Explore our range of shipping containers available in various sizes and configurations.','textarea',1,'2026-07-01 08:43:07'),(971,'products','hero','bg_image','','image',1,'2026-07-01 08:43:07'),(972,'products','hero','overlay_color','#1a2e4a','text',1,'2026-07-01 08:43:07'),(973,'projects','hero','description','See our completed container projects across Kenya ΓÇö from homes and offices to retail and commercial spaces.','textarea',1,'2026-07-01 08:43:07'),(974,'projects','hero','bg_image','','image',1,'2026-07-01 08:43:07'),(975,'projects','hero','overlay_color','#1a2e4a','text',1,'2026-07-01 08:43:07'),(976,'blog','hero','description','Industry insights, expert advice, and the latest trends in container construction and design.','textarea',1,'2026-07-01 08:43:07'),(977,'blog','hero','bg_image','','image',1,'2026-07-01 08:43:07'),(978,'blog','hero','overlay_color','#1a2e4a','text',1,'2026-07-01 08:43:07'),(979,'faq','hero','description','Find answers to common questions about our containers, services, and processes.','textarea',1,'2026-07-01 08:43:07'),(980,'faq','hero','bg_image','','image',1,'2026-07-01 08:43:07'),(981,'faq','hero','overlay_color','#1a2e4a','text',1,'2026-07-01 08:43:07'),(982,'contact','hero','description','Get in touch with our team for inquiries, quotes, or project consultations.','textarea',1,'2026-07-01 08:43:07'),(983,'contact','hero','bg_image','','image',1,'2026-07-01 08:43:07'),(984,'contact','hero','overlay_color','#1a2e4a','text',1,'2026-07-01 08:43:07');
/*!40000 ALTER TABLE `site_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` longtext DEFAULT NULL,
  `setting_type` enum('text','textarea','image','json') DEFAULT 'text',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES (1,'site_name','Kimnest Containers','text','2026-06-30 15:08:35'),(2,'site_email','info@kimnestcontainers.co.ke','text','2026-06-30 15:08:35'),(3,'site_phone','+254 726 632 632','text','2026-06-30 15:39:58'),(4,'site_whatsapp','+254 726 632 632','text','2026-06-30 15:39:58'),(5,'site_address','Nairobi, Kenya','text','2026-06-30 15:08:35'),(6,'site_tagline','We weave your property dream into reality','text','2026-06-30 15:08:35'),(7,'facebook_url','#','text','2026-06-30 15:08:35'),(8,'instagram_url','#','text','2026-06-30 15:08:35'),(9,'linkedin_url','#','text','2026-06-30 15:08:35'),(10,'tiktok_url','#','text','2026-06-30 15:08:35'),(11,'youtube_url','#','text','2026-06-30 15:08:35'),(45,'hero_animation_speed','6000','text','2026-07-01 06:01:44'),(85,'card1_bg','#ffffff','','2026-07-01 06:04:05'),(86,'card1_number_color','#3e7ac5','','2026-07-01 06:04:05'),(87,'card1_accent_color','#f5c140','','2026-07-01 06:04:05'),(88,'card1_text_color','#1a1d23','','2026-07-01 06:04:05'),(89,'card1_sub_color','#9ba3af','','2026-07-01 06:04:05'),(90,'card2_bg','#f5c140','','2026-07-01 06:09:01'),(91,'card2_number_color','#3e7ac5','','2026-07-01 06:04:05'),(92,'card2_accent_color','#fffff','','2026-07-01 06:09:01'),(93,'card2_text_color','#1a1d23','','2026-07-01 06:04:05'),(94,'card2_sub_color','#9ba3af','','2026-07-01 06:04:05'),(95,'card3_bg','#ffffff','','2026-07-01 06:04:05'),(96,'card3_number_color','#3e7ac5','','2026-07-01 06:04:05'),(97,'card3_accent_color','#f5c140','','2026-07-01 06:04:05'),(98,'card3_text_color','#1a1d23','','2026-07-01 06:04:05'),(99,'card3_sub_color','#9ba3af','','2026-07-01 06:04:05');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-01 16:19:54
