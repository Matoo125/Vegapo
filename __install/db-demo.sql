-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `answers`;
CREATE TABLE `answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `auth_tokens`;
CREATE TABLE `auth_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `selector` varchar(12) NOT NULL,
  `hash` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `contact`;
CREATE TABLE `contact` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `edits`;
CREATE TABLE `edits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `state` varchar(20) NOT NULL DEFAULT 'opened',
  `user_id` int(11) NOT NULL,
  `object_type` varchar(20) NOT NULL,
  `object_id` int(11) DEFAULT NULL,
  `comment` text,
  `diff` text,
  `country` varchar(2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `edits_type_idx` (`type`),
  KEY `edits_state_idx` (`state`),
  KEY `edits_object_type_idx` (`object_type`),
  KEY `edits_object_id_idx` (`object_id`),
  KEY `edits_country_idx` (`country`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP VIEW IF EXISTS `edit_details`;
CREATE TABLE `edit_details` (`edit_id` int(11), `edit_type` varchar(20), `edit_sub_type` int(11), `edit_state` varchar(20), `edit_user_id` int(11), `edit_username` varchar(40), `edit_comment` text, `edit_diff` text, `edit_country` varchar(2), `edit_created_at` timestamp, `edit_updated_at` timestamp, `object_type` varchar(20), `object_id` text, `object_name` text, `object_user_id` int(11), `object_username` varchar(40));


DROP TABLE IF EXISTS `favourite_products`;
CREATE TABLE `favourite_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `role` int(1) NOT NULL,
  `country` varchar(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `matching_supermarkets`;
CREATE TABLE `matching_supermarkets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `supermarket_id` int(11) NOT NULL,
  `country` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `matching_tags`;
CREATE TABLE `matching_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `country` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `newsletter`;
CREATE TABLE `newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `country` varchar(2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `newsletter_history`;
CREATE TABLE `newsletter_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `newsletter` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
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
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `suggestions`;
CREATE TABLE `suggestions` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `supermarkets`;
CREATE TABLE `supermarkets` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
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
  `facebook_id` varchar(64) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  KEY `users_facebook_id_idx` (`facebook_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `edit_details`;
CREATE ALGORITHM=UNDEFINED DEFINER=`vegpotraviny`@`88.86.12%` SQL SECURITY DEFINER VIEW `edit_details` AS select `q`.`id` AS `edit_id`,`q`.`type` AS `edit_type`,NULL AS `edit_sub_type`,`q`.`state` AS `edit_state`,`q`.`user_id` AS `edit_user_id`,`w`.`username` AS `edit_username`,`q`.`comment` AS `edit_comment`,`q`.`diff` AS `edit_diff`,`q`.`country` AS `edit_country`,`q`.`created_at` AS `edit_created_at`,`q`.`updated_at` AS `edit_updated_at`,`q`.`object_type` AS `object_type`,'locale' AS `object_id`,NULL AS `object_name`,NULL AS `object_user_id`,NULL AS `object_username` from (`edits` `q` join `users` `w`) where ((`q`.`object_type` = 'locale') and (`q`.`user_id` = `w`.`user_id`)) union select `q`.`id` AS `edit_id`,`q`.`type` AS `edit_type`,`w`.`type` AS `edit_sub_type`,`q`.`state` AS `edit_state`,`q`.`user_id` AS `edit_user_id`,`e`.`username` AS `edit_username`,`q`.`comment` AS `edit_comment`,`q`.`diff` AS `edit_diff`,`q`.`country` AS `edit_country`,`q`.`created_at` AS `edit_created_at`,`q`.`updated_at` AS `edit_updated_at`,'product' AS `object_type`,`w`.`product_id` AS `object_id`,`t`.`name` AS `object_name`,`w`.`user_id` AS `object_user_id`,`r`.`username` AS `object_username` from ((((`edits` `q` join `suggestions` `w`) join `users` `e`) join `users` `r`) join `products` `t`) where ((`q`.`object_type` = 'suggestion') and (`q`.`object_id` = `w`.`id`) and (`q`.`user_id` = `e`.`user_id`) and (`w`.`user_id` = `r`.`user_id`) and (`t`.`id` = `w`.`product_id`)) union select `q`.`id` AS `edit_id`,`q`.`type` AS `edit_type`,NULL AS `edit_sub_type`,`q`.`state` AS `edit_state`,`q`.`user_id` AS `edit_user_id`,`e`.`username` AS `edit_username`,`q`.`comment` AS `edit_comment`,`q`.`diff` AS `edit_diff`,`q`.`country` AS `edit_country`,`q`.`created_at` AS `edit_created_at`,`q`.`updated_at` AS `edit_updated_at`,`q`.`object_type` AS `object_type`,`w`.`id` AS `object_id`,`w`.`name` AS `object_name`,`w`.`author_id` AS `object_user_id`,`r`.`username` AS `object_username` from (((`edits` `q` join `products` `w`) join `users` `e`) join `users` `r`) where ((`q`.`object_type` = 'product') and (`q`.`object_id` = `w`.`id`) and (`q`.`user_id` = `e`.`user_id`) and (`w`.`author_id` = `r`.`user_id`)) union select `q`.`id` AS `edit_id`,`q`.`type` AS `edit_type`,NULL AS `edit_sub_type`,`q`.`state` AS `edit_state`,`q`.`user_id` AS `edit_user_id`,`e`.`username` AS `edit_username`,`q`.`comment` AS `edit_comment`,`q`.`diff` AS `edit_diff`,`q`.`country` AS `edit_country`,`q`.`created_at` AS `edit_created_at`,`q`.`updated_at` AS `edit_updated_at`,`q`.`object_type` AS `object_type`,`w`.`user_id` AS `object_id`,`w`.`username` AS `object_name`,NULL AS `object_user_id`,NULL AS `object_username` from ((`edits` `q` join `users` `w`) join `users` `e`) where ((`q`.`object_type` = 'user') and (`q`.`object_id` = `w`.`user_id`) and (`q`.`user_id` = `e`.`user_id`)) union select `q`.`id` AS `edit_id`,`q`.`type` AS `edit_type`,NULL AS `edit_sub_type`,`q`.`state` AS `edit_state`,`q`.`user_id` AS `edit_user_id`,`w`.`username` AS `edit_username`,NULL AS `edit_comment`,`q`.`diff` AS `edit_diff`,`q`.`country` AS `edit_country`,`q`.`created_at` AS `edit_created_at`,`q`.`updated_at` AS `edit_updated_at`,`q`.`object_type` AS `object_type`,`q`.`comment` AS `object_id`,`q`.`comment` AS `object_name`,NULL AS `object_user_id`,NULL AS `object_username` from (`edits` `q` join `users` `w`) where ((`q`.`object_type` = 'info') and (`q`.`user_id` = `w`.`user_id`)) union select `q`.`id` AS `edit_id`,`q`.`type` AS `edit_type`,NULL AS `edit_sub_type`,`q`.`state` AS `edit_state`,`q`.`user_id` AS `edit_user_id`,`e`.`username` AS `edit_username`,`q`.`comment` AS `edit_comment`,`q`.`diff` AS `edit_diff`,`q`.`country` AS `edit_country`,`q`.`created_at` AS `edit_created_at`,`q`.`updated_at` AS `edit_updated_at`,`q`.`object_type` AS `object_type`,`w`.`id` AS `object_id`,`w`.`name` AS `object_name`,`e`.`user_id` AS `object_user_id`,`e`.`username` AS `object_username` from ((`edits` `q` join `tags` `w`) join `users` `e`) where ((`q`.`object_type` = 'tag') and (`q`.`object_id` = `w`.`id`) and (`q`.`user_id` = `e`.`user_id`)) union select `q`.`id` AS `edit_id`,`q`.`type` AS `edit_type`,NULL AS `edit_sub_type`,`q`.`state` AS `edit_state`,`q`.`user_id` AS `edit_user_id`,`e`.`username` AS `edit_username`,`q`.`comment` AS `edit_comment`,`q`.`diff` AS `edit_diff`,`q`.`country` AS `edit_country`,`q`.`created_at` AS `edit_created_at`,`q`.`updated_at` AS `edit_updated_at`,`q`.`object_type` AS `object_type`,`w`.`id` AS `object_id`,`w`.`name` AS `object_name`,`e`.`user_id` AS `object_user_id`,`e`.`username` AS `object_username` from ((`edits` `q` join `supermarkets` `w`) join `users` `e`) where ((`q`.`object_type` = 'store') and (`q`.`object_id` = `w`.`id`) and (`q`.`user_id` = `e`.`user_id`)) union select `q`.`id` AS `edit_id`,`q`.`type` AS `edit_type`,NULL AS `edit_sub_type`,`q`.`state` AS `edit_state`,`q`.`user_id` AS `edit_user_id`,`e`.`username` AS `edit_username`,`q`.`comment` AS `edit_comment`,`q`.`diff` AS `edit_diff`,`q`.`country` AS `edit_country`,`q`.`created_at` AS `edit_created_at`,`q`.`updated_at` AS `edit_updated_at`,`q`.`object_type` AS `object_type`,`w`.`id` AS `object_id`,`w`.`name` AS `object_name`,`e`.`user_id` AS `object_user_id`,`e`.`username` AS `object_username` from ((`edits` `q` join `categories` `w`) join `users` `e`) where ((`q`.`object_type` = 'category') and (`q`.`object_id` = `w`.`id`) and (`q`.`user_id` = `e`.`user_id`)) union select `q`.`id` AS `edit_id`,`q`.`type` AS `edit_type`,NULL AS `edit_sub_type`,`q`.`state` AS `edit_state`,`q`.`user_id` AS `edit_user_id`,`w`.`username` AS `edit_username`,NULL AS `edit_comment`,`q`.`diff` AS `edit_diff`,`q`.`country` AS `edit_country`,`q`.`created_at` AS `edit_created_at`,`q`.`updated_at` AS `edit_updated_at`,`q`.`object_type` AS `object_type`,`q`.`comment` AS `object_id`,`q`.`comment` AS `object_name`,NULL AS `object_user_id`,NULL AS `object_username` from (`edits` `q` join `users` `w`) where ((`q`.`object_type` = 'newsletter') and (`q`.`user_id` = `w`.`user_id`));

-- 2017-12-15 09:05:39
