CREATE TABLE EXISTS users (
  id INT( 11 ) NOT NULL AUTO_INCREMENT ,
  username VARCHAR( 20 ) NOT NULL ,
  password VARCHAR( 64 ) NOT NULL ,
  email varchar( 128 ) NOT NULL ,
  activation_key varchar( 64 ) NOT NULL DEFAULT '' ,
  superuser INT( 1 ) NOT NULL DEFAULT '0' ,
  status INT( 1 ) NOT NULL DEFAULT '0' ,
  failed_logins INT( 1 ) NOT NULL DEFAULT 0 ,
  last_visit DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00.000000' ,
  created_by_id INT( 11 ) NOT NULL DEFAULT 1  ,
  created_on DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00.000000' ,
  updated_by_id INT( 11 )NOT NULL DEFAULT 1 ,
  updated_on DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00.000000' ,
  PRIMARY KEY id (id) ,
  UNIQUE KEY username (username) ,
  UNIQUE KEY email (email) ,
  KEY status (status) ,
  KEY superuser (superuser)
) ENGINE = InnoDB  DEFAULT CHARSET = utf8 AUTO_INCREMENT = 3 ;

INSERT INTO users (id, username, password, email, activation_key, superuser, status, created_on, updated_on)
VALUES (1,
        'sysadmin' ,
        '$2a$13$XbvEz28oJHUt8CMd7ExY3ONPSPMdub5gG7/J09jvYhxQJ4kjpp0cq' ,
        'webmaster@rinkfinder.com' ,
        '$2a$13$ZgDFkwtY9f57GeMbbV35Sui3umePC8Q2qLprpfuNFHLStRc3yuY.y' ,
        1 ,
        1 ,
        NOW() ,
        NOW() ) ;

CREATE TABLE profiles (
  user_id INT( 11 ) NOT NULL ,
  first_name VARCHAR( 80 ) NOT NULL ,
  last_name VARCHAR( 80 ) NOT NULL ,
  address_line1 VARCHAR( 80 ) NOT NULL DEFAULT '' ,
  address_line2 VARCHAR( 80 ) NOT NULL DEFAULT '' ,
  city VARCHAR( 80 ) NOT NULL DEFAULT '' ,
  state VARCHAR( 2 ) NOT NULL DEFAULT '' ,
  zip VARCHAR( 10 ) NOT NULL DEFAULT '' ,
  phone VARCHAR( 10 ) NOT NULL DEFAULT '' ,
  ext VARCHAR( 10 ) NOT NULL DEFAULT '' ,
  birthday DATE NOT NULL DEFAULT '0000-00-00' ,
  created_by_id INT( 11 ) NOT NULL DEFAULT 1 ,
  created_on DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00.000000' ,
  updated_by_id INT( 11 ) NOT NULL DEFAULT 1 ,
  updated_on DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00.000000' ,
  PRIMARY KEY user_id ( user_id ) ,
) ENGINE = INNODB DEFAULT CHARSET=utf8 ;

INSERT INTO profiles (user_id, first_name, last_name, created_on, updated_on)
VALUES ((SELECT users.id FROM users WHERE users.username = 'sysadmin') ,
        'Administrator' ,
        'Administrator' ,
        NOW() ,
        NOW() );

CREATE TABLE profile_fields (
  id INT( 11 ) NOT NULL AUTO_INCREMENT,
  varname VARCHAR( 50 ) NOT NULL,
  title VARCHAR( 255 ) NOT NULL,
  field_type VARCHAR( 50 ) NOT NULL,
  field_size INT( 4 ) NOT NULL DEFAULT '0',
  field_size_min INT( 3 ) NOT NULL DEFAULT '0',
  required INT( 1 ) NOT NULL DEFAULT '0',
  `match` VARCHAR( 255 ) NOT NULL DEFAULT '',
  range VARCHAR( 255 ) NOT NULL DEFAULT '',
  error_message VARCHAR( 255 ) NOT NULL DEFAULT '',
  other_validator VARCHAR( 5000 ) NOT NULL DEFAULT '',
  `default` VARCHAR( 255 ) NOT NULL DEFAULT '',
  widget VARCHAR( 255 ) NOT NULL DEFAULT '',
  widgetparams VARCHAR( 5000 ) NOT NULL DEFAULT '',
  position INT( 3 ) NOT NULL DEFAULT '0',
  visible INT( 1 ) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY varname (varname, widget, visible)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 4 ;

INSERT INTO profile_fields (id, varname, title, field_type, field_size, field_size_min, required,
        `match`, range, error_message, other_validator, `default`, widget, widgetparams, position, visible)
VALUES
(1, 'first_name', 'First Name', 'VARCHAR', 80, 3, 1, '', '', 'Incorrect First Name (length between 3 and 80 characters).', '', '', '', '', 0, 3),
(2, 'last_name', 'Last Name', 'VARCHAR', 80, 3, 1, '', '', 'Incorrect Last Name (length between 3 and 80 characters).', '', '', '', '', 1, 1),
(3, 'address_line1', 'Address Line 1', 'VARCHAR', 80, 3, 1, '', '', 'Incorrect Address (length between 3 and 80 characters).', '', '', '', '', 2, 1),
(4, 'address_line2', 'Address Line 2', 'VARCHAR', 80, 0, 2, '', '', 'Incorrect Address (length between 0 and 80 characters).', '', '', '', '', 3, 1),
(5, 'city', 'City', 'VARCHAR', 80, 3, 1, '', '', 'Incorrect City (length between 3 and 80 characters).', '', '', '', '', 4, 1),
(6, 'state', 'State', 'VARCHAR', 2, 2, 1, '', '', 'Incorrect State (length 2 character abbreviation).', '', '', '', '', 5, 1),
(7, 'zip', 'Zip Code', 'VARCHAR', 5, 5, 1, '', '', 'Incorrect Zip Code (length 5 digit zip code).', '', '', '', '', 6, 1),
(8, 'phone', 'Phone Number', 'VARCHAR', 10, 10, 2, '', '', 'Incorrect Phone Number (length 10 digit phone number).', '', '', '', '', 7, 1),
(9, 'ext', 'Extension', 'VARCHAR', 10, 0, 2, '', '', 'Incorrect Extension (length between 0 and 10 characters).', '', '', '', '', 8, 1),
(10, 'birthday', 'Birthday', 'DATE', 0, 0, 2, '', '', '', '', '0000-00-00', 'UWjuidate', '{"ui-theme":"redmond"}', 9, 1);
