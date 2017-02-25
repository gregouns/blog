
CREATE TABLE `posts` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(500) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE `tags` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `tag` char(255) NOT NULL,
  `tag_url` char(255) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
