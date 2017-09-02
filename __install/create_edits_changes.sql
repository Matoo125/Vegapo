CREATE TABLE `edits` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`type` VARCHAR(20) NOT NULL,
	`state` VARCHAR(20) NOT NULL DEFAULT 'opened',
	`user_id` INT(11) NOT NULL,
	`comment` TEXT NULL,
	`diff` TEXT NULL,
	`country` VARCHAR(2) NULL DEFAULT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `user_id` (`user_id`),
	INDEX `edits_type_idx` (`type`),
	INDEX `edits_state_idx` (`state`),
	INDEX `edits_country_idx` (`country`)
)
COMMENT='table for storing of user/admin data edits\r\n* object_id - id of object that was edited\r\n* object - edited object (products table etc)\r\n* action - type of edit\r\n* diff - textual diff between current and previous object version\r\n* comment - user comment for this edit'
COLLATE='utf8_general_ci' ENGINE=MyISAM;

CREATE TABLE `changes` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`edit_id` INT(11) NOT NULL,
	`object_type` VARCHAR(20) NULL DEFAULT NULL,
	`object_id` INT(11) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `edit_id` (`edit_id`),
	INDEX `changes_object_type_idx` (`object_type`),
	INDEX `changes_object_id_idx` (`object_id`)
)
COLLATE='utf8_general_ci' ENGINE=MyISAM;
