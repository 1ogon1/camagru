<?php

const SQL_CREATE_TABLE_LP = '
    CREATE TABLE IF NOT EXISTS log_pas (
      id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
      login VARCHAR(255) NOT NULL,
      password VARCHAR(255) NOT NULL,
      active INT(10) NOT NULL
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

const SQL_GET_LOGIN = '
    SELECT * FROM log_pas
';

const SQL_GET_ACTIVE = '
    SELECT * FROM activate
';

const SQL_CREATE_USER = '
    INSERT INTO log_pas (login, password, active) VALUES (?, ?, ?)
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
    SELECT path FROM images
';

?>