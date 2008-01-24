
CREATE TABLE `i_xna_category` (
  `cid` int(11) unsigned NOT NULL auto_increment,
  `cate_title` varchar(120) default '0',
  `cate_content` varchar(255) default NULL,
  `cate_sort` tinyint(1) default '0',
  PRIMARY KEY  (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

#
# Dumping data for table i_xna_category
#

INSERT INTO `i_xna_category` VALUES (1,'General','普通',0);
INSERT INTO `i_xna_category` VALUES (2,'Personal','私人',0);

#
# Table structure for table i_xna_comment
#


CREATE TABLE `i_xna_comment` (
  `cid` int(11) unsigned NOT NULL auto_increment,
  `news_id` int(11) NOT NULL default '0',
  `user_name` varchar(60) default NULL,
  `user_email` varchar(60) default NULL,
  `user_url` varchar(255) default NULL,
  `user_ip` varchar(15) NOT NULL default '',
  `user_ctime` int(11) NOT NULL default '0',
  `user_utime` int(11) NOT NULL default '0',
  `comm_title` varchar(120) default NULL,
  `comm_content` text NOT NULL,
  `user_karma` int(11) NOT NULL default '0',
  `user_approved` enum('0','1','spam') NOT NULL default '1',
  `user_agent` varchar(255) NOT NULL default '',
  `user_type` varchar(20) NOT NULL default '',
  `user_parent` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `support` int(11) default '0',
  `against` int(11) default '0',
  PRIMARY KEY  (`cid`),
  KEY `comment_approved` (`user_approved`),
  KEY `news_id` (`news_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

#
# Dumping data for table i_xna_comment
#

#
# Table structure for table i_xna_links
#

CREATE TABLE `i_xna_links` (
  `lid` int(10) unsigned NOT NULL auto_increment,
  `link_title` varchar(60) default NULL,
  `link_url` varchar(255) default NULL,
  `link_img` varchar(255) default NULL,
  KEY `lid` (`lid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

#
# Dumping data for table i_xna_links
#

INSERT INTO `i_xna_links` VALUES (1,'点燃灵感','http://www.fwcn.com','');
INSERT INTO `i_xna_links` VALUES (2,'Zxsv','http://zxsv.com',NULL);

#
# Table structure for table i_xna_news
#


CREATE TABLE `i_xna_news` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `site_id` int(11) NOT NULL default '0',
  `site_name` varchar(100) NOT NULL default '0',
  `news_title` varchar(100) NOT NULL default '0',
  `news_state` tinyint(1) NOT NULL default '0',
  `news_content` text,
  `news_url` varchar(255) default NULL,
  `news_url_sum` varchar(32) default NULL,
  `news_ctime` int(11) NOT NULL default '0',
  `news_vote` smallint(6) NOT NULL default '0',
  `news_count` smallint(6) NOT NULL default '0',
  `news_hits` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `news_url_sum` (`news_url_sum`),
  FULLTEXT KEY `title_index` (`news_title`),
  FULLTEXT KEY `content_index` (`news_content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

#
# Dumping data for table i_xna_news
#

#
# Table structure for table i_xna_site
#

CREATE TABLE `i_xna_site` (
  `sid` int(11) unsigned NOT NULL auto_increment,
  `site_title` varchar(60) default NULL,
  `site_url` varchar(255) default NULL,
  `site_icon` varchar(255) default NULL,
  `site_ctime` int(11) NOT NULL default '0',
  `site_utime` int(11) NOT NULL default '0',
  `site_audit` tinyint(1) NOT NULL default '0',
  `site_email` varchar(60) default NULL,
  `site_content` text,
  `rss_url` varchar(255) default NULL,
  `rss_feq` tinyint(1) NOT NULL default '30',
  `rss_cate` tinyint(1) NOT NULL default '0',
  `isupdate` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

#
# Dumping data for table i_xna_site
#

INSERT INTO `i_xna_site` VALUES (1,'剑气凌人','http://zxsv.com/','http://zxsv.com/favicon.ico',1167580800,1190117641,0,'','','http://zxsv.com/feed/',30,2,0);

#
# Table structure for table i_xna_tags
#

CREATE TABLE `i_xna_tags` (
  `tid` int(11) unsigned NOT NULL auto_increment,
  `tag_title` varchar(120) collate utf8_bin default '0',
  `news_id` varchar(255) collate utf8_bin default '0',
  PRIMARY KEY  (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=FIXED;

#
# Dumping data for table i_xna_tags
#


#
# Table structure for table i_xna_users
#

DROP TABLE IF EXISTS `i_xna_users`;
CREATE TABLE `i_xna_users` (
  `uid` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(60) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `question` varchar(255) NOT NULL default '',
  `answer` varchar(255) NOT NULL default '',
  `sex` tinyint(1) default '0',
  `email` varchar(60) default NULL,
  `lasttime` int(11) NOT NULL default '0',
  `lastip` varchar(15) NOT NULL default '0',
  `realname` varchar(255) default NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Dumping data for table i_xna_users
#

INSERT INTO `i_xna_users` VALUES (1,'admin','7fef6171469e80d32c0559f88b377245','','',0,'spvrk@spvrk.com',1190113600,'192.168.0.1','Administrator');
