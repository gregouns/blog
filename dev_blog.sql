

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `tag_post_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `date` date NOT NULL,
  `description` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=INNODB CHARACTER SET utf8 COLLATE utf8_bin;

-- --------------------------------------------------------


CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `tag` varchar(255) COLLATE utf8_bin NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=INNODB CHARACTER SET utf8 COLLATE utf8_bin;
