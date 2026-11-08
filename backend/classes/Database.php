<?php
class Database
{
    private static ?PDO $pdo = null;
    private static array $cfg = [];

    public static function init(array $cfg): void {
        self::$cfg = $cfg;
    }

    public static function pdo(): PDO {
        if (self::$pdo === null) {
            $c = self::$cfg;
            $dsn = "mysql:host={$c['host']};dbname={$c['name']};charset={$c['charset']}";
            self::$pdo = new PDO($dsn, $c['user'], $c['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }
        return self::$pdo;
    }

    public static function fetchAll(string $sql, array $params = []): array {
        $st = self::pdo()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public static function fetchOne(string $sql, array $params = []): ?array {
        $st = self::pdo()->prepare($sql);
        $st->execute($params);
        $r = $st->fetch();
        return $r ?: null;
    }

    public static function exec(string $sql, array $params = []): void {
        $st = self::pdo()->prepare($sql);
        $st->execute($params);
    }

    public static function lastId(): int {
        return (int)self::pdo()->lastInsertId();
    }
}