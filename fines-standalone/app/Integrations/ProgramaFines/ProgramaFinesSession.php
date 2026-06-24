<?php

declare(strict_types=1);

namespace FinesApp\Integrations\ProgramaFines;

use FinesApp\Core\Session;

final class ProgramaFinesSession
{
    private const KEY = 'programafines_session_id';

    public function id(): ?string
    {
        $value = Session::get(self::KEY);
        return is_string($value) && $value !== '' ? $value : null;
    }

    public function connected(): bool
    {
        return $this->id() !== null;
    }

    public function connect(string $sessionId): void
    {
        Session::put(self::KEY, $sessionId);
    }

    public function disconnect(): void
    {
        Session::forget(self::KEY);
    }
}
