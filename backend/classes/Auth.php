<?php
class Auth
{
    public static function user(): ?array {
        if (empty($_SESSION['user_id'])) return null;
        return User::find((int)$_SESSION['user_id']);
    }

    public static function check(): bool {
        return self::user() !== null;
    }

    public static function isAdmin(): bool {
        $u = self::user();
        return $u && $u['role'] === 'admin';
    }

    public static function login(int $id): void {
        $_SESSION['user_id'] = $id;
    }

    public static function logout(): void {
        unset($_SESSION['user_id']);
        session_regenerate_id(true);
    }

    public static function requireUser(): array {
        $u = self::user();
        if (!$u) redirect('/gruz/frontend/login.php');
        return $u;
    }

    public static function requireAdmin(): array {
        $u = self::requireUser();
        if ($u['role'] !== 'admin') redirect('/gruz/frontend/applications.php');
        return $u;
    }
}