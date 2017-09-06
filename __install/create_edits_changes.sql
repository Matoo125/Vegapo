DROP TABLE `edit_changes`;
DROP TABLE `edits`;

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
COMMENT=''
COLLATE='utf8_general_ci' ENGINE=MyISAM;

