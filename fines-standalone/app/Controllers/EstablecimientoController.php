<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
use FinesApp\Core\Response;
use FinesApp\Core\Session;
use FinesApp\Repositories\EstablecimientoRepository;

final class EstablecimientoController extends Controller
{
    public function edit(Request $request): void
    {
        $this->requireLogin();
        $user = $this->auth->user();
        $establecimiento = $user === null ? null : (new EstablecimientoRepository($this->pdo))->byUser((int) $user['id']);

        $this->view->render('establecimiento/edit', [
            'title' => 'Establecimiento',
            'establecimiento' => $establecimiento,
            'notice' => flash('notice'),
            'error' => flash('error'),
        ]);
    }

    public function update(Request $request): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $user = $this->auth->user();
        if ($user === null) {
            Response::redirect(url('/login'));
        }

        $repository = new EstablecimientoRepository($this->pdo);
        $current = $repository->byUser((int) $user['id']);
        $baseId = $current === null ? 'nuevo' : (string) $current['id'];

        try {
            $data = [
                'nombre' => $this->required($request, 'nombre'),
                'cue' => $request->input('cue', '') ?? '',
                'direccion' => $request->input('direccion', '') ?? '',
                'logo_path' => $this->storeImage($request->file('logo'), $baseId, 'logo'),
            ];
            $data['firma_director_path'] = $this->storeImage($request->file('firma_director'), $baseId, 'firma_director');
            $data['sello_oval_path'] = $this->storeImage($request->file('sello_oval'), $baseId, 'sello_oval');
            $id = $repository->saveForUser((int) $user['id'], $data);

            if ($baseId === 'nuevo' && ($data['logo_path'] !== null || $data['firma_director_path'] !== null || $data['sello_oval_path'] !== null)) {
                $fixed = [];
                if ($data['logo_path'] !== null) {
                    $fixed['logo_path'] = $this->moveNewAsset($data['logo_path'], $id);
                }
                if ($data['firma_director_path'] !== null) {
                    $fixed['firma_director_path'] = $this->moveNewAsset($data['firma_director_path'], $id);
                }
                if ($data['sello_oval_path'] !== null) {
                    $fixed['sello_oval_path'] = $this->moveNewAsset($data['sello_oval_path'], $id);
                }
                $repository->saveForUser((int) $user['id'], array_merge($data, $fixed));
            }

            Session::flash('notice', 'Establecimiento guardado.');
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url('/establecimiento'));
    }

    private function required(Request $request, string $key): string
    {
        $value = $request->input($key);
        if ($value === null || $value === '') {
            throw new \InvalidArgumentException("El campo {$key} es obligatorio.");
        }

        return $value;
    }

    private function storeImage(?array $file, string $establecimientoId, string $kind): ?string
    {
        if ($file === null) {
            return null;
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('No se pudo subir el archivo.');
        }
        if ($file['size'] > 2 * 1024 * 1024) {
            throw new \RuntimeException('La imagen no puede superar 2 MB.');
        }

        $mime = mime_content_type($file['tmp_name']) ?: '';
        $extensions = $this->imageExtensions();
        if (!isset($extensions[$mime])) {
            throw new \RuntimeException($this->imageUploadMessage());
        }

        $relativeDirectory = '_assets/establecimientos/' . $establecimientoId;
        $directory = $this->constanciasStorageRoot() . DIRECTORY_SEPARATOR . $relativeDirectory;
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $relativePath = $relativeDirectory . '/' . $kind . '.' . $extensions[$mime];
        $target = $this->constanciasStorageRoot() . DIRECTORY_SEPARATOR . $relativePath;
        if (!move_uploaded_file($file['tmp_name'], $target)) {
            throw new \RuntimeException('No se pudo guardar la imagen.');
        }

        return $relativePath;
    }

    private function moveNewAsset(string $relativePath, int $establecimientoId): string
    {
        $newPath = str_replace('_assets/establecimientos/nuevo/', '_assets/establecimientos/' . $establecimientoId . '/', $relativePath);
        if ($newPath === $relativePath) {
            return $relativePath;
        }

        $from = $this->constanciasStorageRoot() . DIRECTORY_SEPARATOR . $relativePath;
        $to = $this->constanciasStorageRoot() . DIRECTORY_SEPARATOR . $newPath;
        if (!is_dir(dirname($to))) {
            mkdir(dirname($to), 0775, true);
        }
        if (is_file($from)) {
            rename($from, $to);
        }

        return $newPath;
    }

    private function constanciasStorageRoot(): string
    {
        $path = (string) ($_ENV['CONSTANCIAS_STORAGE_PATH'] ?? '');
        if ($path !== '') {
            return rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
        }

        return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'constancias';
    }

    private function imageExtensions(): array
    {
        if (extension_loaded('gd') || extension_loaded('imagick')) {
            return ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/webp' => 'webp'];
        }

        return ['image/jpeg' => 'jpg'];
    }

    private function imageUploadMessage(): string
    {
        if (extension_loaded('gd') || extension_loaded('imagick')) {
            return 'La imagen debe ser PNG, JPG o WEBP.';
        }

        return 'Este PHP no tiene GD ni Imagick activos. Subi la imagen en JPG, o habilita GD/Imagick para usar PNG con transparencia.';
    }
}
