<?php

abstract class ModelePDO {
    protected static $pdo = null;

    public static function getPDO() {
        if (self::$pdo === null) {
            require_once dirname(__FILE__) . '/../../configs/mysql_config.class.php';
            self::$pdo = new PDO(
                'mysql:host=' . MysqlConfig::SERVEUR . ';dbname=' . MysqlConfig::BASE . ';charset=utf8',
                MysqlConfig::UTILISATEUR,
                MysqlConfig::MOT_DE_PASSE,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
                ]
            );
        }
        return self::$pdo;
    }
}
