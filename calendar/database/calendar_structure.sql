CREATE TABLE `tasks_types` (
  `id` int unsigned PRIMARY KEY AUTO_INCREMENT,
  `type` varchar(50) UNIQUE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
  `id` int unsigned PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `login` varchar(50) UNIQUE NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tasks` (
  `id` int unsigned PRIMARY KEY AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `status_id` int unsigned NOT NULL DEFAULT 1,
  `topic` varchar(255) NOT NULL,
  `type_id` int unsigned NOT NULL,
  `location` varchar(255) NOT NULL,
  `time` datetime NOT NULL,
  `duration_id` int unsigned NOT NULL,
  `comment` text DEFAULT null
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tasks_statuses` (
  `id` int unsigned PRIMARY KEY AUTO_INCREMENT,
  `status` varchar(50) UNIQUE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tasks_durations` (
  `id` int unsigned PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(50) UNIQUE NOT NULL,
  `seconds` int unsigned UNIQUE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tasks` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `tasks` ADD FOREIGN KEY (`status_id`) REFERENCES `tasks_statuses` (`id`);

ALTER TABLE `tasks` ADD FOREIGN KEY (`type_id`) REFERENCES `tasks_types` (`id`);

ALTER TABLE `tasks` ADD FOREIGN KEY (`duration_id`) REFERENCES `tasks_durations` (`id`);

INSERT INTO `tasks_durations`(name, seconds)
VALUES 
('5 minutes', 300),
('10 minutes', 600), 
('15 minutes', 900), 
('30 minutes', 1800,
('45 minutes', 2700), 
('1 hour', 3600), 
('1 hour and a half', 5400),
('2 hours', 7200), 
('3 hours', 10800), 
('4 hours', 14400), 
('5 hours', 18000), 
('6 hours', 21600);

INSERT INTO `tasks_statuses`(status)
VALUES
('In progress'),
('Expired'),
('Completed'),
('Cancelled');

INSERT INTO `tasks_types`(type)
VALUES
('Meeting'),
('Call'),
('Negotiations'),
('Business');
