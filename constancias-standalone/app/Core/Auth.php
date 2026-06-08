<?php

declare(strict_types=1);

namespace ConstanciasApp\Core;

use PDO;

final class Auth
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function attempt(string $email, string $password): bool
    {
        return false;
    }

    public function attemptGoogle(string $email): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, establecimiento_id, nombre, email, rol, activo FROM fines_app_users WHERE email = :email LIMIT 1'
        );
        $stmt->execute(['email' => strtolower($email)]);
        $user = $stmt->fetch();

        if (!$user || (int) $user['activo'] !== 1) {
            return false;
        }

        $this->loginUser($user);

        return true;
    }

    private function loginUser(array $user): void
    {
        session_regenerate_id(true);
        Session::put('user_id', (int) $user['id']);
        Session::put('user', [
            'id' => (int) $user['id'],
            'establecimiento_id' => $user['establecimiento_id'] === null ? null : (int) $user['establecimiento_id'],
            'nombre' => (string) $user['nombre'],
            'email' => (string) $user['email'],
            'rol' => (string) $user['rol'],
        ]);

        $this->pdo->prepare('UPDATE fines_app_users SET ultimo_login_en = NOW() WHERE id = :id')
            ->execute(['id' => (int) $user['id']]);
    }

    public function logout(): void
    {
        Session::forget('user_id');
        Session::forget('user');
        session_regenerate_id(true);
    }

    public function check(): bool
    {
        return is_int(Session::get('user_id'));
    }

    public function user(): ?array
    {
        $user = Session::get('user');
        return is_array($user) ? $user : null;
    }

    public function requireLogin(): void
    {
        if (!$this->check()) {
            Response::redirect(url('/login'));
        }
    }

    public function canEdit(): bool
    {
        $user = $this->user();
        return $user !== null && in_array($user['rol'], ['admin', 'operador'], true);
    }
}
