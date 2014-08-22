INSERT INTO `#__components` (`id`, `name`, `link`, `menuid`, `parent`, `admin_menu_link`, `admin_menu_alt`, `option`, `ordering`, `admin_menu_img`, `iscore`, `params`, `enabled`) VALUES
('', 'Custom Falsh', 'option=com_customflash', 0, 0, '', '', 'com_customflash', 0, '', 0, '', 1);

CREATE TABLE IF NOT EXISTS `#__customflash` (
  `id` int(10) NOT NULL auto_increment,
  `moviename` varchar(50) NOT NULL,

  `checkflashavailability` varchar(255),
  `file` varchar(255) NOT NULL,
  `width` int(10) unsigned NOT NULL DEFAULT '400',
  `height` int(10) unsigned NOT NULL DEFAULT '300',
  `quality` varchar(20) NOT NULL,
  `wmode` varchar(20) NOT NULL,
  `bgcolor` varchar(20) NOT NULL,
  `style` varchar(255) NOT NULL,
  `play` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `scale` varchar(20) NOT NULL,
  `alternativehtml` text NOT NULL,
  `alternativeimage` varchar(255) NOT NULL,
  `flashvars` varchar(1024) NOT NULL,
  `menu` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cssclass` varchar(100) NOT NULL,
  `loop` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `paramlist` text,

  PRIMARY KEY  (`id`),
  UNIQUE KEY `moviename` (`moviename`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;



