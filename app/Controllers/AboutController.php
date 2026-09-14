<?php
namespace App\Controllers;

use App\Core\Controller;

class AboutController extends Controller
{
    public function index(): void
    {
        $canonical = $this->request->getScheme() . '://' . $this->request->getHost() . $this->request->getBaseDir() . '/tentang';

        $this->render('about/index', [
            'pageTitle' => 'Tentang Kami — Desadroid IT Consultant & Web Studio Bogor',
            'metaDescription' => 'Ketahui profil Desadroid, konsultan IT dan mitra pembuatan website modern terpercaya di Bogor. Berkomitmen menghadirkan solusi teknologi scalable sejak 2025.',
            'metaKeywords' => 'tentang desadroid, it consultant bogor, jasa pembuatan web bogor, konsultan it bogor, profil desadroid, agensi web bogor',
            'metaImage' => 'src/img/DESADROID.jpg',
            'canonical' => $canonical
        ]);
    }
}
