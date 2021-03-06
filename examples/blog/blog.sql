
DROP TABLE IF EXISTS `blog_tags2posts`;
DROP TABLE IF EXISTS `blog_tags`;
DROP TABLE IF EXISTS `blog_categories2posts`;
DROP TABLE IF EXISTS `blog_categories`;
DROP TABLE IF EXISTS `blog_comments`;
DROP TABLE IF EXISTS `blog_posts`;
DROP TABLE IF EXISTS `blog_users`;

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
--

CREATE TABLE `blog_users` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`firstname` varchar(255) NOT NULL,
	`lastname` varchar(255) NOT NULL,
	`username` varchar(255) NOT NULL,
	`password` varchar(255) NOT NULL,
	`email` varchar(255) NOT NULL,
	`active` char(1) NOT NULL DEFAULT '0',
	`access` varchar(255),
	`activationkey` varchar(32) NOT NULL,
	`resetkey` varchar(32) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `users`
-- 

INSERT INTO `blog_users` VALUES (1, 'matt', '', 'matt', '$2y$07$4FFaGBXJa01VYHalkDSF2.Fu4BJVsMnGSRRDfmmZ.3v9ez1e5KDb6', 'matt@mail.com', '1', 'post|admin', '', '');
INSERT INTO `blog_users` VALUES (2, 'chris', '', 'chris', '$2y$07$PRrR/RVRvN7f7w8XC4tCr.NkeArt.a2ZzpsmBupeXPyKf9I/rMi1S', 'chris@mail.com', '1', 'post|admin', '', '');
INSERT INTO `blog_users` VALUES (3, 'jonah', '', 'jonah', '$2y$07$PRrR/RVRvN7f7w8XC4tCr.NkeArt.a2ZzpsmBupeXPyKf9I/rMi1S', 'jonah@mail.com', '1', 'post|admin', '', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `posts`
--

CREATE TABLE `blog_posts` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`post_date` datetime NOT NULL default '0000-00-00 00:00:00',
	`permalink` varchar(255) NOT NULL,
	`title` varchar(255) NOT NULL,
	`excerpt` varchar(255) NOT NULL,
	`content` text NOT NULL,
	`comments_allowed` TINYINT UNSIGNED NOT NULL,
    `post_type` varchar(20) NOT NULL default 'post',
	`users_id` INT(10) UNSIGNED NOT NULL,
	`active` char(1) NOT NULL DEFAULT '0',
	PRIMARY KEY  (`id`),
	FOREIGN KEY (`users_id`) REFERENCES `blog_users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `posts`
-- 

INSERT INTO `blog_posts` VALUES (1, '2010-01-24 00:00:00', 'my-first-post', 'My first post', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent commodo convallis lectus, quis condimentum neque pretium in.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent commodo convallis lectus, quis condimentum neque pretium in. Aliquam nulla nisi, aliquam sed lacinia nec, iaculis eu est. Quisque tristique pellentesque iaculis. Sed ut nulla et elit pharetra aliquam ultricies posuere nulla. Praesent sed tristique mauris. Phasellus venenatis sollicitudin accumsan. Aenean quis ante libero. Nulla nec consequat erat. In tincidunt mattis lectus, consequat pretium enim volutpat sed. Nulla pellentesque dapibus lectus sed scelerisque. ', 0, 'post', 1, '1');
INSERT INTO `blog_posts` VALUES (2, '2010-01-21 00:00:00', 'the-second-post', 'The second post', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent commodo convallis lectus, quis condimentum neque pretium in.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent commodo convallis lectus, quis condimentum neque pretium in. Aliquam nulla nisi, aliquam sed lacinia nec, iaculis eu est. Quisque tristique pellentesque iaculis. Sed ut nulla et elit pharetra aliquam ultricies posuere nulla. Praesent sed tristique mauris. Phasellus venenatis sollicitudin accumsan. Aenean quis ante libero. Nulla nec consequat erat. In tincidunt mattis lectus, consequat pretium enim volutpat sed. Nulla pellentesque dapibus lectus sed scelerisque. ', 1, 'post', 2, '1');



-- --------------------------------------------------------

-- 
-- Table structure for table `comments`
--

CREATE TABLE `blog_comments` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`author` VARCHAR(255) NOT NULL,
	`author_email` VARCHAR(255) NOT NULL,
	`author_url` VARCHAR(255) NOT NULL,
	`users_id` INT(10) UNSIGNED NOT NULL,
	`comment_date` datetime NOT NULL default '0000-00-00 00:00:00',
	`comment` text NOT NULL,
	`approved` CHAR(1) NOT NULL default '1',
	`posts_id` INT UNSIGNED NOT NULL,
	PRIMARY KEY  (`id`),
	FOREIGN KEY (`posts_id`) REFERENCES `blog_posts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `comments`
--

INSERT INTO `blog_comments` 
(`id`,`author`,`author_email`,`author_url`,`users_id`,`comment_date`,`comment`,`approved` ,`posts_id`)
VALUES 
(NULL , 'Matt', 'matt@mail.com', 'mysite.com', 0, '2010-02-02 00:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent commodo convallis lectus, quis condimentum neque pretium in.', '1', '1'),
(NULL , 'Mike', 'mike@mail.com', 'mikewebsite.com', 0, '2010-02-04 00:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent commodo convallis lectus, quis condimentum neque pretium in.', '1', '1');

-- --------------------------------------------------------

-- 
-- Table structure for table `categories`
--

CREATE TABLE `blog_categories` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	`parent` INT UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `categories`
-- 

INSERT INTO `blog_categories` VALUES (1, 'php', 0);
INSERT INTO `blog_categories` VALUES (2, 'mysql', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `categories2posts`
--

CREATE TABLE `blog_categories2posts` (
	`categories_id` INT(10) UNSIGNED NOT NULL,
	`posts_id` INT(10) UNSIGNED NOT NULL,   
	PRIMARY KEY (`categories_id`, `posts_id`),  
	FOREIGN KEY (`categories_id`) REFERENCES `blog_categories` (`id`), 
	FOREIGN KEY (`posts_id`) REFERENCES `blog_posts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `categories2posts`
--


-- --------------------------------------------------------

-- 
-- Table structure for table `tags`
--

CREATE TABLE `blog_tags` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `tags`
-- 

INSERT INTO `blog_tags` VALUES (1, 'php');
INSERT INTO `blog_tags` VALUES (2, 'sql');

-- --------------------------------------------------------

-- 
-- Table structure for table `tags2posts`
--

CREATE TABLE `blog_tags2posts` (
	`tags_id` INT(10) UNSIGNED NOT NULL,
	`posts_id` INT(10) UNSIGNED NOT NULL,   
	PRIMARY KEY (`tags_id`, `posts_id`),  
	FOREIGN KEY (`tags_id`) REFERENCES `blog_tags` (`id`), 
	FOREIGN KEY (`posts_id`) REFERENCES `blog_posts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `tags2posts`
--

