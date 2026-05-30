<?php

namespace Fines7\Core;

class FormData
{
    public static function requiredText(string $key, string $message): string
    {
        $value = self::text($key);
        if ($value === null) {
            wp_die(esc_html($message));
        }

        return $value;
    }

    public static function text(string $key): ?string
    {
        $value = isset($_POST[$key]) ? sanitize_text_field(wp_unslash($_POST[$key])) : '';
        return $value === '' ? null : $value;
    }

    public static function textarea(string $key): ?string
    {
        $value = isset($_POST[$key]) ? sanitize_textarea_field(wp_unslash($_POST[$key])) : '';
        return $value === '' ? null : $value;
    }

    public static function email(string $key): ?string
    {
        $value = isset($_POST[$key]) ? sanitize_email(wp_unslash($_POST[$key])) : '';
        return $value === '' ? null : $value;
    }

    public static function int(string $key): ?int
    {
        $value = isset($_POST[$key]) ? sanitize_text_field(wp_unslash($_POST[$key])) : '';
        return $value === '' ? null : (int) $value;
    }

    public static function date(string $key): ?string
    {
        $value = isset($_POST[$key]) ? sanitize_text_field(wp_unslash($_POST[$key])) : '';
        if ($value === '') {
            return null;
        }

        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) === 1 ? $value : null;
    }

    public static function bool(string $key): int
    {
        return isset($_POST[$key]) ? 1 : 0;
    }
}
