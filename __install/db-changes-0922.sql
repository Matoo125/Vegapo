-- add product type
ALTER TABLE products ADD type int(11);
update products set type = 2 where visibility < 3;

-- add facebook id
alter table users add facebook_id varchar(64) default null;
CREATE INDEX users_facebook_id_idx on users (facebook_id);

-- auth tokens
CREATE TABLE `auth_tokens` (
  id int(11) PRIMARY KEY AUTO_INCREMENT,
  selector varchar(12) NOT NULL,
  hash varchar(64) NOT NULL,
  user_id int(11) NOT NULL,
  expires timestamp NOT NULL
);

DROP TABLE `forgotten_password`;

-- edits
CREATE TABLE `edits` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(20) NOT NULL,
  `state` VARCHAR(20) NOT NULL DEFAULT 'opened',
  `user_id` INT(11) NOT NULL,
  `object_type` VARCHAR(20) not NULL,
  `object_id` INT(11) NULL, 
  `comment` TEXT NULL,
  `diff` TEXT NULL,
  `country` VARCHAR(2) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `user_id` (`user_id`),
  FOREIGN KEY (user_id) REFERENCES users(id) ,
  INDEX `edits_type_idx` (`type`),
  INDEX `edits_state_idx` (`state`),
  INDEX `edits_object_type_idx` (`object_type`),
  INDEX `edits_object_id_idx` (`object_id`),
  INDEX `edits_country_idx` (`country`)
)

-- newsletter history
CREATE TABLE newsletter_history (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `newsletter` varchar(255) NOT NULL, -- id of newsletter
  `email` varchar(255) NOT NULL, -- email of recipient
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)

