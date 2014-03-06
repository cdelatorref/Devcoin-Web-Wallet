

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(100) NOT NULL,
  `password` varchar(40) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `activation_code` varchar(25) DEFAULT NULL,
  `google2authcode` varchar(25) DEFAULT NULL,
  `activegoogle2auth` int(11) DEFAULT '0',
  `privileges` int(11) NOT NULL DEFAULT '0',
  `nickname` varchar(30) DEFAULT NULL,
  `personalmessage` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1$$

