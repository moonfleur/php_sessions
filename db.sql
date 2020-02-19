CREATE TABLE users (
  id int(11) NOT NULL AUTO_INCREMENT,
  login varchar(255) DEFAULT NULL,
  email varchar(50) DEFAULT NULL,
  password varchar(255) DEFAULT NULL,
  created_at datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 12,
AVG_ROW_LENGTH = 1489,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;