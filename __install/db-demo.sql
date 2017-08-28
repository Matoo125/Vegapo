-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.18 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table vegapo.answers
CREATE TABLE IF NOT EXISTS `answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- Dumping data for table vegapo.answers: 0 rows
/*!40000 ALTER TABLE `answers` DISABLE KEYS */;
/*!40000 ALTER TABLE `answers` ENABLE KEYS */;

-- Dumping structure for table vegapo.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `value` int(2) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- Dumping data for table vegapo.categories: 28 rows
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `name`, `slug`, `parent`, `image`, `country`, `created_at`, `updated_at`, `value`, `note`, `description`) VALUES
	(1, 'Cereálie', 'cerealie', 0, '579-cerealie.jpg', 'sk', '2017-02-19 18:11:09', '2017-05-08 18:59:05', 1, NULL, NULL),
	(2, 'Cestoviny', 'cestoviny', 0, '463-pasta.jpg', 'sk', '2017-02-19 18:11:16', '2017-05-08 18:59:09', 2, NULL, NULL),
	(3, 'Pečivo', 'pecivo', 0, '894-1493707514.jpg', 'sk', '2017-02-19 18:11:22', '2017-05-08 18:59:13', 3, NULL, NULL),
	(4, 'Jogurty', 'jogurty', 0, '160-soy-yogurt.jpg', 'sk', '2017-02-19 18:11:30', '2017-05-08 18:59:17', 4, NULL, NULL),
	(5, 'Nátierky', 'natierky', 0, '607-spreads.jpg', 'sk', '2017-02-19 18:11:35', '2017-05-08 18:59:21', 5, NULL, NULL),
	(6, 'Nápoje', 'napoje', 0, '448-drinks.jpg', 'sk', '2017-02-19 18:11:41', '2017-05-08 18:59:27', 6, NULL, NULL),
	(7, 'Omáčky', 'omacky', 0, '440-sauces.jpg', 'sk', '2017-02-19 18:11:47', '2017-05-08 18:59:32', 7, NULL, NULL),
	(8, 'Rastlinné mlieka', 'rastlinne-mlieka', 0, '763-milks.jpg', 'sk', '2017-02-19 18:11:54', '2017-05-08 18:59:37', 8, NULL, NULL),
	(9, 'Sladkosti', 'sladkosti', 0, '964-sweets.jpg', 'sk', '2017-02-19 18:12:00', '2017-05-08 18:59:41', 9, NULL, NULL),
	(10, 'Slané drobnosti', 'slane-drobnosti', 0, '935-saltysnacks.jpg', 'sk', '2017-02-19 18:12:06', '2017-05-08 18:59:49', 10, NULL, NULL),
	(11, 'Náhrady mäsa', 'nahrady-masa', 0, '545-meat-vegan.jpg', 'sk', '2017-02-19 18:12:15', '2017-05-08 18:59:53', 11, NULL, NULL),
	(12, 'Zmrzliny', 'zmrzliny', 0, '647-icecream.jpg', 'sk', '2017-02-19 18:12:22', '2017-05-08 18:59:58', 12, NULL, NULL),
	(13, 'Iné polotovary', 'ine-polotovary', 0, '886-78743.jpg', 'sk', '2017-02-19 18:12:34', '2017-05-08 19:00:03', 13, NULL, NULL),
	(14, 'Sladkosti', 'sladkosti', 0, '523-ritter-marcipan.jpg', 'cz', '2017-03-18 00:56:49', '2017-05-08 19:07:25', 9, NULL, NULL),
	(15, 'Těstoviny', 'testoviny', 0, '386-Panzani-500x500.jpg', 'cz', '2017-03-18 00:59:44', '2017-05-08 19:07:32', 2, NULL, NULL),
	(29, 'Rostlinná mléka', 'rostlinna-mleka', 0, '899-alpromleko.jpg', 'cz', '2017-05-08 19:05:43', '2017-05-10 18:02:26', 8, '', ''),
	(17, 'Tofu', 'tofu', 0, '658-tofu.jpg', 'sk', '2017-05-01 15:48:40', '2017-05-08 19:07:52', 14, NULL, NULL),
	(31, 'Zmrzliny', 'zmrzliny', 0, '833-zmrzlina.jpg', 'cz', '2017-05-08 19:11:34', '2017-05-10 18:04:15', 12, '', ''),
	(19, 'Pečivo', 'pecivo', 0, '730-pecivo.jpg', 'cz', '2017-05-06 17:29:14', '2017-05-10 18:05:09', 3, '', ''),
	(20, 'Jogurty', 'jogurty', 0, '462-jogurt.jpg', 'cz', '2017-05-06 17:29:27', '2017-05-10 18:05:58', 4, '', ''),
	(21, 'Pomazánky', 'pomazanky', 0, '942-pomazanky.jpg', 'cz', '2017-05-06 17:29:39', '2017-05-10 18:06:44', 5, '', ''),
	(22, 'Cereálie', 'cerealie', 0, '551-cerealie.jpg', 'cz', '2017-05-06 17:30:36', '2017-05-10 18:07:38', 1, '', ''),
	(23, 'Omáčky', 'omacky', 0, '406-tatarka.jpg', 'cz', '2017-05-06 17:31:22', '2017-05-10 18:08:35', 7, '', ''),
	(24, 'Slané drobnosti', 'slane-drobnosti', 0, '694-chips.jpg', 'cz', '2017-05-06 17:31:39', '2017-05-10 18:10:47', 10, '', ''),
	(25, 'Náhrady masa', 'nahrady-masa', 0, '563-seitan.jpg', 'cz', '2017-05-06 17:31:55', '2017-05-10 18:11:23', 11, '', ''),
	(26, 'Jiné polotovary', 'jine-polotovary', 0, '116-hracka.jpg', 'cz', '2017-05-06 17:32:39', '2017-05-10 20:22:45', 13, '', ''),
	(28, 'Nápoje', 'napoje', 0, '817-vegannapoje.jpg', 'cz', '2017-05-06 17:34:16', '2017-05-10 20:21:37', 6, '', ''),
	(30, 'Tofu', 'tofu', 0, '502-tofu-uzene-100g-sunfood.jpg', 'cz', '2017-05-08 19:07:06', '2017-05-10 18:01:17', 14, '', '');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Dumping structure for table vegapo.contact
CREATE TABLE IF NOT EXISTS `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `country` varchar(2) NOT NULL,
  `state` varchar(5) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- Dumping data for table vegapo.contact: 0 rows
/*!40000 ALTER TABLE `contact` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact` ENABLE KEYS */;

-- Dumping structure for table vegapo.favourite_products
CREATE TABLE IF NOT EXISTS `favourite_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=290 DEFAULT CHARSET=utf8;

-- Dumping data for table vegapo.favourite_products: 0 rows
/*!40000 ALTER TABLE `favourite_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `favourite_products` ENABLE KEYS */;

-- Dumping structure for table vegapo.forgotten_password
CREATE TABLE IF NOT EXISTS `forgotten_password` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- Dumping data for table vegapo.forgotten_password: 0 rows
/*!40000 ALTER TABLE `forgotten_password` DISABLE KEYS */;
/*!40000 ALTER TABLE `forgotten_password` ENABLE KEYS */;

-- Dumping structure for table vegapo.images
CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `role` int(1) NOT NULL,
  `country` varchar(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=983 DEFAULT CHARSET=utf8;

-- Dumping data for table vegapo.images: 955 rows
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
INSERT INTO `images` (`id`, `filename`, `product_id`, `role`, `country`) VALUES
	(1, '754-img-20170127-125339-hdr.jpg', 1, 1, 'sk'),
	(2, '419-img-20170201-144151.jpg', 2, 1, 'sk'),
	(3, '792-img-20170201-141504.jpg', 3, 1, 'sk'),
	(4, '800-ikxbqasfzsl-s720x720.jpg', 4, 1, 'sk'),
	(5, '623-1427-manner-neapolitaner-haselnuss-75g-st.jpg', 5, 1, 'sk'),
	(6, '316-51-050thr4l-sx355.jpg', 6, 1, 'sk'),
	(7, '642-609ritterdark.jpg', 7, 1, 'sk'),
	(8, '432-img-20170127-124924-hdr.jpg', 8, 1, 'sk'),
	(9, '879-img-20170127-125037-hdr.jpg', 9, 1, 'sk'),
	(10, '290-img-20161206-143653.jpg', 10, 1, 'sk'),
	(11, '822-img-20161206-143714.jpg', 11, 1, 'sk'),
	(12, '976-img-20161206-143920.jpg', 12, 1, 'sk'),
	(13, '890-img-20161206-144137-1.jpg', 13, 1, 'sk'),
	(14, '520-w893516.jpg', 14, 1, 'sk'),
	(15, '996-905446.jpg', 15, 1, 'sk'),
	(16, '217-img-20161206-144240.jpg', 16, 1, 'sk'),
	(17, '627-sam-mills-bezlepkove-kukuricne-cestoviny-fusilli-v-0-jpg-big.jpg', 17, 1, 'sk'),
	(18, '242-img-20161206-144531.jpg', 18, 1, 'sk'),
	(19, '634-ciastka-digestino-tastino.jpg', 19, 1, 'sk'),
	(20, '727-stiahnut.jpg', 20, 1, 'sk'),
	(21, '299-falaf1.JPG', 28, 1, 'sk'),
	(22, '205-ritter-marcipan.jpg', 22, 1, 'cz'),
	(23, '377-panzani-penne-rigate.jpg', 23, 1, 'cz'),
	(24, '956-panzani-spaghetti-testoviny.jpg', 24, 1, 'cz'),
	(25, '447-panzani-torti-testoviny.jpg', 25, 1, 'cz'),
	(26, '364-brusinkova-giant-bar.jpg', 26, 1, 'cz'),
	(27, '463-visnova-giant-bar.jpg', 27, 1, 'cz'),
	(28, '793-20170323-215409.jpg', 29, 1, 'sk'),
	(29, '126-20170323-215521.jpg', 30, 1, 'sk'),
	(30, '775-20170323-215548.jpg', 31, 1, 'sk'),
	(31, '444-20170323-215759.jpg', 32, 1, 'sk'),
	(32, '787-20170323-215911.jpg', 33, 1, 'sk'),
	(33, '271-20170323-215946.jpg', 34, 1, 'sk'),
	(34, '377-zakysan-119.jpg', 35, 1, 'sk'),
	(35, '524-tesco-zeleninove-samozy-200.jpg', 36, 1, 'sk'),
	(36, '350-tesco-cibulove-kruzky-250.jpg', 37, 1, 'sk'),
	(37, '665-spak-tatarska-159.jpg', 38, 1, 'sk'),
	(38, '645-spak-majoneza-159.jpg', 39, 1, 'sk'),
	(39, '205-sojalka-tatarska-omacka-059.jpg', 40, 1, 'sk'),
	(40, '855-sojalka-sojova-studena-omacka-059.jpg', 41, 1, 'sk'),
	(41, '206-sojacik-cucoriedka-049.jpg', 42, 1, 'sk'),
	(42, '858-sojacik-049.jpg', 43, 1, 'sk'),
	(43, '901-lunter-tofu-medvedi-cesnak-119.jpg', 44, 1, 'sk'),
	(44, '997-lunter-tofu-chilli-089.jpg', 45, 1, 'sk'),
	(45, '112-lunter-tofu-bazalka-089.jpg', 46, 1, 'sk'),
	(46, '835-lunter-salama-s-olivami-169.jpg', 47, 1, 'sk'),
	(47, '337-lunter-salama-gurmanska-169.jpg', 48, 1, 'sk'),
	(48, '182-lunter-parky-sojove-jemne-199.jpg', 49, 1, 'sk'),
	(49, '958-lunter-parky-sojove-chilli-199.jpg', 50, 1, 'sk');
/*!40000 ALTER TABLE `images` ENABLE KEYS */;

-- Dumping structure for table vegapo.matching_supermarkets
CREATE TABLE IF NOT EXISTS `matching_supermarkets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `supermarket_id` int(11) NOT NULL,
  `country` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1160 DEFAULT CHARSET=utf8;

-- Dumping data for table vegapo.matching_supermarkets: 94 rows
/*!40000 ALTER TABLE `matching_supermarkets` DISABLE KEYS */;
INSERT INTO `matching_supermarkets` (`id`, `product_id`, `supermarket_id`, `country`) VALUES
	(1, 1, 4, 'sk'),
	(2, 2, 3, 'sk'),
	(3, 3, 8, 'sk'),
	(4, 4, 3, 'sk'),
	(5, 4, 4, 'sk'),
	(6, 4, 8, 'sk'),
	(7, 5, 1, 'sk'),
	(8, 5, 4, 'sk'),
	(9, 5, 7, 'sk'),
	(10, 6, 1, 'sk'),
	(11, 6, 2, 'sk'),
	(12, 6, 3, 'sk'),
	(13, 6, 4, 'sk'),
	(14, 6, 7, 'sk'),
	(15, 7, 2, 'sk'),
	(16, 7, 3, 'sk'),
	(17, 7, 4, 'sk'),
	(18, 7, 7, 'sk'),
	(19, 8, 4, 'sk'),
	(20, 9, 4, 'sk'),
	(21, 10, 3, 'sk'),
	(22, 11, 3, 'sk'),
	(23, 12, 3, 'sk'),
	(24, 13, 3, 'sk'),
	(25, 14, 4, 'sk'),
	(26, 15, 4, 'sk'),
	(27, 17, 2, 'sk'),
	(28, 17, 4, 'sk'),
	(29, 18, 3, 'sk'),
	(30, 19, 3, 'sk'),
	(31, 20, 4, 'sk'),
	(32, 20, 7, 'sk'),
	(33, 21, 1, 'sk'),
	(34, 21, 2, 'sk'),
	(35, 24, 11, 'cz'),
	(36, 24, 12, 'cz'),
	(37, 24, 13, 'cz'),
	(38, 24, 14, 'cz'),
	(39, 24, 15, 'cz'),
	(40, 25, 11, 'cz'),
	(41, 25, 12, 'cz'),
	(42, 25, 13, 'cz'),
	(43, 25, 14, 'cz'),
	(44, 25, 15, 'cz'),
	(45, 27, 11, 'cz'),
	(46, 27, 12, 'cz'),
	(47, 27, 13, 'cz'),
	(48, 27, 14, 'cz'),
	(49, 27, 15, 'cz'),
	(50, 28, 1, 'sk'),
	(51, 29, 1, 'sk'),
	(52, 30, 1, 'sk'),
	(53, 31, 1, 'sk'),
	(54, 32, 1, 'sk'),
	(55, 33, 1, 'sk'),
	(56, 34, 1, 'sk'),
	(57, 5, 2, 'sk'),
	(58, 35, 1, 'sk'),
	(59, 35, 2, 'sk'),
	(60, 35, 9, 'sk'),
	(61, 36, 1, 'sk'),
	(62, 37, 1, 'sk'),
	(63, 38, 1, 'sk'),
	(64, 39, 1, 'sk'),
	(65, 40, 1, 'sk'),
	(66, 40, 9, 'sk'),
	(67, 41, 1, 'sk'),
	(68, 41, 9, 'sk'),
	(69, 42, 1, 'sk'),
	(70, 42, 9, 'sk'),
	(71, 43, 1, 'sk'),
	(72, 43, 9, 'sk'),
	(73, 44, 1, 'sk'),
	(74, 44, 2, 'sk'),
	(75, 44, 4, 'sk'),
	(76, 44, 9, 'sk'),
	(77, 45, 1, 'sk'),
	(78, 45, 2, 'sk'),
	(79, 45, 4, 'sk'),
	(80, 45, 9, 'sk'),
	(81, 46, 1, 'sk'),
	(82, 46, 2, 'sk'),
	(83, 46, 4, 'sk'),
	(84, 46, 9, 'sk'),
	(85, 47, 1, 'sk'),
	(86, 48, 1, 'sk'),
	(87, 49, 1, 'sk'),
	(88, 49, 9, 'sk'),
	(89, 50, 1, 'sk'),
	(90, 50, 9, 'sk'),
	(447, 22, 13, 'cz'),
	(448, 22, 15, 'cz'),
	(692, 34, 4, 'sk'),
	(792, 22, 21, 'cz');
/*!40000 ALTER TABLE `matching_supermarkets` ENABLE KEYS */;

-- Dumping structure for table vegapo.matching_tags
CREATE TABLE IF NOT EXISTS `matching_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `country` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1054 DEFAULT CHARSET=utf8;

-- Dumping data for table vegapo.matching_tags: 23 rows
/*!40000 ALTER TABLE `matching_tags` DISABLE KEYS */;
INSERT INTO `matching_tags` (`id`, `product_id`, `tag_id`, `country`) VALUES
	(1, 23, 1, 'cz'),
	(2, 24, 1, 'cz'),
	(3, 25, 1, 'cz'),
	(4, 26, 1, 'cz'),
	(5, 27, 1, 'cz'),
	(6, 35, 3, 'sk'),
	(7, 35, 4, 'sk'),
	(8, 37, 3, 'sk'),
	(9, 38, 3, 'sk'),
	(10, 39, 3, 'sk'),
	(11, 44, 3, 'sk'),
	(12, 44, 4, 'sk'),
	(13, 44, 5, 'sk'),
	(14, 45, 3, 'sk'),
	(15, 45, 4, 'sk'),
	(16, 45, 5, 'sk'),
	(17, 46, 3, 'sk'),
	(18, 46, 4, 'sk'),
	(19, 46, 5, 'sk'),
	(20, 47, 4, 'sk'),
	(21, 48, 4, 'sk'),
	(22, 49, 4, 'sk'),
	(23, 50, 4, 'sk');
/*!40000 ALTER TABLE `matching_tags` ENABLE KEYS */;

-- Dumping structure for table vegapo.newsletter
CREATE TABLE IF NOT EXISTS `newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `country` varchar(2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

-- Dumping data for table vegapo.newsletter: 0 rows
/*!40000 ALTER TABLE `newsletter` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletter` ENABLE KEYS */;

-- Dumping structure for table vegapo.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `expected_price` decimal(5,2) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `visibility` int(1) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `barcode` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=661 DEFAULT CHARSET=utf8;

-- Dumping data for table vegapo.products: 49 rows
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` (`id`, `name`, `slug`, `category_id`, `expected_price`, `author_id`, `visibility`, `country`, `created_at`, `updated_at`, `barcode`, `note`) VALUES
	(1, 'Toastový chlieb Olz', 'toastovy-chlieb-olz', 3, 0.80, 2, 1, 'sk', '2017-02-20 11:13:58', '2017-03-25 09:26:21', NULL, NULL),
	(2, 'Trolli Spaghettiny ', 'trolli-spaghettiny', 9, 1.00, 2, 1, 'sk', '2017-02-20 11:15:53', '2017-02-27 09:22:12', NULL, NULL),
	(3, 'Beauty Sweeties Saure Katzen', 'beauty-sweeties-saure-katzen', 9, 2.00, 2, 1, 'sk', '2017-02-20 11:17:45', '2017-02-27 09:22:12', NULL, NULL),
	(4, 'Müsli Srdiečka', 'musli-srdiecka', 9, 1.00, 2, 1, 'sk', '2017-02-20 17:26:48', '2017-02-27 09:22:12', NULL, NULL),
	(5, 'Manner Neapolitaner - lieskovce, citron ', 'manner-neapolitaner-lieskovce-citron', 9, 1.00, 2, 1, 'sk', '2017-02-20 17:29:19', '2017-02-27 09:22:12', NULL, NULL),
	(6, 'Ritter Sport Marzipan', 'ritter-sport-marzipan', 9, 2.00, 2, 1, 'sk', '2017-02-20 17:36:09', '2017-02-27 09:22:12', NULL, NULL),
	(7, 'Ritter Sport Dark Chocolate ', 'ritter-sport-dark-chocolate', 9, 2.00, 2, 1, 'sk', '2017-02-20 17:46:33', '2017-02-27 09:22:12', NULL, NULL),
	(8, 'Rustikálny uzlík s olivami', 'rustikalny-uzlik-s-olivami', 3, 1.00, 2, 1, 'sk', '2017-02-20 17:49:16', '2017-02-27 09:22:12', NULL, NULL),
	(9, 'Kvásková Bene Bageta', 'kvaskova-bene-bageta', 3, 1.00, 2, 1, 'sk', '2017-02-20 17:51:23', '2017-02-27 09:22:12', NULL, NULL),
	(10, 'Ražné pečivo z BIO múky ', 'razne-pecivo-z-bio-muky', 3, 1.00, 2, 1, 'sk', '2017-02-20 17:54:58', '2017-02-27 09:22:12', NULL, NULL),
	(11, 'Drevorubačské pečivo', 'drevorubacske-pecivo', 3, 1.00, 2, 1, 'sk', '2017-02-20 17:57:37', '2017-02-27 09:22:12', NULL, NULL),
	(12, 'Rye Bread', 'rye-bread', 3, 1.00, 2, 1, 'sk', '2017-02-20 17:58:48', '2017-02-27 09:22:12', NULL, NULL),
	(13, 'Konzumný chlieb', 'konzumny-chlieb', 3, 1.00, 2, 1, 'sk', '2017-02-20 17:59:25', '2017-02-27 09:22:12', NULL, NULL),
	(14, 'Francúzska bageta stredná', 'francuzska-bageta-stredna', 3, 1.00, 2, 1, 'sk', '2017-02-20 18:00:12', '2017-02-24 20:45:40', NULL, NULL),
	(15, 'Paillasse - olivový, mexický, viaczrnný ', 'paillasse-olivovy-mexicky-viaczrnny', 3, 1.00, 2, 1, 'sk', '2017-02-20 18:03:47', '2017-02-24 20:45:40', NULL, NULL),
	(16, 'Rivercote Wholemeal Bread', 'rivercote-wholemeal-bread', 3, 1.00, 2, 1, 'sk', '2017-02-20 18:04:59', '2017-02-27 09:22:12', NULL, NULL),
	(17, 'Sam Mills Kukuričné cestoviny - rôzne ', 'sam-mills-kukuricne-cestoviny-rozne', 2, 1.00, 2, 3, 'sk', '2017-02-20 18:06:23', '2017-06-19 09:15:48', NULL, NULL),
	(18, 'Sedliacka žemľa ', 'sedliacka-zemla', 3, 1.00, 2, 1, 'sk', '2017-02-20 18:07:22', '2017-02-27 09:22:12', NULL, NULL),
	(19, 'Digestino sušienky', 'digestino-susienky', 9, 1.00, 2, 1, 'sk', '2017-02-20 18:11:52', '2017-02-27 09:22:12', NULL, NULL),
	(20, 'Mon Cheri', 'mon-cheri', 9, 4.00, 2, 1, 'sk', '2017-02-20 18:14:55', '2017-02-27 09:22:12', NULL, NULL),
	(22, 'Ritter - marcipánová příchuť', 'ritter-marcipanova-prichut', 14, 30.00, 3, 1, 'cz', '2017-03-18 00:57:30', '2017-05-29 16:37:44', '', ''),
	(23, 'Panzani Penne rigate', 'panzani-penne-rigate', 15, 40.00, 3, 1, 'cz', '2017-03-18 01:07:48', '2017-03-18 12:13:08', NULL, NULL),
	(24, 'Panzani Spaghetti těstoviny', 'panzani-spaghetti-testoviny', 15, 40.00, 3, 1, 'cz', '2017-03-18 01:08:15', '2017-03-18 12:13:08', NULL, NULL),
	(25, 'Panzani Torti těstoviny', 'panzani-torti-testoviny', 15, 40.00, 3, 1, 'cz', '2017-03-18 01:08:30', '2017-03-18 12:13:08', NULL, NULL),
	(26, 'Giant Bar - brusinková', 'giant-bar-brusinkova', 14, 30.00, 3, 1, 'cz', '2017-03-18 01:11:02', '2017-05-08 19:04:05', NULL, NULL),
	(27, 'Giant Bar - višňová', 'giant-bar-visnova', 14, 30.00, 3, 1, 'cz', '2017-03-18 01:11:24', '2017-05-08 19:04:05', NULL, NULL),
	(28, 'Falafel z cíceru s cesnakom a petržlenovou vňaťou', 'falafel-z-ciceru-s-cesnakom-a-petrzlenovou-vnatou', 13, 2.99, 5, 1, 'sk', '2017-03-30 10:29:53', '2017-03-30 12:09:51', NULL, NULL),
	(29, 'Cornies kukuričné s horkou čokoládou', 'cornies-kukuricne-s-horkou-cokoladou', 1, 0.92, 6, 1, 'sk', '2017-04-22 13:57:33', '2017-04-22 13:57:33', NULL, NULL),
	(30, 'Šalát Bulgur mango slivky kari', 'salat-bulgur-mango-slivky-kari', 13, 1.89, 6, 1, 'sk', '2017-04-22 14:03:16', '2017-04-22 14:03:16', NULL, NULL),
	(31, 'Šalát bulgur červené pesto', 'salat-bulgur-cervene-pesto', 13, 1.89, 6, 1, 'sk', '2017-04-22 14:04:11', '2017-04-22 14:04:11', NULL, NULL),
	(32, 'Koro Knedľa parená kysnutá', 'koro-knedla-parena-kysnuta', 3, 1.15, 6, 1, 'sk', '2017-04-22 14:08:00', '2017-04-22 14:08:00', NULL, NULL),
	(33, 'Štrúdľové cesto', 'strudlove-cesto', 13, 0.85, 6, 1, 'sk', '2017-04-22 14:09:47', '2017-04-22 14:09:47', NULL, NULL),
	(34, 'Lístkové cesto', 'listkove-cesto', 13, 1.73, 6, 1, 'sk', '2017-04-22 14:11:37', '2017-05-14 16:02:18', '', ''),
	(35, 'Lunter zakysaný sójový nápoj', 'lunter-zakysany-sojovy-napoj', 8, 1.19, 6, 1, 'sk', '2017-04-22 17:22:34', '2017-05-01 15:55:26', '', NULL),
	(36, 'Tesco Zeleninové samózy', 'tesco-zeleninove-samozy', 13, 1.99, 6, 1, 'sk', '2017-04-22 17:25:18', '2017-04-22 17:25:18', NULL, NULL),
	(37, 'Tesco FreeFrom Cibuľové krúžky', 'tesco-freefrom-cibulove-kruzky', 13, 2.49, 6, 1, 'sk', '2017-04-22 17:26:47', '2017-04-22 17:26:47', NULL, NULL),
	(38, 'Spak Tatárska omáčka', 'spak-tatarska-omacka', 7, 1.59, 6, 1, 'sk', '2017-04-22 17:27:46', '2017-04-22 17:27:46', NULL, NULL),
	(39, 'Spak Majonéza', 'spak-majoneza', 7, 1.59, 6, 1, 'sk', '2017-04-22 17:28:04', '2017-05-01 15:49:35', '', NULL),
	(40, 'Sojalka Tatárska omáčka', 'sojalka-tatarska-omacka', 7, 0.59, 6, 1, 'sk', '2017-04-22 17:29:15', '2017-05-01 15:49:25', '', NULL),
	(41, 'Sojalka Sójová studená omáčka ', 'sojalka-sojova-studena-omacka', 7, 0.59, 6, 1, 'sk', '2017-04-22 17:29:53', '2017-04-22 17:29:53', NULL, NULL),
	(42, 'Sojáčik Čučoriedka ', 'sojacik-cucoriedka', 4, 0.49, 6, 1, 'sk', '2017-04-22 17:33:29', '2017-05-01 15:55:45', '', NULL),
	(43, 'Sojáčik Jahoda', 'sojacik-jahoda', 4, 0.49, 6, 1, 'sk', '2017-04-22 17:33:52', '2017-05-01 15:55:53', '', NULL),
	(44, 'Lunter Tofu medvedí cesnak', 'lunter-tofu-medvedi-cesnak', 17, 1.19, 6, 1, 'sk', '2017-04-22 17:34:31', '2017-05-01 15:55:59', '', NULL),
	(45, 'Lunter Tofu chilli', 'lunter-tofu-chilli', 17, 0.89, 6, 1, 'sk', '2017-04-22 17:40:11', '2017-05-01 15:54:03', '', NULL),
	(46, 'Lunter Tofu bazalka', 'lunter-tofu-bazalka', 17, 0.89, 6, 1, 'sk', '2017-04-22 18:17:41', '2017-05-01 15:54:09', '', NULL),
	(47, 'Lunter Saláma s olivami', 'lunter-salama-s-olivami', 11, 1.69, 6, 1, 'sk', '2017-04-22 18:18:56', '2017-05-14 16:04:58', '', 'Voda, Sójové bielkoviny 12,5 %, Repkový olej, Pšeničná bielkovina (glutén), Modifikovaný škrob kukuričný, Zahusťovadlo karagénan, Arómy a extrakty korenín, Zelené olivy 1,1 %, Jedlá soľ, Citrusová vláknina, Koreniny	'),
	(48, 'Lunter Saláma gurmánska', 'lunter-salama-gurmanska', 11, 1.69, 6, 1, 'sk', '2017-04-22 18:19:31', '2017-05-14 16:04:04', '', 'Voda, Sójové bielkoviny 16,6 %, Repkový olej, Pšeničná bielkovina (glutén), Modifikovaný škrob kukuričný, Arómy a extrakty korenín, Jedlá soľ, Zahusťovadlo karagénan, Cesnak, Koreniny, Citrusová vláknina	'),
	(49, 'Lunter Párky sójové jemné', 'lunter-parky-sojove-jemne', 11, 1.99, 6, 1, 'sk', '2017-04-22 18:20:27', '2017-04-22 18:20:27', NULL, NULL),
	(50, 'Lunter Párky sójové chilli', 'lunter-parky-sojove-chilli', 11, 1.99, 6, 1, 'sk', '2017-04-22 18:21:37', '2017-04-22 18:21:37', NULL, NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

-- Dumping structure for table vegapo.suggestions
CREATE TABLE IF NOT EXISTS `suggestions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `country` varchar(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;

-- Dumping data for table vegapo.suggestions: 0 rows
/*!40000 ALTER TABLE `suggestions` DISABLE KEYS */;
/*!40000 ALTER TABLE `suggestions` ENABLE KEYS */;

-- Dumping structure for table vegapo.supermarkets
CREATE TABLE IF NOT EXISTS `supermarkets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `value` int(2) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- Dumping data for table vegapo.supermarkets: 25 rows
/*!40000 ALTER TABLE `supermarkets` DISABLE KEYS */;
INSERT INTO `supermarkets` (`id`, `name`, `slug`, `image`, `country`, `created_at`, `updated_at`, `value`, `note`, `description`) VALUES
	(1, 'Tesco', 'tesco', '678-tesco.png', 'sk', '2017-02-14 18:03:49', '2017-05-08 18:56:57', 1, NULL, NULL),
	(2, 'Kaufland', 'kaufland', '946-kaufland.jpg', 'sk', '2017-02-19 18:04:52', '2017-05-08 18:57:02', 2, NULL, NULL),
	(3, 'Lidl', 'lidl', '769-lidl.jpg', 'sk', '2017-02-19 18:05:03', '2017-05-08 18:57:07', 3, NULL, NULL),
	(4, 'Billa', 'billa', '421-billa.png', 'sk', '2017-02-19 18:08:11', '2017-05-08 18:57:13', 4, NULL, NULL),
	(5, 'Jednota', 'jednota', '907-jednota.jpg', 'sk', '2017-02-19 18:08:23', '2017-05-08 18:57:17', 5, NULL, NULL),
	(6, 'CBA', 'cba', '105-cba.jpg', 'sk', '2017-02-19 18:10:12', '2017-05-08 18:57:22', 6, NULL, NULL),
	(7, 'Albert', 'albert', '353-albert.jpg', 'sk', '2017-02-19 18:13:34', '2017-05-08 18:57:28', 7, NULL, NULL),
	(8, 'DM Drogeria Markt', 'dm-drogeria-markt', '288-dm.jpg', 'sk', '2017-02-19 18:13:46', '2017-05-08 18:57:32', 8, NULL, NULL),
	(9, 'Terno', 'terno', '753-terno.jpg', 'sk', '2017-02-19 18:14:11', '2017-05-08 18:57:38', 9, NULL, NULL),
	(10, 'YEME', 'yeme', '458-yeme.jpg', 'sk', '2017-02-19 18:14:17', '2017-05-08 18:58:03', 10, NULL, NULL),
	(11, 'Albert', 'albert', '159-albert.jpg', 'cz', '2017-03-05 23:45:05', '2017-05-10 17:45:18', 7, '', ''),
	(12, 'Billa', 'billa', '856-billa.gif', 'cz', '2017-03-05 23:45:11', '2017-05-10 17:51:15', 4, '', ''),
	(13, 'Kaufland', 'kaufland', '912-kaufland.png', 'cz', '2017-03-05 23:45:17', '2017-05-08 18:58:29', 2, NULL, NULL),
	(14, 'Lidl', 'lidl', '852-lidl.png', 'cz', '2017-03-05 23:45:48', '2017-05-08 18:58:35', 3, NULL, NULL),
	(15, 'Tesco', 'tesco', '331-tesco.gif', 'cz', '2017-03-05 23:45:55', '2017-05-10 17:51:51', 1, '', ''),
	(16, 'Jednota COOP', 'jednota-coop', '210-coop.jpg', 'cz', '2017-05-09 06:34:43', '2017-05-10 17:48:30', NULL, '', ''),
	(17, 'DM Drogeria Markt', 'dm-drogeria-markt', '156-dm.png', 'cz', '2017-05-09 06:34:53', '2017-05-10 17:52:31', NULL, '', ''),
	(18, 'Hruška', 'hruska', '365-hruska.jpg', 'cz', '2017-05-09 06:35:02', '2017-05-10 17:59:00', NULL, '', ''),
	(19, 'Makro', 'makro', '556-makro.png', 'cz', '2017-05-09 06:35:06', '2017-05-12 12:25:17', NULL, '', ''),
	(20, 'Penny', 'penny', '743-penny-alt.jpg', 'cz', '2017-05-09 06:35:09', '2017-05-10 17:53:50', NULL, '', ''),
	(21, 'Globus', 'globus', '573-globus.jpg', 'cz', '2017-05-09 06:35:18', '2017-05-10 17:56:44', NULL, '', ''),
	(22, 'Žabka', 'zabka', '561-zabka.png', 'cz', '2017-05-09 06:35:24', '2017-05-10 17:55:00', NULL, '', ''),
	(23, 'SPAR', 'spar', '737-spar.png', 'cz', '2017-05-09 06:35:27', '2017-05-10 17:50:18', NULL, '', ''),
	(24, 'Fresh', 'fresh', '645-26-fresh.png', 'sk', '2017-05-25 18:40:21', '2017-05-25 18:40:21', NULL, '', ''),
	(25, 'Metro', 'metro', '269-metro.png', 'sk', '2017-06-03 18:01:53', '2017-06-03 18:01:53', NULL, '', '');
/*!40000 ALTER TABLE `supermarkets` ENABLE KEYS */;

-- Dumping structure for table vegapo.tags
CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `value` int(2) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- Dumping data for table vegapo.tags: 12 rows
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` (`id`, `name`, `slug`, `image`, `country`, `created_at`, `updated_at`, `value`, `note`, `description`) VALUES
	(1, 'bez PO', 'bez-po', '681-palm-oil-free.jpg', 'cz', '2017-03-18 00:47:26', '2017-05-08 12:15:59', 2, NULL, NULL),
	(2, 'bez lepku', 'bez-lepku', '681-glutenfree.jpg', 'cz', '2017-03-18 00:48:36', '2017-05-08 12:16:07', 1, NULL, NULL),
	(3, 'bez lepku', 'bez-lepku', '147-bezlepku.png', 'sk', '2017-04-22 13:58:29', '2017-05-08 12:16:16', 1, NULL, NULL),
	(4, 'bez palmového oleja', 'bez-palmoveho-oleja', '409-nopalmoil.png', 'sk', '2017-04-22 13:58:38', '2017-05-08 12:16:26', 2, NULL, NULL),
	(5, 'bez cukru', 'bez-cukru', '463-bezcukru.png', 'sk', '2017-04-22 13:58:52', '2017-05-08 12:16:31', 3, NULL, NULL),
	(6, 'bez sóje', 'bez-soje', '950-sozyfree-2.jpg', 'sk', '2017-04-23 11:21:45', '2017-05-08 12:16:36', 4, NULL, NULL),
	(7, 'nečakané', 'necakane', '556-unexpected.jpg', 'sk', '2017-05-01 16:14:16', '2017-05-08 12:16:41', 5, NULL, NULL),
	(8, 'sezónne', 'sezonne', '408-season.jpg', 'sk', '2017-05-02 08:40:20', '2017-05-08 12:16:46', 6, NULL, NULL),
	(9, 'bez cukru', 'bez-cukru', '868-bezcukru.png', 'cz', '2017-05-08 12:17:43', '2017-05-08 12:18:54', 3, NULL, NULL),
	(10, 'bez sóje', 'bez-soje', '322-sozyfree-2.jpg', 'cz', '2017-05-08 12:18:00', '2017-05-08 12:18:58', 4, NULL, NULL),
	(11, 'nečakané', 'necakane', '736-unexpected.jpg', 'cz', '2017-05-08 12:18:19', '2017-05-08 12:19:03', 5, NULL, NULL),
	(12, 'sezónne', 'sezonne', '372-season.jpg', 'cz', '2017-05-08 12:18:35', '2017-05-08 12:19:07', 6, NULL, NULL);
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;

-- Dumping structure for table vegapo.testimonials
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table vegapo.testimonials: ~4 rows (approximately)
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` (`id`, `name`, `message`, `created_at`, `updated_at`) VALUES
	(1, 'Matej', 'This site is cool ', '2017-08-10 09:33:06', '2017-08-10 09:47:14'),
	(2, 'John', 'I really love this website', '2017-08-10 09:33:19', '2017-08-10 09:49:33'),
	(3, 'Mary', 'Vegapo is bringing vegans what they need', '2017-08-10 09:33:35', '2017-08-10 09:33:35');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;

-- Dumping structure for table vegapo.users
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(40) DEFAULT NULL,
  `last_name` varchar(40) DEFAULT NULL,
  `about_me` text,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_activity` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `country` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=245 DEFAULT CHARSET=utf8;

-- Dumping data for table vegapo.users: 0 rows
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`user_id`, `username`, `password`, `first_name`, `last_name`, `about_me`, `email`, `role`, `created_at`, `updated_at`, `last_activity`, `country`) VALUES
	(239, 'Developer', '$2y$10$tlqS8gyw/j1P2whVtMZRfO/JbPVJKpcv5bnInj6B8tgliC.VnE/ui', NULL, NULL, NULL, 'developer@test.sk', '450', '2017-08-28 09:27:43', '2017-08-28 09:27:43', '2017-08-28 09:27:43', 'sk'),
	(240, 'AdminSK', '$10$tlqS8gyw/j1P2whVtMZRfO/JbPVJKpcv5bnInj6B8tgliC.VnE/ui', NULL, NULL, NULL, 'admin@test.sk', '34', '2017-08-28 09:29:52', '2017-08-28 09:29:53', '2017-08-28 09:29:54', 'sk'),
	(241, 'SuperAdminSK', '$10$tlqS8gyw/j1P2whVtMZRfO/JbPVJKpcv5bnInj6B8tgliC.VnE/ui', NULL, NULL, NULL, 'superadmin@test.sk', '74', '2017-08-28 09:29:52', '2017-08-28 09:29:53', '2017-08-28 09:29:54', 'sk'),
	(242, 'AdminCZ', '$10$tlqS8gyw/j1P2whVtMZRfO/JbPVJKpcv5bnInj6B8tgliC.VnE/ui', NULL, NULL, NULL, 'admin@test.cz', '34', '2017-08-28 09:29:52', '2017-08-28 09:29:53', '2017-08-28 09:29:54', 'cz'),
	(243, 'SuperAdminCZ', '$10$tlqS8gyw/j1P2whVtMZRfO/JbPVJKpcv5bnInj6B8tgliC.VnE/ui', NULL, NULL, NULL, 'superadmin@test.cz', '74', '2017-08-28 09:29:52', '2017-08-28 09:29:53', '2017-08-28 09:29:54', 'cz'),
	(244, 'User', '$10$tlqS8gyw/j1P2whVtMZRfO/JbPVJKpcv5bnInj6B8tgliC.VnE/ui', NULL, NULL, NULL, 'user@test.sk', '4', '2017-08-28 09:31:54', '2017-08-28 09:31:54', '2017-08-28 09:31:55', 'sk');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
