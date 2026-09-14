<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index(): void
    {
        $services = Service::getAll();
        $serviceFeatures = Service::getDefaultFeatures();
        $canonical = $this->request->getScheme() . '://' . $this->request->getHost() . $this->request->getBaseDir() . '/layanan';

        $this->render('services/index', [
            'pageTitle' => 'Layanan IT & Jasa Pembuatan Web Bogor — Desadroid',
            'metaDescription' => 'Solusi teknologi terpadu dari Desadroid di Bogor: Jasa Pembuatan Website, Aplikasi Mobile, UI/UX Design, Arsitektur Sistem, dan Konsultasi IT Profesional.',
            'metaKeywords' => 'layanan it bogor, jasa pembuatan web bogor, it consultant bogor, bikin website bogor, developer aplikasi bogor, web developer bogor',
            'metaImage' => 'src/img/DESADROID.jpg',
            'canonical' => $canonical,
            'services' => $services,
            'serviceFeatures' => $serviceFeatures
        ]);
    }
}
