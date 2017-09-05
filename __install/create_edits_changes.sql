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

# prehlad editov z roznych zdrojov - pracovna verzia, stlpce sa budu menit podla potreby
CREATE or replace VIEW edit_details AS 
	#locale
	SELECT 
		id edit_id, type edit_type, state edit_state, user_id edit_user_id, comment edit_comment, diff edit_diff, country edit_country, created_at edit_created_at, updated_at edit_updated_at, 
		object_type, object_id, null object_user_id
	FROM edits where object_type = 'locale'
	union
	#suggestions 
	SELECT 
		q.id edit_id, q.type edit_type, q.state edit_state, q.user_id edit_user_id, q.comment edit_comment, q.diff edit_diff, q.country edit_country, q.created_at edit_created_at, q.updated_at edit_updated_at,
		q.object_type, q.object_id,  w.user_id object_user_id
	FROM edits q, suggestions w where q.object_type = 'suggestions' and q.object_id = w.id
;

