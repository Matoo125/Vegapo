CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(40) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `category_parent` int(11) DEFAULT '0',
  `category_image` varchar(255) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

CREATE TABLE `matching_supermarkets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `supermarket_id` int(11) NOT NULL,
  `country` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=latin1;

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `expected_price` decimal(4,2) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `authorized_by` int(11) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

CREATE TABLE `supermarkets` (
  `supermarket_id` int(11) NOT NULL AUTO_INCREMENT,
  `supermarket_name` varchar(40) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `supermarket_image` varchar(255) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`supermarket_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;


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
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


--  --  --  --  --  --  --  --  -- --  --  --  --  --  --  --  --  --  
--  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  
-- done


ALTER TABLE products CHANGE authorized_by visibility int(1) DEFAULT NULL;

UPDATE products SET author_id = 2 WHERE author_id IS NULL;

UPDATE products SET visibility = 1 WHERE visibility IS NULL;

ALTER TABLE users ADD last_activity TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE users CHANGE updated_at updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE users ADD country varchar(2) DEFAULT NULL;

ALTER TABLE users ADD UNIQUE (email);


-- todo

ALTER TABLE products CHANGE product_id id int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE products CHANGE product_name name VARCHAR(255) NOT NULL;
ALTER TABLE products CHANGE product_image image VARCHAR(255) DEFAULT NULL;

ALTER TABLE categories CHANGE category_id id int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE categories CHANGE category_name name VARCHAR(40) NOT NULL;
ALTER TABLE categories CHANGE category_parent parent int(11) DEFAULT NULL;
ALTER TABLE categories CHANGE category_image image VARCHAR(255) DEFAULT NULL;

ALTER TABLE supermarkets CHANGE supermarket_id id int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE supermarkets CHANGE supermarket_name name VARCHAR(255) NOT NULL;
ALTER TABLE supermarkets CHANGE supermarket_image image VARCHAR(255) DEFAULT NULL;

-- next

CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `matching_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `country` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


CREATE TABLE `newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL
);

ALTER TABLE `newsletter` ADD country varchar(2) DEFAULT NULL;


CREATE TABLE `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `author` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` TEXT NOT NULL,
  `country` varchar(2) NOT NULL
);

ALTER TABLE `contact` ADD state varchar(5) NOT NULL;
ALTER TABLE `contact` ADD email varchar(255) NOT NULL;

CREATE TABLE `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `filename` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `role` int(1) NOT NULL,
  `country` varchar(2) NOT NULL
);

-- done 30.3.2017
ALTER TABLE products DROP COLUMN image;