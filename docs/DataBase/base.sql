CREATE TABLE `language` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `iso` varchar(5) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `template` varchar(40) DEFAULT NULL,
  `status` enum('draft','active','trash') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `content_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` smallint(5) unsigned NOT NULL,
  `content_id` int(10) unsigned NOT NULL,
  `url` text,
  `md5url` char(32) NOT NULL,
  `title` varchar(180) DEFAULT NULL,
  `body` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pk_content_fk` (`content_id`),
  KEY `pk_lang_content_fk` (`language_id`),
  CONSTRAINT `pk_content_fk` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `pk_lang_content_fk` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
