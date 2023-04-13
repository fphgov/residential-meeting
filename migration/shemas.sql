CREATE TABLE IF NOT EXISTS `log_audit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL,
  `priority` int(11) NOT NULL,
  `priorityName` varchar(45) DEFAULT '',
  `message` longtext NOT NULL,
  `extra` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `log_error` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL,
  `priority` int(11) NOT NULL,
  `priorityName` varchar(45) DEFAULT '',
  `message` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
