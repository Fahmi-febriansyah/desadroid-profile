<?php
namespace App\Controllers;

use App\Core\Controller;

class ErrorController extends Controller
{
    public function notFound(): void
    {
        $this->response->setStatusCode(404);
        $this->render('errors/404', [
            'pageTitle' => '404 — Halaman Tidak Ditemukan | Desadroid',
            'metaDescription' => 'Maaf, halaman yang Anda cari tidak dapat ditemukan atau mungkin telah dipindahkan.',
            'metaRobots' => 'noindex, follow'
        ]);
    }

    public function forbidden(): void
    {
        $this->response->setStatusCode(403);
        $this->render('errors/403', [
            'pageTitle' => '403 — Akses Dilarang | Desadroid',
            'metaDescription' => 'Maaf, Anda tidak memiliki izin untuk mengakses direktori atau halaman ini.',
            'metaRobots' => 'noindex, nofollow'
        ]);
    }

    public function serverError(): void
    {
        $this->response->setStatusCode(500);
        $this->render('errors/500', [
            'pageTitle' => '500 — Kendala Sistem Internal | Desadroid',
            'metaDescription' => 'Mohon maaf, server kami mengalami kendala teknis tak terduga.',
            'metaRobots' => 'noindex, nofollow'
        ]);
    }
}
