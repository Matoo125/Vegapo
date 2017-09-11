CREATE TABLE `auth_tokens` (
  id int(11) PRIMARY KEY AUTO_INCREMENT,
  selector varchar(12) NOT NULL,
  hash varchar(64) NOT NULL,
  user_id int(11) NOT NULL,
  expires timestamp NOT NULL
);

DROP TABLE `forgotten_password`;