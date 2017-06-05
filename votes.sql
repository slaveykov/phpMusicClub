-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Июн 05 2017 г., 18:49
-- Версия сервера: 10.1.22-MariaDB
-- Версия PHP: 7.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `phpmusicclub`
--

-- --------------------------------------------------------

--
-- Структура таблицы `votes`
--

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `type` enum('song','category','','') NOT NULL,
  `data` longtext NOT NULL,
  `ip` varchar(55) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `votes`
--

INSERT INTO `votes` (`id`, `type`, `data`, `ip`, `time`) VALUES
(1, 'song', '{\"cat_id\":10,\"song_id\":17}', '::1', '2017-06-03 10:29:34'),
(2, 'song', '{\"cat_id\":10,\"song_id\":22}', '::1', '2017-06-03 10:33:33'),
(3, 'song', '{\"cat_id\":10,\"song_id\":15,\"playlist_id\":89}', '::1', '2017-06-03 11:51:27'),
(4, 'song', '{\"cat_id\":10,\"song_id\":15,\"playlist_id\":89}', '::1', '2017-06-03 11:51:23'),
(5, 'song', '{\"cat_id\":10,\"song_id\":15,\"playlist_id\":89}', '::1', '2017-06-03 11:51:12'),
(6, 'song', '{\"cat_id\":10,\"song_id\":13,\"playlist_id\":90}', '::1', '2017-06-03 11:55:01'),
(7, 'song', '{\"cat_id\":10,\"song_id\":13,\"playlist_id\":90}', '::1', '2017-06-03 10:54:29');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
