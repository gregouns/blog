

CREATE TABLE `categories` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `id_parent` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `url` char(250) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `posts` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `title` char(255) NOT NULL,
  `url` char(255) NOT NULL,
  `description` tinytext NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `posts_cats` (
  `post_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `posts_tags` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `tags` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `url` char(255) NOT NULL,
  `tag` char(255) NOT NULL,
  `status` tinyint(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


ALTER TABLE `posts_cats`
  ADD UNIQUE KEY `post_id` (`post_id`,`cat_id`);


ALTER TABLE `posts_tags`
  ADD UNIQUE KEY `post_id` (`post_id`,`tag_id`);
