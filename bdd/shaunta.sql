-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 17 déc. 2017 à 14:20
-- Version du serveur :  5.7.19
-- Version de PHP :  7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `shaunta`
--

-- --------------------------------------------------------

--
-- Structure de la table `brand`
--

DROP TABLE IF EXISTS `brand`;
CREATE TABLE IF NOT EXISTS `brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `brand`
--

INSERT INTO `brand` (`id`, `brand`) VALUES
(1, 'Levis'),
(9, 'Lacoste'),
(8, 'Gucci'),
(7, 'Polo'),
(10, 'Armani'),
(11, 'Tom Tailor'),
(17, 'Tessst join'),
(16, 'TestPhotos');

-- --------------------------------------------------------

--
-- Structure de la table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items` text NOT NULL,
  `expire_date` datetime NOT NULL,
  `paid` tinyint(4) NOT NULL DEFAULT '0',
  `shipped` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=87 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cart`
--

INSERT INTO `cart` (`id`, `items`, `expire_date`, `paid`, `shipped`) VALUES
(68, '[{\"id\":\"32\",\"size\":\"md\",\"quantity\":\"1\"}]', '2017-12-31 15:30:06', 1, 0),
(64, '[{\"id\":\"31\",\"size\":\"38\",\"quantity\":4}]', '2017-12-18 13:03:16', 1, 0),
(65, '[{\"id\":\"26\",\"size\":\"sm\",\"quantity\":\"1\"}]', '2017-12-19 16:32:19', 1, 0),
(66, '[{\"id\":\"35\",\"size\":\"md\",\"quantity\":\"1\"}]', '2017-12-19 16:36:44', 1, 0),
(67, '[{\"id\":\"31\",\"size\":\"36\",\"quantity\":2},{\"id\":\"30\",\"size\":\"35\",\"quantity\":2}]', '2017-12-28 18:24:30', 1, 0),
(69, '[{\"id\":\"27\",\"size\":\"lg\",\"quantity\":\"1\"},{\"id\":\"27\",\"size\":\"sm\",\"quantity\":\"1\"}]', '2018-01-02 08:53:27', 1, 0),
(70, '[{\"id\":\"32\",\"size\":\"sm\",\"quantity\":\"1\"},{\"id\":\"35\",\"size\":\"sm\",\"quantity\":\"2\"},{\"id\":\"38\",\"size\":\"sm\",\"quantity\":\"1\"}]', '2018-01-02 08:54:45', 1, 0),
(71, '[{\"id\":\"36\",\"size\":\"sm\",\"quantity\":\"1\"},{\"id\":\"32\",\"size\":\"sm\",\"quantity\":\"1\"},{\"id\":\"31\",\"size\":\"36\",\"quantity\":\"1\"}]', '2018-01-02 08:57:55', 1, 0),
(72, '[{\"id\":\"35\",\"size\":\"sm\",\"quantity\":\"3\"}]', '2018-01-02 09:45:14', 1, 1),
(81, '[{\"id\":\"27\",\"size\":\"sm\",\"quantity\":\"3\"}]', '2018-01-03 17:56:15', 1, 0),
(82, '[{\"id\":\"35\",\"size\":\"md\",\"quantity\":\"5\"}]', '2018-01-03 18:01:26', 1, 0),
(83, '[{\"id\":\"30\",\"size\":\"36\",\"quantity\":\"2\"}]', '2018-01-05 18:37:16', 1, 0),
(84, '[{\"id\":\"32\",\"size\":\"lg\",\"quantity\":\"3\"}]', '2018-01-08 12:30:28', 1, 0),
(85, '[{\"id\":\"31\",\"size\":\"36\",\"quantity\":\"2\"}]', '2018-01-08 12:48:54', 1, 0),
(86, '[{\"id\":\"29\",\"size\":\"sm\",\"quantity\":\"4\"}]', '2018-01-09 14:01:13', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `category`, `parent`) VALUES
(1, 'Men', 0),
(2, 'Women', 0),
(3, 'Boys', 0),
(4, 'Girls', 0),
(5, 'Shirts', 1),
(6, 'Pants', 1),
(7, 'Shoes', 1),
(8, 'Accessories', 1),
(9, 'Shirts', 2),
(10, 'Pants', 2),
(11, 'Shoes', 2),
(12, 'Dresses', 2),
(13, 'Shirts', 3),
(14, 'Pants', 3),
(15, 'Dresses', 4),
(16, 'Shoes', 4),
(18, 'Accessories', 2),
(19, 'Shirts', 4);

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `list_price` decimal(10,2) NOT NULL,
  `brand` int(11) NOT NULL,
  `categories` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `description` text NOT NULL,
  `featured` int(11) NOT NULL,
  `sizes` varchar(255) NOT NULL,
  `deleted` int(11) NOT NULL,
  `sold` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `title`, `price`, `list_price`, `brand`, `categories`, `image`, `description`, `featured`, `sizes`, `deleted`, `sold`) VALUES
(27, 'Long Skirt', '19.99', '39.99', 8, '12', '/shaunta/images/products/7e467564d1f0b630ded204e7e8c2c687.png', '								Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempus dolor ut ex malesuada molestie. Fusce eu pellentesque odio, eget cursus neque. Nullam eu dictum sem, sodales varius orci. Integer eget magna porttitor, tristique leo quis, mattis nulla.													', 1, 'sm:12:,md:20:,lg:35:', 0, 3),
(28, 'Average Purse', '55.99', '74.99', 9, '18', '/shaunta/images/products/1c966175431c4d69ff050bf279bdc480.png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempus dolor ut ex malesuada molestie. Fusce eu pellentesque odio, eget cursus neque. Nullam eu dictum sem, sodales varius orci. Integer eget magna porttitor, tristique leo quis, mattis nulla.							', 1, 'default:0:10', 0, 0),
(29, 'Blouse', '9.99', '19.99', 7, '19', '/shaunta/images/products/b904dbb5c2ddc7397368160e67103708.png', '				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempus dolor ut ex malesuada molestie. Fusce eu pellentesque odio, eget cursus neque. Nullam eu dictum sem, sodales varius orci. Integer eget magna porttitor, tristique leo quis, mattis nulla.										', 1, 'sm:0:10,md:23:10,lg:19:10', 0, 4),
(30, 'High Heels', '89.99', '109.99', 8, '11', '/shaunta/images/products/a4f4f84ff13ed9e4ac3b9380204fce24.jpg', '								Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempus dolor ut ex malesuada molestie. Fusce eu pellentesque odio, eget cursus neque. Nullam eu dictum sem, sodales varius orci. Integer eget magna porttitor, tristique leo quis, mattis nulla.													', 1, '35:16:10,36:23:10,37:8:10', 0, 2),
(31, 'Elegant Shoes', '55.99', '79.99', 10, '11', '/shaunta/images/products/616f70e41090d36e0d2b97747c7fcb96.png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempus dolor ut ex malesuada molestie. Fusce eu pellentesque odio, eget cursus neque. Nullam eu dictum sem, sodales varius orci. Integer eget magna porttitor, tristique leo quis, mattis nulla.							', 1, '36:0:10,37:7:10,38:4:10', 0, 2),
(26, 'Summer Dress', '39.99', '59.99', 10, '12', '/shaunta/images/products/fc6879f7988b0c1feddf87ffd41d5526.png', '				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempus dolor ut ex malesuada molestie. Fusce eu pellentesque odio, eget cursus neque.								', 1, 'sm:12:10,md:24:10,lg:6:10', 0, 0),
(32, 'Striped Hoodie', '7.99', '15.99', 11, '13', '/shaunta/images/products/220ab18a702123ceaad46e9ce6d1d811.png', '				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempus dolor ut ex malesuada molestie. Fusce eu pellentesque odio, eget cursus neque. Nullam eu dictum sem, sodales varius orci. Integer eget magna porttitor, tristique leo quis, mattis nulla.										', 1, 'sm:18:10,md:26:10,lg:4:10', 0, 3),
(33, 'Cars And Trucks onesie', '15.99', '19.99', 7, '13', '/shaunta/images/products/5cef5250e6b542a146bc10905e257c45.png', '				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempus dolor ut ex malesuada molestie. Fusce eu pellentesque odio, eget cursus neque. Nullam eu dictum sem, sodales varius orci. Integer eget magna porttitor, tristique leo quis, mattis nulla.										', 1, 'sm:28:10,md:24:10,lg:17:10', 0, 0),
(34, 'Modern Pants', '29.99', '49.99', 1, '14', '/shaunta/images/products/ac98ba9c29724fd92fde1062c5fd33a3.png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempus dolor ut ex malesuada molestie. Fusce eu pellentesque odio, eget cursus neque. Nullam eu dictum sem, sodales varius orci. Integer eget magna porttitor, tristique leo quis, mattis nulla.							', 1, 'sm:2:10,md:6:10,lg:7:10', 0, 0),
(35, 'Cover Alls', '39.99', '45.99', 9, '14', '/shaunta/images/products/855c7be0a472f016a43b5ab303521dac.png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempus dolor ut ex malesuada molestie. Fusce eu pellentesque odio, eget cursus neque. Nullam eu dictum sem, sodales varius orci. Integer eget magna porttitor, tristique leo quis, mattis nulla.							', 1, 'sm:2:10,md:1:10', 0, 5),
(36, 'Levis Jeans', '45.99', '59.99', 1, '6', '/shaunta/images/products/264384b0380c7b6ff5069a49a5a48f42.png', '												Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempus dolor ut ex malesuada molestie. Fusce eu pellentesque odio, eget cursus neque. Nullam eu dictum sem, sodales varius orci. Integer eget magna porttitor, tristique leo quis, mattis nulla.																', 1, 'sm:15:10,md:24:10,lg:36:10', 0, 0),
(39, 'Second multiple photo', '45.99', '59.99', 16, '5', '/shaunta/images/products/314319801f71181b14e92eb5c5a96fab.png,/shaunta/images/products/b813069d529e54315975f1f516e3250f.png,/shaunta/images/products/edf3e7771a63bf394482f32801e7068a.png,/shaunta/images/products/d86e97bdc1a402d04b4be10ed59fde33.png', '	Multiple photos						', 1, 'sm:5:10,md:7:10,lg:8:10', 1, 0),
(38, 'Test multiple photo', '45.99', '59.99', 16, '6', '/shaunta/images/products/181999ec5f3e7649f2f0f11d7766ff38.png,/shaunta/images/products/a4d3099dd456fdee0e5875ea77061cfb.png,/shaunta/images/products/f415aa50f25a23bcd58858691eda1493.png', '				Test multiple photos										', 1, 'sm:4:10,md:40:10,lg:30:10', 1, 0),
(40, 'testtwig', '45.99', '59.99', 17, '13', '/shaunta/images/products/8a66aa7735cd132eac163567c7eb492d.png,/shaunta/images/products/7d004028a92d612e1baaf7f6e50c7ee4.png', '								Test products page with twig templates\r\n			\r\n			', 1, 'testSm:30:10,testMd:20:10,testLg:50:10', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `charge_id` varchar(255) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(175) NOT NULL,
  `street` varchar(255) NOT NULL,
  `street2` varchar(255) NOT NULL,
  `city` varchar(175) NOT NULL,
  `state` varchar(175) NOT NULL,
  `zip_code` varchar(175) NOT NULL,
  `country` varchar(255) NOT NULL,
  `sub_total` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `grand_total` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `tctn_type` varchar(255) NOT NULL,
  `tctn_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `transactions`
--

INSERT INTO `transactions` (`id`, `charge_id`, `cart_id`, `full_name`, `email`, `street`, `street2`, `city`, `state`, `zip_code`, `country`, `sub_total`, `tax`, `grand_total`, `description`, `tctn_type`, `tctn_date`) VALUES
(1, 'ch_1BPvegCou9RBHddSDQtrqDwS', 64, 'Alvin Mujkic', 'mujkicalvin1@gmail.com', '1B Place de La Mairie', '', 'France, Lons-Le-Saunier, 39000, France', 'Bourgogne', '71310', 'France', '223.96', '44.79', '268.75', '4 items from Shauntas Boutique', 'charge', '2017-11-19 17:28:24'),
(2, 'ch_1BPvizCou9RBHddSnqryIV8U', 65, 'Alvin Mujkic', 'mujkicalvin1@gmail.com', '1B Place de La Mairie', '', 'France, Lons-Le-Saunier, 39000, France', 'Bourgogne', '71310', 'France', '39.99', '8.00', '47.99', '1 item from Shauntas Boutique', 'charge', '2017-11-19 17:32:51'),
(3, 'ch_1BPvpJCou9RBHddSMB3xXHUJ', 66, 'Alvin Mujkic', 'mujkicalvin1@gmail.com', '1B Place de La Mairie', '', 'France, Lons-Le-Saunier, 39000, France', 'Bourgogne', '71310', 'France', '39.99', '8.00', '47.99', '1 item from Shauntas Boutique', 'charge', '2017-11-19 17:39:23'),
(4, 'ch_1BTDoeCou9RBHddS5vXajayg', 67, 'Alvin Mujkic', 'mujkicalvin1@gmail.com', '1B Place de La Mairie', '', 'Mervans', 'Bourgogne', '71310', 'France', '291.96', '58.39', '350.35', '4 items from Shauntas Boutique', 'charge', '2017-11-28 19:28:20'),
(5, 'ch_1BUGTkCou9RBHddShLIheD5K', 68, 'Alvin Mujkic', 'mujkicalvin1@gmail.com', '1B Place de La Mairie', '', 'France, Lons-Le-Saunier, 39000, France', 'Bourgogne', '71310', 'France', '7.99', '1.60', '9.59', '1 item from Shauntas Boutique', 'charge', '2017-12-01 16:31:02'),
(6, 'ch_1BUtEZCou9RBHddSPDpmUZtu', 69, 'Alvin Mujkic', 'mujkicalvin1@gmail.com', '1B Place de La Mairie', '', 'France, Lons-Le-Saunier, 39000, France', 'Bourgogne', '71310', 'France', '39.98', '8.00', '47.98', '2 items from Shauntas Boutique', 'charge', '2017-12-03 09:54:00'),
(7, 'ch_1BUtFnCou9RBHddSpbXRzZ2B', 70, 'Alvin Mujkic', 'mujkicalvin1@gmail.com', '1B Place de La Mairie', '', 'France, Lons-Le-Saunier, 39000, France', 'Bourgogne', '71310', 'France', '133.96', '26.79', '160.75', '4 items from Shauntas Boutique', 'charge', '2017-12-03 09:55:16'),
(8, 'ch_1BUtIpCou9RBHddSLEy9n665', 71, 'Alvin Mujkic', 'mujkicalvin1@gmail.com', '1B Place de La Mairie', '', 'France, Lons-Le-Saunier, 39000, France', 'Bourgogne', '71310', 'France', '109.97', '21.99', '131.96', '3 items from Shauntas Boutique', 'charge', '2017-12-03 09:58:24'),
(9, 'ch_1BUu2eCou9RBHddS1LpS5AeB', 72, 'Alvin Mujkic', 'mujkicalvin1@gmail.com', '1B Place de La Mairie', '', 'Mervans', 'Bourgogne', '71310', 'France', '119.97', '23.99', '143.96', '3 items from Shauntas Boutique', 'charge', '2017-12-03 10:45:45'),
(10, 'ch_1BVOBQCou9RBHddSeW3ckify', 81, 'Alvin Mujkic', 'mujkicalvin1@gmail.com', '1B Place de La Mairie', '', 'Mervans', 'Bourgogne', '71310', 'France', '59.97', '11.99', '71.96', '3 items from Shauntas Boutique', 'charge', '2017-12-04 18:56:48'),
(11, 'ch_1BVOH1Cou9RBHddSHdk2cCFH', 82, 'Alvin Mujkic', 'mujkicalvin1@gmail.com', '1B Place de La Mairie', '', 'Mervans', 'Bourgogne', '71310', 'France', '199.95', '39.99', '239.94', '5 items from Shauntas Boutique', 'charge', '2017-12-04 19:02:36'),
(12, 'ch_1BW7mMCou9RBHddSINcUiaDc', 83, 'Alvin Mujkic', 'mujkicalvin1@gmail.com', '1B Place de La Mairie', '', 'Mervans', 'Bourgogne', '71310', 'France', '179.98', '36.00', '215.98', '2 items from Shauntas Boutique', 'charge', '2017-12-06 19:37:57'),
(13, 'ch_1BX7U5Cou9RBHddSs06vKRPT', 84, 'Mujkic Alvin', 'mujkicalvin1@gmail.com', 'Place de La Mairie', '', 'Mervans', 'Bourgogne', '71310', 'France', '23.97', '4.79', '28.76', '3 items from Shauntas Boutique', 'charge', '2017-12-09 13:31:11'),
(14, 'ch_1BX7lvCou9RBHddSC1cvQiAd', 85, 'Mujkic Alvin', 'mujkicalvin1@gmail.com', '1b Place de La Mairie', 'street 2 test', 'Mervans', 'Bourgogne', '71310', 'France', '111.98', '22.40', '134.38', '2 items from Shauntas Boutique', 'charge', '2017-12-09 13:49:37'),
(15, 'ch_1BXVNECou9RBHddSeuQESJZZ', 86, 'Mujkic Alvin', 'mujkicalvin1@gmail.com', '1b Place de La Mairie', '', 'Mervans', 'Bourgogne', '71310', 'France', '39.96', '7.99', '47.95', '4 items from Shauntas Boutique', 'charge', '2017-12-10 15:01:45');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(175) NOT NULL,
  `password` varchar(255) NOT NULL,
  `join_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime NOT NULL,
  `permissions` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `join_date`, `last_login`, `permissions`) VALUES
(9, 'Mujkic Alvin', 'mujkicalvin1@gmail.com', '$2y$10$2YcOT3d3ZGWmY1lsYFfLRe9DbtJK89VWWIe1kU3euk6BiFanxnH/S', '2017-11-07 08:32:33', '2017-12-16 16:20:36', 'admin,editor');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
