-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Ноя 15 2014 г., 16:59
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES cp1251 */;

--
-- База данных: `items`
--

-- --------------------------------------------------------

--
-- Структура таблицы `delivery_cities`
--

CREATE TABLE IF NOT EXISTS `delivery_cities` (
  `delivery_id` int(11) NOT NULL,
  `delivery_city` varchar(40) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL,
  `tax` tinyint(1) NOT NULL,
  PRIMARY KEY (`delivery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `delivery_cities`
--

INSERT INTO `delivery_cities` (`delivery_id`, `delivery_city`, `tax`) VALUES
(0, '------', 0),
(1, 'Киев', 0),
(2, 'Донецк', 0),
(3, 'Днепропетровск', 1),
(4, 'Харьков', 1),
(5, 'Хмельницкий', 0),
(6, 'Запорожье', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `goods`
--

CREATE TABLE IF NOT EXISTS `goods` (
  `goods_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `price` int(11) NOT NULL,
  `path` varchar(100) NOT NULL,
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `goods`
--

INSERT INTO `goods` (`goods_id`, `name`, `price`, `path`) VALUES
(1, 'ASUS CROSSBLADE RANGER', 2820, '1.jpg'),
(2, 'ASUS Gryphon Z97 Armor Edition', 3110, '2.jpg'),
(3, 'ASUS H97-PRO GAMER', 2360, '3.jpg'),
(4, 'ASUS X99-DELUXE', 7210, '4.jpg'),
(6, 'Asus A55BM-K', 853, '5.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `info_about_purchases`
--

CREATE TABLE IF NOT EXISTS `info_about_purchases` (
  `purchase_id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_ids` text CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL,
  `quantity` text CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL,
  `total_price` int(11) NOT NULL,
  `delivery_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `pickup` tinyint(1) NOT NULL,
  `email` text CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL,
  `name` text CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL,
  `phone` varchar(12) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`purchase_id`),
  KEY `delivery_id` (`delivery_id`,`payment_id`),
  KEY `payment_id` (`payment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

--
-- Дамп данных таблицы `info_about_purchases`
--

INSERT INTO `info_about_purchases` (`purchase_id`, `goods_ids`, `quantity`, `total_price`, `delivery_id`, `payment_id`, `pickup`, `email`, `name`, `phone`, `status`) VALUES
(47, 'a:2:{i:0;s:1:"1";i:1;s:1:"2";}', 'a:2:{i:0;i:1;i:1;i:1;}', 6228, 3, 3, 1, '4lenderman@gmail.com', 'Vova', '0999088149', 1),
(48, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}', 'a:2:{i:0;i:2;i:1;i:1;}', 11955, 2, 2, 1, 'lenchvov@rambler.ru', 'Валентин', '0999088149', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `payments_id` int(11) NOT NULL,
  `payment` varchar(60) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL,
  `tax` tinyint(1) NOT NULL,
  PRIMARY KEY (`payments_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `payments`
--

INSERT INTO `payments` (`payments_id`, `payment`, `tax`) VALUES
(0, '------', 0),
(1, 'Наличный расчет', 0),
(2, 'Оплата при получении', 0),
(3, 'Безналичный расчет', 1),
(4, 'Webmoney', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL,
  `email` text CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL,
  `phone` varchar(13) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL,
  `password` varchar(32) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL,
  `admin` tinyint(1) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `phone`, `password`, `admin`) VALUES
(18, 'Vova', '4lenderman@gmail.com', '0999088149', 'e10adc3949ba59abbe56e057f20f883e', 1),
(20, 'Валентин', 'lenchvov@rambler.ru', '0999088149', '1897a69ef451f0991bb85c6e7c35aa31', 0),
(21, 'Юляха', 'yuliya.lyashenko@list.ru', '0999088149', 'e10adc3949ba59abbe56e057f20f883e', 0);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `info_about_purchases`
--
ALTER TABLE `info_about_purchases`
  ADD CONSTRAINT `info_about_purchases_ibfk_1` FOREIGN KEY (`delivery_id`) REFERENCES `delivery_cities` (`delivery_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `info_about_purchases_ibfk_2` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`payments_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
