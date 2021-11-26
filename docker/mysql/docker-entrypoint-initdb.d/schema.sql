-- if your $DATA_PATH_HOST/mysql is exists and you do not want to delete it, you can run by manual execution:
-- mysql -u root -p < /docker-entrypoint-initdb.d/createdb.sql
-- docker-compose exec mysql bash

CREATE TABLE `user` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Entity ID',
    `username` varchar(40) NOT NULL COMMENT 'User Login',
    `email` varchar(255) NOT NULL COMMENT 'Email',
    `firstname` varchar(255) NOT NULL COMMENT 'First Name',
    `lastname` varchar(255) NOT NULL COMMENT 'Last Name',
    `dob` date DEFAULT NULL COMMENT 'Date of Birth',
    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'User Created Time',
    `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'User Modified Time',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#CREATE INDEX USER_USERNAME_BTREE_INDEX USING BTREE ON tasks (username);
#CREATE INDEX USER_USERNAME_HASH_INDEX USING HASH ON tasks (created);

OPTIMIZE TABLE user;
