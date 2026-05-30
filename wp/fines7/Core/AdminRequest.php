<?php

namespace Fines7\Core;

class AdminRequest
{
    public static function requireCapability(string $capability): void
    {
        if (!current_user_can($capability)) {
            wp_die(esc_html__('No tienes permisos suficientes para realizar esta accion.', 'fines7'));
        }
    }
}
