-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Oct 15, 2016 at 01:44 PM
-- Server version: 10.0.27-MariaDB-cll-lve
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `slavish1_music`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'BG RAP'),
(9, 'CHALGA'),
(10, 'BG POP'),
(11, 'EN POP'),
(12, 'HOUSE'),
(13, 'DEEP HOUSE'),
(14, 'BG RNB');

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

CREATE TABLE IF NOT EXISTS `playlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `song_id` int(11) NOT NULL,
  `time_to_start` int(11) NOT NULL,
  `time_to_finish` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `playlist`
--

INSERT INTO `playlist` (`id`, `cat_id`, `song_id`, `time_to_start`, `time_to_finish`) VALUES
(25, 10, 17, 1472733359, 1472733646),
(26, 10, 20, 1472733646, 1472733957),
(27, 10, 22, 1472733957, 1472734148),
(28, 10, 26, 1472734148, 1472734347),
(29, 10, 19, 1472734347, 1472734576),
(30, 10, 15, 1472734576, 1472734785),
(31, 10, 14, 1472734785, 1472735012),
(32, 10, 18, 1472735012, 1472735220),
(33, 10, 16, 1472735220, 1472735405),
(34, 10, 13, 1472735405, 1472735631),
(35, 10, 21, 1472735631, 1472735846),
(36, 1, 12, 1474728894, 1474729115);

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE IF NOT EXISTS `songs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `seconds` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `youtube_id` varchar(255) NOT NULL,
  `last_play` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`id`, `cat_id`, `seconds`, `name`, `youtube_id`, `last_play`) VALUES
(12, 1, 221, 'Slatkaristika feat. Krisko - Tik-Tak [Official HD Video]', 'JUMU37hAF7o', 0),
(13, 10, 226, 'Gery-Nikol - I''m The Queen /BG Official HD Video, 2016/', 'JrGlNYc-AGM', 0),
(14, 10, 227, 'Ð›Ð¾Ñ€Ð° ÐšÐ°Ñ€Ð°Ð´Ð¶Ð¾Ð²Ð° feat. Ð‘Ð¾Ð±Ð¾ - Ð•Ð´Ð½Ð° Ð�Ð° ÐœÐ¸Ð»Ð¸Ð¾Ð½ [Official HD Video]', '1KfBfqKnsRQ', 0),
(15, 10, 209, 'NADIA - Ð¡Ð°Ð¼Ð¾ Ñ‚ÐµÐ± (Official Video)', 'TvzRN3BVHrc', 0),
(16, 10, 185, 'Ð¡Ð°Ð½Ñ‚Ñ€Ð° feat. Ð›Ð¾Ñ€Ð° ÐšÐ°Ñ€Ð°Ð´Ð¶Ð¾Ð²Ð° - Ð£Ñ�ÐµÑ‰Ð°Ð¼ Ð¾Ñ‰Ðµ [Official HD Video]', 'l0Jin_JR-yI', 0),
(17, 10, 287, 'Lora Karadjova feat. 100 Kila - Spusnati Zavesi (Fang Remix)', 'm1rrJIf8K9Q', 0),
(18, 10, 208, 'Vanko1 & Irina Florin - Zapazi Vecherta', '1hCOlyh_9F8', 0),
(19, 10, 229, '100 Kila feat. Ð›Ð¾Ñ€Ð° ÐšÐ°Ñ€Ð°Ð´Ð¶Ð¾Ð²Ð° - Ð¦Ñ�Ð»Ð° Ð�Ð¾Ñ‰ [Official HD Video]', 'QclJQbgWFiA', 0),
(20, 10, 311, 'GOODSLAV feat. Ð›Ð¾Ñ€Ð° ÐšÐ°Ñ€Ð°Ð´Ð¶Ð¾Ð²Ð° - Ð�ÐµÐºÐ° Ð±ÑŠÐ´Ðµ Ð»Ñ�Ñ‚Ð¾ (Ñ€ÐµÐ¼Ð¸ÐºÑ� 2010)', 'OiaNPhlohHM', 0),
(21, 10, 215, '100 ÐšÐ¸Ð»Ð° feat. ÐœÐ°Ð³Ð¸ Ð”Ð¶Ð°Ð½Ð°Ð²Ð°Ñ€Ð¾Ð²Ð° - ÐœÐ¾ÐµÑ‚Ð¾ Ð Ð°Ð´Ð¸Ð¾ [Official HD Video]', '4sdjJvCVmjE', 0),
(22, 10, 191, 'Ice Cream - Ð–Ð¸Ð²Ð¾Ñ‚ÑŠÑ‚ Ðµ ÐµÐ´Ð¸Ð½ (Official HD video)', 'H9a7xM5ms-g', 0),
(26, 10, 199, 'Billy Hlapeto feat. MIhaela Fileva - Kogato ti triabvam ', 'yDRDvnfmOMg', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
