<?php

namespace Fines7\Core;

use Fines7\Pages\ComisionesPage;
use Fines7\Pages\AlumnoPage;
use Fines7\Pages\PersonasPage;

class Plugin
{
    public const MENU_SLUG = 'fines7';
    public const COMISIONES_SLUG = 'fines7-comisiones';
    public const PERSONAS_SLUG = 'fines7-personas';
    public const ALUMNO_SLUG = 'fines7-alumno';

    private static string $pluginFile;

    public static function init(string $pluginFile): void
    {
        self::$pluginFile = $pluginFile;

        add_action('admin_menu', [self::class, 'registerMenu']);
        add_action('admin_init', [AlumnoPage::class, 'maybeHandlePost']);
        add_action('admin_enqueue_scripts', [self::class, 'enqueueAdminAssets']);
    }

    public static function registerMenu(): void
    {
        add_menu_page(
            'Administracion Fines7',
            'Fines7',
            'edit_posts',
            self::MENU_SLUG,
            [ComisionesPage::class, 'render'],
            'dashicons-admin-generic',
            1
        );

        add_submenu_page(
            self::MENU_SLUG,
            'Buscar Personas',
            'Buscar Personas',
            'edit_posts',
            self::PERSONAS_SLUG,
            [PersonasPage::class, 'render']
        );

        add_submenu_page(
            self::MENU_SLUG,
            'Comisiones',
            'Comisiones',
            'edit_posts',
            self::COMISIONES_SLUG,
            [ComisionesPage::class, 'render']
        );

        add_submenu_page(
            null,
            'Detalle Alumno',
            'Detalle Alumno',
            'edit_posts',
            self::ALUMNO_SLUG,
            [AlumnoPage::class, 'render']
        );
    }

    public static function enqueueAdminAssets(string $hook): void
    {
        if (strpos($hook, self::MENU_SLUG) === false) {
            return;
        }

        wp_enqueue_style('dashicons');
        wp_enqueue_style(
            'fines7-admin',
            plugin_dir_url(self::$pluginFile) . 'assets/admin.css',
            [],
            '0.1.0'
        );
    }
}
