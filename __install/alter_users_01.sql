alter table users add facebook_id varchar(64) default null;
CREATE INDEX users_facebook_id_idx on users (facebook_id);