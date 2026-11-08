<?php
class Review
{
    public static function create(int $appId, int $rating, string $text): void {
        Database::exec(
            'INSERT INTO reviews (application_id, rating, text) VALUES (?, ?, ?)',
            [$appId, $rating, $text]
        );
    }

    public static function existsFor(int $appId): bool {
        return Database::fetchOne('SELECT id FROM reviews WHERE application_id = ?', [$appId]) !== null;
    }
}