CREATE TABLE IF NOT EXISTS `#__je_faq` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `questions` varchar(256) character set utf8 NOT NULL,
  `answers` longtext character set utf8 NOT NULL,
  `ordering` int(11) NOT NULL default '1',
  `state` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  ;

