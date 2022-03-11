SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE IF NOT EXISTS `TheGIFClub`;
USE `TheGIFClub`;

DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
    `user_id` INT NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NOT NULL,
    PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Search`;
CREATE TABLE `Search` (
    `search_id` INT NOT NULL AUTO_INCREMENT,
    `query` VARCHAR(255) NOT NULL,
    `timestamp` DATETIME NOT NULL,
    PRIMARY KEY (`search_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `UserSearch`;
CREATE TABLE `UserSearch` (
    `user_id` INT NOT NULL,
    `search_id` INT NOT NULL,
    PRIMARY KEY(user_id, search_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
	FOREIGN KEY (search_id) REFERENCES Search(search_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;