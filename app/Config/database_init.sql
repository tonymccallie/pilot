CREATE TABLE `roles` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `permissions` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `users` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `role_id` mediumint(9) NOT NULL,
  `email` varchar(100) NOT NULL,
  `passwd` varchar(100) NOT NULL,
  `created` datetime DEFAULT NULL,
  `verified` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

INSERT INTO  `app_install`.`roles` (
`id` ,
`name` ,
`permissions` ,
`created` ,
`modified`
)
VALUES (
NULL ,  'Guest',  '!*:*,CakeError:*,Pages:*,Users:login,Users:register,Users:recover', NOW( ) , NOW( )
), (
NULL ,  'User',  '*:*,!*:admin_*', NOW( ) , NOW( )
), (
NULL ,  'Admin',  '*:*', NOW( ) , NOW( )
);

CREATE TABLE `login_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(32) NOT NULL,
  `duration` varchar(32) NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `expires` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
