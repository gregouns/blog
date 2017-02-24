

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `tag_post_id` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(500) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

