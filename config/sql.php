<?php

const SQL_CREATE_DATABASE = '
	CREATE DATABASE IF NOT EXISTS camagru_db
';

const SQL_CREATE_TABLE_LP = '
    CREATE TABLE IF NOT EXISTS log_pas (
      id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
      login VARCHAR(255) NOT NULL,
      password VARCHAR(255) NOT NULL,
      email VARCHAR(255) NOT NULL,
      logo VARCHAR(255) NOT NULL,
      active INT(10) NOT NULL,
      admin INT(10) NOT NULL
      )
';

const SQL_CREATE_TABLE_IMG = '
    CREATE TABLE IF NOT EXISTS images (
      id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
      login VARCHAR(255) NOT NULL,
      path VARCHAR(255) NOT NULL,
      name VARCHAR(255) NOT NULL
      )
';

const SQL_CREATE_TABLE_ACT = '
    CREATE TABLE IF NOT EXISTS activate (
      id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
      login VARCHAR(255) NOT NULL,
      login_activate VARCHAR(255) NOT NULL
      )
';

const SQL_CREATE_TABLE_COMMENT = '
    CREATE TABLE IF NOT EXISTS comment (
      id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
      img VARCHAR (255) NOT NULL,
      login VARCHAR(255) NOT NULL,
      message VARCHAR (255) NOT NULL
    )
';

const SQL_CREATE_TABLE_LIKES = '
    CREATE TABLE IF NOT EXISTS likes (
      id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
      img VARCHAR (255) NOT NULL,
      login VARCHAR(255) NOT NULL
    )
';

const SQL_CREATE_TABLE_MASKS = '
    CREATE TABLE IF NOT EXISTS masks (
      id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
      path VARCHAR(255) NOT NULL,
      name VARCHAR(255) NOT NULL,
      type INT(10) NOT NULL
      )
';

const SQL_ADD_MASK_1 = "
	INSERT INTO masks (path, name, type) VALUES ('../img/beard.png', 'beard.png', 1)
";

const SQL_ADD_MASK_2 = "
	INSERT INTO masks (path, name, type) VALUES ('../img/beard2.png', 'beard2.png', 1)
";

const SQL_ADD_MASK_3 = "
	INSERT INTO masks (path, name, type) VALUES ('../img/hat.png', 'hat.png', 1)
";

const SQL_ADD_MASK_4 = "
	INSERT INTO masks (path, name, type) VALUES ('../img/snap.png', 'snap.png', 1)
";

const SQL_ADD_MASK_5 = "
	INSERT INTO masks (path, name, type) VALUES ('../img/sunglases.png', 'sunglases.png', 1)
";

const SQL_ADD_MASK_6 = "
	INSERT INTO masks (path, name, type) VALUES ('../img/Vintage.png', 'Vintage.png', 2)
";

const SQL_GET_LOGIN = '
    SELECT * FROM log_pas
';

const SQL_GET_ACTIVE = '
    SELECT * FROM activate
';

const SQL_CREATE_USER = '
    INSERT INTO log_pas (login, password, email, logo, active, admin) VALUES (?, ?, ?, ?, ?, ?)
';

const SQL_ACTIVATE_LINK = '
    INSERT INTO activate (login, login_activate) VALUES (?, ?)
';

const SQL_ACTIVATE_USER = '
    UPDATE log_pas SET active = :active WHERE login = :login
';

const SQL_DELETE_ACTIVE = '
    DELETE FROM activate WHERE login = ?
';

const SQL_GET_IMG_TABLE = '
    SELECT id FROM images WHERE name = ?
';

const SQL_ADD_IMAGE = '
    INSERT INTO images (login, path, name) VALUES (?, ?, ?)
';

const SQL_GET_ALL_IMG = '
    SELECT path, name FROM images ORDER BY id DESC
';

const SQL_GET_USER_IMG = '
    SELECT * FROM images WHERE login = ?  ORDER BY id DESC
';

const SQL_SET_USER_LOGO = '
    UPDATE log_pas SET path = :path WHERE login = :login
';

const SQL_ADD_COMMENT = '
    INSERT INTO comment (img, login, message) VALUES (?, ?, ?)
';

const SQL_GET_COMMENT = '
    SELECT * FROM comment WHERE img = ? ORDER BY id DESC
';

const SQL_GET_ALL_LIKES = '
    SELECT * FROM likes WHERE img = ?
';

const SQL_ADD_LIKE = '
    INSERT INTO likes (img, login) VALUES (?, ?)
';

const SQL_DELETE_LIKE = '
    DELETE FROM likes WHERE (id = ?)
';

const SQL_GET_USER_BY_LOGIN = '
    SELECT * FROM log_pas WHERE login = ?
';

const SQL_UPDATE_PASSWORD = '
    UPDATE log_pas SET password = :password WHERE login = :login
';

const SQL_CHANGE_PHOTO = '
    UPDATE log_pas SET logo = :logo WHERE login = :login
';

const SQL_DELETE_IMG = '
    DELETE FROM images WHERE name = ?
';

const SQL_DELETE_IMG_COMMENT = '
	DELETE FROM comment WHERE img = ?
';

const SQL_DELETE_IMG_LIKE = '
	DELETE FROM likes WHERE img = ?
';

const SQL_GET_IMG_BY_ID = '
	SELECT name FROM images WHERE id = ?
';

const SQL_CHANGE_USER = '
	UPDATE log_pas SET login = :login, email = :email, active = :active, admin = :admin WHERE id = :id
';

const SQL_CHANGE_USER_PW = '
	UPDATE log_pas SET login = :login, password = :password, email = :email, active = :active, admin = :admin WHERE id = :id
';

const SQL_DELETE_COMMENT = '
	DELETE FROM comment WHERE id = ?
';

const SQL_SELECT_ALL_MASKS = '
	SELECT * FROM masks
';

const SQL_ADD_MASKS = '
	INSERT INTO masks (path, name) VALUES (?, ?)
';

const SQL_DELETE_MASK = '
	DELETE FROM masks WHERE name = ?
';

//const SQL_GET_PATH_BY_ID = '
//	SELECT path FROM masks WHERE id = ?
//';

?>