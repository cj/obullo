-- phpMyAdmin SQL Dump
-- version 2.11.1
-- http://www.phpmyadmin.net
--
-- Anamakine: localhost
-- Üretim Zamanı: 20 Ekim 2009 saat 23:34:43
-- Sunucu sürümü: 5.0.45
-- PHP Sürümü: 5.2.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Veritabanı: `example_db`
--

-- --------------------------------------------------------

--
-- Tablo yapısı: `articles`
--

CREATE TABLE `articles` (
  `article_id` int(11) NOT NULL auto_increment,
  `title` varchar(500) character set utf8 NOT NULL,
  `article` text character set utf8 NOT NULL,
  `link` varchar(600) character set utf8 NOT NULL,
  PRIMARY KEY  (`article_id`),
  KEY `link` (`link`(333))
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=10 ;

--
-- Tablo döküm verisi `articles`
--

INSERT INTO `articles` (`article_id`, `title`, `article`, `link`) VALUES
(1, 'baslik', 'metjk kdanf a.di nalf a f', 'this-the-link'),
(2, 'i See dead peoPle', 'i see bla bla bla blab bla', 'i-see-dead-people'),
(6, '', '', ''),
(7, 'çğü._eğş08ÀØñ', '', 'cgu-_egs08aqn-1'),
(5, 'çğü._eğş08ÀØñ', '', 'cgu-_egs08aqn-2');

-- --------------------------------------------------------

--
-- Tablo yapısı: `comments`
--

CREATE TABLE `comments` (
  `cm_article_id` int(11) NOT NULL,
  `cm_comment` text collate latin1_general_ci NOT NULL,
  KEY `cm_article_id` (`cm_article_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Tablo döküm verisi `comments`
--

INSERT INTO `comments` (`cm_article_id`, `cm_comment`) VALUES
(1, 'blablabla test comment.... 1 '),
(1, 'HELLo THis Is test COmment .... 2 ');
