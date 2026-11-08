<?php
class User
{
    public static function find(int $id): ?array {
        return Database::fetchOne('SELECT * FROM users WHERE id = ?', [$id]);
    }

    public static function findByLogin(string $login): ?array {
        return Database::fetchOne('SELECT * FROM users WHERE login = ?', [$login]);
    }

    public static function create(array $d): int {
        Database::exec(
            'INSERT INTO users (login, password_hash, full_name, phone, email, role)
             VALUES (?, ?, ?, ?, ?, ?)',
            [
                $d['login'],
                password_hash($d['password'], PASSWORD_DEFAULT),
                $d['full_name'],
                $d['phone'],
                $d['email'],
                'user',
            ]
        );
        return Database::lastId();
    }
}