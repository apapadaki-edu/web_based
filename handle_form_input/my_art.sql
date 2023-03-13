SET AUTOCOMMIT = 0;
START TRANSACTION;

CREATE DATABASE IF NOT EXISTS `my_art`;

USE `my_art`;

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip` varchar(50) NOT NULL,
  `country` varchar(100) NOT NULL
);

ALTER TABLE `customer` ADD PRIMARY KEY (`id`);

ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


CREATE USER IF NOT EXISTS 'webuser'@'localhost' IDENTIFIED BY '12345678';

GRANT SELECT, INSERT, UPDATE, DELETE ON my_art.* TO webuser@'localhost';

COMMIT;
