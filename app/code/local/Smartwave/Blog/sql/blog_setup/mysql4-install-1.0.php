<?php

$installer = $this;

$installer->startSetup();
try {
    $installer->run("

CREATE TABLE IF NOT EXISTS {$this->getTable('blog/blog')} (
  `post_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `post_content` text NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `identifier` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `update_user` varchar(255) NOT NULL DEFAULT '',
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `comments` tinyint(11) NOT NULL,
  `tags` text NOT NULL,
  `short_content` text NOT NULL,
  `banner_content` text NOT NULL,
  PRIMARY KEY (`post_id`),
  UNIQUE KEY `identifier` (`identifier`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

INSERT INTO {$this->getTable('blog/blog')} (`post_id`,`title`,`post_content`,`status`,`image`,`banner`,`created_time`,`update_time`,`identifier`,`user`,`update_user`,`meta_keywords`,`meta_description`,`comments`,`tags`,`short_content`,`banner_content`) values 

(1, 'Suspendisse Eros Ligula', '<p>Donec porta risus at felis facilisis rhoncus. Nam interdum pretium lorem, ac rutrum ligula aliquet sit amet. Vivamus sagittis volutpat lectus a feugiat. Aliquam erat volutpat. Phasellus id tempor tortor.</p>', 1, 'wysiwyg/smartwave/blog/Blog-1-thumb.jpg', 'wysiwyg/smartwave/blog/Blog-1-banner.jpg', '2014-05-12 07:19:00', '2014-06-06 07:33:39', 'blog-one', 'kerry jhon', 'kerry jhon', '', '', 0, 'close', '<p>Donec porta risus at felis facilisis rhoncus. Nam interdum pretium lorem, ac rutrum ligula aliquet sit amet. Vivamus sagittis volutpat lectus a feugiat.&nbsp;Aliquam erat volutpat.</p>', '<img src=\"{{media url=\"wysiwyg/Blog-1-banner-content.jpg\"}}\"  alt=\"\" />'),
(2, 'PRAESENT ET VULPUTATE TORTOR', '<p>Sed viverra egestas nibh, non posuere dui convallis vitae. Vestibulum scelerisque massa venenatis, cursus odio at, tempor lacus. Nam tellus mauris, hendrerit et lacus sit amet, blandit commodo lectus.</p>', 1, 'wysiwyg/smartwave/blog/Blog-2-thumb.jpg', 'wysiwyg/smartwave/blog/Blog-2-banner.jpg', '2014-05-12 08:52:01', '2014-06-06 07:32:15', 'blog-two', 'Henry', 'kerry jhon', '', '', 0, 'Food,Fresh News', '<p>Sed viverra egestas nibh, non posuere dui convallis vitae. Vestibulum scelerisque massa venenatis, cursus odio at, tempor lacus. Nam tellus mauris, hendrerit et lacus sit amet, blandit commodo lectus.</p>', '<img src=\"{{media url=\"wysiwyg/Blog-2-banner-content.jpg\"}}\"  alt=\"\" />'),
(3, 'Etiam nisl odio, pharetra', '<p>Integer rhoncus gravida mauris, quis rutrum arcu. Nullam et vehicula arcu. Phasellus ultricies eget neque a malesuada. Sed eleifend mollis semper. Praesent odio purus, auctor eget justo quis, pretium volutpat dolor.</p>', 1, 'wysiwyg/smartwave/blog/Blog-3-thumb.jpg', 'wysiwyg/smartwave/blog/Blog-3-banner.jpg', '2014-05-12 08:53:54', '2014-06-06 07:31:40', 'blog-three', 'Maria', 'kerry jhon', '', '', 0, '', '<p>Integer rhoncus gravida mauris, quis rutrum arcu. Nullam et vehicula arcu. Phasellus ultricies eget neque a malesuada. Sed eleifend mollis semper.</p>', '<img src=\"{{media url=\"wysiwyg/Blog-3-banner-content.jpg\"}}\"  alt=\"\" />'),
(4, 'Etiam laoreet orci justo', '<p>If you&rsquo;re keen to plan a trip around the world to visit special places of rare beauty, the car can be a good choice. It can also be a very challenging experience depending on where you go and how much of the &ldquo;world&rdquo; you intend to see at once.</p>', 1, 'wysiwyg/smartwave/blog/blog-4-thumb.jpg', 'wysiwyg/smartwave/blog/blog-4-banner.jpg', '2014-05-12 08:55:25', '2014-06-06 07:31:17', 'blog-four', 'Karl', 'kerry jhon', '', '', 0, 'Fresh News,Food,Fashion Life', '<p>If you&rsquo;re keen to plan a trip around the world to visit special places of rare beauty, the car can be a good choice.</p>', '<img src=\"{{media url=\"wysiwyg/Blog-4-banner-content.jpg\"}}\"  alt=\"\" />'),
(5, 'Get in to your car and see the world!', '<p>Define the itinerary. You will need to draw up an itinerary dependent on roads. Since roads criss-cross much of the world it shouldn&rsquo;t be too hard to begin with but keep in mind the following challenges:</p>', 1, 'wysiwyg/smartwave/blog/Blog-5-thumb.jpg', 'wysiwyg/smartwave/blog/Blog-5-banner.jpg', '2014-05-12 08:56:50', '2014-06-06 07:30:56', 'blog-five', 'Marks', 'kerry jhon', '', '', 0, 'Art New,Breaking News', '<p>If you&rsquo;re keen to plan a trip around the world to visit special places of rare beauty, the car can be a good choice. It can also be a very challenging experience depending on where you go and how much of the &ldquo;world&rdquo; you intend to see at once.</p>', '<img src=\"{{media url=\"wysiwyg/Blog-5-banner-content.jpg\"}}\"  alt=\"\" />'),
(6, 'Would you like a cup of coffee', '<p>Hippocrates said, &ldquo;Let food be thy medicine and medicine be thy food.&rdquo; Just about every nutrition-related professional I know has that quote displayed somewhere in their office.</p>', 1, 'wysiwyg/smartwave/blog/Blog-6-thumb.jpg', 'wysiwyg/smartwave/blog/Blog-6-banner.jpg', '2014-03-12 03:03:21', '2014-06-06 07:34:07', 'blog-six', 'Martin', 'kerry jhon', '', '', 0, 'Car,World Car', '<p>Hippocrates said, &ldquo;Let food be thy medicine and medicine be thy food.&rdquo; Just about every nutrition-related professional I know has that quote displayed somewhere in their office.</p>', '<img src=\"{{media url=\"wysiwyg/Blog-6-banner-content.jpg\"}}\"  alt=\"\" />'),
(7, 'The Happiest day in your life', '<p>It is supposed to be the happiest day of your life, but 60 per cent of couples say they fell out with friends and family over their wedding plans.</p>', 1, 'wysiwyg/smartwave/blog/Blog-7-thumb.jpg', 'wysiwyg/smartwave/blog/Blog-7-banner.jpg', '2014-02-11 04:26:01', '2014-06-06 07:34:56', 'blog-seven', 'Robben', 'kerry jhon', '', '', 0, 'happy,good day', '<p>It is supposed to be the happiest day of your life, but 60 per cent of couples say they fell out with friends and family over their wedding plans.Pressure to keep relatives happy is the biggest cause of pre-matrimonial tension.</p>', '<img src=\"{{media url=\"wysiwyg/Blog-7-banner-content.jpg\"}}\"  alt=\"\" />'),
(8, 'Travelling', '<p>Modern life is impossible without travelling. Thousands of people travel every day either on business or for pleasure. They can travel by air, by rail, by sea or by road.</p>\r\n<p>Of course, travelling by air is the fastest and the most convenient way.</p>', 1, 'wysiwyg/smartwave/blog/Blog-8-thumb.jpg', 'wysiwyg/smartwave/blog/Blog-8-banner.jpg', '2014-06-05 03:32:14', '2014-06-06 07:29:37', 'blog-eight', 'Ronaldo', 'kerry jhon', '', '', 0, 'travelling,boat', '<p>Modern life is impossible without travelling. Thousands of people travel every day either on business or for pleasure. They can travel by air, by rail, by sea or by road.</p>', '<img src=\"{{media url=\"wysiwyg/Blog-8-banner-content.jpg\"}}\"  alt=\"\" />'),
(10, 'DO YOU WEAR JEANS?', '<p>Jeans are pants made from denim or dungaree cloth. Often the term &ldquo;jeans&rdquo; refers to a particular style of pants, called &ldquo;blue jeans&rdquo; and invented by Jacob Davis and Levi Strauss in 1873.</p>', 1, 'wysiwyg/smartwave/blog/Blog-10-thumb.jpg', 'wysiwyg/smartwave/blog/Blog-10-banner.jpg', '2014-06-05 03:36:44', '2014-06-06 07:28:29', 'blog-ten', 'kerry jhon', 'kerry jhon', '', '', 0, 'close', '<p>Jeans are pants made from denim or dungaree cloth. Often the term &ldquo;jeans&rdquo; refers to a particular style of pants, called &ldquo;blue jeans&rdquo; and invented by Jacob Davis and Levi Strauss in 1873.</p>', '<img src=\"{{media url=\"wysiwyg/Blog-10-banner-content.jpg\"}}\"  alt=\"\" />'),
(11, 'ARE YOU A GENTLEMAN?', '<p>In modern speech the term&nbsp;gentleman&nbsp;&nbsp;refers to any man of good, courteous conduct. It may also refer to all men collectively, as in indications of gender-separated facilities.</p>', 1, 'wysiwyg/smartwave/blog/Blog-11-thumb.jpg', 'wysiwyg/smartwave/blog/Blog-11-banner.jpg', '2014-06-05 03:38:20', '2014-06-06 07:26:47', 'blog-11', 'kerry jhon', 'kerry jhon', '', '', 0, '', '<p>In modern speech the term&nbsp;gentleman&nbsp;&nbsp;refers to any man of good, courteous conduct. It may also refer to all men collectively, as in indications of gender-separated facilities, or as a sign of the speaker&rsquo;s own courtesy when addressing others.In its original meaning.</p>', '<img src=\"{{media url=\"wysiwyg/Blog-11-banner-content.jpg\"}}\"  alt=\"\" />'),
(12, 'BEAUTY OF OUR PLANET', '<p>The Earth provides resources that are exploitable by humans for useful purposes. Some of these are&nbsp;non-renewable resources, such as&nbsp;mineral fuels, that are difficult to replenish on a short time scale.</p>\r\n<p>Large deposits of&nbsp;fossil fuels&nbsp;are obtained from the Earth&rsquo;s crust.</p>', 1, 'wysiwyg/smartwave/blog/Blog-12-thumb.jpg', 'wysiwyg/smartwave/blog/Blog-12-banner.jpg', '2014-06-04 01:40:14', '2014-06-06 07:30:29', 'blog-12', 'kerry jhon', 'kerry jhon', '', '', 0, '', '<p>The Earth provides resources that are exploitable by humans for useful purposes. Some of these are&nbsp;non-renewable resources, such as&nbsp;mineral fuels.</p>', '<img src=\"{{media url=\"wysiwyg/Blog-12-banner-content.jpg\"}}\"  alt=\"\" />'),
(13, 'THE SILENCE OF THE STREETS', '<p>It&rsquo;s the rare observation about urbanism which one won&rsquo;t find already made, or anticipated, somewhere in the writings of Jane Jacobs. It&rsquo;s no surprise, therefore.</p>', 1, 'wysiwyg/smartwave/blog/Blog-13-thumb.jpg', 'wysiwyg/smartwave/blog/Blog-13-banner.jpg', '2014-06-04 08:44:22', '2014-06-06 07:30:00', 'blog-13', 'kerry jhon', 'kerry jhon', '', '', 0, '', '<p>It&rsquo;s the rare observation about urbanism which one won&rsquo;t find already made, or anticipated, somewhere&nbsp;in the writings of Jane Jacobs.&nbsp; It&rsquo;s no surprise, therefore, to find that Jacobs was also one of the&nbsp;first writers to make a&nbsp;case for the functional benefits of narrow streets.</p>', '<img src=\"{{media url=\"wysiwyg/Blog-13-banner-content.jpg\"}}\"  alt=\"\" />'),
(9, 'Entertainment', '<p>Entertainment is a form of activity that holds the attention and interest of an&nbsp;audience, or gives pleasure and delight.</p>', 1, 'wysiwyg/smartwave/blog/Blog-9-thumb.jpg', 'wysiwyg/smartwave/blog/Blog-9-banner.jpg', '2014-06-05 03:35:03', '2014-06-06 07:29:14', 'blog-nine', 'kerry jhon', 'kerry jhon', '', '', 0, '', '<p>Entertainment&nbsp;is a form of activity that holds the attention and interest of an&nbsp;audience, or gives pleasure and delight. It can be an idea or a task.</p>', '<img src=\"{{media url=\"wysiwyg/Blog-9-banner-content.jpg\"}}\"  alt=\"\" />');

CREATE TABLE IF NOT EXISTS {$this->getTable('blog/cat')} (
  `cat_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `identifier` varchar(255) NOT NULL DEFAULT '',
  `sort_order` tinyint(6) NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO {$this->getTable('blog/cat')} (`cat_id`,`title`,`identifier`,`sort_order`,`meta_keywords`,`meta_description`) values (2,'All about clothing','all-about-clothing',0,'',''),(3,'Make-up & beauty','make-up-beauty',1,'',''),(4,'Accessories','accessories',2,'',''),(5,'Fashion trends','fashion-trends',3,'',''),(6,'Haircuts & hairstyles','haircuts-hairstyles',4,'','');

CREATE TABLE IF NOT EXISTS {$this->getTable('blog/cat_store')} (
  `cat_id` smallint(6) unsigned DEFAULT NULL,
  `store_id` smallint(6) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO {$this->getTable('blog/cat_store')} (`cat_id`,`store_id`) values (2,0),(3,0),(4,0),(5,0),(6,0);

CREATE TABLE IF NOT EXISTS {$this->getTable('blog/comment')} (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` smallint(11) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  `user` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO {$this->getTable('blog/comment')} (`comment_id`,`post_id`,`comment`,`status`,`created_time`,`user`,`email`) values (2,3,'Cgestas metus id nunc vestibulum dictum. Etiam dapibus nunc nec risus egestas vel bibendum eros vehicula. Suspendisse facilisisneque in augue feugiat tempor donec velit diam pharetra.',2,'2013-10-16 13:28:09','Elen Aliquam','elen@gmail.com'),(3,3,'Aliquam eu augue dolor, eget commodo lacus. Nullam diam lorem, pellentesque dignissim tempor id, interdum quis nisi. Duis tempor, mauris nec interdum molestie, elit erat porta dui, quis sagittis sapien ante nec nibh.',2,'2013-10-16 13:31:16','Martin Doe','martin@gmail.com');

CREATE TABLE IF NOT EXISTS {$this->getTable('blog/post_cat')} (
  `cat_id` smallint(6) unsigned DEFAULT NULL,
  `post_id` smallint(6) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO {$this->getTable('blog/post_cat')} (`cat_id`,`post_id`) values (2,3),(6,7),(5,6),(2,4),(5,5),(2,1),(4,2),(5,8),(4,9),(5,10),(2,11),(6,12),(5,13);

CREATE TABLE IF NOT EXISTS {$this->getTable('blog/store')} (
  `post_id` smallint(6) unsigned DEFAULT NULL,
  `store_id` smallint(6) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO {$this->getTable('blog/store')} (`post_id`,`store_id`) values (1,0),(2,0),(3,0),(4,0),(5,0),(6,0),(7,0),(8,0),(9,0),(10,0),(11,0),(12,0),(13,0);

CREATE TABLE IF NOT EXISTS  {$this->getTable('blog/tag')} (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  `tag_count` int(11) NOT NULL DEFAULT '0',
  `store_id` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tag` (`tag`,`tag_count`,`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8;

INSERT INTO {$this->getTable('blog/tag')} (`id`,`tag`,`tag_count`,`store_id`) values (12, 'Accessories', 0, 0),
(136, 'Art New', 1, 1),
(138, 'Art New', 1, 2),
(137, 'Art New', 1, 3),
(139, 'Art New', 1, 4),
(63, 'Bags', 0, 1),
(120, 'Bags', 0, 2),
(119, 'Bags', 0, 3),
(121, 'Bags', 0, 4),
(22, 'Blog', 0, 1),
(108, 'Blog', 0, 2),
(107, 'Blog', 0, 3),
(109, 'Blog', 0, 4),
(181, 'boat', 1, 1),
(183, 'boat', 1, 2),
(182, 'boat', 1, 3),
(185, 'boat', 1, 4),
(184, 'boat', 1, 5),
(140, 'Breaking News', 1, 1),
(142, 'Breaking News', 1, 2),
(141, 'Breaking News', 1, 3),
(143, 'Breaking News', 1, 4),
(156, 'Car', 1, 1),
(158, 'Car', 1, 2),
(157, 'Car', 1, 3),
(160, 'Car', 1, 4),
(159, 'Car', 1, 5),
(132, 'close', 2, 1),
(134, 'close', 2, 2),
(133, 'close', 2, 3),
(135, 'close', 2, 4),
(186, 'close', 2, 5),
(73, 'Clother', 1, 1),
(2, 'Clothing', 0, 1),
(105, 'Clothing', 0, 2),
(104, 'Clothing', 0, 3),
(106, 'Clothing', 0, 4),
(103, 'Dresses', 0, 1),
(129, 'Dresses', 0, 2),
(128, 'Dresses', 0, 3),
(130, 'Dresses', 0, 4),
(83, 'Fashio', 0, 1),
(126, 'Fashio', 0, 2),
(125, 'Fashio', 0, 3),
(127, 'Fashio', 0, 4),
(53, 'Fashion', 0, 1),
(117, 'Fashion', 0, 2),
(116, 'Fashion', 0, 3),
(118, 'Fashion', 0, 4),
(152, 'Fashion Life', 1, 1),
(154, 'Fashion Life', 1, 2),
(153, 'Fashion Life', 1, 3),
(155, 'Fashion Life', 1, 4),
(148, 'Food', 2, 0),
(150, 'Food', 2, 2),
(149, 'Food', 2, 3),
(151, 'Food', 2, 4),
(144, 'Fresh News', 2, 1),
(146, 'Fresh News', 2, 2),
(145, 'Fresh News', 2, 3),
(147, 'Fresh News', 2, 4),
(171, 'good day', 1, 1),
(173, 'good day', 1, 2),
(172, 'good day', 1, 3),
(175, 'good day', 1, 4),
(174, 'good day', 1, 5),
(166, 'happy', 1, 1),
(168, 'happy', 1, 2),
(167, 'happy', 1, 3),
(170, 'happy', 1, 4),
(169, 'happy', 1, 5),
(32, 'Photography', 0, 1),
(111, 'Photography', 0, 2),
(110, 'Photography', 0, 3),
(112, 'Photography', 0, 4),
(93, 'Shoes', 0, 1),
(123, 'Shoes', 0, 2),
(122, 'Shoes', 0, 3),
(124, 'Shoes', 0, 4),
(176, 'travelling', 1, 1),
(178, 'travelling', 1, 2),
(177, 'travelling', 1, 3),
(180, 'travelling', 1, 4),
(179, 'travelling', 1, 5),
(43, 'Women', 0, 1),
(114, 'Women', 0, 2),
(113, 'Women', 0, 3),
(115, 'Women', 0, 4),
(161, 'World Car', 1, 1),
(163, 'World Car', 1, 2),
(162, 'World Car', 1, 3),
(165, 'World Car', 1, 4),
(164, 'World Car', 1, 5);
");
} catch (Exception $e) {
    
}

$installer->endSetup();

