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
);

-- newsletter history
CREATE TABLE newsletter_history (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `newsletter` varchar(255) NOT NULL, -- id of newsletter
  `email` varchar(255) NOT NULL, -- email of recipient
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE comments(
  `id` INT(11) PRIMARY KEY AUTO_INCREMENT,
  `product_id` INT(11) NOT NULL,
  `author_id` INT(11) NOT NULL,
  `body` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE users ADD avatar varchar(255);

# prehlad editov z roznych zdrojov - pracovna verzia, stlpce sa budu menit podla potreby
CREATE or replace VIEW edit_details AS 
  #locale
  SELECT 
    q.id edit_id, q.type edit_type, null edit_sub_type, q.state edit_state, q.user_id edit_user_id, w.username edit_username, q.comment edit_comment, q.diff edit_diff, q.country edit_country, q.created_at edit_created_at, q.updated_at edit_updated_at,
    q.object_type, 'locale' object_id, null object_name, null object_user_id, null object_username
  FROM edits q, users w 
  where q.object_type = 'locale' and q.user_id = w.user_id
  union
  #suggestion
  SELECT 
    q.id edit_id, q.type edit_type, w.type edit_sub_type, q.state edit_state, q.user_id edit_user_id, e.username edit_username, q.comment edit_comment, q.diff edit_diff, q.country edit_country, q.created_at edit_created_at, q.updated_at edit_updated_at,
    'product' object_type, w.product_id object_id, t.name object_name,  w.user_id object_user_id, r.username object_username
  FROM edits q, suggestions w, users e, users r, products t
  where q.object_type = 'suggestion' and q.object_id = w.id and q.user_id = e.user_id and w.user_id = r.user_id and t.id= w.product_id
  union
  #product  
  SELECT 
    q.id edit_id, q.type edit_type, null edit_sub_type, q.state edit_state, q.user_id edit_user_id, e.username edit_username, q.comment edit_comment, q.diff edit_diff, q.country edit_country, q.created_at edit_created_at, q.updated_at edit_updated_at,
    q.object_type, w.id object_id, w.name object_name,  w.author_id object_user_id, r.username object_username
  FROM edits q, products w, users e, users r
  where q.object_type = 'product' and q.object_id = w.id and q.user_id = e.user_id and w.author_id = r.user_id 
  union
  #user
  SELECT 
    q.id edit_id, q.type edit_type, null edit_sub_type, q.state edit_state, q.user_id edit_user_id, e.username edit_username, q.comment edit_comment, q.diff edit_diff, q.country edit_country, q.created_at edit_created_at, q.updated_at edit_updated_at,
    q.object_type, w.user_id object_id, w.username object_name,  null object_user_id, null object_username
  FROM edits q, users w, users e
  where q.object_type = 'user' and q.object_id = w.user_id and q.user_id = e.user_id 
  union
  #info
  SELECT 
    q.id edit_id, q.type edit_type, null edit_sub_type, q.state edit_state, q.user_id edit_user_id, w.username edit_username, null edit_comment, q.diff edit_diff, q.country edit_country, q.created_at edit_created_at, q.updated_at edit_updated_at,
    q.object_type, q.comment object_id, q.comment object_name, null object_user_id, null object_username
  FROM edits q, users w 
  where q.object_type = 'info' and q.user_id = w.user_id  
  union
  #tag
  SELECT 
    q.id edit_id, q.type edit_type, null edit_sub_type, q.state edit_state, q.user_id edit_user_id, e.username edit_username, q.comment edit_comment, q.diff edit_diff, q.country edit_country, q.created_at edit_created_at, q.updated_at edit_updated_at,
    q.object_type, w.id object_id, w.name object_name,  e.user_id object_user_id, e.username object_username
  FROM edits q, tags w, users e
  where q.object_type = 'tag' and q.object_id = w.id and q.user_id = e.user_id 
  union
  #store
  SELECT 
    q.id edit_id, q.type edit_type, null edit_sub_type, q.state edit_state, q.user_id edit_user_id, e.username edit_username, q.comment edit_comment, q.diff edit_diff, q.country edit_country, q.created_at edit_created_at, q.updated_at edit_updated_at,
    q.object_type, w.id object_id, w.name object_name,  e.user_id object_user_id, e.username object_username
  FROM edits q, supermarkets w, users e
  where q.object_type = 'store' and q.object_id = w.id and q.user_id = e.user_id 
  union
  #category
  SELECT 
    q.id edit_id, q.type edit_type, null edit_sub_type, q.state edit_state, q.user_id edit_user_id, e.username edit_username, q.comment edit_comment, q.diff edit_diff, q.country edit_country, q.created_at edit_created_at, q.updated_at edit_updated_at,
    q.object_type, w.id object_id, w.name object_name,  e.user_id object_user_id, e.username object_username
  FROM edits q, categories w, users e
  where q.object_type = 'category' and q.object_id = w.id and q.user_id = e.user_id   
  union
  #newsletter
  SELECT 
    q.id edit_id, q.type edit_type, null edit_sub_type, q.state edit_state, q.user_id edit_user_id, w.username edit_username, null edit_comment, q.diff edit_diff, q.country edit_country, q.created_at edit_created_at, q.updated_at edit_updated_at,
    q.object_type, q.comment object_id, q.comment object_name, null object_user_id, null object_username
  FROM edits q, users w 
  where q.object_type = 'newsletter' and q.user_id = w.user_id  
;


