<?php
class Application
{
    public const CARGO_TYPES = [
        'Хрупкое', 'Скоропортящееся', 'Требуется рефрижератор',
        'Животные', 'Жидкость', 'Мебель', 'Мусор',
    ];

    public static function create(int $userId, array $d): int {
        Database::exec(
            'INSERT INTO applications
             (user_id, transport_date, transport_time, weight_kg, dimensions,
              address_from, address_to, cargo_type, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, "Новая")',
            [
                $userId, $d['date'], $d['time'], $d['weight'], $d['dimensions'],
                $d['address_from'], $d['address_to'], $d['cargo_type'],
            ]
        );
        return Database::lastId();
    }

    public static function byUser(int $userId): array {
        return Database::fetchAll(
            'SELECT a.*, r.id AS review_id, r.rating, r.text AS review_text
             FROM applications a
             LEFT JOIN reviews r ON r.application_id = a.id
             WHERE a.user_id = ?
             ORDER BY a.created_at DESC',
            [$userId]
        );
    }

    public static function findAll(?string $status = null, ?string $cargo = null, string $sort = 'asc'): array {
        $sql = 'SELECT a.*, u.full_name, u.phone, u.email
                FROM applications a JOIN users u ON u.id = a.user_id WHERE 1=1';
        $params = [];
        if ($status) { $sql .= ' AND a.status = ?'; $params[] = $status; }
        if ($cargo)  { $sql .= ' AND a.cargo_type = ?'; $params[] = $cargo; }
        $sql .= $sort === 'desc' ? ' ORDER BY a.transport_date DESC, a.transport_time DESC'
                                 : ' ORDER BY a.transport_date ASC, a.transport_time ASC';
        return Database::fetchAll($sql, $params);
    }

    public static function find(int $id): ?array {
        return Database::fetchOne('SELECT * FROM applications WHERE id = ?', [$id]);
    }

    public static function updateStatus(int $id, string $status, ?string $reason = null): void {
        Database::exec(
            'UPDATE applications SET status = ?, cancel_reason = ? WHERE id = ?',
            [$status, $status === 'Отменена' ? $reason : null, $id]
        );
    }
}