
--
-- Tablo yapýsý: `articles` please create a database named example_db !!
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
(1, 'çðü._eðþ08ÀØñ', 'blablabla blab lbal balb la', 'cgu-_egs08aqn'),
(2, 'i See dead peoPle', 'i see bla bla bla blab bla', 'i-see-dead-people'),
(6, '', '', ''),
(7, 'çðü._eðþ08ÀØñ', '', 'cgu-_egs08aqn-1'),
(5, 'çðü._eðþ08ÀØñ', '', 'cgu-_egs08aqn-2'),
(8, 'çðü._eðþ08ÀØñ', '', 'cgu-_egs08aqn-3'),
(9, 'çðü._eðþ08ÀØñ', '', 'cgu-_egs08aqn-4');
