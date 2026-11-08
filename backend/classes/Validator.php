<?php
class Validator
{
    public static function login(string $v): ?string {
        if (!preg_match('/^[A-Za-z0-9]{6,}$/', $v)) {
            return 'Логин: только латиница и цифры, минимум 6 символов';
        }
        return null;
    }

    public static function password(string $v): ?string {
        if (mb_strlen($v) < 6) return 'Пароль: минимум 6 символов';
        return null;
    }

    public static function fullName(string $v): ?string {
        if (!preg_match('/^[А-Яа-яЁё\s]+$/u', $v)) {
            return 'ФИО: только кириллица и пробелы';
        }
        return null;
    }

    public static function phone(string $v): ?string {
        if (!preg_match('/^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/', $v)) {
            return 'Телефон: формат +7(XXX)-XXX-XX-XX';
        }
        return null;
    }

    public static function email(string $v): ?string {
        if (!filter_var($v, FILTER_VALIDATE_EMAIL)) {
            return 'Некорректный email';
        }
        return null;
    }

    public static function weight(string $v): ?string {
        if (!ctype_digit($v)) return 'Вес: число от 1 до 20000';
        $n = (int)$v;
        if ($n < 1 || $n > 20000) return 'Вес: число от 1 до 20000';
        return null;
    }

    public static function date(string $v): ?string {
        $d = DateTime::createFromFormat('d.m.Y', $v);
        if (!$d || $d->format('d.m.Y') !== $v) {
            return 'Дата: формат ДД.ММ.ГГГГ';
        }
        $today = new DateTime('today');
        if ($d < $today) return 'Дата не может быть в прошлом';
        return null;
    }

    public static function time(string $v): ?string {
        if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $v)) {
            return 'Время: формат ЧЧ:ММ';
        }
        return null;
    }
}