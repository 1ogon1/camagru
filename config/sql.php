<?php

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

?>

