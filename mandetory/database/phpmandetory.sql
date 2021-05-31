-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Vært: 127.0.0.1:3306
-- Genereringstid: 31. 05 2021 kl. 16:14:55
-- Serverversion: 8.0.21
-- PHP-version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phpmandetory`
--

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` double(10,2) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `subCategory` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Data dump for tabellen `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `category`, `subCategory`) VALUES
(1, 'Samsung phone', 60000.00, 'Technology', 'Phone'),
(4, 'Rolex watch', 10400000.00, 'Accessories', 'Jewelry'),
(5, 'VASCHY backpack', 18500.00, 'Accessories', 'Bags'),
(10, 'HP Notebook', 200000.00, 'Technology', 'Laptop'),
(11, 'Car plug to USB adapter', 12000.00, 'Technology', 'Adapters'),
(12, 'Sony Headphones', 38000.00, 'Technology', 'Sound'),
(13, 'Panasonic phone', 60000.00, 'Technology', 'Phone');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `phone` int DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Data dump for tabellen `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `address`, `email`, `password`, `admin`) VALUES
(1, 'Sebastian', 28896778, 'Holmebækhuse 157, 4681 Herfølge', 'sebhein17@gmail.com', '123', 1),
(2, 'bo', 12345679, 'bovej 1, 1111 boby', 'bo@mail.com', '', 0),
(3, 'Notadmin', 12345678, 'Notadmin 1, 1111 Notadmin', 'notadmin@mail.com', '123', 0),
(4, 'Admin', 12345678, 'Admin 1, 1111 Admin', 'admin@mail.com', '123', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
